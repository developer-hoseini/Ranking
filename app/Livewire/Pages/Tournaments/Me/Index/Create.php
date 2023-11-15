<?php

namespace App\Livewire\Pages\Tournaments\Me\Index;

use App\Enums\StatusEnum;
use App\Models\Country;
use App\Models\Cup;
use App\Models\Game;
use App\Models\State;
use App\Models\Status;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Create extends Component
{
    public $countries = [];

    public $states = [];

    #[Rule([
        'form.game_id' => 'required|exists:games,id',
        'form.country_id' => 'required|exists:countries,id',
        'form.state_id' => 'required|exists:states,id',
        'form.name' => 'required|string|min:5|max:255',
        'form.register_cost_coin' => 'required|numeric|min:0,max:999000000',
        'form.capacity' => 'required|numeric|min:2,max:48',
        'form.end_register_at' => 'required|date|after:today',
        'form.start_at' => 'required|date|after_or_equal:form.end_register_at',
        'form.description' => 'nullable|min:5|max:999999999',
    ])]
    public $form = [
        'game_id' => '',
        'country_id' => '',
        'state_id' => '',
        'name' => '',
        'register_cost_coin' => 0,
        'capacity' => 2,
        'end_register_at' => '',
        'start_at' => '',
        'description' => '',
    ];

    public function mount()
    {
        $this->countries = Country::select(['id', 'name'])->get()->toArray();

    }

    public function updatedFormCountryId()
    {
        $this->form['state_id'] = '';
        $this->states = $this->getStates();
    }

    #[Computed]
    public function games()
    {
        return Game::active()->orderBy('sort', 'asc')->select(['id', 'name'])->get();
    }

    public function submitForm()
    {
        $this->validate();

        Cup::create([
            'name' => $this->form['name'],
            'capacity' => $this->form['capacity'],
            'register_cost_coin' => $this->form['register_cost_coin'],
            'description' => $this->form['description'],
            'state_id' => $this->form['state_id'],
            'game_id' => $this->form['game_id'],
            'end_register_at' => $this->form['end_register_at'],
            'start_at' => $this->form['start_at'],
            'status_id' => Status::query()->nameScope(StatusEnum::PENDING->value)->firstOrFail()?->id,
            'created_by_user_id' => auth()->id(),
        ]);

        $this->reset();
        session()->flash('success', 'your tournament created succsesfully, please wait to confirm by admin');

        $this->redirect(route('tournaments.me.index', ['type' => 'created']));

    }

    public function render()
    {
        return view('livewire.pages.tournaments.me.index.create');
    }

    private function getStates()
    {
        return State::query()
            ->select(['id', 'name'])
            ->where('country_id', $this->form['country_id'])
            ->get()
            ->toArray();
    }
}
