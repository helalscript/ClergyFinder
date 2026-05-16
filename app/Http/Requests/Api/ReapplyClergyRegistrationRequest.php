<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ReapplyClergyRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth('api')->user();
        return $user && $user->role === 'clergy' && $user->clergy !== null;
    }

    protected function prepareForValidation(): void
    {
        if ($this->hasFile('credential_documents') && ! is_array($this->file('credential_documents'))) {
            $this->files->set('credential_documents', [$this->file('credential_documents')]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:100',
            'phone' => 'nullable|numeric|max_digits:20|unique:users,phone,'.auth('api')->id(),
            'religion' => 'nullable|string|max:255',
            'denomination' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:255',
            'preferred_language' => 'nullable',
            'specialities' => 'nullable',
            'credential_documents' => 'required|array|min:1|max:5',
            'credential_documents.*' => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'credential_documents.required' => 'Please upload at least one credential document.',
            'credential_documents.min' => 'Please upload at least one credential document.',
            'credential_documents.max' => 'You may upload up to 5 credential documents.',
            'credential_documents.*.required' => 'Each credential must be a valid file.',
            'credential_documents.*.file' => 'Each credential must be a valid file.',
            'credential_documents.*.mimes' => 'Credentials must be PDF or image (jpg, png, webp).',
            'credential_documents.*.max' => 'Each credential file may not exceed 10 MB.',
        ];
    }
}
