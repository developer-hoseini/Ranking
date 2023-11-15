<?php

namespace App\Livewire\Pages\Tournaments\Me;

use Illuminate\Http\Request;
use Livewire\Component;

class Index extends Component
{
    public $type = 'participated';

    public function mount(Request $request)
    {
        $type = $request->get('type');

        if ($type) {
            if (in_array($type, ['participated', 'created'])) {
                $this->type = $type;
            }
        }
    }

    public function render()
    {
        return view('livewire.pages.tournaments.me.index');
    }
}
