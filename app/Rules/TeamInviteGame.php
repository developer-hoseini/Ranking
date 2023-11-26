<?php

namespace App\Rules;

use App\Enums\StatusEnum;
use App\Models\Invite;
use App\Models\Team;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TeamInviteGame implements ValidationRule
{
    public function __construct(public Team $team)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $teamSelect = Team::whereHas('teamStatus', static fn ($q) => $q->where('name', StatusEnum::ACCEPTED->value))
            ->with('users')
            ->where([
                ['state_id', auth()->user()?->profile->state_id],
                ['id',  $value],
                ['game_id', $this->team?->game_id],
            ])->first();

        // user not found
        if (! $teamSelect) {
            $fail(__('message.teamname_not_found'));
        }

        // does the team have members?
        if ($this->team?->users->count() === 0) {
            $fail(__('message.The team has no members'));
        }

        // does the opponent team have members?
        if ($teamSelect?->users->count() === 0) {
            $fail(__('message.The opponent team has no members'));
        }

        // A player can't be in both teams
        $countTeamUser = Team::query()
            ->where('id', $value)
            ->whereHas('users', fn ($q) => $q->where('id', $this->team?->users->pluck('id')))
            ->count();

        if ($countTeamUser !== 0) {
            $fail(__('message.player_can_not_be_in_both_teams'));
        }

        // **** Already Sent ****
        $alreadySend = Invite::where(function ($query) use ($value) {
            $query->where(['inviter_user_id' => $this->team?->id, 'invited_user_id' => $value])
                ->orWhereRaw('invited_user_id=? AND inviter_user_id=?', [auth()->id(), $this->team?->id]);
        })->whereHas('confirmStatus', fn ($q) => $q->whereIn('name', StatusEnum::getAlreadyInvite()))
            ->count();

        if ($alreadySend > 0) {
            $fail(__('message.you_already_sent_team'));
        }
    }
}
