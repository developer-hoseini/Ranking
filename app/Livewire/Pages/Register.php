<?php

namespace App\Livewire\Pages;

use App\Enums\StatusEnum;
use App\Models\Country;
use App\Models\State;
use App\Models\User;
use App\Notifications\Achievement\Score\RegisterNotification;
use App\Services\Actions\Achievement\User\ReceiveCoin;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class Register extends Component
{
    public $countries = [];

    public $states = [];

    public $form = [
        'avatar_name' => '',
        'email' => '',
        'password' => '',
        'state_id' => '',
        'country_id' => '',
    ];

    public function rules()
    {
        return [
            'form.avatar_name' => 'required|min:5|max:25|unique:users,username',
            'form.email' => 'required|email|unique:users,email',
            'form.password' => [
                'required',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->letters(),
            ],
        ];
    }

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
        DB::beginTransaction();

        try {
            $user = User::create([
                'username' => $this->form['avatar_name'],
                'email' => $this->form['email'],
                'password' => bcrypt($this->form['password']),
            ]);
            ReceiveCoin::handle(
                $user,
                config('ranking.rules.coin.user.register'),
                StatusEnum::ACHIEVEMENT_SIGNUP
            );
            event(new Registered($user));
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        $user->notify(new RegisterNotification($user));

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
