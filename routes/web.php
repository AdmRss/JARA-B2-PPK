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
Route::delete('/lists/{taskList}', [TaskListController::class, 'destroy']);

// Route SRS-03 & SRS-04 (Tasks & Progress)
Route::get('/lists/{taskList}/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/lists/{taskList}/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggleStatus'])->name('tasks.toggle');

// ============================================================================
// SRS-01: Keamanan Inti & Manajemen Admin (Adam Mulya Rasyid)
// Rute Baru: Admin User Management dengan Proteksi AdminMiddleware & Transaksi Atomik
// ============================================================================
use App\Http\Controllers\Admin\UserController as AdminUserModuleController;

Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users-management', [AdminUserModuleController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserModuleController::class, 'create'])->name('users.create');
    Route::post('/users-management', [AdminUserModuleController::class, 'store'])->name('users.store');
    Route::delete('/users-management/{user}', [AdminUserModuleController::class, 'destroy'])->name('users.destroy');
});
require __DIR__.'/dev.php';
