@extends('backend.app')
@section('title', 'Chat Room')

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">Chat Room #{{ $roomData->id }}</h3>
            <a href="{{ route('admin.chats.index') }}" class="btn btn-outline-primary btn-sm">Back to Chats</a>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm border-0 rounded-4 bg-white mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            {{ $roomData->type === 'live_session' ? 'Live Session' : 'Prayer' }} Chat
                        </h5>
                        <span class="badge bg-{{ $roomData->status === 'completed' ? 'success' : 'warning' }}">
                            {{ ucfirst($roomData->status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted">Seeker</label>
                                <div class="fw-bold">{{ $roomData->seeker->name ?? '-' }}</div>
                                <small class="text-muted">{{ $roomData->seeker->email ?? '' }}</small>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted">Clergy</label>
                                <div class="fw-bold">{{ $roomData->clergy->name ?? '-' }}</div>
                                <small class="text-muted">{{ $roomData->clergy->email ?? '' }}</small>
                            </div>
                        </div>

                        @if($roomData->type === 'prayer' && $roomData->prayerRequest)
                            <div class="mb-3">
                                <label class="text-muted">Seeker Message</label>
                                <div class="border rounded p-3 bg-light">{{ $roomData->prayerRequest->message }}</div>
                            </div>
                            @if(!empty($roomData->prayerRequest->clergy_reply))
                                <div class="mb-3">
                                    <label class="text-muted">Clergy Reply</label>
                                    <div class="border rounded p-3 bg-light">{{ $roomData->prayerRequest->clergy_reply }}</div>
                                </div>
                            @endif
                        @endif

                        @if($roomData->type === 'live_session' && $roomData->liveSessionRequest)
                            <div class="mb-3">
                                <label class="text-muted">Scheduled Time</label>
                                <div class="fw-bold">
                                    {{ $roomData->liveSessionRequest->scheduled_time ? $roomData->liveSessionRequest->scheduled_time->format('d M, Y h:i A') : '-' }}
                                </div>
                            </div>
                            @if(!empty($roomData->liveSessionRequest->meeting_link))
                                <div class="mb-3">
                                    <label class="text-muted">Meeting Link</label>
                                    <div>
                                        <a href="{{ $roomData->liveSessionRequest->meeting_link }}" target="_blank" rel="noopener">
                                            {{ $roomData->liveSessionRequest->meeting_link }}
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="card shadow-sm border-0 rounded-4 bg-white">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Messages</h5>
                    </div>
                    <div class="card-body">
                        @forelse($roomData->messages as $message)
                            <div class="mb-3">
                                <small class="text-muted">
                                    {{ ucfirst($message->sender_type) }}
                                    @if($message->sender_type !== 'system' && $message->sender)
                                        ({{ $message->sender->name ?? 'User' }})
                                    @endif
                                    - {{ $message->created_at?->format('d M, Y h:i A') }}
                                </small>

                                <div class="border rounded p-3 {{ $message->sender_type === 'clergy' ? 'bg-light' : 'bg-white' }}">
                                    @if(!empty($message->body))
                                        <div class="mb-2">{{ $message->body }}</div>
                                    @endif

                                    @if(!empty($message->metadata) && is_array($message->metadata))
                                        @if(!empty($message->metadata['meeting_link']))
                                            <div class="mb-2">
                                                <strong>Meeting link:</strong>
                                                <a href="{{ $message->metadata['meeting_link'] }}" target="_blank" rel="noopener">
                                                    {{ $message->metadata['meeting_link'] }}
                                                </a>
                                            </div>
                                        @endif
                                        @if(!empty($message->metadata['scheduled_time']))
                                            <div>
                                                <strong>Scheduled time:</strong>
                                                {{ \Carbon\Carbon::parse($message->metadata['scheduled_time'])->format('d M, Y h:i A') }}
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-muted">No messages yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

