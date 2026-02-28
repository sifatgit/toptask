<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Task;

class TaskPolicy
{
    /**
     * Determine whether the given task can be viewed by the user.
     * Only the owner of the task is allowed to access it.
     */
    public function view(User $user, Task $task): bool
    {
        return $task->user_id === $user->id;
    }

    /**
     * Determine whether the given task can be updated by the user.
     * Restricts modifications to the task owner.
     */
    public function update(User $user, Task $task): bool
    {
        return $task->user_id === $user->id;
    }

    /**
     * Determine whether the given task can be deleted by the user.
     * Ensures only the owner can remove the task.
     */
    public function delete(User $user, Task $task): bool
    {
        return $task->user_id === $user->id;
    }
}
