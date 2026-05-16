<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PrayerRequest extends Model
{
    protected $fillable = [
        'seeker_id',
        'clergy_id',
        'clergy_user_id',
        'message',
        'clergy_reply',
        'status',
        'declined_reason',
        'closed_at',
    ];

    protected $casts = [
        'closed_at' => 'datetime',
    ];

    public function seeker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seeker_id');
    }

    /**
     * The clergy profile (clergies.id) this request is addressed to.
     */
    public function clergyProfile(): BelongsTo
    {
        return $this->belongsTo(Clergy::class, 'clergy_id');
    }

    /**
     * The clergy user account (when assigned / active thread).
     */
    public function clergyUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'clergy_user_id');
    }

    public function chatRoom(): HasOne
    {
        return $this->hasOne(ChatRoom::class, 'prayer_request_id');
    }

    public function scopePendingClergy($query)
    {
        return $query->where('status', 'pending_clergy');
    }

    public function scopeActiveChat($query)
    {
        return $query->where('status', 'active');
    }
}
