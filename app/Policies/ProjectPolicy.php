<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Determine whether the given project can be viewed by the user.
     * Only the owner of the project is allowed to access it.
     */
    public function view(User $user, Project $project): bool
    {
        return $project->user_id === $user->id;
    }

    /**
     * Determine whether the given project can be updated by the user.
     * Restricts modifications to the project owner.
     */
    public function update(User $user, Project $project): bool
    {
        return $project->user_id === $user->id;
    }

    /**
     * Determine whether the given project can be deleted by the user.
     * Ensures only the project owner can remove it.
     */
    public function delete(User $user, Project $project): bool
    {
        return $project->user_id === $user->id;
    }
}
