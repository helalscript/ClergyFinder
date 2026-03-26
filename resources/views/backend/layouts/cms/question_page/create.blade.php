@extends('backend.app')
@section('title', 'Add ' . ucfirst($userType->value) . ' Question')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
    <style>
        .dropify-wrapper { height: 200px; }
        .dropify-wrapper .dropify-preview .dropify-render img { display: block; margin-left: auto; margin-right: auto; }
    </style>
@endpush

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">Add {{ ucfirst($userType->value) }} Question</h3>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item"><a href="#" class="d-flex align-items-center text-decoration-none"><i class="ri-home-4-line fs-18 text-primary me-1"></i><span class="text-secondary fw-medium hover">Dashboard</span></a></li>
                    <li class="breadcrumb-item active"><span class="fw-medium">Question Page</span></li>
                    <li class="breadcrumb-item active"><span class="fw-medium">{{ ucfirst($userType->value) }} - Add</span></li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <form action="{{ route('cms.question_page.' . $userType->value . '.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Question</label>
                                <textarea class="form-control text-dark h-55 @error('question') is-invalid @enderror" name="question" rows="3" placeholder="Enter question">{{ old('question') }}</textarea>
                                @error('question')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <label class="label text-secondary mb-1">Image</label>
                                <input class="dropify form-control" type="file" name="image" accept="image/*" data-default-file="">
                                @error('image')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Answer Type</label>
                                <select class="form-control text-dark h-55 @error('answer_type') is-invalid @enderror" name="answer_type" id="answer_type">
                                    <option value="">Select</option>
                                    @foreach($answerTypes as $type)
                                        <option value="{{ $type->value }}" {{ old('answer_type') == $type->value ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $type->value)) }}</option>
                                    @endforeach
                                </select>
                                @error('answer_type')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Sort Order</label>
                                <input type="number" class="form-control text-dark h-55" name="sort_order" value="{{ old('sort_order', 0) }}" placeholder="0">
                            </div>
                        </div>
                        <div class="col-lg-12" id="options_wrapper" style="display:none;">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Options</label>
                                <div id="options_container">
                                    @php $oldOpts = old('options'); @endphp
                                    @if(!empty($oldOpts))
                                        @foreach($oldOpts as $idx => $opt)
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" name="options[]" placeholder="Option {{ $idx + 1 }}" value="{{ $opt }}">
                                            <button type="button" class="btn btn-outline-danger btn-remove-option"><i class="ri-close-line"></i></button>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" name="options[]" placeholder="Option 1">
                                            <button type="button" class="btn btn-outline-danger btn-remove-option"><i class="ri-close-line"></i></button>
                                        </div>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add_option"><i class="ri-add-line"></i> Add Option</button>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Answer (Admin Reference)</label>
                                <textarea class="form-control text-dark @error('answer') is-invalid @enderror" name="answer" rows="3" placeholder="Correct/reference answer">{{ old('answer') }}</textarea>
                                @error('answer')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <button type="button" class="btn btn-danger py-2 px-4 fw-medium fs-16 text-white" onclick="window.location.href='{{ route('cms.question_page.' . $userType->value . '.index') }}'">Cancel</button>
                            <button type="submit" class="btn btn-primary py-2 px-4 fw-medium fs-16"><i class="ri-check-line text-white fw-medium"></i> Submit</button>
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
            function toggleOptions() {
                var t = $('#answer_type').val();
                if (t === 'single_choice' || t === 'multiple_choice') $('#options_wrapper').show();
                else $('#options_wrapper').hide();
            }
            $('#answer_type').on('change', toggleOptions);
            toggleOptions();
            var optCount = 1;
            $('#add_option').on('click', function() {
                optCount++;
                $('#options_container').append('<div class="input-group mb-2"><input type="text" class="form-control" name="options[]" placeholder="Option '+optCount+'"><button type="button" class="btn btn-outline-danger btn-remove-option"><i class="ri-close-line"></i></button></div>');
            });
            $(document).on('click', '.btn-remove-option', function() {
                if ($('#options_container .input-group').length > 1) $(this).closest('.input-group').remove();
            });
        });
    </script>
@endpush
