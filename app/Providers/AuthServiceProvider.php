<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Classroom;
use App\Models\Classwork;
use App\Models\Scopes\UserClassroomScope;
use App\Models\User;
use App\Policies\ClassroomPolicy;
use App\Policies\ClassworkPolicy;
use App\Policies\TopicPolicy;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Classroom::class => ClassroomPolicy::class,
        Classwork::class => ClassworkPolicy::class,
        Topic::class => TopicPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('createTopic', [TopicPolicy::class, 'createTopic']);

        Gate::define('submissions.create' , function(User $user , Classwork $classwork){
            $teacher =  $user->classrooms()
            ->withoutGlobalScope(UserClassroomScope::class)
            ->wherePivot('classroom_id' ,'=' ,$classwork->classroom_id)
            ->wherePivot('role' , '=' , 'teacher')
            ->exists();

            if($teacher){
                return false;
            }

            return $user->classworks()
            ->wherePivot('classwork_id' ,'=' , $classwork->id)
            ->exists();
        });
    }
}


