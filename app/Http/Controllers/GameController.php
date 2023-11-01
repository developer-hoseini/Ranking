<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;

class GameController extends Controller
{
    public function show(Game $game)
    {

        if (! $game->active) {
            return redirect()->route('home');
        }

        $game->loadCount(['invites']);

        $users = User::query()
            ->whereHas('competitions', function ($query) use ($game) {
                $query->where('game_id', $game->id);
            })
            ->with([
                'profile',
                'competitions' => fn ($q) => $q->where('game_id', $game->id),
                'competitions.game' => fn ($q) => $q->where('id', $game->id),
            ])
            ->orderBy('coin', 'desc')
            ->paginate(config('setting.gameinfo_list'));

        return view('game.show', ['game' => $game, 'users' => $users]);
    }
}
