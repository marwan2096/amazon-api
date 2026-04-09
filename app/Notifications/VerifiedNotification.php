<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifiedNotification extends Notification
{
    use Queueable;
     public $message;
    public $subject;
    public $fromEmail;
    public $mailer;
    /**

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        $this->message='verified';
        $this->subject='verified';
        $this->mailer='smtp';

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
            return (new MailMessage)
          ->subject($this->subject)
            ->greeting('Hello ' . $notifiable->first_name . '!')
            ->action('Visit', url('/'))
            ->salutation('Regards, Amazon')
            ->line($this->message)


            ->salutation('Regards, Amazon');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
