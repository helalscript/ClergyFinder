@extends('backend.app')
@section('title', 'View Client')

@push('styles')
    <style>
        .info-label {
            font-weight: 500;
            color: #6c757d;
            margin-bottom: 4px;
        }
        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: #343a40;
        }
        .profile-avatar {
            max-width: 150px;
            height: auto;
            border-radius: 10px;
        }
    </style>
@endpush

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">Client Details</h3>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-4-line fs-18 text-primary me-1"></i>
                            <span class="text-secondary fw-medium hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-medium">Client View</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white border-0 rounded-3">
            <div class="card-body p-4">

                <div class="mb-4">
                    <h4 class="fs-20 mb-1">Client Information</h4>
                    <p class="fs-15 text-muted">Details of the selected Client.</p>
                </div>

                <div class="row gy-4">
                    <!-- Avatar -->
                    <div class="col-lg-12 text-center">
                        <img src="{{ asset($data->avatar ?? 'backend/admin/assets/images/avatar_defult.png') }}"
                             alt="Client Avatar"
                             class="profile-avatar shadow-sm mb-3">
                    </div>

                    <!-- Name -->
                    <div class="col-md-6">
                        <div>
                            <div class="info-label">Name</div>
                            <div class="info-value">{{ $data->name ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <div>
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $data->email ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <!-- Company Title -->
                    <div class="col-md-6">
                        <div>
                            <div class="info-label">Company Title</div>
                            <div class="info-value">{{ $data->clientProfile->company_title ?? 'N/A' }}</div>
                        </div>
                    </div>

                    {{-- <!-- Phone -->
                    <div class="col-md-6">
                        <div>
                            <div class="info-label">Phone</div>
                            <div class="info-value">{{ $data->phone ?? 'N/A' }}</div>
                        </div>
                    </div> --}}


                </div>

            </div>
        </div>
    </div>
@endsection
