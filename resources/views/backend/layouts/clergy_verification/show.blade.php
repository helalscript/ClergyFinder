@extends('backend.app')
@section('title', 'Review clergy registration')

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">Review clergy registration</h3>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.clergy_verification.index') }}" class="btn btn-outline-secondary">Back to list</a>
                @if ($clergy->user)
                    <a href="{{ route('user_management.clergy.show', $clergy->user->id) }}" class="btn btn-outline-primary">Open user profile</a>
                @endif
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">
            <div class="col-lg-4 mb-3">
                <div class="card bg-white border-0 rounded-3 h-100">
                    <div class="card-body text-center">
                        @php
                            $avatarPath = $clergy->user?->getRawOriginal('avatar');
                        @endphp
                        <div class="mb-3">
                            <img src="{{ $avatarPath ? asset($avatarPath) : asset('backend/admin/assets/images/avatar_defult.png') }}"
                                alt="avatar" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                        </div>
                        <h4 class="mb-1">{{ $clergy->user?->name }}</h4>
                        <p class="text-muted mb-1">{{ $clergy->user?->email }}</p>
                        <p class="text-muted mb-0">{{ $clergy->user?->phone }}</p>
                        <span class="badge bg-warning text-dark mt-2">Pending verification</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 mb-3">
                <div class="card bg-white border-0 rounded-3 mb-3">
                    <div class="card-body">
                        <h5 class="mb-3">Clergy profile</h5>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Religion</strong>
                                <p class="mb-0">{{ $clergy->religion ?? '—' }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Denomination</strong>
                                <p class="mb-0">{{ $clergy->denomination ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Languages</strong>
                                <p class="mb-0">
                                    @if (is_array($clergy->preferred_language))
                                        {{ implode(', ', $clergy->preferred_language) }}
                                    @else
                                        {{ $clergy->preferred_language ?? '—' }}
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <strong>Specialties</strong>
                                <p class="mb-0">
                                    @if (is_array($clergy->specialities))
                                        {{ implode(', ', $clergy->specialities) }}
                                    @else
                                        {{ $clergy->specialities ?? '—' }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="mb-2">
                            <strong>Bio</strong>
                            <p class="mb-0 text-secondary">{{ $clergy->bio ?? '—' }}</p>
                        </div>
                        <div class="row mb-0">
                            <div class="col-md-6">
                                <strong>Submitted</strong>
                                <p class="mb-0">{{ $clergy->created_at?->format('M j, Y g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card bg-white border-0 rounded-3 mb-3">
                    <div class="card-body">
                        <h5 class="mb-3">Credential documents</h5>
                        @php
                            $docs = $clergy->credential_documents;
                        @endphp
                        @if (is_array($docs) && count($docs) > 0)
                            <ul class="list-group list-group-flush">
                                @foreach ($docs as $i => $url)
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Document {{ $i + 1 }}</span>
                                        <a href="{{ str_starts_with($url, 'http') ? $url : asset($url) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">Open</a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mb-0">No credential files were uploaded.</p>
                        @endif
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card border border-success rounded-3 h-100">
                            <div class="card-body">
                                <h6 class="text-success mb-3">Approve</h6>
                                <p class="small text-muted mb-3">The clergy will be listed publicly and can use clergy app features.</p>
                                <form action="{{ route('admin.clergy_verification.approve', $clergy) }}" method="post" onsubmit="return confirm('Approve this clergy registration?');">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100">Approve registration</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border border-danger rounded-3 h-100">
                            <div class="card-body">
                                <h6 class="text-danger mb-3">Reject</h6>
                                <form action="{{ route('admin.clergy_verification.reject', $clergy) }}" method="post">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Reason (sent by email)</label>
                                        <textarea name="reason" class="form-control" rows="4" required maxlength="2000" placeholder="Explain why the registration is rejected…"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-danger w-100">Reject and notify</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
