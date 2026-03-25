<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $fillable = [
        'chat_room_id',
        'sender_type',
        'sender_id',
        'body',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(ChatRoom::class, 'chat_room_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}

