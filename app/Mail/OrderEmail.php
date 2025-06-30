<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order,)
    {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Новый заказ в Смартлайн',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
