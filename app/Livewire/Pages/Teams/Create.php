<?php

namespace App\Livewire\Pages\Teams;

use App\Models\Game;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    #[Rule([
        'form.game_id' => 'required|exists:games,id',
        'form.name' => 'required|min:4|max:255|unique:teams,name',
        'form.about' => 'nullable|string|max:65535',
        'form.avatar' => 'nullable|image|max:10000',
    ])]
    public $form = [
        'game_id' => null,
        'name' => null,
        'about' => null,
        'avatar' => null,
    ];

    #[Computed]
    public function games()
    {
        $games = Game::active()->get();

        return $games;
    }

    public function formSubmit()
    {
        $this->validate();

        $authId = \Auth::id();

        DB::beginTransaction();
        try {
            $team = Team::create([
                'game_id' => $this->form['game_id'],
                'name' => $this->form['name'],
                'about' => $this->form['about'] ?? '',
                'created_by_user_id' => $authId,
            ]);

            $team->users()->attach($authId);

            if (isset($this->form['avatar'])) {
                $team->addMedia($this->form['avatar']?->getRealPath())
                    ->toMediaCollection('avatar');

                $this->reset('form.avatar');
            }

            $this->reset();

            DB::commit();

            session()->flash('success', 'Your team created successfully');

        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', 'somthing is wrong ... ');
            throw $th;
        }

        $this->redirect(route('teams.me.index'));
    }

    public function render()
    {
        return view('livewire.pages.teams.create');
    }
}
