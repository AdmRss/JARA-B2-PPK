<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    private function authorizeAccess(TaskList $taskList)
    {
        if (!Auth::check()) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        $userId = Auth::id();
        $isMember = $taskList->isMember($userId);

        if (!$isMember) {
            // Memberikan pesan error spesifik jika 403
            abort(403, "Anda (User ID: $userId) bukan pembuat maupun kolaborator dari Task List ini (Owner ID: $taskList->owner_id).");
        }
    }

    public function index(TaskList $taskList)
    {
        $this->authorizeAccess($taskList);
        $tasks = $taskList->tasks()->latest()->get();
        return view('tasks.index', compact('taskList', 'tasks'));
    }

    public function store(Request $request, TaskList $taskList)
    {
        $this->authorizeAccess($taskList);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'priority' => 'required|in:low,mid,high',
            'due_date' => 'required|date|after_or_equal:today',
            'assignee_id' => 'nullable|exists:users,id',
        ], [
            'due_date.after_or_equal' => 'Batas waktu (Due date) tidak boleh di masa lalu.'
        ]);

        if (!empty($validated['assignee_id'])) {
            if (!$taskList->isMember($validated['assignee_id'])) {
                return back()->withErrors(['assignee_id' => 'Anggota yang ditugaskan (Assignee) harus merupakan anggota dari Task List ini.']);
            }
        }

        $taskList->tasks()->create($validated);

        return back()->with('success', 'Tugas berhasil ditambahkan!');
    }

    public function edit(Task $task)
    {
        $this->authorizeAccess($task->taskList);
        $taskList = $task->taskList;
        return view('tasks.edit', compact('task', 'taskList'));
    }

    public function update(Request $request, Task $task)
    {
        $taskList = $task->taskList;
        $this->authorizeAccess($taskList);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'priority' => 'required|in:low,mid,high',
            'due_date' => 'required|date|after_or_equal:today',
            'assignee_id' => 'nullable|exists:users,id',
        ]);

        if (!empty($validated['assignee_id'])) {
            if (!$taskList->isMember($validated['assignee_id'])) {
                return back()->withErrors(['assignee_id' => 'Anggota yang ditugaskan (Assignee) harus merupakan anggota dari Task List ini.']);
            }
        }

        $task->update($validated);

        return back()->with('success', 'Tugas berhasil diperbarui!');
    }

    public function destroy(Task $task)
    {
        $this->authorizeAccess($task->taskList);
        $task->delete();
        return back()->with('success', 'Tugas berhasil dihapus!');
    }

    public function toggleStatus(Task $task)
    {
        $this->authorizeAccess($task->taskList);

        $task->update([
            'status' => !$task->status
        ]);

        return back()->with('success', 'Status tugas diperbarui!');
    }
}
