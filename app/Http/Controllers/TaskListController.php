<?php

namespace App\Http\Controllers;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Http\Request;

class TaskListController extends Controller
{
    public function index() {
        // Asumsi user ID 1 sedang login (karena belum ada sistem auth utuh)
        $userId = 1; 
        $lists = TaskList::with('collaborators')->where('owner_id', $userId)->get();
        $users = User::where('id', '!=', $userId)->get(); // Untuk dropdown tambah anggota
        
        return view('tasklists.index', compact('lists', 'users'));
    }

    public function store(Request $request) {
        TaskList::create([
            'name' => $request->name,
            'description' => $request->description,
            'owner_id' => 1 // Hardcode sementara untuk testing
        ]);
        return back();
    }

    public function addCollaborator(Request $request, TaskList $taskList) {
        // Attach user ke pivot table
        $taskList->collaborators()->syncWithoutDetaching([$request->user_id]);
        return back();
    }
}