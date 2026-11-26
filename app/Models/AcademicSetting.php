<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicSetting extends Model
{
    protected $fillable = [
        'academic_year',
        'semester',
        'evaluation_periods',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'evaluation_periods' => 'array',
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}