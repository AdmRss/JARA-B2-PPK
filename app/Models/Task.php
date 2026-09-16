<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_list_id',
        'title',
        'priority',
        'due_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'due_date' => 'datetime',
        ];
    }

    /**
     * Relasi ke TaskList pemilik tugas ini.
     */
    public function taskList()
    {
        return $this->belongsTo(TaskList::class, 'task_list_id');
    }

    /**
     * Relasi many-to-many ke user yang di-assign pada task ini (SRS-03).
     */
    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'task_user');
    }

    /**
     * Hapus task beserta seluruh assignee-nya secara atomik.
     *
     * 📦 KONTRAK SRS-03 → SRS-02
     * Dipanggil oleh Fahri (SRS-02) di dalam DB::transaction saat hapus TaskList.
     * JANGAN ubah nama/signature tanpa koordinasi dengan SRS-02.
     */
    public function deleteWithAssignees(): void
    {
        $this->assignedUsers()->detach();
        $this->delete();
    }
}
