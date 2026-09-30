<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // أولاً: نضيف Index مستقل لـ teacher_id
        // حتى لا يعتمد الـ Foreign Key على الـ unique القديم.
        Schema::table('teacher_assignments', function (Blueprint $table) {
            $table->index(
                'teacher_id',
                'teacher_assignments_teacher_id_index'
            );
        });

        // ثانياً: نحذف القيد القديم
        Schema::table('teacher_assignments', function (Blueprint $table) {
            $table->dropUnique(
                'teacher_assignments_teacher_id_section_id_unique'
            );
        });

        // ثالثاً: نضيف القيد الجديد
        // المعلم + الشعبة + المادة
        Schema::table('teacher_assignments', function (Blueprint $table) {
            $table->unique(
                [
                    'teacher_id',
                    'section_id',
                    'subject_id',
                ],
                'teacher_assignments_teacher_section_subject_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('teacher_assignments', function (Blueprint $table) {
            $table->dropUnique(
                'teacher_assignments_teacher_section_subject_unique'
            );
        });

        Schema::table('teacher_assignments', function (Blueprint $table) {
            $table->unique(
                [
                    'teacher_id',
                    'section_id',
                ],
                'teacher_assignments_teacher_id_section_id_unique'
            );
        });

        Schema::table('teacher_assignments', function (Blueprint $table) {
            $table->dropIndex(
                'teacher_assignments_teacher_id_index'
            );
        });
    }
};