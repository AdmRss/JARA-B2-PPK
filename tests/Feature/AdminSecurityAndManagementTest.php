<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TaskList;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class AdminSecurityAndManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test 1: Middleware memblokir user yang belum login (unauthenticated).
     */
    public function test_guest_cannot_access_admin_routes(): void
    {
        $response = $this->get('/admin/users-management');
        $response->assertStatus(403);
    }

    /**
     * Test 2: Middleware memblokir user biasa (role = user).
     */
    public function test_regular_user_cannot_access_admin_routes(): void
    {
        $regularUser = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this->actingAs($regularUser)->get('/admin/users-management');
        $response->assertStatus(403);
    }

    /**
     * Test 3: Middleware mengizinkan Admin (role = admin).
     */
    public function test_admin_can_access_admin_users_index_and_create(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $indexResponse = $this->actingAs($admin)->get('/admin/users-management');
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Manajemen Pengguna (SRS-01)');

        $createResponse = $this->actingAs($admin)->get('/admin/users/create');
        $createResponse->assertStatus(200);
        $createResponse->assertSee('Tambah Akun Pengguna Baru');
    }

    /**
     * Test 4: Admin dapat menambahkan user baru dengan validasi.
     */
    public function test_admin_can_store_new_user(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $postData = [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => 'secret12345',
            'password_confirmation' => 'secret12345',
            'role' => 'user',
        ];

        $response = $this->actingAs($admin)->post('/admin/users-management', $postData);
        $response->assertRedirect('/admin/users-management');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'budi@example.com',
            'name' => 'Budi Santoso',
            'role' => 'user',
        ]);
    }

    /**
     * Test 5: Atomic Delete Operation - Menghapus user beserta record pivot kolaborasi (task_list_user).
     */
    public function test_atomic_delete_removes_user_and_pivot_records(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $owner = User::factory()->create(['role' => 'user']);
        $collaborator = User::factory()->create(['role' => 'user']);

        // Buat TaskList milik owner
        $taskList = TaskList::create([
            'name' => 'Proyek Website',
            'description' => 'List proyek kolaboratif',
            'owner_id' => $owner->id,
        ]);

        // Masukkan collaborator ke pivot task_list_user
        $taskList->collaborators()->attach($collaborator->id);

        $this->assertDatabaseHas('task_list_user', [
            'task_list_id' => $taskList->id,
            'user_id' => $collaborator->id,
        ]);

        // Hapus collaborator oleh Admin
        $response = $this->actingAs($admin)->delete("/admin/users-management/{$collaborator->id}");
        $response->assertRedirect('/admin/users-management');
        $response->assertSessionHas('success');

        // Pastikan pivot task_list_user dan user terhapus secara atomik
        $this->assertDatabaseMissing('task_list_user', [
            'user_id' => $collaborator->id,
        ]);
        $this->assertDatabaseMissing('users', [
            'id' => $collaborator->id,
        ]);
    }

    /**
     * Test 6: Policy Checks (ListPolicy & TaskPolicy).
     */
    public function test_policies_enforce_correct_access_rules(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $owner = User::factory()->create(['role' => 'user']);
        $collaborator = User::factory()->create(['role' => 'user']);
        $stranger = User::factory()->create(['role' => 'user']);

        $taskList = TaskList::create([
            'name' => 'List Rahasia',
            'owner_id' => $owner->id,
        ]);
        $taskList->collaborators()->attach($collaborator->id);

        $task = Task::create([
            'task_list_id' => $taskList->id,
            'title' => 'Kerjakan modul login',
            'priority' => 'high',
        ]);

        // ListPolicy Checks
        $this->assertTrue(Gate::forUser($admin)->allows('view', $taskList));
        $this->assertTrue(Gate::forUser($owner)->allows('view', $taskList));
        $this->assertTrue(Gate::forUser($collaborator)->allows('view', $taskList));
        $this->assertFalse(Gate::forUser($stranger)->allows('view', $taskList));

        $this->assertTrue(Gate::forUser($admin)->allows('update', $taskList));
        $this->assertTrue(Gate::forUser($owner)->allows('update', $taskList));
        $this->assertFalse(Gate::forUser($collaborator)->allows('update', $taskList)); // Collaborator cannot edit list attributes
        $this->assertFalse(Gate::forUser($stranger)->allows('update', $taskList));

        // TaskPolicy Checks
        $this->assertTrue(Gate::forUser($admin)->allows('view', $task));
        $this->assertTrue(Gate::forUser($owner)->allows('view', $task));
        $this->assertTrue(Gate::forUser($collaborator)->allows('view', $task));
        $this->assertFalse(Gate::forUser($stranger)->allows('view', $task));

        $this->assertTrue(Gate::forUser($collaborator)->allows('update', $task));
        $this->assertTrue(Gate::forUser($collaborator)->allows('toggleStatus', $task));
        $this->assertFalse(Gate::forUser($collaborator)->allows('delete', $task)); // Only owner or admin can delete task
        $this->assertTrue(Gate::forUser($owner)->allows('delete', $task));
    }
}
