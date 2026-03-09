@extends('backend.app')
@section('title', 'Profile')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
    <style>
        .dropify-wrapper {
            height: 200px;
        }

        .dropify-wrapper .dropify-preview .dropify-render img {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
@endpush
@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">Profile Settings</h3>

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
                        <span class="fw-medium">Profile</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <ul class="ps-0 mb-4 list-unstyled d-flex flex-wrap gap-2 gap-lg-3">
                    <li>
                        <a href="{{ route('profile_settings.index') }}"
                            class="btn btn-primary border border-primary bg-primary text-white py-2 px-3 fw-semibold">Account
                            Settings</a>
                    </li>
                    <li>
                        <a href="{{ route('profile_settings.password') }}"
                            class="btn btn-primary border border-primary bg-transparent text-primary py-2 px-3 fw-semibold">Change
                            Password</a>
                    </li>
                </ul>

                <div class="mb-4">
                    <h4 class="fs-20 mb-1">Profile</h4>
                    <p class="fs-15">Update your photo and personal details here.</p>
                </div>

                <form action="{{ route('profile_settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Name</label>
                                <div class="form-group position-relative">
                                    <input type="text"
                                        class="form-control text-dark ps-5 h-55 @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name', $user->name) }}" required
                                        placeholder="Enter Name here">
                                    <i
                                        class="ri-user-line position-absolute top-50 start-0 translate-middle-y fs-20 text-gray-light ps-20"></i>
                                </div>
                                @error('name')
                                    <div id="name-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Email Address</label>
                                <div class="form-group position-relative">
                                    <input type="email"
                                        class="form-control text-dark ps-5 h-55 @error('email') is-invalid @enderror"
                                        name="email" required placeholder="Email Address"
                                        value="{{ old('email', $user->email) }}">
                                    <i
                                        class="ri-mail-line position-absolute top-50 start-0 translate-middle-y fs-20 text-gray-light ps-20"></i>
                                </div>
                                @error('email')
                                    <div id="email-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Phone</label>
                                <div class="form-group position-relative">
                                    <input type="tel"
                                        class="form-control text-dark ps-5 h-55 @error('phone') is-invalid @enderror"
                                        name="phone" required placeholder="0123122123"
                                        value="{{ old('phone', $user->phone) }}">
                                    <i
                                        class="ri-phone-line position-absolute top-50 start-0 translate-middle-y fs-20 text-gray-light ps-20"></i>
                                </div>
                                @error('phone')
                                    <div id="phone-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Gender</label>
                                <div class="form-group position-relative">
                                    <select class="form-select form-control ps-5 h-55 @error('gender') is-invalid @enderror"
                                        name="gender" required aria-label="Default select example">
                                        <option value="" class="text-dark">Select Gender</option>
                                        <option value="male"
                                            {{ old('gender', $user->gender ?? '') == 'male' ? 'selected' : '' }}
                                            class="text-dark">Male</option>
                                        <option value="female"
                                            {{ old('gender', $user->gender ?? '') == 'female' ? 'selected' : '' }}
                                            class="text-dark">Female</option>
                                        <option value="others"
                                            {{ old('gender', $user->gender ?? '') == 'others' ? 'selected' : '' }}
                                            class="text-dark">Others</option>
                                    </select>
                                    <i
                                        class="ri-men-line position-absolute top-50 start-0 translate-middle-y fs-20 text-gray-light ps-20"></i>
                                </div>
                                @error('gender')
                                    <div id="gender-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group ">
                                <label class="label text-secondary mb-1">Your Photo</label>
                                <span class="d-block mb-3">This will be displayed on your profile.</span>
                                {{-- <div class="form-control h-100 text-center position-relative p-4 p-lg-5"> --}}
                                <input class="dropify form-control @error('avatar') is-invalid @enderror" type="file"
                                    name="avatar"
                                    data-default-file="{{ asset(auth()->user()->avatar ? auth()->user()->avatar : 'backend/admin/assets/images/avatar_defult.png') }}">
                                @error('avatar')
                                    <div id="avatar-error" class="text-danger">{{ $message }}</div>
                                @enderror
                                {{-- </div> --}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap gap-3">
                                {{-- <button type="submit" class="btn btn-danger py-2 px-4 fw-medium fs-16 text-white">Cancel</button> --}}
                                <button type="submit" class="btn btn-primary py-2 px-4 fw-medium fs-16"> <i
                                        class="ri-check-line text-white fw-medium"></i> Update Profile</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('frontend/assets/js/plugins/jquery-3.7.1.min.js') }}"></script>
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();
        })
    </script>
@endpush
