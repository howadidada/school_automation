<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'grade_level',
    ];

    /**
     * الشعب التابعة لهذا الفصل.
     */
    public function sections()
    {
        return $this->hasMany(Section::class);
    }
}