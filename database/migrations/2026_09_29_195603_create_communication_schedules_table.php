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
        Schema::create('communication_schedules', function (Blueprint $table) {

            $table->id();

            // ربط جدول أوقات التواصل بالمعلم
            $table->foreignId('teacher_id')
                ->constrained('teachers')
                ->cascadeOnDelete();

            // اسم اليوم
            $table->string('day_of_week');

            // هل اليوم مفعل للتواصل
            $table->boolean('enabled')
                ->default(false);

            // وقت بداية استقبال الرسائل
            $table->time('start_time')
                ->nullable();

            // وقت نهاية استقبال الرسائل
            $table->time('end_time')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communication_schedules');
    }
};