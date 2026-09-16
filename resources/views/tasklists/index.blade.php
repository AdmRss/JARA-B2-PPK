<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Proyek - Jara</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen p-4 md:p-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-center mb-6">
            <div>
                <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wider">Dashboard Pemantauan</p>
                <h1 class="text-2xl font-bold text-slate-900">Proyek & Kolaborasi</h1>
                <p class="text-sm text-slate-500">Pantau progres proyek, anggota, dan akses dalam satu tampilan.</p>
            </div>
            <a href="/" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">&larr; Kembali ke Beranda</a>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                <p class="font-semibold">Aksi belum bisa diproses.</p>
                <ul class="mt-1 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Buat Proyek -->
        <div class="bg-white p-5 md:p-6 rounded-lg shadow-sm border border-slate-200 mb-8">
            <h2 class="text-base font-semibold text-slate-800 mb-4">Buat Proyek Baru</h2>
            <form action="/lists" method="POST" class="grid grid-cols-1 lg:grid-cols-[1fr_1fr_auto] gap-3">
                @csrf
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama proyek" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <input type="text" name="description" value="{{ old('description') }}" placeholder="Deskripsi singkat" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-5 py-2 rounded-lg text-sm transition whitespace-nowrap">Buat Proyek</button>
            </form>
        </div>

        <!-- List Proyek & Kolaborator -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
            @forelse($lists as $list)
            @php
                $totalTasks = $list->tasks()->count();
                $completedTasks = $list->tasks()->where('status', true)->count();
                $pendingTasks = $totalTasks - $completedTasks;
                $progress = $list->progressPercentage();
            @endphp
            <div class="bg-white p-5 rounded-lg shadow-sm border border-slate-200">
                <div class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-start mb-4">
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="text-lg font-bold text-slate-900">{{ $list->name }}</h3>
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $list->owner_id === $userId ? 'bg-indigo-50 text-indigo-700 border border-indigo-100' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                {{ $list->owner_id === $userId ? 'Owner' : 'Collaborator' }}
                            </span>
                        </div>
                        <p class="text-slate-500 text-sm">{{ $list->description ?? 'Tidak ada deskripsi' }}</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('tasks.index', $list->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3.5 py-2 rounded-lg text-xs font-semibold transition">
                            Kelola
                        </a>
                        @if($list->owner_id === $userId)
                            <form action="/lists/{{ $list->id }}" method="POST" onsubmit="return confirm('Hapus proyek ini beserta semua tugas dan kolaborator?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-700 px-3.5 py-2 rounded-lg text-xs font-semibold transition">
                                    Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Mini Progres Tugas (SRS-04) -->
                <div class="rounded-lg border border-slate-100 bg-slate-50 p-4">
                    <div class="flex justify-between items-end mb-2">
                        <div>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Progres Tugas</p>
                            <p class="text-sm text-slate-600">{{ $completedTasks }} selesai, {{ $pendingTasks }} belum selesai</p>
                        </div>
                        <span class="text-2xl font-bold text-indigo-600">{{ $progress }}%</span>
                    </div>
                    <div class="w-full bg-white rounded-full h-3 overflow-hidden border border-slate-200">
                        <div class="bg-indigo-600 h-full rounded-full transition-all duration-300" style="width: {{ $progress }}%"></div>
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
                            <select name="user_id" class="min-w-0 flex-1 md:flex-none border border-slate-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-3 py-2 rounded-lg text-xs font-medium transition whitespace-nowrap">Tambah</button>
                        </form>
                    @endif
                </div>
            </div>
            @empty
                <div class="xl:col-span-2 bg-white p-8 rounded-lg shadow-sm border border-slate-200 text-center">
                    <h3 class="text-slate-800 font-semibold">Belum Ada Proyek</h3>
                    <p class="text-slate-500 text-sm mt-1">Buat proyek pertama untuk mulai memantau tugas dan kolaborasi.</p>
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>
