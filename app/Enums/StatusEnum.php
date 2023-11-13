<?php

declare(strict_types=1);

namespace App\Enums;

use App\Models\Achievement;
use App\Models\Competition;
use App\Models\GameResult;
use App\Models\Invite;
use App\Models\Ticket;
use App\Traits\BaseEnum;

enum StatusEnum: string
{
    use BaseEnum;

    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case CANCELED = 'canceled';

    case FINISHED = 'finished';
    case SUBMIT_RESULT = 'submit_result';
    case WAIT_OPPONENT_RESULT = 'wait_opponent_result';
    case WAIT_IMAGE_VERIFY = 'wait_image_verify';
    case WAIT_CLUB_VERIFY = 'wait_club_verify';
    case GAME_RESULT_WIN = 'game_result_win';
    case GAME_RESULT_LOSE = 'game_result_lose';
    case GAME_RESULT_ABSENT = 'game_result_absent';
    case TICKET_PENDING = 'ticket_pending';
    case TICKET_ANSWERED = 'ticket_answered';
    case TICKET_CLOSED = 'ticket_closed';
    case ACHIEVEMENT_WIN = 'achievement_win';
    case ACHIEVEMENT_LOSE = 'achievement_lose';
    case ACHIEVEMENT_APPROVE = 'achievement_approve';
    case ACHIEVEMENT_SIGNUP = 'achievement_signup';
    case ACHIEVEMENT_COMPLETE_PROFILE = 'achievement_complete-profile';
    case ACHIEVEMENT_BUY_OR_SELL_COIN = 'achievement_buy-or-sell-coin';
    case COMPETITION_TOURNAMENT = 'competition_tournament';
    case COMPETITION_ONE_PLAYER = 'competition_one-player';
    case COMPETITION_TWO_PLAYERS = 'competition_two-players';
    case COMPETITION_MULTI_PLAYERS = 'competition_multi-players';

    public function getModelType(): ?string
    {
        return match ($this) {
            self::SUBMIT_RESULT, self::WAIT_OPPONENT_RESULT,
            self::WAIT_IMAGE_VERIFY,self::WAIT_CLUB_VERIFY => Invite::class,
            self::GAME_RESULT_WIN,self::GAME_RESULT_LOSE,self::GAME_RESULT_ABSENT => GameResult::class,
            self::TICKET_PENDING,self::TICKET_ANSWERED,self::TICKET_CLOSED => Ticket::class,
            self::ACHIEVEMENT_WIN,self::ACHIEVEMENT_LOSE,self::ACHIEVEMENT_SIGNUP,self::ACHIEVEMENT_APPROVE,self::ACHIEVEMENT_COMPLETE_PROFILE => Achievement::class,
            self::COMPETITION_TOURNAMENT,self::COMPETITION_ONE_PLAYER,self::COMPETITION_TWO_PLAYERS,self::COMPETITION_MULTI_PLAYERS => Competition::class,
            default => null
        };
    }

    public function getMessage(): ?string
    {
        return match ($this) {
            self::GAME_RESULT_WIN => 'you won',
            self::GAME_RESULT_LOSE => 'you lose',
            self::GAME_RESULT_ABSENT => 'you absent',
            self::TICKET_PENDING,self::TICKET_ANSWERED,self::TICKET_CLOSED => '',
            default => null
        };
    }

    public static function getAlreadyInvite(): array
    {
        return [
            self::PENDING->value,
            self::ACCEPTED->value,
            self::WAIT_IMAGE_VERIFY->value,
            self::WAIT_CLUB_VERIFY->value,
        ];
    }

    public static function getNotReadyInvite(): array
    {
        return [
            self::PENDING->value,
            self::CANCELED->value,
            self::REJECTED->value,
        ];
    }
}
