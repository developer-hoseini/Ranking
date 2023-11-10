<?php

namespace App\Livewire\Pages\MgcCoin;

use App\Enums\CoinRequestTypeEnum;
use App\Enums\StatusEnum;
use App\Models\CoinRequest;
use App\Models\Status;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Buy extends Component
{
    #[Rule([
        'form.count' => 'required|min:1|max:9000000',
        'form.requested_at' => 'required|date',
    ])]
    public array $form = [
        'count' => '',
        'requested_at' => '',
    ];

    public function submitForm()
    {
        $this->validate();

        CoinRequest::create([
            'count' => $this->form['count'],
            'requested_at' => $this->form['requested_at'],
            'type' => CoinRequestTypeEnum::BUY->value,
            'created_by_user_id' => auth()->id(),
            'status_id' => Status::nameScope(StatusEnum::PENDING->value)->first()?->id,
        ]);

        session()->flash('success', "your {$this->form['count']} MGC coin request is submited successfully. please wait to confirm that by admin");

        $this->reset();

        $this->redirect(route('mgc-coin.index', ['type' => CoinRequestTypeEnum::BUY->value]));
    }

    public function render()
    {
        return view('livewire.pages.mgc-coin.buy');
    }
}
