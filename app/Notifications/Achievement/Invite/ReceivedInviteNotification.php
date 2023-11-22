<?php

namespace App\Notifications\Achievement\Invite;

use App\Enums\ReasonEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReceivedInviteNotification extends Notification
{
    use Queueable;

    private $invite;

    private $message;

    public function __construct($invite, public $game)
    {
        $this->afterCommit();

        $this->invite = $invite;

        $this->setMessage();
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Invited User')
            ->line($this->message)
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable): array
    {
        return [
            ...$this->invite->toArray(),
            'message' => $this->message,
            'type' => ReasonEnum::INVITE_RECEIVED,
        ];
    }

    private function setMessage(): void
    {
        $this->message = __('message.sms_you_have_new_invite', ['fullname' => $this->invite->invitedUser?->profile?->fullname, 'game' => $this->game->name]);
    }
}
