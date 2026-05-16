<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class JoinLiveSessionQueueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('api')->check() && auth('api')->user()->role === 'seeker';
    }

    public function rules(): array
    {
        return [
            'clergy_id' => 'required|exists:clergies,id',
            'message' => 'nullable|string|max:5000',
        ];
    }
}
