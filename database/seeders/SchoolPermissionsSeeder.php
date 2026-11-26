<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\Permission;

class SchoolPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // صلاحيات السكرتارية
            $secretaryPermissions = [
                'attendance.view' => 'عرض سجلات الحضور والغياب',
                'attendance.followup' => 'تسجيل متابعة غياب الطلاب',
                'excuses.view' => 'عرض أعذار غياب الطلاب',
                'excuses.forward' => 'إحالة أعذار الطلاب للجهة المختصة',
            ];

            // صلاحيات المعلم
            $teacherPermissions = [
                'grades.view' => 'عرض الدرجات',
                'grades.create' => 'إدخال الدرجات وإنشاء كشوف الدرجات',
            ];

            // إنشاء الصلاحيات
            $permissions = array_merge(
                $secretaryPermissions,
                $teacherPermissions
            );

            foreach ($permissions as $name => $description) {
                Permission::firstOrCreate(
                    ['name' => $name],
                    ['description' => $description]
                );
            }

            // ربط صلاحيات السكرتارية
            $secretary = Role::where(
                'name',
                'secretary'
            )->firstOrFail();

            $secretaryPermissionIds = Permission::whereIn(
                'name',
                array_keys($secretaryPermissions)
            )->pluck('id')->all();

            $secretary->permissions()->syncWithoutDetaching(
                $secretaryPermissionIds
            );

            // ربط صلاحيات المعلم
            $teacher = Role::where(
                'name',
                'teacher'
            )->firstOrFail();

            $teacherPermissionIds = Permission::whereIn(
                'name',
                array_keys($teacherPermissions)
            )->pluck('id')->all();

            $teacher->permissions()->syncWithoutDetaching(
                $teacherPermissionIds
            );
        });
    }
}