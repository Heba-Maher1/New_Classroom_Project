<?php

namespace App\Providers;

use App\Events\ClassworkCreated;
use App\Events\classworkUpdated;
use App\Listeners\PostInClassroomStream;
use App\Listeners\SendClassworkUpdatedNotificationToAssignedStudent;
use App\Listeners\SendNotificationToAssignedStudents;
use App\Models\Classroom;
use App\Observers\ClassroomObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ClassworkCreated::class => [
            PostInClassroomStream::class,
            SendNotificationToAssignedStudents::class,
        ],
        classworkUpdated::class => [
            SendClassworkUpdatedNotificationToAssignedStudent::class,
        ]
    ];

    // protected $observers = [
    //     Classroom::class => ClassroomObserver::class,
    // ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // Classroom::observe(ClassroomObserver::class);

        // Event::listen('classwork.created' //the event ,[new PostInClassroomStream , 'handle'] // the listener with its handle method that conatin the logic); if i have many listeners to this event , repeat this statment count of listeners

        // Event::listen('classwork.created' , function($classroom , $classwork){
        //     // the logic
        // });
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
