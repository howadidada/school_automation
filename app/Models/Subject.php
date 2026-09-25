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
     * المعلمون الذين يدرسون هذه المادة.
     */
    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }
}