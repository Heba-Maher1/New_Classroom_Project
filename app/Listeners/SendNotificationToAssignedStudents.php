<?php

namespace App\Listeners;

use App\Events\ClassworkCreated;
use App\Models\User;
use App\Notifications\NewClassworkNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendNotificationToAssignedStudents
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
    public function handle(ClassworkCreated $event): void
    {
        // foreach($event->Classwork->users as $user){
        //   $user->notify(new NewClassworkNotification($event->classwork));  
        // }
        
        // instead of using foreach , we use Notification facade class that take two argument , the users that we want to sebd notif , and the notification class

        // Notification::send($event->classwork->users, new NewClassworkNotification($event->classwork));
    }
}
