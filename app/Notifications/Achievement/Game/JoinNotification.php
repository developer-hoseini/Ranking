<?php

namespace App\Notifications\Achievement\Game;

use App\Enums\ReasonEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class JoinNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $message;

    public function __construct(public $game)
    {
        $this->afterCommit();

        $this->setMessage();
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [];
    }

    public function toArray($notifiable): array
    {
        return [

            ...$this->game->toArray(),
            'message' => $this->message,
            'type' => ReasonEnum::JOIN_SCORES,
        ];
    }

    private function setMessage(): void
    {
        $this->message = __('message.you_joined_successfully', ['game_name' => $this->game->name]);
    }
}
