<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $task;

    public function __construct($task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $isHeadmaster = $notifiable->role === 'headmaster';
        $isTeacher = $notifiable->role === 'teacher';
        $isStudent = $notifiable->role === 'student';

        $mail = (new MailMessage)
            ->subject('Task Approved');

        if ($isHeadmaster) {
            $mail->greeting('Hello Headmaster,');
        } else if ($isTeacher) {
            $mail->greeting('Hello ' . $this->task->teacher->name . ',');
        } else if ($isStudent) {
            $mail->greeting('Hello ' . $notifiable->name . ',');
        }

        $mail->line('Task has been successfully approved.')
            ->line('Thank you.');

        return $mail;
    }
}