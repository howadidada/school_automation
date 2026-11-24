<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {

            $table->id();

            $table->string('title');

            $table->longText('content');

            $table->enum('priority', [
                'normal',
                'important',
                'urgent'
            ])->default('normal');

            $table->enum('status', [
                'draft',
                'published',
                'scheduled'
            ])->default('draft');

            $table->timestamp('published_at')->nullable();

            $table->timestamp('expires_at')->nullable();

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();

            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};