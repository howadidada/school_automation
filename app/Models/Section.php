<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_class_id',
        'name',
        'capacity',
    ];

    /**
     * الفصل الذي تنتمي إليه الشعبة.
     */
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    /**
     * الطلاب الموجودون في هذه الشعبة.
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    /**
     * إسنادات المعلمين لهذه الشعبة.
     */
    public function teacherAssignments()
    {
        return $this->hasMany(TeacherAssignment::class);
    }

    /**
     * المعلمون الذين يدرسون هذه الشعبة.
     */
    public function teachers()
    {
        return $this->belongsToMany(
            Teacher::class,
            'teacher_assignments',
            'section_id',
            'teacher_id'
        )->withTimestamps();
    }
}