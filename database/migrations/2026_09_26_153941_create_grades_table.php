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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();

            // الطالب
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            // المعلم الذي أدخل الدرجة
            $table->foreignId('teacher_id')
                ->constrained('teachers')
                ->cascadeOnDelete();

            // المادة
            $table->foreignId('subject_id')
                ->constrained('subjects')
                ->cascadeOnDelete();

            // اسم التقييم
            // مثال: اختبار قصير 1، واجب، منتصف الفصل
            $table->string('evaluation_name');

            // الدرجة التي حصل عليها الطالب
            $table->decimal('score', 6, 2);

            // الدرجة القصوى
            $table->decimal('max_score', 6, 2);

            // ملاحظة اختيارية
            $table->text('note')->nullable();

            $table->timestamps();

            // منع تكرار نفس التقييم لنفس الطالب
            // في نفس المادة بواسطة نفس المعلم
            $table->unique(
                [
                    'student_id',
                    'teacher_id',
                    'subject_id',
                    'evaluation_name'
                ],
                'grades_unique_evaluation'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};