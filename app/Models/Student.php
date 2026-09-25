<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'section_id',
        'student_number',
        'date_of_birth',
        'gender',
    ];

    /**
     * حساب المستخدم الخاص بالطالب.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * الشعبة التي ينتمي إليها الطالب.
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * أولياء الأمور المرتبطون بهذا الطالب.
     */
    public function parents()
    {
        return $this->belongsToMany(
            ParentModel::class,
            'parent_students',
            'student_id',
            'parent_id'
        )
        ->withPivot('relation')
        ->withTimestamps();
    }
}