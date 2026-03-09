@extends('backend.app')
@section('title', 'Review Section - Add')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
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

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">Review Section</h3>

            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="#" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-4-line fs-18 text-primary me-1"></i>
                            <span class="text-secondary fw-medium hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-medium">Home Page</span>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-medium">Review Section</span>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-medium">Add</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <div class="mb-4">
                    <h4 class="fs-20 mb-1">Add Review Item</h4>
                    <p class="fs-15">Add new review item (title, sub title, description, image, stars).</p>
                </div>

                <form action="{{ route('cms.home_page.review_section.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="row">

                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Title <span class="text-danger">*</span></label>
                                <div class="form-group position-relative">
                                    <input type="text"
                                        class="form-control text-dark h-55 @error('title') is-invalid @enderror"
                                        name="title" value="{{ old('title') }}" placeholder="Enter title here">
                                </div>
                                @error('title')
                                    <div id="title-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Sub Title</label>
                                <div class="form-group position-relative">
                                    <input type="text"
                                        class="form-control text-dark h-55 @error('sub_title') is-invalid @enderror"
                                        name="sub_title" value="{{ old('sub_title') }}" placeholder="Enter sub title here">
                                </div>
                                @error('sub_title')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Description</label>
                                <div class="form-group position-relative">
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description"
                                        placeholder="Enter description here" rows="5">{{ old('description') }}</textarea>
                                </div>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Stars (1-5) <span class="text-danger">*</span></label>
                                <div class="form-group position-relative">
                                    <input type="number" min="1" max="5" step="1"
                                        class="form-control text-dark h-55 @error('stars') is-invalid @enderror"
                                        name="stars" value="{{ old('stars', 5) }}" placeholder="Enter stars (1-5)">
                                </div>
                                @error('stars')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary mb-1">Image <span class="text-danger">*</span></label>
                                <input class="dropify form-control @error('image') is-invalid @enderror" type="file"
                                    name="image" accept="image/*" data-default-file="">
                                @error('image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap gap-3">
                                <button type="reset" class="btn btn-danger py-2 px-4 fw-medium fs-16 text-white"
                                    onclick="window.location.href='{{ route('cms.home_page.review_section.index') }}'">Cancel</button>
                                <button type="submit" class="btn btn-primary py-2 px-4 fw-medium fs-16">
                                    <i class="ri-check-line text-white fw-medium"></i> Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();
        });
    </script>
@endpush
