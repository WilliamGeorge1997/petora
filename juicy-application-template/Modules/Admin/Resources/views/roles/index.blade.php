@extends('common::layouts.master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/vendors/css/tables/datatable/responsive.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/vendors/css/tables/datatable/buttons.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/vendors/css/pickers/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/css-rtl/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/vendors/css/extensions/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/css-rtl/plugins/extensions/ext-component-sweet-alerts.css">

@endsection
@section('content')



        <div class="content-body">
            <h3>الصلاحيات و الوظائف</h3>
            <p class="mb-2">
                يوفر أحد الأدوار إمكانية الوصول إلى القوائم والميزات المحددة مسبقًا بحيث يمكن للمسؤول ، بناءً على الدور المعين ، الوصول إلى ما يحتاج إليه
            </p>

            <!-- Role cards -->
            <div class="row">
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="d-flex align-items-end justify-content-center h-100">
                                    <img src="{{asset('')}}admin/images/illustration/faq-illustrations.svg" class="img-fluid mt-2" alt="Image" width="85" />
                                </div>
                            </div>
                            <div class="col-sm-7">
                                @can('Create-role')
                                <div class="card-body text-sm-end text-center ps-sm-0">
                                    <a href="javascript:void(0)" data-bs-target="#addRoleModal" data-bs-toggle="modal" class="stretched-link text-nowrap add-new-role">
                                        <span class="btn btn-primary mb-1">اضافة وظيفة جديدة </span>
                                    </a>
                                </div>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Role cards -->
            <h3 class="mt-50">جميع الوظائف</h3>
            <!-- table -->
            <div class="card">
                <div class="table-responsive">
                    <table class="user-list-table table">
                        <thead class="table-light">
                        <tr>
                            <th></th>
                            <th></th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- table -->
            <!-- Add Role Modal -->
            <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
                    <div class="modal-content">
                        <div class="modal-header bg-transparent">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-5 pb-5">
                            <div class="text-center mb-4">
                                <h1 class="role-title">اضف وظيفة جديدة</h1>
                                <p>تعيين صلاحيات الوظيفة</p>
                            </div>
                            <!-- Add role form -->
                            <form id="addRoleForm" class="row" onsubmit="return false">
                                <div class="col-12">
                                    <label class="form-label" for="modalRoleName">اسم الوظيفة</label>
                                    <input type="text" id="modalRoleName" name="modalRoleName" class="form-control" placeholder="Enter role name" tabindex="-1" data-msg="Please enter role name" />
                                </div>
                                <div class="col-12">
                                    <h4 class="mt-2 pt-50">صلاحيات الوظائف</h4>
                                    <!-- Permission table -->
                                    <div class="table-responsive">
                                        <table class="table table-flush-spacing">
                                            <tbody>
                                            <tr>
                                                <td class="text-nowrap fw-bolder">
                                                    Administrator Access
                                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="Allows a full access to the system">
                                                                <i data-feather="info"></i>
                                                            </span>
                                                </td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="selectAll" />
                                                        <label class="form-check-label" for="selectAll"> Select All </label>
                                                    </div>
                                                </td>
                                            </tr>
                                            @foreach($cat_permissions as $key => $permissions)
                                            <tr>
                                                <td class="text-nowrap fw-bolder">{{$key}}</td>
                                                <td>
                                                    <div class="d-flex">
                                                        @foreach($permissions as $permission)
                                                        <div class="form-check me-3 me-lg-5">
                                                            <input class="form-check-input" name="permission" value="{{$permission['id']}}" type="checkbox" />
                                                            <label class="form-check-label" for="userManagementRead"> {{$permission['display']}} </label>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Permission table -->
                                </div>
                                <div class="col-12 text-center mt-2">
                                    <button type="submit" class="btn btn-primary me-1 data-submit">Submit</button>
                                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                                        Discard
                                    </button>
                                </div>
                            </form>
                            <!--/ Add role form -->
                        </div>
                    </div>
                </div>
            </div>

            <!--/ Add Role Modal -->

        </div>


@endsection


@section('js')
    <script src="{{asset('')}}admin/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/tables/datatable/dataTables.bootstrap5.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/tables/datatable/responsive.bootstrap5.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/tables/datatable/buttons.bootstrap5.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/extensions/sweetalert2.all.min.js"></script>

<!-- BEGIN: Page JS-->
<script src="{{asset('')}}admin/js/scripts/pages/modal-add-role.js"></script>
<script src="{{asset('')}}admin/js/scripts/pages/app-access-roles.js"></script>
<!-- END: Page JS-->

    @if(session('updated'))
        <script>
            Swal.fire({
                title: 'أحسنت!',
                text: 'لقد تم تعديل الوظيفة بنجاح',
                icon: 'success',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            });
        </script>
    @endif
@endsection
