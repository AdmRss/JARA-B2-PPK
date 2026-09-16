<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Bypassing untuk Admin: Administrator memiliki akses penuh ke seluruh task.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ((isset($user->role) && $user->role === 'admin') || (isset($user->is_admin) && $user->is_admin)) {
            return true;
        }

        return null;
    }

    /**
     * Menentukan apakah user boleh melihat daftar task.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Menentukan apakah user boleh melihat task tertentu.
     * Hak akses: Owner list induk atau Collaborator list induk.
     */
    public function view(User $user, Task $task): bool
    {
        $taskList = $task->taskList;
        if (!$taskList) {
            return false;
        }

        return $user->id === $taskList->owner_id ||
               $taskList->collaborators()->where('users.id', $user->id)->exists();
    }

    /**
     * Menentukan apakah user boleh membuat task baru pada sebuah TaskList.
     * Hak akses: Owner list induk atau Collaborator list induk.
     */
    public function create(User $user, ?TaskList $taskList = null): bool
    {
        if (!$taskList) {
            return true;
        }

        return $user->id === $taskList->owner_id ||
               $taskList->collaborators()->where('users.id', $user->id)->exists();
    }

    /**
     * Menentukan apakah user boleh memperbarui/mengedit task.
     * Sesuai design.md: Collaborator & Owner dapat berinteraksi dengan tugas (edit).
     */
    public function update(User $user, Task $task): bool
    {
        $taskList = $task->taskList;
        if (!$taskList) {
            return false;
        }

        return $user->id === $taskList->owner_id ||
               $taskList->collaborators()->where('users.id', $user->id)->exists();
    }

    /**
     * Menentukan apakah user boleh mengubah status task (toggle pending <-> done).
     * Sesuai design.md: Collaborator & Owner dapat mengubah status tugas.
     */
    public function toggleStatus(User $user, Task $task): bool
    {
        $taskList = $task->taskList;
        if (!$taskList) {
            return false;
        }

        return $user->id === $taskList->owner_id ||
               $taskList->collaborators()->where('users.id', $user->id)->exists();
    }

    /**
     * Menentukan apakah user boleh menghapus task.
     * Hak akses: Hanya Owner dari task list (atau Admin).
     */
    public function delete(User $user, Task $task): bool
    {
        $taskList = $task->taskList;
        if (!$taskList) {
            return false;
        }

        return $user->id === $taskList->owner_id;
    }

    /**
     * Menentukan apakah user boleh mengelola task secara penuh.
     */
    public function manage(User $user, Task $task): bool
    {
        $taskList = $task->taskList;
        if (!$taskList) {
            return false;
        }

        return $user->id === $taskList->owner_id;
    }
}
