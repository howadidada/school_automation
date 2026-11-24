<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'file_path',
        'section_id',
        'teacher_id',
        'type',
        'uploaded_by',
    ];

    /**
     * أنواع الجداول المعتمدة في نظام صلة.
     */
    public const TEACHER_SCHEDULE = 'teacher_schedule';
    public const STUDENT_SCHEDULE = 'student_schedule';
    public const EXAM_SCHEDULE = 'exam_schedule';

    /**
     * المستخدم الذي رفع ملف الجدول.
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * الشعبة المرتبطة بجدول الطالب أو الامتحانات.
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * المعلم المرتبط بجدول حصص المعلم.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * الاسم العربي لنوع الجدول.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            self::TEACHER_SCHEDULE => 'جدول حصص المعلم',
            self::STUDENT_SCHEDULE => 'جدول حصص الطالب',
            self::EXAM_SCHEDULE => 'جدول الامتحانات',
            default => 'غير محدد',
        };
    }
}