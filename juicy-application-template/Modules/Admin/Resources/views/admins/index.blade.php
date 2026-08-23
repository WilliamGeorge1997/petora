@extends('common::layouts.master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('') }}admin/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('') }}admin/vendors/css/tables/datatable/responsive.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('') }}admin/vendors/css/tables/datatable/buttons.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('') }}admin/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin/vendors/css/pickers/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('') }}admin/css-rtl/plugins/extensions/ext-component-sweet-alerts.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin/vendors/css/forms/select/select2.min.css">
@endsection
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">المديرين</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                            <li class="breadcrumb-item">ادارة العضويات</li>
                            <li class="breadcrumb-item active">المديرين</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <section id="filter-section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">فلتره المديرين</h4>
                    </div>
                    <div class="card-body">
                        <form id="filter-form">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-1">
                                        <label class="form-label" for="branch-filter">الفرع</label>
                                        <select id="branch-filter" name="branch_id" class="form-select">
                                            <option value="">جميع الفروع</option>
                                            @foreach ($viewModel->Branches() as $branch)
                                                <option {{ $branch->id == request('branch_id') ? 'selected' : '' }}
                                                    value="{{ $branch->id }}">
                                                    {{ $branch->getTranslations('title')['ar'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" id="apply-filter" class="btn btn-primary me-1">تطبيق الفلتره</button>
                                    @if(request()->has('branch_id') && request('branch_id') != '')
                                        <a href="{{ url()->current() }}" class="btn btn-outline-danger">ازاله الفلتره</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Basic table -->
    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <table class="datatables-basic table">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>id</th>
                                <th>الاسم</th>
                                <th>الوظيفة</th>
                                <th>اسم الفرع</th>
                                <th>الهاتف</th>
                                <th>التاريخ</th>
                                <th>الحالة</th>
                                <th>الاجراءات</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        @can('Create-admin')
            <!-- Modal to add new record -->
            {{--        <div class="modal modal-slide-in fade" id="modals-slide-in"> --}}
            {{--            <div class="modal-dialog sidebar-sm"> --}}
            {{--                <form class="add-new-record modal-content pt-0" enctype="multipart/form-data" method="post"> --}}
            {{--                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button> --}}
            {{--                    <div class="modal-header mb-1"> --}}
            {{--                        <h5 class="modal-title" id="exampleModalLabel">New Record</h5> --}}
            {{--                    </div> --}}
            {{--                    <div class="modal-body flex-grow-1"> --}}
            {{--                        <div class="mb-1"> --}}
            {{--                            <label class="form-label" for="basic-icon-default-fullname">Name</label> --}}
            {{--                            <input type="text" class="form-control dt-full-name" id="basic-icon-default-fullname" placeholder="John Doe" aria-label="John Doe" /> --}}
            {{--                        </div> --}}
            {{--                        <div class="mb-1"> --}}
            {{--                            <label class="form-label" for="basic-icon-default-email">Email</label> --}}
            {{--                            <input type="text" id="basic-icon-default-email" class="form-control dt-email" placeholder="john.doe@example.com" aria-label="john.doe@example.com" /> --}}
            {{--                            <small class="form-text"> You can use letters, numbers & periods </small> --}}
            {{--                        </div> --}}
            {{--                        <div class="mb-1"> --}}
            {{--                            <label class="form-label" for="basic-icon-default-post">Password</label> --}}
            {{--                            <input type="password" id="basic-icon-default-post" class="form-control dt-password" placeholder="password" /> --}}
            {{--                        </div> --}}

            {{--                        <div class="mb-1"> --}}
            {{--                            <label class="form-label" for="basic-icon-default-date">Phone</label> --}}
            {{--                            <input type="text" class="form-control dt-phone" id="basic-icon-default-date" placeholder="01xxxxxx" /> --}}
            {{--                        </div> --}}

            {{--                        <div class="mb-1"> --}}
            {{--                            <label class="form-label" for="basic-icon-default-date">Image</label> --}}
            {{--                            <input type="file" class="form-control dt-image" id="formFile" /> --}}
            {{--                        </div> --}}

            {{--                        <div class="mb-1"> --}}
            {{--                            <label class="form-label" for="basic-icon-default-date">Role</label> --}}
            {{--                            <select class="select2 form-select dt-role" id="select2-basic" name="role"> --}}
            {{--                                @foreach ($roles as $role) --}}
            {{--                                <option value="{{$role['id']}}">{{$role['name']}}</option> --}}
            {{--                                @endforeach --}}
            {{--                            </select> --}}
            {{--                        </div> --}}
            {{--                        <div class="mb-4"> --}}
            {{--                            <label class="form-label" for="basic-icon-default-salary">Salary</label> --}}
            {{--                            <input type="text" id="basic-icon-default-salary" class="form-control dt-salary" placeholder="$12000" aria-label="$12000" /> --}}
            {{--                        </div> --}}
            {{--                        <button type="button" class="btn btn-primary data-submit me-1">Submit</button> --}}
            {{--                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button> --}}
            {{--                    </div> --}}
            {{--                </form> --}}
            {{--            </div> --}}
            {{--        </div> --}}
        @endcan
    </section>
    <!--/ Basic table -->
    {{ $admins->withQueryString()->links() }}
@endsection


@section('js')
    @include('common::includes.datatable')

    {{--    <script src="{{asset('')}}admin/js/scripts/tables/table-datatables-basic.js"></script> --}}

    {{-- <script src="{{asset('')}}js/admins/script.js"></script> --}}

    <script>
        var select = $('.select2');

        select.each(function() {
            var $this = $(this);
            $this.wrap('<div class="position-relative"></div>');
            $this.select2({
                // the following code is used to disable x-scrollbar when click in select input and
                // take 100% width in responsive also
                dropdownAutoWidth: true,
                width: '100%',
                dropdownParent: $this.parent()
            });
        });
    </script>

    @if (session('created'))
        <script>
            Swal.fire({
                title: 'أحسنت!',
                text: 'لقد تم انشاء الادمن بنجاح',
                icon: 'success',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            });
        </script>
    @endif

    @if (session('updated'))
        <script>
            Swal.fire({
                title: 'أحسنت!',
                text: 'لقد تم تعديل الادمن بنجاح',
                icon: 'success',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            });
        </script>
    @endif

    <script>
        $(function() {
            'use strict';
            var dt_basic_table = $('.datatables-basic'),
                dt_date_table = $('.dt-date');
            if (dt_basic_table.length) {
                var url = new URL(window.location.href);
                var ajaxRequest = 'admins?';
                var page = url.searchParams.get("page");
                var branch_id = url.searchParams.get("branch_id");
                if (page) ajaxRequest += 'page=' + page + '&';
                if (branch_id) ajaxRequest += 'branch_id=' + branch_id + '&';

                var dt_basic = dt_basic_table.DataTable({
                    ajax: ajaxRequest,
                    searching: false,
                    columns: [{
                            data: 'id'
                        }, // for responsive show
                        {
                            data: 'id'
                        }, // for checkbox
                        {
                            data: 'id'
                        }, // used for sorting so will hide this column
                        {
                            data: 'name'
                        },
                        {
                            data: 'roles'
                        },
                        {
                            data: 'branch.title',
                            render: function(data) {
                                if (!data) return '-';
                                let fullTitle = data.ar + ' - ' + data.en;
                                const maxLength = 30;
                                let title = fullTitle.length > maxLength ? fullTitle.substring(0,
                                    maxLength) + '...' : fullTitle;
                                return `<span data-bs-toggle="tooltip" data-bs-placement="top" title="${fullTitle}" data-bs-title="${fullTitle}">${title}</span>`
                            }
                        },
                        {
                            data: 'phone'
                        },
                        {
                            data: 'created_at'
                        },
                        {
                            data: 'Status'
                        },
                        {
                            data: 'id'
                        },
                    ],
                    columnDefs: [{
                            // For Responsive
                            className: 'control',
                            orderable: false,
                            responsivePriority: 2,
                            targets: 0
                        },
                        {
                            // For Checkboxes
                            targets: 1,
                            orderable: false,
                            responsivePriority: 3,
                            render: function(data, type, full, meta) {
                                return (
                                    '<div class="form-check"> <input class="form-check-input dt-checkboxes" type="checkbox" value="" id="checkbox' +
                                    data +
                                    '" /><label class="form-check-label" for="checkbox' +
                                    data +
                                    '"></label></div>'
                                );
                            },
                            checkboxes: {
                                selectAllRender: '<div class="form-check"> <input class="form-check-input" type="checkbox" value="" id="checkboxSelectAll" /><label class="form-check-label" for="checkboxSelectAll"></label></div>'
                            }
                        },
                        {
                            targets: 2,
                            visible: false
                        },
                        {
                            // Avatar image/badge, Name and email
                            targets: 3,
                            responsivePriority: 4,
                            render: function(data, type, full, meta) {
                                var $user_img = full['image'],
                                    $name = data,
                                    $email = full['email'];
                                if ($user_img) {
                                    var $user_img = window.location.origin + '/uploads/admin/' +
                                        $user_img;
                                    // For Avatar image
                                    var $output =
                                        '<img src="' + $user_img +
                                        '" alt="Avatar" width="32" height="32">';
                                } else {
                                    // For Avatar badge
                                    var stateNum = full['is_active'];
                                    var states = ['info', 'primary'];
                                    var $state = states[stateNum],
                                        $name = full['name'],
                                        $initials = $name.match(/\b\w/g) || [];
                                    $initials = (($initials.shift() || '') + ($initials.pop() ||
                                        '')).toUpperCase();
                                    $output = '<span class="avatar-content">' + $initials +
                                        '</span>';
                                }

                                var colorClass = $user_img === null ? ' bg-light-' + $state + ' ' :
                                    '';
                                // Creates full output for row
                                var $row_output =
                                    '<div class="d-flex justify-content-left align-items-center">' +
                                    '<div class="avatar ' +
                                    colorClass +
                                    ' me-1">' +
                                    $output +
                                    '</div>' +
                                    '<div class="d-flex flex-column">' +
                                    '<span class="emp_name text-truncate fw-bold">' +
                                    $name +
                                    '</span>' +
                                    '<small class="emp_post text-truncate text-muted">' +
                                    $email +
                                    '</small>' +
                                    '</div>' +
                                    '</div>';
                                return $row_output;
                            }
                        },
                        {
                            responsivePriority: 1,
                            targets: 4,
                            render: function(data, type, full, meta) {
                                var $role_name = full['roles'][0]['name'];

                                var $row_output =
                                    '<div class="d-flex justify-content-left align-items-center">' +
                                    '<div class="d-flex flex-column">' +
                                    '<span class="emp_name text-truncate fw-bold">' +
                                    $role_name +
                                    '</span>' +
                                    '</div>' +
                                    '</div>';
                                return $row_output;
                            }
                        },
                        {
                            // Label
                            targets: -2,
                            render: function(data, type, full, meta) {
                                var $status_number = full['is_active'];
                                var $status = {
                                    0: {
                                        title: 'غير مفعل',
                                        class: 'badge-light-danger'
                                    },
                                    1: {
                                        title: 'مفعل',
                                        class: ' badge-light-success'
                                    },
                                };
                                if (typeof $status[$status_number] === 'undefined') {
                                    return data;
                                }
                                return (
                                    '<span class="badge rounded-pill ' +
                                    $status[$status_number].class +
                                    '">' +
                                    $status[$status_number].title +
                                    '</span>'
                                );
                            }
                        },
                        {
                            // Actions
                            targets: -1,
                            title: 'الاجراءات',
                            orderable: false,
                            render: function(data, type, full, meta) {
                                var activation = '';
                                if (full['is_active'] == 0) activation = 'تفعيل';
                                else activation = 'الغاء التفعيل';
                                return (
                                    '<div class="d-inline-flex">' +
                                    '<a class="pe-1 dropdown-toggle hide-arrow text-primary" data-bs-toggle="dropdown">' +
                                    feather.icons['more-vertical'].toSvg({
                                        class: 'font-small-4'
                                    }) +
                                    '</a>' +
                                    '<div class="dropdown-menu dropdown-menu-end">' +
                                    @can('Edit-admin')
                                        '<a href="admins/activate/' + data +
                                            '" class="dropdown-item">' +
                                            feather.icons['file-text'].toSvg({
                                                class: 'font-small-4 me-50'
                                            }) +
                                            activation + '</a>'
                                    @endcan
                                    @can('Delete-admin')
                                        +
                                        '<a href="javascript:;" class="dropdown-item delete-record">' +
                                        feather.icons['trash-2'].toSvg({
                                                class: 'font-small-4 me-50'
                                            }) +
                                            'حذف</a>'
                                    @endcan +
                                    '</div>' +
                                    '</div>'
                                    @can('Edit-admin')
                                        +
                                        '<a href="admins/' + data +
                                            '/edit" class="item-edit">' +
                                            feather.icons['edit'].toSvg({
                                                class: 'font-small-4'
                                            }) +
                                            '</a>'
                                    @endcan
                                );
                            }
                        }
                    ],
                    order: [
                        [2, 'desc']
                    ],
                    dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    displayLength: 50,
                    lengthMenu: [7, 10, 25, 50, 75, 100],
                    buttons: [{
                            extend: 'collection',
                            className: 'btn btn-outline-secondary dropdown-toggle me-2',
                            text: feather.icons['share'].toSvg({
                                class: 'font-small-4 me-50'
                            }) + 'Export',
                            buttons: [{
                                    extend: 'print',
                                    text: feather.icons['printer'].toSvg({
                                        class: 'font-small-4 me-50'
                                    }) + 'Print',
                                    className: 'dropdown-item',
                                    exportOptions: {
                                        columns: [3, 4, 5, 6, 7]
                                    }
                                },
                                {
                                    extend: 'csv',
                                    text: feather.icons['file-text'].toSvg({
                                        class: 'font-small-4 me-50'
                                    }) + 'Csv',
                                    className: 'dropdown-item',
                                    exportOptions: {
                                        columns: [3, 4, 5, 6, 7]
                                    }
                                },
                                {
                                    extend: 'excel',
                                    text: feather.icons['file'].toSvg({
                                        class: 'font-small-4 me-50'
                                    }) + 'Excel',
                                    className: 'dropdown-item',
                                    exportOptions: {
                                        columns: [3, 4, 5, 6, 7]
                                    }
                                },
                                {
                                    extend: 'pdf',
                                    text: feather.icons['clipboard'].toSvg({
                                        class: 'font-small-4 me-50'
                                    }) + 'Pdf',
                                    className: 'dropdown-item',
                                    exportOptions: {
                                        columns: [3, 4, 5, 6, 7]
                                    }
                                },
                                {
                                    extend: 'copy',
                                    text: feather.icons['copy'].toSvg({
                                        class: 'font-small-4 me-50'
                                    }) + 'Copy',
                                    className: 'dropdown-item',
                                    exportOptions: {
                                        columns: [3, 4, 5, 6, 7]
                                    }
                                }
                            ],
                            init: function(api, node, config) {
                                $(node).removeClass('btn-secondary');
                                $(node).parent().removeClass('btn-group');
                                setTimeout(function() {
                                    $(node).closest('.dt-buttons').removeClass('btn-group')
                                        .addClass('d-inline-flex');
                                }, 50);
                            }
                        },
                        {
                            text: feather.icons['user-check'].toSvg({
                                class: 'font-small-4 me-50'
                            }) + 'الوظائف',
                            className: 'create-new btn btn-warning me-2',
                            // attr: {
                            //     'data-bs-toggle': 'modal',
                            //     'data-bs-target': '#modals-slide-in'
                            // },
                            action: function(e, dt, node, config) {
                                //This will send the page to the location specified
                                window.location.href = './roles';
                            },
                            init: function(api, node, config) {
                                $(node).removeClass('btn-secondary');
                            }
                        },
                        @can('Create-admin')


                            {
                                text: feather.icons['plus'].toSvg({
                                    class: 'me-50 font-small-4'
                                }) + 'اضافة جديد',
                                className: 'create-new btn btn-primary',
                                // attr: {
                                //     'data-bs-toggle': 'modal',
                                //     'data-bs-target': '#modals-slide-in'
                                // },
                                action: function(e, dt, node, config) {
                                    //This will send the page to the location specified
                                    window.location.href = './admins/create';
                                },
                                init: function(api, node, config) {
                                    $(node).removeClass('btn-secondary');
                                }
                            }
                        @endcan
                    ],
                    responsive: {
                        details: {
                            display: $.fn.dataTable.Responsive.display.modal({
                                header: function(row) {
                                    var data = row.data();
                                    return 'Details of ' + data['name'];
                                }
                            }),
                            type: 'column',
                            renderer: function(api, rowIdx, columns) {
                                var data = $.map(columns, function(col, i) {
                                    return col.title !==
                                        '' // ? Do not show row in modal popup if title is blank (for check box)
                                        ?
                                        '<tr data-dt-row="' +
                                        col.rowIdx +
                                        '" data-dt-column="' +
                                        col.columnIndex +
                                        '">' +
                                        '<td>' +
                                        col.title +
                                        ':' +
                                        '</td> ' +
                                        '<td>' +
                                        col.data +
                                        '</td>' +
                                        '</tr>' :
                                        '';
                                }).join('');

                                return data ? $('<table class="table"/>').append('<tbody>' + data +
                                    '</tbody>') : false;
                            }
                        }
                    },
                    language: {
                        paginate: {
                            // remove previous & next text from pagination
                            previous: '&nbsp;',
                            next: '&nbsp;'
                        }
                    }
                });
                $('div.head-label').html('<h6 class="mb-0">المديرين</h6>');
            }

            // Flat Date picker
            if (dt_date_table.length) {
                dt_date_table.flatpickr({
                    monthSelectorType: 'static',
                    dateFormat: 'm/d/Y'
                });
            }

            // Add New record
            // ? Remove/Update this code as per your requirements ?
            var count = 101;
            $('.data-submit').on('click', function() {
                var $name = $('.add-new-record .dt-full-name').val(),
                    $password = $('.add-new-record .password').val(),
                    $email = $('.add-new-record .dt-email').val(),
                    $phone = $('.add-new-record .dt-phone').val(),
                    $role = $('.add-new-record .dt-role').val(),
                    $image = $('#formFile')[0].files;
                // $new_salary = $('.add-new-record .dt-salary').val();

                if ($name != '') {
                    $('.modal').modal('hide');
                    var token = $("meta[name='csrf-token']").attr("content");
                    var formData = new FormData();
                    formData.append('name', $name);
                    formData.append('email', $email);
                    formData.append('phone', $phone);
                    formData.append('role', $role);
                    formData.append('is_active', 1);
                    formData.append('password', $password);
                    formData.append('image', $image[0]);
                    formData.append('_token', token);
                    console.log(formData);
                    $.ajax({
                        url: "admins",
                        type: 'POST',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            dt_basic.row
                                .add({
                                    responsive_id: null,
                                    id: response.data.id,
                                    name: $name,
                                    email: $email,
                                    phone: $phone,
                                    roles: response.data.roles,
                                    created_at: new Date().toLocaleString(),
                                    is_active: 1
                                })
                                .draw();
                        }
                    });
                    Swal.fire({
                        title: 'أحسنت!',
                        text: 'لقد تم انشاء الادمن بنجاح',
                        icon: 'success',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });
                }
            });

            // Delete Record
            $('.datatables-basic tbody').on('click', '.delete-record', function() {
                let that = this;
                var id = dt_basic.row($(this).parents('tr')).data().id
                var token = $("meta[name='csrf-token']").attr("content");
                Swal.fire({
                    title: 'هل انت متأكد من الحذف ؟ ',
                    text: "لن تتمكن من التراجع عن هذا!",
                    cancelButtonText: 'الغاء',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'نعم ، احذفها!',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ms-1'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.isConfirmed) {
                        dt_basic.row($(that).parents('tr')).remove().draw();
                        $.ajax({
                            url: window.location.origin + "/admin/admins/" + id,
                            type: 'POST',
                            data: {
                                "id": id,
                                "_method": "DELETE",
                                "_token": token,
                            },
                            success: function() {}
                        });
                        if (result.value) {
                            Swal.fire({
                                icon: 'success',
                                title: 'تم الحذف!',
                                text: 'تم حذف الادمن.',
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
