@extends('backend.app')

@section('title')
    Dashboard
@endsection

@push('styles')
@endpush

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="row">
            <div class="col-md-7">
                <div class="mb-4">
                    <h3 class="fs-20 fw-semibold mb-1">Welcome Back, <span class="text-primary">{{ Auth::user()->name ?? 'Mr. John Doe' }}!</span>
                    </h3>
                    <p style="line-height: 1.4;">Monitor and manage coaches stucture, categories and more
                        in one place.</p>
                </div>
            </div>
            {{-- <div class="col-md-5">
                <div class="d-flex flex-wrap gap-2 justify-content-md-end mb-4">
                    <a href="pricing-plan.html" class="btn d-flex align-items-center gap-1"
                        style="background-color: #F3E8FF; color: #7C24CC; padding: 3.5px 11px;">
                        <i class="ri-vip-crown-line fs-18 lh-1" style="color: #7C24CC;"></i>
                        <span>Plan Upgrade</span>
                    </a>
                    <button class="btn d-flex align-items-center gap-1"
                        style="background-color: #FFE8D4; color: #C52B09; padding: 3.5px 11px;">
                        <i class="ri-file-download-line fs-18 lh-1 position-relative top-1" style="color: #C52B09;"></i>
                        <span>Export Reports</span>
                    </button>
                </div>
            </div> --}}
        </div>
        <div class="row">
            {{-- <div class="col-lg-4 col-md-6">
                <div class="card border-0 rounded-3 bg-white mb-4">
                    <div class="custom-padding-30 position-relative">
                        <div class="d-flex align-items-center mb-4 pb-2">
                            <div class="flex-shrink-0">
                                <div class="text-center rounded-2 bg-primary-50"
                                    style="width: 44px; height: 44px; line-height: 44px;">
                                    <img src="{{ asset('backend/admin/assets/images/icon-employees.svg') }}"
                                        alt="icon-employees">
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <span class="d-block mb-1">Total Employees</span>
                                <h3 class="fw-medium fs-20 mb-0">15,720</h3>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <i class="ri-arrow-right-up-line d-inline-block text-center rounded-1 fs-18 text-success-50"
                                style="width: 26px; height: 26px; line-height: 26px; background-color: #D8FFC8;"></i>
                            <p class="ms-2"><span class="text-secondary fw-medium">+12%</span> last year</p>
                        </div>

                        <div id="total_employees" class="chart-position top-50 translate-middle-y"></div>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="col-lg-4 col-md-6">
                <div class="card border-0 rounded-3 bg-white mb-4">
                    <div class="custom-padding-30 position-relative">
                        <div class="d-flex align-items-center mb-4 pb-2">
                            <div class="flex-shrink-0">
                                <div class="text-center rounded-2 bg-danger-50"
                                    style="width: 44px; height: 44px; line-height: 44px;">
                                    <img src="{{ asset('backend/admin/assets/images/icon-resigned.svg') }}"
                                        alt="icon-resigned">
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <span class="d-block mb-1">Resigned Employees</span>
                                <h3 class="fw-medium fs-20 mb-0">3,18</h3>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <i class="ri-arrow-right-down-line d-inline-block text-center rounded-1 fs-18 text-danger-50"
                                style="width: 26px; height: 26px; line-height: 26px; background-color: #FFE8D4;"></i>
                            <p class="ms-2"><span class="text-secondary fw-medium">-5%</span> last year</p>
                        </div>

                        <div id="resigned_employees" class="chart-position top-50 translate-middle-y"></div>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="col-lg-4 col-md-12">
                <div class="card border-0 rounded-3 bg-white mb-4">
                    <div class="custom-padding-30 position-relative">
                        <div class="d-flex align-items-center mb-4 pb-2">
                            <div class="flex-shrink-0">
                                <div class="text-center rounded-2 bg-primary-div-50"
                                    style="width: 44px; height: 44px; line-height: 44px;">
                                    <img src="{{ asset('backend/admin/assets') }}/images/icon-employees.svg"
                                        alt="icon-employees">
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <span class="d-block mb-1">New Employees</span>
                                <h3 class="fw-medium fs-20 mb-0">8,24</h3>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <i class="ri-arrow-right-up-line d-inline-block text-center rounded-1 fs-18 text-success-50"
                                style="width: 26px; height: 26px; line-height: 26px; background-color: #D8FFC8;"></i>
                            <p class="ms-2"><span class="text-secondary fw-medium">+10%</span> last year</p>
                        </div>

                        <div id="new_employees" class="chart-position top-50 translate-middle-y me-1 mt-2">
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>

        {{-- <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-0">
                <div
                    class="d-flex justify-content-between align-items-center flex-wrap gap-3 custom-padding-30 border-bottom pb-4">
                    <h3 class="mb-0">Employee List</h3>
                    <div class="d-flex align-items-center">
                        <form class="position-relative table-src-form">
                            <input type="text" id="SearchControl" class="form-control border-0" style="width: 265px;"
                                placeholder="Search for a name....">
                            <i
                                class="material-symbols-outlined position-absolute top-50 start-0 translate-middle-y">search</i>
                        </form>
                        <div class="dropdown action-opt">
                            <button class="btn bg-transparent p-0" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i data-feather="more-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end bg-white border box-shadow">
                                <li>
                                    <a class="dropdown-item" href="javascript:;">
                                        <i data-feather="clock"></i>
                                        Today
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:;">
                                        <i data-feather="pie-chart"></i>
                                        Last 7 Days
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:;">
                                        <i data-feather="rotate-cw"></i>
                                        Last Month
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:;">
                                        <i data-feather="calendar"></i>
                                        Last 1 Year
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:;">
                                        <i data-feather="bar-chart"></i>
                                        All Time
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:;">
                                        <i data-feather="eye"></i>
                                        View
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:;">
                                        <i data-feather="trash"></i>
                                        Delete
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="default-table-area style-three employee-list for-data-table">
                    <div class="table-responsive">
                        <table class="table align-middle border-0" id="myTable">
                            <thead class="border-bottom">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Employee</th>
                                    <th scope="col">Department</th>
                                    <th scope="col">Position</th>
                                    <th scope="col">Joining Date</th>
                                    <th scope="col">Salary</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-medium">EMP001</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('backend/admin/assets') }}/images/user-6.jpg"
                                                    class="rounded-circle" alt="user">
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <h4 class="fs-14 fw-medium mb-0">Olivia Turner</h4>
                                                <span class="fs-12 text-body">olivia@gmail.com</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Marketing</td>
                                    <td>Marketing Lead</td>
                                    <td>Jan 15, 2020</td>
                                    <td>$85,000</td>
                                    <td>
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success p-2 fs-12 fw-normal">Active</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-primary">visibility</i>
                                            </button>
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-body">edit</i>
                                            </button>
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-danger">delete</i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">EMP002</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('backend/admin/assets') }}/images/user-7.jpg"
                                                    class="rounded-circle" alt="user">
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <h4 class="fs-14 fw-medium mb-0">Liam Bennett</h4>
                                                <span class="fs-12 text-body">liam@gmail.com</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Human Resources</td>
                                    <td>HR Manager</td>
                                    <td>Mar 10, 2021</td>
                                    <td>$75,000</td>
                                    <td>
                                        <span
                                            class="badge bg-primary-div bg-opacity-10 text-primary-div p-2 fs-12 fw-normal">On
                                            Leave</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-primary">visibility</i>
                                            </button>
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-body">edit</i>
                                            </button>
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-danger">delete</i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">EMP003</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('backend/admin/assets') }}/images/user-8.jpg"
                                                    class="rounded-circle" alt="user">
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <h4 class="fs-14 fw-medium mb-0">Sophia Myers</h4>
                                                <span class="fs-12 text-body">sophia@gmail.com</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>IT</td>
                                    <td>Software Engineer</td>
                                    <td>Feb 22, 2019</td>
                                    <td>$95,000</td>
                                    <td>
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success p-2 fs-12 fw-normal">Active</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-primary">visibility</i>
                                            </button>
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-body">edit</i>
                                            </button>
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-danger">delete</i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">EMP004</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('backend/admin/assets') }}/images/user-9.jpg"
                                                    class="rounded-circle" alt="user">
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <h4 class="fs-14 fw-medium mb-0">Ethan Collins</h4>
                                                <span class="fs-12 text-body">ethan@gmail.com</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Sales</td>
                                    <td>Sales Manager</td>
                                    <td>Apr 12, 2022</td>
                                    <td>$90,000</td>
                                    <td>
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success p-2 fs-12 fw-normal">Active</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-primary">visibility</i>
                                            </button>
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-body">edit</i>
                                            </button>
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-danger">delete</i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">EMP005</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('backend/admin/assets') }}/images/user-10.jpg"
                                                    class="rounded-circle" alt="user">
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <h4 class="fs-14 fw-medium mb-0">Isabella Reed</h4>
                                                <span class="fs-12 text-body">isabella@gmail.com</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Finance</td>
                                    <td>Financial Analyst</td>
                                    <td>Aug 05, 2020</td>
                                    <td>$80,000</td>
                                    <td>
                                        <span
                                            class="badge bg-danger bg-opacity-10 text-danger p-2 fs-12 fw-normal">Resigned</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-primary">visibility</i>
                                            </button>
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-body">edit</i>
                                            </button>
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-danger">delete</i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">EMP006</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('backend/admin/assets') }}/images/user-11.jpg"
                                                    class="rounded-circle" alt="user">
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <h4 class="fs-14 fw-medium mb-0">Sophia Myers</h4>
                                                <span class="fs-12 text-body">sophia@gmail.com</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>IT</td>
                                    <td>Software Engineer</td>
                                    <td>Feb 22, 2019</td>
                                    <td>$95,000</td>
                                    <td>
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success p-2 fs-12 fw-normal">Active</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-primary">visibility</i>
                                            </button>
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-body">edit</i>
                                            </button>
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-danger">delete</i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">EMP007</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('backend/admin/assets') }}/images/user-12.jpg"
                                                    class="rounded-circle" alt="user">
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <h4 class="fs-14 fw-medium mb-0">Isabella Reed</h4>
                                                <span class="fs-12 text-body">isabella@gmail.com</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Finance</td>
                                    <td>Financial Analyst</td>
                                    <td>Aug 05, 2020</td>
                                    <td>$80,000</td>
                                    <td>
                                        <span
                                            class="badge bg-danger bg-opacity-10 text-danger p-2 fs-12 fw-normal">Resigned</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-primary">visibility</i>
                                            </button>
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-body">edit</i>
                                            </button>
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-danger">delete</i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">EMP008</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('backend/admin/assets') }}/images/user-13.jpg"
                                                    class="rounded-circle" alt="user">
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <h4 class="fs-14 fw-medium mb-0">Olivia Turner</h4>
                                                <span class="fs-12 text-body">olivia@gmail.com</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Marketing</td>
                                    <td>Marketing Lead</td>
                                    <td>Jan 15, 2020</td>
                                    <td>$85,000</td>
                                    <td>
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success p-2 fs-12 fw-normal">Active</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-primary">visibility</i>
                                            </button>
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-body">edit</i>
                                            </button>
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-danger">delete</i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">EMP009</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('backend/admin/assets') }}/images/user-14.jpg"
                                                    class="rounded-circle" alt="user">
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <h4 class="fs-14 fw-medium mb-0">Liam Bennett</h4>
                                                <span class="fs-12 text-body">liam@gmail.com</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Human Resources</td>
                                    <td>HR Manager</td>
                                    <td>Mar 10, 2021</td>
                                    <td>$75,000</td>
                                    <td>
                                        <span
                                            class="badge bg-primary-div bg-opacity-10 text-primary-div p-2 fs-12 fw-normal">On
                                            Leave</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-primary">visibility</i>
                                            </button>
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-body">edit</i>
                                            </button>
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-danger">delete</i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">EMP0010</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('backend/admin/assets') }}/images/user-15.jpg"
                                                    class="rounded-circle" alt="user">
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <h4 class="fs-14 fw-medium mb-0">Ethan Collins</h4>
                                                <span class="fs-12 text-body">ethan@gmail.com</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Sales</td>
                                    <td>Sales Manager</td>
                                    <td>Apr 12, 2022</td>
                                    <td>$90,000</td>
                                    <td>
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success p-2 fs-12 fw-normal">Active</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-primary">visibility</i>
                                            </button>
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-body">edit</i>
                                            </button>
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                <i class="material-symbols-outlined fs-18 text-danger">delete</i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
@endsection


@push('scripts')
@endpush
