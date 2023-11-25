<?php

namespace App\Http\Controllers;

use App\Enums\StatusEnum;
use App\Http\Requests\GamePageInviteRequest;
use App\Models\Game;
use App\Models\GameType;
use App\Models\Invite;
use App\Models\Status;
use App\Models\User;
use App\Notifications\Achievement\Invite\AcceptInviteNotification;
use App\Notifications\Achievement\Invite\CancelInviteNotification;
use App\Notifications\Achievement\Invite\ReceivedInviteNotification;
use App\Notifications\Achievement\Invite\RejectInviteNotification;
use App\Services\Actions\User\GetCountryRank;
use App\Services\Actions\User\GetGameRank;
use Auth;
use DB;

class GamePageController extends Controller
{
    public function index(Game $game, User $opponent = null)
    {
        $game->loadMissing(['gameJoinUserAchievements' => fn ($q) => $q->where('achievementable_id', auth()->id())
            ->where('achievementable_type', User::class)->where('count', 1),
            'gameCompetitionsUsers' => fn ($q) => $q->groupBy(['competitions.game_id']),
        ])->loadCount([
            'competitions' => fn ($q) => $q->has('gameResults'),
            'gameTypes as in_club_count' => fn ($q) => $q->where('name', 'in_club'),
            'gameTypes as with_image_count' => fn ($q) => $q->where('name', 'with_image'),
        ]);

        $users = ($game->gameCompetitionsUsers ?? collect([]))?->union($game->gameCompetitionsTeamsUsers);
        $game['users_count'] = $users?->count() ?? 0;

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
        })->whereHas('gameStatus', fn ($q) => $q->where('name', StatusEnum::FINISHED->value))
            ->count();

        if ($invitesCount >= config('setting.profile_middleware_count') && empty(auth()->user()?->profile?->bio)) {
            return redirect()->route('profile.complete-profile')->withErrors([
                'message' => __('message.Please complete your profile first'),
            ]);
        }

        $user = Auth::user()?->loadSum([
            'userScoreAchievements' => function ($query) use ($game) {
                $query->whereHas('achievementCompetition', fn ($q) => $q->where('game_id', $game->id));
            }], 'count')
            ->loadSum(['userCoinAchievements' => function ($query) use ($game) {
                $query->whereHas('achievementCompetition', fn ($q) => $q->where('game_id', $game->id));
            }], 'count');

        $gameResult = $user->gameResults()
            ->whereHas('gameResultAdminStatus', fn ($q) => $q->where('statuses.name', StatusEnum::ACCEPTED->value))
            ->whereHas('gameresultableCompetition', fn ($q) => $q->where('competitions.game_id', $game->id))
            ->with([
                'gameResultStatus',
                'gameResultCompetitionInviteGameType',
            ])->get();

        $gameResultCompetitionInviteGameType = $gameResult->pluck('gameResultCompetitionInviteGameType')->flatten();
        $gameResultStatus = $gameResult->pluck('gameResultStatus');
        $score = [
            'in_club' => $gameResultCompetitionInviteGameType->where('name', 'in_club')->count(),
            'with_image' => $gameResultCompetitionInviteGameType->where('name', 'with_image')->count(),
            'win' => $gameResultStatus->where('name', StatusEnum::GAME_RESULT_WIN->value)->count(),
            'lose' => $gameResultStatus->where('name', StatusEnum::GAME_RESULT_LOSE->value)->count(),
            'absent' => $gameResultStatus->whereIn('name', [StatusEnum::GAME_RESULT_I_ABSENT->value, StatusEnum::GAME_RESULT_HE_ABSENT->value])->count(),
            'total' => $gameResultStatus->count(),
        ];

        $user->rank = GetGameRank::handle($user->id, $game?->id);
        $user->country_rank = GetCountryRank::handle($user->id, $user?->profile?->state?->country_id, $game?->id);

        return view('games.page.index', compact('game', 'opponent', 'user', 'score'));
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
