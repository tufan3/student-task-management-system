<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;

class StudentWelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $plainPassword;

    public function __construct($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Welcome to the School!')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your student account has been successfully created.')
            ->line('Please use the following credentials to login:')
            ->line('Email: ' . $notifiable->email)
            ->line('Password: ' . $this->plainPassword)
            ->line('We’re happy to have you here!')
            ->line('Thank you.');
    }
}


