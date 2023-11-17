<?php

namespace App\Livewire\Pages\Games\Index;

use App\Models\Game;
use Livewire\Attributes\Computed;
use Livewire\Component;

class OnlineGames extends Component
{
    #[Computed]
    public function games()
    {
        $games = Game::query()
            ->active()
            ->withWhereHas('onlineGames')
            ->gameTypesScope(['online'], true)
            ->select(['id', 'name'])
            ->with([
                'gameTypes',
            ])
            ->orderBy('sort')
            ->get();

        $refactorGames = [
            'one-player' => [],
            'two-player' => [],
            'multiplayer' => [],
        ];

        foreach ($games as $game) {

            if ($game->gameTypes->where('name', 'one player')->first()) {
                $refactorGames['one-player'][] = $game;
            }
            if ($game->gameTypes->where('name', 'two player')->first()) {
                $refactorGames['two-player'][] = $game;
            }
            if ($game->gameTypes->where('name', 'multiplayer')->first()) {
                $refactorGames['multiplayer'][] = $game;
            }
        }

        return $refactorGames;
    }

    public function render()
    {
        return view('livewire.pages.games.index.online-games');
    }
}
