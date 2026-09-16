<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/dev/login/{id}', function ($id) {
    Auth::loginUsingId($id);
    return "Berhasil login sebagai User ID $id. <br><a href='/lists/1/tasks'>Klik di sini untuk ke halaman Tugas Proyek 1</a>";
});
