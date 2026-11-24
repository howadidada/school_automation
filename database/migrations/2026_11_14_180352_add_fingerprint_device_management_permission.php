<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * إضافة صلاحية إدارة أجهزة البصمة لمدير النظام.
     */
    public function up(): void
    {
        $now = now();

        // إضافة الصلاحية إذا لم تكن موجودة.
        DB::table('permissions')->updateOrInsert(
            ['name' => 'إدارة أجهزة البصمة'],
            [
                'description' => 'إضافة أجهزة البصمة وتعديلها وإدارتها ومتابعة المزامنة',
                'updated_at' => $now,
            ]
        );

        // جلب معرف الصلاحية.
        $permissionId = DB::table('permissions')
            ->where('name', 'إدارة أجهزة البصمة')
            ->value('id');

        // جلب دور مدير النظام بالاسم، وليس برقم ثابت.
        $adminRoleId = DB::table('roles')
            ->where('name', 'admin')
            ->value('id');

        // ربط الصلاحية بمدير النظام فقط.
        if ($permissionId && $adminRoleId) {
            DB::table('permission_role')->updateOrInsert(
                [
                    'role_id' => $adminRoleId,
                    'permission_id' => $permissionId,
                ],
                [
                    'updated_at' => $now,
                ]
            );
        }
    }

    /**
     * التراجع عن إضافة الصلاحية.
     */
    public function down(): void
    {
        $permissionId = DB::table('permissions')
            ->where('name', 'إدارة أجهزة البصمة')
            ->value('id');

        if (!$permissionId) {
            return;
        }

        $adminRoleId = DB::table('roles')
            ->where('name', 'admin')
            ->value('id');

        if ($adminRoleId) {
            DB::table('permission_role')
                ->where('role_id', $adminRoleId)
                ->where('permission_id', $permissionId)
                ->delete();
        }

        DB::table('permissions')
            ->where('id', $permissionId)
            ->delete();
    }
};