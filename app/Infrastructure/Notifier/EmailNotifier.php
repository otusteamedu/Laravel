<?php

declare(strict_types=1);

namespace App\Infrastructure\Notifier;

use App\Infrastructure\Eloquent\Repositories\UserRepository;
use App\Services\NotifierInterface;
use Illuminate\Support\Facades\Mail;
use Webmozart\Assert\Assert;

/**
 * Смотри пояснения в
 * @see NotifierInterface
 */
class EmailNotifier implements NotifierInterface
{

    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    public function send(string $text, int $recipientId): void
    {

        $user = $this->userRepository->find($recipientId);
        Assert::notNull($user);


        $recipientEmail = $user->email;

        Mail::raw($text, function ($message) use ($recipientEmail) {
            $message->to($recipientEmail)
                ->subject('Notification');
        });
    }
}
