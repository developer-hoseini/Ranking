<?php

namespace App\Livewire\Pages;

use App\Enums\AchievementTypeEnum;
use App\Models\Achievement;
use App\Models\Game;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Ranks extends Component
{
    public $game = [
        'id' => '',
        'name' => '',
        'game_competitions_users_count' => '',
    ];

    public $filters = [
        'rank' => '',
    ];

    #[Computed]
    public function games()
    {
        $items = Game::orderBy('sort')
            ->select(['id', 'name'])
            ->withCount([
                'gameCompetitionsUsers' => function ($query) {
                    $query->groupBy('competitionable_type');
                },
            ])
            ->get();

        if (! $this->game['id']) {
            $this->game = $items->first()?->toArray();
        }

        return $items;
    }

    #[Computed]
    public function rankUsers()
    {
        $game = Game::query()
            ->where('id', $this->game['id'])
            ->whereHas('competitions', function ($q) {
                $q->has('gameResults');
            })
            ->with([
                'gameCompetitionsUsers' => function ($q) {
                    $q->orderByDesc(
                        Achievement::selectRaw('sum(count) as total_scores')
                            ->where('achievementable_type', User::class)
                            ->where('type', AchievementTypeEnum::SCORE->value)
                            ->whereColumn('achievementable_id', 'users.id')
                            ->groupBy('achievementable_id')
                    )
                        ->withSum('scoreAchievements', 'count')
                        ->withSum('coinAchievements', 'count');
                },
                'gameCompetitionsUsers.media',
                'gameCompetitionsUsers.profile',
            ])
            ->first();

        if ($this->filters['rank']) {
            $ranks = $game?->gameCompetitionsUsers->values()->get($this->filters['rank'] - 1);

            return $ranks ? [$ranks] : [];
        }

        return $game?->gameCompetitionsUsers ?? [];
    }

    public function changeGame()
    {
        $game = $this->games->where('id', $this->game['id'])->first();

        if ($game) {
            $this->game = $game->toArray();
            $this->filters['rank'] = '';
        }

    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.pages.ranks')->title(__('words. - ').__('words.Ranks'));
    }
}
