<?php

namespace App\Http\Controllers;

use App\Models\Invite;
use App\Models\Status;

class TestController extends Controller
{
    public function index()
    {
        $statuse = Status::modelType(Invite::class)->latest()->get()->pluck('name');

        dd($statuse);
    }
}
