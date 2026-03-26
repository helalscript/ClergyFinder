<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table = 'faqs';
    protected $fillable = ['question', 'answer', 'status', 'faq_category_id'];

    protected $casts = [
        'question' => 'string',
        'answer' => 'string',
        'status' => 'string',
    ];

    public function category()
    {
        return $this->belongsTo(FaqCategory::class, 'faq_category_id');
    }

}
