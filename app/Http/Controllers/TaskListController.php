<?php

namespace App\Http\Controllers;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskListController extends Controller
{
    public function index() {
        // Asumsi user ID 1 sedang login (karena belum ada sistem auth utuh)
        $userId = 1; 
        $lists = TaskList::with('collaborators')
            ->where('owner_id', $userId)
            ->orWhereHas('collaborators', function ($query) use ($userId) {
                $query->where('users.id', $userId);
            })
            ->get();
        $users = User::where('id', '!=', $userId)->get(); // Untuk dropdown tambah anggota
        
        return view('tasklists.index', compact('lists', 'users', 'userId'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        TaskList::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'owner_id' => 1 // Hardcode sementara untuk testing
        ]);
        return back();
    }

    public function addCollaborator(Request $request, TaskList $taskList) {
        $userId = 1;

        abort_unless($taskList->isOwner($userId), 403);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Attach user ke pivot table
        $taskList->collaborators()->syncWithoutDetaching([$validated['user_id']]);
        return back();
    }

    public function destroy(TaskList $taskList) {
        $userId = 1;

        abort_unless($taskList->isOwner($userId), 403);

        DB::transaction(function () use ($taskList) {
            $taskList->collaborators()->detach();
            $taskList->tasks()->get()->each->deleteWithAssignees();
            $taskList->delete();
        });

        return redirect('/lists');
    }
}
