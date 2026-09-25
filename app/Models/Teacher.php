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
    ];

    /**
     * حساب المستخدم الخاص بالمعلم.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * المادة التي يدرسها المعلم.
     * كل معلم له مادة واحدة فقط.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * الشعب التي تم إسنادها للمعلم.
     */
    public function assignments()
    {
        return $this->hasMany(TeacherAssignment::class);
    }

    /**
     * الشعب التي يدرسها المعلم.
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