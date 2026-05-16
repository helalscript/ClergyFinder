<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class DeclinePrayerRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('api')->check() && auth('api')->user()->role === 'clergy';
    }

    public function rules(): array
    {
        return [
            'reason' => 'nullable|string|max:2000',
        ];
    }
}
