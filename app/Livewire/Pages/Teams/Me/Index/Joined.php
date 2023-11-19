<?php

namespace App\Livewire\Pages\Teams\Me\Index;

use App\Models\Team;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Joined extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    #[Computed]
    public function teams()
    {
        $query = Team::query()
            ->whereHas('users', fn ($q) => $q->authScope())
            ->withSum('teamScoreAchievements', 'count')
            ->withCount('users')
            ->latest();

        return $query->paginate(config('ranking.settings.global.per_page'));

    }

    public function render()
    {
        return view('livewire.pages.teams.me.index.joined');
    }
}
