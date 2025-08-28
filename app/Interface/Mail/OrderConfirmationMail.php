<?php

namespace App\Interface\Mail;

use App\Domain\Order\Model\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('Подтверждение заказа #' . $this->order->getId())
            ->view('emails.order_confirmation')
            ->with([
                'order' => $this->order,
                'orderNumber' => $this->order->getId(),
                'customerName' => $this->order->getName() ?: 'Клиент',
                'totalAmount' => number_format($this->order->getTotalAmount(), 2, '.', ' '),
                'orderDate' => $this->order->getCreatedAt()->format('d.m.Y H:i'),
            ]);
    }
}
