<?php

namespace App\Livewire\Pages\Teams;

use App\Enums\StatusEnum;
use App\Models\Club;
use App\Models\Country;
use App\Models\State;
use App\Models\Team;
use Livewire\Component;

class SearchTeam extends Component
{
    public string $teamName = '';

    public $teamSelect = null;

    public $team;

    public $teamsResult = [];

    public $teamsRandom = [];

    public $countries = [];

    public $clubs = [];

    public $states = [];

    public $countryId = '';

    public $stateId = '';

    public function mount(Team $team): void
    {
        $this->team = $team;

        $this->teamsRandom = Team::whereHas('teamStatus', fn ($q) => $q->where('name', StatusEnum::ACCEPTED->value))
            ->where([
                ['state_id', auth()->user()?->profile->state_id],
                ['id', '!=', $team->id],
                ['game_id', $team?->game_id],
            ])
            ->inRandomOrder()
            ->take(config('setting.random_users'))->get();

        $this->countries = Country::select(['id', 'name'])
            ->whereHas('states', fn ($q) => $q->whereHas('clubs', fn ($q2) => $q2->where('active', true)))
            ->get()
            ->toArray();
    }

    public function selectTeam(Team $teamSelect): void
    {
        $this->teamSelect = $teamSelect;
        $this->teamsRandom = collect([]);
        $this->teamsResult = null;
        $this->teamName = '';
    }

    public function updatedTeamName($teamName): void
    {
        $this->teamSelect = null;
        $this->teamsRandom = collect([]);
        $this->teamsResult = Team::whereHas('teamStatus', fn ($q) => $q->where('name', StatusEnum::ACCEPTED->value))
            ->where('name', 'Like', "%{$teamName}%")
            ->where([
                ['state_id', auth()->user()?->profile->state_id],
                ['id', '!=', $this->team->id],
                ['game_id', $this->team?->game_id],
            ])
            ->inRandomOrder()
            ->take(config('setting.random_users'))->get();
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
        return view('livewire.pages.teams.search-team');
    }
}
