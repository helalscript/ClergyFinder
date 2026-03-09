<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAnswer extends Model
{
    protected $table = 'user_answers';

    protected $fillable = [
        'user_id',
        'question_id',
        'user_answer',
        'selected_option_ids',
    ];

    protected $casts = [
        'selected_option_ids' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
