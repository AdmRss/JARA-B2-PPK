<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\TaskList;
use Illuminate\Database\Seeder;

class DevSeeder extends Seeder
{
    public function run(): void
    {
        $u1 = User::factory()->create(['name' => 'Owner (User 1)', 'email' => 'owner@test.com']);
        $u2 = User::factory()->create(['name' => 'Collaborator (User 2)', 'email' => 'collab@test.com']);
        $u3 = User::factory()->create(['name' => 'Other (User 3)', 'email' => 'other@test.com']);
        
        $list = TaskList::create(['name' => 'Proyek Alpha', 'description' => 'Test List', 'owner_id' => $u1->id]);
        $list->collaborators()->attach($u2->id);
    }
}
