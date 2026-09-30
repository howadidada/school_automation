<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'sender_user_id',
        'receiver_user_id',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // ===============================================================
    // المستخدم المرسل
    // ===============================================================
    public function sender(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'sender_user_id'
        );
    }

    // ===============================================================
    // المستخدم المستقبل
    // ===============================================================
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'receiver_user_id'
        );
    }
}