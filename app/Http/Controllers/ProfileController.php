<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function show(User $user)
    {

        $status = config('status');

        $user->load('profile.state.country')
            ->loadSum('scoreAchievements', 'count')
            ->loadSum('coinAchievements', 'count')
            ->loadCount('likes');

        $isMyProfile = auth()?->id() === $user->id;

        if (auth()->check()) {
            $user->loadCount([
                'likes as is_like' => fn ($q) => $q->where('liked_by_user_id', auth()->id()),
            ]);
        }

        $daysAgo = Carbon::now()->subDays(config('setting.days_ago'));

        // Certificates
        //        $certificates = Certificate::where(['exported' => $status['Yes'], 'user_id' => $user->id])->with('bracket.competition')->get();

        return view('profile.show', [
            'user' => $user,
            'isMyProfile' => $isMyProfile,
            'daysAgo' => $daysAgo,
            //            'certificates' => $certificates,
        ]);

    }
}
