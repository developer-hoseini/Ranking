<?php

namespace App\Http\Controllers;

use App\Models\Invite;
use App\Models\Status;
use App\Models\User;

class TestController extends Controller
{
    public function index()
    {


        $user = User::with('profile')->find(3);

        dd($user);

        $media = $user->getMedia('avatar')->first();


        dd($media,$media->getPath(),$media->getUrl());
        $statuse = Status::modelType(Invite::class)->latest()->get()->pluck('name');

        dd($statuse);
    }
}
