<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FaqCategory extends Model
{
    protected $table = 'faq_categories';

    protected $fillable = ['name', 'status'];

    protected $casts = [
        'name' => 'string',
        'status' => 'string',
    ];

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class, 'faq_category_id');
    }
}

