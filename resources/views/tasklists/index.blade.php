<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Proyek - Jara</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen p-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-slate-800">Daftar Proyek & Kolaborasi</h1>
            <a href="/" class="text-sm text-indigo-600 hover:underline">&larr; Kembali ke Beranda</a>
        </div>

        <!-- Form Buat Proyek -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 mb-8">
            <h2 class="text-md font-semibold text-slate-700 mb-4">Buat Proyek Baru</h2>
            <form action="/lists" method="POST" class="space-y-4">
                @csrf
                <input type="text" name="name" placeholder="Nama Proyek (Contoh: Pengembangan Fitur)" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <input type="text" name="description" placeholder="Deskripsi Singkat Proyek" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 rounded-lg text-sm transition">Buat Proyek</button>
            </form>
        </div>

        <!-- List Proyek & Kolaborator -->
        <div class="space-y-4">
            @foreach($lists as $list)
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">{{ $list->name }}</h3>
                        <p class="text-slate-500 text-sm">{{ $list->description ?? 'Tidak ada deskripsi' }}</p>
                        <p class="text-slate-400 text-xs mt-1">{{ $list->owner_id === $userId ? 'Owner' : 'Collaborator' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('tasks.index', $list->id) }}" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 px-3.5 py-1.5 rounded-lg text-xs font-semibold transition inline-flex items-center gap-1 shadow-sm">
                            Kelola Tugas & Progres &rarr;
                        </a>
                        @if($list->owner_id === $userId)
                            <form action="/lists/{{ $list->id }}" method="POST" onsubmit="return confirm('Hapus proyek ini beserta semua tugas dan kolaborator?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-700 px-3.5 py-1.5 rounded-lg text-xs font-semibold transition">
                                    Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kolaborator:</span>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @forelse($list->collaborators as $collab)
                                <span class="bg-slate-100 text-slate-700 text-xs px-2.5 py-1 rounded-md">{{ $collab->name }}</span>
                            @empty
                                <span class="text-slate-400 text-xs italic">Belum ada kolaborator</span>
                            @endforelse
                        </div>
                    </div>

                    @if($list->owner_id === $userId)
                        <form action="/lists/{{ $list->id }}/collaborators" method="POST" class="flex items-center gap-2 w-full md:w-auto">
                            @csrf
                            <select name="user_id" class="border border-slate-200 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition whitespace-nowrap">Tambah Anggota</button>
                        </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</body>
</html>
