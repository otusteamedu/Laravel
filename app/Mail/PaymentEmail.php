<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $uid, public string $event, public int $amount)
    {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Новая оплата в Смартлайн',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
