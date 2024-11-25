<?php


namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class JobSubmissionNotification extends Notification
{
    use Queueable;

    protected $description;
    protected $position;
    protected $cvPath;

    public function __construct($description, $position, $cvPath)
    {
        $this->description = $description;
        $this->position = $position;
        $this->cvPath = $cvPath;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Job Application for {$this->position}")
            ->line($this->description)
            ->line('Please find the attached CV.')
            ->attach(storage_path("app/public/{$this->cvPath}"));
    }
}
