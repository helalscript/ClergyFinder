@extends('backend.layouts.settings.layout')
@section('form_title', 'System')
@section('form_description', 'System')
@section('form_content')
    @push('styles')
        <style>
            .dropify-wrapper {
                height: 200px;
            }

            .dropify-wrapper .dropify-preview .dropify-render img {
                display: block;
                margin-left: auto;
                margin-right: auto;
            }
        </style>
    @endpush
    <form action="{{ route('system_settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label class="label text-secondary">Title</label>
                    <div class="form-group position-relative">
                        <input type="text" class="form-control text-dark ps-5 h-55 @error('title') is-invalid @enderror"
                            name="title" value="{{ old('title', $settings->title ?? '') }}" required
                            placeholder="Enter Title here">
                    </div>
                    @error('title')
                        <div id="title-error" class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label class="label text-secondary">System Name</label>
                    <div class="form-group position-relative">
                        <input type="text"
                            class="form-control text-dark ps-5 h-55 @error('system_name') is-invalid @enderror"
                            name="system_name" value="{{ old('system_name', $settings->system_name ?? '') }}" required
                            placeholder="Enter system name here">
                    </div>
                    @error('system_name')
                        <div id="system_name-error" class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label class="label text-secondary">Contact Number</label>
                    <div class="form-group position-relative">
                        <input type="text"
                            class="form-control text-dark ps-5 h-55 @error('contact_number') is-invalid @enderror"
                            name="contact_number" value="{{ old('contact_number', $settings->contact_number ?? '') }}"
                            placeholder="Enter contact number here">
                    </div>
                    @error('contact_number')
                        <div id="contact_number-error" class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label class="label text-secondary">Email</label>
                    <div class="form-group position-relative">
                        <input type="email" class="form-control text-dark ps-5 h-55 @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email', $settings->email ?? '') }}" required
                            placeholder="Enter email here">
                    </div>
                    @error('email')
                        <div id="email-error" class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label class="label text-secondary">Copyright Text</label>
                    <div class="form-group position-relative">
                        <input type="text"
                            class="form-control text-dark ps-5 h-55 @error('copyright_text') is-invalid @enderror"
                            name="copyright_text" value="{{ old('copyright_text', $settings->copyright_text ?? '') }}"
                            required placeholder="Enter copyright text here">
                    </div>
                    @error('copyright_text')
                        <div id="copyright_text-error" class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label class="label text-secondary">Company Open Hour</label>
                    <div class="form-group position-relative">
                        <input type="text"
                            class="form-control text-dark ps-5 h-55 @error('company_open_hour') is-invalid @enderror"
                            name="company_open_hour"
                            value="{{ old('company_open_hour', $settings->company_open_hour ?? '') }}"
                            placeholder="Enter company open hours here">
                    </div>
                    @error('company_open_hour')
                        <div id="company_open_hour-error" class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label class="label text-secondary">Address</label>
                    <div class="form-group position-relative">
                        <input type="text"
                            class="form-control text-dark ps-5 h-55 @error('address') is-invalid @enderror" name="address"
                            value="{{ old('address', $settings->address ?? '') }}" required
                            placeholder="Enter address here">
                    </div>
                    @error('address')
                        <div id="address-error" class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label class="label text-secondary">Description</label>
                    <div class="form-group position-relative">
                        <textarea class="form-control text-dark ps-5 h-55 @error('description') is-invalid @enderror" name="description"
                            required placeholder="Enter description here">{{ old('description', $settings->description ?? '') }}</textarea>
                    </div>
                    @error('description')
                        <div id="description-error" class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>


            <div class="col-lg-6">
                <div class="form-group ">
                    <label class="label text-secondary mb-1">Logo</label>
                    <input class="dropify form-control @error('logo') is-invalid @enderror" type="file" name="logo"
                        data-default-file="{{ asset($settings->logo ?? 'backend/admin/assets/logo.png') }}">
                    @error('logo')
                        <div id="logo-error" class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>


            <div class="col-lg-6">
                <div class="form-group ">
                    <label class="label text-secondary mb-1">Favicon</label>
                    <input class="dropify form-control @error('favicon') is-invalid @enderror" type="file"
                        name="favicon"
                        data-default-file="{{ asset($settings->favicon ?? 'backend/admin/assets/logo.png') }}">
                    @error('favicon')
                        <div id="favicon-error" class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap gap-3">
                    {{-- <button type="submit" class="btn btn-danger py-2 px-4 fw-medium fs-16 text-white">Cancel</button> --}}
                    <button type="submit" class="btn btn-primary py-2 px-4 fw-medium fs-16"> <i
                            class="ri-check-line text-white fw-medium"></i> Update</button>
                </div>
            </div>
        </div>
    </form>
@endsection
