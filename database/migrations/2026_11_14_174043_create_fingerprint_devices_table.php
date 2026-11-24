<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * إنشاء جدول أجهزة البصمة.
     */
    public function up(): void
    {
        Schema::create('fingerprint_devices', function (Blueprint $table) {
            $table->id();

            // معرف الجهاز الفريد داخل نظام صلة
            $table->string('device_code')->unique();

            // اسم الجهاز
            $table->string('name');

            // نوع الجهاز: تطبيق Flutter أو جهاز حقيقي
            $table->enum('device_type', [
                'flutter_simulator',
                'physical',
            ])->default('flutter_simulator');

            // الشركة المصنعة والموديل (للأجهزة الحقيقية)
            $table->string('manufacturer')->nullable();
            $table->string('model')->nullable();

            // موقع الجهاز داخل المدرسة
            $table->string('location')->nullable();

            // طريقة الاتصال بالجهاز
            $table->enum('connection_type', [
                'api',
                'tcp_ip',
                'usb',
            ])->default('api');

            // عنوان الجهاز على الشبكة (عند الحاجة)
            $table->string('ip_address')->nullable();

            // منفذ الاتصال
            $table->unsignedInteger('port')->nullable();

            // حالة تفعيل الجهاز
            $table->boolean('is_active')->default(true);

            // آخر مزامنة ناجحة
            $table->timestamp('last_sync_at')->nullable();

            // آخر مرة تواصل فيها الجهاز مع النظام
            $table->timestamp('last_seen_at')->nullable();

            // ملاحظات إدارية
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * حذف الجدول عند التراجع عن الترحيل.
     */
    public function down(): void
    {
        Schema::dropIfExists('fingerprint_devices');
    }
};