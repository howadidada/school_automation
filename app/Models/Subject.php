<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * المعلمون المرتبطون بهذه المادة الدراسية.
     */
    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    /**
     * التوزيعات التدريسية المرتبطة بهذه المادة.
     */
    public function teacherAssignments()
    {
        return $this->hasMany(TeacherAssignment::class);
    }
}