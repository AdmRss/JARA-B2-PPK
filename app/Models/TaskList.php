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
    }