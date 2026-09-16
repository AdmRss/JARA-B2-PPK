<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengguna - Jara Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen antialiased text-slate-800">
    <div class="max-w-6xl mx-auto px-4 py-8 sm:px-6 lg:px-8">

        <!-- Top Navigation & Breadcrumb -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 mb-6 border-b border-slate-200/80 gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-medium text-slate-500 mb-1">
                    <a href="/" class="hover:text-indigo-600 transition">Beranda</a>
                    <span>/</span>
                    <span class="text-slate-800">Admin</span>
                    <span>/</span>
                    <span class="text-indigo-600">Manajemen Pengguna</span>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Manajemen Pengguna (SRS-01)</h1>
                <p class="text-sm text-slate-500 mt-0.5">Kelola kredensial pengguna, hak akses peran, serta integritas data kolaboratif sistem Jara.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="/" class="inline-flex items-center justify-center px-3.5 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition shadow-sm">
                    &larr; Ke Beranda
                </a>
                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Pengguna Baru
                </a>
            </div>
        </div>

        <!-- Flash Notifications -->
        @if(session('success'))
            <div class="mb-6 flex items-center p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl shadow-sm text-sm" role="alert">
                <svg class="w-5 h-5 mr-2.5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 flex items-center p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl shadow-sm text-sm" role="alert">
                <svg class="w-5 h-5 mr-2.5 text-rose-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Quick Stat Badges -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Pengguna</p>
                    <p class="text-2xl font-bold text-slate-800 mt-1">{{ $users->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                    👥
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Administrator</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1">{{ $users->where('role', 'admin')->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                    🛡️
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Regular Users</p>
                    <p class="text-2xl font-bold text-slate-700 mt-1">{{ $users->where('role', 'user')->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center font-bold">
                    👤
                </div>
            </div>
        </div>

        <!-- Tabel Daftar User -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200/80 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h2 class="text-base font-semibold text-slate-800">Daftar Akun Terdaftar</h2>
                <span class="text-xs text-slate-500">Total: {{ $users->count() }} akun</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 border-b border-slate-100 font-semibold text-xs uppercase tracking-wider">
                            <th class="px-6 py-3.5">Nama & Profil</th>
                            <th class="px-6 py-3.5">Email</th>
                            <th class="px-6 py-3.5 text-center">Peran (Role)</th>
                            <th class="px-6 py-3.5 text-center">Relasi Kolaborasi</th>
                            <th class="px-6 py-3.5">Tanggal Dibuat</th>
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($users as $user)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-xs uppercase mr-3">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-900">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-400">ID: #{{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-600">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700 border border-purple-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-xs text-slate-500">
                                <span title="Jumlah Task List yang dibuat" class="inline-block bg-slate-100 px-2 py-0.5 rounded">Owner: {{ $user->owned_lists_count ?? 0 }}</span>
                                <span title="Jumlah List di mana user berkolaborasi" class="inline-block bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded ml-1">Collab: {{ $user->collaborated_lists_count ?? 0 }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500">
                                {{ $user->created_at ? $user->created_at->format('d M Y H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('PERINGATAN: Menghapus pengguna \'{{ $user->name }}\' akan menghapus seluruh data pivot kolaborasi (task_list_user) secara atomik menggunakan DB::transaction(). Lanjutkan?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 font-semibold px-2.5 py-1.5 rounded hover:bg-rose-50 transition">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                                Belum ada data pengguna yang terdaftar di dalam sistem.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="mt-8 text-center text-xs text-slate-400">
            Jara Task Management &bull; Modul SRS-01: Keamanan Inti & Manajemen Admin &bull; Transaksi Atomik DB::transaction() Aktif
        </div>
    </div>
</body>
</html>
