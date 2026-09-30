<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'teacher_id',
        'subject_id',
        'type',
        'note',
        'note_date',
    ];

    protected $casts = [
        'note_date' => 'date',
    ];

    // ===============================================================
    // الطالب
    // ===============================================================
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // ===============================================================
    // المعلم
    // ===============================================================
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    // ===============================================================
    // المادة - اختيارية
    // ===============================================================
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}