@extends('backend.app')
@section('title','Loan Package Details')

@section('content')
<div class="main-content-container overflow-hidden">
  <h3 class="mb-4">Loan Package: {{ $data->name }}</h3>

  <div class="card bg-white border-0 rounded-3 mb-3">
    <div class="card-body p-4">
      <div class="row g-3">
        <div class="col-md-3"><strong>Amount:</strong> {{ $data->amount }}</div>
        <div class="col-md-3"><strong>Origination fee:</strong> {{ $data->origination_fee }}</div>
        <div class="col-md-3"><strong>Service fee:</strong> {{ $data->service_fee }}</div>
        <div class="col-md-3"><strong>Status:</strong> {{ $data->status }}</div>
        <div class="col-md-12"><strong>Descriptions:</strong> {{ $data->descriptions }}</div>
      </div>
    </div>
  </div>

  <div class="card bg-white border-0 rounded-3">
    <div class="card-body p-4">
      <h5 class="mb-3">Repayment cycles</h5>
      <div class="table-responsive">
        <table class="table">
          <thead><tr><th>#</th><th>Repayment Type</th><th>Installments</th><th>Amount</th><th>Status</th></tr></thead>
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
    </div>
  </div>
  <div class="mt-3">
    <a href="{{ route('loan_packages.index') }}" class="btn btn-secondary">Back</a>
  </div>
</div>
@endsection


