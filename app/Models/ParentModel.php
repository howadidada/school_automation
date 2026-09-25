<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentModel extends Model
{
    use HasFactory;

    protected $table = 'parents';

    protected $fillable = [
        'user_id',
    ];

    /**
     * حساب المستخدم الخاص بولي الأمر.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * الطلاب المرتبطون بولي الأمر.
     */
    public function students()
    {
        return $this->belongsToMany(
            Student::class,
            'parent_students',
            'parent_id',
            'student_id'
        )
        ->withPivot('relation')
        ->withTimestamps();
    }
}