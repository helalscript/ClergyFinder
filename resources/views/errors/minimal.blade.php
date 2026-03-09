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
    <!-- Links Of CSS File -->

    @include('backend.partials.style')
    <!-- Favicon -->
    <link rel="icon" type="image/png"
        href="{{ asset($systemSetting->favicon ?? 'backend/admin/assets/favicon.ico') }} ">
    <!-- Title -->
    <title>@yield('title') | {{ $systemSetting->system_name ?? '' }} </title>
</head>

<body class="boxed-size">
        @yield('header')

        @yield('content')
</body>

</html>
