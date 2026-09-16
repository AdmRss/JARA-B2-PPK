<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;

class TaskPolicy
{
    /**
     * Bolehkah user melihat daftar task di list ini?
     * 🔗 Memanggil isMember() — dependency dari SRS-02 (Fahri)
     */
    public function viewAny(User $user, TaskList $taskList): bool
    {
        return $taskList->isMember($user->id);
    }

    /**
     * Bolehkah user membuat task baru di list ini?
     * 🔗 Memanggil isMember() — dependency dari SRS-02 (Fahri)
     */
    public function create(User $user, TaskList $taskList): bool
    {
        return $taskList->isMember($user->id);
    }

    /**
     * Bolehkah user melihat/mengedit task ini?
     * 🔗 Memanggil isMember() — dependency dari SRS-02 (Fahri)
     */
    public function view(User $user, Task $task): bool
    {
        return $task->taskList->isMember($user->id);
    }

    /**
     * Bolehkah user mengupdate task ini?
     * 🔗 Memanggil isMember() — dependency dari SRS-02 (Fahri)
     */
    public function update(User $user, Task $task): bool
    {
        return $task->taskList->isMember($user->id);
    }

    /**
     * Bolehkah user menghapus task ini?
     * 🔗 Memanggil isMember() — dependency dari SRS-02 (Fahri)
     */
    public function delete(User $user, Task $task): bool
    {
        return $task->taskList->isMember($user->id);
    }

    /**
     * Bolehkah user toggle status task ini?
     * 🔗 Memanggil isMember() — dependency dari SRS-02 (Fahri)
     */
    public function toggleStatus(User $user, Task $task): bool
    {
        return $task->taskList->isMember($user->id);
    }
}
