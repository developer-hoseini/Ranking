<?php

namespace App\Livewire\Pages;

use App\Models\Competition;
use App\Models\GameResult as ModelsGameResult;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class GameResult extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    #[Computed]
    public function gameResults()
    {
        $authUser = \Auth::user();

        $gameResults = ModelsGameResult::query()
            ->where('playerable_id', $authUser->id)
            ->where('playerable_type', User::class)
            ->where('gameresultable_type', Competition::class)
            ->with([
                'gameresultable.game:id,name',
                'gameresultable.opponentUsers:id,name,active',
                'gameresultable.opponentUsers.profile:id,user_id,avatar_name',
                'status:id,name',
                'gameResultStatus:id,name,model_type',
            ])
            ->paginate(config('ranking.settings.global.per_page'));

        return $gameResults;
    }

    public function render()
    {
        return view('livewire.pages.game-result');
    }
}
