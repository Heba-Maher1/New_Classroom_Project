<?php

namespace App\Listeners;

use App\Models\Stream;
use App\Notifications\ClassworkUpdatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendClassworkUpdatedNotificationToAssignedStudent
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        Notification::send($event->classwork->users, new ClassworkUpdatedNotification($event->classwork));
    }
}
