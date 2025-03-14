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
        $cvFullPath = public_path("storage/" . str_replace('\\', '/', $this->cvPath));

        if (file_exists($cvFullPath)) {
            return (new MailMessage)
                ->subject("Job Application for {$this->position}")
                ->line($this->message)
                ->line("Please find the attached CV.")
                ->attach($cvFullPath);
        } else {
            Log::error("CV file not found at: " . $cvFullPath);
            return (new MailMessage)
                ->subject("Job Application for {$this->position}")
                ->line($this->message)
                ->line("CV file is missing.");
        }

    }
}
