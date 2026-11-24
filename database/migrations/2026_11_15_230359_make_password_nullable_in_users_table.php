<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * السماح بإنشاء حساب دون كلمة مرور.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')
                ->nullable()
                ->change();
        });
    }

    /**
     * التراجع عن التعديل.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')
                ->nullable(false)
                ->change();
        });
    }
};