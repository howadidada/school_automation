<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // قد يكون العمود أُضيف أثناء المحاولة السابقة.
        if (!Schema::hasColumn('sections', 'gender_section')) {
            Schema::table('sections', function (Blueprint $table) {
                $table->string('gender_section', 10)->nullable();
            });
        }

        // إنشاء فهرس مستقل لدعم المفتاح الأجنبي.
        Schema::table('sections', function (Blueprint $table) {
            $table->index(
                'school_class_id',
                'sections_school_class_id_index'
            );
        });

        // إزالة قيد التكرار القديم.
        Schema::table('sections', function (Blueprint $table) {
            $table->dropUnique('sections_class_name_unique');
        });

        // إنشاء قيد التكرار الجديد.
        Schema::table('sections', function (Blueprint $table) {
            $table->unique(
                ['school_class_id', 'name', 'gender_section'],
                'sections_class_name_gender_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->dropUnique('sections_class_name_gender_unique');
        });

        Schema::table('sections', function (Blueprint $table) {
            $table->unique(
                ['school_class_id', 'name'],
                'sections_class_name_unique'
            );
        });

        Schema::table('sections', function (Blueprint $table) {
            $table->dropIndex('sections_school_class_id_index');
        });

        Schema::table('sections', function (Blueprint $table) {
            $table->dropColumn('gender_section');
        });
    }
};