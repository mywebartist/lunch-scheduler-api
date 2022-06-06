<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginSecurityCodeNotification extends Notification
{
//    use Queueable;

    protected string $pin;

    public function __construct($pin)
    {
        $this->pin = $pin;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Here is your login pin to Lunch Scheduler.')
                    ->line($this->pin)
//                    ->action('Login', route('verify_token', $this->login_code))
                    ->line('Thank you for using our application!');
    }

}
