<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole('manager');
    }

    public function update(User $user, Task $task): bool
    {
        return $user->hasRole('manager') || $user->id === $task->user_id;
    }

    public function view(User $user, Task $task): bool
    {
        return $user->hasRole('manager') || $user->id === $task->user_id;
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->hasRole('manager');
    }
}
