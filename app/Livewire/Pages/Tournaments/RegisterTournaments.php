<?php

namespace App\Livewire\Pages\Tournaments;

use App\Models\Cup;
use Livewire\Component;

class RegisterTournaments extends Component
{
    public $cupId;

    public function mount($id)
    {
        $this->cupId = $id;

        $this->registerAuthUser();
    }

    public function registerAuthUser()
    {
        $authUser = auth()?->user();

        $cup = Cup::where('id', $this->cupId)->acceptedStatusScope()->firstOrFail();

        $cup->registeredUsers()->attach($authUser->id);

        return redirect()
            ->route('tournaments.show', $cup->id)
            ->with('success', 'you register successfully to this tournament');

    }

    public function render()
    {
        return view('livewire.pages.tournaments.register-tournaments');
    }
}
