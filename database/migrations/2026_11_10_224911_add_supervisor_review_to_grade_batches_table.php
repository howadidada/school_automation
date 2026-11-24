<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('grade_batches', function (Blueprint $table) {

            // المشرف الذي راجع كشف الدرجات
            $table->foreignId('supervisor_reviewed_by')
                ->nullable()
                ->constrained('users')
                ->restrictOnDelete();

            // وقت انتهاء مراجعة المشرف
            $table->timestamp('supervisor_reviewed_at')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('grade_batches', function (Blueprint $table) {

            $table->dropForeign([
                'supervisor_reviewed_by'
            ]);

            $table->dropColumn([
                'supervisor_reviewed_by',
                'supervisor_reviewed_at',
            ]);
        });
    }
};