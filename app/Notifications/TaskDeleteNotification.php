<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskDeleteNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $task;
    public $student;
    public $createdBy;
    public function __construct($createdBy, $student, $task)
    {
        $this->task = $task;
        $this->student = $student;
        $this->createdBy = $createdBy;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $isHeadmaster = $notifiable->role === 'headmaster';

        $mail = (new MailMessage)
            ->subject('Task Deleted');

        if ($isHeadmaster) {
            $mail->greeting('Hello Headmaster,');
        } else {
            $mail->greeting('Hello ' . $this->createdBy->name . ',');
        }

        $mail->line('Task has been successfully deleted.')
            ->line('Thank you.');

        return $mail;
    }
}
