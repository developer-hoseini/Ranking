<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {

        $unconfirmed_quick_submitted = 0;
        $team_invites_count = 0;
        $support_new_ticket = 0;
        $tournament_invite = 0;

        $auth_user = auth()?->user();

        return view('home', compact(
            'unconfirmed_quick_submitted', 'team_invites_count',
            'support_new_ticket', 'tournament_invite',
            'auth_user'
        ));
    }
}
