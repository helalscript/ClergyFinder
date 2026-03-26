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
            <div class="auth-title">Email your account</div>
            <div class="se--new-user">
              <p>New user?</p>
              <a href="{{route('register')}}">Create an Account</a>

            </div>
            <form class="auth-form" method="POST" action="{{ route('password.email') }}">
              @csrf
              <fieldset class="input-wrapper">
                <label for="userEmail" class="input-label">Email</label>
                <input type="email" id="userEmail" class="input-field" placeholder="kolchie@mail.com" required name="email" :value="old('email')"  autofocus autocomplete="username" />
              </fieldset>
              @if ($errors->has('email'))
                <div class="text-danger mb-2">{{ $errors->first('email') }}</div>
              @endif
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

