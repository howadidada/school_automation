<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'priority',
        'status',
        'published_at',
        'expires_at',
        'created_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public const PRIORITY_NORMAL = 'normal';
    public const PRIORITY_IMPORTANT = 'important';
    public const PRIORITY_URGENT = 'urgent';

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_SCHEDULED = 'scheduled';

    /*
    |--------------------------------------------------------------------------
    | المستخدم الذي أنشأ الإعلان
    |--------------------------------------------------------------------------
    */

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /*
    |--------------------------------------------------------------------------
    | الفئات المستهدفة
    |--------------------------------------------------------------------------
    */

    public function targets(): HasMany
    {
        return $this->hasMany(AnnouncementTarget::class);
    }

    /*
    |--------------------------------------------------------------------------
    | الإعلان المنشور والفعال
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query
            ->whereIn('status', [
                self::STATUS_PUBLISHED,
                self::STATUS_SCHEDULED,
            ])
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            });
    }

    /*
    |--------------------------------------------------------------------------
    | اسم الأولوية بالعربية
    |--------------------------------------------------------------------------
    */

    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority) {
            self::PRIORITY_IMPORTANT => 'مهم',
            self::PRIORITY_URGENT => 'عاجل',
            default => 'عادي',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | حالة الإعلان بالعربية
    |--------------------------------------------------------------------------
    */

    public function getStatusLabelAttribute(): string
    {
        if (
            $this->status === self::STATUS_SCHEDULED
            && $this->published_at?->isPast()
        ) {
            return 'منشور';
        }

        return match ($this->status) {
            self::STATUS_PUBLISHED => 'منشور',
            self::STATUS_SCHEDULED => 'مجدول',
            default => 'مسودة',
        };
    }
}