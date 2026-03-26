@extends('backend.app')
@section('title','Edit Loan Package')

@section('content')
<div class="main-content-container overflow-hidden">
  <h3 class="mb-4">Edit Loan Package</h3>
  <div class="card bg-white border-0 rounded-3">
    <div class="card-body p-4">
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      <form method="POST" action="{{ route('loan_packages.update', $data->id) }}">
        @csrf
        @method('PUT')
        <div class="row">
          <div class="col-md-4 mb-3">
            <label class="label">Name</label>
            <input type="text" name="name" value="{{ old('name', $data->name) }}" class="form-control @error('name') is-invalid @enderror" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-4 mb-3">
            <label class="label">Amount</label>
            <input type="number" step="0.01" name="amount" value="{{ old('amount', $data->amount) }}" class="form-control @error('amount') is-invalid @enderror" required>
            @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-4 mb-3">
            <label class="label">Origination fee</label>
            <input type="number" step="0.01" name="origination_fee" value="{{ old('origination_fee', $data->origination_fee) }}" class="form-control @error('origination_fee') is-invalid @enderror" required>
            @error('origination_fee')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-4 mb-3">
            <label class="label">Service fee</label>
            <input type="number" step="0.01" name="service_fee" value="{{ old('service_fee', $data->service_fee) }}" class="form-control @error('service_fee') is-invalid @enderror" required>
            @error('service_fee')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-8 mb-3">
            <label class="label">Descriptions</label>
            <input type="text" name="descriptions" value="{{ old('descriptions', $data->descriptions) }}" class="form-control @error('descriptions') is-invalid @enderror">
            @error('descriptions')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-4 mb-3">
            <label class="label">Status</label>
            <select name="status" class="form-select @error('status') is-invalid @enderror">
              <option value="active" {{ old('status', $data->status)==='active'?'selected':'' }}>active</option>
              <option value="inactive" {{ old('status', $data->status)==='inactive'?'selected':'' }}>inactive</option>
            </select>
            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>

        <h5 class="mt-3">Existing cycles</h5>
        <div class="table-responsive">
          <table class="table">
            <thead><tr><th>#</th><th>Repayment</th><th>Installments</th><th>Amount</th><th>Status</th></tr></thead>
            <tbody>
            @foreach($cycles as $c)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ optional($c->repaymentType)->name }}</td>
                <td>{{ $c->installment_no }}</td>
                <td>{{ $c->installment_amount }}</td>
                <td>{{ $c->status }}</td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>

        <hr>
        <h5 class="mb-2">Add more cycles</h5>
        <div id="cycle-builder" class="border rounded p-3 mb-3">
          <div class="row g-2 align-items-end">
            <div class="col-md-6">
              <label class="label">Repayment Type</label>
              <select id="builder_repayment_type" class="form-select">
                @foreach($repaymentTypes as $rt)
                  <option value="{{ $rt->id }}">{{ $rt->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label class="label">No. of installments</label>
              <input id="builder_installments" type="number" min="1" value="1" class="form-control">
            </div>
            <div class="col-md-3">
              <button type="button" id="builder_generate" class="btn btn-outline-primary w-100">Generate</button>
            </div>
          </div>
        </div>
        <div id="cycles"></div>

        <div class="mt-3">
          <button class="btn btn-primary">Update</button>
          <a href="{{ route('loan_packages.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  (function(){
    let cycleIndex = 0;
    const cyclesWrap = document.getElementById('cycles');
    document.getElementById('builder_generate').addEventListener('click', function(){
      const repaymentTypeId = document.getElementById('builder_repayment_type').value;
      const repaymentTypeText = document.getElementById('builder_repayment_type').options[document.getElementById('builder_repayment_type').selectedIndex].text;
      const count = parseInt(document.getElementById('builder_installments').value || '1', 10);
      if (!repaymentTypeId || count < 1) return;

      const card = document.createElement('div');
      card.className = 'card bg-white border rounded-3 mb-3';
      let inner = '<div class="card-body">';
      inner += '<div class="d-flex justify-content-between align-items-center mb-2"><strong>'+repaymentTypeText+'</strong><button type="button" class="btn btn-sm btn-outline-danger" data-remove>Remove group</button></div>';
      inner += '<div class="table-responsive"><table class="table mb-0"><thead><tr><th>#</th><th>Installment No</th><th>Amount</th></tr></thead><tbody>';
      for (let i=1;i<=count;i++){
        inner += '<tr>'+
          '<td>'+i+'</td>'+
          '<td><input type="number" class="form-control" name="cycles['+cycleIndex+'][installment_no]" value="'+i+'"></td>'+
          '<td><div class="input-group"><span class="input-group-text">$</span><input type="number" step="0.01" class="form-control" name="cycles['+cycleIndex+'][installment_amount]" value="0"></div></td>'+
          '</tr>'+
          '<input type="hidden" name="cycles['+cycleIndex+'][repayment_type_id]" value="'+repaymentTypeId+'">';
        cycleIndex++;
      }
      inner += '</tbody></table></div>';
      inner += '</div>';
      card.innerHTML = inner;
      card.querySelector('[data-remove]').addEventListener('click', function(){ card.remove(); });
      cyclesWrap.appendChild(card);
    });
  })();
</script>
@endpush


