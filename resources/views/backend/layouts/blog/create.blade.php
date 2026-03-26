@extends('backend.app')
@section('title', 'Blog page')

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
    {{--  Quill v2.0.2 only --}}
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
    <style>
        .custom-orange-btn {
            background-color: #E25C2C;
            color: white;
            border: none;
            padding: 6px 12px;
            font-size: 14px;
            border-radius: 4px;
            cursor: pointer;
        }

        .custom-orange-btn:hover {
            background-color: #cc4e23;
        }
    </style>
@endpush
@section('content')

    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">Blog Page</h3>

            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="#" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-4-line fs-18 text-primary me-1"></i>
                            <span class="text-secondary fw-medium hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-medium">Blog Page</span>
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
                    <h4 class="fs-20 mb-1">Blog Create Page</h4>
                    <p class="fs-15">Add New Blog Create here.</p>
                </div>
                {{-- from start --}}
                <form action="{{ route('blogs.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="row">
                        {{-- star number feild --}}
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Title<span class="text-danger">*</span></label>
                                <div class="form-group position-relative">
                                    <input type="tesxt"
                                        class="form-control text-dark h-55 @error('title') is-invalid @enderror"
                                        name="title" value="{{ old('title') }}" placeholder="Enter title here">
                                </div>
                                @error('title')
                                    <div id="title-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Author Name<span class="text-danger">*</span></label>
                                <div class="form-group position-relative">
                                    <input type="tesxt"
                                        class="form-control text-dark h-55 @error('author') is-invalid @enderror"
                                        name="author" value="{{ old('author') }}" placeholder="Enter author here">
                                </div>
                                @error('author')
                                    <div id="author-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group ">
                                <label class="label text-secondary mb-1">Thumbnails<span
                                        class="text-danger">*</span></label>
                                <input class="dropify form-control @error('thumbnail') is-invalid @enderror" type="file"
                                    name="thumbnail" accept="thumbnail/*"
                                    data-default-file="{{ isset($data) && $data->thumbnail ? asset($data->thumbnail) : '' }}">
                                @error('thumbnail')
                                    <div id="thumbnail-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Tag Field -->
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Tags</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Enter tag text" name="tag"
                                        aria-label="Tags" aria-describedby="basic-addon2" title="Must be a valid tag"
                                        value="{{ old('tag', $data->tag ?? '') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button" id="button-addon2"
                                            onclick="addTags()">Add</button>
                                    </div>
                                </div>
                                <div id="tag-container" class="row">
                                    @if (isset($data) && $data->tags)
                                        @foreach (json_decode($data->tags) as $tag)
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center mb-3">
                                                    <input type="text" class="form-control" name="tags[]"
                                                        value="{{ $tag }}" readonly>
                                                    <button type="button" class="btn btn-danger ml-2"
                                                        onclick="removeTag(this)"
                                                        style="border-radius: 50%; background-color: red;">
                                                        <i class="ri-close-fill"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- Description Field -->
                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Text</label>
                                <div id="editor" style="height: 300px;"></div>

                                <input type="hidden" name="description" id="description">

                                @error('description')
                                    <div id="description-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap gap-3">
                                <button type="reset" class="btn btn-danger py-2 px-4 fw-medium fs-16 text-white"
                                    onclick="window.location.href='{{ route('blogs.index') }}'">Cancel</button>
                                <button type="submit" class="btn btn-primary py-2 px-4 fw-medium fs-16"
                                    onclick="check()"> <i class="ri-check-line text-white fw-medium"></i> Submit</button>
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
                        'font': []
                    }], // Font family
                    [{
                        'header': [1, 2, 3, 4, 5, 6, false]
                    }], // Headers
                    ['bold', 'italic', 'underline', 'strike'], // Basic formatting
                    [{
                        'color': []
                    }, {
                        'background': []
                    }], // Text and background colors
                    [{
                        'script': 'sub'
                    }, {
                        'script': 'super'
                    }], // Subscript/Superscript
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }], // Lists
                    [{
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }], // Indent
                    [{
                        'align': []
                    }], // Alignment
                    ['blockquote', 'code-block'], // Quote / Code
                    ['link', 'image'], // Media: Link, Image, Video, formula
                    [{
                        'size': ['small', false, 'large', 'huge']
                    }], // Font size
                    // ['clean']                                   // Remove formatting
                ]
            }
        });


        // Update hidden input before form submission
        function check() {
            document.getElementById('description').value = quill.root.innerHTML;
        }
    </script>
    {{-- Add tags script --}}
    <script>
        function addTags() {
            console.log('Adding tag');
            let tag = document.querySelector('input[name="tag"]').value;
            if (tag.trim() === '') {
                flasher.warning('Please enter a valid tag.');
                return;
            }

            if (tag) {
                let tagContainer = document.getElementById('tag-container');
                tagContainer.insertAdjacentHTML('beforeend', `<div class="col-md-6">
                                    <div class="d-flex align-items-center mb-3 ">
                                    <input type="text" class="form-control" name="tags[]" value="${tag}" readonly>
                                    <button type="button" class="btn btn-danger ml-2" onclick="removeTag(this)" style="border-radius: 50%; background-color: red;"><i class="ri-close-fill"></i></button>
                                </div>
                                </div>`);
                document.querySelector('input[name="tag"]').value = '';
            }
        }

        function removeTag(element) {
            element.parentElement.remove();
        }
    </script>
@endpush
