@extends('backend.app')
@section('title', 'Work Progress Section - Edit')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
    <style>
        .dropify-wrapper { height: 200px; }
        .dropify-wrapper .dropify-preview .dropify-render img { display: block; margin-left: auto; margin-right: auto; }
    </style>
@endpush

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">Work Progress Section</h3>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item"><a href="#" class="d-flex align-items-center text-decoration-none"><i class="ri-home-4-line fs-18 text-primary me-1"></i><span class="text-secondary fw-medium hover">Dashboard</span></a></li>
                    <li class="breadcrumb-item active"><span class="fw-medium">How It Work Page</span></li>
                    <li class="breadcrumb-item active"><span class="fw-medium">Work Progress Section</span></li>
                    <li class="breadcrumb-item active"><span class="fw-medium">Edit</span></li>
                </ol>
            </nav>
        </div>
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <div class="mb-4">
                    <h4 class="fs-20 mb-1">Edit Work Progress Item</h4>
                    <p class="fs-15">Update title, description and image.</p>
                </div>
                <form action="{{ route('cms.how_it_work_page.work_progress_section.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Title</label>
                                <input type="text" class="form-control text-dark h-55 @error('title') is-invalid @enderror"
                                    name="title" value="{{ old('title', $data->title ?? '') }}" placeholder="Enter title">
                                @error('title')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" placeholder="Enter description">{{ old('description', $data->description ?? '') }}</textarea>
                                @error('description')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <label class="label text-secondary mb-1">Image</label>
                                <input class="dropify form-control @error('image') is-invalid @enderror" type="file" name="image" accept="image/*"
                                    data-default-file="{{ isset($data) && $data->image ? asset($data->image) : '' }}">
                                @error('image')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <button type="reset" class="btn btn-danger py-2 px-4 fw-medium fs-16 text-white" onclick="window.location.href='{{ route('cms.how_it_work_page.work_progress_section.index') }}'">Cancel</button>
                            <button type="submit" class="btn btn-primary py-2 px-4 fw-medium fs-16"><i class="ri-check-line text-white fw-medium"></i> Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
    <script>$(document).ready(function() { $('.dropify').dropify(); });</script>
@endpush
