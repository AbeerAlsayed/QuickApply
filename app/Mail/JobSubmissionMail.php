<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobSubmissionMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $messageContent;
    public string $position;
    public string $cvPath;

    public function __construct(string $message, string $position, string $cvPath)
    {
        $this->messageContent = $message;
        $this->position = $position;
        $this->cvPath = $cvPath;
    }

    public function build()
    {
        return $this->to('recipient@example.com') // استبدلها ببريد المستلم الفعلي
        ->subject("Job Application for {$this->position}")
            ->view('emails.job_submission')
            ->with([
                'messageContent' => $this->messageContent,
                'position' => $this->position,
            ])
            ->attach(storage_path("app/public/cvs/" . basename($this->cvPath)), [
                'as' => 'CV.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
