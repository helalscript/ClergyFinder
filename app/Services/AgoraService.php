<?php

namespace App\Services;

use App\Models\LiveSessionRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Peterujah\Agora\Agora;
use Peterujah\Agora\Builders\RtcToken;
use Peterujah\Agora\Roles;
use Peterujah\Agora\User as AgoraUser;
use RuntimeException;

class AgoraService
{
    public function userCanAccessSession(LiveSessionRequest $req, User $user): bool
    {
        return (int) $req->seeker_id === (int) $user->id
            || (int) $req->clergy_user_id === (int) $user->id;
    }

    public function canJoinSession(LiveSessionRequest $req): bool
    {
        return in_array($req->status, ['ready', 'active'], true)
            && $req->video_status !== 'completed';
    }

    /**
     * @return array{
     *     channel: string,
     *     uid: int|string,
     *     token: string,
     *     expires_at: string,
     *     live_session_request_id: int,
     *     join_url: string
     * }
     */
    public function buildJoinCredentials(LiveSessionRequest $req, User $user): array
    {
        $this->assertConfigured();

        $channel = $this->ensureChannelName($req);
        $uid = $this->uidForUser($user);
        $ttl = $this->tokenTtlSeconds();
        $token = $this->buildRtcToken($channel, $uid, $ttl);

        return [
            'channel' => $channel,
            'uid' => $uid,
            'token' => $token,
            'expires_at' => Carbon::now()->addSeconds($ttl)->toIso8601String(),
            'live_session_request_id' => $req->id,
            'join_url' => $this->joinUrl($req->id),
        ];
    }

    public function ensureChannelName(LiveSessionRequest $req): string
    {
        if ($req->agora_channel) {
            return $req->agora_channel;
        }

        $channel = 'live_'.$req->clergy_id.'_'.$req->id.'_'.Str::lower(Str::random(8));
        $req->update(['agora_channel' => $channel]);

        return $channel;
    }

    public function uidForUser(User $user): int|string
    {
        $id = (int) $user->id;

        if ($id >= 1 && $id <= 4294967295) {
            return $id;
        }

        return 'user_'.$id;
    }

    protected function buildRtcToken(string $channel, int|string $uid, int $ttl): string
    {
        $privilegeExpire = time() + $ttl;

        $client = new Agora(
            (string) config('agora.app_id'),
            (string) config('agora.app_certificate'),
        );
        $client->setExpiration($ttl);

        $agoraUser = (new AgoraUser($uid))
            ->setChannel($channel)
            ->setRole(Roles::RTC_PUBLISHER)
            ->setPrivilegeExpire($privilegeExpire);

        if (is_int($uid)) {
            return RtcToken::buildTokenWithUid($client, $agoraUser);
        }

        return RtcToken::buildTokenWithUserAccount($client, $agoraUser);
    }

    protected function joinUrl(int $liveSessionRequestId): string
    {
        return rtrim((string) config('app.frontend_url'), '/').'/live/call/'.$liveSessionRequestId;
    }

    protected function tokenTtlSeconds(): int
    {
        return max(60, (int) config('agora.token_ttl_seconds', 3600));
    }

    protected function assertConfigured(): void
    {
        if (! config('agora.app_id') || ! config('agora.app_certificate')) {
            throw new RuntimeException('agora_not_configured');
        }
    }
}
