<?php

namespace App\Http\Controllers;

use App\Models\Gallery;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Gallery::typeSlider()->orderBy('sort')->active()->get();

        $events_count = 0;
        session(
            [
                'photo' => 'assets/img/menu/user-dark.png',
            ]
        );

        $chats_count = 0;
        $unconfirmed_quick_submitted = 0;
        $team_invites_count = 0;
        $support_new_ticket = 0;
        $tournament_invite = 0;

        $auth_user = auth()?->user();

        return view('home', compact('sliders', 'events_count',
            'chats_count', 'unconfirmed_quick_submitted', 'team_invites_count',
            'support_new_ticket', 'tournament_invite',
            'auth_user'
        ));
    }
}
