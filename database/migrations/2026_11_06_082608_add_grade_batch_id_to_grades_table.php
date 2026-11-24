<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * ربط درجات الطلاب بمجموعات التقييم.
     */
    public function up(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->foreignId('grade_batch_id')
                ->nullable()
                ->constrained('grade_batches')
                ->restrictOnDelete();
        });
    }

    /**
     * التراجع عن الربط عند الحاجة.
     */
    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropConstrainedForeignId('grade_batch_id');
        });
    }
};