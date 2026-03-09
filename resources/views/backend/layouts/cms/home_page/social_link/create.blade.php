@extends('backend.app')
@section('title', 'Social Link')

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
            <h3 class="mb-0">CMS Social Link</h3>
        </div>

        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">

                <div class="mb-4">
                    <h4 class="fs-20 mb-1">CMS Social Link</h4>
                    <p class="fs-15">Update Social Link and site details here.</p>
                </div>

                <form action="{{ route('cms.home_page.social_link.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- Title Field -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Title<span class="text-danger">*</span></label>
                                <div class="form-group position-relative">
                                    <input type="text"
                                        class="form-control text-dark ps-5 h-55 @error('title') is-invalid @enderror"
                                        name="title" value="{{ old('title') }}" required placeholder="facebook">
                                </div>
                                @error('title')
                                    <div id="title-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <!-- 2nd Subtitle Field -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Profile Link<span
                                        class="text-danger">*</span></label>
                                <div class="form-group position-relative">
                                    <input type="url"
                                        class="form-control text-dark ps-5 h-55 @error('link_url') is-invalid @enderror"
                                        name="link_url" value="{{ old('link_url') }}"
                                        placeholder="https://github.com/helalscript">
                                </div>
                                @error('link_url')
                                    <div id="link_url-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-12">
                        <div class="form-group ">
                            <label class="label text-secondary mb-1">Social Platform Image<span class="text-danger">*</span></label>
                            <input class="dropify form-control @error('image') is-invalid @enderror" type="file"
                                name="image">
                            @error('image')
                                <div id="image-error" class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap gap-3">
                        {{-- <button type="submit" class="btn btn-danger py-2 px-4 fw-medium fs-16 text-white">Cancel</button> --}}
                        <button type="submit" class="btn btn-primary py-2 px-4 fw-medium fs-16"> <i
                                class="ri-check-line text-white fw-medium"></i> Submit</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>



@endsection

@push('scripts')
    <script src="{{ asset('frontend/assets/js/plugins/jquery-3.7.1.min.js') }}"></script>
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();
        })
    </script>
@endpush
