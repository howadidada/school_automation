<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FingerprintDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_code',
        'name',
        'device_type',
        'manufacturer',
        'model',
        'location',
        'connection_type',
        'ip_address',
        'port',
        'is_active',
        'last_sync_at',
        'last_seen_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'port' => 'integer',
            'last_sync_at' => 'datetime',
            'last_seen_at' => 'datetime',
        ];
    }
}