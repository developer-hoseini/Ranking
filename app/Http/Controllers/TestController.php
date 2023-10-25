<?php

namespace App\Http\Controllers;

use App\Models\GameResult;
use App\Models\Invite;
use App\Models\Status;
use App\Models\User;

class TestController extends Controller
{
    public function index()
    {

        $a = GameResult::where('id', 1)->with('gameResultStatus')->get();

        return $a;
        dd($a);

        $user = User::first();

        $media = $user->getMedia('avatar')->first();

        dd($media, $media->getPath(), $media->getUrl());
        $statuse = Status::modelType(Invite::class)->latest()->get()->pluck('name');

        dd($statuse);
    }
}
