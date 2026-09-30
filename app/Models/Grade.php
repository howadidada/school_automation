<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    protected $fillable = [
        'student_id',
        'teacher_id',
        'subject_id',
        'evaluation_name',
        'score',
        'max_score',
        'note',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'max_score' => 'decimal:2',
    ];

    /**
     * الطالب صاحب الدرجة
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * المعلم الذي أدخل الدرجة
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * المادة الخاصة بالدرجة
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}