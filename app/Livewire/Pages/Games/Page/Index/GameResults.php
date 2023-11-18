<?php

namespace App\Livewire\Pages\Games\Page\Index;

use App\Enums\StatusEnum;
use App\Models\Game;
use App\Models\Invite;
use Livewire\Attributes\On;
use Livewire\Component;

class GameResults extends Component
{
    public $game;

    public $inviteGameResults;

    public function mount(Game $game): void
    {
        $this->game = $game;
        $this->loadGameResults();
    }

    #[On('reloadGameResults')]
    public function loadGameResults(): void
    {
        $this->inviteGameResults = Invite::query()
            ->where('invites.game_id', $this->game->id)
            ->whereHas('confirmStatus', fn ($q) => $q->whereNotIn('statuses.name', StatusEnum::getNotReadyInvite()))
            ->where(function ($query) {
                $query->where('invites.invited_user_id', auth()->id())
                    ->orWhere('invites.inviter_user_id', auth()->id());
            })
            ->has('inviteCompetitionsGameResults', '>=', 1)
            ->with([
                'inviteCompetitionsScoreOccurredModel',
                'inviteCompetitionsGameResults',
                'competitions' => fn ($q) => $q->latest(),
                'invitedUser' => fn ($q) => $q->withSum('userScoreAchievements', 'count'),
                'inviterUser' => fn ($q) => $q->withSum('userScoreAchievements', 'count'),
                'gameType',
                'confirmStatus',
                'club',
            ])
            ->orderBy('invites.id', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.pages.games.page.index.game-results');
    }
}
