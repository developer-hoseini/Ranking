<?php

namespace App\Rules;

use App\Enums\StatusEnum;
use App\Models\Invite;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoRepeatClub implements ValidationRule
{
    public function __construct(public int $gameId, public int $userId)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value) {
            // Have you played with this player in days ago?
            $lastSendCount = Invite::query()
                ->where('created_at', '>', now()->subDays(config('setting.no_repeat_days')))
                ->where(function ($q) {
                    $q->orWhere(function ($q1) {
                        $q1->where('inviter_user_id', auth()->id())
                            ->where('invited_user_id', $this->userId);
                    })->orWhere(function ($q2) {
                        $q2->where('invited_user_id', auth()->id())
                            ->where('inviter_user_id', $this->userId);
                    });
                })
                ->where('game_id', $this->gameId)
                ->whereHas('confirmStatus', fn ($q3) => $q3->where('name', StatusEnum::FINISHED->value))
                ->count();

            if ($lastSendCount > 0) { // Yes
                $fail(__('message.days_not_take_or_latest_played_not_enough', ['days_count' => config('setting.no_repeat_days'),
                    'played_count' => config('setting.no_repeat_played')]));
                // Do you enough played in days ago to play again with this player?
                /*$last_played = Invite::select(['invited_user_id', 'inviter_user_id'])
                    ->where(function ($query) {
                        $query->where('inviter_user_id', auth()->id())
                            ->orWhere('invited_user_id', auth()->id());
                    })->where('game_id', $this->gameId)
                    ->whereHas('confirmStatus', fn ($q) => $q->where('name', StatusEnum::FINISHED->value))
                    ->orderBy('id', 'desc')
                    ->take(config('setting.no_repeat_played'))
                    ->get();

                if ($last_played->contains('inviter_id', $invited_id) ||
                    $last_played->contains('invited_id', $invited_id)) { // No
                    $request->session()->flash('message', __('message.days_not_take_or_latest_played_not_enough', ['days_count' => $setting['no_repeat_days'],
                        'played_count' => $setting['no_repeat_played']]));
                    $request->session()->flash('alert-class', 'alert-danger');

                    return redirect()->route('gamepage', ['game_id' => $game_id]);
                }*/
            }
        }
    }
}
