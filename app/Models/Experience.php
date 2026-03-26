<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'start_date',
        'end_date',
        'role',
        'company',
        'responsibilities',
        'is_currently_working',
        'status',
    ];

    protected $casts = [
        'candidate_id'         => 'integer',
        'start_date'           => 'date',
        'end_date'             => 'date',
        'role'                 => 'string',
        'company'              => 'string',
        'responsibilities'     => 'array', // JSON field
        'is_currently_working' => 'boolean',
        'status'               => 'string',
        'created_at'           => 'datetime',
        'updated_at'           => 'datetime',
    ];

    /**
     * Relationships
     */
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
