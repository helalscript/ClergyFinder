@extends('backend.app')
@section('title','Edit Package Repayment Cycles')

@section('content')
<div class="main-content-container overflow-hidden">
  <h3 class="mb-4">Edit Package Repayment Cycles</h3>
  <div class="card bg-white border-0 rounded-3">
    <div class="card-body p-4">
      @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
      @endif
      
      <form method="POST" action="{{ route('package_cycles.update', $data->id ?? 0) }}">
        @csrf
        @method('PUT')
        
        <div class="row mb-3">
          <div class="col-md-6">
            <label class="label">Package</label>
            <select name="loan_package_id" id="package_select" class="form-select @error('loan_package_id') is-invalid @enderror" required>
              @foreach($packages as $package)
                <option value="{{ $package->id }}" {{ ($data->loan_package_id ?? 0) == $package->id ? 'selected' : '' }}>
                  {{ $package->name }} - ${{ $package->amount }} (Total: ${{ $package->amount + $package->origination_fee + $package->service_fee }})
                </option>
              @endforeach
            </select>
            @error('loan_package_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-6">
            <label class="label">Repayment Type</label>
            <select name="repayment_type_id" id="repayment_select" class="form-select @error('repayment_type_id') is-invalid @enderror" required>
              @foreach($repaymentTypes as $type)
                <option value="{{ $type->id }}" {{ ($data->repayment_type_id ?? 0) == $type->id ? 'selected' : '' }}>
                  {{ $type->name }} ({{ $type->duration_in_days }} days)
                </option>
              @endforeach
            </select>
            @error('repayment_type_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>

        <div id="package_info" class="alert alert-info">
          <strong>Package Total: $<span id="package_total">{{ $data->loanPackage->amount + $data->loanPackage->origination_fee + $data->loanPackage->service_fee }}</span></strong><br>
          <small>Amount: $<span id="package_amount">{{ $data->loanPackage->amount }}</span> + Origination: $<span id="package_origination">{{ $data->loanPackage->origination_fee }}</span> + Service: $<span id="package_service">{{ $data->loanPackage->service_fee }}</span></small>
        </div>
        
        <h5 class="mb-2">Existing Cycles</h5>
        <div class="table-responsive mb-3">
          <table class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>Installment No</th>
                <th>Amount</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="existing_cycles">
              @foreach($cycles as $cycle)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>
                    <input type="number" name="cycles[{{ $loop->index }}][installment_no]" value="{{ $cycle->installment_no }}" class="form-control">
                    <input type="hidden" name="cycles[{{ $loop->index }}][id]" value="{{ $cycle->id }}">
                  </td>
                  <td>
                    <div class="input-group">
                      <span class="input-group-text">$</span>
                      <input type="number" step="0.01" name="cycles[{{ $loop->index }}][installment_amount]" value="{{ $cycle->installment_amount }}" class="form-control cycle-amount">
                    </div>
                  </td>
                  <td>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCycle(this)">Remove</button>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <hr>
        <h5 class="mb-2">Add New Cycles</h5>
        <div id="cycle-builder" class="border rounded p-3 mb-3">
          <div class="row g-2 align-items-end">
            <div class="col-md-3">
              <label class="label">No. of installments</label>
              <input id="builder_installments" type="number" min="1" value="1" class="form-control">
            </div>
            <div class="col-md-3">
              <label class="label">Amount per installment</label>
              <input id="builder_amount" type="number" step="0.01" min="0" class="form-control">
            </div>
            <div class="col-md-3">
              <button type="button" id="builder_generate" class="btn btn-outline-primary w-100">Add Cycles</button>
            </div>
            <div class="col-md-3">
              <button type="button" id="builder_clear" class="btn btn-outline-danger w-100">Clear All</button>
            </div>
          </div>
        </div>
        
        <div id="total_validation" class="alert alert-warning" style="display: none;"></div>

        <div class="mt-3">
          <button type="submit" class="btn btn-primary">Update Cycles</button>
          <a href="{{ route('package_cycles.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  (function(){
    let cycleIndex = {{ count($cycles) }};
    const existingCycles = document.getElementById('existing_cycles');
    const packageSelect = document.getElementById('package_select');
    const packageInfo = document.getElementById('package_info');
    const packageTotal = document.getElementById('package_total');
    const packageAmount = document.getElementById('package_amount');
    const packageOrigination = document.getElementById('package_origination');
    const packageService = document.getElementById('package_service');
    const totalValidation = document.getElementById('total_validation');

    packageSelect.addEventListener('change', function(){
      const option = this.options[this.selectedIndex];
      if (option.value) {
        packageAmount.textContent = option.dataset.amount;
        packageOrigination.textContent = option.dataset.origination;
        packageService.textContent = option.dataset.service;
        const total = parseFloat(option.dataset.amount) + parseFloat(option.dataset.origination) + parseFloat(option.dataset.service);
        packageTotal.textContent = total.toFixed(2);
        packageInfo.style.display = 'block';
      } else {
        packageInfo.style.display = 'none';
      }
    });

    document.getElementById('builder_generate').addEventListener('click', function(){
      const count = parseInt(document.getElementById('builder_installments').value || '1', 10);
      const amount = parseFloat(document.getElementById('builder_amount').value || '0', 10);
      if (count < 1 || amount <= 0) return;

      for (let i=1;i<=count;i++){
        const row = document.createElement('tr');
        row.innerHTML = '<td>' + (existingCycles.children.length + 1) + '</td>' +
          '<td><input type="number" class="form-control" name="cycles['+cycleIndex+'][installment_no]" value="'+(existingCycles.children.length + 1)+'"></td>' +
          '<td><div class="input-group"><span class="input-group-text">$</span><input type="number" step="0.01" class="form-control cycle-amount" name="cycles['+cycleIndex+'][installment_amount]" value="'+amount+'"></div></td>' +
          '<td><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCycle(this)">Remove</button></td>';
        existingCycles.appendChild(row);
        cycleIndex++;
      }
      updateTotal();
    });

    document.getElementById('builder_clear').addEventListener('click', function(){
      existingCycles.innerHTML = '';
      cycleIndex = 0;
      updateTotal();
    });

    window.removeCycle = function(button){
      button.closest('tr').remove();
      updateTotal();
    };

    function updateTotal(){
      const amounts = Array.from(document.querySelectorAll('.cycle-amount')).map(input => parseFloat(input.value) || 0);
      const total = amounts.reduce((sum, amount) => sum + amount, 0);
      const packageTotalValue = parseFloat(packageTotal.textContent) || 0;
      
      if (packageTotalValue > 0) {
        if (total > packageTotalValue) {
          totalValidation.textContent = 'Total cycle amount ($' + total.toFixed(2) + ') exceeds package total ($' + packageTotalValue.toFixed(2) + ')';
          totalValidation.className = 'alert alert-danger';
          totalValidation.style.display = 'block';
        } else {
          totalValidation.textContent = 'Total cycle amount: $' + total.toFixed(2) + ' / Package total: $' + packageTotalValue.toFixed(2);
          totalValidation.className = 'alert alert-info';
          totalValidation.style.display = 'block';
        }
      }
    }

    document.addEventListener('change', function(e){
      if (e.target.classList.contains('cycle-amount')) updateTotal();
    });

    // Initial calculation
    updateTotal();
  })();
</script>
@endpush
