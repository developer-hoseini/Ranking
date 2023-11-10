<?php

namespace App\Livewire\Pages\Games\Page\Index;

use App\Models\Game;
use App\Models\User;
use Livewire\Component;

class SearchUser extends Component
{
    public Game $game;

    public ?User $opponent;

    public string $username = '';

    public $users;

    public $usersRandom;

    public function mount(Game $game, $opponent = null)
    {
        $this->game = $game;
        $this->opponent = $opponent;
        $this->usersRandom = $opponent?->id ? collect([]) :
            User::active()
                ->whereHas('competitions', fn ($q) => $q->where('game_id', $game?->id))
                ->whereHas('profile', fn ($q) => $q->where('state_id', auth()->user()?->profile?->state_id))
                ->inRandomOrder()
                ->whereNot('id', auth()->id())
                ->take(config('setting.random_users'))->get();
    }

    public function updatedUsername($username)
    {
        $this->usersRandom = collect([]);
        $this->users = User::searchUsername($username)
            ->active()
            ->whereHas('competitions', fn ($q) => $q->where('game_id', $this->game?->id))
            ->whereNot('id', auth()?->id())
            ->get();
    }

    public function render()
    {
        return view('livewire.pages.games.page.index.search-user', [
            'usersRandom' => $this->username ?: [],
        ]);
    }
}
