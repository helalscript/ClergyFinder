@extends('backend.app')
@section('title', 'Clergy Details')

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">Clergy Details</h3>
            <a href="{{ route('user_management.clergy.edit', $data->id) }}" class="btn btn-primary">
                <i class="ri-edit-line"></i> Edit
            </a>
        </div>

        <div class="row">
            <div class="col-lg-4 mb-3">
                <div class="card bg-white border-0 rounded-3 h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <img src="{{ $data->avatar ? asset($data->avatar) : asset('backend/admin/assets/images/avatar_defult.png') }}"
                                 alt="avatar" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                        </div>
                        <h4 class="mb-1">{{ $data->name }}</h4>
                        <p class="text-muted mb-1">{{ $data->email }}</p>
                        <p class="text-muted mb-0">{{ $data->phone }}</p>
                        <span class="badge bg-{{ $data->status === 'active' ? 'success' : 'secondary' }} mt-2">
                            {{ ucfirst($data->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 mb-3">
                <div class="card bg-white border-0 rounded-3 h-100">
                    <div class="card-body">
                        <h5 class="mb-3">Basic Info</h5>
                        <div class="row mb-3">
                            {{-- <div class="col-md-4">
                                <strong>First Name:</strong>
                                <p class="mb-0">{{ $data->first_name }}</p>
                            </div>
                            <div class="col-md-4">
                                <strong>Last Name:</strong>
                                <p class="mb-0">{{ $data->last_name }}</p>
                            </div> --}}
                            <div class="col-md-6">
                                <strong>Full Name:</strong>
                                <p class="mb-0">{{ $data->name }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Gender:</strong>
                                <p class="mb-0 text-capitalize">{{ $data->gender }}</p>
                            </div>
                        </div>

                        @if($data->clergy)
                            <hr>
                            <h5 class="mb-3">Clergy Details</h5>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Religion:</strong>
                                    <p class="mb-0">{{ $data->clergy->religion }}</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Preferred Languages:</strong>
                                    <p class="mb-0">
                                        @if(is_array($data->clergy->preferred_language))
                                            {{ implode(', ', $data->clergy->preferred_language) }}
                                        @else
                                            {{ $data->clergy->preferred_language }}
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Specialities:</strong>
                                    <p class="mb-0">
                                        @if(is_array($data->clergy->specialities))
                                            {{ implode(', ', $data->clergy->specialities) }}
                                        @else
                                            {{ $data->clergy->specialities }}
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Address:</strong>
                                    <p class="mb-0">{{ $data->clergy->address }}</p>
                                </div>
                                <div class="col-md-3">
                                    <strong>City:</strong>
                                    <p class="mb-0">{{ $data->clergy->city }}</p>
                                </div>
                                <div class="col-md-3">
                                    <strong>State:</strong>
                                    <p class="mb-0">{{ $data->clergy->state }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <strong>ZIP Code:</strong>
                                    <p class="mb-0">{{ $data->clergy->zip_code }}</p>
                                </div>
                                <div class="col-md-3">
                                    <strong>Country:</strong>
                                    <p class="mb-0">{{ $data->clergy->country }}</p>
                                </div>
                                <div class="col-md-3">
                                    <strong>Latitude:</strong>
                                    <p class="mb-0">{{ $data->clergy->latitude }}</p>
                                </div>
                                <div class="col-md-3">
                                    <strong>Longitude:</strong>
                                    <p class="mb-0">{{ $data->clergy->longitude }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Available At:</strong>
                                    <p class="mb-0">{{ $data->clergy->available_at }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Bio:</strong>
                                    <p class="mb-0">{{ $data->clergy->bio }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <strong>Flags:</strong>
                                    <p class="mb-0">
                                        <span class="badge bg-{{ $data->clergy->is_verified ? 'success' : 'secondary' }}">Verified</span>
                                        <span class="badge bg-{{ $data->clergy->is_live_session ? 'success' : 'secondary' }}">Live Session</span>
                                        <span class="badge bg-{{ $data->clergy->is_prayer_request ? 'success' : 'secondary' }}">Prayer Request</span>
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

