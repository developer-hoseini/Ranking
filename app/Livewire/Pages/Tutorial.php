<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class Tutorial extends Component
{
    public function render()
    {
        return view('livewire.pages.tutorial')->title(__('words. - ').__('words.Tutorial'));
    }
}
