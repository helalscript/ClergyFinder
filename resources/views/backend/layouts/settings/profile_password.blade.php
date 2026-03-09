@extends('backend.app')
@section('title', 'Profile Password')

@push('styles')
@endpush
@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">Change Password</h3>

            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="#" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-4-line fs-18 text-primary me-1"></i>
                            <span class="text-secondary fw-medium hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-medium">Settings</span>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-medium">Change Password</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <ul class="ps-0 mb-4 list-unstyled d-flex flex-wrap gap-2 gap-lg-3">
                    <li>
                        <a href="{{route('profile_settings.index')}}"
                            class="btn btn-primary border border-primary bg-transparent text-primary py-2 px-3 fw-semibold">Account
                            Settings</a>
                    </li>
                    <li>
                        <a href="{{route('profile_settings.password')}}"
                            class="btn btn-primary border border-primary bg-primary text-white py-2 px-3 fw-semibold">Change
                            Password</a>
                    </li>
                </ul>

                <form action="{{ route('profile_settings.password_change') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Old Password</label>
                                <div class="form-group">
                                    <div class="password-wrapper position-relative">
                                        <input type="password" id="password" class="form-control h-55 text-dark @error('old_password') is-invalid @enderror" name="old_password" required>
                                        <i style="color: #A9A9C8; font-size: 16px; right: 15px;"
                                            class="ri-eye-off-line password-toggle-icon translate-middle-y top-50 position-absolute"
                                            aria-hidden="true"></i>
                                    </div>
                                    @error('old_password')
                                        <div id="old_password-error" class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">New Password</label>
                                <div class="form-group">
                                    <div class="password-wrapper position-relative">
                                        <input type="password" class="form-control h-55 text-dark @error('password') is-invalid @enderror" name="password" required>
                                        {{-- <i style="color: #A9A9C8; font-size: 16px; right: 15px;"
                                            class="ri-eye-off-line password-toggle-icon translate-middle-y top-50 position-absolute"
                                            aria-hidden="true"></i> --}}
                                    </div>
                                    @error('password')
                                        <div id="password-error" class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Confirm Password</label>
                                <div class="form-group">
                                    <div class="password-wrapper position-relative">
                                        <input type="password" class="form-control h-55 text-dark" name="password_confirmation" required>
                                        {{-- <i style="color: #A9A9C8; font-size: 16px; right: 15px;"
                                            class="ri-eye-off-line password-toggle-icon translate-middle-y top-50 position-absolute"
                                            aria-hidden="true"></i> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group d-flex gap-3 align-items-center">
                                <button type="submit" class="btn btn-primary py-2 px-4 fw-medium fs-16"> <i
                                        class="ri-check-line text-white fw-medium"></i> Change Password</button>
                                {{-- <a href="#" class="text-danger fs-16 text-decoration-none">Forgot Password?</a> --}}
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

@endpush
