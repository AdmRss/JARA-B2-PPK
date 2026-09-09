<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Menampilkan daftar tugas di dalam suatu list beserta progress bar (SRS-03 & SRS-04).
     */
    public function index(TaskList $taskList)
    {
        $tasks = $taskList->tasks()->orderBy('status', 'asc')->orderBy('due_date', 'asc')->get();
        $totalTasks = $tasks->count();
        $completedTasks = $tasks->where('status', true)->count();
        $progress = $taskList->progressPercentage();

        return view('tasks.index', compact('taskList', 'tasks', 'totalTasks', 'completedTasks', 'progress'));
    }

    /**
     * Menyimpan tugas baru ke dalam task list (SRS-03).
     */
    public function store(Request $request, TaskList $taskList)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'priority' => 'required|in:low,mid,high',
            'due_date' => 'nullable|date|after_or_equal:today',
        ], [
            'title.required' => 'Judul tugas wajib diisi.',
            'priority.required' => 'Prioritas wajib dipilih.',
            'priority.in' => 'Prioritas harus salah satu dari: Low, Mid, High.',
            'due_date.after_or_equal' => 'Tenggat waktu tidak boleh tanggal yang sudah lewat.',
        ]);

        $taskList->tasks()->create([
            'title' => $validated['title'],
            'priority' => $validated['priority'],
            'due_date' => $validated['due_date'] ?? null,
            'status' => false,
        ]);

        return redirect()->route('tasks.index', $taskList->id)->with('success', 'Tugas baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit tugas (SRS-03).
     */
    public function edit(Task $task)
    {
        $taskList = $task->taskList;
        return view('tasks.edit', compact('task', 'taskList'));
    }

    /**
     * Memperbarui data tugas (SRS-03).
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'priority' => 'required|in:low,mid,high',
            'due_date' => 'nullable|date',
        ], [
            'title.required' => 'Judul tugas wajib diisi.',
            'priority.required' => 'Prioritas wajib dipilih.',
            'priority.in' => 'Prioritas harus salah satu dari: Low, Mid, High.',
        ]);

        $task->update([
            'title' => $validated['title'],
            'priority' => $validated['priority'],
            'due_date' => $validated['due_date'] ?? null,
        ]);

        return redirect()->route('tasks.index', $task->task_list_id)->with('success', 'Tugas berhasil diperbarui!');
    }

    /**
     * Menghapus tugas dari task list (SRS-03).
     */
    public function destroy(Task $task)
    {
        $taskListId = $task->task_list_id;
        $task->delete();

        return redirect()->route('tasks.index', $taskListId)->with('success', 'Tugas berhasil dihapus!');
    }

    /**
     * Toggle status tugas (pending <-> done) dan hitung ulang progress (SRS-04).
     */
    public function toggleStatus(Task $task)
    {
        $task->status = !$task->status;
        $task->save();

        $statusText = $task->status ? 'selesai' : 'belum selesai';
        return back()->with('success', "Status tugas diubah menjadi {$statusText}.");
    }
}
