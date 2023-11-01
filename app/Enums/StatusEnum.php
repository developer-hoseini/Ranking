<?php

declare(strict_types=1);

namespace App\Enums;

use App\Models\Achievement;
use App\Models\GameResult;
use App\Models\Invite;
use App\Models\Ticket;
use App\Traits\BaseEnum;

enum StatusEnum: string
{
    use BaseEnum;

    case ACTIVE = 'active';
    case FINISHED = 'finished';
    case PENDING_FINISHED = 'pending_finished';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case CANCELED = 'canceled';
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
    case ACHIEVEMENT_COMPLETE_PROFIL = 'achievement_complete-profile';

    public function getModelType(): ?string
    {
        return match ($this) {
            self::SUBMIT_RESULT, self::WAIT_OPPONENT_RESULT,
            self::WAIT_IMAGE_VERIFY,self::WAIT_CLUB_VERIFY => Invite::class,
            self::GAME_RESULT_WIN,self::GAME_RESULT_LOSE,self::GAME_RESULT_ABSENT => GameResult::class,
            self::TICKET_PENDING,self::TICKET_ANSWERED,self::TICKET_CLOSED => Ticket::class,
            self::ACHIEVEMENT_WIN,self::ACHIEVEMENT_LOSE,self::ACHIEVEMENT_SIGNUP,self::ACHIEVEMENT_APPROVE,self::ACHIEVEMENT_COMPLETE_PROFIL => Achievement::class,
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
}
