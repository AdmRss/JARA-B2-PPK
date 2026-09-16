<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function index(TaskList $taskList)
    {
        $this->authorize('viewAny', [Task::class, $taskList]);

        // Eager-load tasks.assignedUsers
        $tasks = $taskList->tasks()->with('assignedUsers')->latest()->get();
        $members = $taskList->allMembers();
        
        $progress = $taskList->progressPercentage();
        $totalTasks = $taskList->tasks()->count();
        $completedTasks = $taskList->tasks()->where('status', true)->count();
        
        return view('tasks.index', compact('taskList', 'tasks', 'members', 'progress', 'totalTasks', 'completedTasks'));
    }

    public function store(Request $request, TaskList $taskList)
    {
        $this->authorize('create', [Task::class, $taskList]);

        $memberIds = $taskList->allMembers()->pluck('id')->toArray();

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'priority'    => 'required|in:low,mid,high',
            'due_date'    => 'nullable|date|after_or_equal:today',
            'assignees'   => 'nullable|array',
            'assignees.*' => ['integer', Rule::in($memberIds)],
        ], [
            'title.required'          => 'Judul tugas wajib diisi.',
            'priority.required'       => 'Prioritas wajib dipilih.',
            'priority.in'             => 'Prioritas harus salah satu dari: low, mid, high.',
            'due_date.after_or_equal' => 'Tenggat waktu tidak boleh tanggal yang sudah lewat.',
            'assignees.*.in'          => 'User yang dipilih bukan anggota Daftar Tugas ini.',
        ]);

        DB::transaction(function () use ($taskList, $validated) {
            $task = $taskList->tasks()->create([
                'title'    => $validated['title'],
                'priority' => $validated['priority'],
                'due_date' => $validated['due_date'] ?? null,
                'status'   => false,
            ]);

            if (!empty($validated['assignees'])) {
                $task->assignedUsers()->sync($validated['assignees']);
            }
        });

        return back()->with('success', 'Tugas baru berhasil ditambahkan!');
    }

    public function edit(Task $task)
    {
        $this->authorize('view', $task);
        
        $task->load('assignedUsers');
        $taskList = $task->taskList;
        $members = $taskList->allMembers();

        return view('tasks.edit', compact('task', 'taskList', 'members'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $taskList = $task->taskList;
        $memberIds = $taskList->allMembers()->pluck('id')->toArray();

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'priority'    => 'required|in:low,mid,high',
            'due_date'    => 'nullable|date|after_or_equal:today',
            'assignees'   => 'nullable|array',
            'assignees.*' => ['integer', Rule::in($memberIds)],
        ], [
            'assignees.*.in' => 'User yang dipilih bukan anggota Daftar Tugas ini.',
        ]);

        DB::transaction(function () use ($task, $validated) {
            $task->update([
                'title'    => $validated['title'],
                'priority' => $validated['priority'],
                'due_date' => $validated['due_date'] ?? null,
            ]);

            if (isset($validated['assignees'])) {
                $task->assignedUsers()->sync($validated['assignees']);
            } else {
                $task->assignedUsers()->detach();
            }
        });

        return back()->with('success', 'Tugas berhasil diperbarui!');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        DB::transaction(function () use ($task) {
            $task->deleteWithAssignees();
        });

        return back()->with('success', 'Tugas berhasil dihapus!');
    }

    public function toggleStatus(Task $task)
    {
        $this->authorize('toggleStatus', $task);

        $task->update([
            'status' => !$task->status
        ]);

        return back()->with('success', 'Status tugas diperbarui!');
    }
}
