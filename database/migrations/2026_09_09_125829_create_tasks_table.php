<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_list_id')->constrained('task_lists')->onDelete('cascade');
            $table->string('title');
            $table->enum('priority', ['low', 'mid', 'high'])->default('low');
            $table->dateTime('due_date')->nullable();
            $table->boolean('status')->default(false); // false = pending, true = done
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
