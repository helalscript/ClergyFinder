@extends('backend.app')
@section('title', 'Loan List')

@push('styles')
    <style>
        [type=checkbox]:checked,
        [type=radio]:checked {
            background-size: 40% !important;
        }
    </style>
@endpush

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">Loan List</h3>
            {{-- <a href="{{ route('loans.create') }}" class="btn btn-outline-primary py-1 px-2 px-sm-4 fs-14 fw-medium rounded-3">Add Package</a> --}}
        </div>

        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-0">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 p-4">
                    <span class="position-relative table-src-form me-0">
                        <input type="text" class="form-control" placeholder="Search here" id="customSearchBox">
                        <i class="material-symbols-outlined position-absolute top-50 start-0 translate-middle-y">search</i>
                    </span>
                </div>

                <div class="default-table-area style-two all-products">
                    <div class="table-responsive">
                        <table class="table align-middle" id="basic_tables">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Borrower Name</th>
                                    <th>Amount</th>
                                    <th>Repayment Type</th>
                                    <th>Repayment Method</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center justify-content-sm-between align-items-center text-center flex-wrap gap-2 showing-wrap">
                        <span class="fs-12 fw-medium"></span>
                        <nav aria-label="Page navigation example">
                            <ul class="pagination mb-0 justify-content-center">
                                <li class="page-item">
                                    <a class="page-link icon" aria-label="Previous" href="#" id="prevPage">
                                        <i class="material-symbols-outlined">keyboard_arrow_left</i>
                                    </a>
                                </li>
                                <li class="row " id="customPagination"></li>
                                <li class="page-item">
                                    <a class="page-link icon" aria-label="Next" href="#" id="nextPage">
                                        <i class="material-symbols-outlined">keyboard_arrow_right</i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('backend/admin/assets/datatables/data-tables.min.js') }}"></script>
    <script src="{{ asset('backend/admin/assets/datatables/datatables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend/admin/assets/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('backend/admin/assets/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('backend/admin/assets/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend/admin/assets/datatables/buttons.print.min.js') }}"></script>
    <script src="{{ asset('backend/admin/assets/custom-actions.js') }}"></script>
    <script>
        $(document).ready(function() {
            let dTable = $('#basic_tables').DataTable({
                order: [],
                destroy: true,
                lengthMenu: [ [10, 25, 50, 100, 200, 500, -1], [10, 25, 50, 100, 200, 500, "All"] ],
                processing: true,
                responsive: true,
                serverSide: true,
                paging: true,
                language: {
                    lengthMenu: `<span style="margin-left: 20px;">Show _MENU_ entries</span>`,
                    processing: `<div class="text-center"><div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>`
                },
                dom: "<'row justify-content-between table-topbar'<'col-md-6 col-sm-4 px-0'l>>tir",
                ajax: { url: "{{ route('loans.index') }}", type: "get" },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'user.name', name: 'user.name' },
                    { data: 'principal_amount', name: 'principal_amount' },
                    { data: 'frequency', name: 'frequency' },
                    { data: 'repayment_method', name: 'repayment_method' },
                    { data: 'status', name: 'status', orderable: true, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                drawCallback: function(settings) {
                    const totalPages = Math.ceil(settings._iRecordsDisplay / settings._iDisplayLength);
                    const currentPage = settings._iDisplayStart / settings._iDisplayLength + 1;
                    updateCustomPagination(totalPages, currentPage);
                }
            });

            $('#customSearchBox').on('keyup', function() { dTable.search(this.value).draw(); });

            function updateCustomPagination(totalPages, currentPage) {
                const paginationContainer = $('#customPagination');
                paginationContainer.empty();
                const maxVisiblePages = 5;
                let startPage, endPage;
                if (totalPages <= maxVisiblePages) { startPage = 1; endPage = totalPages; }
                else if (currentPage <= Math.floor(maxVisiblePages / 2)) { startPage = 1; endPage = maxVisiblePages; }
                else if (currentPage + Math.floor(maxVisiblePages / 2) >= totalPages) { startPage = totalPages - maxVisiblePages + 1; endPage = totalPages; }
                else { startPage = currentPage - Math.floor(maxVisiblePages / 2); endPage = currentPage + Math.floor(maxVisiblePages / 2); }
                if (startPage > 1) { paginationContainer.append(`<li class="page-item col-1"><a class="page-link active" href="#" data-page="1">1</a></li>`); if (startPage > 2) { paginationContainer.append(`<span class="ellipsis">...</span>`); } }
                for (let i = startPage; i <= endPage; i++) { paginationContainer.append(` <li class="page-item col-1"><a class="pagination-item page-link ${i === currentPage ? 'active' : ''}" href="#" data-page="${i}">${i}</a></li>`); }
                if (endPage < totalPages) { if (endPage < totalPages - 1) { paginationContainer.append(`<span class="ellipsis">...</span>`); } paginationContainer.append(`<li class=\"page-item col-1\"><a class=\"pagination-item page-link\"  data-page=\"${totalPages}\">${totalPages}</a></li>`); }
                $('.pagination-item').on('click', function(e) { e.preventDefault(); const page = $(this).data('page'); if (!$(this).hasClass('disabled')) { dTable.page(page - 1).draw('page'); } });
                $('#prevPage').off().on('click', function(e) { e.preventDefault(); if (currentPage > 1) { dTable.page(currentPage - 2).draw('page'); } });
                $('#nextPage').off().on('click', function(e) { e.preventDefault(); if (currentPage < totalPages) { dTable.page(currentPage).draw('page'); } });
            }



            window.deleteRecord = function(event, id) {
                event.preventDefault();
                let deleteUrl = '{{ route('loans.destroy', ':id') }}'.replace(':id', id);
                showDeleteConfirm(id, deleteUrl);
            }
        });
    </script>
@endpush


