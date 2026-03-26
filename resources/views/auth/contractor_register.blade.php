@extends('backend.app')

@section('title')
    Provider Register Page
@endsection

@section('header')
    {{-- @include('frontend.partials.header2') --}}
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/serviceResponsive.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/service.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/plugins/aos-2.3.1.min.css') }}" />

    <style>
        #map {
            width: 100%;
            height: 40vh;
            border-radius: 10px;
        }

        .leaflet-top.leaflet-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 8px;
        }

        .map-button {
            background: white;
            border: 2px solid #ccc;
            border-radius: 5px;
            padding: 8px;
            cursor: pointer;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
            font-size: 14px;
        }

        .map-button:hover {
            background: #f0f0f0;
        }
    </style>
@endpush

@section('content')
    <main class="auth-container mt-md-5 mb-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6" data-aos="fade-right">
                    <figure class="auth-img">
                        <img src="{{ asset('frontend/assets') }}/images/auth.png" alt="auth image" />
                    </figure>
                </div>
                <div class="col-md-6" data-aos="fade-left">
                    <div class="auth-step-form-container">
                        <form class="auth-step-form" method="POST" action="{{ route('provider.register.store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="step-content">
                                <div class="step-title">Tell us about yourself</div>

                                <div class="form-group">
                                    <label>Upload Profile Image</label>
                                    <div class="profile-upload-container">
                                        <div class="profile-upload-box">
                                            <div class="content">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                    viewBox="0 0 32 32" fill="none">
                                                    <path d="M16 11V21M21 16H11M31 16C31 17.9698 ..." stroke="#222222"
                                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <div class="text">Upload Photo</div>
                                            </div>
                                            <input type="file" name="avatar" accept="image/*" hidden />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" name="name" placeholder="Enter your name"
                                        value="{{ old('name') }}" required />
                                    <span class="text-red-600 text-sm"
                                        style="color: red">{{ $errors->first('name') }}</span>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" placeholder="Enter your email"
                                        value="{{ old('email') }}" required />
                                    <span class="text-red-600 text-sm"
                                        style="color: red">{{ $errors->first('email') }}</span>
                                </div>

                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" id="phone" name="phone" inputmode="numeric"
                                        placeholder="Enter your phone number" value="{{ old('phone') }}" required />
                                    <span class="text-red-600 text-sm"
                                        style="color: red">{{ $errors->first('phone') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="instagram_social_link">Instagram Social Link</label>
                                    <input type="text" id="instagram_social_link" name="instagram_social_link"
                                        placeholder="https://www.instagram.com/kolchie...."
                                        value="{{ old('instagram_social_link') }}" />
                                    <span class="text-red-600 text-sm"
                                        style="color: red">{{ $errors->first('instagram_social_link') }}</span>
                                </div>
                                <!-- Category -->
                                <div class="form-group">
                                    <label class="label text-secondary">Category<span style="color: red">*</span></label>
                                    <select id="category" class="form-select @error('category_id') is-invalid @enderror"
                                        name="category_id" required>
                                        <option value="" selected disabled>Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}
                                                data-subcategories="{{ json_encode($category->subCategories) }}">
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="userPassword" class="input-label">Password</label>
                                    <input type="password" id="userPassword" class="input-field" placeholder="******"
                                        required name="password" />
                                    <span class="text-red-600 text-sm"
                                        style="color: red">{{ $errors->first('password') }}</span>
                                </div>

                                <div class="form-group">
                                    <label for="userPassword" class="input-label">Confirm Password</label>
                                    <input type="password" id="userPassword" class="input-field" placeholder="******"
                                        required name="password_confirmation" />
                                    <span class="text-red-600 text-sm"
                                        style="color: red">{{ $errors->first('password_confirmation') }}</span>
                                </div>
                                <fieldset class="checkbox-wrapper">
                                    <input type="checkbox" id="terms" class="checkbox-field" required />
                                    <label class="checkbox-label" for="terms">
                                        I Accept Terms and Condition
                                    </label>
                                </fieldset>

                                <div class="form-group">
                                    <label for="location">Set Your Location</label>
                                    <div id="map"></div>
                                    <input type="hidden" name="latitude" id="latitude">
                                    <input type="hidden" name="longitude" id="longitude">
                                    <input type="hidden" name="address" id="address">
                                </div>

                                <div class="form-navigation">
                                    <button type="submit" class="button-next">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset('frontend/assets/js/plugins/aos-2.3.1.min.js') }}"></script>
    <script>
        let currentMarker;
        const defaultLat = 34.0522; // Default: Los Angeles
        const defaultLng = -118.2437;
        let mapTilerKey = '';

        const map = L.map('map', {
            center: [defaultLat, defaultLng],
            zoom: 13,
            zoomControl: true,
            scrollWheelZoom: false
        });
        $.ajax({
            url: "{{ route('map.api.key') }}",
            method: "GET",
            success: function(response) {
                mapTilerKey = response.key;
                initializeMap();
            },
            error: function() {
                alert("Failed to load Map API key!");
            }
        });

        function initializeMap() {
            L.tileLayer(`https://api.maptiler.com/maps/basic-v2/{z}/{x}/{y}.png?key=${mapTilerKey}`, {
                // attribution: '© MapTiler contributors',
                maxZoom: 18,
                tileSize: 512,
                zoomOffset: -1
            }).addTo(map);
        }


        // Ensure full map rendering
        setTimeout(() => {
            map.invalidateSize();
        }, 500);

        function updateMarker(lat, lng) {
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            if (currentMarker) {
                currentMarker.setLatLng([lat, lng]);
            } else {
                currentMarker = L.marker([lat, lng], {
                        draggable: true
                    }).addTo(map)
                    .bindPopup('You are here!')
                    .openPopup();

                currentMarker.on('dragend', function(e) {
                    let {
                        lat,
                        lng
                    } = e.target.getLatLng();
                    updateMarker(lat, lng);
                });
            }

            fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('address').value = data.display_name || 'Unknown';
                })
                .catch(() => {
                    document.getElementById('address').value = 'Location not found';
                });
        }

        function locateUser() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        map.setView([lat, lng], 13);
                        updateMarker(lat, lng);
                    },
                    () => {
                        alert("Location access denied! Using default location.");
                        updateMarker(defaultLat, defaultLng);
                    }, {
                        enableHighAccuracy: true,
                        timeout: 5000
                    }
                );
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        // Add a "Find Me" button
        const locateButton = L.control({
            position: 'topright'
        });
        locateButton.onAdd = function() {
            const div = L.DomUtil.create('div', 'map-button');
            div.innerHTML = '📍 Find Me';
            div.onclick = locateUser;
            return div;
        };
        locateButton.addTo(map);

        locateUser(); // Auto-locate on load
    </script>
@endpush
