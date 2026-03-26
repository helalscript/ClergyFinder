@php
    $systemSetting = App\Models\SystemSetting::first();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <title>Service Banner</title> --}}

    @include('backend.partials.style')

    <!-- Favicon -->
    {{-- <link rel="icon" type="image/png" href="{{ asset('backend/admin/assets/images/favicon.png')}}"> --}}
    <link rel="shortcut icon" href="{{ asset($systemSetting->favicon ?? 'favicon.ico') }}" type="image/x-icon">
    <!-- Title -->
    <title>Login | {{ $systemSetting->system_name ?? '' }} </title>
</head>

<body class="boxed-size bg-white">


    <!-- Start Main Content Area -->
    <div class="container">
        <div class="main-content d-flex flex-column p-0">
            <div class="m-auto m-1230">
                <div class="row align-items-center">
                    <div class="col-lg-6 d-none d-lg-block">
                        {{-- <img src="{{ asset('backend/admin')}}/assets/images/login.jpg" class="rounded-3" alt="login"> --}}
                    </div>
                    <div class="col-lg-12">
                        <div class="mw-480 ms-lg-auto">
                            <div class="d-inline-block mb-4">
                                 <img src="{{ asset($systemSetting->logo ??'') }}"
                                    class="rounded-3 for-light-logo" alt="login" height="200px" width="200px">
                                {{--<img src="{{ asset('backend/admin') }}/assets/images/white-logo.svg"
                                    class="rounded-3 for-dark-logo" alt="login"> --}}
                            </div>
                            <h3 class="fs-28 mb-2">Welcome back to {{ $systemSetting->system_name ?? '' }}!</h3>
                            


                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                @method('POST')
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Email Address</label>
                                    <input type="email" class="form-control h-55" id="userEmail"
                                        placeholder="Enter your Email" required name="email" value="{{ old('email', '') }}"
                                        autofocus autocomplete="username" style="width: 500px">
                                        <span class="text-red-600 text-sm" style="color: red">{{ $errors->first('email') }}</span> 
                                </div>
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Password</label>
                                    <input type="password" class="form-control h-55" placeholder="Type password"
                                        id="userPassword" name="password" required autocomplete="current-password" style="width: 500px">
                                </div>
                                <div class="form-group mb-4">
                                    {{-- <a href="{{route('password.request')}}"
                                        class="text-decoration-none text-primary fw-semibold">Forgot Password?</a> --}}
                                </div>
                                <div class="form-group mb-4">
                                    <button type="submit" class="btn btn-primary fw-medium py-2 px-3 w-100">
                                        <div class="d-flex align-items-center justify-content-center py-1">
                                            <i class="material-symbols-outlined text-white fs-20 me-2">login</i>
                                            <span>Login</span>
                                        </div>
                                    </button>
                                </div>
                                <div class="form-group">
                                    {{-- <p>Don’t have an account. <a href="{{ route('register') }}"
                                            class="fw-medium text-primary text-decoration-none">Register</a></p> --}}
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <button class="switch-toggle settings-btn dark-btn p-0 bg-transparent position-absolute top-0 d-none"
        id="switch-toggle">
        <span class="dark"><i class="material-symbols-outlined">light_mode</i></span>
        <span class="light"><i class="material-symbols-outlined">dark_mode</i></span>
    </button>

    @include('backend.partials.script')
</body>

</html>
