@extends('backend.app')

@section('title')
    Home Page
@endsection
@section('header')
    {{-- @include('frontend.partials.header') --}}
    {{-- @include('frontend.partials.header2') --}}
@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/service.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/serviceResponsive.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/plugins/aos-2.3.1.min.css') }}" />
@endpush

@section('content')
    <!-- main section start -->
    <main class="auth-container mt-md-5 mb-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6" data-aos="fade-right">
                    <figure class="auth-img">
                        <img src="{{ asset('frontend/assets') }}/images/auth.png" alt="auth image" />
                    </figure>
                </div>
                <div class="col-md-6" data-aos="fade-left">
                    <div class="auth-card">
                        <div class="auth-title">Sign Up</div>
                        <form class="auth-form" method="POST" action="{{ route('register') }}">
                            @csrf
                            <fieldset class="input-wrapper">
                                <label for="userName" class="input-label">Name</label>
                                <input type="text" id="userName" class="input-field" placeholder="Enter your Name"
                                    required name="name"    value="{{ old('name') }}" />
                                    <span class="text-red-600 text-sm" style="color: red">{{ $errors->first('name') }}</span> 
                            </fieldset>
                            <fieldset class="input-wrapper">
                                <label for="userEmail" class="input-label">Email</label>
                                <input type="email" id="userEmail" class="input-field" placeholder="Enter your Email"
                                        required name="email" value="{{ old('email') }}" />
                                    <span class="text-red-600 text-sm" style="color: red">{{ $errors->first('email') }}</span> 
                            </fieldset>
                            <fieldset class="input-wrapper password-wrapper">
                                <label for="userPassword" class="input-label">Password</label>
                                <input type="password" id="userPassword" class="input-field" placeholder="******"
                                    required name="password" /> 
                                    <span class="text-red-600 text-sm" style="color: red">{{ $errors->first('password') }}</span> 
                            </fieldset>
                            <fieldset class="input-wrapper password-wrapper">
                                <label for="userPassword" class="input-label">Confirm Password</label>
                                <input type="password" id="userPassword" class="input-field" placeholder="******"
                                    required name="password_confirmation" /> 
                                    <span class="text-red-600 text-sm" style="color: red">{{ $errors->first('password_confirmation') }}</span> 
                            </fieldset>
                            <fieldset class="checkbox-wrapper">
                                <input type="checkbox" id="terms" class="checkbox-field" required />
                                <label class="checkbox-label" for="terms">
                                    I Accept Terms and Condition
                                </label>
                            </fieldset>
                            <button type="submit" class="button w-100">Sign Up</button>
                        </form>
                        <div class="auth-des auth-bottom text-center">
                            Already have an account? <a href="{{ route('login') }}">Sign In</a>
                        </div>
                        {{-- <div class="text-separator">
                            <div class="bar"></div>
                            <div class="text">or</div>
                            <div class="bar"></div>
                        </div>
                        <button type="button" class="social-auth-btn" id="google-auth-btn">
                            <img src="{{ asset('frontend/assets') }}/images/google-logo-9808 1.png" alt="google logo" />
                            <span>Sign Up Google account</span>
                        </button>
                        <button type="button" class="social-auth-btn" id="facebook-auth-btn">
                            <img src="{{ asset('frontend/assets') }}/images/logos_facebook.png" alt="facebook logo" />
                            <span>Sign Up Facebook account</span>
                        </button> --}}
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- main section end -->
@endsection

@push('scripts')
<script type="text/javascript" src="{{ asset('frontend/assets/js/plugins/aos-2.3.1.min.js') }}"></script>
@endpush
