<?php

namespace App\Notifications\Achievement\Invite;

use App\Enums\ReasonEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AcceptInviteNotification extends Notification
{
    use Queueable;

    private $invite;

    private $message;

    public function __construct($invite)
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
            ->subject('Invite Has Accepted')
            ->line($this->message)
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable): array
    {
        return [
            ...$this->invite->toArray(),
            'message' => $this->message,
            'type' => ReasonEnum::INVITE_ACCEPTED,
        ];
    }

    private function setMessage(): void
    {
        $this->message = __('message.sms_your_invite_has_accepted', ['fullname' => $this->invite->invitedUser?->profile?->fullname]);
    }
}
