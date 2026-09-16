<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna Baru - Jara Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen antialiased text-slate-800">
    <div class="max-w-3xl mx-auto px-4 py-8 sm:px-6 lg:px-8">

        <!-- Top Navigation & Breadcrumb -->
        <div class="pb-6 mb-6 border-b border-slate-200/80">
            <div class="flex items-center gap-2 text-xs font-medium text-slate-500 mb-1">
                <a href="/" class="hover:text-indigo-600 transition">Beranda</a>
                <span>/</span>
                <a href="{{ route('admin.users.index') }}" class="hover:text-indigo-600 transition">Manajemen Pengguna</a>
                <span>/</span>
                <span class="text-indigo-600">Tambah Pengguna</span>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Tambah Akun Pengguna Baru</h1>
                    <p class="text-sm text-slate-500 mt-0.5">Daftarkan akun baru ke dalam sistem Jara secara internal melalui wewenang Administrator.</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-3.5 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition shadow-sm">
                    &larr; Kembali ke Daftar
                </a>
            </div>
        </div>

        <!-- Global Errors Notification -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-800 text-sm shadow-sm">
                <div class="font-semibold mb-1 flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    Mohon perbaiki kesalahan berikut sebelum menyimpan:
                </div>
                <ul class="list-disc list-inside space-y-1 text-xs text-rose-700 ml-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Tambah User -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200/80 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-700">Formulir Informasi Akun</h2>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 space-y-5">
                @csrf

                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           class="w-full px-3.5 py-2.5 text-sm bg-white border @error('name') border-rose-300 ring-1 ring-rose-300 @else border-slate-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                           placeholder="Contoh: Adam Mulya Rasyid">
                    @error('name')
                        <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Alamat Email <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-3.5 py-2.5 text-sm bg-white border @error('email') border-rose-300 ring-1 ring-rose-300 @else border-slate-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                           placeholder="nama@domain.com">
                    @error('email')
                        <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Grid Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Password (Min. 8 Karakter) <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" id="password" name="password" required
                               class="w-full px-3.5 py-2.5 text-sm bg-white border @error('password') border-rose-300 ring-1 ring-rose-300 @else border-slate-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                               placeholder="••••••••">
                        @error('password')
                            <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Konfirmasi Password <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               class="w-full px-3.5 py-2.5 text-sm bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                               placeholder="••••••••">
                    </div>
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Hak Akses / Peran Sistem <span class="text-rose-500">*</span>
                    </label>
                    <select id="role" name="role" required
                            class="w-full px-3.5 py-2.5 text-sm bg-white border @error('role') border-rose-300 ring-1 ring-rose-300 @else border-slate-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User (Pengguna Reguler - Kolaborator & Pemilik List)</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin (Administrator Sistem - Hak Akses Penuh)</option>
                    </select>
                    @error('role')
                        <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-slate-400 mt-1">Sesuai design.md: Peran User digunakan untuk membuat task list dan berkolaborasi. Peran Admin berwenang mengelola pengguna.</p>
                </div>

                <!-- Tombol Aksi -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100 rounded-lg transition">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Pengguna Baru
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
