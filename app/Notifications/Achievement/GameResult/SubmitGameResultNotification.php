<?php

namespace App\Notifications\Achievement\GameResult;

use App\Enums\ReasonEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubmitGameResultNotification extends Notification
{
    use Queueable;

    private $message;

    public function __construct(public $gameResult)
    {
        $this->afterCommit();

        $this->setMessage();
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Submit Result')
            ->line($this->message)
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable): array
    {
        return [
            ...$this->gameResult->toArray(),
            'message' => $this->message,
            'type' => ReasonEnum::OPPONENT_SUBMITTED,
        ];
    }

    private function setMessage(): void
    {
        $this->message = __('message.result_saved');
    }
}
