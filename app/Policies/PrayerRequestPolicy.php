<?php

namespace App\Policies;

use App\Models\PrayerRequest;
use App\Models\User;

class PrayerRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['seeker', 'clergy', 'admin']);
    }

    public function view(User $user, PrayerRequest $prayerRequest): bool
    {
        if ($user->role === 'admin') {
            return true;
        }
        if ($user->role === 'seeker') {
            return $prayerRequest->seeker_id === $user->id;
        }
        if ($user->role === 'clergy') {
            return $user->clergy?->is_prayer_request === true;
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->role === 'seeker';
    }

    public function update(User $user, PrayerRequest $prayerRequest): bool
    {
        return $user->role === 'admin' || ($user->role === 'clergy' && $user->clergy?->is_prayer_request);
    }

    public function delete(User $user, PrayerRequest $prayerRequest): bool
    {
        return $user->role === 'admin';
    }
}
