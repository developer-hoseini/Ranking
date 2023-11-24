<?php

namespace App\Livewire\Pages\GameResults;

use App\Enums\CompetitionableType;
use App\Enums\StatusEnum;
use App\Models\Game;
use App\Models\Status;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule as ValidationRule;
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

    public ?array $agents = [];

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
        'agent_user_id' => null,
    ];

    public function rules()
    {
        return [
            'form.agent_user_id' => ['nullable', 'exists:users,id', ValidationRule::in(array_column($this->agents, 'id'))],
        ];
    }

    public function mount(): void
    {
        $this->agents = User::hasAgentRolesScope()->select(['id', 'username'])->get()->toArray();
    }

    #[Computed]
    public function games()
    {
        $query = Game::active()
            ->gameTypesScope(['one player', 'team'], false)
            ->doesntHave('onlineGames')
            ->orderBy('sort', 'asc')
            ->select(['id', 'name']);

        return $query->get();
    }

    public function updatedSearchUser()
    {
        $this->resetErrorBag('form.user_id');

        $this->users = [];

        // if (Str::length($this->searchUser) <= 2) {
        //     return;
        // }

        $this->users = User::query()
            ->whereHas('profile', fn ($q) => $q->whereNotNull('avatar_name'))
            ->where(function ($q) {
                $q->searchProfileAvatarNameScope($this->searchUser)
                    ->orWhere('name', 'like', "%$this->searchUser%");
            })
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

        $authUser = \Auth::user();

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

            $competition->users()->attach($this->selectedUser['id']);

            if (! empty($this->form['agent_user_id'])) {
                $competition->competitionAgents()->attach($this->form['agent_user_id'], ['type' => CompetitionableType::AGENT->value]);
            }

            $gameResultStatusPlayer1 = $this->form['result'] == 'win' ? StatusEnum::GAME_RESULT_WIN->value : StatusEnum::GAME_RESULT_LOSE->value;
            $gameResultStatusPlayer2 = $this->form['result'] == 'win' ? StatusEnum::GAME_RESULT_LOSE->value : StatusEnum::GAME_RESULT_WIN->value;

            $statusPending = Status::where('name', StatusEnum::PENDING->value)->first();
            $statusAccepted = Status::where('name', StatusEnum::ACCEPTED->value)->first();

            $competition->gameResults()->create([
                'playerable_id' => $authUser->id,
                'playerable_type' => User::class,
                'game_result_status_id' => Status::where('name', $gameResultStatusPlayer1)->first()->id,
                'user_status_id' => $statusAccepted->id,
                'admin_status_id' => $statusPending->id,
            ]);

            $competition->gameResults()->create([
                'playerable_id' => $this->selectedUser['id'],
                'playerable_type' => User::class,
                'game_result_status_id' => Status::where('name', $gameResultStatusPlayer2)->first()->id,
                'user_status_id' => $statusPending->id,
                'admin_status_id' => $statusPending->id,
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
