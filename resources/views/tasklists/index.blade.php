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

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm">
                <p class="font-semibold mb-1">Input belum sesuai:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Buat Proyek -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 mb-8">
            <h2 class="text-md font-semibold text-slate-700 mb-4">Buat Proyek Baru</h2>
            <form action="/lists" method="POST" class="space-y-4">
                @csrf
                <div>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama Proyek (Contoh: Pengembangan Fitur)" required class="w-full border {{ $errors->has('name') ? 'border-rose-300 bg-rose-50' : 'border-slate-200' }} rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('name')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <input type="text" name="description" value="{{ old('description') }}" placeholder="Deskripsi Singkat Proyek" class="w-full border {{ $errors->has('description') ? 'border-rose-300 bg-rose-50' : 'border-slate-200' }} rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('description')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 rounded-lg text-sm transition">Buat Proyek</button>
            </form>
        </div>

        <!-- List Proyek & Kolaborator -->
        <div class="space-y-4">
            @forelse($lists as $list)
            @php
                $totalTasks = $list->tasks()->count();
                $completedTasks = $list->tasks()->where('status', true)->count();
                $pendingTasks = $totalTasks - $completedTasks;
                $progress = $list->progressPercentage();
            @endphp
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">{{ $list->name }}</h3>
                        <p class="text-slate-500 text-sm">{{ $list->description ?? 'Tidak ada deskripsi' }}</p>
                    </div>
                    <a href="{{ route('tasks.index', $list->id) }}" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 px-3.5 py-1.5 rounded-lg text-xs font-semibold transition inline-flex items-center gap-1 shadow-sm">
                        Kelola Tugas & Progres &rarr;
                    </a>
                </div>

                <!-- Mini Progres Tugas (SRS-04) -->
                <div class="mt-3">
                    <div class="flex justify-between items-center text-xs mb-1">
                        <span class="text-slate-500 font-medium">Progres:</span>
                        <span class="font-bold text-indigo-600">{{ $progress }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                        <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300" style="width: {{ $progress }}%"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 mt-3">
                        <div class="rounded-lg border border-slate-100 bg-slate-50 px-3 py-2">
                            <p class="text-[11px] uppercase font-semibold text-slate-400">Total</p>
                            <p class="text-sm font-bold text-slate-700">{{ $totalTasks }}</p>
                        </div>
                        <div class="rounded-lg border border-emerald-100 bg-emerald-50 px-3 py-2">
                            <p class="text-[11px] uppercase font-semibold text-emerald-500">Selesai</p>
                            <p class="text-sm font-bold text-emerald-700">{{ $completedTasks }}</p>
                        </div>
                        <div class="rounded-lg border border-amber-100 bg-amber-50 px-3 py-2">
                            <p class="text-[11px] uppercase font-semibold text-amber-500">Pending</p>
                            <p class="text-sm font-bold text-amber-700">{{ $pendingTasks }}</p>
                        </div>
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

                    <form action="/lists/{{ $list->id }}/collaborators" method="POST" class="flex items-center gap-2 w-full md:w-auto">
                        @csrf
                        <select name="user_id" class="border border-slate-200 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition whitespace-nowrap">Tambah Anggota</button>
                    </form>
                </div>
            </div>
            @empty
                <div class="bg-white p-8 rounded-xl shadow-sm border border-slate-100 text-center">
                    <h3 class="text-slate-700 font-semibold mb-1">Belum Ada Proyek</h3>
                    <p class="text-slate-400 text-sm">Buat proyek pertama untuk mulai memantau tugas dan kolaborasi tim.</p>
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>
