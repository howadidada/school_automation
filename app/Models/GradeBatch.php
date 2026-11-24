<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GradeBatch extends Model
{
    protected $fillable = [
        'teacher_id',
        'subject_id',
        'section_id',
        'academic_year',
        'semester',
        'evaluation_name',
        'evaluation_period',
        'max_score',
        'status',
        'submitted_at',

        // مراجعة المشرف
        'supervisor_reviewed_by',
        'supervisor_reviewed_at',

        // الاعتماد النهائي من الوكيل
        'approved_by',
        'approved_at',

        // بيانات إرجاع الكشف للتعديل
        'rejection_reason',
        'returned_at',
    ];

    protected $casts = [
        'max_score' => 'decimal:2',
        'submitted_at' => 'datetime',
        'supervisor_reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    // المعلم صاحب كشف الدرجات
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    // المادة الدراسية
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    // الشعبة الدراسية
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    // درجات الطلاب التابعة للكشف
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    // المشرف الذي راجع كشف الدرجات
    public function supervisorReviewer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'supervisor_reviewed_by'
        );
    }

    // الوكيل الذي اعتمد كشف الدرجات نهائيًا
    public function approver(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }
}