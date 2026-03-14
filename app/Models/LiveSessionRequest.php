<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiveSessionRequest extends Model
{
    protected $fillable = [
        'seeker_id',
        'clergy_id',
        'message',
        'meeting_link',
        'scheduled_time',
        'status',
    ];

    protected $casts = [
        'scheduled_time' => 'datetime',
    ];

    public function seeker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seeker_id');
    }

    public function clergy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'clergy_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
