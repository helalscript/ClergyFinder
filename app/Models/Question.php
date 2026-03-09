<?php

namespace App\Models;

use App\Enums\AnswerType;
use App\Enums\UserType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'user_type',
        'question',
        'image',
        'answer_type',
        'answer',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'user_type' => UserType::class,
        'answer_type' => AnswerType::class,
        'sort_order' => 'integer',
    ];

    public function scopeClergy($query)
    {
        return $query->where('user_type', UserType::Clergy);
    }

    public function scopeSeeker($query)
    {
        return $query->where('user_type', UserType::Seeker);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function userAnswers(): HasMany
    {
        return $this->hasMany(UserAnswer::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class)->orderBy('sort_order');
    }
}
