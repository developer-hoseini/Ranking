<?php

namespace App\Http\Controllers;

use App\Models\Cup;

class TestController extends Controller
{
    public function index()
    {
        $cup = Cup::find(39);

        $cup->load('cupCompetitionsGame')->loadCount('cupRegisteredTeamsUsers');

        dd($cup);
    }
}
