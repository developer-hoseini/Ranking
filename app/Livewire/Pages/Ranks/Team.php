<?php

namespace App\Livewire\Pages\Ranks;

use Livewire\Component;
use Request;

class Team extends Component
{
    public function mount(Request $request)
    {
        /*
         TODO: add filter[id] for show rank of one special team 'filter[id]'
        this add in route /teams/me

        */
    }

    public function render()
    {
        return view('livewire.pages.ranks.team');
    }
}
