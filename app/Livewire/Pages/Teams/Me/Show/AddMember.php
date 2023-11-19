<?php

namespace App\Livewire\Pages\Teams\Me\Show;

use App\Enums\StatusEnum;
use App\Models\Invite;
use App\Models\Status;
use App\Models\Team;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;

class AddMember extends Component
{
    public ?string $searchUser = '';

    public $team;

    public ?array $users = [];

    public $selectedUser = null;

    #[Rule([
        'form.user_id' => 'required|exists:users,id',
    ])]
    public ?array $form = [
        'user_id' => null,
    ];

    public function mount($teamId)
    {
        $this->team = Team::where('id', $teamId)
            ->where('created_by_user_id', \Auth::id())
            ->firstOrFail();
    }

    public function updatedSearchUser()
    {
        $this->resetErrorBag('form.user_id');

        $this->users = [];

        $this->users = User::query()
            ->whereHas('profile', fn ($q) => $q->whereNotNull('avatar_name'))
            ->whereDoesntHave('invited', function ($q) {
                $q->where('inviteable_type', Team::class)
                    ->where('inviteable_id', $this->team?->id);
            })
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

    public function formSubmit()
    {
        $this->validate();

        $columns = [
            'invited_user_id' => $this->selectedUser['id'],
            'inviteable_type' => Team::class,
            'inviteable_id' => $this->team->id,
        ];

        $updateColumn = [
            'confirm_status_id' => Status::nameScope(StatusEnum::PENDING->value)->firstOrFail()?->id,
            'inviter_user_id' => \Auth::id(),

        ];

        $invite = Invite::where($columns)->first();

        if ($invite) {
            $invite->updat($updateColumn);
        } else {
            Invite::create([
                ...$columns,
                ...$updateColumn,
            ]);

        }

        session()->flash('success', 'User invited successfully. please wait to accepte this request by user');

        $this->redirect(route('teams.me.show.memebers', $this->team->id));
    }

    public function render()
    {
        return view('livewire.pages.teams.me.show.add-member');
    }
}
