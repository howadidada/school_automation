<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject_id',
        'specialization',
        'education_stage',
    ];

    /**
     * حساب المستخدم الخاص بالمعلم.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * المادة الأساسية للمعلم.
     *
     * نحتفظ بها حاليًا للتوافق مع النظام القديم،
     * أما المواد الفعلية المسندة للمعلم فتحدد
     * من خلال teacher_assignments.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * إسنادات المعلم:
     * المادة + الشعبة.
     */
    public function assignments()
    {
        return $this->hasMany(TeacherAssignment::class);
    }

    /**
     * الشعب المرتبطة بالمعلم.
     */
    public function sections()
    {
        return $this->belongsToMany(
            Section::class,
            'teacher_assignments',
            'teacher_id',
            'section_id'
        )->withTimestamps();
    }
}