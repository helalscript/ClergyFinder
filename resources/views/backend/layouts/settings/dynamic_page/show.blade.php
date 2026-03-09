@php
    $systemSetting = App\Models\SystemSetting::first();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->page_title }} || {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        footer {
            background-color: #f8f9fa;
            padding: 20px 0;
            margin-top: 50px;
        }
        /* Style for the banner */
        .banner {
            background-image: url('your-banner-image.jpg'); /* Replace with your banner image */
            background-size: cover;
            background-position: center;
            height: 300px; /* Adjust height as needed */
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        .banner h1 {
            font-size: 3rem;
            margin: 0;
        }
        /* Ensure .card-body has a minimum height but expands with content */
        .card-body {
            min-height: 600px; /* Adjust the minimum height as per your design */
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header class="bg-light shadow-sm">
        <nav class="navbar navbar-expand-lg navbar-light bg-light container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset($systemSetting->logo ?? '/backend/images/logo.png') }}" alt="Logo" style="height: 50px; border-radius:50%;">
                {{ config('app.name')}}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('about') ? 'active' : '' }}" href="{{ url("pages/$page->page_slug") }}">{{ $page->page_title }}</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link {{ Request::is('about') ? 'active' : '' }}" href="{{ url('pages/about') }}">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('services') ? 'active' : '' }}" href="{{ url('pages/services') }}">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('contact') ? 'active' : '' }}" href="{{ url('pages/contact') }}">Contact</a>
                    </li> --}}
                </ul>
                {{-- <a href="#" class="btn btn-outline-primary">Login</a> --}}
            </div>
        </nav>
    </header>

    <!-- Banner Section -->
    <div class="banner" style="background-color: rgb(131, 175, 175)">
        <h1>{{ $page->page_title }}</h1>
    </div>

    <!-- Main Content -->
    <div class="container my-5">
        <div class="card shadow">
            <div class="card-body">
                <p class="text-muted small">Last updated: {{ \Carbon\Carbon::parse($page->updated_at)->format('F j, Y') }}</p>
                {!! $page->page_content !!}
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="text-center">
        <div class="container">
            <p class="mb-0">&copy; 2024 {{ config('app.name') }}. All rights reserved.</p>
            <ul class="list-inline">
                {{-- <li class="list-inline-item"><a href="#">Privacy Policy</a></li>
                <li class="list-inline-item"><a href="#">Terms and Conditions</a></li>
                <li class="list-inline-item"><a href="#">Contact Us</a></li> --}}
            </ul>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
