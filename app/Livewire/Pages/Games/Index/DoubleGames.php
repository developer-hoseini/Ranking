<?php

namespace App\Livewire\Pages\Games\Index;

use App\Models\Game;
use App\Models\User;
use App\Services\Actions\User\GetGameRank;
use Livewire\Attributes\Computed;
use Livewire\Component;

class DoubleGames extends Component
{
    #[Computed]
    public function games()
    {
        $games = Game::query()
            ->active()
            ->gameTypesScope(['two player'], true)
            ->select(['id', 'name'])
            ->with([
                'gameCompetitionsUsers' => fn ($q) => $q->groupBy(['competitions.game_id']),
                'gameCompetitionsTeams' => fn ($q) => $q->groupBy(['competitions.game_id']),
                'gameCompetitionsTeams.users',
            ])
            ->withCount([
                'competitions' => fn ($q) => $q->has('gameResults'),
                'gameCompetitionsUsers' => fn ($q) => $q->where('users.id', auth()->id()),
            ])
            ->withSum([
                'gameCompetitionsScoreOccurredModel' => function ($query) {
                    $query->where('achievementable_type', User::class)
                        ->where('achievementable_id', auth()->id());
                },
            ], 'count')
            ->orderBy('sort')
            ->get();

        return $games->map(function ($game) {
            $users = ($game->gameCompetitionsUsers ?? collect([]))?->union($game->gameCompetitionsTeamsUsers);
            $game['users_count'] = $users?->count() ?? 0;

            $game['user_rank'] = $game->game_competitions_users_count ? GetGameRank::handle(auth()->id(), $game?->id) : '-';
            $game['user_score'] = $game->game_competitions_users_count ? $game->game_competitions_score_occurred_model_sum_count : '-';

            return $game;
        });
    }

    public function render()
    {
        $this->games();

        return view('livewire.pages.games.index.double-games');
    }
}
