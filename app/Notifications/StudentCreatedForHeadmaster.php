<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;

class StudentCreatedForHeadmaster extends Notification implements ShouldQueue
{
    use Queueable;

    public $student;
    public $create_by_name;

    public function __construct($student, $create_by_name)
    {
        $this->student = $student;
        $this->create_by_name = $create_by_name;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Student Registered')
            ->greeting('Dear Headmaster,')
            ->line('A new student has been registered:')
            ->line('Name: ' . $this->student->name)
            ->line('Email: ' . $this->student->email)
            ->line('Phone: ' . $this->student->phone)
            ->line('Class: ' . $this->student->class . ' - Section: ' . $this->student->section)
            ->line('Created by: ' . $this->create_by_name)
            ->line('Thank you.');
    }
}

