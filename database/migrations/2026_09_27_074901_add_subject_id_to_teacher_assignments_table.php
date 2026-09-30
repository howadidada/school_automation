<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // إضافة subject_id إلى إسنادات المعلمين
        Schema::table('teacher_assignments', function (Blueprint $table) {
            $table->foreignId('subject_id')
                ->nullable()
                ->after('section_id')
                ->constrained('subjects')
                ->restrictOnDelete();
        });

        // -----------------------------------------------------------
        // الحفاظ على الإسنادات الموجودة حاليًا
        //
        // المعلم الحالي عنده المادة في teachers.subject_id
        // ننقلها تلقائيًا إلى teacher_assignments.subject_id
        // حتى لا نخسر مادة عربي الحالية.
        // -----------------------------------------------------------
        $assignments = DB::table('teacher_assignments')->get();

        foreach ($assignments as $assignment) {
            $subjectId = DB::table('teachers')
                ->where('id', $assignment->teacher_id)
                ->value('subject_id');

            if ($subjectId) {
                DB::table('teacher_assignments')
                    ->where('id', $assignment->id)
                    ->update([
                        'subject_id' => $subjectId,
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_assignments', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->dropColumn('subject_id');
        });
    }
};