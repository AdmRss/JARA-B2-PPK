<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;

class TaskPolicy
{
    /**
     * Bypassing untuk sementara karena sistem Auth belum ada,
     * jadi SEMUA tamu (guest) maupun user yang login diizinkan mengakses fitur ini.
     */

    public function viewAny(?User $user, TaskList $taskList): bool
    {
        return true;
    }

    public function create(?User $user, TaskList $taskList): bool
    {
        return true;
    }

    public function view(?User $user, Task $task): bool
    {
        return true;
    }

    public function update(?User $user, Task $task): bool
    {
        return true;
    }

    public function delete(?User $user, Task $task): bool
    {
        return true;
    }

    public function toggleStatus(?User $user, Task $task): bool
    {
        return true;
    }
}
