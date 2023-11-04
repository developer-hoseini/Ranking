<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;

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
}
