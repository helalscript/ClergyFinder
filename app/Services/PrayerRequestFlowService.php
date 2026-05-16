<?php

namespace App\Services;

use App\Models\PrayerRequest;
use App\Models\User;
use App\Notifications\Chat\PrayerRequestAcceptedToSeekerInApp;
use App\Notifications\Chat\PrayerRequestClosedToSeekerInApp;
use App\Notifications\Chat\PrayerRequestDeclinedToSeekerInApp;
use App\Services\Chat\ChatRoomService;
use Illuminate\Support\Facades\DB;

class PrayerRequestFlowService
{
    public function __construct(
        private ChatRoomService $chatRoomService
    ) {}

    public function accept(User $clergyUser, PrayerRequest $prayerRequest): PrayerRequest
    {
        $clergy = $clergyUser->clergy;
        if (! $clergy || ! $clergy->is_prayer_request) {
            throw new \RuntimeException('not_allowed');
        }

        if ((int) $prayerRequest->clergy_id !== (int) $clergy->id) {
            throw new \RuntimeException('forbidden');
        }

        if ($prayerRequest->status !== 'pending_clergy') {
            throw new \RuntimeException('invalid_status');
        }

        return DB::transaction(function () use ($prayerRequest, $clergyUser) {
            $prayerRequest->update([
                'status' => 'active',
                'clergy_user_id' => $clergyUser->id,
            ]);

            $fresh = $prayerRequest->fresh();
            $room = $this->chatRoomService->createPrayerChatRoom($fresh);

            if ($fresh->seeker) {
                $fresh->seeker->notify(new PrayerRequestAcceptedToSeekerInApp($fresh, $room));
            }

            $fresh->setAttribute('chat_room_id', $room->id);

            return $fresh;
        });
    }

    public function decline(User $clergyUser, PrayerRequest $prayerRequest, ?string $reason): PrayerRequest
    {
        $clergy = $clergyUser->clergy;
        if (! $clergy || ! $clergy->is_prayer_request) {
            throw new \RuntimeException('not_allowed');
        }

        if ((int) $prayerRequest->clergy_id !== (int) $clergy->id) {
            throw new \RuntimeException('forbidden');
        }

        if ($prayerRequest->status !== 'pending_clergy') {
            throw new \RuntimeException('invalid_status');
        }

        $prayerRequest->update([
            'status' => 'declined',
            'declined_reason' => $reason,
        ]);

        $fresh = $prayerRequest->fresh();

        if ($fresh->seeker) {
            $fresh->seeker->notify(new PrayerRequestDeclinedToSeekerInApp($fresh));
        }

        return $fresh;
    }

    public function close(User $clergyUser, PrayerRequest $prayerRequest): PrayerRequest
    {
        $clergy = $clergyUser->clergy;
        if (! $clergy || ! $clergy->is_prayer_request) {
            throw new \RuntimeException('not_allowed');
        }

        if ((int) $prayerRequest->clergy_id !== (int) $clergy->id) {
            throw new \RuntimeException('forbidden');
        }

        if ($prayerRequest->status !== 'active') {
            throw new \RuntimeException('invalid_status');
        }

        $prayerRequest->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        $room = $prayerRequest->chatRoom;
        if ($room) {
            $room->update(['status' => 'completed']);
        }

        $fresh = $prayerRequest->fresh();

        if ($fresh->seeker) {
            $fresh->seeker->notify(new PrayerRequestClosedToSeekerInApp($fresh));
        }

        return $fresh;
    }
}
