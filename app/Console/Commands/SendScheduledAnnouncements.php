<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Announcement;
use App\Notifications\AnnouncementNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\User;

class SendScheduledAnnouncements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:send-scheduled-announcements';
    protected $signature = 'send:scheduled-announcements';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $announcements = Announcement::where('scheduled_at', '<=', now())->where('sent', false)->get();

        foreach ($announcements as $announcement) {
            $students = User::where('role', 'student')->get();

            Notification::send($students, new AnnouncementNotification($announcement));
            // $students->notify(new AnnouncementNotification($announcement));

            $announcement->update(['sent' => true]);
        }
    }

}
