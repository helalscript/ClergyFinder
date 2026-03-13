<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clergy extends Model
{
    protected $fillable = [
        'user_id',
        'religion',
        'preferred_language',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'latitude',
        'longitude',
        'bio',
        'available_at',
        'specialities',
        'is_verified',
        'is_live_session',
        'is_prayer_request',
        'status',
    ];

    protected $casts = [
        'preferred_language' => 'array',
        'specialities' => 'array',
        'is_verified' => 'boolean',
        'is_live_session' => 'boolean',
        'is_prayer_request' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
