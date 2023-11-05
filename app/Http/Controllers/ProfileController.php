<?php

namespace App\Http\Controllers;

use App\Enums\ReasonReportEnum;
use App\Enums\StatusEnum;
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
            'scoreAchievements' => fn ($q) => $q->orderBy('count', 'desc'),
            'scoreAchievements.occurredModel.game',
        ])->loadSum('coinAchievements', 'count')
            ->loadCount('likes');

        if (auth()->check()) {
            $user->loadCount([
                'likes as is_like' => fn ($q) => $q->where('liked_by_user_id', auth()->id()),
            ]);
        }

        //        dd($user->toArray(), $user->scoreAchievements->sum('count'));

        //        $scores = \App\User_Score::with(['game'])
        //            ->where(['user_id'=>$user->id, 'is_join'=>config('status.Yes')])->orderBy('score','desc')->get();

        $daysAgo = Carbon::now()->subDays(config('setting.days_ago'));

        // Certificates
        //        $certificates = Certificate::where(['exported' => $status['Yes'], 'user_id' => $user->id])->with('bracket.competition')->get();

        return view('profile.show', compact('user', 'daysAgo'));

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
