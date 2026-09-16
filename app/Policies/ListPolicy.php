<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TaskList;
use Illuminate\Auth\Access\HandlesAuthorization;

class ListPolicy
{
    use HandlesAuthorization;

    /**
     * Bypassing untuk Admin: Administrator memiliki akses penuh ke seluruh list (Super Admin).
     */
    public function before(User $user, string $ability): ?bool
    {
        if ((isset($user->role) && $user->role === 'admin') || (isset($user->is_admin) && $user->is_admin)) {
            return true;
        }

        return null;
    }

    /**
     * Menentukan apakah user boleh melihat daftar task list.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Menentukan apakah user boleh melihat detail sebuah TaskList.
     * Hak akses: Owner list atau Collaborator yang terdaftar di task_list_user.
     */
    public function view(User $user, TaskList $taskList): bool
    {
        if ($user->id === $taskList->owner_id) {
            return true;
        }

        return $taskList->collaborators()->where('users.id', $user->id)->exists();
    }

    /**
     * Menentukan apakah user boleh membuat TaskList baru.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Menentukan apakah user boleh mengedit/memperbarui TaskList.
     * Sesuai design.md: Hanya Owner yang berhak mengedit atribut list.
     */
    public function update(User $user, TaskList $taskList): bool
    {
        return $user->id === $taskList->owner_id;
    }

    /**
     * Menentukan apakah user boleh menghapus TaskList.
     * Sesuai design.md: Hanya Owner yang berhak menghapus list miliknya.
     */
    public function delete(User $user, TaskList $taskList): bool
    {
        return $user->id === $taskList->owner_id;
    }

    /**
     * Menentukan apakah user boleh mengelola (manage) TaskList secara umum.
     */
    public function manage(User $user, TaskList $taskList): bool
    {
        return $user->id === $taskList->owner_id;
    }

    /**
     * Menentukan apakah user boleh menambahkan kolaborator ke dalam TaskList.
     * Sesuai design.md: Hanya Owner yang berhak mengelola collaborator.
     */
    public function addCollaborator(User $user, TaskList $taskList): bool
    {
        return $user->id === $taskList->owner_id;
    }
}
