<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
    {
        protected $fillable = ['name', 'description', 'owner_id'];

        public function owner() {
            return $this->belongsTo(User::class, 'owner_id');
        }

        public function collaborators() {
            return $this->belongsToMany(User::class, 'task_list_user');
        }
        
        // Relasi untuk tugasnya Arga nanti
        public function tasks() {
            return $this->hasMany(Task::class);
        }

        /**
         * Hitung persentase progres penyelesaian tugas (SRS-04).
         * Dilengkapi guard clause untuk menghindari pembagian dengan nol.
         */
        public function progressPercentage(): int
        {
            $total = $this->tasks()->count();
            if ($total === 0) {
                return 0;
            }

            $completed = $this->tasks()->where('status', true)->count();
            return (int) round(($completed / $total) * 100);
        }
    }