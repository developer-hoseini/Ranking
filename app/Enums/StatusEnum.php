<?php

declare(strict_types=1);

namespace App\Enums;

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
    case WIN = 'win';
    case LOSE = 'lose';
    case ABSENT = 'absent';
    case PENDING = 'pending';
    case ANSWERED = 'answered';
    case CLOSED = 'closed';

    public function getModelType(): ?string
    {
        return match ($this) {
            self::SUBMIT_RESULT, self::WAIT_OPPONENT_RESULT,
            self::WAIT_IMAGE_VERIFY,self::WAIT_CLUB_VERIFY => Invite::class,
            self::WIN,self::LOSE,self::ABSENT => GameResult::class,
            self::PENDING,self::ANSWERED,self::CLOSED => Ticket::class,
            default => null
        };
    }

    public function getMessage(): ?string
    {
        return match ($this) {
            self::WIN => 'you won',
            self::LOSE => 'you lose',
            self::ABSENT => 'you absent',
            self::PENDING,self::ANSWERED,self::CLOSED => '',
            default => null
        };
    }
}
