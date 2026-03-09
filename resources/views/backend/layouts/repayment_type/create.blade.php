@extends('backend.app')
@section('title','Create Repayment Type')

@section('content')
<div class="main-content-container overflow-hidden">
  <h3 class="mb-4">Create Repayment Type</h3>
  <div class="card bg-white border-0 rounded-3">
    <div class="card-body p-4">
      @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
      @endif
      <form method="POST" action="{{ route('repayment_types.store') }}">
        @csrf
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="label">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-3 mb-3">
            <label class="label">Duration in days</label>
            <input type="number" name="duration_in_days" value="{{ old('duration_in_days') }}" class="form-control @error('duration_in_days') is-invalid @enderror" required>
            @error('duration_in_days')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-3 mb-3">
            <label class="label">Status</label>
            <select name="status" class="form-select @error('status') is-invalid @enderror">
              <option value="active" {{ old('status')==='active'?'selected':'' }}>active</option>
              <option value="inactive" {{ old('status')==='inactive'?'selected':'' }}>inactive</option>
            </select>
            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>
        <div class="mt-3">
          <button class="btn btn-primary">Save</button>
          <a href="{{ route('repayment_types.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection


