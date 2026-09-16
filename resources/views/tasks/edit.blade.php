<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tugas - {{ $task->title }} | Jara</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen p-4 md:p-8 flex items-center justify-center">
    <div class="w-full max-w-lg">
        <div class="mb-4">
            <a href="{{ route('tasks.index', $task->task_list_id) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition inline-flex items-center gap-1">
                &larr; Kembali ke Daftar Tugas
            </a>
        </div>

        <div class="bg-white p-6 md:p-8 rounded-xl shadow-sm border border-slate-100">
            <h1 class="text-xl font-bold text-slate-800 mb-1">Edit Tugas</h1>
            <p class="text-slate-500 text-xs mb-6">Perbarui atribut tugas di dalam proyek <span class="font-semibold text-slate-700">{{ $taskList->name }}</span></p>

            @if($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm">
                    <p class="font-semibold mb-1">Terjadi kesalahan:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Judul Tugas <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $task->title) }}" required
                           class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Prioritas <span class="text-rose-500">*</span></label>
                    <select name="priority" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                        <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>🟢 Low (Rendah)</option>
                        <option value="mid" {{ old('priority', $task->priority) == 'mid' ? 'selected' : '' }}>🟡 Mid (Sedang)</option>
                        <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>🔴 High (Tinggi)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Tenggat Waktu (Due Date)</label>
                    <input type="datetime-local" name="due_date" 
                           value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d\TH:i') : '') }}"
                           class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Tugaskan Ke (Opsional)</label>
                    <select name="assignees[]" multiple class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white h-24">
                        @foreach($members as $member)
                            <option value="{{ $member->id }}"
                                {{ in_array($member->id, old('assignees', $task->assignedUsers->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $member->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-400 mt-1">Tahan Ctrl/Cmd untuk memilih beberapa anggota.</p>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2">
                    <a href="{{ route('tasks.index', $task->task_list_id) }}" 
                       class="px-4 py-2 border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-lg text-sm font-medium transition">
                        Batal
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
