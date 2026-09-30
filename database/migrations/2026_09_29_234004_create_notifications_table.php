<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            // المستخدم صاحب الإشعار
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // نوع الإشعار
            // task, grade, announcement, schedule, message ...
            $table->string('type');

            // عنوان الإشعار
            $table->string('title');

            // نص الإشعار
            $table->text('body');

            // هل تم قراءة الإشعار؟
            $table->boolean('is_read')
                ->default(false);

            // رقم العنصر المرتبط بالإشعار إن وجد
            // مثل message_id أو task_id أو grade_id
            $table->unsignedBigInteger('reference_id')
                ->nullable();

            // نوع المرجع المرتبط
            $table->string('reference_type')
                ->nullable();

            $table->timestamps();

            $table->index([
                'user_id',
                'is_read',
            ]);

            $table->index([
                'reference_type',
                'reference_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};