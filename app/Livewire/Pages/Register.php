<?php

namespace App\Livewire\Pages;

use App\Models\Country;
use App\Models\State;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Register extends Component
{
    public $countries = [];

    public $states = [];

    #[Rule([
        'form.avatar_name' => 'required|min:4|max:25|unique:users,username',
        'form.email' => 'required|email|unique:users,email',
        'form.password' => 'required|min:5|max:50',
    ])]
    public $form = [
        'avatar_name' => '',
        'email' => '',
        'password' => '',
        'state_id' => '',
        'country_id' => '',
    ];

    public function mount()
    {
        // $this->countries = Country::select(['id', 'name'])->get()->toArray();

    }

    public function updatedFormCountryId()
    {
        // $this->form['state_id'] = '';
        // $this->states = $this->getStates();
    }

    public function submitForm()
    {
        $this->validate();

        $user = User::create([
            'username' => $this->form['avatar_name'],
            'email' => $this->form['email'],
            'password' => bcrypt($this->form['password']),
        ]);

        event(new Registered($user));

        auth()->login($user);

        return redirect('/')->with('success', 'Account successfully registered. Please verify your email address');

    }

    public function render()
    {
        return view('livewire.pages.register');
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
