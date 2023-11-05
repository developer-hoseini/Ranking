<?php

namespace App\Http\Controllers;

use App\Models\Like;
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

    public function like(Request $request)
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
        if ($request->ajax() && Auth::check()) {
            $user_id = $request->get('user_id');

            $report = \App\Report::where([
                'reporter_id' => Auth::user()->id,
                'reported_id' => $user_id,
                'status' => config('status.Pending'),
            ])->get();

            if ($report->count() == 0) {
                \App\Report::create([
                    'reporter_id' => Auth::user()->id,
                    'reported_id' => $user_id,
                    'reason' => config('reason.Profile_Photo'),
                    'dt' => date('Y-m-d H:i:s', time()),
                    'status' => config('status.Pending'),
                ]);
            }
        }
    }
}
