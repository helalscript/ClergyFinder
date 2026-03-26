<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StorePrayerRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('api')->check() && auth('api')->user()->role === 'seeker';
    }

    public function rules(): array
    {
        return [
            'message' => 'required|string|max:5000',
            'clergy_id' => 'required|exists:clergies,id',
        ];
    }
}
