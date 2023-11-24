<?php

namespace App\Http\Controllers;

use App\Enums\StatusEnum;
use App\Http\Requests\GamePageInviteRequest;
use App\Models\Event;
use App\Models\Game;
use App\Models\GameType;
use App\Models\Invite;
use App\Models\Status;
use App\Models\User;
use App\Notifications\Achievement\Invite\AcceptInviteNotification;
use App\Notifications\Achievement\Invite\CancelInviteNotification;
use App\Notifications\Achievement\Invite\ReceivedInviteNotification;
use App\Notifications\Achievement\Invite\RejectInviteNotification;
use Auth;
use DB;
use Illuminate\Validation\Rules\In;
use Image;

class GamePageController extends Controller
{
    public function index(Game $game, User $opponent = null)
    {
        $game->loadMissing(['gameJoinUserAchievements' => fn ($q) => $q->where('achievementable_id', auth()->id())
            ->where('achievementable_type', User::class)->where('count', 1),
        ]);

        if (! $game->gameJoinUserAchievements->count()) {
            return redirect()->route('games.index');
        }

        // cant invite yourself!
        if ($opponent && $opponent?->id === auth()->id()) {
            return redirect()->route('games.index');
        }

        // after 10 plays, must complete his profile
        $invitesCount = Invite::where(function ($query) {
            $query->where('inviter_user_id', auth()?->id())
                ->orWhere('invited_user_id', auth()?->id());
        })->whereHas('gameStatus', fn ($q) => $q->where('name', StatusEnum::FINISHED))
            ->count();

        if ($invitesCount >= config('setting.profile_middleware_count') && empty(auth()->user()?->profile?->bio)) {
            return redirect()->route('profile.complete-profile')->withErrors([
                'message' => __('message.Please complete your profile first'),
            ]);
        }

        /*
        $game_results = DB::table('invite')
            ->join('game_result', 'invite.id', '=', 'game_result.invite_id')
            ->where('game_id', $game_id)->where(
            function ($query) use ($user_id) {
                $query->where('inviter_id', $user_id)->orWhere('invited_id', $user_id);
            })
            ->whereNotIn('status', [$status['Pending'], $status['Rejected'], $status['Canceled']])
            ->orderBy('invite.id', 'desc')->get();
*/

        //        $setting = config('setting');

        //        $game = \App\Game::withCount(['scores', 'invites', 'in_club', 'with_image'])->where('id', $game_id)->first();

        //$score = \App\User_Score::where(['user_id'=>$user_id, 'game_id'=>$game_id])->first();

        /* $inclub_stars = \App\Invite::inclub_stars($user_id, $game_id, $status, $setting);
         $image_stars = \App\Invite::image_stars($user_id, $game_id, $status, $setting);

         $team_played_stars = \App\Team_Played_User::whereHas('team', function ($query) use ($game_id) {
             $query->where('game_id', $game_id);
         })->where([
             ['user_id', '=', $user_id],
             ['dt', '>=', $setting['days_ago']],
         ])->count();*/

        //Warnings
        /*$false_result = \App\Event::where(['user_id' => $user_id, 'type' => config('event.Warning'), 'reason' => config('reason.False_Result')])->count();
        $no_submit = \App\Event::where(['user_id' => $user_id, 'type' => config('event.Warning'), 'reason' => config('reason.No_Submit')])->count();
        $you_absent = \App\Event::where(['user_id' => $user_id, 'type' => config('event.Warning'), 'reason' => config('reason.You_Absent')])->count();
        $false_image = \App\Event::where(['user_id' => $user_id, 'type' => config('event.Warning'), 'reason' => config('reason.False_Image')])->count();
        $false_club = \App\Event::where(['user_id' => $user_id, 'type' => config('event.Warning'), 'reason' => config('reason.False_Club')])->count();*/
        /*


                $no_submit_results_count = \App\Invite::whereHas('no_submit_result')->whereRaw('game_id=? and (inviter_id=? or invited_id=?)', [$game_id, $user_id, $user_id])->count();
                $one_submit_results_count = \App\Invite::whereHas('one_submit_result')->whereRaw('game_id=? and (inviter_id=? or invited_id=?)', [$game_id, $user_id, $user_id])->count();

                if ($opponent_id) {
                    $user_score = \App\User_Score::where(
                        ['user_id' => $opponent_id, 'game_id' => $game_id, 'is_join' => $status['Yes']]
                    )->first();

                    if ($user_score) {
                        $opponent = \App\User::with(['scores'])->where('id', $opponent_id)->first();
                    } else {
                        $opponent = null;
                    }
                } else {
                    $opponent = null;
                }*/
        /*[
            'game' => $game,
            'opponent' => $opponent,
            /* 'score' => $score,
            'inclub_stars' => $inclub_stars,
            'image_stars' => $image_stars,
            'team_played_stars' => $team_played_stars,
            'false_result' => $false_result,
            'no_submit' => $no_submit,
            'you_absent' => $you_absent,
            'false_image' => $false_image,
            'false_club' => $false_club,
            'sent' => $sent,
            'received' => $received,
            'game_results' => $game_results,
            'no_submit_results_count' => $no_submit_results_count,
            'one_submit_results_count' => $one_submit_results_count,
        ];*/

        return view('games.page.index', compact('game', 'opponent'));
    }

