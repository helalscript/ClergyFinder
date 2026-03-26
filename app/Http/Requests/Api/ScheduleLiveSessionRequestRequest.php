<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleLiveSessionRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('api')->check() && auth('api')->user()->role === 'clergy';
    }

    public function rules(): array
    {
        return [
            'meeting_link' => 'required|string|url|max:500',
            'scheduled_time' => 'required|date|after:now',
        ];
    }
}
