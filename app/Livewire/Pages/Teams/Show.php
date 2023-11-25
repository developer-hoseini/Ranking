<?php

namespace App\Livewire\Pages\Teams;

use App\Models\Team;
use Livewire\Component;

class Show extends Component
{
    public $team;

    public function mount(Team $team)
    {
        if ($team->capitan_user_id !== auth()->id()) {
            return redirect()->route('teams.me.index');
        }
        /* TODO: complete this page */
        $this->team = $team;

    }

    public function render()
    {
        return view('livewire.pages.teams.show');
    }
}
