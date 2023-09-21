<?php

namespace App\Policies;

use App\Models\User;
use App\Models\classroom;
use Illuminate\Auth\Access\Response;

class ClassroomPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function view(User $user, Classroom $classroom): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Classroom $classroom): Response
    {
        // Only the user who created the classroom can update it
        return $user->id === $classroom->user_id
            ? Response::allow()
            : Response::deny('You are not authorized to update this classroom');
    }

    public function delete(User $user, Classroom $classroom): Response
    {
        // Only the user who created the classroom can delete it
        return $user->id === $classroom->user_id
            ? Response::allow()
            : Response::deny('You are not authorized to delete this classroom');
    }
    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, classroom $classroom): bool
    {
        return $user->classrooms()
            ->withoutGlobalScope(UserClassroomScope::class)
            ->wherePivot('classroom_id', '=', $classroom->id)
            ->wherePivot('role', '=', 'teacher')
            ->exists();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, classroom $classroom): bool
    {
        return $user->classrooms()
            ->withoutGlobalScope(UserClassroomScope::class)
            ->wherePivot('classroom_id', '=', $classroom->id)
            ->wherePivot('role', '=', 'teacher')
            ->exists();
    }
}
