@extends('backend.app')
@section('title', 'Contact Us')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
@endpush

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">Contact Us List</h3>


            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="#" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-4-line fs-18 text-primary me-1"></i>
                            <span class="text-secondary fw-medium hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-medium">Contact Us</span>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-medium">Contact Us List</span>
                    </li>
                </ol>
            </nav>
        </div>
        {{-- ---------------------- --}}
        <div class="row justify-content-center ">
            <div class="col-md-8 ">
                <div class="card shadow-sm border-0 rounded-4 bg-white">
                    <div class="card-header bg-white text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Contact Us Details</h4>
                        <a href="{{ route('admin_contact_us.index') }}" class="btn bg-dark text-white btn-sm">Back</a>
                    </div>

                    <div class="card-body">
                        {{-- <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted">Type</label>
                                <div class="fw-bold">{{ $data->type ?? 'N/A' }}</div>
                            </div>
                        </div> --}}

                        <div class="row mb-3">
                            @if ($data->first_name)
                                <div class="col-md-6">
                                    <label class="text-muted">First Name</label>
                                    <div class="fw-bold">{{ $data->first_name }}</div>
                                </div>
                            @endif
                            @if ($data->last_name)
                                <div class="col-md-6">
                                    <label class="text-muted">Last Name</label>
                                    <div class="fw-bold">{{ $data->last_name }}</div>
                                </div>
                            @endif
                            @if ($data->full_name)
                                <div class="col-md-6">
                                    <label class="text-muted">Full Name</label>
                                    <div class="fw-bold">{{ $data->full_name }}</div>
                                </div>
                            @endif

                        </div>

                        <div class="row mb-3">
                            @if ($data->email)
                            <div class="col-md-6">
                                <label class="text-muted">Email</label>
                                <div class="fw-bold">{{ $data->email }}</div>
                            </div>
                            @endif
                            @if ($data->phone)
                                <div class="col-md-6">
                                    <label class="text-muted">Phone</label>
                                    <div class="fw-bold">{{ $data->phone }}</div>
                                </div>
                            @endif
                            @if ($data->company_name)
                                <div class="col-md-6">
                                    <label class="text-muted">Company Name</label>
                                    <div class="fw-bold">{{ $data->company_name }}</div>
                                </div>
                            @endif
                            @if ($data->linkedin_profile)
                                <div class="col-md-6">
                                    <label class="text-muted">Linkedin Profile</label>
                                    <div class="fw-bold">{{ $data->linkedin_profile }}</div>
                                </div>
                            @endif
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted">IP Address</label>
                                <div class="fw-bold">{{ $data->ip_address ?? 'N/A' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted">Submitted At</label>
                                <div class="fw-bold">{{ \Carbon\Carbon::parse($data->created_at)->format('d M, Y h:i A') }}
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted">Message</label>
                            <div class="border rounded p-3 bg-light fw-normal" style="min-height: 120px;">
                                {{ $data->message ?? 'N/A' }}
                            </div>
                        </div>

                        @if ($data->file)
                            <div class="mb-3">
                                <label class="text-muted">File</label>
                                <div class="border rounded p-3 bg-light fw-normal">
                                    <a href="{{ asset($data->file) }}" target="_blank"
                                        class="text-primary d-block">Download File</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
