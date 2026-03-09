@extends('backend.app')
@section('title', 'CMS Page')

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
            <h3 class="mb-0">CMS Item List</h3>


            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="#" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-4-line fs-18 text-primary me-1"></i>
                            <span class="text-secondary fw-medium hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-medium">Home Page </span>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-medium"> Item List</span>
                    </li>
                </ol>
            </nav>
        </div>
        {{-- ---------------------- --}}

        <div class="row justify-content-center">
            <div class="col-xl-5 col-xxl-5 col-lg-6">
                <div class="card bg-white border-0 rounded-3 mb-4">
                    <div class="card-body p-4">

                        <div class="mb-4">
                            <h4 class="fs-20 mb-1">CMS Testimonial Container</h4>
                            <p class="fs-15">Update Testimonial Container and site details here.</p>
                        </div>

                        <form action="{{ route('cms.home_page.testimonial_section.update_container') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <!-- Title Field -->
                                <div class="col-lg-12">
                                    <div class="form-group mb-4">
                                        <label class="label text-secondary">Section Title<span
                                                class="text-danger">*</span></label>
                                        <div class="form-group position-relative">
                                            <input type="text"
                                                class="form-control text-dark ps-5 h-55 @error('title') is-invalid @enderror"
                                                name="title" value="{{ old('title', $containerData->title ?? '') }}"
                                                required placeholder="Enter Section Title here">
                                        </div>
                                        @error('title')
                                            <div id="title-error" class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Sub Title Field -->
                                <div class="col-lg-12">
                                    <div class="form-group mb-4">
                                        <label class="label text-secondary">Title<span
                                                class="text-danger">*</span></label>
                                        <div class="form-group position-relative">
                                            <input type="text"
                                                class="form-control text-dark ps-5 h-55 @error('sub_title') is-invalid @enderror"
                                                name="sub_title" value="{{ old('sub_title', $containerData->sub_title ?? '') }}"
                                                required placeholder="Enter Title here">
                                        </div>
                                        @error('sub_title')
                                            <div id="sub_title-error" class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="d-flex flex-wrap gap-3">
                                        {{-- <button type="submit" class="btn btn-danger py-2 px-4 fw-medium fs-16 text-white">Cancel</button> --}}
                                        <button type="submit" class="btn btn-primary py-2 px-4 fw-medium fs-16"> <i
                                                class="ri-check-line text-white fw-medium"></i> Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            {{-- here table container --}}
            <div class="col-xl-7 col-xxl-7 col-lg-6">
                <div class="card bg-white border-0 rounded-3 mb-4">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 p-4">
                            <span class="position-relative table-src-form me-0">
                                <input type="text" class="form-control" placeholder="Search here" id="customSearchBox">
                                <i
                                    class="material-symbols-outlined position-absolute top-50 start-0 translate-middle-y">search</i>
                            </span>
                            <a href="{{ route('cms.home_page.testimonial_section.create') }}"
                                class="btn btn-outline-primary py-1 px-2 px-sm-4 fs-14 fw-medium rounded-3 hover-bg">
                                <span class="py-sm-1 d-block">
                                    <i class="ri-add-line d-none d-sm-inline-block"></i>
                                    <span>Add New Content</span>
                                </span>
                            </a>
                        </div>

                        <div class="default-table-area style-two all-products">
                            <div class="table-responsive">
                                <table class="table align-middle" id="basic_tables">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">User Image</th>
                                            <th scope="col">User Name</th>
                                            <th scope="col">User TagLine</th>
                                            <th scope="col">Start</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div
                                class="d-flex justify-content-center justify-content-sm-between align-items-center text-center flex-wrap gap-2 showing-wrap">
                                <span class="fs-12 fw-medium"></span>

                                <nav aria-label="Page navigation example">
                                    <ul class="pagination mb-0 justify-content-center">
                                        <li class="page-item">
                                            <a class="page-link icon" aria-label="Previous" href="#" id="prevPage">
                                                <i class="material-symbols-outlined">keyboard_arrow_left</i>
                                            </a>
                                        </li>
                                        <!-- Pagination Container !-->
                                        <li class="row " id="customPagination">
                                        </li>
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

        {{-- ---------------  --}}

    </div>

@endsection

