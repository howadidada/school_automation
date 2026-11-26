<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_settings', function (Blueprint $table) {

            $table->id();

            $table->string('academic_year', 20);

            $table->string('semester', 30);

            $table->json('evaluation_periods');

            $table->boolean('is_active')
                ->default(false);

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();

            $table->unique([
                'academic_year',
                'semester',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_settings');
    }
};