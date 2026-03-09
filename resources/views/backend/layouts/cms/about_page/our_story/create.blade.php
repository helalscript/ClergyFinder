@extends('backend.app')
@section('title', 'Our Story Section')

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">CMS Our Story Section</h3>
        </div>

        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">

                <div class="mb-4">
                    <h4 class="fs-20 mb-1">CMS Our Story Section</h4>
                    <p class="fs-15">Update Our Story Section details here.</p>
                </div>

                <form action="{{ route('cms.about_page.our_story.update') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- Title Field -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Title</label>
                                <div class="form-group position-relative">
                                    <input type="text"
                                        class="form-control text-dark ps-5 h-55 @error('title') is-invalid @enderror"
                                        name="title" value="{{ old('title', $data->title ?? '') }}"
                                        placeholder="Enter Title here">
                                </div>
                                @error('title')
                                    <div id="title-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Sub Title Field -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Sub Title</label>
                                <div class="form-group position-relative">
                                    <input type="text"
                                        class="form-control text-dark ps-5 h-55 @error('sub_title') is-invalid @enderror"
                                        name="sub_title" value="{{ old('sub_title', $data->sub_title ?? '') }}"
                                        placeholder="Enter Sub Title here">
                                </div>
                                @error('sub_title')
                                    <div id="sub_title-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Description Field -->
                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Description</label>
                                <div class="form-group position-relative">
                                    <textarea class="form-control text-dark ps-5 @error('description') is-invalid @enderror"
                                        name="description" rows="6" placeholder="Enter Description here">{{ old('description', $data->description ?? '') }}</textarea>
                                </div>
                                @error('description')
                                    <div id="description-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Sub Description Field -->
                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Sub Description</label>
                                <div class="form-group position-relative">
                                    <textarea class="form-control text-dark ps-5 @error('sub_description') is-invalid @enderror"
                                        name="sub_description" rows="4" placeholder="Enter Sub Description here">{{ old('sub_description', $data->sub_description ?? '') }}</textarea>
                                </div>
                                @error('sub_description')
                                    <div id="sub_description-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Button Text Field -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Button Text</label>
                                <div class="form-group position-relative">
                                    <input type="text"
                                        class="form-control text-dark ps-5 h-55 @error('button_text') is-invalid @enderror"
                                        name="button_text" value="{{ old('button_text', $data->button_text ?? '') }}"
                                        placeholder="Enter Button Text here">
                                </div>
                                @error('button_text')
                                    <div id="button_text-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Link URL Field -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Link URL</label>
                                <div class="form-group position-relative">
                                    <input type="url"
                                        class="form-control text-dark ps-5 h-55 @error('link_url') is-invalid @enderror"
                                        name="link_url" value="{{ old('link_url', $data->link_url ?? '') }}"
                                        placeholder="Enter Link URL here">
                                </div>
                                @error('link_url')
                                    <div id="link_url-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Image Field -->
                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Image</label>
                                <div class="form-group position-relative">
                                    <input type="file" class="form-control text-dark ps-5 h-55 @error('image') is-invalid @enderror"
                                        name="image" accept="image/*">
                                </div>
                                @if(isset($data) && $data->image)
                                    <div class="mt-2">
                                        <img src="{{ asset($data->image) }}" alt="Current Image" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                @endif
                                @error('image')
                                    <div id="image-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap gap-3">
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

