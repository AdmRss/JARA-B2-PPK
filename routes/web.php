<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\TaskListController;

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