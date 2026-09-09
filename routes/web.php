<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\TaskListController;
use App\Http\Controllers\TaskController;

// Halaman utama (menu navigasi)
Route::get('/', function () {
    return view('welcome');
});

// Route SRS-01 (Admin)
Route::get('/admin/users', [AdminUserController::class, 'index']);
Route::post('/admin/users', [AdminUserController::class, 'store']);
Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy']);

// Route SRS-02 (Task List)
Route::get('/lists', [TaskListController::class, 'index']);
Route::post('/lists', [TaskListController::class, 'store']);
Route::post('/lists/{taskList}/collaborators', [TaskListController::class, 'addCollaborator']);

// Route SRS-03 & SRS-04 (Tasks & Progress)
Route::get('/lists/{taskList}/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/lists/{taskList}/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggleStatus'])->name('tasks.toggle');