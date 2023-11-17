<?php

namespace App\Livewire\Pages\Teams\Me;

use Illuminate\Http\Request;
use Livewire\Component;

class Index extends Component
{
    public $type = 'created';

    public function mount(Request $request)
    {
        /* TODO: it is not complete all pages in teams me please check and add they */
        $type = $request->get('type');

        if ($type) {
            if (in_array($type, ['joined', 'created'])) {
                $this->type = $type;
            }
        }
    }

    public function render()
    {
        return view('livewire.pages.teams.me.index')->title(__('words. - ').__('words.My Teams'));
    }
}
