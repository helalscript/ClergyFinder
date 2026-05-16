<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class LiveAvailabilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('api')->check() && auth('api')->user()->role === 'clergy';
    }

    public function rules(): array
    {
        return [
            'is_available_for_live' => 'required|boolean',
            'live_unavailable_message' => 'nullable|string|max:2000',
        ];
    }
}
