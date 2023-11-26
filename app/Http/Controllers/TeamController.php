<?php

namespace App\Http\Controllers;

use App\Enums\StatusEnum;
use App\Http\Requests\TeamInviteRequest;
use App\Models\GameType;
use App\Models\Invite;
use App\Models\Status;
use App\Models\Team;
use App\Notifications\Achievement\Invite\ReceivedInviteNotification;

class TeamController extends Controller
{
    public function show(Team $team)
    {
        if ($team->capitan_user_id !== auth()->id()) {
            return redirect()->route('teams.me.index');
        }

        return view('team.index', compact('team'));
    }

    public function invite(TeamInviteRequest $request, Team $team): \Illuminate\Http\RedirectResponse
    {
        $gameType = [];

        if ($request->input('in_club', false)) {
            $gameType[] = 'in_club';
        }

        if ($request->input('with_image', false)) {
            $gameType[] = 'with_image';
        }

        $invite = Invite::create([
            'inviter_user_id' => $team->id,
            'invited_user_id' => $request->input('teamId'),
            'game_id' => $team->game_id,
            'club_id' => $request->input('club'),
            'confirm_status_id' => Status::where('name', StatusEnum::PENDING->value)->first('id')?->id,
        ]);

        if (count($gameType) > 0) {
            $gameTypes = GameType::select('id')
                ->whereIn('name', $gameType)
                ->pluck('id');

            $invite->gameType()->attach($gameTypes->toArray());
        }

        Team::where('id', $request->input('teamId'))->first()
            ?->capitan?->notify(new ReceivedInviteNotification($invite, $game));

        return redirect()->route('teams.show', ['team' => $team->id])
            ->with('success', __('message.team_invite_sent_successfully', ['teamname' => $request->input('username')]));
    }

    public function profile(Team $team)
    {
        dd($team);
    }
}
