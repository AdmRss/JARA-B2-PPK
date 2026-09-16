<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Tugas - {{ $taskList->name }} | Jara</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen p-4 md:p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header & Navigasi -->
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2 mb-6">
            <div>
                <a href="/lists" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition inline-flex items-center gap-1 mb-2">
                    &larr; Kembali ke Daftar Proyek
                </a>
                <h1 class="text-2xl font-bold text-slate-800">{{ $taskList->name }}</h1>
                <p class="text-slate-500 text-sm">{{ $taskList->description ?? 'Tidak ada deskripsi' }}</p>
            </div>
            <div class="text-xs text-slate-500 bg-white border border-slate-200 px-3 py-2 rounded-lg self-start sm:self-auto shadow-sm">
                Owner: <span class="font-semibold text-slate-700">{{ $taskList->owner->name ?? 'User #'.$taskList->owner_id }}</span>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 font-bold">&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm">
                <p class="font-semibold mb-1">Terjadi kesalahan validasi:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- SRS-04: Card Pelacakan Status & Progress Tracking -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                <div>
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider">Progres Penyelesaian Tugas</h2>
                    <p class="text-xs text-slate-400">Total progres dihitung otomatis berdasarkan tugas yang telah selesai</p>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-indigo-600">{{ $progress }}%</span>
                    <span class="text-xs text-slate-500 font-medium">({{ $completedTasks }}/{{ $totalTasks }} tugas)</span>
                </div>
            </div>

            <!-- Visual Progress Bar -->
            <div class="w-full bg-slate-100 rounded-full h-3.5 overflow-hidden">
                <div class="bg-indigo-600 h-3.5 rounded-full transition-all duration-500 ease-out flex items-center justify-end pr-1"
                     style="width: {{ $progress }}%">
                </div>
            </div>
        </div>

        <!-- SRS-03: Form Tambah Tugas Baru -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 mb-8">
            <h2 class="text-md font-semibold text-slate-700 mb-4 flex items-center gap-2">
                <span>➕</span> Tambah Tugas Baru
            </h2>
            <form action="{{ route('tasks.store', $taskList->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Judul Tugas <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Membuat Wireframe Desain" required
                           class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Prioritas <span class="text-rose-500">*</span></label>
                        <select name="priority" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>🟢 Low (Rendah)</option>
                            <option value="mid" {{ old('priority', 'mid') == 'mid' ? 'selected' : '' }}>🟡 Mid (Sedang)</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>🔴 High (Tinggi)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Tenggat Waktu (Due Date)</label>
                        <input type="datetime-local" name="due_date" value="{{ old('due_date') }}"
                               class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Tugaskan Ke (Opsional)</label>
                    <select name="assignees[]" multiple class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white h-24">
                        @foreach($members as $member)
                            <option value="{{ $member->id }}"
                                {{ in_array($member->id, old('assignees', [])) ? 'selected' : '' }}>
                                {{ $member->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-400 mt-1">Tahan Ctrl/Cmd untuk memilih beberapa anggota.</p>
                </div>

                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 rounded-lg text-sm transition shadow-sm">
                    Simpan Tugas
                </button>
            </form>
        </div>

        <!-- Daftar Tugas -->
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <h2 class="text-md font-semibold text-slate-700">Daftar Tugas ({{ $totalTasks }})</h2>
            </div>

            @forelse($tasks as $task)
                <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 transition hover:border-slate-300">
                    <!-- Sisi Kiri: Status Toggle & Info Tugas -->
                    <div class="flex items-start gap-3 flex-1">
                        <!-- Toggle Button (SRS-04) -->
                        <form action="{{ route('tasks.toggle', $task->id) }}" method="POST" class="pt-0.5">
                            @csrf
                            @method('PATCH')
                            <button type="submit" title="{{ $task->status ? 'Klik untuk tandai belum selesai' : 'Klik untuk tandai selesai' }}"
                                    class="w-6 h-6 rounded-md border flex items-center justify-center transition
                                    {{ $task->status ? 'bg-emerald-500 border-emerald-500 text-white hover:bg-emerald-600' : 'border-slate-300 hover:border-indigo-500 text-transparent hover:text-indigo-400' }}">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </form>

                        <div class="space-y-1 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="font-medium text-slate-800 text-sm md:text-base {{ $task->status ? 'line-through text-slate-400' : '' }}">
                                    {{ $task->title }}
                                </h3>

                                <!-- Badge Prioritas (SRS-03) -->
                                @if($task->priority === 'high')
                                    <span class="bg-rose-50 text-rose-700 border border-rose-200 text-xs px-2 py-0.5 rounded font-semibold">🔴 High</span>
                                @elseif($task->priority === 'mid')
                                    <span class="bg-amber-50 text-amber-700 border border-amber-200 text-xs px-2 py-0.5 rounded font-semibold">🟡 Mid</span>
                                @else
                                    <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs px-2 py-0.5 rounded font-semibold">🟢 Low</span>
                                @endif

                                <!-- Badge Status (SRS-04) -->
                                @if($task->status)
                                    <span class="bg-emerald-100 text-emerald-800 text-xs px-2 py-0.5 rounded-full font-medium">Selesai</span>
                                @else
                                    <span class="bg-slate-100 text-slate-600 text-xs px-2 py-0.5 rounded-full font-medium">Pending</span>
                                @endif
                            </div>

                            <!-- Tenggat Waktu (SRS-03) -->
                            <div class="text-xs text-slate-500 flex items-center gap-1">
                                <span>📅 Tenggat:</span>
                                @if($task->due_date)
                                    <span class="{{ !$task->status && $task->due_date->isPast() ? 'text-rose-600 font-semibold' : 'text-slate-600' }}">
                                        {{ $task->due_date->format('d M Y, H:i') }}
                                        @if(!$task->status && $task->due_date->isPast())
                                            (Terlewat!)
                                        @endif
                                    </span>
                                @else
                                    <span class="text-slate-400 italic">Tidak ada</span>
                                @endif
                            </div>

                            <!-- Assignees Badge (SRS-03) -->
                            <div class="flex flex-wrap gap-1 mt-1">
                                @forelse($task->assignedUsers as $assignee)
                                    <span class="bg-indigo-50 text-indigo-700 text-xs px-2 py-0.5 rounded-full border border-indigo-100 font-medium">
                                        {{ $assignee->name }}
                                    </span>
                                @empty
                                    <span class="text-xs text-slate-400 italic">Belum ditugaskan</span>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Sisi Kanan: Tombol Edit & Hapus (SRS-03) -->
                    <div class="flex items-center gap-2 self-end md:self-center">
                        <a href="{{ route('tasks.edit', $task->id) }}"
                           class="px-3 py-1.5 text-xs font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                            Edit
                        </a>

                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 text-xs font-medium text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg transition">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white p-8 rounded-xl shadow-sm border border-slate-100 text-center">
                    <div class="text-3xl mb-2">📋</div>
                    <h3 class="text-slate-700 font-semibold mb-1">Belum Ada Tugas</h3>
                    <p class="text-slate-400 text-sm">Gunakan form di atas untuk menambahkan tugas pertama ke proyek ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>
