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
@endsection
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">المنتجات</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                            <li class="breadcrumb-item">ادارة المنتجات</li>
                            <li class="breadcrumb-item active">المنتجات</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-2">
        <div class="card-header">
            <h4 class="card-title mb-0">فلترة المنتجات</h4>
        </div>
        <div class="card-body">
            <form class="row g-3 align-items-center">
                @if (request()->filled('branch_id'))
                    <input type="hidden" name="branch_id" value="{{ request()->get('branch_id') }}">
                @endif
                <div class="col-lg-4">
                    <label for="title" class="form-label mb-1">اسم المنتج</label>
                    <input type="text" name="title" id="title" class="form-control"
                        value="{{ request()->get('title') }}" placeholder="ابحث باسم المنتج">
                </div>
                <div class="col-lg-3">
                    <label for="is_active" class="form-label mb-1">الحالة</label>
                    <select name="is_active" id="is_active" class="form-select">
                        <option value="">الكل</option>
                        <option value="1" {{ request()->get('is_active') === '1' ? 'selected' : '' }}>مفعل</option>
                        <option value="0"
                            {{ request()->has('is_active') && request()->get('is_active') == '0' ? 'selected' : '' }}>غير
                            مفعل</option>
                    </select>
                </div>
                <div class="col-auto align-self-end">
                    <button type="submit" class="btn btn-primary">بحث</button>
                </div>
                @if (
                    (request()->has('title') && request()->get('title') !== '') ||
                        (request()->has('branch_id') && request()->get('branch_id') !== '') ||
                        (request()->has('is_active') && request()->get('is_active') !== ''))
                    <div class="col-auto align-self-end">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">ازالة الفلترة</a>
                    </div>
                @endif
            </form>
        </div>
    </div>

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
                                <th>السعر</th>
                                <th>القسم</th>
                                <th>التاريخ</th>
                                <th>الحالة</th>
                                <th>الاجراءات</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!--/ Basic table -->

    {{ $products->withQueryString()->links() }}
@endsection


