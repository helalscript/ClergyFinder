<?php

namespace App\Policies;

use App\Models\LiveSessionRequest;
use App\Models\User;

class LiveSessionRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['seeker', 'clergy', 'admin']);
    }

    public function view(User $user, LiveSessionRequest $liveSessionRequest): bool
    {
        if ($user->role === 'admin') {
            return true;
        }
        if ($user->role === 'seeker') {
            return $liveSessionRequest->seeker_id === $user->id;
        }
        if ($user->role === 'clergy') {
            return $user->clergy?->is_live_session === true;
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->role === 'seeker';
    }

    public function update(User $user, LiveSessionRequest $liveSessionRequest): bool
    {
        return $user->role === 'admin' || ($user->role === 'clergy' && $user->clergy?->is_live_session);
    }

    public function delete(User $user, LiveSessionRequest $liveSessionRequest): bool
    {
        return $user->role === 'admin';
    }
}
