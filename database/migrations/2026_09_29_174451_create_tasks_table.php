<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('teacher_id')
                ->constrained('teachers')
                ->cascadeOnDelete();

            $table->foreignId('subject_id')
                ->constrained('subjects')
                ->cascadeOnDelete();

            $table->foreignId('section_id')
                ->constrained('sections')
                ->cascadeOnDelete();

            $table->string('title');

            $table->text('details');

            $table->enum('type', [
                'homework',
                'activity',
            ]);

            $table->date('due_date');

            $table->timestamps();

            $table->index([
                'teacher_id',
                'subject_id',
                'section_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};