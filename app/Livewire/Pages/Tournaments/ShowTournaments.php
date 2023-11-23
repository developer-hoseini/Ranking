<?php

namespace App\Livewire\Pages\Tournaments;

use App\Models\Competition;
use App\Models\Cup;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ShowTournaments extends Component
{
    public Cup $cup;

    public $setting = [];

    public $winerUsers = [];

    public $winerTeams = [];

    public function mount($id)
    {
        $this->cup = $this->getCup($id);
        $this->setting = config('ranking.rules.coin.tournoment');

        if ($this->cup->is_team) {
            $this->winerTeams = $this->getWinerTeams();
        } else {
            $this->winerUsers = $this->getWinerUsers();
        }
    }

    private function getCup($id)
    {

        $cup = Cup::where('id', $id)
            ->cupAcceptedStatusScope()
            ->with([
                'competitions.gameResults.gameResultAdminStatus',
                'competitions.gameResults.gameResultStatus',
                'registeredUsers',
                'registeredTeams',
                'state.country',
                'createdByUser.profile',
                'media',
            ])
            ->firstOrFail();

        return $cup;
    }

    private function getWinerUsers()
    {
        $cupFirstAndSecondUsers = $this->cup?->cupFirstAndSecondUsers;
        $cupThirdAndForthUser = $this->cup?->cupThirdAndForthUser;

        $winerUsers = collect([...$cupFirstAndSecondUsers, ...$cupThirdAndForthUser])->values()->filter();
        $winerUsers = Collection::make($winerUsers)->values();
        $winerUsers
            ->load(['media', 'profile'])
            ->loadSum([
                'userScoreAchievements' => function ($q) {
                    $q->whereHasMorph('achievementable', [Competition::class], function ($q) {
                        $q->where('game_id', $this->cup->game_id);
                    });
                },
            ], 'count')
            ->loadSum([
                'userCoinAchievements' => function ($q) {
                    $q->whereHasMorph('achievementable', [Competition::class], function ($q) {
                        $q->where('game_id', $this->cup->game_id);
                    });
                },
            ], 'count');

        $loadedSumWinderUsers = [...$cupFirstAndSecondUsers, ...$cupThirdAndForthUser];

        return $loadedSumWinderUsers;
    }

    private function getWinerTeams()
    {
        $cupFirstAndSecondTeams = $this->cup?->cupFirstAndSecondUsers;
        $cupThirdAndForthTeams = $this->cup?->cupThirdAndForthUser;

        $winerTeams = collect([...$cupFirstAndSecondTeams, ...$cupThirdAndForthTeams])->values()->filter();
        $winerTeams = Collection::make($winerTeams)->values();

        $winerTeams
            ->load(['media'])
            ->loadSum([
                'teamScoreAchievements' => function ($q) {
                    $q->whereHasMorph('achievementable', [Competition::class], function ($q) {
                        $q->where('game_id', $this->cup->game_id);
                    });
                },
            ], 'count')
            ->loadSum([
                'teamCoinAchievements' => function ($q) {
                    $q->whereHasMorph('achievementable', [Competition::class], function ($q) {
                        $q->where('game_id', $this->cup->game_id);
                    });
                },
            ], 'count');

        $loadedSumWinderTeams = [...$cupFirstAndSecondTeams, ...$cupThirdAndForthTeams];

        return $loadedSumWinderTeams;
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.pages.tournaments.show-tournaments');
    }
}
