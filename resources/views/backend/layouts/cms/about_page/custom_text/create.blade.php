@extends('backend.app')
@section('title', 'Custom Text Section')

@push('styles')
    {{--  Quill v2.0.2 only --}}
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
@endpush

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">CMS Custom Text Section</h3>
        </div>

        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <div class="mb-4">
                    <h4 class="fs-20 mb-1">CMS Custom Text Section</h4>
                    <p class="fs-15">Update Custom Text Section details here.</p>
                </div>

                <form action="{{ route('cms.about_page.custom_text_section.update') }}" method="POST">
                    @csrf
                    <div class="row">
                        <!-- Description Field -->
                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Text</label>
                                <div id="editor" style="height: 600px;"></div>

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
<script>
    // Initialize Quill v2
    var quill = new Quill('#editor', {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ 'font': [] }],                     // Font family
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],  // Headers
            ['bold', 'italic', 'underline', 'strike'],  // Basic formatting
            [{ 'color': [] }, { 'background': [] }],    // Text and background colors
            [{ 'script': 'sub'}, { 'script': 'super' }], // Subscript/Superscript
            [{ 'list': 'ordered'}, { 'list': 'bullet' }], // Lists
            [{ 'indent': '-1'}, { 'indent': '+1' }],     // Indent
            [{ 'align': [] }],                          // Alignment
            ['blockquote', 'code-block'],               // Quote / Code
            ['link', 'image'],     // Media: Link, Image, Video, formula
            [{ 'size': ['small', false, 'large', 'huge'] }], // Font size
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
