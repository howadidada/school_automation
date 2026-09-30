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
        'uploaded_by',
    ];

    /**
     * المستخدم الذي رفع ملف الجدول الدراسي.
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * الشعبة التي ينتمي إليها الجدول الدراسي.
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}