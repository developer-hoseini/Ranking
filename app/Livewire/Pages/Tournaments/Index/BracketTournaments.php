<?php

namespace App\Livewire\Pages\Tournaments\Index;

use App\Models\Cup;
use Livewire\Component;

class BracketTournaments extends Component
{
    #[\Livewire\Attributes\Computed]
    public function cups()
    {
        $cups = Cup::query()
            ->cupAcceptedStatusScope()
            ->withWhereHas('competitions')
            ->latest()
            ->take(10)
            ->get();

        return $cups ?? [];
    }

    public function render()
    {
        return view('livewire.pages.tournaments.index.bracket-tournaments');
    }
}
