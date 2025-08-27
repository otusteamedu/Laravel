<?php

namespace ISS\App\Infrastructure\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

/**
 * @var string $examDate дата экзамена по расписанию
 * @var string $pointName название точки обучающего маршрута (из справочника)
 * @var string $routeName название обучающего маршрута (из справочника)
 * @var string $examCheckResult результат проверки экзамена
 */

class IssExamStatusNotify extends Mailable
{
    use Queueable, SerializesModels;

    private string $examDate;
    private string $pointName;
    private string $routeName;
    private string $examCheckResult;

    /**
     * Create a new message instance.
     */
    public function __construct(string $examDate, string $pointName, string $routeName, string $examCheckResult)
    {
        $this->examDate = $examDate;
        $this->pointName = $pointName;
        $this->routeName = $routeName;
        $this->examCheckResult = $examCheckResult;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('iss.ISS_MAIL_FROM_ADDRESS'), __('iss::issNotify.studentMail.fromName')),
            subject: __('iss::issNotify.examStatusNotify.mailHeader'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'iss::mail.examStatusNotify',
            with: [
                'examDate' => $this->examDate,
                'pointName' => $this->pointName,
                'routeName' => $this->routeName,
                'examCheckResult' => $this->examCheckResult,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
