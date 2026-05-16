<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiveSessionRequest extends Model
{
    protected $fillable = [
        'seeker_id',
        'clergy_id',
        'clergy_user_id',
        'message',
        'meeting_link',
        'scheduled_time',
        'status',
        'video_status',
        'video_started_at',
        'video_completed_at',
        'queue_joined_at',
        'last_queue_heartbeat_at',
        'confirmation_deadline_at',
        'ready_confirmed_at',
        'agora_channel',
    ];

    protected $casts = [
        'scheduled_time' => 'datetime',
        'video_started_at' => 'datetime',
        'video_completed_at' => 'datetime',
        'queue_joined_at' => 'datetime',
        'last_queue_heartbeat_at' => 'datetime',
        'confirmation_deadline_at' => 'datetime',
        'ready_confirmed_at' => 'datetime',
    ];

    public function seeker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seeker_id');
    }

    public function clergyProfile(): BelongsTo
    {
        return $this->belongsTo(Clergy::class, 'clergy_id');
    }

    public function clergyUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'clergy_user_id');
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
