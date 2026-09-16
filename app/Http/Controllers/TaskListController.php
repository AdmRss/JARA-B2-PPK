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
        ], [
            'name.required' => 'Nama proyek wajib diisi.',
            'name.max' => 'Nama proyek maksimal 255 karakter.',
        ]);

        TaskList::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'owner_id' => 1 // Hardcode sementara untuk testing
        ]);
        return back()->with('success', 'Proyek baru berhasil dibuat.');
    }

    public function addCollaborator(Request $request, TaskList $taskList) {
        $userId = 1;

        if (! $taskList->isOwner($userId)) {
            return back()->withErrors([
                'authorization' => 'Aksi ditolak. Hanya owner proyek yang boleh menambahkan anggota.',
            ]);
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ], [
            'user_id.required' => 'Pilih anggota yang ingin ditambahkan.',
            'user_id.exists' => 'Anggota yang dipilih tidak valid.',
        ]);

        // Attach user ke pivot table
        $taskList->collaborators()->syncWithoutDetaching([$validated['user_id']]);
        return back()->with('success', 'Anggota berhasil ditambahkan ke proyek.');
    }

    public function destroy(TaskList $taskList) {
        $userId = 1;

        if (! $taskList->isOwner($userId)) {
            return back()->withErrors([
                'authorization' => 'Aksi ditolak. Hanya owner proyek yang boleh menghapus proyek.',
            ]);
        }

        DB::transaction(function () use ($taskList) {
            $taskList->collaborators()->detach();
            $taskList->tasks()->get()->each->deleteWithAssignees();
            $taskList->delete();
        });

        return redirect('/lists')->with('success', 'Proyek berhasil dihapus.');
    }
}
