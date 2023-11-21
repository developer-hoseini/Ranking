<?php

namespace App\Livewire\Pages\Games\Page\Index;

use App\Enums\StatusEnum;
use App\Models\Game;
use App\Services\Actions\User\GetGameRank;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class SubmitGameResult extends Component
{
    use WithFileUploads;

    public $inviteGameResult;

    public $user = null;

    public $status = null;

    public $inClub = false;

    public $withImage = false;

    public $activeResult = false;

    #[Validate('required')]
    public $result;

    #[Validate('image|max:1024')]
    public $image;

    public function rules(): array
    {
        $rules = [
            'result' => [
                'required',
                Rule::in(StatusEnum::getGameResult()),
            ],
        ];

        if ($this->withImage) {
            $rules['image'] = [
                'required',
                'image',
                'max:1024',
            ];
        }

        return $rules;
    }

    public function mount($inviteGameResult, Game $game): void
    {
        $this->inviteGameResult = $inviteGameResult;
        $this->user = $inviteGameResult->inviter_user_id == auth()->id() ? $inviteGameResult->invitedUser : $inviteGameResult->inviterUser;
        $this->user->game_rank = GetGameRank::handle($this->user?->id, $game->id);
        $this->inClub = $inviteGameResult->gameType?->whereIn('name', 'in_club')->count();
        $this->withImage = $inviteGameResult->gameType?->whereIn('name', 'with_image')->count();
        $this->activeResult = $inviteGameResult->inviteCompetitionsScoreOccurredModel->count() >= 2;
        $this->status = $inviteGameResult->confirmStatus->name;
    }

    public function updateResult($result): void
    {
        $this->result = $result;
    }

    public function submitResult($inviteId)
    {
        $this->validate();
        dd($inviteId, $this->result);
    }

    public function render()
    {
        return view('livewire.pages.games.page.index.submit-game-result');
    }
}
