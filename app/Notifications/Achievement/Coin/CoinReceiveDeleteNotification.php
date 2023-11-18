<?php

namespace App\Notifications\Achievement\Coin;

use App\Models\Achievement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CoinReceiveDeleteNotification extends Notification
{
    use Queueable;

    private $achievement;

    private $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(Achievement $achievement)
    {
        $this->afterCommit();

        $this->achievement = $achievement;

        $this->setMessage();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting('Ranking : Received Mgc Coin!')
            ->line($this->message)
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            ...$this->achievement->toArray(),
            'message' => $this->message,
            'type' => 'delete',
        ];
    }

    private function setMessage(): void
    {
        $achievementStatusName = $this->achievement?->achievementStatus->nameWithoutModelPrefix;

        $this->message = "your '{$this->achievement?->count}' MGC coin is updated for '{$achievementStatusName}' !";
    }
}
