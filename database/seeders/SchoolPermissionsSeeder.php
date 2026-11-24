<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class SchoolPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء الصلاحيات إذا لم تكن موجودة
        $permissions = [
            'attendance.view' => 'عرض سجلات الحضور والغياب',
            'attendance.followup' => 'تسجيل متابعة غياب الطلاب',
            'excuses.view' => 'عرض أعذار غياب الطلاب',
            'excuses.forward' => 'إحالة أعذار الطلاب للجهة المختصة',
        ];

        foreach ($permissions as $name => $description) {
            Permission::firstOrCreate(
                ['name' => $name],
                ['description' => $description]
            );
        }

        // الحصول على دور السكرتيرة
        $secretary = Role::where('name', 'secretary')->firstOrFail();

        // ربط الصلاحيات بالسكرتيرة دون إزالة أي صلاحيات سابقة
        $permissionIds = Permission::whereIn(
            'name',
            array_keys($permissions)
        )->pluck('id')->all();

        $secretary->permissions()->syncWithoutDetaching($permissionIds);
    }
}