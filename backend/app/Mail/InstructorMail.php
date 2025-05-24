<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InstructorMail extends Mailable
{
    use Queueable, SerializesModels;

    public $full_name;
    public $deadline;
    public $subjects;
    public $instructor_id;

    public function __construct(array $data)
    {
        $this->full_name = $data['full_name'];
        $this->deadline = $data['deadline'];
        $this->subjects = $data['subjects'];
        $this->instructor_id = $data['instructor_id'];
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Grade Submission Reminder',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.mail',  // This view must exist
            with: [
                'full_name' => $this->full_name,
                'subjects' => $this->subjects,
                'deadline' => $this->deadline,
                'instructor_id' => $this->instructor_id,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

