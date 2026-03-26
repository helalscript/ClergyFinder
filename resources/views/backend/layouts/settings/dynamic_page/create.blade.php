@extends('backend.app')
@section('title', 'Dynamic page')

@push('styles')
{{-- CKEditor CDN --}}
<script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>
<style>
  .ck-editor__editable_inline {
      min-height: 300px;
  }
</style>
@endpush
@section('content')

<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h3 class="mb-0">Dynamic Page</h3>

        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="#" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-4-line fs-18 text-primary me-1"></i>
                        <span class="text-secondary fw-medium hover">Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span class="fw-medium">Dynamic Page</span>
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
                <h4 class="fs-20 mb-1">Dynamic Page</h4>
                <p class="fs-15">Add New Dynamic Page here.</p>
            </div>

            <form action="{{ route('dynamic_page.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group mb-4">
                            <label class="label text-secondary">Page Title</label>
                            <div class="form-group position-relative">
                                <input type="text"
                                    class="form-control text-dark ps-5 h-55 @error('page_title') is-invalid @enderror"
                                    name="page_title" value="{{ old('page_title') }}" required
                                    placeholder="Enter Page Title here">
                                
                            </div>
                            @error('page_title')
                                <div id="page_title-error" class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group mb-4">
                            <label class="label text-secondary">Page Content</label>
                            <div class="form-group position-relative">
                                <textarea name="page_content" class="form-control @error('page_content') is-invalid @enderror" id="page_content" placeholder="Page Content here">{{ old('page_content') }}</textarea>

                            </div>
                            @error('page_content')
                                <div id="page_content-error" class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>


                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap gap-3">
                            <button type="reset" class="btn btn-danger py-2 px-4 fw-medium fs-16 text-white" onclick="window.location.href='{{route('dynamic_page.index')}}'">Cancel</button>
                            <button type="submit" class="btn btn-primary py-2 px-4 fw-medium fs-16"> <i class="ri-check-line text-white fw-medium"></i> Submit</button>
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
  ClassicEditor
      .create(document.querySelector('#page_content'), {
          removePlugins: ['CKFinderUploadAdapter', 'CKFinder', 'EasyImage', 'ImageUpload', 'MediaEmbed'],
          toolbar: ['bold', 'italic', 'heading', '|', 'undo', 'redo']
      })
      .catch(error => {
          console.error(error);
      });
</script>
@endpush