@push('scripts')
    <script src="{{ asset('backend/admin/assets/datatables/data-tables.min.js') }}"></script>
    <!--buttons dataTables-->
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
                lengthMenu: [
                    [10, 25, 50, 100, 200, 500, -1],
                    [10, 25, 50, 100, 200, 500, "All"]
                ],
                processing: true,
                responsive: true,
                serverSide: true,
                paging: true, // Disable built-in pagination
                language: {
                    lengthMenu: `<span style="margin-left: 20px;">Show _MENU_ entries</span>`,
                    processing: `<div class="text-center">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>`
                },
                scroller: {
                    loadingIndicator: false
                },
                // Remove the default search box
                dom: "<'row justify-content-between table-topbar'<'col-md-6 col-sm-4 px-0'l>>tir",

                ajax: {
                    url: "{{ route('cms.home_page.testimonial_section.index') }}",
                    type: "get",
                    // success: function(data) {
                    //     console.log(data);
                    // }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false,
                    },
                                        {
                        data: 'sub_title',
                        name: 'sub_title',
                        orderable: true,
                        searchable: true,
                        render: function(data, type, row) {
                            if (data.length > 50) {
                                return data.substring(0, 50) + '...';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        data: 'sub_description',
                        name: 'sub_description',
                        orderable: true,
                        searchable: true,
                        render: function(data, type, row) {
                            if (data.length > 50) {
                                return data.substring(0, 50) + '...';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        data: 'title',
                        name: 'title',
                        orderable: true,
                        searchable: true,
                        render: function(data, type, row) {
                            if (data.length > 50) {
                                return data.substring(0, 50) + '...';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        data: 'description',
                        name: 'description',
                        orderable: true,
                        searchable: true,
                        render: function(data, type, row) {
                            if (data.length > 50) {
                                return data.substring(0, 50) + '...';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                drawCallback: function(settings) {
                    const totalPages = Math.ceil(settings._iRecordsDisplay / settings._iDisplayLength);;
                    const currentPage = settings._iDisplayStart / settings._iDisplayLength + 1;
                    updateCustomPagination(totalPages, currentPage);
                }
            });

            $('#customSearchBox').on('keyup', function() {
                dTable.search(this.value).draw();
            });

            $('#customSearchBox').on('keyup', function() {
                dTable.search(this.value).draw();
            });


            // Custom pagination logic with ellipsis
            function updateCustomPagination(totalPages, currentPage) {
                const paginationContainer = $('#customPagination');
                paginationContainer.empty();

                const maxVisiblePages = 5; // Number of visible pages before and after the current page
                let startPage, endPage;

                // Determine the start and end page range
                if (totalPages <= maxVisiblePages) {
                    startPage = 1;
                    endPage = totalPages;
                } else {
                    if (currentPage <= Math.floor(maxVisiblePages / 2)) {
                        startPage = 1;
                        endPage = maxVisiblePages;
                    } else if (currentPage + Math.floor(maxVisiblePages / 2) >= totalPages) {
                        startPage = totalPages - maxVisiblePages + 1;
                        endPage = totalPages;
                    } else {
                        startPage = currentPage - Math.floor(maxVisiblePages / 2);
                        endPage = currentPage + Math.floor(maxVisiblePages / 2);
                    }
                }

                // Add first page and ellipsis if needed
                if (startPage > 1) {
                    paginationContainer.append(
                        ` <li class="page-item col-1"><a class="page-link active" href="#" data-page="1">1</a></li>`
                    );
                    if (startPage > 2) {
                        paginationContainer.append(`<span class="ellipsis">...</span>`);
                    }
                }

                // Add the visible page range
                for (let i = startPage; i <= endPage; i++) {
                    paginationContainer.append(
                        ` <li class="page-item col-1"><a class="pagination-item page-link ${i === currentPage ? 'active' : ''}" href="#" data-page="${i}">${i}</a></li>`
                    );
                }

                // Add ellipsis and last page if needed
                if (endPage < totalPages) {
                    if (endPage < totalPages - 1) {
                        paginationContainer.append(`<span class="ellipsis">...</span>`);
                    }
                    paginationContainer.append(
                        `<li class="page-item col-1"><a class="pagination-item page-link "  data-page="${totalPages}">${totalPages}</a></li>`
                    );
                }

                // Click event for pagination items
                $('.pagination-item').on('click', function(e) {
                    e.preventDefault();
                    console.log('pagination-item')
                    const page = $(this).data('page');
                    if (!$(this).hasClass('disabled')) {
                        dTable.page(page - 1).draw('page'); // DataTables is 0-based index, so subtract 1
                    }
                });

                // Click event for 'Previous' button
                $('#prevPage').off().on('click', function(e) {
                    e.preventDefault();
                    if (currentPage > 1) {
                        dTable.page(currentPage - 2).draw('page');
                    }
                });

                // Click event for 'Next' button
                $('#nextPage').off().on('click', function(e) {
                    e.preventDefault();
                    // console.log('nextPage')
                    if (currentPage < totalPages) {
                        dTable.page(currentPage).draw('page');
                    }
                });
            }

        });
    </script>
    <script src="{{ asset('backend/admin/assets/custom-actions.js') }}"></script>
    <script>
        // Use the status change alert
        function changeStatus(event, id) {
            event.preventDefault();
            let statusUrl = '{{ route('cms.home_page.testimonial_section.status', ':id') }}';
            showStatusChangeAlert(id, statusUrl);
        }

        // Use the delete confirm alert
        function deleteRecord(event, id) {
            event.preventDefault();
            let deleteUrl = '{{ route('cms.home_page.testimonial_section.destroy', ':id') }}';
            showDeleteConfirm(id, deleteUrl);
        }
    </script>
@endpush
