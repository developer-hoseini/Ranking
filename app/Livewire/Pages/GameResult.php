<?php

namespace App\Livewire\Pages;

use App\Enums\StatusEnum;
use App\Models\Competition;
use App\Models\GameResult as ModelsGameResult;
use App\Models\Status;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
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
                'gameResultAdminStatus:id,name',
                'gameResultUserStatus:id,name',
                'gameResultStatus:id,name,model_type',
            ])
            ->paginate(config('ranking.settings.global.per_page'));

        return $gameResults;
    }

    #[On('change-status')]
    public function changeStatus($resultId, $type)
    {
        $authUser = \Auth::user();

        $gameResult = \App\Models\GameResult::query()
            ->where([
                'id' => $resultId,
                'playerable_type' => User::class,
                'playerable_id' => $authUser->id,
            ])
            ->with('gameResultable')
            ->firstOrFail();

        if ($gameResult->gameResultAdminStatus->name == StatusEnum::ACCEPTED->value) {
            session()->flash('error', 'this result accepted by admin !!');

            return $this->redirect(route('game-results.index'));
        }

        if ($gameResult->gameResultUserStatus->name != StatusEnum::PENDING->value) {
            session()->flash('error', 'only game results that have pending status is allowed !!');

            return $this->redirect(route('game-results.index'));
        }

        if ($type === 'accept') {
            $gameResult->update([
                'user_status_id' => Status::nameScope(StatusEnum::ACCEPTED->value)->firstOrFail()?->id,
            ]);
        }

        if ($type === 'reject') {
            $gameResult->update([
                'user_status_id' => Status::nameScope(StatusEnum::REJECTED->value)->firstOrFail()?->id,
            ]);
        }

        session()->flash('success', 'status changed successfully');

        $this->redirect(route('game-results.index'));

    }

    public function render()
    {
        return view('livewire.pages.game-result');
    }
}
