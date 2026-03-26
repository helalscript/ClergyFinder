@extends('errors::minimal')

@section('title', __('Service Unavailable'))
@section('code', '503')
@section('message', __('We are currently undergoing maintenance. Please check back later.'))

@section('content')
    <div class="container-fluid">
        <div class="main-content d-flex flex-column p-0">
            <div class="m-auto text-center">
                <img src="{{ asset('backend/admin/assets/images/error.png') }}" class="mw-430 mb-4 w-100" alt="error">
                <h3 class="fs-24 mb-3">{{ __('Service Unavailable') }}</h3>
                <p class="mb-4">{{ __('We are currently performing maintenance. We will be back soon!') }}</p>
                <a href="{{ route('home') }}" class="btn btn-primary py-2 px-4 fs-16 fw-medium">
                    <span class="d-inline-block py-1">{{ __('Back To Home') }}</span>
                </a>
            </div>
        </div>
    </div>
@endsection
