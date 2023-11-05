<?php

namespace App\Livewire\Pages\Tournaments;

use App\Models\Cup;
use Livewire\Component;

class BracketTournaments extends Component
{
    #[\Livewire\Attributes\Computed]
    public function cups()
    {
        $cups = Cup::query()
            ->withWhereHas('competitions')
            ->latest()
            ->take(10)
            ->get();

        return $cups ?? [];
    }

    public function render()
    {
        return view('livewire.pages.tournaments.bracket-tournaments');
    }
}
