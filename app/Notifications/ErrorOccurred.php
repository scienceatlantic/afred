<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ErrorOccurred extends Notification
{
    use Queueable;

    public $exceptionMessage;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($exceptionMessage)
    {
        $this->exceptionMessage = $exceptionMessage;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
                    ->error()
                    ->subject('AFRED | API Exception!')
                    ->greeting('AHHHHHHHHHHHHHHHHHHH!')
                    ->line('Error message:')
                    ->line($this->exceptionMessage);
    }
}
