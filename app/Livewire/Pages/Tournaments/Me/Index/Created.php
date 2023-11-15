<?php

namespace App\Livewire\Pages\Tournaments\Me\Index;

use App\Models\Cup;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Created extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    #[Computed]
    public function tournaments()
    {
        $tournaments = Cup::query()
            ->whereHas('createdByUser', fn ($q) => $q->authScope())
            ->withCount('registeredUsers')
            ->with([
                'game:id,name',
                'status:id,name',
                'competitions.gameResults.status',
                'competitions.gameResults.playerable' => function (MorphTo $morphTo) {
                    $morphTo->morphWith([
                        User::class => ['profile', 'media'],
                    ]);
                },
            ])
            ->paginate(config('ranking.settings.global.per_page'));

        return $tournaments;

    }

    public function render()
    {
        return view('livewire.pages.tournaments.me.index.created');
    }
}
