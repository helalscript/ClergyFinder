@extends('errors::minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('Something went wrong on our end.'))

@section('content')
    <div class="container-fluid">
        <div class="main-content d-flex flex-column p-0">
            <div class="m-auto text-center">
                <img src="{{ asset('backend/admin/assets/images/error.png') }}" class="mw-430 mb-4 w-100" alt="error">
                <h3 class="fs-24 mb-3">{{ __('Internal Server Error') }}</h3>
                <p class="mb-4">{{ __('We are currently experiencing some technical issues. Please try again later.') }}</p>
                <a href="{{ route('home') }}" class="btn btn-primary py-2 px-4 fs-16 fw-medium">
                    <span class="d-inline-block py-1">{{ __('Back To Home') }}</span>
                </a>
            </div>
        </div>
    </div>
@endsection
