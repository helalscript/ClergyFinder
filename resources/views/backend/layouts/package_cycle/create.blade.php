@extends('backend.app')
@section('title','Create Package Repayment Cycles')

@section('content')
<div class="main-content-container overflow-hidden">
  <h3 class="mb-4">Create Package Repayment Cycles</h3>
  <div class="card bg-white border-0 rounded-3">
    <div class="card-body p-4">
      @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
      @endif
      <form method="POST" action="{{ route('package_cycles.store') }}">
        @csrf
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="label">Package</label>
            <select name="loan_package_id" id="package_select" class="form-select @error('loan_package_id') is-invalid @enderror" required>
              <option value="">Select Package</option>
              @foreach($packages as $package)
                <option value="{{ $package->id }}" data-amount="{{ $package->amount }}" data-origination="{{ $package->origination_fee }}" data-service="{{ $package->service_fee }}">
                  {{ $package->name }} - ${{ $package->amount }} (Total: ${{ $package->amount + $package->origination_fee + $package->service_fee }})
                </option>
              @endforeach
            </select>
            @error('loan_package_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-6 mb-3">
            <label class="label">Repayment Type</label>
            <select name="repayment_type_id" id="repayment_select" class="form-select @error('repayment_type_id') is-invalid @enderror" required>
              <option value="">Select Repayment Type</option>
              @foreach($repaymentTypes as $type)
                <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->duration_in_days }} days)</option>
              @endforeach
            </select>
            @error('repayment_type_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>

        <div id="package_info" class="alert alert-info" style="display: none;">
          <strong>Package Total: $<span id="package_total">0</span></strong><br>
          <small>Amount: $<span id="package_amount">0</span> + Origination: $<span id="package_origination">0</span> + Service: $<span id="package_service">0</span></small>
        </div>

        <hr>
        <h5 class="mb-2">Repayment Cycles</h5>
        <p class="text-muted">Add installments for this package and repayment type. Total cannot exceed package total.</p>
        
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
              <button type="button" id="builder_generate" class="btn btn-outline-primary w-100">Generate</button>
            </div>
            <div class="col-md-3">
              <button type="button" id="builder_clear" class="btn btn-outline-danger w-100">Clear All</button>
            </div>
          </div>
        </div>
        
        <div id="cycles"></div>
        <div id="total_validation" class="alert alert-warning" style="display: none;"></div>

        <div class="mt-3">
          <button type="submit" class="btn btn-primary">Save Cycles</button>
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
    let cycleIndex = 0;
    const cyclesWrap = document.getElementById('cycles');
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

      const card = document.createElement('div');
      card.className = 'card bg-white border rounded-3 mb-3';
      let inner = '<div class="card-body">';
      inner += '<div class="d-flex justify-content-between align-items-center mb-2"><strong>Installments</strong><button type="button" class="btn btn-sm btn-outline-danger" data-remove>Remove group</button></div>';
      inner += '<div class="table-responsive"><table class="table mb-0"><thead><tr><th>#</th><th>Installment No</th><th>Amount</th></tr></thead><tbody>';
      for (let i=1;i<=count;i++){
        inner += '<tr>'+
          '<td>'+i+'</td>'+
          '<td><input type="number" class="form-control" name="cycles['+cycleIndex+'][installment_no]" value="'+i+'"></td>'+
          '<td><div class="input-group"><span class="input-group-text">$</span><input type="number" step="0.01" class="form-control cycle-amount" name="cycles['+cycleIndex+'][installment_amount]" value="'+amount+'"></div></td>'+
          '</tr>';
        cycleIndex++;
      }
      inner += '</tbody></table></div>';
      inner += '</div>';
      card.innerHTML = inner;
      card.querySelector('[data-remove]').addEventListener('click', function(){ card.remove(); updateTotal(); });
      cyclesWrap.appendChild(card);
      updateTotal();
    });

    document.getElementById('builder_clear').addEventListener('click', function(){
      cyclesWrap.innerHTML = '';
      cycleIndex = 0;
      updateTotal();
    });

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
  })();
</script>
@endpush
