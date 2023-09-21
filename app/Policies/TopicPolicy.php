<?php

namespace App\Policies;

use App\Models\Classroom;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TopicPolicy
{
    public function createTopic(User $user, Classroom $classroom): Response
    {
        // Retrieve the teacher (user) associated with the classroom
        $teacher = $classroom->teacher->first();

        // Check if the user is a teacher of the classroom
        return $teacher && $teacher->id === $user->id
            ? Response::allow()
            : Response::deny('Only the teacher of this classroom can create topics.');
    }

}