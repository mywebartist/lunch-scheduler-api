<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmailNotification extends Notification
{
//    use Queueable;

    protected string $login_code;

    public function __construct($login_code)
    {
        $this->login_code = $login_code;
    }


    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', route('verify_token', $this->login_code))
                    ->line('Thank you for using our application!');

//        return (new MailMessage)->view('welcome');
    }

}
