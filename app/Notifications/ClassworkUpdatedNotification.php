<?php

namespace App\Notifications;

use App\Models\Classwork;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClassworkUpdatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Classwork $classwork)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail' , 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $classwork = $this->classwork;

        $content =__(':name updated :type: :title' , [
            'name'=> $classwork->user->name,
            'type' => __($classwork->type->value),
            'title' => $classwork->title,
        ]);

        return (new MailMessage)
                    ->subject(__('Update :title' , [
                        'type' =>  $classwork->type->value,
                         'title' => $classwork->title,

                    ]))
                    ->greeting(__('Hi :name' , [
                        'name' => $notifiable->name
                    ]))    
                    ->line($content)
                    ->action("Go to classwork to see what's new", route('classrooms.classworks.show' , [$classwork->classroom_id , $classwork->id]))
                    ->line('Thank you for using our application');
    }

    public function toDatabase(object $notifiable): DatabaseMessage
    {
        $classwork = $this->classwork;

        $content =__(':name updated :type: :title' , [
            'name'=> $classwork->user->name,
            'type' => __($classwork->type->value),
            'title' => $classwork->title,
        ]);

        return new DatabaseMessage([
            'title' => __('New :type' , [
                'type' =>  $classwork->type->value
            ]),
            'body' => $content,
            'link' => route('classrooms.classworks.show' , [$classwork->classroom_id , $classwork->id]),
            'classwork' => $classwork->id,
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
