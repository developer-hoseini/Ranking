<?php

namespace App\Livewire\Pages\Teams\Me\Index;

use App\Models\Team;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Created extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    #[Computed]
    public function teams()
    {
        $query = Team::query()
            ->authCreatedScope()
            ->withSum('teamScoreAchievements', 'count')
            ->withCount('users');

        return $query->paginate(config('ranking.settings.global.per_page'));

    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.pages.teams.me.index.created');
    }
}
