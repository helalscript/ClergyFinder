<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clergy extends Model
{
    protected $fillable = [
        'religion',
        'preferred_language',
        'address',
        'latitude',
        'longitude',
        'bio',
        'available_at',
        'speciaalities',
        'is_verified',
        'is_live_session',
        'is_prayer_request',
        'status',
    ];
}