    /**
     * @throws \Throwable
     */
    public function accept(int $inviteId): ?\Illuminate\Http\RedirectResponse
    {
        $invite = Invite::where([
            'id' => $inviteId,
            'invited_user_id' => Auth::user()->id,
        ])->whereHas('confirmStatus', fn ($q) => $q->where('name', StatusEnum::PENDING->value))
            ->with(['inviterUser', 'invitedUser', 'game'])
            ->first();

        // yes, really received the invite
        if ($invite) {
            DB::beginTransaction();

            try {
                $invite->confirm_status_id = Status::where('name', StatusEnum::ACCEPTED->value)->first()?->id;
                $invite->save();

                $statusPendingId = Status::where('name', StatusEnum::PENDING->value)->first()?->id;
                $statusAcceptId = Status::where('name', StatusEnum::ACCEPTED->value)->first()?->id;

                $invite->competitions()->create([
                    'name' => "invite - {$invite->invitedUser?->username} vs {$invite->inviterUser?->username}",
                    'capacity' => 2,
                    'game_id' => $invite->game_id,
                    'status_id' => $statusPendingId,
                    'start_at' => now(),
                ]);

                $invite->competitions->first()->gameResults()->createMany([
                    [
                        'playerable_id' => $invite->invited_user_id,
                        'playerable_type' => User::class,
                        'game_result_status_id' => $statusPendingId,
                        'user_status_id' => $statusAcceptId,
                    ],
                    [
                        'playerable_id' => $invite->inviter_user_id,
                        'playerable_type' => User::class,
                        'game_result_status_id' => $statusPendingId,
                        'user_status_id' => $statusAcceptId,
                    ],
                ]);

                $invite->inviterUser->notify(new AcceptInviteNotification($invite));

                DB::commit();

            } catch (\Throwable $e) {
                DB::rollBack();

                throw $e;
            }

            return redirect()->back()->with('success', __('message.invite_accepted'));

        }

        return redirect()->back()->withErrors([
            'message' => __('message.you_already_accepted'),
        ]);
    }

    public function reject(int $inviteId): ?\Illuminate\Http\RedirectResponse
    {
        $invite = Invite::where([
            'id' => $inviteId,
            'invited_user_id' => Auth::user()->id,
        ])->whereHas('confirmStatus', fn ($q) => $q->where('name', StatusEnum::PENDING->value))
            ->first();

        // yes, really received the invite
        if ($invite) {
            $invite->confirm_status_id = Status::where('name', StatusEnum::REJECTED->value)->first()?->id;
            $invite->save();

            $invite->inviterUser->notify(new RejectInviteNotification($invite));

            return redirect()->back()->with('success', __('message.invite_rejected'));

        }

        return redirect()->back()->withErrors([
            'message' => __('message.you_already_rejected'),
        ]);
    }

    public function cancel(int $inviteId): ?\Illuminate\Http\RedirectResponse
    {
        $invite = Invite::where([
            'id' => $inviteId,
            'inviter_user_id' => Auth::user()->id,
        ])->whereHas('confirmStatus', fn ($q) => $q->where('name', StatusEnum::PENDING->value))
            ->first();

        // yes, really received the invite
        if ($invite) {
            $invite->confirm_status_id = Status::where('name', StatusEnum::CANCELED->value)->first()?->id;
            $invite->save();

            $invite->invitedUser->notify(new CancelInviteNotification($invite));

            return redirect()->back()->with('success', __('message.invite_canceled'));
        }

        return redirect()->back()->withErrors(
            [
                'message' => __('message.you_already_canceled'),
            ]
        );
    }

    public function invite(GamePageInviteRequest $request, Game $game): \Illuminate\Http\RedirectResponse
    {
        $gameType = [];

        if ($request->input('in_club', false)) {
            $gameType[] = 'in_club';
        }

        if ($request->input('with_image', false)) {
            $gameType[] = 'with_image';
        }

        $invite = Invite::create([
            'inviter_user_id' => auth()->id(),
            'invited_user_id' => $request->input('userId'),
            'game_id' => $game->id,
            'club_id' => $request->input('club'),
            'confirm_status_id' => Status::where('name', StatusEnum::PENDING->value)->first('id')?->id,
        ]);

        if (count($gameType) > 0) {
            $gameTypes = GameType::select('id')
                ->whereIn('name', $gameType)
                ->pluck('id');

            $invite->gameType()->attach($gameTypes->toArray());
        }

        User::where('id', $request->input('userId'))->first()?->notify(new ReceivedInviteNotification($invite, $game));

        return redirect()->route('games.page.index', ['game' => $game->id])
            ->with('success', __('message.invite_sent_successfully', ['username' => $request->input('username')]));
    }
}
