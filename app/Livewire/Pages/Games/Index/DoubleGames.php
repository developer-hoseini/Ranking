<?php

namespace App\Livewire\Pages\Games\Index;

use App\Models\Game;
use Livewire\Attributes\Computed;
use Livewire\Component;

class DoubleGames extends Component
{
    #[Computed]
    public function games()
    {
        $games = Game::query()
            ->active()
            ->gameTypeScope('two player')
            ->select(['id', 'name'])
            ->with([
                'gameCompetitionsUsers' => fn ($q) => $q->groupBy(['competitions.game_id']),
                'gameCompetitionsTeams' => fn ($q) => $q->groupBy(['competitions.game_id']),
                'gameCompetitionsTeams.users',
            ])
            ->withCount([
                'competitions' => fn ($q) => $q->has('gameResults'),
            ])
            ->orderBy('sort')
            ->get();

        $games = $games->map(function ($game) {
            $competitionUsers = $game->gameCompetitionsUsers ?? collect([]);
            $users = $competitionUsers?->union($game->gameCompetitionsTeamsUsers);
            $game['users_count'] = $users?->count() ?? 0;

            return $game;
        });

        return $games;
    }

    public function render()
    {
        $this->games();

        return view('livewire.pages.games.index.double-games');
    }
}
