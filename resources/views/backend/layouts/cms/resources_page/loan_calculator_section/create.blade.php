@extends('backend.app')
@section('title', 'Loan Calculator Section')
@push('styles')
    {{--  Quill v2.0.2 only --}}
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
@endpush
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h3 class="mb-0">Loan Calculator Section</h3>

        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="#" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-4-line fs-18 text-primary me-1"></i>
                        <span class="text-secondary fw-medium hover">Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span class="fw-medium">Loan Calculator Section</span>
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
                <h4 class="fs-20 mb-1">Loan Calculator Create Page</h4>
                <p class="fs-15">Add new loan calculator create here.</p>
            </div>
            {{-- from start --}}
            <form action="{{ route('cms.resources_page.loan_calculator_section.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="row">
                    {{-- star number feild --}}
                    <div class="col-lg-12">
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
                    {{-- description feild --}}
                    <div class="row">
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

                </div>
                <div class="row mt-3">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap gap-3">
                            <button type="reset" class="btn btn-danger py-2 px-4 fw-medium fs-16 text-white"
                                onclick="window.location.href='{{ route('cms.resources_page.loan_calculator_section.index') }}'">Cancel</button>
                            <button type="submit" class="btn btn-primary py-2 px-4 fw-medium fs-16" onclick="check()"> <i
                                    class="ri-check-line text-white fw-medium"></i> Submit</button>
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
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }], // Lists
            ]
        }
    });




    // Update hidden input before form submission
    function check() {
        document.getElementById('description').value = quill.root.innerHTML;
    }
</script>
@endpush
