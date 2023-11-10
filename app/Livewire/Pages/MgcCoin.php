<?php

namespace App\Livewire\Pages;

use App\Enums\CoinRequestTypeEnum;
use Illuminate\Http\Request;
use Livewire\Component;

class MgcCoin extends Component
{
    public $type = 'buy';

    public function mount(Request $request)
    {
        $type = CoinRequestTypeEnum::tryFrom($request->get('type'))?->value;

        if ($type) {
            $this->type = $type;
        }
    }

    public function render()
    {
        return view('livewire.pages.mgc-coin');
    }
}
