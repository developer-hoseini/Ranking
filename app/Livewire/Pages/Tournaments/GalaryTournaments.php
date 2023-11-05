<?php

namespace App\Livewire\Pages\Tournaments;

use App\Models\Competition;
use Livewire\Component;

class GalaryTournaments extends Component
{
    #[\Livewire\Attributes\Computed]
    public function competitions()
    {
        $competitions = Competition::query()
            ->statusTournament()
            ->with([
                'media',
            ])
            ->latest()
            ->take(12)
            ->get();

        return $competitions ?? [];
    }

    public function render()
    {
        return view('livewire.pages.tournaments.galary-tournaments');
    }
}
