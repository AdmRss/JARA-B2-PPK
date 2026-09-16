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
        // Teman kamu mungkin pakai users() atau nama lain. Asumsi: users()
        $isMember = $taskList->users()->where('users.id', Auth::id())->exists();

        if (!$isMember) {
            abort(403, 'Anda bukan anggota dari Task List ini.');
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
        ], [
            'due_date.after_or_equal' => 'Batas waktu (Due date) tidak boleh di masa lalu.'
        ]);

        $taskList->tasks()->create($validated);

        return back()->with('success', 'Tugas berhasil ditambahkan!');
    }

    public function edit(Task $task)
    {
        $this->authorizeAccess($task->taskList);
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorizeAccess($task->taskList);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'priority' => 'required|in:low,mid,high',
            'due_date' => 'required|date|after_or_equal:today',
        ]);

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
