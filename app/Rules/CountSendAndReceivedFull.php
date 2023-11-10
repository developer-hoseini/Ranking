<?php

namespace App\Rules;

use App\Enums\StatusEnum;
use App\Models\Invite;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CountSendAndReceivedFull implements ValidationRule
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
        $receivedCount = Invite::where([
            'invited_user_id' => $value,
            'game_id' => $this->gameId,
        ])->whereHas('confirmStatus', fn ($q) => $q->where('name', StatusEnum::PENDING->value))
            ->count();

        if ($receivedCount >= config('setting.max_received')) {
            $fail(__('message.received_list_of_opponent_is_full'));
        }

        $sentCount = Invite::where([
            'inviter_user_id' => auth()?->id(),
            'game_id' => $this->gameId,
        ])->whereHas('confirmStatus', fn ($q) => $q->where('name', StatusEnum::PENDING->value))
            ->count();

        if ($sentCount >= config('setting.max_sent')) {
            $fail(__('message.your_sent_list_is_full', ['sent_max' => config('setting.max_sent')]));
        }
    }
}
