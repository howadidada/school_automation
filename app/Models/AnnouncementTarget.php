<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnnouncementTarget extends Model
{
    protected $fillable = [
        'announcement_id',
        'role',
        'section_gender',
        'education_stage',
        'school_class_id',
        'section_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | الإعلان المرتبط بالفئة
    |--------------------------------------------------------------------------
    */

    public function announcement(): BelongsTo
    {
        return $this->belongsTo(Announcement::class);
    }

    /*
    |--------------------------------------------------------------------------
    | الصف الدراسي
    |--------------------------------------------------------------------------
    */

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    /*
    |--------------------------------------------------------------------------
    | الشعبة الدراسية
    |--------------------------------------------------------------------------
    */

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
}