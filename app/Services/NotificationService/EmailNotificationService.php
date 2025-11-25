<?php

namespace App\Services\NotificationService;

use Illuminate\Log\LogManager;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

class EmailNotificationService
{
    public function __construct(private string $fromEmail)
    {

    }

    public function notify(\App\Models\User $user, \App\VO\NotificationText $text)
    {
        Mail::raw($text->getText(), function (Message $mail) use ($user) {
            $mail->to($user->email);
            $mail->subject("New like");
            $mail->from($this->fromEmail);
        });
    }
}
