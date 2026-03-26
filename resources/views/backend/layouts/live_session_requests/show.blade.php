@extends('backend.app')
@section('title', 'Live Session Request Details')

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">Live Session Request Details</h3>
            <a href="{{ route('admin.live_session_requests.index') }}" class="btn btn-outline-primary btn-sm">Back to List</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 rounded-4 bg-white mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Request #{{ $data->id }}</h5>
                        <span class="badge bg-{{ $data->status === 'completed' ? 'success' : ($data->status === 'scheduled' ? 'info' : 'warning') }}">{{ ucfirst($data->status) }}</span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted">Seeker</label>
                                <div class="fw-bold">{{ $data->seeker->name ?? '-' }}</div>
                                <small class="text-muted">{{ $data->seeker->email ?? '' }}</small>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted">Assigned Clergy</label>
                                <div class="fw-bold">{{ $data->clergy->name ?? 'Not assigned' }}</div>
                                @if($data->clergy)<small class="text-muted">{{ $data->clergy->email }}</small>@endif
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted">Submitted At</label>
                                <div class="fw-bold">{{ $data->created_at->format('d M, Y h:i A') }}</div>
                            </div>
                            @if($data->scheduled_time)
                                <div class="col-md-6">
                                    <label class="text-muted">Scheduled Time</label>
                                    <div class="fw-bold">{{ $data->scheduled_time->format('d M, Y h:i A') }}</div>
                                </div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="text-muted">Message</label>
                            <div class="border rounded p-3 bg-light">{{ $data->message }}</div>
                        </div>
                        @if($data->meeting_link)
                            <div class="mb-3">
                                <label class="text-muted">Meeting Link</label>
                                <div><a href="{{ $data->meeting_link }}" target="_blank" rel="noopener">{{ $data->meeting_link }}</a></div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card shadow-sm border-0 rounded-4 bg-white mb-4">
                    <div class="card-header bg-white"><h5 class="mb-0">Assign Clergy</h5></div>
                    <div class="card-body">
                        <form action="{{ route('admin.live_session_requests.assign_clergy', $data->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Select Clergy</label>
                                <select name="clergy_id" class="form-select" required>
                                    <option value="">-- Select clergy --</option>
                                    @foreach($clergyList as $clergy)
                                        <option value="{{ $clergy->id }}" {{ $data->clergy_id == $clergy->id ? 'selected' : '' }}>{{ $clergy->name }} ({{ $clergy->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Assign Clergy</button>
                        </form>
                    </div>
                </div>

                @if($data->status !== 'completed')
                    <form action="{{ route('admin.live_session_requests.mark_completed', $data->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">Mark as Completed</button>
                    </form>
                @endif

                <div class="mt-3">
                    <button type="button" class="btn btn-danger" onclick="deleteRecord(event, {{ $data->id }})">Delete Request</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('backend/admin/assets/custom-actions.js') }}"></script>
    <script>
        function deleteRecord(event, id) { event.preventDefault(); let deleteUrl = '{{ route('admin.live_session_requests.destroy', ':id') }}'.replace(':id', id); showDeleteConfirm(id, deleteUrl); }
    </script>
@endpush
