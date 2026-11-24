<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * جعل سعة الشعبة إلزامية.
     */
    public function up(): void
    {
        // التأكد من عدم وجود شعب بدون سعة صحيحة
        $invalidSections = DB::table('sections')
            ->whereNull('capacity')
            ->orWhere('capacity', '<', 1)
            ->count();

        if ($invalidSections > 0) {
            throw new RuntimeException(
                'توجد شعب بدون سعة صحيحة. يجب تصحيحها قبل تنفيذ Migration.'
            );
        }

        Schema::table('sections', function (Blueprint $table) {
            $table->unsignedInteger('capacity')
                ->nullable(false)
                ->change();
        });
    }

    /**
     * التراجع عن التعديل عند الحاجة.
     */
    public function down(): void
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->unsignedInteger('capacity')
                ->nullable()
                ->change();
        });
    }
};