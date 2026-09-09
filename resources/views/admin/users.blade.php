<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengguna - Jara</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen p-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Pengguna (Admin)</h1>
            <a href="/" class="text-sm text-indigo-600 hover:underline">&larr; Kembali ke Beranda</a>
        </div>

        <!-- Form Tambah User -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 mb-8">
            <h2 class="text-md font-semibold text-slate-700 mb-4">Tambah Pengguna Baru</h2>
            <form action="/admin/users" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf
                <input type="text" name="name" placeholder="Nama Lengkap" required class="border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <input type="email" name="email" placeholder="Alamat Email" required class="border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <input type="password" name="password" placeholder="Password" required class="border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <select name="role" class="border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <button type="submit" class="md:col-span-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 rounded-lg text-sm transition">Simpan Pengguna</button>
            </form>
        </div>

        <!-- Tabel Data User -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-600">
                        <th class="p-4 font-semibold">Nama</th>
                        <th class="p-4 font-semibold">Email</th>
                        <th class="p-4 font-semibold">Role</th>
                        <th class="p-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @foreach($users as $u)
                    <tr class="hover:bg-slate-50">
                        <td class="p-4 font-medium">{{ $u->name }}</td>
                        <td class="p-4 text-slate-500">{{ $u->email }}</td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $u->role === 'admin' ? 'bg-purple-50 text-purple-600' : 'bg-slate-100 text-slate-600' }}">
                                {{ ucfirst($u->role) }}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <form action="/admin/users/{{ $u->id }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-medium text-xs">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>