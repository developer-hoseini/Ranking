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

    public $usersResult;

    public $usersRandom;

    public function mount(Game $game, $opponent = null)
    {
        $this->game = $game;
        $this->opponent = $opponent?->loadSum('scoreAchievements', 'count');
        $this->usersRandom = $opponent?->id ? collect([]) :
            User::active()
                ->whereHas('competitions', fn ($q) => $q->where('game_id', $game?->id))
                ->whereHas('profile', fn ($q) => $q->where('state_id', auth()->user()?->profile?->state_id))
                ->inRandomOrder()
                ->whereNot('id', auth()->id())
                ->take(config('setting.random_users'))->get();
    }

    public function selectUser(User $opponent)
    {
        $this->opponent = $opponent->loadSum('scoreAchievements', 'count');
        $this->usersRandom = collect([]);
        $this->usersResult = collect([]);
        $this->username = '';
    }

    public function updatedUsername($username)
    {
        $this->opponent = null;
        $this->usersRandom = collect([]);
        $this->usersResult = User::searchUsername($username)
            ->with('profile')
            ->withSum('scoreAchievements', 'count')
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
