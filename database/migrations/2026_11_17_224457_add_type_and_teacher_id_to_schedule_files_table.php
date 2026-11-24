<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * إضافة نوع الجدول والمعلم المستهدف.
     */
    public function up(): void
    {
        Schema::table('schedule_files', function (Blueprint $table) {

            // نوع الجدول الدراسي
            $table->string('type')
                ->nullable();

            // المعلم المستهدف بجدول الحصص
            $table->foreignId('teacher_id')
                ->nullable()
                ->constrained('teachers')
                ->restrictOnDelete();

        });
    }

    /**
     * التراجع عن التعديلات.
     */
    public function down(): void
    {
        Schema::table('schedule_files', function (Blueprint $table) {

            $table->dropForeign(['teacher_id']);

            $table->dropColumn([
                'teacher_id',
                'type',
            ]);

        });
    }
};