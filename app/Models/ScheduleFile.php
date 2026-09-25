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
        'uploaded_by',
    ];

    /**
     * المستخدم الذي رفع ملف الجدول الدراسي.
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}