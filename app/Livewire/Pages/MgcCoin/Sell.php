<?php

namespace App\Livewire\Pages\MgcCoin;

use App\Enums\CoinRequestTypeEnum;
use App\Enums\StatusEnum;
use App\Models\CoinRequest;
use App\Models\Status;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Sell extends Component
{
    #[Rule([
        'form.wallet_address' => 'required|max:255',
    ])]
    public array $form = [
        'wallet_address' => '',
        'count' => '',
    ];

    public function submitForm()
    {
        $this->validate();

        $maxCoinCanSell = auth()->user()->sumCoinAchievements;
        $this->validate(['form.count' => ['required', 'numeric', 'min:1', 'max:'.$maxCoinCanSell]]);

        CoinRequest::create([
            'wallet_address' => $this->form['wallet_address'],
            'count' => $this->form['count'],
            'type' => CoinRequestTypeEnum::SELL->value,
            'created_by_user_id' => auth()->id(),
            'status_id' => Status::nameScope(StatusEnum::PENDING->value)->first()?->id,
        ]);

        session()->flash('success', "your {$this->form['count']} MGC coin request is submited successfully. please wait to confirm that by admin");

        $this->reset();

        $this->redirect(route('mgc-coin.index', ['type' => CoinRequestTypeEnum::SELL->value]));
    }

    public function render()
    {
        return view('livewire.pages.mgc-coin.sell');
    }
}
