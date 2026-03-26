@extends('backend.app')
@section('title', 'Edit Clergy')

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">Edit Clergy User</h3>
        </div>

        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('user_management.clergy.update', $data->id) }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-6">
                            <label class="label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $data->name) }}"
                                   class="form-control @error('name') is-invalid @enderror" required
                                   placeholder="Enter full name">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        {{-- <div class="col-md-6 mb-6">
                            <label class="label">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name', $data->first_name) }}"
                                   class="form-control @error('first_name') is-invalid @enderror">
                            @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-6">
                            <label class="label">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name', $data->last_name) }}"
                                   class="form-control @error('last_name') is-invalid @enderror">
                            @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div> --}}
                        <div class="col-md-6 mb-6">
                            <label class="label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $data->email) }}"
                                   class="form-control @error('email') is-invalid @enderror" required
                                   placeholder="Enter email address">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="label">Phone <small class="text-muted">(Optional)</small></label>
                            <input type="text" name="phone" value="{{ old('phone', $data->phone) }}"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   placeholder="Enter phone number">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="label">Gender <small class="text-muted">(Optional)</small></label>
                            <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                <option value="">Select gender</option>
                                <option value="male" {{ old('gender', $data->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $data->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="others" {{ old('gender', $data->gender) == 'others' ? 'selected' : '' }}>Others</option>
                            </select>
                            @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="label">Avatar <small class="text-muted">(Optional)</small></label>
                            <input type="file" name="avatar"
                                   class="form-control @error('avatar') is-invalid @enderror">
                            @if ($data->avatar)
                                <img src="{{ asset($data->avatar) }}" alt="avatar" class="mt-2" style="height: 60px;">
                            @endif
                            @error('avatar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="label">Password <small class="text-muted">(Optional, leave blank to keep current)</small></label>
                            <input type="password" name="password"
                                   placeholder="Enter new password"
                                   class="form-control @error('password') is-invalid @enderror">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <hr>
                    <h5 class="mb-3">Clergy Details</h5>

                    @php
                        $clergy = $data->clergy;
                    @endphp

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="label">Religion <small class="text-muted">(Optional)</small></label>
                            <input type="text" name="religion" value="{{ old('religion', $clergy->religion ?? '') }}"
                                   class="form-control @error('religion') is-invalid @enderror"
                                   placeholder="e.g. Christianity, Islam, Hinduism">
                            @error('religion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="label">Preferred Languages <small class="text-muted">(Optional, comma separated)</small></label>
                            <input type="text" name="preferred_language"
                                   value="{{ old('preferred_language', isset($clergy->preferred_language) ? implode(',', (array) $clergy->preferred_language) : '') }}"
                                   class="form-control @error('preferred_language') is-invalid @enderror"
                                   placeholder="e.g. English, Spanish">
                            @error('preferred_language')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="label">Specialities <small class="text-muted">(Optional, comma separated)</small></label>
                            <input type="text" name="specialities"
                                   value="{{ old('specialities', isset($clergy->specialities) ? implode(',', (array) $clergy->specialities) : '') }}"
                                   class="form-control @error('specialities') is-invalid @enderror"
                                   placeholder="e.g. Marriage counseling, Anxiety">
                            @error('specialities')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="label">Address <small class="text-muted">(Optional)</small></label>
                            <input type="text" name="address" value="{{ old('address', $clergy->address ?? '') }}"
                                   class="form-control @error('address') is-invalid @enderror"
                                   placeholder="Street address">
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="label">City <small class="text-muted">(Optional)</small></label>
                            <input type="text" name="city" value="{{ old('city', $clergy->city ?? '') }}"
                                   class="form-control @error('city') is-invalid @enderror"
                                   placeholder="City">
                            @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="label">State <small class="text-muted">(Optional)</small></label>
                            <input type="text" name="state" value="{{ old('state', $clergy->state ?? '') }}"
                                   class="form-control @error('state') is-invalid @enderror"
                                   placeholder="State">
                            @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="label">ZIP Code <small class="text-muted">(Optional)</small></label>
                            <input type="text" name="zip_code" value="{{ old('zip_code', $clergy->zip_code ?? '') }}"
                                   class="form-control @error('zip_code') is-invalid @enderror"
                                   placeholder="ZIP / Postal code">
                            @error('zip_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="label">Country <small class="text-muted">(Optional)</small></label>
                            <input type="text" name="country" value="{{ old('country', $clergy->country ?? '') }}"
                                   class="form-control @error('country') is-invalid @enderror"
                                   placeholder="Country">
                            @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="label">Latitude <small class="text-muted">(Optional)</small></label>
                            <input type="text" name="latitude" value="{{ old('latitude', $clergy->latitude ?? '') }}"
                                   class="form-control @error('latitude') is-invalid @enderror"
                                   placeholder="e.g. 23.8103">
                            @error('latitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="label">Longitude <small class="text-muted">(Optional)</small></label>
                            <input type="text" name="longitude" value="{{ old('longitude', $clergy->longitude ?? '') }}"
                                   class="form-control @error('longitude') is-invalid @enderror"
                                   placeholder="e.g. 90.4125">
                            @error('longitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="label">Available At <small class="text-muted">(Optional)</small></label>
                            <input type="text" name="available_at"
                                   value="{{ old('available_at', $clergy->available_at ?? '') }}"
                                   class="form-control @error('available_at') is-invalid @enderror"
                                   placeholder="e.g. Mon-Fri 10AM - 6PM">
                            @error('available_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="label">Bio <small class="text-muted">(Optional)</small></label>
                            <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" rows="3">{{ old('bio', $clergy->bio ?? '') }}</textarea>
                            @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="is_verified" id="is_verified"
                                       value="1" {{ old('is_verified', $clergy->is_verified ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_verified">Verified <small class="text-muted">(Optional)</small></label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="is_live_session" id="is_live_session"
                                       value="1" {{ old('is_live_session', $clergy->is_live_session ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_live_session">Live Session <small class="text-muted">(Optional)</small></label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="is_prayer_request" id="is_prayer_request"
                                       value="1" {{ old('is_prayer_request', $clergy->is_prayer_request ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_prayer_request">Prayer Request <small class="text-muted">(Optional)</small></label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button class="btn btn-primary">Update</button>
                        <a href="{{ route('user_management.clergy.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

