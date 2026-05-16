@extends('backend.app')
@section('title', 'Pending clergy verifications')

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
            <h3 class="mb-0">Pending clergy verifications</h3>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-4-line fs-18 text-primary me-1"></i>
                            <span class="text-secondary fw-medium hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-medium">Clergy verification</span>
                    </li>
                </ol>
            </nav>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-xl-12 col-xxl-12 col-lg-12">
                <div class="card bg-white border-0 rounded-3 mb-4">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 p-4">
                            <span class="position-relative table-src-form me-0">
                                <input type="text" class="form-control" placeholder="Search name, email…" id="customSearchBox">
                                <i class="material-symbols-outlined position-absolute top-50 start-0 translate-middle-y">search</i>
                            </span>
                            <a href="{{ route('user_management.clergy.index') }}" class="btn btn-outline-secondary py-1 px-2 px-sm-4 fs-14 fw-medium rounded-3">
                                All clergy users
                            </a>
                        </div>

                        <div class="default-table-area style-two all-products">
                            <div class="table-responsive">
                                <table class="table align-middle" id="basic_tables">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Avatar</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Religion</th>
                                            <th scope="col">Denomination</th>
                                            <th scope="col">Submitted</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center justify-content-sm-between align-items-center text-center flex-wrap gap-2 showing-wrap p-3">
                                <span class="fs-12 fw-medium"></span>
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination mb-0 justify-content-center">
                                        <li class="page-item">
                                            <a class="page-link icon" aria-label="Previous" href="#" id="prevPage">
                                                <i class="material-symbols-outlined">keyboard_arrow_left</i>
                                            </a>
                                        </li>
                                        <li class="row" id="customPagination"></li>
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
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('backend') }}/admin/assets/datatables/data-tables.min.js"></script>
    <script src="{{ asset('backend') }}/admin/assets/datatables/datatables.buttons.min.js"></script>
    <script src="{{ asset('backend') }}/admin/assets/datatables/jszip.min.js"></script>
    <script src="{{ asset('backend') }}/admin/assets/datatables/pdfmake.min.js"></script>
    <script src="{{ asset('backend') }}/admin/assets/datatables/buttons.html5.min.js"></script>
    <script src="{{ asset('backend') }}/admin/assets/datatables/buttons.print.min.js"></script>
    <script>
        $(document).ready(function() {
            let dTable = $('#basic_tables').DataTable({
                order: [[6, 'desc']],
                destroy: true,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                processing: true,
                responsive: true,
                serverSide: true,
                paging: true,
                language: {
                    lengthMenu: `<span style="margin-left: 20px;">Show _MENU_ entries</span>`,
                    processing: `<div class="text-center">
                        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>`
                },
                scroller: { loadingIndicator: false },
                dom: "<'row justify-content-between table-topbar'<'col-md-6 col-sm-4 px-0'l>>tir",
                ajax: {
                    url: "{{ route('admin.clergy_verification.index') }}",
                    type: "get"
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'avatar', name: 'avatar', orderable: false, searchable: false },
                    { data: 'name', name: 'name', orderable: false, searchable: true },
                    { data: 'email', name: 'email', orderable: false, searchable: true },
                    { data: 'religion', name: 'religion', orderable: true, searchable: true },
                    { data: 'denomination', name: 'denomination', orderable: true, searchable: true },
                    { data: 'created_at', name: 'created_at', orderable: true, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                drawCallback: function(settings) {
                    const totalPages = Math.ceil(settings._iRecordsDisplay / settings._iDisplayLength) || 1;
                    const currentPage = settings._iDisplayStart / settings._iDisplayLength + 1;
                    updateCustomPagination(totalPages, currentPage);
                }
            });

            $('#customSearchBox').on('keyup', function() {
                dTable.search(this.value).draw();
            });

            function updateCustomPagination(totalPages, currentPage) {
                const paginationContainer = $('#customPagination');
                paginationContainer.empty();
                const maxVisiblePages = 5;
                let startPage, endPage;
                if (totalPages <= maxVisiblePages) {
                    startPage = 1;
                    endPage = totalPages;
                } else if (currentPage <= Math.floor(maxVisiblePages / 2)) {
                    startPage = 1;
                    endPage = maxVisiblePages;
                } else if (currentPage + Math.floor(maxVisiblePages / 2) >= totalPages) {
                    startPage = totalPages - maxVisiblePages + 1;
                    endPage = totalPages;
                } else {
                    startPage = currentPage - Math.floor(maxVisiblePages / 2);
                    endPage = currentPage + Math.floor(maxVisiblePages / 2);
                }
                if (startPage > 1) {
                    paginationContainer.append(`<li class="page-item col-1"><a class="page-link active" href="#" data-page="1">1</a></li>`);
                    if (startPage > 2) paginationContainer.append(`<span class="ellipsis">...</span>`);
                }
                for (let i = startPage; i <= endPage; i++) {
                    paginationContainer.append(
                        `<li class="page-item col-1"><a class="page-link ${i === currentPage ? 'active' : ''}" href="#" data-page="${i}">${i}</a></li>`
                    );
                }
                if (endPage < totalPages) {
                    if (endPage < totalPages - 1) paginationContainer.append(`<span class="ellipsis">...</span>`);
                    paginationContainer.append(
                        `<li class="page-item col-1"><a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a></li>`
                    );
                }
                paginationContainer.find('a.page-link').off('click').on('click', function(e) {
                    e.preventDefault();
                    const page = parseInt($(this).data('page'), 10);
                    if (!isNaN(page)) dTable.page(page - 1).draw('page');
                });
            }

            $('#prevPage').off('click').on('click', function(e) {
                e.preventDefault();
                dTable.page('previous').draw('page');
            });
            $('#nextPage').off('click').on('click', function(e) {
                e.preventDefault();
                dTable.page('next').draw('page');
            });
        });
    </script>
@endpush
