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
        'first_name',
        'last_name',
        'full_name',
        'company_name',
        'linkedin_profile',
        'file',
        'ip_address',
        'email',
        'phone',
        'message',
        'is_read',
        'is_agree',
    ];

    // Column casts for type safety
    protected $casts = [
        'type' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'full_name' => 'string',
        'company_name' => 'string',
        'linkedin_profile' => 'string',
        'file' => 'string',
        'is_read' => 'boolean',
        'is_agree' => 'boolean',
        'ip_address' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'message' => 'string',
    ];
}
