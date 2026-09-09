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
}
