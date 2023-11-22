<?php

namespace App\Livewire\Pages\Ranks;

use App\Enums\AchievementTypeEnum;
use App\Models\Achievement;
use App\Models\Game;
use App\Models\Team as ModelsTeam;
use Illuminate\Http\Request;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Team extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $game = [
        'id' => '',
        'name' => '',
        'game_competitions_users_count' => '',
    ];

    public $filters = [
        'rank' => '',
    ];

    public $type = 'global';

    public $team;

    public function mount(Request $request)
    {
        if ($request->has('filter.id')) {
            $this->setTeam();
        }
    }

    public function updatedFiltersRank($value)
    {
        $this->team = null;

    }

    #[Computed]
    public function games()
    {
        $items = Game::orderBy('sort')
            ->select(['id', 'name'])
            ->withCount('teams')
            ->get();

        if (! $this->game['id']) {
            $this->game = $items->first()?->toArray();
        }

        return $items;
    }

    #[Computed]
    public function rankTeams()
    {

        if ($this->type == 'country') {
            return $this->getCountryRankTeams() ?? [];
        }

        return $this->getGlobalRankTeams() ?? [];

    }

    private function getGlobalRankTeams()
    {
        $query = ModelsTeam::query()
            // ->acceptedScope()
            ->where('id', '!=', $this->team?->id)
            ->whereHas('game', function ($q) {
                $q->where('games.id', $this->game['id']);
            })
            ->with([
                'capitan',
            ])
            ->orderByDesc(
                Achievement::selectRaw('sum(count) as total_scores')
                    ->where('achievementable_type', ModelsTeam::class)
                    ->where('type', AchievementTypeEnum::SCORE->value)
                    ->whereColumn('achievementable_id', 'teams.id')
                    ->groupBy('achievementable_id')
            )
            ->withSum('teamScoreAchievements', 'count')
            ->withSum('teamCoinAchievements', 'count');

        if ($this->filters['rank']) {
            $teams = $query->skip($this->filters['rank'] - 1)->take(1)->get();
        } else {
            $teams = $query
                ->paginate(config('ranking.settings.global.per_page'));
        }

        return $teams;
    }

    private function getCountryRankTeams()
    {
        $countryId = null;
        $authUser = auth()->user();

        if ($authUser) {
            $countryId = $authUser?->state?->country_id;
        }

        if ($this->team) {
            $countryId = $this->team?->state?->country_id;
        }

        $query = ModelsTeam::query()
            ->where('id', '!=', $this->team?->id)
            ->whereHas('game', function ($q) {
                $q->where('games.id', $this->game['id']);
            })
            ->whereHas('state', fn ($q) => $q->where('country_id', $countryId))
            ->with([
                'capitan',
            ])
            ->orderByDesc(
                Achievement::selectRaw('sum(count) as total_scores')
                    ->where('achievementable_type', ModelsTeam::class)
                    ->where('type', AchievementTypeEnum::SCORE->value)
                    ->whereColumn('achievementable_id', 'teams.id')
                    ->groupBy('achievementable_id')
            )
            ->withSum('teamScoreAchievements', 'count')
            ->withSum('teamCoinAchievements', 'count');

        if ($this->filters['rank']) {
            $teams = $query->skip($this->filters['rank'] - 1)->take(1)->get();
        } else {
            $teams = $query
                ->paginate(config('ranking.settings.global.per_page'));
        }

        return $teams;
    }

    public function changeGame()
    {
        $this->team = null;
        $game = $this->games->where('id', $this->game['id'])->first();

        if ($game) {
            $this->game = $game->toArray();
            $this->filters['rank'] = '';
        }

    }

    private function setTeam()
    {
        $teamId = collect(request()->get('filter'))->get('id');

        $this->team = ModelsTeam::query()
            ->acceptedScope()
            ->where('id', $teamId)
            ->with([
                'capitan',
            ])
            ->withSum('teamScoreAchievements', 'count')
            ->withSum('teamCoinAchievements', 'count')
            ->first();

        if ($this->team) {
            $this->game = $this->games->where('id', $this->team->game_id)->first()?->toArray();
        }
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.pages.ranks.team');
    }
}
