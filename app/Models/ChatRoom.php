<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatRoom extends Model
{
    protected $fillable = [
        'type',
        'prayer_request_id',
        'live_session_request_id',
        'seeker_id',
        'clergy_id',
        'status',
        'video_status',
        'video_started_at',
        'video_completed_at',
    ];

    protected $casts = [
        'video_started_at' => 'datetime',
        'video_completed_at' => 'datetime',
    ];

    public function prayerRequest(): BelongsTo
    {
        return $this->belongsTo(PrayerRequest::class, 'prayer_request_id');
    }

    public function liveSessionRequest(): BelongsTo
    {
        return $this->belongsTo(LiveSessionRequest::class, 'live_session_request_id');
    }

    public function seeker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seeker_id');
    }

    public function clergy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'clergy_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->latest('id');
    }

    public function presences(): HasMany
    {
        return $this->hasMany(ChatPresence::class);
    }
}

