@extends('backend.app')
@section('title', 'State Licensing Page')

@push('styles')
    {{--  Quill v2.0.2 only --}}
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
@endpush

@section('content')

    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">State Licensing Page</h3>

            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="#" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-4-line fs-18 text-primary me-1"></i>
                            <span class="text-secondary fw-medium hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-medium">State Licensing Edit Page</span>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-medium">Edit</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">


                <div class="mb-4">
                    <h4 class="fs-20 mb-1">State Licensing Page</h4>
                    <p class="fs-15">Edit State Licensing Page here.</p>
                </div>

                <form action="{{ route('cms.state_licensing_page.content_section.update', $data->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT') <!-- This is important for update actions -->
                    <div class="row">
                        {{-- star number feild --}}
                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Title<span class="text-danger">*</span></label>
                                <div class="form-group position-relative">
                                    <input type="tesxt"
                                        class="form-control text-dark h-55 @error('title') is-invalid @enderror"
                                        name="title" value="{{ old('title', $data->title ?? '') }}" placeholder="Enter title here">
                                </div>
                                @error('title')
                                    <div id="title-error" class="text-danger">{{ $message }}</div>
                                @enderror
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
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap gap-3">
                                <button type="reset" class="btn btn-danger py-2 px-4 fw-medium fs-16 text-white"
                                    onclick="window.location.href='{{ route('cms.state_licensing_page.content_section.index') }}'">Cancel</button>
                                <button type="submit" class="btn btn-primary py-2 px-4 fw-medium fs-16" onclick="check()"> <i
                                        class="ri-check-line text-white fw-medium"></i> Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
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
                    ['link'], // Media: Link, Image, Video, formula
                    // ['clean']                                   // Remove formatting
                ]
            }
        });


        // // Show Quill version in console (to confirm it's v2.x)
        // console.log("Quill version:", Quill.version);

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
