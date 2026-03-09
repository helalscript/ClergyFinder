@extends('backend.app')
@section('title', 'Settings')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
@endpush

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">@yield('form_title') Settings</h3>

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
                        <span class="fw-medium">@yield('form_title')</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <ul class="ps-0 mb-4 list-unstyled d-flex flex-wrap gap-2 gap-lg-3">
                    <li>
                        <a href="{{ route('system_settings.index') }}"
                            class="btn btn-primary border border-primary {{ request()->routeIs('system_settings.index') ? 'bg-primary text-white' : 'bg-transparent text-primary' }} py-2 px-3 fw-semibold">System
                            Settings</a>
                    </li>
                    <li>
                        <a href="{{ route('system_settings.mail_get') }}"
                            class="btn btn-primary border border-primary {{ request()->routeIs('system_settings.mail_get') ? 'bg-primary text-white' : 'bg-transparent text-primary' }} py-2 px-3 fw-semibold">Mail
                            <Source:media:sizes></Source:media:sizes>Settings
                        </a>
                    </li>
                </ul>

                <div class="mb-4">
                    <h4 class="fs-20 mb-1">@yield('form_title') Settings</h4>
                    <p class="fs-15">Update your @yield('form_description') details here.</p>
                </div>
                {{-- here form --}} 
                @yield('form_content')
                
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
    </script>
@endpush
