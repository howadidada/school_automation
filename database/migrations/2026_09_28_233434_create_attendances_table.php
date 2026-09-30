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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            // الطالب
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            // تاريخ الحضور
            $table->date('attendance_date');

            // حالة الطالب
            $table->enum('status', [
                'present',
                'absent',
                'late',
                'excused',
            ])->default('present');

            // ملاحظات اختيارية
            $table->text('notes')->nullable();

            $table->timestamps();

            // يمنع تسجيل حضور نفس الطالب مرتين في نفس اليوم
            $table->unique(
                ['student_id', 'attendance_date'],
                'student_attendance_date_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};