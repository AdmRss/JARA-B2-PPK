<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * Menampilkan daftar tugas di dalam suatu list beserta progress bar (SRS-03 & SRS-04).
     */
    public function index(TaskList $taskList)
    {
        // --- Otorisasi (🔗 memanggil isMember dari SRS-02) ---
        Gate::authorize('viewAny', [Task::class, $taskList]);

        $tasks = $taskList->tasks()->with('assignedUsers')
            ->orderBy('status', 'asc')
            ->orderBy('due_date', 'asc')
            ->get();

        $totalTasks = $tasks->count();
        $completedTasks = $tasks->where('status', true)->count();
        $progress = $taskList->progressPercentage();
        $members = $taskList->allMembers();

        return view('tasks.index', compact('taskList', 'tasks', 'totalTasks', 'completedTasks', 'progress', 'members'));
    }

    /**
     * Menyimpan tugas baru ke dalam task list (SRS-03).
     */
    public function store(Request $request, TaskList $taskList)
    {
        // --- Otorisasi (🔗 memanggil isMember dari SRS-02) ---
        Gate::authorize('create', [Task::class, $taskList]);

        // --- Validasi (assignees di-scope ke anggota list) ---
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
            'priority.in'             => 'Prioritas harus salah satu dari: Low, Mid, High.',
            'due_date.after_or_equal' => 'Tenggat waktu tidak boleh tanggal yang sudah lewat.',
            'assignees.*.in'          => 'User yang dipilih bukan anggota Daftar Tugas ini.',
        ]);

        // --- Transaction: create task + sync assignees ---
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

        return redirect()->route('tasks.index', $taskList->id)->with('success', 'Tugas baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit tugas (SRS-03).
     */
    public function edit(Task $task)
    {
        // --- Otorisasi (🔗 memanggil isMember dari SRS-02) ---
        Gate::authorize('view', $task);

        $taskList = $task->taskList;
        $task->load('assignedUsers');
        $members = $taskList->allMembers();

        return view('tasks.edit', compact('task', 'taskList', 'members'));
    }

    /**
     * Memperbarui data tugas (SRS-03).
     */
    public function update(Request $request, Task $task)
    {
        // --- Otorisasi (🔗 memanggil isMember dari SRS-02) ---
        Gate::authorize('update', $task);

        $taskList = $task->taskList;

        // --- Validasi (assignees di-scope ke anggota list) ---
        $memberIds = $taskList->allMembers()->pluck('id')->toArray();

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'priority'    => 'required|in:low,mid,high',
            'due_date'    => 'nullable|date',
            'assignees'   => 'nullable|array',
            'assignees.*' => ['integer', Rule::in($memberIds)],
        ], [
            'title.required'    => 'Judul tugas wajib diisi.',
            'priority.required' => 'Prioritas wajib dipilih.',
            'priority.in'       => 'Prioritas harus salah satu dari: Low, Mid, High.',
            'assignees.*.in'    => 'User yang dipilih bukan anggota Daftar Tugas ini.',
        ]);

        // --- Transaction: update task + sync assignees ---
        DB::transaction(function () use ($task, $validated) {
            $task->update([
                'title'    => $validated['title'],
                'priority' => $validated['priority'],
                'due_date' => $validated['due_date'] ?? null,
            ]);

            $task->assignedUsers()->sync($validated['assignees'] ?? []);
        });

        return redirect()->route('tasks.index', $task->task_list_id)->with('success', 'Tugas berhasil diperbarui!');
    }

    /**
     * Menghapus tugas dari task list (SRS-03).
     */
    public function destroy(Task $task)
    {
        // --- Otorisasi (🔗 memanggil isMember dari SRS-02) ---
        Gate::authorize('delete', $task);

        $taskListId = $task->task_list_id;

        // --- Transaction: hapus assignees + task secara atomik ---
        DB::transaction(function () use ($task) {
            $task->deleteWithAssignees();
        });

        return redirect()->route('tasks.index', $taskListId)->with('success', 'Tugas berhasil dihapus!');
    }

    /**
     * Toggle status tugas (pending <-> done) dan hitung ulang progress (SRS-04).
     */
    public function toggleStatus(Task $task)
    {
        // --- Otorisasi (🔗 memanggil isMember dari SRS-02) ---
        Gate::authorize('toggleStatus', $task);

        $task->status = !$task->status;
        $task->save();

        $statusText = $task->status ? 'selesai' : 'belum selesai';
        return back()->with('success', "Status tugas diubah menjadi {$statusText}.");
    }
}
