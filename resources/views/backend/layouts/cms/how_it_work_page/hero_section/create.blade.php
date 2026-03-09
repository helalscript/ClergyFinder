@extends('backend.app')
@section('title', 'Hero Section - How It Work Page')
@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">Hero Section</h3>
        </div>
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <div class="mb-4">
                    <h4 class="fs-20 mb-1">Hero Section</h4>
                    <p class="fs-15">Title, Description, Button Text</p>
                </div>
                <form action="{{ route('cms.how_it_work_page.hero_section.update') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Title</label>
                                <input type="text" class="form-control text-dark h-55 @error('title') is-invalid @enderror"
                                    name="title" value="{{ old('title', $data->title ?? '') }}" placeholder="Enter Title">
                                @error('title')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Description</label>
                                <textarea class="form-control text-dark @error('description') is-invalid @enderror" name="description" rows="4" placeholder="Enter Description">{{ old('description', $data->description ?? '') }}</textarea>
                                @error('description')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Button Text</label>
                                <input type="text" class="form-control text-dark h-55 @error('button_text') is-invalid @enderror"
                                    name="button_text" value="{{ old('button_text', $data->button_text ?? '') }}" placeholder="Enter Button Text">
                                @error('button_text')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary py-2 px-4 fw-medium fs-16"><i class="ri-check-line text-white fw-medium"></i> Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
