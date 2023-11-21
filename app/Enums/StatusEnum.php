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
    case ACCEPTED = 'accepted';
    case PENDING = 'pending';
    case REJECTED = 'rejected';
    case CANCELED = 'canceled';

    case FINISHED = 'finished';
    case SUBMIT_RESULT = 'submit_result';
    case WAIT_OPPONENT_RESULT = 'wait_opponent_result';
    case WAIT_IMAGE_VERIFY = 'wait_image_verify';
    case QUICK_WAIT_OPPONENT = 'quick_wait_opponent';
    case WAIT_CLUB_VERIFY = 'wait_club_verify';
    case GAME_RESULT_WIN = 'game_result_win';
    case GAME_RESULT_LOSE = 'game_result_lose';
    case GAME_RESULT_ABSENT = 'game_result_absent';
    case GAME_RESULT_I_ABSENT = 'game_result_i_absent';
    case GAME_RESULT_HE_ABSENT = 'game_result_he_absent';
    case END_RESULT_FALSE = 'end_result_false';
    case END_NO_SUBMIT = 'end_no_submit';
    case END_IMAGE_FALSE = 'end_image_false';
    case END_CLUB_FALSE = 'end_club_false';
    case GAME_RESULT_PENDING_CONFIRM_USER = 'game_result_pending_confirm_user';
    case TICKET_PENDING = 'ticket_pending';
    case TICKET_ANSWERED = 'ticket_answered';
    case TICKET_CLOSED = 'ticket_closed';
    case ACHIEVEMENT_WIN = 'achievement_win';
    case ACHIEVEMENT_LOSE = 'achievement_lose';
    case ACHIEVEMENT_APPROVE = 'achievement_approve';
    case ACHIEVEMENT_SIGNUP = 'achievement_signup';
    case ACHIEVEMENT_COMPLETE_PROFILE = 'achievement_complete-profile';
    case ACHIEVEMENT_BUY_OR_SELL_COIN = 'achievement_buy-or-sell-coin';
    case ACHIEVEMENT_CONFIRM_COMPETITION = 'achievement_confirm_competition';
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
            self::ACHIEVEMENT_WIN,self::ACHIEVEMENT_LOSE,self::ACHIEVEMENT_SIGNUP,self::ACHIEVEMENT_APPROVE,self::ACHIEVEMENT_COMPLETE_PROFILE ,
            self::ACHIEVEMENT_BUY_OR_SELL_COIN,self::ACHIEVEMENT_CONFIRM_COMPETITION => Achievement::class,
            self::COMPETITION_TOURNAMENT,self::COMPETITION_ONE_PLAYER,self::COMPETITION_TWO_PLAYERS,self::COMPETITION_MULTI_PLAYERS => Competition::class,
            default => null
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDING => __('words.Pending'),
            self::SUBMIT_RESULT => __('words.Submit your game result'),
            self::WAIT_OPPONENT_RESULT => __('words.Wait for opponent to submit the result'),
            self::QUICK_WAIT_OPPONENT => __('words.wait_opponent_verify'),
            self::WAIT_IMAGE_VERIFY => __('words.Wait for image verifying by referee'),
            self::WAIT_CLUB_VERIFY => __('words.Wait for verifying by club'),
            self::GAME_RESULT_WIN => __('words.You Won!'),
            self::GAME_RESULT_LOSE => __('words.Your opponent has won'),
            self::GAME_RESULT_I_ABSENT => __('words.You were absent'),
            self::GAME_RESULT_HE_ABSENT => __('words.Your opponent was absent'),
            self::END_RESULT_FALSE => __('words.Result of play not correct (Fault)'),
            self::END_NO_SUBMIT => __('words.Play ended without submit of result'),
            self::ACCEPTED => __('words.Accepted'),
            self::REJECTED => __('words.Rejected'),
            self::CANCELED => __('words.Canceled'),
            self::END_IMAGE_FALSE => __('words.Image not verified by referee'),
            self::END_CLUB_FALSE => __('words.Play not verified by club'),
            self::FINISHED => throw new \Exception('To be implemented'),
            self::GAME_RESULT_ABSENT => throw new \Exception('To be implemented'),
            self::GAME_RESULT_PENDING_CONFIRM_USER => throw new \Exception('To be implemented'),
            self::TICKET_PENDING => throw new \Exception('To be implemented'),
            self::TICKET_ANSWERED => throw new \Exception('To be implemented'),
            self::TICKET_CLOSED => throw new \Exception('To be implemented'),
            self::ACHIEVEMENT_WIN => throw new \Exception('To be implemented'),
            self::ACHIEVEMENT_LOSE => throw new \Exception('To be implemented'),
            self::ACHIEVEMENT_APPROVE => throw new \Exception('To be implemented'),
            self::ACHIEVEMENT_SIGNUP => throw new \Exception('To be implemented'),
            self::ACHIEVEMENT_COMPLETE_PROFILE => throw new \Exception('To be implemented'),
            self::ACHIEVEMENT_BUY_OR_SELL_COIN => throw new \Exception('To be implemented'),
            self::ACHIEVEMENT_CONFIRM_COMPETITION => throw new \Exception('To be implemented'),
            self::COMPETITION_TOURNAMENT => throw new \Exception('To be implemented'),
            self::COMPETITION_ONE_PLAYER => throw new \Exception('To be implemented'),
            self::COMPETITION_TWO_PLAYERS => throw new \Exception('To be implemented'),
            self::COMPETITION_MULTI_PLAYERS => throw new \Exception('To be implemented')
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

    public static function getGameResult(): array
    {
        return [
            self::GAME_RESULT_I_ABSENT->value,
            self::GAME_RESULT_HE_ABSENT->value,
            self::GAME_RESULT_LOSE->value,
            self::GAME_RESULT_WIN->value,
        ];
    }
}
