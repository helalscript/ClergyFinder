<?php

namespace App\Services;

use App\Models\Clergy;
use App\Models\LiveSessionRequest;
use App\Models\PrayerRequest;
use App\Models\User;
use App\Notifications\Chat\LiveSessionConfirmTimeoutInApp;
use App\Notifications\Chat\LiveSessionQueueAbandonedInApp;
use App\Notifications\Chat\LiveSessionSeekerConfirmedReadyInApp;
use App\Notifications\Chat\LiveSessionYourTurnInApp;
use App\Notifications\Chat\LiveSessionSeekerJoinedQueueInApp;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LiveSessionQueueService
{
    public const HEARTBEAT_TIMEOUT_SECONDS = 45;

    public const CONFIRM_READY_SECONDS = 120;

    public static function blockingStatuses(): array
    {
        return ['awaiting_confirm', 'ready', 'active'];
    }

    public static function activeQueueStatuses(): array
    {
        return ['queued', 'awaiting_confirm', 'ready', 'active'];
    }

    public function purgeStaleQueueEntries(?int $clergyTableId = null): void
    {
        $now = Carbon::now();
        $heartbeatCutoff = $now->copy()->subSeconds(self::HEARTBEAT_TIMEOUT_SECONDS);

        $q = LiveSessionRequest::query()
            ->where('status', 'queued')
            ->where(function ($w) use ($heartbeatCutoff) {
                $w->whereNull('last_queue_heartbeat_at')
                    ->orWhere('last_queue_heartbeat_at', '<', $heartbeatCutoff);
            });

        if ($clergyTableId !== null) {
            $q->where('clergy_id', $clergyTableId);
        }

        $stale = $q->get();
        foreach ($stale as $row) {
            $this->markAbandoned($row, 'Removed from queue due to inactivity.');
        }

        $awaitingHb = LiveSessionRequest::query()
            ->where('status', 'awaiting_confirm')
            ->where(function ($w) use ($heartbeatCutoff) {
                $w->whereNull('last_queue_heartbeat_at')
                    ->orWhere('last_queue_heartbeat_at', '<', $heartbeatCutoff);
            });

        if ($clergyTableId !== null) {
            $awaitingHb->where('clergy_id', $clergyTableId);
        }

        foreach ($awaitingHb->get() as $row) {
            $this->markConfirmTimeoutAndAdvance($row);
        }

        $confirmQ = LiveSessionRequest::query()
            ->where('status', 'awaiting_confirm')
            ->whereNotNull('confirmation_deadline_at')
            ->where('confirmation_deadline_at', '<', $now);

        if ($clergyTableId !== null) {
            $confirmQ->where('clergy_id', $clergyTableId);
        }

        foreach ($confirmQ->get() as $row) {
            $this->markConfirmTimeoutAndAdvance($row);
        }
    }

    public function markAbandoned(LiveSessionRequest $req, string $reason = 'Removed from queue.'): void
    {
        if ($req->status !== 'queued') {
            return;
        }

        $req->update([
            'status' => 'abandoned',
            'confirmation_deadline_at' => null,
        ]);

        if ($req->seeker) {
            $req->seeker->notify(new LiveSessionQueueAbandonedInApp($req, $reason));
        }

        if ($req->clergy_id) {
            $this->maybePromoteNextInQueue($req->clergy_id);
        }
    }

    public function markConfirmTimeoutAndAdvance(LiveSessionRequest $req): void
    {
        if ($req->status !== 'awaiting_confirm') {
            return;
        }

        $clergyId = $req->clergy_id;

        $req->update([
            'status' => 'confirm_timeout',
            'confirmation_deadline_at' => null,
        ]);

        if ($req->seeker) {
            $req->seeker->notify(new LiveSessionConfirmTimeoutInApp($req));
        }

        if ($clergyId) {
            $this->maybePromoteNextInQueue($clergyId);
        }
    }

    public function seekerHasActiveQueueMembership(User $seeker): bool
    {
        return LiveSessionRequest::query()
            ->where('seeker_id', $seeker->id)
            ->whereIn('status', self::activeQueueStatuses())
            ->exists();
    }

    public function hasBlockingSessionForClergy(int $clergyTableId): bool
    {
        return LiveSessionRequest::query()
            ->where('clergy_id', $clergyTableId)
            ->whereIn('status', self::blockingStatuses())
            ->exists();
    }

    /**
     * @return array{0: LiveSessionRequest, 1: int|null} request and queue position (1-based among queued), null if not queued
     */
    public function joinQueue(User $seeker, Clergy $clergy, ?string $message = null): array
    {
        $this->purgeStaleQueueEntries($clergy->id);

        if ($this->seekerHasActiveQueueMembership($seeker)) {
            throw new \RuntimeException('already_in_queue');
        }

        if ($clergy->registration_status !== 'approved' || $clergy->status !== 'active' || ! $clergy->is_live_session) {
            throw new \RuntimeException('clergy_unavailable');
        }

        if (! $clergy->is_available_for_live) {
            throw new \RuntimeException('clergy_not_accepting_live');
        }

        $now = Carbon::now();

        return DB::transaction(function () use ($seeker, $clergy, $message, $now) {
            $blocking = $this->hasBlockingSessionForClergy($clergy->id);

            $row = LiveSessionRequest::create([
                'seeker_id' => $seeker->id,
                'clergy_id' => $clergy->id,
                'clergy_user_id' => $clergy->user_id,
                'message' => $message ?? '',
                'status' => $blocking ? 'queued' : 'awaiting_confirm',
                'queue_joined_at' => $now,
                'last_queue_heartbeat_at' => $now,
                'confirmation_deadline_at' => $blocking ? null : $now->copy()->addSeconds(self::CONFIRM_READY_SECONDS),
                'video_status' => 'not_started',
            ]);

            if (! $blocking && $row->seeker) {
                $row->seeker->notify(new LiveSessionYourTurnInApp($row));
            }

            if ($clergy->user) {
                $clergy->user->notify(new LiveSessionSeekerJoinedQueueInApp($row));
            }

            $position = $row->status === 'queued'
                ? $this->queuePositionFor($row)
                : null;

            return [$row, $position];
        });
    }

    public function queuePositionFor(LiveSessionRequest $req): int
    {
        if ($req->status !== 'queued' || ! $req->clergy_id) {
            return 0;
        }

        $t = $req->queue_joined_at ?? $req->created_at;

        return 1 + (int) LiveSessionRequest::query()
            ->where('clergy_id', $req->clergy_id)
            ->where('status', 'queued')
            ->where(function ($w) use ($t, $req) {
                $w->where('queue_joined_at', '<', $t)
                    ->orWhere(function ($w2) use ($t, $req) {
                        $w2->where('queue_joined_at', '=', $t)
                            ->where('id', '<', $req->id);
                    });
            })
            ->count();
    }

    public function heartbeat(User $seeker, LiveSessionRequest $req): void
    {
        if ((int) $req->seeker_id !== (int) $seeker->id) {
            throw new \RuntimeException('forbidden');
        }

        if (! in_array($req->status, ['queued', 'awaiting_confirm'], true)) {
            throw new \RuntimeException('invalid_status');
        }

        $now = Carbon::now();
        $req->update(['last_queue_heartbeat_at' => $now]);

        if ($req->clergy_id) {
            $this->purgeStaleQueueEntries($req->clergy_id);
        }
    }

    public function leaveQueue(User $seeker, LiveSessionRequest $req): void
    {
        if ((int) $req->seeker_id !== (int) $seeker->id) {
            throw new \RuntimeException('forbidden');
        }

        if (! in_array($req->status, ['queued', 'awaiting_confirm', 'ready'], true)) {
            throw new \RuntimeException('invalid_status');
        }

        $clergyId = $req->clergy_id;

        $wasBlockingSlot = in_array($req->status, ['awaiting_confirm', 'ready'], true);

        $req->update([
            'status' => 'cancelled',
            'confirmation_deadline_at' => null,
        ]);

        if ($clergyId && $wasBlockingSlot) {
            $this->maybePromoteNextInQueue($clergyId);
        }
    }

    public function confirmReady(User $seeker, LiveSessionRequest $req): void
    {
        if ((int) $req->seeker_id !== (int) $seeker->id) {
            throw new \RuntimeException('forbidden');
        }

        if ($req->status !== 'awaiting_confirm') {
            throw new \RuntimeException('invalid_status');
        }

        if ($req->confirmation_deadline_at && $req->confirmation_deadline_at->isPast()) {
            throw new \RuntimeException('expired');
        }

        $now = Carbon::now();

        $req->update([
            'status' => 'ready',
            'ready_confirmed_at' => $now,
            'confirmation_deadline_at' => null,
            'agora_channel' => $req->agora_channel ?? ('live_'.$req->clergy_id.'_'.$req->id.'_'.Str::lower(Str::random(8))),
        ]);

        $fresh = $req->fresh(['clergyProfile.user', 'seeker:id,name']);

        $clergyUser = $fresh->clergyUser ?? $fresh->clergyProfile?->user;
        if ($clergyUser) {
            $clergyUser->notify(new LiveSessionSeekerConfirmedReadyInApp($fresh));
        }
    }

    public function maybePromoteNextInQueue(int $clergyTableId): void
    {
        if ($this->hasBlockingSessionForClergy($clergyTableId)) {
            return;
        }

        $next = LiveSessionRequest::query()
            ->where('clergy_id', $clergyTableId)
            ->where('status', 'queued')
            ->orderBy('queue_joined_at')
            ->orderBy('id')
            ->first();

        if (! $next) {
            return;
        }

        $now = Carbon::now();

        $next->update([
            'status' => 'awaiting_confirm',
            'confirmation_deadline_at' => $now->copy()->addSeconds(self::CONFIRM_READY_SECONDS),
            'last_queue_heartbeat_at' => $now,
        ]);

        if ($next->seeker) {
            $next->seeker->notify(new LiveSessionYourTurnInApp($next));
        }
    }

    public function onVideoCompleted(LiveSessionRequest $req): void
    {
        if (! $req->clergy_id) {
            return;
        }

        $this->maybePromoteNextInQueue($req->clergy_id);
    }

    /**
     * Clergy action: remind seeker awaiting confirm, or promote first queued seeker.
     *
     * @return array{action: string, live_session_request: LiveSessionRequest}
     */
    public function notifyNextSeeker(Clergy $clergy): array
    {
        $this->purgeStaleQueueEntries($clergy->id);

        $awaiting = LiveSessionRequest::query()
            ->where('clergy_id', $clergy->id)
            ->where('status', 'awaiting_confirm')
            ->with(['seeker:id,name,email,avatar'])
            ->first();

        if ($awaiting) {
            $now = Carbon::now();
            $awaiting->update([
                'confirmation_deadline_at' => $now->copy()->addSeconds(self::CONFIRM_READY_SECONDS),
                'last_queue_heartbeat_at' => $now,
            ]);

            if ($awaiting->seeker) {
                $awaiting->seeker->notify(new LiveSessionYourTurnInApp($awaiting->fresh()));
            }

            return [
                'action' => 'reminded',
                'live_session_request' => $awaiting->fresh(['seeker:id,name,email,avatar']),
            ];
        }

        if (LiveSessionRequest::query()
            ->where('clergy_id', $clergy->id)
            ->whereIn('status', ['ready', 'active'])
            ->exists()) {
            throw new \RuntimeException('session_in_progress');
        }

        $this->maybePromoteNextInQueue($clergy->id);

        $promoted = LiveSessionRequest::query()
            ->where('clergy_id', $clergy->id)
            ->where('status', 'awaiting_confirm')
            ->with(['seeker:id,name,email,avatar'])
            ->first();

        if (! $promoted) {
            throw new \RuntimeException('queue_empty');
        }

        return [
            'action' => 'promoted',
            'live_session_request' => $promoted,
        ];
    }

    public function queueSnapshotForClergy(Clergy $clergy): array
    {
        $this->purgeStaleQueueEntries($clergy->id);

        $queued = LiveSessionRequest::query()
            ->where('clergy_id', $clergy->id)
            ->where('status', 'queued')
            ->orderBy('queue_joined_at')
            ->orderBy('id')
            ->with(['seeker:id,name,email,avatar'])
            ->get();

        $currentSession = LiveSessionRequest::query()
            ->where('clergy_id', $clergy->id)
            ->whereIn('status', ['ready', 'active', 'awaiting_confirm'])
            ->with(['seeker:id,name,email,avatar'])
            ->orderByRaw("FIELD(status, 'ready', 'active', 'awaiting_confirm')")
            ->first();

        $queueList = $queued->values()->map(function (LiveSessionRequest $row, int $index) {
            return $this->formatQueueRowForClergyUi($row, $index + 1, $index === 0);
        });

        $weekStart = Carbon::now()->startOfWeek();

        return [
            'stats' => [
                'waiting_in_queue' => $queued->count(),
                'open_prayer_threads' => PrayerRequest::query()
                    ->where('clergy_id', $clergy->id)
                    ->where('status', 'active')
                    ->count(),
                'sessions_this_week' => LiveSessionRequest::query()
                    ->where('clergy_id', $clergy->id)
                    ->where('status', 'completed')
                    ->where('updated_at', '>=', $weekStart)
                    ->count(),
            ],
            'is_available_for_live' => (bool) $clergy->is_available_for_live,
            'live_unavailable_message' => $clergy->live_unavailable_message,
            'heartbeat_timeout_seconds' => self::HEARTBEAT_TIMEOUT_SECONDS,
            'confirm_ready_seconds' => self::CONFIRM_READY_SECONDS,
            'current_session' => $currentSession
                ? $this->formatQueueRowForClergyUi($currentSession, null, false)
                : null,
            'queue' => $queueList,
            'can_notify_next_seeker' => $queued->isNotEmpty()
                || LiveSessionRequest::query()
                    ->where('clergy_id', $clergy->id)
                    ->where('status', 'awaiting_confirm')
                    ->exists(),
            // Legacy keys for older clients
            'queued' => $queued,
            'in_progress' => LiveSessionRequest::query()
                ->where('clergy_id', $clergy->id)
                ->whereIn('status', ['awaiting_confirm', 'ready', 'active'])
                ->with(['seeker:id,name,email,avatar'])
                ->get(),
            'counts' => [
                'queued' => $queued->count(),
            ],
        ];
    }

    protected function formatQueueRowForClergyUi(
        LiveSessionRequest $row,
        ?int $queuePosition,
        bool $isNextInQueue
    ): array {
        return [
            'id' => $row->id,
            'status' => $row->status,
            'status_label' => $this->statusLabel($row->status),
            'badges' => $this->statusBadges($row->status),
            'subtitle' => $this->statusSubtitle($row, $isNextInQueue),
            'queue_position' => $queuePosition,
            'is_next' => $isNextInQueue,
            'seeker' => $row->seeker,
            'message' => $row->message,
            'ready_confirmed_at' => $row->ready_confirmed_at,
            'confirmation_deadline_at' => $row->confirmation_deadline_at,
            'agora_channel' => $row->agora_channel,
            'can_start_agora_call' => $row->status === 'ready',
            'video_status' => $row->video_status,
        ];
    }

    protected function statusLabel(string $status): string
    {
        return match ($status) {
            'queued' => 'IN QUEUE',
            'awaiting_confirm' => 'AWAITING READY',
            'ready' => 'CONFIRMED READY',
            'active' => 'LIVE',
            default => strtoupper(str_replace('_', ' ', $status)),
        };
    }

    /** @return list<string> */
    protected function statusBadges(string $status): array
    {
        return match ($status) {
            'awaiting_confirm' => ['SEEKING LIVE SESSION', 'AWAITING CONFIRM'],
            'ready' => ['SEEKING LIVE SESSION', 'CONFIRMED READY'],
            'active' => ['LIVE SESSION'],
            'queued' => ['IN QUEUE'],
            default => [strtoupper(str_replace('_', ' ', $status))],
        };
    }

    protected function statusSubtitle(LiveSessionRequest $row, bool $isNextInQueue): string
    {
        return match ($row->status) {
            'ready' => 'Waiting room confirmed and ready.',
            'awaiting_confirm' => 'Waiting for seeker to confirm they are ready.',
            'active' => 'Video session in progress.',
            'queued' => $isNextInQueue
                ? 'Ready to join when current session ends.'
                : 'In queue.',
            default => '',
        };
    }
}
