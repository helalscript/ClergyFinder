@extends('backend.app')
@section('title', 'Edit Client Page')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
@endpush

@section('content')

    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">Edit Client Page</h3>

            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="#" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-4-line fs-18 text-primary me-1"></i>
                            <span class="text-secondary fw-medium hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-medium">Client Edit Page</span>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-medium">Edit</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">

                <div class="mb-4">
                    <h4 class="fs-20 mb-1">Edit Client</h4>
                    <p class="fs-15">Update Client details here.</p>
                </div>

                <form action="{{ route('users.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <!-- Company Title -->
                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Company Title<span class="text-danger">*</span></label>
                                <div class="form-group position-relative">
                                    <input type="text"
                                        class="form-control text-dark ps-5 h-55 @error('company_title') is-invalid @enderror"
                                        name="company_title" value="{{ old('company_title', $data->clientProfile->company_title?? '') }}" required
                                        placeholder="Enter Company Title here">
                                </div>
                                @error('company_title')
                                    <div id="company_title-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Name Field -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Name<span class="text-danger">*</span></label>
                                <div class="form-group position-relative">
                                    <input type="text"
                                        class="form-control text-dark ps-5 h-55 @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name', $data->name?? '') }}" required
                                        placeholder="Enter Client Name here">
                                </div>
                                @error('name')
                                    <div id="name-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Email Field -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Email<span class="text-danger">*</span></label>
                                <div class="form-group position-relative">
                                    <input type="email"
                                        class="form-control text-dark ps-5 h-55 @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email', $data->email?? '') }}" required
                                        placeholder="Enter Client Email here">
                                </div>
                                @error('email')
                                    <div id="email-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Password Field -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Password</label>
                                <div class="form-group position-relative">
                                    <input type="password"
                                        class="form-control text-dark ps-5 h-55 @error('password') is-invalid @enderror"
                                        name="password" placeholder="Enter Password here" value="{{ old('password') }}">
                                    <button type="button" class="btn position-absolute top-50 end-0 translate-middle-y" onclick="togglePasswordVisibility(this)">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                </div>
                                @if (old('password'))
                                    <div id="emailHelp" class="form-text">*Old password will be reset if you fill this field.</div>
                                @else
                                    <div id="emailHelp" class="form-text">*Leave this field blank if you don't want to change the password.</div>
                                @endif
                                @error('password')
                                    <div id="password-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Avatar Field -->
                        <div class="col-lg-6">
                            <div class="form-group ">
                                <label class="label text-secondary mb-1">Avatar<span class="text-danger">*</span></label>
                                <input class="dropify form-control @error('avatar') is-invalid @enderror" type="file"
                                    name="avatar" data-default-file="{{ isset($data) && $data->avatar ? asset($data->avatar) : '' }}">
                                @error('avatar')
                                    <div id="avatar-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap gap-3">
                                <button type="reset" class="btn btn-danger py-2 px-4 fw-medium fs-16 text-white"
                                    onclick="window.location.href='{{ route('users.index') }}'">Cancel</button>
                                <button type="submit" class="btn btn-primary py-2 px-4 fw-medium fs-16"> <i
                                        class="ri-check-line text-white fw-medium"></i> Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();
        })

        function togglePasswordVisibility(button) {
            const passwordInput = button.previousElementSibling;
            const icon = button.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('ri-eye-line');
                icon.classList.add('ri-eye-off-line');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('ri-eye-off-line');
                icon.classList.add('ri-eye-line');
            }
        }
    </script>

@endpush

