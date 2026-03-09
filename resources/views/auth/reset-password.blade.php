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
                        <div class="auth-title">New password</div>
                        {{-- <p>Lorem ipsum dolor sit amet consectetur. Pretium id dui in ac. Condimentum lobortis sed egestas elementum
              viverra.</p> --}}
                        <form class="auth-form" method="POST" action="{{ route('password.store') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">
                          <input type="email" name="email" name="email" value="{{old('email', $request->email)}}" required autofocus autocomplete="username" hidden>
                            <!-- password  -->

                            <fieldset class="input-wrapper password-wrapper">
                                <label for="userPassword" class="input-label">Password</label>
                                <div class="icon-input-wrapper">
                                    <input class="input-field" type="password" id="userPassword" placeholder="********"
                                        name="password" />
                                    <button class="pass-show-btn" type="button">
                                        <span class="show-eye hide-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M15.5799 12C15.5799 13.98 13.9799 15.58 11.9999 15.58C10.0199 15.58 8.41992 13.98 8.41992 12C8.41992 10.02 10.0199 8.41998 11.9999 8.41998C13.9799 8.41998 15.5799 10.02 15.5799 12Z"
                                                    stroke="#929292" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                                <path
                                                    d="M12.0001 20.27C15.5301 20.27 18.8201 18.19 21.1101 14.59C22.0101 13.18 22.0101 10.81 21.1101 9.39997C18.8201 5.79997 15.5301 3.71997 12.0001 3.71997C8.47009 3.71997 5.18009 5.79997 2.89009 9.39997C1.99009 10.81 1.99009 13.18 2.89009 14.59C5.18009 18.19 8.47009 20.27 12.0001 20.27Z"
                                                    stroke="#929292" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                            </svg>
                                        </span>
                                        <span class="hide-eye">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M14.5299 9.46998L9.46992 14.53C8.81992 13.88 8.41992 12.99 8.41992 12C8.41992 10.02 10.0199 8.41998 11.9999 8.41998C12.9899 8.41998 13.8799 8.81998 14.5299 9.46998Z"
                                                    stroke="#929292" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                                <path
                                                    d="M17.8201 5.76998C16.0701 4.44998 14.0701 3.72998 12.0001 3.72998C8.47009 3.72998 5.18009 5.80998 2.89009 9.40998C1.99009 10.82 1.99009 13.19 2.89009 14.6C3.68009 15.84 4.60009 16.91 5.60009 17.77"
                                                    stroke="#929292" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                                <path
                                                    d="M8.41992 19.53C9.55992 20.01 10.7699 20.27 11.9999 20.27C15.5299 20.27 18.8199 18.19 21.1099 14.59C22.0099 13.18 22.0099 10.81 21.1099 9.39999C20.7799 8.87999 20.4199 8.38999 20.0499 7.92999"
                                                    stroke="#929292" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                                <path d="M15.5099 12.7C15.2499 14.11 14.0999 15.26 12.6899 15.52"
                                                    stroke="#929292" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                                <path d="M9.47 14.53L2 22" stroke="#929292" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M22 2L14.53 9.47" stroke="#929292" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                                @error('password')
                                    <span style="color:red">{{ $message }}</span>
                                @enderror

                            </fieldset>

                            <!-- confirm password -->
                            <fieldset class="input-wrapper password-wrapper">
                                <label for="userConfirmPassword" class="input-label">Confirm Password</label>
                                <div class="icon-input-wrapper">
                                    <input class="input-field" type="password" id="userConfirmPassword"
                                        placeholder="********" name="password_confirmation" />
                                    <button class="pass-show-btn" type="button">
                                        <span class="show-eye hide-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M15.5799 12C15.5799 13.98 13.9799 15.58 11.9999 15.58C10.0199 15.58 8.41992 13.98 8.41992 12C8.41992 10.02 10.0199 8.41998 11.9999 8.41998C13.9799 8.41998 15.5799 10.02 15.5799 12Z"
                                                    stroke="#929292" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                                <path
                                                    d="M12.0001 20.27C15.5301 20.27 18.8201 18.19 21.1101 14.59C22.0101 13.18 22.0101 10.81 21.1101 9.39997C18.8201 5.79997 15.5301 3.71997 12.0001 3.71997C8.47009 3.71997 5.18009 5.79997 2.89009 9.39997C1.99009 10.81 1.99009 13.18 2.89009 14.59C5.18009 18.19 8.47009 20.27 12.0001 20.27Z"
                                                    stroke="#929292" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                            </svg>
                                        </span>
                                        <span class="hide-eye">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M14.5299 9.46998L9.46992 14.53C8.81992 13.88 8.41992 12.99 8.41992 12C8.41992 10.02 10.0199 8.41998 11.9999 8.41998C12.9899 8.41998 13.8799 8.81998 14.5299 9.46998Z"
                                                    stroke="#929292" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                                <path
                                                    d="M17.8201 5.76998C16.0701 4.44998 14.0701 3.72998 12.0001 3.72998C8.47009 3.72998 5.18009 5.80998 2.89009 9.40998C1.99009 10.82 1.99009 13.19 2.89009 14.6C3.68009 15.84 4.60009 16.91 5.60009 17.77"
                                                    stroke="#929292" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                                <path
                                                    d="M8.41992 19.53C9.55992 20.01 10.7699 20.27 11.9999 20.27C15.5299 20.27 18.8199 18.19 21.1099 14.59C22.0099 13.18 22.0099 10.81 21.1099 9.39999C20.7799 8.87999 20.4199 8.38999 20.0499 7.92999"
                                                    stroke="#929292" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                                <path d="M15.5099 12.7C15.2499 14.11 14.0999 15.26 12.6899 15.52"
                                                    stroke="#929292" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                                <path d="M9.47 14.53L2 22" stroke="#929292" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M22 2L14.53 9.47" stroke="#929292" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <span style="color:red">{{ $message }}</span>
                                @enderror
                            </fieldset>
                            <button type="submit" class="button w-100">Continue</button>
                        </form>


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
