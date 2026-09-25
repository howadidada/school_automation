<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'section_id',
    ];

    /**
     * المعلم المرتبط بهذا الإسناد.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * الشعبة المرتبطة بهذا الإسناد.
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}