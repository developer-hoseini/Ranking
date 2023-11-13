<?php

namespace App\Livewire\Pages\Games\Page\Index;

use App\Enums\StatusEnum;
use App\Models\Game;
use Livewire\Attributes\On;
use Livewire\Component;

class SendReceivedBox extends Component
{
    public $game;

    public $currentUser;

    public function mount(Game $game): void
    {
        $this->game = $game;
        $this->loadCurrentUser();
    }

    #[On('reloadCurrentUser')]
    public function loadCurrentUser(): void
    {

        $this->currentUser = auth()->user()?->load([
            'inviter' => function ($query) {
                $query
                    ->where('game_id', $this->game->id)
                    ->whereHas('confirmStatus', fn ($q) => $q->where('name', StatusEnum::PENDING->value))
                    ->with([
                        'invitedUser' => fn ($q) => $q->withSum('scoreAchievements', 'count'),
                        'club',
                        'confirmStatus',
                        'gameType',
                    ]);
            },
            'invited' => function ($query) {
                $query
                    ->where('game_id', $this->game->id)
                    ->whereHas('confirmStatus', fn ($q) => $q->where('name', StatusEnum::PENDING->value))
                    ->with([
                        'inviterUser' => fn ($q) => $q->with('profile')->withSum('scoreAchievements', 'count'),
                        'club',
                        'confirmStatus',
                        'gameType',
                    ]);
            },
        ]);
    }

    public function render()
    {
        return view('livewire.pages.games.page.index.send-received-box');
    }
}
