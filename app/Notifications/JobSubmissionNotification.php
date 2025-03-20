<?php


namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;

class JobSubmissionNotification extends Notification
{
    use Queueable;

    protected string $message;
    protected string $position;
    protected string $cvPath;

    public function __construct(string $message, string $position, string $cvPath)
    {
        $this->message = $message;
        $this->position = $position;
        $this->cvPath = $cvPath;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $cvPath = 'cvs/' . basename($this->cvPath);
        $cvStoragePath = storage_path("app/public/{$cvPath}");

        Log::info("Checking CV at: " . $cvStoragePath);

        if (file_exists($cvStoragePath)) {
            return (new MailMessage)
                ->subject("Job Application for {$this->position}")
                ->line($this->message)
                ->attach($cvStoragePath, [
                    'as' => 'CV.pdf',
                    'mime' => 'application/pdf'
                ]);
        } else {
            Log::error("CV file not found at: " . $cvStoragePath);
            return (new MailMessage)
                ->subject("Job Application for {$this->position}")
                ->line($this->message)
                ->line("CV file is missing.");
        }
    }


}
