<?php

namespace App\Mail;

use App\Models\Game;
use App\Models\Invite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvitedUserMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Invite $invite, public Game $game)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invited User',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.invited-user',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
