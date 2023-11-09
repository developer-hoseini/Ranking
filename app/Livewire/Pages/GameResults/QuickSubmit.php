<?php

namespace App\Livewire\Pages\GameResults;

use App\Enums\StatusEnum;
use App\Models\Game;
use App\Models\Status;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class QuickSubmit extends Component
{
    use WithFileUploads;

    public ?string $searchUser = '';

    public ?array $users = [];

    public $selectedUser = null;

    #[Rule([
        'form.user_id' => 'required|exists:users,id',
        'form.game_id' => 'required|exists:games,id',
        'form.image' => 'nullable|image|max:30000',
        'form.result' => 'required|in:win,lost',
    ])]
    public ?array $form = [
        'user_id' => null,
        'game_id' => null,
        'image' => null,
        'result' => '',
    ];

    public function mount(): void
    {
        // $this->form->fill();
    }

    #[Computed]
    public function games()
    {
        return Game::active()->orderBy('sort', 'asc')->select(['id', 'name'])->get();
    }

    public function updatedSearchUser()
    {
        $this->resetErrorBag('form.user_id');
        $this->users = [];

        $this->users = User::searchProfileAvatarNameScope($this->searchUser)
            ->with(['profile:id,user_id,avatar_name'])
            ->select(['id', 'name'])
            ->get()
            ->toArray();
    }

    #[On('user-selected')]
    public function userSeleced($userId, $avatarName)
    {
        $this->form['user_id'] = $userId;
        $user = collect($this->users)->filter(fn ($user) => $user['id'] == $userId)->first();
        if ($user) {
            $this->selectedUser = $user;
            $this->searchUser = $user['profile']['avatar_name'] ?? '';
        }

    }

    public function submitForm()
    {
        $this->validate();

        $authUser = auth()->user();

        $game = Game::where('id', $this->form['game_id'])->first();
        $selectedUserAvatarName = $this->selectedUser['profile']['avatar_name'] ?? '';
        $today = today()->format('Y-m-d');

        DB::beginTransaction();
        try {
            $competition = $authUser->competitions()->create([
                'name' => "{$game->name} - {$authUser?->profile?->avatar_name} vs {$selectedUserAvatarName} - $today",
                'game_id' => $game->id,
                'state_id' => $authUser?->state?->id,
                'created_by_user_id' => $authUser->id,
                'status_id' => Status::where('name', StatusEnum::COMPETITION_TWO_PLAYERS->value)->first()->id,

            ]);

            $gameResultStatusPlayer1 = $this->form['result'] == 'win' ? StatusEnum::GAME_RESULT_WIN->value : StatusEnum::GAME_RESULT_LOSE->value;
            $gameResultStatusPlayer2 = $this->form['result'] == 'win' ? StatusEnum::GAME_RESULT_LOSE->value : StatusEnum::GAME_RESULT_WIN->value;

            $competition->gameResults()->create([
                'playerable_id' => $authUser->id,
                'playerable_type' => User::class,
                'game_result_status_id' => Status::where('name', $gameResultStatusPlayer1)->first()->id,
                'status_id' => Status::where('name', StatusEnum::PENDING->value)->first()->id,
            ]);

            $competition->gameResults()->create([
                'playerable_id' => $this->selectedUser['id'],
                'playerable_type' => User::class,
                'game_result_status_id' => Status::where('name', $gameResultStatusPlayer2)->first()->id,
                'status_id' => Status::where('name', StatusEnum::PENDING->value)->first()->id,
            ]);

            if (isset($this->form['image'])) {
                $competition->addMedia($this->form['image']?->getRealPath())
                    ->toMediaCollection('images');

                $this->reset('form.image');
            }
            DB::commit();
            $this->reset();
            session()->flash('success', 'Your game result submited successfully.');

        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', 'Error!');

        }

        $this->redirect(route('game-results.quick-submit'));

    }

    public function render()
    {
        return view('livewire.pages.game-results.quick-submit');
    }
}
