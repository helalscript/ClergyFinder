# ClergyFinder — Complete Frontend API Reference

> **For Frontend Developers and AI Assistants**
> This is the single source of truth for building the full ClergyFinder app frontend. Read this document end-to-end before writing any frontend code.

**Backend base URL:** `https://your-domain.com/api`
**All requests must include:** `Content-Type: application/json` and `Accept: application/json`
**Last updated:** 2026-06-27 (matches juanjperez Laravel backend)

---

## Table of Contents

1. [App Overview and Roles](#1-app-overview-and-roles)
2. [Tech Stack Recommendations](#2-tech-stack-recommendations)
3. [Environment Variables](#3-environment-variables)
4. [API Response Format](#4-api-response-format)
5. [Authentication Flow](#5-authentication-flow)
6. [User Profile and Account](#6-user-profile-and-account)
7. [Clergy Profile and Registration](#7-clergy-profile-and-registration)
8. [Find Clergy (Public)](#8-find-clergy-public)
9. [Prayer Requests — Seeker](#9-prayer-requests--seeker)
10. [Prayer Requests — Clergy](#10-prayer-requests--clergy)
11. [Live Session Queue — Seeker](#11-live-session-queue--seeker)
12. [Live Session Queue — Clergy](#12-live-session-queue--clergy)
13. [Agora Video Call (Shared)](#13-agora-video-call-shared)
14. [Chat and Messaging](#14-chat-and-messaging)
15. [Notifications](#15-notifications)
16. [Real-Time Events (WebSocket)](#16-real-time-events-websocket)
17. [CMS Content Endpoints (Public)](#17-cms-content-endpoints-public)
18. [Questions (Onboarding)](#18-questions-onboarding)
19. [Live Session State Machine](#19-live-session-state-machine)
20. [Screen Map](#20-screen-map)
21. [Implementation Checklist](#21-implementation-checklist)
22. [Quick Reference — All Endpoints](#22-quick-reference--all-endpoints)

---

## 1. App Overview and Roles

ClergyFinder connects **Seekers** (people seeking spiritual guidance) with **Clergy** (religious leaders).

| Role | Description |
|------|-------------|
| `seeker` | Submits prayer requests, joins live session queues, has text/video sessions with clergy |
| `clergy` | Approved religious leader; receives prayer requests, manages live queue, starts video calls |
| `admin` | Platform administrator (separate web admin panel — NOT part of the mobile app) |

### Core Features to Build

1. **Auth** — Register, Login, Email OTP Verification, Password Reset, Social Login
2. **Clergy Profile** — Profile management, credential upload, registration status
3. **Find Clergy** — Browse/search clergy directory
4. **Prayer Requests** — Submit and track prayer requests (text + chat)
5. **Live Session Queue** — Real-time queue with Agora video calls
6. **Chat** — In-app messaging per prayer request or live session
7. **Notifications** — In-app and WebSocket push notifications
8. **CMS Pages** — Home, How It Works, FAQ, Onboarding, etc.

---

## 2. Tech Stack Recommendations

| Platform | HTTP | WebSocket | Video |
|----------|------|-----------|-------|
| React / Vue | axios or fetch | laravel-echo + pusher-js | agora-rtc-sdk-ng |
| React Native | axios | laravel-echo + pusher-js | react-native-agora |
| Flutter | dio | pusher_channels_flutter | agora_rtc_engine |

---

## 3. Environment Variables

```env
# Backend API
VITE_API_BASE_URL=https://api.yourdomain.com/api

# Agora App ID (public — NEVER put App Certificate here)
VITE_AGORA_APP_ID=your_agora_app_id

# Laravel Reverb WebSocket
VITE_REVERB_APP_KEY=your_reverb_key
VITE_REVERB_HOST=api.yourdomain.com
VITE_REVERB_PORT=443
VITE_REVERB_SCHEME=https
```

> **CRITICAL:** `VITE_AGORA_APP_ID` is NEVER returned by the API. Configure it once in the frontend and always pass it to every Agora `join()` call.

---

## 4. API Response Format

### Success

```json
{
  "status": true,
  "message": "Human readable message",
  "code": 200,
  "data": {}
}
```

### Error

```json
{
  "status": false,
  "message": "Human readable error",
  "code": 422,
  "errors": {}
}
```

### Paginated List

```json
{
  "status": true,
  "message": "...",
  "code": 200,
  "data": [],
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 73,
    "next_page_url": "...",
    "prev_page_url": null
  }
}
```

---

## 5. Authentication Flow

### 5.1 Register

```
POST /api/register
```

**Body (Seeker):**

```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "phone": "+1234567890",
  "role": "seeker"
}
```

**Body (Clergy)** — includes extra profile fields:

```json
{
  "name": "Rev. Smith",
  "email": "rev@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "clergy",
  "religion": "Christianity",
  "denomination": "Baptist",
  "bio": "25 years of pastoral ministry",
  "address": "123 Church St",
  "city": "Nashville",
  "preferred_language": ["English", "Spanish"],
  "specialities": ["grief counseling", "marriage"],
  "credential_documents": ["https://storage.example.com/doc1.pdf"]
}
```

**Success `200`:** OTP verification email sent. No JWT token returned yet.

**Frontend action:** Show OTP input screen. Call `/api/verify-email` next.

---

### 5.2 Verify Email (OTP)

```
POST /api/verify-email
Body: { "email": "john@example.com", "otp": "1234" }
```

**Success `200`:**

```json
{
  "status": true,
  "message": "Email verified successfully.",
  "token_type": "bearer",
  "token": "eyJ0eXAiOiJKV1Q...",
  "data": { "id": 10, "name": "John", "email": "...", "role": "seeker" }
}
```

**Frontend action:** Store `token` and `data.id` (userId) in secure storage. Initialize Laravel Echo with this token.

---

### 5.3 Resend OTP

```
POST /api/resend-otp
Body: { "email": "john@example.com" }
```

---

### 5.4 Login

```
POST /api/login
Body: { "email": "john@example.com", "password": "password123" }
```

**Success `200`:**

```json
{
  "status": true,
  "token_type": "bearer",
  "token": "eyJ0eXAiOiJKV1Q...",
  "data": {
    "id": 10,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "seeker",
    "avatar": "https://..."
  }
}
```

---

### 5.5 Forgot Password

```
POST /api/forget-password
Body: { "email": "john@example.com" }
```

Then verify OTP:

```
POST /api/verify-otp
Body: { "email": "john@example.com", "otp": "5678" }
```

Then reset:

```
POST /api/reset-password
Body: { "email": "...", "password": "newpass", "password_confirmation": "newpass", "token": "..." }
```

---

### 5.6 Social Login

```
POST /api/social-login
Body: { "provider": "google", "access_token": "..." }
```

Returns same token structure as login.

---

### 5.7 Logout

```
POST /api/logout
Authorization: Bearer <token>
```

---

### 5.8 Refresh Token

```
GET /api/refresh-token
Authorization: Bearer <token>
```

---

### Standard Auth Headers (All Authenticated Requests)

```
Authorization: Bearer <jwt_token>
Content-Type: application/json
Accept: application/json
```

---

## 6. User Profile and Account

### 6.1 Get Current User

```
GET /api/me
Authorization: Bearer <token>
```

Returns full user object including `clergy` relation if applicable.

---

### 6.2 Update Profile

```
POST /api/update-profile
Authorization: Bearer <token>
Content-Type: multipart/form-data
```

| Field | Type |
|-------|------|
| `name` | string |
| `phone` | string |
| `avatar` | file (image) |
| `cover_photo` | file (image) |
| `gender` | string: male / female / other |

---

### 6.3 Change Password

```
POST /api/update-password
Body: { "current_password": "...", "password": "...", "password_confirmation": "..." }
```

---

### 6.4 Dashboard Data

```
GET /api/dashboard-data/home
Authorization: Bearer <token>
```

Returns summary stats for the logged-in user's home dashboard.

---

## 7. Clergy Profile and Registration

> All routes below require `Authorization: Bearer <token>` with `role === 'clergy'`.

### 7.1 Get Clergy Profile

```
GET /api/clergy/profile
```

Returns user object with full `clergy` relation.

---

### 7.2 Update Clergy Profile

```
POST /api/clergy/profile
Content-Type: multipart/form-data
```

| Field | Type | Notes |
|-------|------|-------|
| `first_name` | string | Required |
| `last_name` | string | Required |
| `phone` | string | |
| `gender` | string | |
| `avatar` | file | |
| `cover_photo` | file | |
| `religion` | string | |
| `denomination` | string | |
| `bio` | text | |
| `address` | string | |
| `city` | string | |
| `state` | string | |
| `zip_code` | string | |
| `country` | string | |
| `latitude` | decimal | |
| `longitude` | decimal | |
| `preferred_language` | array or CSV | |
| `specialities` | array or CSV | |
| `is_live_session` | boolean | Toggle live session availability |
| `is_prayer_request` | boolean | Toggle prayer request availability |
| `available_at` | string | Schedule description text |
| `is_available_for_live` | boolean | Only for approved clergy |
| `live_unavailable_message` | string | Shown to seekers when offline |

---

### 7.3 Registration Status

```
GET /api/clergy/registration-status
```

```json
{
  "data": {
    "registration_status": "pending_admin",
    "registration_rejection_reason": null,
    "registration_reviewed_at": null,
    "can_reapply": false,
    "is_verified": false
  }
}
```

| `registration_status` | Meaning |
|-----------------------|---------|
| `pending_admin` | Awaiting admin review |
| `approved` | Approved — can use live sessions and prayer requests |
| `rejected` | Rejected — can re-apply |

---

### 7.4 Re-Apply After Rejection

```
POST /api/clergy/reapply-registration
```

Same fields as profile update. Only allowed when `registration_status === 'rejected'`.

---

## 8. Find Clergy (Public)

### 8.1 List Clergy

```
GET /api/clergy?per_page=15&page=1
```

Publicly accessible. No auth required. Returns paginated clergy list.

---

### 8.2 Clergy Detail

```
GET /api/clergy/{id}
```

Returns single clergy profile with full `clergy` relation.

---

## 9. Prayer Requests — Seeker

> Requires `Authorization: Bearer <token>` with `role === 'seeker'`
> Base prefix: `/api/seeker`

### Prayer Request Status Values

| Status | Meaning |
|--------|---------|
| `pending_clergy` | Waiting for clergy to accept or decline |
| `active` | Accepted — chat room is open |
| `closed` | Clergy closed the thread |
| `declined` | Clergy declined with reason |

---

### 9.1 Submit Prayer Request

```
POST /api/seeker/prayer-requests
Body: { "clergy_id": 5, "message": "I need guidance about..." }
```

**Success `201`:** Prayer request created. Email and in-app notification sent to clergy.

**Errors:**

| Code | Message |
|------|---------|
| 404 | Clergy not found |
| 422 | This clergy member is not accepting prayer requests. |

---

### 9.2 List My Prayer Requests

```
GET /api/seeker/prayer-requests?per_page=15
```

Paginated. Each record includes `clergyUser` and `chatRoom`.

---

### 9.3 Show Prayer Request

```
GET /api/seeker/prayer-requests/{id}
```

---

## 10. Prayer Requests — Clergy

> Requires `Authorization: Bearer <token>` with approved clergy role
> Base prefix: `/api/clergy`

### 10.1 List Prayer Requests

```
GET /api/clergy/prayer-requests?tab=pending&per_page=15
```

**`tab` values:** `pending` | `active` | `history`

---

### 10.2 Show Prayer Request

```
GET /api/clergy/prayer-requests/{id}
```

Includes `seeker` and `chatRoom` relations.

---

### 10.3 Accept Prayer Request

```
POST /api/clergy/prayer-requests/{id}/accept
```

Status changes to `active`. Chat room created. Seeker notified.

---

### 10.4 Decline Prayer Request

```
POST /api/clergy/prayer-requests/{id}/decline
Body: { "reason": "Currently at capacity" }
```

Status changes to `declined`. Seeker notified with reason.

---

### 10.5 Close Prayer Thread

```
POST /api/clergy/prayer-requests/{id}/close
```

Status changes to `closed`. Chat room archived.

---

## 11. Live Session Queue — Seeker

> Requires `Authorization: Bearer <token>` with `role === 'seeker'`
> Base prefix: `/api/seeker`

### 11.1 Join Queue

```
POST /api/seeker/live-session-queue/join
Body: { "clergy_id": 5, "message": "I need spiritual guidance on..." }
```

**Success `201`:**

```json
{
  "data": {
    "live_session_request": {
      "id": 42,
      "seeker_id": 10,
      "clergy_id": 5,
      "status": "queued",
      "video_status": "not_started",
      "message": "...",
      "queue_joined_at": "2026-06-27T10:00:00Z"
    },
    "queue_position": 3,
    "heartbeat_interval_seconds": 40
  }
}
```

> If clergy is immediately free: `status = "awaiting_confirm"`, `queue_position = null`

**Frontend actions:**
- Save `live_session_request.id` in local state
- Start heartbeat timer using `heartbeat_interval_seconds`
- Navigate based on `status`: `queued` → Waiting screen | `awaiting_confirm` → Confirm Ready screen

**Errors:**

| Code | Message |
|------|---------|
| 409 | You are already in a live session queue. |
| 422 | This clergy member is not available for live sessions. |
| 422 | This clergy member is not online for live sessions right now. |

---

### 11.2 Send Heartbeat

```
POST /api/seeker/live-session-queue/{id}/heartbeat
```

No request body needed.

**When to call:** Every ~40 seconds while `status` is `queued` or `awaiting_confirm`.

**If missed for 120 seconds:** Backend sets status to `abandoned` and broadcasts `live_session_queue_abandoned` notification.

---

### 11.3 Leave Queue

```
POST /api/seeker/live-session-queue/{id}/leave
```

Allowed when status is: `queued`, `awaiting_confirm`, or `ready`.

---

### 11.4 Confirm Ready

```
POST /api/seeker/live-session-queue/{id}/confirm-ready
```

**Success `200`:**

```json
{
  "data": {
    "live_session_request": { "id": 42, "status": "ready", "agora_channel": "live_5_42_abc123de" },
    "status": "ready",
    "agora_channel": "live_5_42_abc123de",
    "ready_confirmed_at": "2026-06-27T10:05:00Z"
  }
}
```

**Frontend action:** Show "Waiting for clergy to start the call" screen and listen for `live_session_clergy_started_video` broadcast.

**Errors:**

| Code | Message |
|------|---------|
| 409 | The confirmation window has expired. |
| 409 | Unable to confirm for this record. |

---

### 11.5 Get Queue Position (Polling)

```
GET /api/seeker/live-session-requests/{id}
```

Response includes `queue_position` dynamically. Only meaningful when `status === 'queued'`.

---

### 11.6 List Live Sessions (State Restore)

```
GET /api/seeker/live-session-requests?per_page=5
```

On app relaunch, find any record with status in `['queued', 'awaiting_confirm', 'ready', 'active']` to restore active session state.

---

## 12. Live Session Queue — Clergy

> Requires `Authorization: Bearer <token>` with approved clergy role
> Base prefix: `/api/clergy`

### 12.1 Toggle Live Availability

```
POST /api/clergy/live-availability
```

**Go Online:**

```json
{ "is_available_for_live": true }
```

**Go Offline with message:**

```json
{
  "is_available_for_live": false,
  "live_unavailable_message": "Back in 30 minutes"
}
```

When going offline, all waiting seekers receive `clergy_live_step_out` notification.

---

### 12.2 Queue Snapshot (Main Dashboard Call)

```
GET /api/clergy/live-session-requests?tab=queue
```

**Poll this every 10-15 seconds as WebSocket fallback.**

**Success `200`:**

```json
{
  "data": {
    "stats": {
      "waiting_in_queue": 2,
      "open_prayer_threads": 1,
      "sessions_this_week": 5
    },
    "is_available_for_live": true,
    "live_unavailable_message": null,
    "heartbeat_timeout_seconds": 120,
    "confirm_ready_seconds": 600,
    "current_session": {
      "id": 42,
      "status": "ready",
      "status_label": "CONFIRMED READY",
      "badges": ["SEEKING LIVE SESSION", "CONFIRMED READY"],
      "seeker": { "id": 10, "name": "John", "email": "...", "avatar": "..." },
      "message": "Need guidance",
      "agora_channel": "live_5_42_abc123de",
      "can_start_agora_call": true,
      "video_status": "not_started",
      "ready_confirmed_at": "2026-06-27T10:05:00Z"
    },
    "queue": [
      {
        "id": 43,
        "status": "queued",
        "status_label": "IN QUEUE",
        "queue_position": 1,
        "is_next": true,
        "seeker": { "id": 11, "name": "Jane", "email": "..." },
        "message": "..."
      }
    ],
    "can_notify_next_seeker": true
  }
}
```

---

### 12.3 Session History

```
GET /api/clergy/live-session-requests?tab=history&per_page=15
```

---

### 12.4 Show Single Session

```
GET /api/clergy/live-session-requests/{id}
```

---

### 12.5 Notify Next Seeker

```
POST /api/clergy/live-session-queue/notify-next
```

Promotes the next `queued` seeker to `awaiting_confirm`. If someone is already in `awaiting_confirm`, sends a reminder instead.

**Response `data.action`:** `"promoted"` or `"reminded"`

**Errors:**

| Code | Message |
|------|---------|
| 409 | A seeker is already ready or in a live call. Start or complete it first. |
| 404 | No seekers are waiting in your queue. |

---

### 12.6 Start Video Call

```
POST /api/clergy/live-session-requests/{id}/video/start
```

**Precondition:** `status === 'ready'`

**Success `200`:**

```json
{
  "data": {
    "live_session_request_id": 42,
    "chat_room_id": 7,
    "agora_channel": "live_5_42_abc123de",
    "video_status": "started",
    "agora": {
      "channel": "live_5_42_abc123de",
      "uid": 5,
      "token": "007eJx...",
      "expires_at": "2026-06-27T11:05:00Z",
      "live_session_request_id": 42
    }
  }
}
```

> `agora` object only present when Agora is configured on the server.

**Frontend action:** Use `data.agora` to join Agora immediately. Seeker is automatically notified via broadcast.

**Errors:**

| Code | Message |
|------|---------|
| 409 | The seeker must confirm they are ready before you can start the call. |
| 409 | Video already started / completed |

---

### 12.7 Complete Video Call

```
POST /api/clergy/live-session-requests/{id}/video/complete
```

**Precondition:** `video_status === 'started'`

**Frontend action:** Leave Agora channel → release camera/mic tracks → refresh queue. The backend auto-promotes the next waiting seeker.

---

### 12.8 Reject / Remove Seeker from Queue

```
POST /api/clergy/live-session-requests/{id}/reject
Body: { "reason": "Emergency meeting" }
```

Works on any session with `status` in: `queued`, `awaiting_confirm`, `ready`, `active`.

The `reason` field is optional and defaults to "Session cancelled by clergy."

**Success `200`:**

```json
{
  "data": {
    "live_session_request_id": 42,
    "status": "cancelled"
  }
}
```

**Effect:** Session cancelled → seeker receives `live_session_rejected` broadcast with the reason text → next queued seeker automatically promoted.

---

## 13. Agora Video Call (Shared)

Available to both seeker and clergy who are participants of the session.

```
GET /api/live-session-requests/{id}/agora/join
Authorization: Bearer <token>
```

**Precondition:** `status` is `ready` or `active`, video not completed.

**Success `200`:**

```json
{
  "data": {
    "agora": {
      "channel": "live_5_42_abc123de",
      "uid": 10,
      "token": "007eJx...",
      "expires_at": "2026-06-27T11:05:00Z",
      "live_session_request_id": 42,
      "join_url": "https://your-frontend.com/live/call/42"
    }
  }
}
```

### When to Use This Endpoint

| Scenario | Token Source |
|----------|-------------|
| Clergy starts call | `video/start` response — `data.agora` |
| Seeker receives start broadcast | `notification.agora_join` object |
| Token expired mid-call | Call `/agora/join` then `renewToken()` |
| App reopened during active call | Call `/agora/join` |
| Broadcast was missed | Call `/agora/join` |

### Agora Integration Code (Web)

```javascript
import AgoraRTC from 'agora-rtc-sdk-ng';

const AGORA_APP_ID = import.meta.env.VITE_AGORA_APP_ID;
let client = null, localAudioTrack = null, localVideoTrack = null;

export async function joinAgoraCall(agoraJoin) {
  const { channel, uid, token } = agoraJoin;
  client = AgoraRTC.createClient({ mode: 'rtc', codec: 'vp8' });

  client.on('user-published', async (user, mediaType) => {
    await client.subscribe(user, mediaType);
    if (mediaType === 'video') user.videoTrack.play('#remote-video');
    if (mediaType === 'audio') user.audioTrack.play();
  });

  client.on('user-unpublished', (user) => {
    document.querySelector('#remote-video').innerHTML = '';
  });

  // Refresh expired token automatically
  client.on('token-privilege-will-expire', async () => {
    const res = await fetch(`/api/live-session-requests/${agoraJoin.live_session_request_id}/agora/join`, {
      headers: { Authorization: `Bearer ${getJwtToken()}` }
    });
    const data = (await res.json()).data.agora;
    await client.renewToken(data.token);
  });

  await client.join(AGORA_APP_ID, channel, token, Number(uid));
  [localAudioTrack, localVideoTrack] = await AgoraRTC.createMicrophoneAndCameraTracks();
  localVideoTrack.play('#local-video');
  await client.publish([localAudioTrack, localVideoTrack]);
}

export async function leaveAgoraCall() {
  localAudioTrack?.close();
  localVideoTrack?.close();
  await client?.leave();
  client = null;
}
```

### Critical Rules

1. Always use the `uid` value returned by the backend — never invent your own.
2. `app_id` comes from frontend `.env` only — never from API responses.
3. Seeker and clergy get different tokens for the same channel.
4. Refresh the token on `token-privilege-will-expire` event or before `expires_at`.
5. Both sides are publishers — enable camera and mic for both seeker and clergy.
6. Leave the channel when clergy completes the call or user navigates away.

---

## 14. Chat and Messaging

> Auth: Bearer token (seeker or clergy)
> Base prefix: `/api/chats`

### 14.1 List Chat Rooms

```
GET /api/chats?per_page=15
```

Returns only rooms where the logged-in user is a participant (filtered automatically by role).

---

### 14.2 Room Details and Online Status

```
GET /api/chats/{room_id}
```

**Success `200`:**

```json
{
  "data": {
    "room": {
      "id": 7,
      "type": "live",
      "seeker_id": 10,
      "clergy_id": 5,
      "live_session_request_id": 42,
      "prayer_request_id": null,
      "status": "active",
      "video_status": "started"
    },
    "participants": [
      { "user_id": 10, "role": "seeker", "online": true },
      { "user_id": 5, "role": "clergy", "online": false }
    ]
  }
}
```

A user is "online" if they sent a presence update within the last 120 seconds.

---

### 14.3 Get Messages (Paginated)

```
GET /api/chats/messages/{room_id}?per_page=15
```

Returns messages newest-first. Use `prev_page_url` to load older messages.

Each message record:

```json
{
  "id": 101,
  "chat_room_id": 7,
  "sender_type": "seeker",
  "sender_id": 10,
  "body": "Thank you for your time.",
  "metadata": null,
  "created_at": "2026-06-27T10:15:00Z",
  "sender": { "id": 10, "name": "John", "email": "..." }
}
```

---

### 14.4 Send Message

```
POST /api/chats/send-messages/{room_id}
Body: { "message": "Your text here" }
```

**Success `201`:** Message object returned. Real-time broadcast fired via `MessageEvent`.

**Errors:**

| Code | Reason |
|------|--------|
| 403 | Blocked by or blocking other participant |
| 403 | Prayer thread is not currently active |

---

### 14.5 Update Presence (Show as Online)

```
POST /api/chats/presence/{room_id}
```

No body needed. Call every 60-90 seconds while the chat screen is open to appear "online".

---

### 14.6 Block / Unblock User

```
POST /api/chats/block/{room_id}
Body: { "action": "block" }
```

Use `"action": "unblock"` to unblock.

---

### Chat Real-Time Events

```javascript
echo.channel(`chat.${roomId}`)
  .listen('MessageEvent', (data) => {
    appendMessage(data);
  });
```

**MessageEvent payload:**

```json
{
  "id": 101,
  "chat_room_id": 7,
  "sender_type": "seeker",
  "sender_id": 10,
  "body": "Hello!",
  "created_at": "2026-06-27T10:15:00Z"
}
```

---

## 15. Notifications

### 15.1 List Notifications

```
GET /api/my-notifications
Authorization: Bearer <token>
```

Returns paginated in-app notifications for the logged-in user.

---

### 15.2 Mark as Read

```
POST /api/my-notifications/mark-as-read
Body: { "notification_ids": ["uuid-1", "uuid-2"] }
```

---

### 15.3 Delete Notification

```
DELETE /api/my-notifications-delete/{id}
```

---

### 15.4 Delete All Notifications

```
DELETE /api/my-notifications-delete-all
```

---

## 16. Real-Time Events (WebSocket)

### Echo Setup

```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
window.Pusher = Pusher;

const echo = new Echo({
  broadcaster: 'reverb',
  key: import.meta.env.VITE_REVERB_APP_KEY,
  wsHost: import.meta.env.VITE_REVERB_HOST,
  wsPort: parseInt(import.meta.env.VITE_REVERB_PORT) || 80,
  wssPort: parseInt(import.meta.env.VITE_REVERB_PORT) || 443,
  forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
  enabledTransports: ['ws', 'wss'],
  authEndpoint: `${import.meta.env.VITE_API_BASE_URL}/broadcasting/auth`,
  auth: {
    headers: { Authorization: `Bearer ${getJwtToken()}` },
  },
});

// Private user channel for in-app notifications
echo.private(`App.Models.User.${userId}`)
  .notification((notification) => handleNotification(notification));

// Public chat room channel for messages
echo.channel(`chat.${roomId}`)
  .listen('MessageEvent', (data) => appendMessage(data));
```

---

### All Notification Types

#### Seeker Receives

| `type` | Trigger | Key Fields | UI Action |
|--------|---------|------------|-----------|
| `live_session_your_turn` | Promoted to front of queue | `request_id` | Navigate to Confirm Ready screen |
| `live_session_clergy_started_video` | Clergy started video | `request_id`, `agora_channel`, `agora_join` | Join Agora call immediately |
| `live_session_confirm_timeout` | 10-min window expired | `request_id` | Show timeout message, reset state |
| `live_session_queue_abandoned` | Removed due to missed heartbeat | `request_id`, `message` | Show reason, reset state |
| `live_session_rejected` | Clergy rejected or removed seeker | `request_id`, `message` (reason text) | Show rejection reason alert, reset state |
| `clergy_live_step_out` | Clergy went offline | `clergy_id`, `message` | Show clergy unavailable message |

#### Clergy Receives

| `type` | Trigger | Key Fields | UI Action |
|--------|---------|------------|-----------|
| `live_session_queue_join` | New seeker joined queue | `request_id` | Refresh queue snapshot |
| `live_session_seeker_confirmed_ready` | Seeker confirmed ready | `request_id`, `agora_channel` | Enable "Start Video" button |

---

### Notification Handler Template

```javascript
function handleNotification(n) {
  switch (n.type) {
    // SEEKER
    case 'live_session_your_turn':
      navigateTo(`/live/confirm/${n.request_id}`);
      break;

    case 'live_session_clergy_started_video':
      joinAgoraCall(n.agora_join);
      navigateTo(`/live/call/${n.request_id}`);
      break;

    case 'live_session_confirm_timeout':
    case 'live_session_queue_abandoned':
      showAlert(n.message || 'You have been removed from the queue.');
      resetLiveSessionState();
      break;

    case 'live_session_rejected':
      showAlert(`Session cancelled.\nReason: ${n.message}`);
      resetLiveSessionState();
      break;

    case 'clergy_live_step_out':
      showAlert(`Clergy is temporarily unavailable: ${n.message}`);
      break;

    // CLERGY
    case 'live_session_queue_join':
    case 'live_session_seeker_confirmed_ready':
      refreshQueueSnapshot();
      break;
  }
}
```

---

### Full Payload — `live_session_clergy_started_video`

```json
{
  "type": "live_session_clergy_started_video",
  "title": "Live session started",
  "message": "The clergy started the video call. Join now.",
  "request_id": 42,
  "agora_channel": "live_5_42_abc123de",
  "agora_join": {
    "channel": "live_5_42_abc123de",
    "uid": 10,
    "token": "007eJx...",
    "expires_at": "2026-06-27T11:05:00Z",
    "live_session_request_id": 42,
    "join_url": "https://your-frontend.com/live/call/42"
  }
}
```

### Full Payload — `live_session_rejected`

```json
{
  "type": "live_session_rejected",
  "title": "Session Cancelled",
  "message": "Emergency meeting",
  "request_id": 42
}
```

---

## 17. CMS Content Endpoints (Public)

No authentication required.

### Home Page

| Endpoint | Description |
|----------|-------------|
| `GET /api/cms/home-page/hero-section` | Hero banner content |
| `GET /api/cms/home-page/counter-section` | Stats counters |
| `GET /api/cms/home-page/meet-our-clergy-section` | Featured clergy cards |
| `GET /api/cms/home-page/how-it-works-section` | Steps section |
| `GET /api/cms/home-page/review-section` | Testimonials |
| `GET /api/cms/home-page/spiritual-journey-section` | CTA section |

### Other Pages

| Endpoint | Description |
|----------|-------------|
| `GET /api/cms/find-clergy-page/hero-section` | Find Clergy page hero |
| `GET /api/cms/faq-page/hero-section` | FAQ page hero |
| `GET /api/cms/how-it-work-page/hero-section` | How It Works hero |
| `GET /api/cms/how-it-work-page/work-progress-section` | Steps |
| `GET /api/cms/how-it-work-page/our-values-section` | Values section |
| `GET /api/cms/how-it-work-page/spiritual-journey-section` | CTA |

### System and Navigation

| Endpoint | Description |
|----------|-------------|
| `GET /api/system-info` | App name, logo, meta info |
| `GET /api/social-links` | Social media links |
| `GET /api/onboarding-screens` | Onboarding slide data |
| `GET /api/dynamic-pages` | List of dynamic pages |
| `GET /api/dynamic-pages/single/{slug}` | Single dynamic page by slug |

### FAQs

```
GET /api/faqs?per_page=20
GET /api/faqs-categories
GET /api/faqs-categories/{id}
```

### Newsletter and Contact

```
POST /api/newsletter/subscribe
Body: { "email": "user@example.com" }

POST /api/contact-support-message/sent
Body: { "name": "...", "email": "...", "message": "..." }
```

---

## 18. Questions (Onboarding)

### Get Questions

```
GET /api/questions/seeker
GET /api/questions/clergy
```

### Save User Answer

```
POST /api/questions/user-answer
Authorization: Bearer <token>
Body: { "question_id": 1, "answer": "..." }
```

---

## 19. Live Session State Machine

```
[idle]
  |-- join (clergy busy) --> [queued]
  |-- join (clergy free) --> [awaiting_confirm]

[queued]
  |-- clergy notify-next / auto-promotion --> [awaiting_confirm]
  |-- seeker leave / clergy reject ---------> [cancelled]
  |-- heartbeat missed 120s ---------------> [abandoned]

[awaiting_confirm]
  |-- seeker confirmReady -----------------> [ready]
  |-- seeker leave / clergy reject ---------> [cancelled]
  |-- 600s window expires -----------------> [confirm_timeout]

[ready]
  |-- clergy video/start ------------------> [active]
  |-- seeker leave / clergy reject ---------> [cancelled]

[active]
  |-- clergy video/complete ---------------> [completed]
  |-- clergy reject -----------------------> [cancelled]

Terminal states: [completed], [cancelled], [abandoned], [confirm_timeout]
```

### Timing Constants

| Constant | Value | Purpose |
|----------|-------|---------|
| Heartbeat timeout | 120 seconds | Seeker removed if no heartbeat for 2 minutes |
| Confirm ready window | 600 seconds (10 min) | Seeker must confirm from `confirmation_deadline_at` |
| Heartbeat interval | ~40 seconds | Returned in join as `heartbeat_interval_seconds` |
| Agora token TTL | 3600 seconds | Refresh via `/agora/join` endpoint |

### Heartbeat Implementation

```javascript
let heartbeatTimer = null;

function startHeartbeat(sessionId, intervalSeconds = 40) {
  stopHeartbeat();
  heartbeatTimer = setInterval(async () => {
    try {
      await api.post(`/seeker/live-session-queue/${sessionId}/heartbeat`);
    } catch (e) {
      console.error('Heartbeat failed', e);
    }
  }, intervalSeconds * 1000);
}

function stopHeartbeat() {
  if (heartbeatTimer) clearInterval(heartbeatTimer);
  heartbeatTimer = null;
}
// Call stopHeartbeat() when status changes away from queued/awaiting_confirm
```

### Confirm Ready Countdown

```javascript
const deadline = new Date(session.confirmation_deadline_at);
const secondsLeft = Math.max(0, Math.floor((deadline - Date.now()) / 1000));
// Display as MM:SS countdown from 10:00 to 0:00
```

### Restore State on App Launch

```javascript
async function restoreLiveSession() {
  const res = await api.get('/seeker/live-session-requests?per_page=5');
  const active = res.data.data.find((r) =>
    ['queued', 'awaiting_confirm', 'ready', 'active'].includes(r.status)
  );
  if (active) {
    if (['queued', 'awaiting_confirm'].includes(active.status)) {
      startHeartbeat(active.id);
    }
    if (active.status === 'active') {
      const agoraRes = await api.get(`/live-session-requests/${active.id}/agora/join`);
      await joinAgoraCall(agoraRes.data.data.agora);
    }
    navigateToScreenForStatus(active.status, active.id);
  }
}
```

---

## 20. Screen Map

### Seeker Screens

| Route | Screen | Condition |
|-------|--------|-----------|
| `/register` | Registration | — |
| `/verify-email` | OTP verification | After register |
| `/login` | Login | — |
| `/forgot-password` | Forgot password | — |
| `/onboarding` | Onboarding slides | First launch |
| `/home` | Home (CMS) | Logged in |
| `/find-clergy` | Browse clergy | — |
| `/clergy/:id` | Clergy profile | — |
| `/prayer-request/new/:clergyId` | Submit prayer request | — |
| `/prayer-requests` | My prayer request list | — |
| `/prayer-request/:id` | Prayer request detail | — |
| `/chat/:roomId` | Chat room | — |
| `/live/join/:clergyId` | Join live queue | — |
| `/live/waiting/:sessionId` | Queue position and leave | status = queued |
| `/live/confirm/:sessionId` | Confirm ready with countdown | status = awaiting_confirm |
| `/live/ready/:sessionId` | Waiting for clergy to start | status = ready |
| `/live/call/:sessionId` | Agora video call | status = active |
| `/notifications` | All notifications | — |
| `/profile` | My profile | — |

### Clergy Screens

| Route | Screen | Condition |
|-------|--------|-----------|
| `/clergy/dashboard` | Live session queue dashboard | — |
| `/clergy/prayer-requests` | Prayer requests with tabs | — |
| `/clergy/prayer-request/:id` | Prayer request detail | — |
| `/clergy/chat/:roomId` | Chat room | — |
| `/clergy/live/call/:sessionId` | Agora video call | — |
| `/clergy/profile` | Profile edit | — |
| `/clergy/registration-status` | Registration status and re-apply | — |
| `/notifications` | All notifications | — |

---

## 21. Implementation Checklist

### Phase 1 — Auth and Setup

- [ ] Register (seeker path and clergy path)
- [ ] Email OTP verification, store JWT and userId
- [ ] Login, store JWT securely
- [ ] Forgot password and reset flow
- [ ] Social login (Google)
- [ ] Logout and clear storage
- [ ] JWT auto-refresh via `/api/refresh-token`
- [ ] Laravel Echo initialized on login with JWT
- [ ] `VITE_AGORA_APP_ID` in `.env`

### Phase 2 — CMS and Discovery

- [ ] Onboarding slides from API
- [ ] Home page CMS sections
- [ ] Find Clergy listing
- [ ] Clergy profile detail
- [ ] FAQs page
- [ ] Dynamic pages

### Phase 3 — Prayer Requests

- [ ] Seeker: submit prayer request
- [ ] Seeker: list and detail view
- [ ] Clergy: list with pending / active / history tabs
- [ ] Clergy: accept, decline with reason, close
- [ ] Notification handlers for prayer events

### Phase 4 — Chat

- [ ] List chat rooms
- [ ] Message list (paginated, reverse chronological)
- [ ] Send message
- [ ] Real-time messages via Echo `MessageEvent`
- [ ] Presence heartbeat every 60s while open
- [ ] Online indicators for participants

### Phase 5 — Live Session (Seeker)

- [ ] Join queue screen and API
- [ ] Queue waiting screen with dynamic position
- [ ] Leave queue button
- [ ] Heartbeat timer (40s interval, stop when status changes)
- [ ] Confirm ready screen with 10-min countdown from `confirmation_deadline_at`
- [ ] "Waiting for clergy" screen
- [ ] Handle broadcasts: `your_turn`, `clergy_started_video`, `rejected`, `abandoned`, `timeout`
- [ ] State restore on app reopen

### Phase 6 — Live Session (Clergy)

- [ ] Live availability toggle (online / offline)
- [ ] Queue dashboard with WebSocket updates and polling fallback
- [ ] Notify next seeker button
- [ ] Reject seeker from waiting queue with reason prompt
- [ ] Reject active session with reason input
- [ ] Start video call when `can_start_agora_call === true`
- [ ] Complete video call
- [ ] Auto-refresh queue after any action

### Phase 7 — Agora Video

- [ ] Install Agora SDK
- [ ] Camera and mic permission request
- [ ] Join from broadcast `agora_join` (seeker) or API response (clergy)
- [ ] Local video preview
- [ ] Remote video display
- [ ] Mute / unmute audio
- [ ] Enable / disable camera
- [ ] Leave channel on complete or navigate away
- [ ] Token refresh on `token-privilege-will-expire` event
- [ ] Fallback: call `/agora/join` if broadcast is missed

### Phase 8 — Notifications

- [ ] In-app notification list
- [ ] Mark as read
- [ ] Delete single / delete all
- [ ] Unread badge count
- [ ] Real-time via Echo private user channel

### Phase 9 — Polish

- [ ] Loading states on all API calls
- [ ] Error toasts for all failures
- [ ] Offline detection and reconnect
- [ ] Deep link handler for `/live/call/:id`
- [ ] Empty state UI for all list screens
- [ ] Pull-to-refresh on all lists
- [ ] Test full flow on two real devices (seeker and clergy simultaneously)

---

## 22. Quick Reference — All Endpoints

### Public (No Auth)

| Method | Endpoint |
|--------|----------|
| POST | `/api/register` |
| POST | `/api/verify-email` |
| POST | `/api/resend-otp` |
| POST | `/api/login` |
| POST | `/api/forget-password` |
| POST | `/api/verify-otp` |
| POST | `/api/reset-password` |
| POST | `/api/social-login` |
| GET | `/api/clergy` |
| GET | `/api/clergy/{id}` |
| GET | `/api/faqs` |
| GET | `/api/faqs-categories` |
| GET | `/api/faqs-categories/{id}` |
| POST | `/api/newsletter/subscribe` |
| GET | `/api/social-links` |
| GET | `/api/system-info` |
| GET | `/api/onboarding-screens` |
| POST | `/api/contact-support-message/sent` |
| GET | `/api/questions/seeker` |
| GET | `/api/questions/clergy` |
| GET | `/api/cms/home-page/hero-section` |
| GET | `/api/cms/home-page/counter-section` |
| GET | `/api/cms/home-page/meet-our-clergy-section` |
| GET | `/api/cms/home-page/how-it-works-section` |
| GET | `/api/cms/home-page/review-section` |
| GET | `/api/cms/home-page/spiritual-journey-section` |
| GET | `/api/cms/find-clergy-page/hero-section` |
| GET | `/api/cms/faq-page/hero-section` |
| GET | `/api/cms/how-it-work-page/hero-section` |
| GET | `/api/cms/how-it-work-page/work-progress-section` |
| GET | `/api/cms/how-it-work-page/our-values-section` |
| GET | `/api/cms/how-it-work-page/spiritual-journey-section` |
| GET | `/api/dynamic-pages` |
| GET | `/api/dynamic-pages/single/{slug}` |

### Authenticated (Any Role)

| Method | Endpoint |
|--------|----------|
| GET | `/api/refresh-token` |
| POST | `/api/logout` |
| GET | `/api/me` |
| POST | `/api/update-profile` |
| POST | `/api/update-password` |
| GET | `/api/dashboard-data/home` |
| GET | `/api/my-notifications` |
| POST | `/api/my-notifications/mark-as-read` |
| DELETE | `/api/my-notifications-delete/{id}` |
| DELETE | `/api/my-notifications-delete-all` |
| POST | `/api/questions/user-answer` |
| GET | `/api/chats` |
| GET | `/api/chats/{room_id}` |
| GET | `/api/chats/messages/{room_id}` |
| POST | `/api/chats/send-messages/{room_id}` |
| POST | `/api/chats/presence/{room_id}` |
| POST | `/api/chats/block/{room_id}` |
| GET | `/api/live-session-requests/{id}/agora/join` |
| POST | `/api/broadcasting/auth` |

### Seeker Only

| Method | Endpoint |
|--------|----------|
| GET | `/api/seeker/prayer-requests` |
| GET | `/api/seeker/prayer-requests/{id}` |
| POST | `/api/seeker/prayer-requests` |
| GET | `/api/seeker/live-session-requests` |
| GET | `/api/seeker/live-session-requests/{id}` |
| POST | `/api/seeker/live-session-queue/join` |
| POST | `/api/seeker/live-session-queue/{id}/heartbeat` |
| POST | `/api/seeker/live-session-queue/{id}/leave` |
| POST | `/api/seeker/live-session-queue/{id}/confirm-ready` |

### Clergy Only (Approved Clergy)

| Method | Endpoint |
|--------|----------|
| GET | `/api/clergy/profile` |
| POST | `/api/clergy/profile` |
| GET | `/api/clergy/registration-status` |
| POST | `/api/clergy/reapply-registration` |
| GET | `/api/clergy/prayer-requests` |
| GET | `/api/clergy/prayer-requests/{id}` |
| POST | `/api/clergy/prayer-requests/{id}/accept` |
| POST | `/api/clergy/prayer-requests/{id}/decline` |
| POST | `/api/clergy/prayer-requests/{id}/close` |
| GET | `/api/clergy/live-session-requests` |
| GET | `/api/clergy/live-session-requests/{id}` |
| POST | `/api/clergy/live-availability` |
| POST | `/api/clergy/live-session-queue/notify-next` |
| POST | `/api/clergy/live-session-requests/{id}/reject` |
| POST | `/api/clergy/live-session-requests/{id}/video/start` |
| POST | `/api/clergy/live-session-requests/{id}/video/complete` |

---

*Document version: 2026-06-27 — ClergyFinder platform (juanjperez Laravel API)*
