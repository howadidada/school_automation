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
        Schema::create('student_notes', function (Blueprint $table) {
            $table->id();

            // الطالب صاحب الملاحظة
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            // المعلم الذي كتب الملاحظة
            $table->foreignId('teacher_id')
                ->constrained('teachers')
                ->cascadeOnDelete();

            // المادة اختيارية
            // الملاحظة السلوكية العامة قد لا تكون مرتبطة بمادة
            $table->foreignId('subject_id')
                ->nullable()
                ->constrained('subjects')
                ->nullOnDelete();

            // نوع الملاحظة:
            // behavior          = ملاحظة سلوكية
            // positive          = ملاحظة إيجابية
            // academic_warning  = تنبيه أكاديمي
            $table->string('type', 50)
                ->default('behavior');

            // نص الملاحظة
            $table->text('note');

            // تاريخ الملاحظة
            $table->date('note_date');

            $table->timestamps();

            // لتحسين سرعة البحث
            $table->index([
                'student_id',
                'teacher_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_notes');
    }
};