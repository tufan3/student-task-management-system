<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Announcement;
use Illuminate\Support\Facades\Log;

class AnnouncementNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $announcement;

    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Announcement')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('There is a new announcement.')
            ->line('Title: ' . $this->announcement->title)
            ->line('Description: ' . $this->announcement->description)
            ->line('See the image below:')
            ->line('<img src="' . asset('storage/' . $this->announcement->image) . '" alt="Announcement Image" style="width:120px;">')
            ->action('View Announcement', url('/announcements/' . $this->announcement->id))
            ->line('Thank you.')
            ->markdown('emails.announcement');
    }


    public function toDatabase($notifiable)
    {
        return [
            'announcement_id' => $this->announcement->id,
            'title' => $this->announcement->title,
            'description' => $this->announcement->description,
            'image' => $this->announcement->resized_image_path, // or original_image_path
            'sent_at' => now(),
        ];
    }
}
