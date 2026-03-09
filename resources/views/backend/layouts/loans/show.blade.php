@extends('backend.app')
@section('title', 'Loan Details')

@section('content')
    <div class="main-content-container overflow-hidden">
        <h3 class="mb-4">Loan Details</h3>

        {{-- Loan & User Overview --}}
        <div class="row g-4 mb-4">
            {{-- Loan Package --}}
            <div class="col-md-6">
                <div class="card bg-white border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body p-4">
                        <h5 class="mb-3"><i class="bi bi-box-seam me-2"></i> Loan Package</h5>
                        <p><strong>Name:</strong> {{ $data->loanPackage['name'] ?? 'N/A' }}</p>
                        <p><strong>Amount:</strong> {{ $data->loanPackage['amount'] ?? 'N/A' }}</p>
                        <p><strong>Descriptions:</strong><br>{!! nl2br($data->loanPackage['descriptions'] ?? 'N/A') !!}</p>
                    </div>
                </div>
            </div>

            {{-- User Information --}}
            <div class="col-md-6">
                <div class="card bg-white border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body p-4">
                        <h5 class="mb-3"><i class="bi bi-person-circle me-2"></i> User Information</h5>
                        <p><strong>Name:</strong> {{ $data->user->name ?? '—' }}</p>
                        <p><strong>Email:</strong> {{ $data->user->email ?? '—' }}</p>
                        <p><strong>Phone:</strong> {{ $data->user->phone ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Loan Overview --}}
        <div class="card bg-white border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <h5 class="mb-3"><i class="bi bi-cash-coin me-2"></i> Loan Overview</h5>
                <div class="row g-3">
                    <div class="col-md-3"><strong>Principal:</strong> ${{ $data->principal_amount }}</div>
                    <div class="col-md-3"><strong>Origination Fee:</strong> ${{ $data->origination_fee_amount }}</div>
                    <div class="col-md-3"><strong>Service Fee:</strong> ${{ $data->service_fee_amount }}</div>
                    <div class="col-md-3"><strong>Total Repay:</strong> ${{ $data->total_amount }}</div>
                    <div class="col-md-3">
                        <strong>Status:</strong>
                        <span
                            class="badge bg-{{ $data->status == 'pending'
                                ? 'warning'
                                : ($data->status == 'approved'
                                    ? 'primary'
                                    : ($data->status == 'rejected'
                                        ? 'danger'
                                        : ($data->status == 'completed'
                                            ? 'success'
                                            : 'info'))) }}">
                            {{ ucfirst($data->status) }}
                        </span>
                    </div>
                    <div class="col-md-3"><strong>Issued At:</strong> {{ $data->loan_issue_date ?? '—' }}</div>
                    <div class="col-md-3"><strong>Closed At:</strong> {{ $data->payment_close_date ?? '—' }}</div>
                    <div class="col-md-3"><strong>Terms & Conditions:</strong>
                        {{ $data->agreed_terms ? 'Agreed' : 'Not Agreed' }}</div>
                    <div class="col-md-3"><strong>Review Note:</strong> {{ $data->review_note ?? '—' }}</div>
                    <div class="col-md-3"><strong>Frequency:</strong> {{ ucfirst($data->frequency) }}</div>
                    <div class="col-md-3"><strong>Purpose:</strong> {{ $data->loan_purpose ?? '—' }}</div>

                </div>
            </div>
        </div>

        {{-- Applicants --}}
        <div class="card bg-white border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <h5 class="mb-3"><i class="bi bi-people me-2"></i> Applicant Information</h5>
                <div class="row">
                    @foreach ($data->applicantInformations as $applicant)
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 mb-3">
                                <h6 class="mb-2 text-primary">{{ ucfirst($applicant['information_type']) }} Information
                                </h6>
                                <p><strong>Name:</strong> {{ $applicant['first_name'] }} {{ $applicant['last_name'] }}</p>
                                <p><strong>Email:</strong> {{ $applicant['email'] }}</p>
                                <p><strong>Phone:</strong> {{ $applicant['phone'] }}</p>
                                <p><strong>Birth Day:</strong> {{ $applicant['birth_date']?\Carbon\Carbon::parse($applicant['birth_date'])->format('Y-m-d'):'N/A' }}</p>
                                <p><strong>Address:</strong> {{ $applicant['street_address'] }}, {{ $applicant['city'] }},
                                    {{ $applicant['state'] }} {{ $applicant['zip'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @php
            $incomeSources = $data->income_sources;

            // First decode
            $decoded = json_decode($incomeSources, true);

            // If still string, decode again
            if (is_string($decoded)) {
                $decoded = json_decode($decoded, true);
            }

            $incomeSources = $decoded ?? [];
        @endphp

        <div class="card bg-white border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <h5 class="mb-3"><i class="bi bi-people me-2"></i> Applicant Income Source</h5>
                <div class="row">
                    @forelse ($incomeSources as $applicant)
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 mb-3">
                                <h6 class="mb-2 text-primary">Income Information</h6>

                                @foreach ($applicant as $key => $value)
                                    <p>
                                        <strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong>
                                        {{ $value }}
                                    </p>
                                @endforeach

                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No income sources provided.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Documents --}}
        <div class="card bg-white border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <h5 class="mb-3"><i class="bi bi-file-earmark-text me-2"></i> Documents</h5>
                @if (count($data->loanDocuments))
                    <ul class="list-group">
                        @foreach ($data->loanDocuments as $doc)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $doc['title'] ?? 'Document' }}</span>
                                <div>
                                    {{-- <span
                                        class="badge bg-{{ $doc['status'] == 'pending' ? 'warning' : ($doc['status'] == 'approved' ? 'success' : 'danger') }}">
                                        {{ ucfirst($doc['status']) }}
                                    </span> --}}
                                    <a href="{{ asset($doc['file_path']) }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary ms-2">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No documents uploaded.</p>
                @endif
            </div>
        </div>

        {{-- Repayment Schedule --}}
        <div class="card bg-white border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <h5 class="mb-3"><i class="bi bi-calendar-check me-2"></i> Repayment Schedule</h5>
                @if (count($data->loanRepaymentSchedules))
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Re-Payment Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data->loanRepaymentSchedules as $key => $schedule)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ date('Y-M-d', strtotime($schedule['due_date'])) }}</td>
                                        <td>${{ $schedule['amount'] }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $schedule['status'] == 'paid' ? 'success' : ($schedule['status'] == 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($schedule['status']) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($schedule['status'] == 'paid')
                                                <span class="text-success">Already Paid</span>
                                            @else
                                                <a href="{{ route('loan_repayments.update_status', ['id' => $schedule['id']]) }}"
                                                    class="btn btn-sm btn-outline-primary ms-2">Mark As Paid</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Repayment schedule will be generated after loan approval.</p>
                @endif
            </div>
        </div>
        {{--  this section for some button to accept or reject and or make a Review by admin or team --}}
        {{-- Action Buttons for Admin/Team --}}
        <div class="card bg-white border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <h5 class="mb-3"><i class="bi bi-check2-circle me-2"></i> Admin Actions</h5>

                <div class="d-flex gap-2">

                    {{-- Review (with note) --}}
                    @if (in_array($data->status, ['pending', 'approved']))
                        <form action="{{ route('loans.update_status', $data->id) }}" method="POST" class="d-flex gap-2">
                            @csrf
                            <input type="hidden" name="status" value="review">
                            <input type="text" name="review_note" placeholder="Enter review note" class="form-control"
                                style="max-width: 250px;">
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-chat-left-text"></i> Review
                            </button>
                        </form>
                    @endif

                    {{-- Approve --}}
                    @if (in_array($data->status, ['pending', 'review']))
                        <form action="{{ route('loans.update_status', $data->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Approve
                            </button>
                        </form>
                    @endif

                    {{-- Reject --}}
                    @if (in_array($data->status, ['pending', 'review', 'approved']))
                        <form action="{{ route('loans.update_status', $data->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-x-circle"></i> Reject
                            </button>
                        </form>
                    @endif

                    {{-- Disbursed --}}
                    @if ($data->status === 'approved')
                        <form action="{{ route('loans.update_status', $data->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="disbursed">
                            <button type="submit" class="btn btn-secondary">
                                <i class="bi bi-lock"></i> Disburse
                            </button>
                        </form>
                    @endif

                    {{-- Completed --}}
                    @if (in_array($data->status, ['repayment', 'overdue']))
                        <form action="{{ route('loans.update_status', $data->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Completed
                            </button>
                        </form>
                    @endif



                </div>
            </div>
        </div>
        <div class="mt-3">
            <a href="{{ route('loans.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back</a>
        </div>
    </div>
@endsection
