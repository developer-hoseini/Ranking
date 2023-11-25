<?php

namespace App\Http\Controllers;

use App\Enums\AchievementTypeEnum;
use App\Enums\StatusEnum;
use App\Models\Achievement;
use App\Models\Competition;
use App\Models\Game;
use App\Models\Status;
use App\Models\User;
use App\Notifications\Achievement\Game\JoinNotification;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function show(Game $game)
    {

        if (! $game->active) {
            return redirect()->route('page.home');
        }

        $game->loadCount(['invites']);

        $users = User::query()
            ->withSum([
                'userScoreAchievements' => function ($query) use ($game) {
                    $query->whereHas('achievementCompetition', fn ($q) => $q->where('game_id', $game->id));
                },
            ], 'count')
            ->withSum('userCoinAchievements', 'count')
            ->withCount('likes')
            ->whereHas('competitions', function ($query) use ($game) {
                $query->where('game_id', $game->id);
            })
            ->with([
                'profile',
                'media',
            ])
            ->orderByDesc(
                Achievement::selectRaw('sum(count) as total_scores')
                    ->where('achievementable_type', User::class)
                    ->where('type', AchievementTypeEnum::SCORE->value)
                    ->whereColumn('achievementable_id', 'users.id')
                    ->whereHas('achievementCompetition', fn ($q) => $q->where('game_id', $game->id))
                    ->groupBy('achievementable_id')
            )
            ->paginate(config('setting.gameinfo_list'));

        return view('games.show', ['game' => $game, 'users' => $users]);
    }

    public function showOnline($id)
    {
        $game = Game::query()
            ->where('id', $id)
            ->active()
            ->withWhereHas('onlineGames')
            ->select(['id', 'name'])
            ->orderBy('sort')
            ->firstOrFail();

        return view('games.show-online', compact('game'));
    }

    public function joinStatusGame(Game $game, bool $type): \Illuminate\Http\RedirectResponse
    {
        $game->loadMissing(['gameJoinUserAchievements' => fn ($q) => $q->where('achievementable_id', auth()->id())->where('achievementable_type', User::class)]);

        \DB::beginTransaction();
        if ($game->gameJoinUserAchievements->count()) {
            $game->gameJoinUserAchievements()->update([
                'count' => $type,
            ]);
        } else {
            $user = Auth::user()?->load('profile');
            $profile = $user->profile;
            $acceptStatusId = Status::nameScope(StatusEnum::ACCEPTED->value)->first()?->id;
            $competition = Competition::create([
                'name' => "{$game->name} - join {$user->avatar_name}",
                'capacity' => 1,
                'description' => "Join user to #{$game->id}",
                'game_id' => $game->id,
                'state_id' => $profile->state_id,
                'status_id' => $acceptStatusId,
            ]);
            $user->competitions()->attach([
                $competition->id => [
                    'status_id' => $acceptStatusId,
                ],
            ]);
            $user->achievements()->createMany([
                [
                    'occurred_model_id' => $game->id,
                    'occurred_model_type' => Game::class,
                    'type' => AchievementTypeEnum::JOIN->value,
                    'count' => $type,
                    'created_by_user_id' => auth()->id(),
                ],
                [
                    'occurred_model_id' => $competition->id,
                    'occurred_model_type' => Competition::class,
                    'type' => AchievementTypeEnum::SCORE->value,
                    'count' => 100,
                    'status_id' => $acceptStatusId,
                    'created_by_user_id' => auth()->id(),
                ],
            ]);
            if ($type) {
                auth()->user()?->notify(new JoinNotification($game));
            }
        }

        \DB::commit();
        if ($type) {

            return redirect()->route('games.page.index', ['game' => $game->id])
                ->with('success', __('message.you_joined_successfully', ['game_name' => $game->name]));
        }

        return redirect()->back()
            ->with('success', __('message.you_left', ['game_name' => $game->name]));

    }
}
