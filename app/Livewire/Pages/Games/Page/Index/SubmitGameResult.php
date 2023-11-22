<?php

namespace App\Livewire\Pages\Games\Page\Index;

use App\Enums\StatusEnum;
use App\Models\Competition;
use App\Models\Game;
use App\Models\Invite;
use App\Models\Status;
use App\Notifications\Achievement\GameResult\SubmitGameResultNotification;
use App\Services\Actions\User\GetGameRank;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class SubmitGameResult extends Component
{
    use WithFileUploads;

    public $inviteGameResult;

    public $user = null;

    public $game = null;

    public $gameResult = null;

    public ?Competition $competitions = null;

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
        $this->game = $game;
        $this->inviteGameResult = $inviteGameResult;
        $this->loadData();
    }

    private function loadData(): void
    {
        $inviteGameResult = $this->inviteGameResult;
        $this->competitions = $inviteGameResult->competitions->first();
        $user = $inviteGameResult->inviter_user_id === auth()->id() ? $inviteGameResult->invitedUser : $inviteGameResult->inviterUser;
        $user->game_rank = GetGameRank::handle($user?->id, $this->game->id);

        $inviteCompetitionsGameResults = $inviteGameResult->inviteCompetitionsGameResults;
        $this->gameResult = $inviteCompetitionsGameResults->where('playerable_id', auth()->id())->first();

        $this->user = (object) [...$user->toArray(), 'avatar_name' => $user?->avatar_name, 'avatar' => $user?->avatar];
        $this->inClub = $inviteGameResult->gameType?->whereIn('name', 'in_club')->count();
        $this->withImage = $inviteGameResult->gameType?->whereIn('name', 'with_image')->count();
        $this->activeResult = (bool) $this->gameResult?->gameResultStatus;
        $this->result = $this->result ?? $this->gameResult?->gameResultStatus?->name;
        $this->status = $this->gameResult?->gameResultStatus?->name;
    }

    #[On('reloadInviteGameResults')]
    public function loadInviteGameResults(): void
    {
        $this->inviteGameResult = Invite::query()
            ->where('invites.game_id', $this->game->id)
            ->whereHas('confirmStatus', fn ($q) => $q->whereNotIn('statuses.name', StatusEnum::getNotReadyInvite()))
            ->where(function ($query) {
                $query->where('invites.invited_user_id', auth()->id())
                    ->orWhere('invites.inviter_user_id', auth()->id());
            })
            ->has('inviteCompetitionsGameResults', '>=', 1)
            ->with([
                'inviteCompetitionsScoreOccurredModel',
                'inviteCompetitionsGameResults.gameResultStatus',
                'competitions' => fn ($q) => $q->latest(),
                'invitedUser' => fn ($q) => $q->with('profile')->withSum('userScoreAchievements', 'count'),
                'inviterUser' => fn ($q) => $q->with('profile')->withSum('userScoreAchievements', 'count'),
                'gameType',
                'confirmStatus',
                'club',
            ])
            ->orderBy('invites.id', 'desc')
            ->where('id', $this->inviteGameResult->id)
            ->first();

        $this->loadData();
    }

    public function updateResult($result): void
    {
        $this->result = $result;
    }

    public function submitResult()
    {
        $this->validate();

        if ($this->withImage && $this->image) {
            $this->competitions->addMedia($this->image)
                ->toMediaCollection('images');
            $this->image = null;
        }

        $statusResult = Status::where('name', StatusEnum::tryFrom($this->result)->value)->first('id')?->id;

        $this->gameResult->game_result_status_id = $statusResult;

        $this->gameResult->save();

        auth()->user()?->notify(new SubmitGameResultNotification($this->gameResult));

        $this->result = null;

        $this->dispatch('reloadInviteGameResults')->self();
    }

    public function render()
    {
        return view('livewire.pages.games.page.index.submit-game-result');
    }
}
