<?php

namespace ISS\App\Infrastructure\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

/**
 * Уведомление обучающемуся сотруднику, что подходит срок сдачи экзамена
 * @var string $issUserName имя сотрудника
 * @var string $issUserSecondName отчество сотрудника
 * @var string $issUserLastName фамилия сотрудника
 * @var string $routeName название обучающего маршрута
 * @var string $pointName название справочной точки обучающего маршрута, относ-я к реальной точке для которой подходит срок сдачи экзамена
 * @var string $examDate запланированная дата экзамена
 */

class IssExamDateCome extends Mailable
{
    use Queueable, SerializesModels;

    private string $issUserName;
    private string $issUserSecondName;
    private string $issUserLastName;
    private string $routeName;
    private string $pointName;
    private string $examDate;


    /**
     * Create a new message instance.
     */
    public function __construct(
        string $issUserName,
        string $issUserSecondName,
        string $issUserLastName,
        string $routeName,
        string $pointName,
        string $examDate
    )
    {
        $this->issUserName = $issUserName;
        $this->issUserSecondName = $issUserSecondName;
        $this->issUserLastName = $issUserLastName;
        $this->routeName = $routeName;
        $this->pointName = $pointName;
        $this->examDate = $examDate;
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
            view: 'iss::mail.examDateComeNotify',
            with: [
                'examDate' => $this->examDate,
                'pointName' => $this->pointName,
                'routeName' => $this->routeName,
                'issUserName' => $this->issUserName,
                'issUserSecondName' => $this->issUserSecondName,
                'issUserLastName' => $this->issUserLastName,
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
