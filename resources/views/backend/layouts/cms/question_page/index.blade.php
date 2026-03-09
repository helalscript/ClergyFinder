@extends('backend.app')
@section('title', ucfirst($userType->value) . ' Questions')

@push('styles')
    <style>
        [type=checkbox]:checked, [type=radio]:checked { background-size: 40% !important; }
    </style>
@endpush

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">{{ ucfirst($userType->value) }} Questions</h3>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item"><a href="#" class="d-flex align-items-center text-decoration-none"><i class="ri-home-4-line fs-18 text-primary me-1"></i><span class="text-secondary fw-medium hover">Dashboard</span></a></li>
                    <li class="breadcrumb-item active"><span class="fw-medium">Question Page</span></li>
                    <li class="breadcrumb-item active"><span class="fw-medium">{{ ucfirst($userType->value) }}</span></li>
                </ol>
            </nav>
        </div>

        <div class="col-xl-12">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-0">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 p-4">
                        <span class="position-relative table-src-form me-0">
                            <input type="text" class="form-control" placeholder="Search here" id="customSearchBox">
                            <i class="material-symbols-outlined position-absolute top-50 start-0 translate-middle-y">search</i>
                        </span>
                        <a href="{{ route('cms.question_page.' . $userType->value . '.create') }}" class="btn btn-outline-primary py-1 px-2 px-sm-4 fs-14 fw-medium rounded-3 hover-bg">
                            <span class="py-sm-1 d-block"><i class="ri-add-line d-none d-sm-inline-block"></i><span>Add Question</span></span>
                        </a>
                    </div>
                    <div class="default-table-area style-two all-products">
                        <div class="table-responsive">
                            <table class="table align-middle" id="basic_tables">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Question</th>
                                        <th>Image</th>
                                        <th>Answer Type</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center justify-content-sm-between align-items-center text-center flex-wrap gap-2 showing-wrap p-4">
                            <span class="fs-12 fw-medium"></span>
                            <nav aria-label="Page navigation">
                                <ul class="pagination mb-0 justify-content-center">
                                    <li class="page-item"><a class="page-link icon" href="#" id="prevPage"><i class="material-symbols-outlined">keyboard_arrow_left</i></a></li>
                                    <li class="row" id="customPagination"></li>
                                    <li class="page-item"><a class="page-link icon" href="#" id="nextPage"><i class="material-symbols-outlined">keyboard_arrow_right</i></a></li>
                                </ul>
                            </nav>
                        </div>
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
    <script>
        $(document).ready(function() {
            let dTable = $('#basic_tables').DataTable({
                order: [],
                destroy: true,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                processing: true,
                responsive: true,
                serverSide: true,
                paging: true,
                language: { lengthMenu: `<span style="margin-left: 20px;">Show _MENU_ entries</span>`, processing: `<div class="text-center"><div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>` },
                scroller: { loadingIndicator: false },
                dom: "<'row justify-content-between table-topbar'<'col-md-6 col-sm-4 px-0'l>>tir",
                ajax: { url: "{{ route('cms.question_page.' . $userType->value . '.index') }}", type: "get" },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'question', name: 'question', orderable: true, searchable: true, render: function(d) { return d && d.length > 50 ? d.substring(0, 50) + '...' : (d || '-'); } },
                    { data: 'image', name: 'image', orderable: false, searchable: false },
                    { data: 'answer_type', name: 'answer_type', orderable: true, searchable: false },
                    { data: 'status', name: 'status', orderable: true, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                drawCallback: function(s) {
                    const total = Math.ceil(s._iRecordsDisplay / s._iDisplayLength);
                    const curr = s._iDisplayStart / s._iDisplayLength + 1;
                    updatePagination(total, curr);
                }
            });
            $('#customSearchBox').on('keyup', function() { dTable.search(this.value).draw(); });
            function updatePagination(total, curr) {
                const c = $('#customPagination'); c.empty();
                const max = 5; let s = 1, e = total;
                if (total > max) {
                    if (curr <= Math.floor(max/2)) { e = max; }
                    else if (curr + Math.floor(max/2) >= total) { s = total - max + 1; }
                    else { s = curr - Math.floor(max/2); e = curr + Math.floor(max/2); }
                }
                for (let i = s; i <= e; i++) c.append(`<li class="page-item col-1"><a class="pagination-item page-link ${i===curr?'active':''}" href="#" data-page="${i}">${i}</a></li>`);
                $('.pagination-item').on('click', function(e) { e.preventDefault(); dTable.page($(this).data('page')-1).draw('page'); });
                $('#prevPage').off().on('click', function(e) { e.preventDefault(); if (curr > 1) dTable.page(curr-2).draw('page'); });
                $('#nextPage').off().on('click', function(e) { e.preventDefault(); if (curr < total) dTable.page(curr).draw('page'); });
            }
        });
    </script>
    <script src="{{ asset('backend/admin/assets/custom-actions.js') }}"></script>
    <script>
        function changeStatus(event, id) { event.preventDefault(); showStatusChangeAlert(id, '{{ route("cms.question_page." . $userType->value . ".status", ":id") }}'.replace(':id', id)); }
        function deleteRecord(event, id) { event.preventDefault(); showDeleteConfirm(id, '{{ route("cms.question_page." . $userType->value . ".destroy", ":id") }}'.replace(':id', id)); }
    </script>
@endpush
