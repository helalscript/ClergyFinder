<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    // Define the table name if it's not the default
    protected $table = 'contact_us';

    // Fillable columns for mass assignment
    protected $fillable = [
        'type',
        'full_name',
        'file',
        'ip_address',
        'email',
        'message',
        'is_read',
        'is_agree',
    ];

    // Column casts for type safety
    protected $casts = [
        'type' => 'string',
        'full_name' => 'string',
        'file' => 'string',
        'is_read' => 'boolean',
        'is_agree' => 'boolean',
        'ip_address' => 'string',
        'email' => 'string',
        'message' => 'string',
    ];
}
