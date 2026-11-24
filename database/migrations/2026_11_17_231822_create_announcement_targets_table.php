<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcement_targets', function (Blueprint $table) {

            $table->id();

            $table->foreignId('announcement_id')
                ->constrained('announcements')
                ->cascadeOnDelete();

            // الفئة المستهدفة
            $table->string('role');

            // القسم: بنين أو بنات
            $table->string('section_gender')->nullable();

            // المرحلة الدراسية
            $table->string('education_stage')->nullable();

            // الصف الدراسي
            $table->foreignId('school_class_id')
                ->nullable()
                ->constrained('school_classes')
                ->nullOnDelete();

            // الشعبة
            $table->foreignId('section_id')
                ->nullable()
                ->constrained('sections')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['role', 'education_stage']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcement_targets');
    }
};