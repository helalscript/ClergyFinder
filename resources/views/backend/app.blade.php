@php
    $systemSetting = App\Models\SystemSetting::first();
@endphp

<!DOCTYPE html>
<html lang="zxx">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Links Of CSS File -->

    @include('backend.partials.style')
    <!-- Favicon -->
    <link rel="icon" type="image/png"
        href="{{ asset($systemSetting->favicon ?? 'backend/admin/assets/favicon.ico') }} ">
    <!-- Title -->
    <title>@yield('title') | {{ $systemSetting->system_name ?? '' }} </title>
</head>

<body class="boxed-size">


    @include('backend.partials.asidebar')

    <!-- Start Main Content Area -->
    <div class="container-fluid">
        <div class="main-content d-flex flex-column">

            @include('backend.partials.header')
            @yield('content')

            {{-- <div class="flex-grow-1"></div> --}}

            @include('backend.partials.footer')
        </div>
    </div>
    <!-- Start Main Content Area -->
    @include('backend.partials.settings_area')

    @include('backend.partials.script')


</body>

</html>
