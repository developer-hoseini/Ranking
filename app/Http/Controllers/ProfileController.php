<?php

namespace App\Http\Controllers;

use App\Enums\ReasonReportEnum;
use App\Enums\StatusEnum;
use App\Models\Game;
use App\Models\GameResult;
use App\Models\Like;
use App\Models\Report;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        $user->load([
            'profile.state.country',
        ])->loadSum('coinAchievements', 'count')
            ->loadCount('likes');

        if (auth()->check()) {
            $user->loadCount([
                'likes as is_like' => fn ($q) => $q->where('liked_by_user_id', auth()->id()),
            ]);
        }
        //        $daysAgo = Carbon::now()->subDays(config('setting.days_ago'));

        $statues = Status::select(['id', 'name'])
            ->where('model_type', GameResult::class)
            ->get();

        $userGames = Game::query()
            ->whereHas('gameCompetitionsUsers', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->withCount([
                'gameCompetitionsGameResults as win_count' => function ($query) use ($user, $statues) {
                    $query->where('playerable_type', User::class)
                        ->where('playerable_id', $user->id)
                        ->where('game_results.game_result_status_id', $statues->where('name', StatusEnum::GAME_RESULT_WIN->value)->first()?->id);
                },
                'gameCompetitionsGameResults as win_absent' => function ($query) use ($user, $statues) {
                    $query->where('playerable_type', User::class)
                        ->where('playerable_id', $user->id)
                        ->where('game_results.game_result_status_id', $statues->where('name', StatusEnum::GAME_RESULT_ABSENT->value)->first()?->id);
                },
                'gameCompetitionsGameResults as lose_count' => function ($query) use ($user, $statues) {
                    $query->where('playerable_type', User::class)
                        ->where('playerable_id', $user->id)
                        ->where('game_results.game_result_status_id', $statues->where('name', StatusEnum::GAME_RESULT_LOSE->value)->first()?->id);
                },
            ])
            ->withSum([
                'gameCompetitionsScoreOccurredModel' => function ($query) use ($user) {
                    $query->where('achievementable_type', User::class)
                        ->where('achievementable_id', $user->id);
                },
            ], 'count')
            ->orderByDesc('game_competitions_score_occurred_model_sum_count')
            ->get();

        // Certificates
        //        $certificates = Certificate::where(['exported' => $status['Yes'], 'user_id' => $user->id])->with('bracket.competition')->get();

        return view('profile.show', compact('user', 'userGames'));

    }

    public function like(Request $request): bool
    {
        if ($request->ajax() && Auth::check() && $request->has('liked_id')) {
            $likedId = $request->get('liked_id');

            $likedUser = User::find($likedId);

            if (! $likedUser) {
                return false;
            }

            $userLike = Like::where([
                'liked_by_user_id' => Auth::id(),
                'likeable_id' => $likedId,
                'likeable_type' => User::class,
            ])->get();

            if ($userLike->count() === 0) {
                $likedUser->likes()->create([
                    'liked_by_user_id' => Auth::id(),
                ]);
            } else {
                $likedUser->likes()
                    ->where('liked_by_user_id', Auth::id())
                    ->delete();
            }

            return true;

        }

        return false;
    }

    public function report(Request $request)
    {
        if ($request->ajax() && Auth::check() && $request->has('user_id')) {

            $userId = $request->get('user_id');

            $report = Report::where([
                'reporter_id' => Auth::id(),
                'reported_id' => $userId,
                'status_id' => Status::where('name', StatusEnum::PENDING->value)->first()?->id,
            ])->get();

            if ($report->count() === 0) {
                Report::create([
                    'reporter_id' => Auth::id(),
                    'reported_id' => $userId,
                    'status_id' => Status::where('name', StatusEnum::PENDING->value)->first()?->id,
                    'reason' => ReasonReportEnum::PROFILE_PHOTO->value,
                ]);
            }
        }
    }
}
