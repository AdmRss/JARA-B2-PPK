<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index() {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function store(Request $request) {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);
        return back();
    }

    public function destroy(User $user) {
        $user->delete();
        return back();
    }
}
