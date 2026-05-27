<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class SendEmailService
{
    public function sendEmail(
        string $to,
        string $subject,
        string $body
    ) {
        Log::info("Email sent", compact("to", "subject", "body"));
    }
}
