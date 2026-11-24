<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * إنشاء جدول مجموعات الدرجات
     */
    public function up(): void
    {
        Schema::create('grade_batches', function (Blueprint $table) {

            $table->id();

            // المعلم صاحب مجموعة الدرجات
            $table->foreignId('teacher_id')
                ->constrained('teachers')
                ->restrictOnDelete();

            // المادة الدراسية
            $table->foreignId('subject_id')
                ->constrained('subjects')
                ->restrictOnDelete();

            // الشعبة الدراسية
            $table->foreignId('section_id')
                ->constrained('sections')
                ->restrictOnDelete();

            // السنة الدراسية، مثال: 2026-2027
            $table->string('academic_year', 20);

            // الفصل الدراسي
            $table->string('semester', 30);

            // نوع التقييم، مثال: Monthly Test
            $table->string('evaluation_name');

            // الفترة، مثال: October
            $table->string('evaluation_period', 50);

            // الدرجة الكلية للتقييم
            $table->decimal('max_score', 6, 2);

            // حالة مجموعة الدرجات
            $table->string('status', 20)
                ->default('draft');

            // وقت إرسال المجموعة للاعتماد
            $table->timestamp('submitted_at')
                ->nullable();

            // المستخدم الذي اعتمد المجموعة
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->restrictOnDelete();

            // وقت الاعتماد
            $table->timestamp('approved_at')
                ->nullable();

            // سبب إرجاع المجموعة للمعلم
            $table->text('rejection_reason')
                ->nullable();

            // وقت إرجاع المجموعة
            $table->timestamp('returned_at')
                ->nullable();

            $table->timestamps();

            // منع إنشاء مجموعتين لنفس التقييم والفترة
            // بواسطة نفس المعلم وللشعبة والمادة نفسها
            $table->unique(
                [
                    'teacher_id',
                    'subject_id',
                    'section_id',
                    'academic_year',
                    'semester',
                    'evaluation_name',
                    'evaluation_period',
                ],
                'grade_batches_unique_evaluation'
            );

            // تسريع البحث حسب حالة الاعتماد
            $table->index(
                ['status', 'submitted_at'],
                'grade_batches_status_index'
            );
        });
    }

    /**
     * حذف الجدول عند التراجع عن Migration
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_batches');
    }
};