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

    public function mount($id)
    {
        $this->cup = $this->getCup($id);
        $this->setting = config('ranking.rules.coin.tournoment');
        $this->winerUsers = $this->getWinerUsers();
    }

    private function getCup($id)
    {
        return Cup::where('id', $id)
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
                'scoreAchievements' => function ($q) {
                    $q->whereHasMorph('achievementable', [Competition::class], function ($q) {
                        $q->where('game_id', $this->cup->game_id);
                    });
                },
            ], 'count')
            ->loadSum([
                'coinAchievements' => function ($q) {
                    $q->whereHasMorph('achievementable', [Competition::class], function ($q) {
                        $q->where('game_id', $this->cup->game_id);
                    });
                },
            ], 'count');

        $loadedSumWinderUsers = [...$cupFirstAndSecondUsers, ...$cupThirdAndForthUser];

        return $loadedSumWinderUsers;
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.pages.tournaments.show-tournaments');
    }
}
