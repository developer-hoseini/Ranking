<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class Rules extends Component
{
    public function render()
    {
        return view('livewire.pages.rules')->title(__('words. - ').__('words.Rules'));
    }
}
