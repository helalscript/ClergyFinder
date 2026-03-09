@extends('backend.app')
@section('title', 'Create Candidate Page')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
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
            <h3 class="mb-0">Create Candidate Page</h3>

            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="#" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-4-line fs-18 text-primary me-1"></i>
                            <span class="text-secondary fw-medium hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-medium">Candidate Create Page</span>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-medium">Create</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">

                <div class="mb-4">
                    <h4 class="fs-20 mb-1">CANDIDATE OVERVIEW</h4>
                    {{-- <p class="fs-15">Add new Candidate details here.</p> --}}
                </div>
                {{-- Form to create a new candidate --}}
                <form action="{{ route('candidates.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- Name Field -->
                        <div class="col-lg-6 mb-4">
                            <label class="label text-secondary">Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                required placeholder="Enter Name" value="{{ old('name') }}">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Ref No Field -->
                        <div class="col-lg-6 mb-4">
                            <label class="label text-secondary">Ref No</label>
                            <input type="text" class="form-control @error('refer_number') is-invalid @enderror" name="refer_number"
                                placeholder="Enter Ref No" value="{{ old('refer_number') }}">
                            @error('refer_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Current Job Title Field -->
                        <div class="col-lg-6 mb-4">
                            <label class="label text-secondary">Current Job Title</label>
                            <input type="text" class="form-control @error('current_job') is-invalid @enderror"
                                name="current_job" placeholder="Enter Job Title" value="{{ old('current_job') }}">
                            @error('current_job')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Experience Field -->
                        <div class="col-lg-6 mb-4">
                            <label class="label text-secondary">Experience</label>
                            <select class="form-control @error('experience_year') is-invalid @enderror" name="experience_year">
                                <option value="">Select Experience</option>
                                <option value="1 Year" {{ old('experience_year') == '1 Year' ? 'selected' : '' }}>1 Year</option>
                                <option value="2 Years" {{ old('experience_year') == '2 Years' ? 'selected' : '' }}>2 Years
                                </option>
                                <option value="5 Years" {{ old('experience_year') == '5 Years' ? 'selected' : '' }}>5 Years
                                </option>
                                <option value="10+ Years" {{ old('experience_year') == '10+ Years' ? 'selected' : '' }}>10+
                                    Years</option>
                            </select>
                            @error('experience_year')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Qualifications Field -->
                        <div class="col-lg-6 mb-4">
                            <label class="label text-secondary">Qualifications</label>
                            <input type="text" class="form-control @error('qualification') is-invalid @enderror"
                                name="qualification" placeholder="APA, CIP, etc" value="{{ old('qualification') }}">
                            @error('qualification')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Location Field -->
                        <div class="col-lg-6 mb-4">
                            <label class="label text-secondary">Location</label>
                            <select class="form-control @error('location') is-invalid @enderror" name="location">
                                <option value="">Select Location</option>
                                <option value="Ireland, Dublin"
                                    {{ old('location') == 'Ireland, Dublin' ? 'selected' : '' }}>Ireland, Dublin</option>
                                <option value="UK, London" {{ old('location') == 'UK, London' ? 'selected' : '' }}>UK,
                                    London</option>
                                <option value="Remote" {{ old('location') == 'Remote' ? 'selected' : '' }}>Remote</option>
                            </select>
                            @error('location')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Work Preference Field -->
                        <div class="col-lg-6 mb-4">
                            <label class="label text-secondary">Work Preference</label>
                            <select class="form-control @error('work_preference') is-invalid @enderror"
                                name="work_preference">
                                <option value="">Select Preference</option>
                                <option value="Remote" {{ old('work_preference') == 'Remote' ? 'selected' : '' }}>Remote
                                </option>
                                <option value="Onsite" {{ old('work_preference') == 'Onsite' ? 'selected' : '' }}>Onsite
                                </option>
                                <option value="Hybrid" {{ old('work_preference') == 'Hybrid' ? 'selected' : '' }}>Hybrid
                                </option>
                            </select>
                            @error('work_preference')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- LinkedIn Profile Field -->
                        <div class="col-lg-6 mb-4">
                            <label class="label text-secondary">LinkedIn Profile</label>
                            <input type="url" class="form-control @error('linkedin_profile') is-invalid @enderror"
                                name="linkedin_profile" placeholder="Paste LinkedIn URL" value="{{ old('linkedin_profile') }}">
                            @error('linkedin_profile')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Rating Field -->
                        <div class="col-lg-6 mb-4">
                            <label class="label text-secondary">Rating</label>
                            <input type="text" class="form-control @error('rating') is-invalid @enderror"
                                name="rating" placeholder="e.g. Highly Recommended" value="{{ old('rating') }}">
                            @error('rating')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap gap-3">
                                {{-- <button type="reset" class="btn btn-danger py-2 px-4 fw-medium fs-16 text-white"
                                    onclick="window.location.href='{{ route('candidates.index') }}'">Cancel</button> --}}
                                <button type="submit" class="custom-orange-btn w-100">Save</button>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Key Strengths
            const keyWrapper = document.getElementById('key-strengths-wrapper');
            const addKeyBtn = document.getElementById('add-key-strength');

            addKeyBtn.addEventListener('click', function() {
                const count = keyWrapper.querySelectorAll('.input-group').length + 2;
                const group = document.createElement('div');
                group.className = 'input-group mb-2';
                group.innerHTML = `
                <input type="text" name="key_strengths[]" class="form-control" placeholder="${count}.">
                <button class="btn btn-dark remove-input" type="button">❌</button>
            `;
                keyWrapper.appendChild(group);
            });

            // Observations
            const obsWrapper = document.getElementById('observation-wrapper');
            const addObsBtn = document.getElementById('add-observation');

            addObsBtn.addEventListener('click', function() {
                const count = obsWrapper.querySelectorAll('.input-group').length + 2;
                const group = document.createElement('div');
                group.className = 'input-group mb-2';
                group.innerHTML = `
                <input type="text" name="observations[]" class="form-control" placeholder="${count}.">
                <button class="btn btn-dark remove-input" type="button">❌</button>
            `;
                obsWrapper.appendChild(group);
            });

            // Global event listener for remove buttons
            document.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove-input')) {
                    const group = e.target.closest('.input-group');
                    group.remove();
                }
            });
        });
    </script>


    <script>
        let experienceIndex = 1;
        let qualificationIndex = 1;

        // Add new Experience block
        document.getElementById('addExperienceBtn').addEventListener('click', function() {
            const wrapper = document.querySelector('.experience-wrapper');
            const newItem = document.querySelector('.experience-item').cloneNode(true);

            // Reset input values and update names
            newItem.querySelectorAll('input, select').forEach(input => {
                if (input.name) {
                    input.name = input.name.replace(/\[\d+\]/, `[${experienceIndex}]`);
                }
                if (input.type !== 'checkbox') input.value = '';
                if (input.type === 'checkbox') input.checked = false;
            });

            // Clear responsibility fields
            const responsibilityBox = newItem.querySelector('.responsibility-box');
            if (responsibilityBox) responsibilityBox.innerHTML = '';

            // Add remove experience button
            const removeBtn = document.createElement('button');
            removeBtn.className = 'btn btn-sm btn-dark mt-2 remove-experience';
            removeBtn.textContent = '❌ Remove this experience';
            newItem.appendChild(removeBtn);

            wrapper.appendChild(newItem);
            experienceIndex++;
        });

        // Add new Qualification block
        document.getElementById('addQualificationBtn').addEventListener('click', function() {
            const wrapper = document.querySelector('.qualification-wrapper');
            const newItem = document.querySelector('.qualification-item').cloneNode(true);

            newItem.querySelectorAll('input, select').forEach(input => {
                if (input.name) {
                    input.name = input.name.replace(/\[\d+\]/, `[${qualificationIndex}]`);
                }
                input.value = '';
            });

            // Add remove qualification button
            const removeBtn = document.createElement('button');
            removeBtn.className = 'btn btn-sm btn-dark mt-2 remove-qualification';
            removeBtn.textContent = '❌ Remove this qualification';
            newItem.appendChild(removeBtn);

            wrapper.appendChild(newItem);
            qualificationIndex++;
        });

        // Add new Responsibility input
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('add-responsibility')) {
                const container = e.target.previousElementSibling; // .responsibility-box
                const inputCount = container.querySelectorAll('input').length + 1;

                const group = document.createElement('div');
                group.className = 'input-group mb-2';

                const newInput = document.createElement('input');
                newInput.type = 'text';
                newInput.name = container.querySelector('input')?.name || `responsibilities[]`;
                newInput.placeholder = inputCount + '.';
                newInput.className = 'form-control';

                const removeBtn = document.createElement('button');
                removeBtn.className = 'btn btn-dark remove-input';
                removeBtn.type = 'button';
                removeBtn.textContent = '❌';

                group.appendChild(newInput);
                group.appendChild(removeBtn);
                container.appendChild(group);
            }
        });

        // Global remove handlers (for dynamic elements)
        document.addEventListener('click', function(e) {
            // Remove responsibility input
            if (e.target.classList.contains('remove-input')) {
                e.target.closest('.input-group').remove();
            }

            // Remove full experience block
            if (e.target.classList.contains('remove-experience')) {
                e.target.closest('.experience-item').remove();
            }

            // Remove full qualification block
            if (e.target.classList.contains('remove-qualification')) {
                e.target.closest('.qualification-item').remove();
            }
        });
    </script>
@endpush
