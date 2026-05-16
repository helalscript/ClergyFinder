<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClergyProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('api')->check() && auth('api')->user()->role === 'clergy';
    }

    public function rules(): array
    {
        $userId = auth('api')->id();

        return [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'nullable|unique:users,phone,'.$userId.'|numeric|max_digits:20',
            'gender' => 'nullable|string|in:male,female,others',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'cover_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'religion' => 'nullable|string|max:255',
            'denomination' => 'nullable|string|max:255',
            'preferred_language' => 'nullable',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'bio' => 'nullable|string',
            'available_at' => 'nullable|string|max:255',
            'specialities' => 'nullable',
            'is_live_session' => 'nullable|boolean',
            'is_prayer_request' => 'nullable|boolean',
            'is_available_for_live' => 'nullable|boolean',
            'live_unavailable_message' => 'nullable|string|max:2000',
        ];
    }
}
