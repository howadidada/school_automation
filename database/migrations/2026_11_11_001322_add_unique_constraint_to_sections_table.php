<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * إضافة قيود لحماية بيانات الشعب.
     */
    public function up(): void
    {
        Schema::table('sections', function (Blueprint $table) {
            // منع تكرار اسم الشعبة داخل الفصل نفسه.
            $table->unique(
                ['school_class_id', 'name'],
                'sections_class_name_unique'
            );
        });

        // استبدال الحذف التلقائي بمنع حذف الفصل المرتبط بشعب.
        Schema::table('sections', function (Blueprint $table) {
            $table->dropForeign(['school_class_id']);
        });

        Schema::table('sections', function (Blueprint $table) {
            $table->foreign('school_class_id')
                ->references('id')
                ->on('school_classes')
                ->restrictOnDelete();
        });
    }

    /**
     * التراجع عن التعديلات.
     */
    public function down(): void
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->dropForeign(['school_class_id']);
        });

        Schema::table('sections', function (Blueprint $table) {
            $table->foreign('school_class_id')
                ->references('id')
                ->on('school_classes')
                ->cascadeOnDelete();
        });

        Schema::table('sections', function (Blueprint $table) {
            $table->dropUnique('sections_class_name_unique');
        });
    }
};