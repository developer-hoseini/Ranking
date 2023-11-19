<?php

namespace App\Livewire\Pages\Teams\Me\Show;

use App\Models\Team;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Members extends Component
{
    public $teamId;

    public function mount($id)
    {
        /* TODO: compelete add memeber team */
        $this->teamId = $id;
    }

    #[Computed]
    public function team()
    {
        $team = Team::where('id', $this->teamId)
            ->with(['users', 'capitan'])
            ->first();

        return $team;
    }

    public function render()
    {
        return view('livewire.pages.teams.me.show.members');
    }
}
