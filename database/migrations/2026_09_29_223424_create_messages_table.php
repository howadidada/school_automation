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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            // المستخدم الذي أرسل الرسالة
            $table->foreignId('sender_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // المستخدم الذي استقبل الرسالة
            $table->foreignId('receiver_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // نص الرسالة
            $table->text('message');

            // هل تم قراءة الرسالة؟
            $table->boolean('is_read')
                ->default(false);

            $table->timestamps();

            // لتحسين سرعة جلب الرسائل
            $table->index([
                'sender_user_id',
                'receiver_user_id',
            ]);

            $table->index([
                'receiver_user_id',
                'is_read',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};