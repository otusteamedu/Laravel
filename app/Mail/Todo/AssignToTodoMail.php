<?php

namespace App\Mail\Todo;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Mail\Mailables\Envelope;
use App\Services\Repositories\DTOs\UserDTO;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\Repositories\DTOs\ProjectDTO;
use App\Services\Repositories\Todo\TodoFetchDTO;


class AssignToTodoMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     * @param UserDTO $user
     * @param ProjectDTO $project
     * @param TodoFetchDTO $todo
     * @param string $role
     */
    public function __construct(
        public UserDTO $user,
        public ProjectDTO $project,
        public TodoFetchDTO $todo,
        public string $role,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $from = Config::get('mail.from');

        return new Envelope(
            from: new Address($from['address'], $from['name']),
            subject: "Вас назначили на роль {$this->role} в задаче {$this->todo->title}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content('notifications.mail.assign-todo-role');
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
