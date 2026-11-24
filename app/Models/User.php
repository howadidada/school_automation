<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * الحقول المسموح بحفظها وتحديثها.
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role_id',
        'is_active',
        'education_stage',
        'gender_section',
    ];

    /**
     * الحقول المخفية عند تحويل النموذج إلى JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * تحويل أنواع البيانات.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * الدور الخاص بالمستخدم.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * بيانات المعلم.
     */
    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    /**
     * بيانات الطالب.
     */
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    /**
     * بيانات ولي الأمر.
     */
    public function parentProfile()
    {
        return $this->hasOne(ParentModel::class);
    }

    /**
     * ملفات الجدول التي رفعها المستخدم.
     */
    public function scheduleFiles()
    {
        return $this->hasMany(
            ScheduleFile::class,
            'uploaded_by'
        );
    }

    /**
     * التحقق من دور المستخدم.
     */
    public function hasRole(string $role): bool
    {
        return $this->role?->name === $role;
    }

    /**
     * التحقق من امتلاك صلاحية معينة.
     */
    public function hasPermission(string $permission): bool
    {
        if (!$this->role) {
            return false;
        }

        return $this->role
            ->permissions()
            ->where('permissions.name', $permission)
            ->exists();
    }
}