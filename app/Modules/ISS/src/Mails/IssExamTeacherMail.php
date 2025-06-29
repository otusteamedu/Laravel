<?php

namespace App\Modules\ISS\src\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use App\Modules\ISS\src\Services\EducationExam\fillExamBlank\QuestionWithAnswersWithTextDTO;

/**
 * @var string $signedUrl ссылка для преподавателя, по которой надо перейти чтобы отправить результат проверки
 * @var string $checkCode однооразовый код проверки экзамена
 * @var array<QuestionWithAnswersWithTextDTO> $checkedQuestions массив id экзаменационных вопросов с ответами ученика
 *                                            и правильными ответами (где есть варианты) дополненый текстом вопросов\ответов
 */

class IssExamTeacherMail extends Mailable
{
    use Queueable, SerializesModels;

    private string $signedUrl; //если установиь доступ public то св-во автоматически станет доступно в шаблоне
    private string $checkCode; //если установиь доступ public то св-во автоматически станет доступно в шаблоне

    private array $checkedQuestions; //если установиь доступ public то св-во автоматически станет доступно в шаблоне

    /**
     * Create a new message instance.
     */
    public function __construct(string $signedUrl, string $checkCode, array $checkedQuestions)
    {
        $this->signedUrl = $signedUrl;
        $this->checkCode = $checkCode;
        $this->checkedQuestions = $checkedQuestions;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('iss.ISS_MAIL_FROM_ADDRESS'), __('iss::issNotify.teacherMail.fromName')),
            subject: __('iss::issNotify.teacherMail.mailHeader'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'iss::mail.teacherMail',
            with: [
                'signedUrl' => $this->signedUrl,
                'checkCode' => $this->checkCode,
                'checkedQuestions' => $this->checkedQuestions
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