@section('js')
    @include('common::includes.datatable')


    {{-- <script src="{{asset('')}}admin/js/scripts/tables/table-datatables-basic.js"></script> --}}

    {{-- <script src="{{asset('')}}js/proucts/script.js"></script> --}}

    @if (session('updated'))
        <script>
            Swal.fire({
                title: 'أحسنت!',
                text: 'لقد تم تعديل المنتج بنجاح',
                icon: 'success',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            });
        </script>
    @endif

    @if (session('created'))
        <script>
            Swal.fire({
                title: 'أحسنت!',
                text: 'لقد تم انشاء المنتج بنجاح',
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
            var url = new URL(window.location.href);
            var page = url.searchParams.get('page');
            var title = url.searchParams.get('title');
            var is_active = url.searchParams.get('is_active');
            var ajaxRequest = 'products?';
            if (page) ajaxRequest += 'page=' + page + '&';
            if (title) ajaxRequest += 'title=' + title + '&';
            if (is_active !== null && is_active !== '') ajaxRequest += 'is_active=' + is_active + '&';
            @if (auth('admin')->user()->hasRole('Super Admin'))
                var branch_id = url.searchParams.get('branch_id');
                if (branch_id) ajaxRequest += 'branch_id=' + branch_id + '&';
            @endif
            var dt_basic_table = $('.datatables-basic'),
                dt_date_table = $('.dt-date');
            if (dt_basic_table.length) {
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
                            data: 'title'
                        },
                        {
                            data: 'price'
                        },
                        {
                            data: 'category.title',
                            render: function(data, type, full, meta) {
                                return data['ar'] + ' - ' + data['en'];
                            }
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
                                    $name = data['ar'] + ' - ' + data['en'];
                                if ($user_img) {
                                    // var $user_img = window.location.origin+'/uploads/product/'+$user_img;
                                    // For Avatar image
                                    var $output =
                                        '<img src="' + $user_img +
                                        '" alt="Avatar" width="32" height="32">';
                                } else {
                                    // For Avatar badge
                                    var stateNum = full['is_active'];
                                    var states = ['info', 'primary'];
                                    var $state = states[stateNum],
                                        $name = full['title']['ar'] + ' - ' + full['title']['en'],
                                        $initials = $name.match(/\b\w/g) || [];
                                    $initials = (($initials.shift() || '') + ($initials.pop() ||
                                        '')).toUpperCase();
                                    $output = '<span class="avatar-content">' + $initials +
                                        '</span>';
                                }

                                var colorClass = $user_img === null ? ' bg-light-' + $state + ' ' :
                                    '';
                                var $fullName = $name;
                                if ($name.length > 40) {
                                    $name = $name.substring(0, 40) + '...';
                                }
                                var titleAttr = $fullName ? ' title="' + $fullName.replace(/"/g,
                                    '&quot;') + '"' : '';
                                // Creates full output for row
                                var $row_output =
                                    '<div class="d-flex justify-content-left align-items-center">' +
                                    '<div class="avatar ' +
                                    colorClass +
                                    ' me-1">' +
                                    $output +
                                    '</div>' +
                                    '<div class="d-flex flex-column">' +
                                    '<span class="emp_name text-truncate fw-bold"' + titleAttr +
                                    '>' +
                                    $name +
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
                                    '<div class="dropdown-menu dropdown-menu-end">'
                                    @can('Edit-product')
                                        +
                                        '<a href="products/activate/' + data +
                                            '" class="dropdown-item">' +
                                            feather.icons['power'].toSvg({
                                                class: 'font-small-4 me-50'
                                            }) +
                                            activation + '</a>'
                                    @endcan +
                                    '<a href="product/' + data +
                                    '/add_attribute" class="dropdown-item">' +
                                    feather.icons['plus'].toSvg({
                                        class: 'font-small-4 me-50'
                                    }) +
                                    'اضافة المزيد من الخصائص' + '</a>' +
                                    '<a href="product/' + data +
                                    '/attributes" class="dropdown-item">' +
                                    feather.icons['database'].toSvg({
                                        class: 'font-small-4 me-50'
                                    }) +
                                    'جميع الخصائص' + '</a>'
                                    @can('Branch-product')
                                        +
                                        '<a href="product/' + data +
                                            '/sendBranch" class="dropdown-item">' +
                                            feather.icons['send'].toSvg({
                                                class: 'font-small-4 me-50'
                                            }) +
                                            'ارسال الي الفروع' + '</a>'
                                    @endcan
                                    @can('Delete-product')
                                        +
                                        '<a href="javascript:;" class="dropdown-item delete-record">' +
                                        feather.icons['trash-2'].toSvg({
                                                class: 'font-small-4 me-50'
                                            }) +
                                            'حذف</a>'
                                    @endcan +
                                    '</div>' +
                                    '</div>'
                                    @can('Edit-product')
                                        +
                                        '<a href="products/' + data +
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
                    buttons: [
                        // {
                        // extend: 'collection',
                        // className: 'btn btn-outline-secondary dropdown-toggle me-2',
                        // text: feather.icons['share'].toSvg({
                        //     class: 'font-small-4 me-50'
                        // }) + 'تصدير',
                        // buttons: [
                        //     // {
                        //     //     extend: 'print',
                        //     //     text: feather.icons['printer'].toSvg({
                        //     //         class: 'font-small-4 me-50'
                        //     //     }) + 'طباعة',
                        //     //     className: 'dropdown-item',
                        //     //     exportOptions: {
                        //     //         columns: [3, 4, 5, 6, 7]
                        //     //     }
                        //     // },
                        //     // {
                        //     //     extend: 'csv',
                        //     //     text: feather.icons['file-text'].toSvg({
                        //     //         class: 'font-small-4 me-50'
                        //     //     }) + 'CSV',
                        //     //     className: 'dropdown-item',
                        //     //     exportOptions: {
                        //     //         columns: [3, 4, 5, 6, 7]
                        //     //     }
                        //     // },
                        //     // {
                        //     //     extend: 'excel',
                        //     //     text: feather.icons['file'].toSvg({
                        //     //         class: 'font-small-4 me-50'
                        //     //     }) + 'EXCEL',
                        //     //     className: 'dropdown-item',
                        //     //     exportOptions: {
                        //     //         columns: [3, 4, 5, 6, 7]
                        //     //     }
                        //     // },
                        //     // {
                        //     //     extend: 'pdf',
                        //     //     text: feather.icons['clipboard'].toSvg({
                        //     //         class: 'font-small-4 me-50'
                        //     //     }) + 'PDF',
                        //     //     className: 'dropdown-item',
                        //     //     exportOptions: {
                        //     //         columns: [3, 4, 5, 6, 7]
                        //     //     }
                        //     // },
                        //     // {
                        //     //     extend: 'copy',
                        //     //     text: feather.icons['copy'].toSvg({
                        //     //         class: 'font-small-4 me-50'
                        //     //     }) + 'نسخ',
                        //     //     className: 'dropdown-item',
                        //     //     exportOptions: {
                        //     //         columns: [3, 4, 5, 6, 7]
                        //     //     }
                        //     // }
                        // ],
                        // init: function(api, node, config) {
                        //     $(node).removeClass('btn-secondary');
                        //     $(node).parent().removeClass('btn-group');
                        //     setTimeout(function() {
                        //         $(node).closest('.dt-buttons').removeClass('btn-group')
                        //             .addClass('d-inline-flex');
                        //     }, 50);
                        // }
                        // },
                        @can('Create-product')
                            // {
                            //     text: feather.icons['file'].toSvg({
                            //         class: 'me-50 font-small-4'
                            //     }) + 'تصدير المنتجات مع الخصائص',
                            //     className: 'export-new btn btn-outline-primary me-2',
                            //     attr: {
                            //         'data-bs-toggle': 'modal',
                            //         'data-bs-target': '#modals-slide-in-export'
                            //     },
                            //     action: function(e, dt, node, config) {
                            //         //This will send the page to the location specified
                            //         window.location.href = './products/export-with-attributes';
                            //     },
                            //     init: function(api, node, config) {
                            //         $(node).removeClass('btn-secondary');
                            //     }
                            // },
                            {
                                text: feather.icons['plus'].toSvg({
                                    class: 'me-50 font-small-4'
                                }) + 'اضافة جديد',
                                className: 'create-new btn btn-primary',
                                attr: {
                                    'data-bs-toggle': 'modal',
                                    'data-bs-target': '#modals-slide-in'
                                },
                                action: function(e, dt, node, config) {
                                    //This will send the page to the location specified
                                    window.location.href = './products/create';
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
                                    return 'Details of ' + data['title'];
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
                $('div.head-label').html('<h6 class="mb-0">المنتجات</h6>');
            }

            // Flat Date picker
            if (dt_date_table.length) {
                dt_date_table.flatpickr({
                    monthSelectorType: 'static',
                    dateFormat: 'm/d/Y'
                });
            }

            // Delete Record
            $('.datatables-basic tbody').on('click', '.delete-record', function() {
                let that = this;
                var id = dt_basic.row($(this).parents('tr')).data().id
                var token = $("meta[name='csrf-token']").attr("content");
                Swal.fire({
                    title: 'هل انت متأكد من الحذف ؟ ',
                    text: "لن تتمكن من التراجع عن هذا!",
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonText: 'الغاء',
                    confirmButtonText: 'نعم ، احذفها!',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ms-1'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    console.log(result);
                    if (result.isConfirmed) {
                        dt_basic.row($(that).parents('tr')).remove().draw();
                        $.ajax({
                            url: window.location.origin + "/admin/products/" + id,
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
                                text: 'تم حذف المنتج.',
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
