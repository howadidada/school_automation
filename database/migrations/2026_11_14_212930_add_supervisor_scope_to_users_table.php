<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * إضافة المرحلة والقسم إلى حسابات المستخدمين.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('education_stage', 30)
                ->nullable();

            $table->string('gender_section', 10)
                ->nullable();

        });
    }

    /**
     * التراجع عن التعديلات.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'education_stage',
                'gender_section',
            ]);

        });
    }
};