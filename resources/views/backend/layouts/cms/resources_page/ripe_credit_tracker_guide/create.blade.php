@extends('backend.app')
@section('title', 'Ripe Credit Tracker Guide Section')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
    <style>
        .dropify-wrapper {
            height: 250px;
        }

        .dropify-wrapper .dropify-preview .dropify-render img {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
    {{--  Quill v2.0.2 only --}}
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
@endpush

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">CMS Ripe Credit Tracker Guide Section</h3>
        </div>

        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">

                <div class="mb-4">
                    <h4 class="fs-20 mb-1">CMS Ripe Credit Tracker Guide Section</h4>
                    <p class="fs-15">Update Ripe Credit Tracker Guide Section details here.</p>
                </div>

                <form action="{{ route('cms.resources_page.ripe_credit_tracker_guide.update') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- Title Field -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Section Title<span class="text-danger">*</span></label>
                                <div class="form-group position-relative">
                                    <input type="text"
                                        class="form-control text-dark ps-5 h-55 @error('title') is-invalid @enderror"
                                        name="title" value="{{ old('title', $data->title ?? '') }}" required
                                        placeholder="Enter Section Title here">
                                </div>
                                @error('title')
                                    <div id="title-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Sub Title Field -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Title</label>
                                <div class="form-group position-relative">
                                    <input type="text"
                                        class="form-control text-dark ps-5 h-55 @error('sub_title') is-invalid @enderror"
                                        name="sub_title" value="{{ old('sub_title', $data->sub_title ?? '') }}"
                                        placeholder="Enter Title here">
                                </div>
                                @error('sub_title')
                                    <div id="sub_title-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Description Field -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Text</label>
                                <div id="editor" style="height: 200px;"></div>

                                <input type="hidden" name="description" id="description">

                                @error('description')
                                    <div id="description-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Image Field -->
                        <div class="col-lg-6">
                            <div class="form-group ">
                                <label class="label text-secondary mb-1">Image<span class="text-danger">*</span></label>
                                <input class="dropify form-control @error('image') is-invalid @enderror" type="file"
                                    name="image" accept="image/*"
                                    data-default-file="{{ isset($data) && $data->image ? asset($data->image) : '' }}">
                                @error('image')
                                    <div id="image-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap gap-3">
                                <button type="submit" class="btn btn-primary py-2 px-4 fw-medium fs-16" onclick="check()">
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
        })
    </script>
    <script>
        // Initialize Quill v2
        var quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }], // Lists
                ]
            }
        });

        // Load existing content (old input or DB value)
        @if (old('description'))
            quill.root.innerHTML = {!! json_encode(old('description')) !!};
        @else
            quill.root.innerHTML = {!! json_encode($data?->description) !!};
        @endif

        // Update hidden input before form submission
        function check() {
            document.getElementById('description').value = quill.root.innerHTML;
        }
    </script>
@endpush
