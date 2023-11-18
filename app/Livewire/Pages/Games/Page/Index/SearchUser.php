<?php

namespace App\Livewire\Pages\Games\Page\Index;

use App\Models\Club;
use App\Models\Country;
use App\Models\Game;
use App\Models\State;
use App\Models\User;
use Livewire\Component;

class SearchUser extends Component
{
    public Game $game;

    public ?User $opponent = null;

    public string $username = '';

    public $usersResult = null;

    public $usersRandom = [];

    public $countries = [];

    public $clubs = [];

    public $states = [];

    public $countryId = '';

    public $stateId = '';

    public function mount(Game $game, $opponent = null): void
    {
        $this->game = $game;

        $this->opponent = $opponent?->loadSum('userScoreAchievements', 'count');

        $this->usersRandom = $opponent?->id ? collect([]) :
            User::active()
                ->whereHas('competitions', fn ($q) => $q->where('game_id', $game?->id))
                ->whereHas('profile', fn ($q) => $q->where('state_id', auth()->user()?->profile?->state_id))
                ->inRandomOrder()
                ->whereNot('id', auth()->id())
                ->take(config('setting.random_users'))->get();

        $this->countries = Country::select(['id', 'name'])
            ->whereHas('states', fn ($q) => $q->whereHas('clubs', fn ($q2) => $q2->where('active', true)))
            ->get()
            ->toArray();
    }

    public function selectUser(User $opponent): void
    {
        $this->opponent = $opponent->loadSum('userScoreAchievements', 'count');
        $this->usersRandom = collect([]);
        $this->usersResult = null;
        $this->username = '';
    }

    public function updatedUsername($username): void
    {
        $this->opponent = null;
        $this->usersRandom = collect([]);
        $this->usersResult = User::searchUsername($username)
            ->with('profile')
            ->withSum('userScoreAchievements', 'count')
            ->active()
            ->whereHas('competitions', fn ($q) => $q->where('game_id', $this->game?->id))
            ->whereNot('id', auth()?->id())
            ->get();
    }

    public function updatedCountryId($countryId): void
    {
        if ($countryId) {
            $this->states = State::select(['id', 'name'])
                ->whereHas('clubs', static fn ($q) => $q->where('active', true))
                ->where('country_id', $countryId)
                ->orderBy('name')
                ->get()
                ->toArray();
            $this->stateId = '';
            $this->clubs = [];
        }
    }

    public function updatedStateId($stateId): void
    {
        if ($stateId) {
            $this->clubs = Club::select(['id', 'name'])
                ->where('active', true)
                ->where('state_id', $stateId)
                ->orderBy('sort')
                ->get()
                ->toArray();
        }
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.pages.games.page.index.search-user', [
            'usersRandom' => $this->username ?: [],
        ]);
    }
}
