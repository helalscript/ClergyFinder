<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Clergy extends Model
{
    protected $fillable = [
        'user_id',
        'religion',
        'denomination',
        'preferred_language',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'latitude',
        'longitude',
        'bio',
        'credential_documents',
        'registration_rejection_reason',
        'registration_reviewed_at',
        'available_at',
        'specialities',
        'is_verified',
        'is_live_session',
        'is_prayer_request',
        'is_available_for_live',
        'live_unavailable_message',
        'registration_status',
        'status',
    ];

    protected $casts = [
        'preferred_language' => 'array',
        'specialities' => 'array',
        'credential_documents' => 'array',
        'is_verified' => 'boolean',
        'is_live_session' => 'boolean',
        'is_prayer_request' => 'boolean',
        'is_available_for_live' => 'boolean',
        'registration_reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublicDirectory(Builder $query): Builder
    {
        return $query
            ->where('registration_status', 'approved')
            ->where('status', 'active')
            ->whereHas('user', fn ($q) => $q->where('status', 'active'));
    }


    /**
     * @return list<string>|null
     */
    public function getCredentialDocumentsAttribute(mixed $value): ?array
    {
        $documents = $this->normalizeCredentialDocuments($value);

        if ($documents === null) {
            return null;
        }

        if (request()->is('api/*')) {
            return array_map(function (string $item): string {
                if (filter_var($item, FILTER_VALIDATE_URL)) {
                    return $item;
                }

                return url($item);
            }, $documents);
        }

        return $documents;
    }

    /**
     * @return list<string>|null
     */
    protected function normalizeCredentialDocuments(mixed $value): ?array
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_array($value)) {
            return array_values(array_filter(array_map('strval', $value)));
        }

        if (! is_string($value)) {
            return null;
        }

        $decoded = json_decode($value, true);
        if (is_array($decoded)) {
            return array_values(array_filter(array_map('strval', $decoded)));
        }

        // Legacy: single URL or relative path stored as plain string
        return [$value];
    }

    /**
     * Relative storage paths for file operations (bypasses API URL accessor).
     *
     * @return list<string>
     */
    public function getCredentialDocumentStoragePaths(): array
    {
        $raw = $this->getAttributes()['credential_documents'] ?? null;

        return $this->normalizeCredentialDocuments($raw) ?? [];
    }
}
