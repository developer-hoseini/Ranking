<?php

namespace App\Http\Controllers;

use App\Models\Cup;

class TestController extends Controller
{
    public function index()
    {

        if (request()->has('userId')) {
            \Auth::login(User::find(request('userId')));

            return \Auth::user()->toArray();
        }

        $game = Game::with('gameCompetitionsUsers')->find(32);
        dd($game->toArray(), $game->gameCompetitionsUsers->where('id', 229)->keys()->first());

        return request()->all();

        $competition = Competition::find(1);
        dd($competition->load('team')->toArray());

        $team = Team::find(1);
        $team->competitions()->attach([1]);

        dd($team->load('competitions')->toArray());

        $a = Cup::where('id', 1)->with('competitions')->get();

        return $a;
        dd($a);

        $user = User::with('profile')->find(3);

        dd($user);

        $media = $user->getMedia('avatar')->first();

        dd($media, $media->getPath(), $media->getUrl());
        $statuse = Status::modelType(Invite::class)->latest()->get()->pluck('name');

        dd($cup);
    }
}
