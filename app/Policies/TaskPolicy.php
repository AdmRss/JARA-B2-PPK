<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;

class TaskPolicy
{
    /**
     * Bypassing untuk Admin
     */
    public function before(User $user, string $ability): ?bool
    {
        if ((isset($user->role) && $user->role === 'admin') || (isset($user->is_admin) && $user->is_admin)) {
            return true;
        }

        return null;
    }

    /**
     * Bolehkah user melihat daftar task di list ini?
     */
    public function viewAny(User $user, TaskList $taskList): bool
    {
        return $taskList->isMember($user->id);
    }

    /**
     * Bolehkah user membuat task baru di list ini?
     */
    public function create(User $user, TaskList $taskList): bool
    {
        return $taskList->isMember($user->id);
    }

    /**
     * Bolehkah user melihat/mengedit task ini?
     */
    public function view(User $user, Task $task): bool
    {
        return $task->taskList->isMember($user->id);
    }

    /**
     * Bolehkah user mengupdate task ini?
     */
    public function update(User $user, Task $task): bool
    {
        return $task->taskList->isMember($user->id);
    }

    /**
     * Bolehkah user menghapus task ini?
     */
    public function delete(User $user, Task $task): bool
    {
        return $task->taskList->isMember($user->id);
    }

    /**
     * Bolehkah user toggle status task ini?
     */
    public function toggleStatus(User $user, Task $task): bool
    {
        return $task->taskList->isMember($user->id);
    }
}
