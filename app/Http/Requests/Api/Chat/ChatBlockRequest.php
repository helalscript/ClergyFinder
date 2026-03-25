<?php

namespace App\Http\Requests\Api\Chat;

use Illuminate\Foundation\Http\FormRequest;

class ChatBlockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('api')->check();
    }

    public function rules(): array
    {
        return [
            'action' => 'sometimes|in:block,unblock',
        ];
    }
}

