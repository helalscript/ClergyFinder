@extends('backend.app')
@section('title', 'Update Candidate Page')

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
            <h3 class="mb-0">Update Candidate Page</h3>

            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="#" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-4-line fs-18 text-primary me-1"></i>
                            <span class="text-secondary fw-medium hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-medium">Candidate Update Page</span>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-medium">Update</span>
                    </li>
                </ol>
            </nav>
        </div>


        {{-- Overview Section --}}
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">

                <div class="mb-4">
                    <h4 class="fs-20 mb-1">CANDIDATE OVERVIEW</h4>
                    {{-- <p class="fs-15">Update Candidate details here.</p> --}}
                </div>
                {{-- Form to update candidate --}}
                <form action="{{ route('candidates.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    {{-- @dd($data['overviews']) --}}
                    <div class="row">
                        <!-- Name Field -->
                        <div class="col-lg-6 mb-4">
                            <label class="label text-secondary">Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                required placeholder="Enter Name"
                                value="{{ old('name', $data['overviews'][0]->name) ?? '' }}">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Ref No Field -->
                        <div class="col-lg-6 mb-4">
                            <label class="label text-secondary">Ref No</label>
                            <input type="text" class="form-control @error('refer_number') is-invalid @enderror"
                                name="refer_number" placeholder="Enter Ref No"
                                value="{{ old('refer_number', $data['overviews'][0]->refer_number ?? '') }}">
                            @error('refer_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Current Job Title Field -->
                        <div class="col-lg-6 mb-4">
                            <label class="label text-secondary">Current Job Title</label>
                            <input type="text" class="form-control @error('current_job') is-invalid @enderror"
                                name="current_job" placeholder="Enter Job Title"
                                value="{{ old('current_job', $data['overviews'][0]->current_job ?? '') }}">
                            @error('current_job')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Experience Field -->
                        <div class="col-lg-6 mb-4">
                            <label class="label text-secondary">Experience</label>
                            <select class="form-control @error('experience_year') is-invalid @enderror"
                                name="experience_year">
                                <option value="">Select Experience</option>
                                <option value="1 Year"
                                    {{ old('experience_year', $data['overviews'][0]->experience_year) ==
                                    '1
                                                                                                    Year'
                                        ? 'selected'
                                        : '' }}>
                                    1 Year
                                </option>
                                <option value="2 Years"
                                    {{ old('experience_year', $data['overviews'][0]->experience_year) ==
                                    '2
                                                                                                    Years'
                                        ? 'selected'
                                        : '' }}>
                                    2 Years
                                </option>
                                <option value="5 Years"
                                    {{ old('experience_year', $data['overviews'][0]->experience_year) ==
                                    '5
                                                                                                    Years'
                                        ? 'selected'
                                        : '' }}>
                                    5 Years
                                </option>
                                <option value="10+ Years"
                                    {{ old('experience_year', $data['overviews'][0]->experience_year) == '10+ Years' ? 'selected' : '' }}>
                                    10+
                                    Years</option>
                            </select>
                            @error('experience')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Qualifications Field -->
                        <div class="col-lg-6 mb-4">
                            <label class="label text-secondary">Qualifications</label>
                            <input type="text" class="form-control @error('qualification') is-invalid @enderror"
                                name="qualification" placeholder="APA, CIP, etc"
                                value="{{ old('qualification', $data['overviews'][0]->qualification) }}">
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
                                    {{ old('location', $data['overviews'][0]->location) ==
                                    'Ireland,
                                                                                                    Dublin'
                                        ? 'selected'
                                        : '' }}>
                                    Ireland, Dublin
                                </option>
                                <option value="UK, London"
                                    {{ old('location', $data['overviews'][0]->location) == 'UK, London' ? 'selected' : '' }}>
                                    UK,
                                    London</option>
                                <option value="Remote"
                                    {{ old('location', $data['overviews'][0]->location) == 'Remote' ? 'selected' : '' }}>
                                    Remote
                                </option>
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
                                <option value="Remote"
                                    {{ old('work_preference', $data['overviews'][0]->work_preference) == 'Remote' ? 'selected' : '' }}>
                                    Remote
                                </option>
                                <option value="Onsite"
                                    {{ old('work_preference', $data['overviews'][0]->work_preference) == 'Onsite' ? 'selected' : '' }}>
                                    Onsite
                                </option>
                                <option value="Hybrid"
                                    {{ old('work_preference', $data['overviews'][0]->work_preference) == 'Hybrid' ? 'selected' : '' }}>
                                    Hybrid
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
                                name="linkedin_profile" placeholder="Paste LinkedIn URL"
                                value="{{ old('linkedin_profile', $data['overviews'][0]->linkedin_profile ?? '') }}">
                            @error('linkedin_profile')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Rating Field -->
                        <div class="col-lg-6 mb-4">
                            <label class="label text-secondary">Rating</label>
                            <input type="text" class="form-control @error('rating') is-invalid @enderror"
                                name="rating" placeholder="e.g. Highly Recommended"
                                value="{{ old('rating', $data['overviews'][0]->rating ?? '') }}">
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
        {{-- end --Overview Section --}}
        {{-- Professional Profile Section --}}
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">

                <div class="mb-4">
                    <h4 class="fs-20 mb-1">PROFESSIONAL PROFILE</h4>
                </div>

                @php
                    // Get first profile if available, else null
                    $proProfile = $data['proProfiles']->first();
                    $keyStrengths = json_decode(optional($proProfile)->key_strengths, true) ?? [];
                    $observations = json_decode(optional($proProfile)->observations, true) ?? [];
                @endphp

                <form action="{{ route('candidates.update_pro_profile', $data->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="row">

                        <!-- Screening Date -->
                        <div class="col-lg-12 mb-4">
                            <label class="label text-secondary">Screening Date</label>
                            <input type="date" name="screening_date"
                                class="form-control @error('screening_date') is-invalid @enderror"
                                value="{{ old('screening_date', optional($proProfile)->screening_date ? optional($proProfile)->screening_date->format('Y-m-d') : '') }}">
                            @error('screening_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Candidate Insights -->
                        <div class="col-lg-12 mb-4">
                            <label class="label text-secondary">Candidate Insights</label>
                            <textarea name="insights" class="form-control @error('insights') is-invalid @enderror" rows="3">{{ old('insights', optional($proProfile)->insights) }}</textarea>
                            @error('insights')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Location & Flexibility -->
                        <div class="col-lg-12 mb-4">
                            <label class="label text-secondary">Location & Flexibility</label>
                            <textarea name="flexibility" class="form-control @error('flexibility') is-invalid @enderror" rows="2">{{ old('flexibility', optional($proProfile)->flexibility) }}</textarea>
                            @error('flexibility')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Motivation for a Change -->
                        <div class="col-lg-12 mb-4">
                            <label class="label text-secondary">Motivation for a Change</label>
                            <textarea name="motivation" class="form-control @error('motivation') is-invalid @enderror" rows="2">{{ old('motivation', optional($proProfile)->motivation) }}</textarea>
                            @error('motivation')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Career Goals & Alignment -->
                        <div class="col-lg-12 mb-4">
                            <label class="label text-secondary">Career Goals & Alignment</label>
                            <textarea name="career_goals" class="form-control @error('career_goals') is-invalid @enderror" rows="3">{{ old('career_goals', optional($proProfile)->career_goals) }}</textarea>
                            @error('career_goals')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Key Strengths -->
                        <div class="col-lg-12 mb-4">
                            <label class="label text-secondary">Key Strengths</label>
                            <div id="key-strengths-wrapper">
                                @if ($keyStrengths)
                                    @foreach ($keyStrengths as $key => $strength)
                                        <input type="text" name="key_strengths[]" class="form-control mb-2"
                                            placeholder="{{ $key + 1 }}."
                                            value="{{ old('key_strengths.' . $key, $strength) }}">
                                    @endforeach
                                @else
                                    <input type="text" name="key_strengths[]" class="form-control mb-2"
                                        placeholder="1.">
                                @endif
                            </div>
                            <button type="button" class="btn btn-link" id="add-key-strength" style="color:#E25C2C">+
                                Add New</button>
                        </div>

                        <!-- Observation -->
                        <div class="col-lg-12 mb-4">
                            <label class="label text-secondary">Observation</label>
                            <div id="observation-wrapper">
                                @if ($observations)
                                    @foreach ($observations as $key => $obs)
                                        <input type="text" name="observations[]" class="form-control mb-2"
                                            placeholder="{{ $key + 1 }}."
                                            value="{{ old('observations.' . $key, $obs) }}">
                                    @endforeach
                                @else
                                    <input type="text" name="observations[]" class="form-control mb-2"
                                        placeholder="1.">
                                @endif
                            </div>
                            <button type="button" class="btn btn-link" id="add-observation" style="color:#E25C2C">+ Add
                                New</button>
                        </div>

                        <!-- Cultural Fit & Personality -->
                        <div class="col-lg-12 mb-4">
                            <label class="label text-secondary">Cultural Fit & Personality</label>
                            <textarea name="culture_fit" class="form-control @error('culture_fit') is-invalid @enderror" rows="2">{{ old('culture_fit', optional($proProfile)->culture_fit) }}</textarea>
                            @error('culture_fit')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Salary Expectations -->
                        <div class="col-lg-12 mb-4">
                            <label class="label text-secondary">Salary Expectations</label>
                            <input type="text" name="salary_expectations"
                                class="form-control @error('salary_expectations') is-invalid @enderror"
                                value="{{ old('salary_expectations', optional($proProfile)->salary_expectations) }}">
                            @error('salary_expectations')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Notice Period -->
                        <div class="col-lg-12 mb-4">
                            <label class="label text-secondary">Notice Period</label>
                            <select name="notice_period"
                                class="form-control @error('notice_period') is-invalid @enderror">
                                <option value="">Select Notice Period</option>
                                <option value="Immediate"
                                    {{ old('notice_period', optional($proProfile)->notice_period) == 'Immediate' ? 'selected' : '' }}>
                                    Immediate</option>
                                <option value="2 Weeks"
                                    {{ old('notice_period', optional($proProfile)->notice_period) == '2 Weeks' ? 'selected' : '' }}>
                                    2 Weeks</option>
                                <option value="1 Month"
                                    {{ old('notice_period', optional($proProfile)->notice_period) == '1 Month' ? 'selected' : '' }}>
                                    1 Month</option>
                                <option value="2 Months"
                                    {{ old('notice_period', optional($proProfile)->notice_period) == '2 Months' ? 'selected' : '' }}>
                                    2 Months</option>
                            </select>
                            @error('notice_period')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <!-- Submit -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap gap-3">
                                <button type="submit" class="custom-orange-btn w-100">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{-- End Professional Profile Section --}}

        {{-- Experience Section --}}
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">

                <div class="mb-4">
                    <h4 class="fs-20 mb-1">EXPERIENCE</h4>
                    {{-- <p class="fs-15">Add new Candidate details here.</p> --}}
                </div>
                {{-- Experience Form Section --}}
                <form action="{{ route('candidates.update_experience', $data->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="experience-wrapper">
                        @if (old('experiences', $data->experiences ?? [])->count() == 0)
                            <div class="experience-item border p-3 mb-3">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Employment Period</label>
                                        <input type="date" name="experience[0][start_date]" class="form-control"
                                            required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>End Date</label>
                                        <input type="date" name="experience[0][end_date]" class="form-control">
                                        <div class="form-check mt-1">
                                            <input class="form-check-input" type="checkbox"
                                                name="experience[0][is_currently_working]" value="1">
                                            <label class="form-check-label">Currently Working</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Role</label>
                                        <input type="text" name="experience[0][role]" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Company Name</label>
                                        <input type="text" name="experience[0][company]" class="form-control"
                                            required>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label>Responsibilities</label>
                                        <div class="responsibilities">
                                            <input type="text" name="experience[0][responsibilities][]"
                                                class="form-control mb-2" value="">
                                        </div>
                                        <button type="button" class="btn btn-link p-0 add-responsibility"
                                            style="color:#E25C2C">+ Add New</button>
                                    </div>
                                </div>
                            </div>
                        @else
                            @foreach ($data->experiences as $key => $experience)
                                <div class="experience-item border p-3 mb-3">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label>Employment Period</label>
                                            <input type="date" name="experience[{{ $key }}][start_date]"
                                                class="form-control" required
                                                value="{{ \Carbon\Carbon::parse($experience->start_date)->format('Y-m-d') }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label>End Date</label>
                                            <input type="date" name="experience[{{ $key }}][end_date]"
                                                class="form-control"
                                                value="{{ \Carbon\Carbon::parse($experience->end_date)->format('Y-m-d') }}">
                                            <div class="form-check mt-1">
                                                <input class="form-check-input" type="checkbox"
                                                    name="experience[{{ $key }}][is_currently_working]"
                                                    value="1"
                                                    {{ $experience->is_currently_working ? 'checked' : '' }}>
                                                <label class="form-check-label">Currently Working</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label>Role</label>
                                            <input type="text" name="experience[{{ $key }}][role]"
                                                class="form-control" required value="{{ $experience->role }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label>Company Name</label>
                                            <input type="text" name="experience[{{ $key }}][company]"
                                                class="form-control" required value="{{ $experience->company }}">
                                        </div>
                                        @php
                                            $responsibilities = json_decode($experience->responsibilities, true) ?? [];
                                        @endphp
                                        <div class="col-12 mb-3">
                                            <label>Responsibilities</label>
                                            <div class="responsibilities">
                                                @if ($responsibilities)
                                                    @foreach ($responsibilities as $responsibility)
                                                        <input type="text"
                                                            name="experience[{{ $key }}][responsibilities][]"
                                                            class="form-control mb-2" value="{{ $responsibility }}">
                                                    @endforeach
                                                @else
                                                    <input type="text"
                                                        name="experience[{{ $key }}][responsibilities][]"
                                                        class="form-control mb-2" value="">
                                                @endif

                                            </div>
                                            <button type="button" class="btn btn-link p-0 add-responsibility"
                                                style="color:#E25C2C">+ Add New</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        @endif

                    </div>

                    <button type="button" class="btn btn-outline-primary mb-3" id="addExperienceBtn">+ Add another
                        experience</button>

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
        {{-- end Experience Section --}}

        {{-- Qualification Section --}}
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">

                <div class="mb-4">
                    <h4 class="fs-20 mb-1">QUALIFICATIONS</h4>
                    {{-- <p class="fs-15">Qualification details here.</p> --}}
                </div>
                {{-- Qualification Form Section --}}
                <form action="{{ route('candidates.update_qualification', $data->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="qualification-wrapper">
                        @if (old('qualifications', $data->qualifications ?? [])->count() == 0)
                            <div class="qualification-item border p-3 mb-3">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Level of Education</label>
                                        <input type="text" name="qualifications[0][degree]" class="form-control"
                                            value="" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Institute Name</label>
                                        <input type="text" name="qualifications[0][institution]" class="form-control"
                                            value="" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Duration</label>
                                        <input type="date" name="qualifications[0][duration]" class="form-control"
                                            value="" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Year of Passing</label>
                                        <input type="date" name="qualifications[0][graduation_date]"
                                            class="form-control" value="" required>
                                    </div>
                                </div>
                            </div>
                        @else
                            @foreach (old('qualifications', $data->qualifications ?? []) as $index => $qualification)
                                <div class="qualification-item border p-3 mb-3">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label>Level of Education</label>
                                            <input type="text" name="qualifications[{{ $index }}][degree]"
                                                class="form-control"
                                                value="{{ old('qualifications.' . $index . '.degree', $qualification->degree ?? '') }}"
                                                required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label>Institute Name</label>
                                            <input type="text" name="qualifications[{{ $index }}][institution]"
                                                class="form-control"
                                                value="{{ old('qualifications.' . $index . '.institution', $qualification->institution ?? '') }}"
                                                required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label>Duration</label>
                                            <input type="date" name="qualifications[{{ $index }}][duration]"
                                                class="form-control"
                                                value="{{ old('qualifications.' . $index . '.duration', optional($qualification->duration)->format('Y-m-d') ?? '') }}"
                                                required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label>Year of Passing</label>
                                            <input type="date"
                                                name="qualifications[{{ $index }}][graduation_date]"
                                                class="form-control"
                                                value="{{ old('qualifications.' . $index . '.graduation_date', optional($qualification->graduation_date)->format('Y-m-d') ?? '') }}"
                                                required>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <button type="button" class="btn btn-outline-primary mb-3" id="addQualificationBtn">+ Add another
                        qualification</button>

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
        {{-- end Qualification Section --}}

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
