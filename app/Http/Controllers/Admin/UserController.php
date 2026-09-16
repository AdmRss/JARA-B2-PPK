<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua User yang terdaftar dalam sistem (SRS-01 Fitur 1).
     */
    public function index()
    {
        // Mengambil daftar pengguna beserta jumlah kepemilikan task list dan kolaborasi
        $users = User::withCount(['ownedLists', 'collaboratedLists'])
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan form untuk menambahkan User baru (SRS-01 Fitur 1).
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Menyimpan User baru ke dalam sistem dengan validasi lengkap (SRS-01 Fitur 1).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', Rule::in(['admin', 'user'])],
        ], [
            'name.required'     => 'Nama lengkap wajib diisi.',
            'email.required'    => 'Alamat email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => 'Alamat email ini sudah terdaftar dalam sistem.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal harus 8 karakter.',
            'password.confirmed'=> 'Konfirmasi password tidak cocok.',
            'role.required'     => 'Role pengguna wajib dipilih.',
            'role.in'           => 'Role pengguna harus berupa admin atau user.',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Akun pengguna {$validated['name']} berhasil ditambahkan ke sistem.");
    }

    /**
     * Menghapus User beserta seluruh data pivot kolaborasi secara atomik (SRS-01 Fitur 3).
     * Seluruh operasi dibungkus di dalam DB::transaction(). Jika terjadi kegagalan,
     * seluruh perubahan akan di-rollback tanpa menyisakan data korup.
     */
    public function destroy(User $user)
    {
        // Pencegahan: Jangan izinkan admin menghapus akunnya sendiri yang sedang aktif
        if (auth()->check() && auth()->id() === $user->id) {
            return redirect()
                ->back()
                ->with('error', 'Tindakan dibatalkan: Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $userName = $user->name;

        try {
            DB::transaction(function () use ($user) {
                // 1. Hapus data pivot kolaborasi milik User dari tabel task_list_user (sesuai design.md Bagian 4C)
                if (Schema::hasTable('task_list_user')) {
                    DB::table('task_list_user')->where('user_id', $user->id)->delete();
                }

                // Antisipasi backward-compatibility jika terdapat tabel pivot tambahan di masa mendatang
                if (Schema::hasTable('list_user')) {
                    DB::table('list_user')->where('user_id', $user->id)->delete();
                }
                if (Schema::hasTable('task_user')) {
                    DB::table('task_user')->where('user_id', $user->id)->delete();
                }

                // 2. Hapus record User dari tabel users
                $user->delete();
            });

            return redirect()
                ->route('admin.users.index')
                ->with('success', "Pengguna {$userName} beserta seluruh data relasi kolaborasinya berhasil dihapus secara bersih.");
        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Penghapusan gagal dilakukan. Seluruh transaksi dibatalkan (rollback) demi integritas data. Detail: ' . $e->getMessage());
        }
    }
}
