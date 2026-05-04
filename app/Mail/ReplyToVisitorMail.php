<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReplyToVisitorMail extends Mailable
{
    use Queueable, SerializesModels;

    public Contact $contact;

    public string $reply;

    public function __construct(Contact $contact, string $reply)
    {
        $this->contact = $contact;
        $this->reply = $reply;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Re: La tua richiesta per '.$this->contact->car->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reply-visitor',
        );
    }
}
