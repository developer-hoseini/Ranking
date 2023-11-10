<?php

namespace App\Rules;

use App\Enums\StatusEnum;
use App\Models\Invite;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UserInviteGame implements ValidationRule
{
    public function __construct(public int $gameId)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $invited = User::select('id')
            ->whereHas('competitions', function ($query) {
                $query->where('game_id', $this->gameId);
            })->whereNot('id', auth()->id())
            ->where('id', $value)
            ->active()
            ->first();

        // user not found
        if (! $invited) {
            $fail(__('message.username_not_found'));
        }

        $alreadySend = Invite::where(function ($query) use ($value) {
            $query->where(['inviter_user_id' => auth()->id(), 'invited_user_id' => $value])
                ->orWhereRaw('invited_user_id=? AND inviter_user_id=?', [auth()->id(), $value]);
        })->where('game_id', $this->gameId)
            ->whereHas('confirmStatus', fn ($q) => $q->whereIn('name', StatusEnum::getAlreadyInvite()))
            ->count();

        if ($alreadySend > 0) {
            $fail(__('message.you_already_sent'));
        }
    }
}
