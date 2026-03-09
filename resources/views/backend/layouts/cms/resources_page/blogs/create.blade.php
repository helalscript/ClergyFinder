@extends('backend.app')
@section('title', 'Blogs Section')

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">CMS Blogs Section</h3>
        </div>

        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">

                <div class="mb-4">
                    <h4 class="fs-20 mb-1">CMS Blogs Section</h4>
                    <p class="fs-15">Update Blogs Section details here.</p>
                </div>

                <form action="{{ route('cms.resources_page.blogs.update') }}" method="POST">
                    @csrf
                    <div class="row">
                        <!-- Title Field -->
                        <div class="col-lg-12">
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

                        <!-- Tags Field (JSON for multiple blogs) -->
                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Blog Posts (JSON Format)</label>
                                <div class="form-group position-relative">
                                    <textarea class="form-control text-dark ps-5 @error('tags') is-invalid @enderror"
                                        name="tags" rows="15" placeholder='Enter blog posts in JSON format: [{"title": "Blog Title", "excerpt": "Short excerpt", "content": "Blog content", "author": "Author Name", "date": "2024-01-01", "image": "image-url", "tags": ["tag1", "tag2"]}]'>{{ old('tags', $data->tags ?? '') }}</textarea>
                                </div>
                                <small class="text-muted">Format: [{"title": "Blog Title", "excerpt": "Short excerpt", "content": "Blog content", "author": "Author Name", "date": "2024-01-01", "image": "image-url", "tags": ["tag1", "tag2"]}]</small>
                                @error('tags')
                                    <div id="tags-error" class="text-danger">{{ $message }}</div>
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

