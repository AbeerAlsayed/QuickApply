<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class JobSubmissionNotification extends Notification
{
    use Queueable;

    public $description;
    public $position;
    public $cvPath;

    public function __construct($description, $position, $cvPath)
    {
        $this->description = $description;
        $this->position = $position;
        $this->cvPath = $cvPath;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail']; // لتحديد القناة التي سيتم الإرسال عبرها، في هذه الحالة البريد الإلكتروني
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->position) // البوزيشن سيكون عنوان الإيميل
            ->greeting('Dear ' . $notifiable->name)
            ->line($this->description)
            ->line('You can download your CV here:')
            ->action('Download CV', url('storage/' . $this->cvPath));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'description' => $this->description,
            'position' => $this->position,
            'cvPath' => $this->cvPath,
        ];
    }
}
