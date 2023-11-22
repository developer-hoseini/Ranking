<?php

namespace App\Livewire\Pages\Teams;

use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    #[Rule([
        'form.about' => 'nullable|string|max:65535',
        'form.avatar' => 'nullable|image|max:10000',
    ])]
    public $form = [
        'name' => null,
        'about' => null,
        'avatar' => null,
    ];

    public ?Team $team;

    public function mount($id)
    {
        $team = Team::where('id', $id)->where('created_by_user_id', \Auth::id())->firstOrFail();
        $this->team = $team;
        $this->form = [
            ...$this->form,
            'name' => $this->team->name ?? '',
            'about' => $this->team->about ?? '',
        ];
    }

    public function rules()
    {
        return [
            'form.name' => ['required', 'string', 'min:4', 'max:255', 'unique:teams,name,'.$this->team->id],
        ];
    }

    public function formSubmit()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $this->team->update([
                'name' => $this->form['name'] ?? '',
                'about' => $this->form['about'] ?? '',
            ]);

            if (isset($this->form['avatar'])) {
                $this->team->addMedia($this->form['avatar']?->getRealPath())
                    ->toMediaCollection('avatar');

                $this->reset('form.avatar');
            }

            $this->reset();

            session()->flash('success', 'Your team updated successfully');

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        $this->redirect(route('teams.me.index'));
    }

    public function render()
    {
        return view('livewire.pages.teams.edit');
    }
}
