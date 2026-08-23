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
                    <h2 class="content-header-title float-start mb-0">صور المنيو</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                            <li class="breadcrumb-item active">صور المنيو</li>
                        </ol>
                    </div>
                </div>
            </div>
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
                                <th>الصورة</th>
                                <th>الفرع</th>
                                <th>الترتيب</th>
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

    {{ $galleries->links() }}
@endsection

@section('js')
    @include('common::includes.datatable')

    @if (session('updated'))
        <script>
            Swal.fire({
                title: 'أحسنت!',
                text: 'لقد تم التعديل بنجاح',
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
                text: 'لقد تم الانشاء بنجاح',
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
                var dt_basic = dt_basic_table.DataTable({
                    ajax: 'galleries',
                    searching: false,
                    columns: [{
                            data: 'id'
                        }, // for responsive
                        {
                            data: 'id'
                        }, // for checkbox
                        {
                            data: 'id'
                        }, // hidden sort column
                        {
                            data: 'image'
                        },
                        {
                            data: 'branch'
                        },
                        {
                            data: 'sort_order'
                        },
                        {
                            data: 'is_active'
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
                            // Hidden ID column used for ordering
                            targets: 2,
                            visible: false
                        },
                        {
                            // Image preview
                            targets: 3,
                            orderable: false,
                            responsivePriority: 1,
                            render: function(data, type, full, meta) {
                                if (data) {
                                    return '<img src="' + data +
                                        '" alt="gallery" width="60" height="60" style="object-fit:cover; border-radius:6px;">';
                                }
                                return '<span class="text-muted">لا توجد صورة</span>';
                            }
                        },
                        {
                            // Branch name
                            targets: 4,
                            render: function(data, type, full, meta) {
                                if (data && data.title) {
                                    return data.title.ar ?? data.title.en ?? '';
                                }
                                return '-';
                            }
                        },
                        {
                            // Sort order badge
                            targets: 5,
                            render: function(data, type, full, meta) {
                                return '<span class="badge bg-primary">' + data + '</span>';
                            }
                        },
                        {
                            // Status label
                            targets: 6,
                            render: function(data, type, full, meta) {
                                var $status = {
                                    0: {
                                        title: 'غير مفعل',
                                        class: 'badge-light-danger'
                                    },
                                    1: {
                                        title: 'مفعل',
                                        class: 'badge-light-success'
                                    },
                                };
                                if (typeof $status[data] === 'undefined') return data;
                                return '<span class="badge rounded-pill ' + $status[data].class +
                                    '">' + $status[data].title + '</span>';
                            }
                        },
                        {
                            // Actions
                            targets: -1,
                            title: 'الاجراءات',
                            orderable: false,
                            render: function(data, type, full, meta) {
                                var activation = full['is_active'] == 0 ? 'تفعيل' : 'الغاء التفعيل';
                                return (
                                    '<div class="d-inline-flex">' +
                                    '<a class="pe-1 dropdown-toggle hide-arrow text-primary" data-bs-toggle="dropdown">' +
                                    feather.icons['more-vertical'].toSvg({
                                        class: 'font-small-4'
                                    }) +
                                    '</a>' +
                                    '<div class="dropdown-menu dropdown-menu-end">' +
                                    '<a href="galleries/' + data +
                                    '/activate" class="dropdown-item">' +
                                    feather.icons['power'].toSvg({
                                        class: 'font-small-4 me-50'
                                    }) +
                                    activation + '</a>' +
                                    '<a href="javascript:;" class="dropdown-item delete-record">' +
                                    feather.icons['trash-2'].toSvg({
                                        class: 'font-small-4 me-50'
                                    }) +
                                    'حذف</a>' +
                                    '</div>' +
                                    '</div>' +
                                    '<a href="galleries/' + data + '/edit" class="item-edit">' +
                                    feather.icons['edit'].toSvg({
                                        class: 'font-small-4 me-1'
                                    }) +
                                    '</a>'
                                );
                            }
                        }
                    ],
                    order: [],
                    dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    displayLength: 50,
                    lengthMenu: [7, 10, 25, 50, 75, 100],
                    buttons: [{
                            text: 'ترتيب صور المنيو',
                            className: 'btn btn-primary me-1',
                            action: function(e, dt, node, config) {
                                window.location.href = './galleries/sort';
                            },
                            init: function(api, node, config) {
                                $(node).removeClass('btn-secondary');
                            }
                        },
                        {
                            text: feather.icons['plus'].toSvg({
                                class: 'me-50 font-small-4'
                            }) + 'اضافة جديد',
                            className: 'create-new btn btn-primary',
                            action: function(e, dt, node, config) {
                                window.location.href = './galleries/create';
                            },
                            init: function(api, node, config) {
                                $(node).removeClass('btn-secondary');
                            }
                        }
                    ],
                    responsive: {
                        details: {
                            display: $.fn.dataTable.Responsive.display.modal({
                                header: function(row) {
                                    return 'تفاصيل الصورة';
                                }
                            }),
                            type: 'column',
                            renderer: function(api, rowIdx, columns) {
                                var data = $.map(columns, function(col, i) {
                                    return col.title !== '' ?
                                        '<tr data-dt-row="' + col.rowIdx +
                                        '" data-dt-column="' + col.columnIndex + '">' +
                                        '<td>' + col.title + ':</td> ' +
                                        '<td>' + col.data + '</td>' +
                                        '</tr>' : '';
                                }).join('');
                                return data ? $('<table class="table"/>').append('<tbody>' + data +
                                    '</tbody>') : false;
                            }
                        }
                    },
                    language: {
                        paginate: {
                            previous: '&nbsp;',
                            next: '&nbsp;'
                        }
                    }
                });

                $('div.head-label').html('<h6 class="mb-0">صور المنيو</h6>');
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
                var id = dt_basic.row($(this).parents('tr')).data().id;
                var token = $("meta[name='csrf-token']").attr("content");
                Swal.fire({
                    title: 'هل انت متأكد من الحذف ؟',
                    text: "لن تتمكن من التراجع عن هذا!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'نعم ، احذفها!',
                    cancelButtonText: 'الغاء',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ms-1'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.value) {
                        dt_basic.row($(that).parents('tr')).remove().draw();
                        $.ajax({
                            url: window.location.origin + "/admin/galleries/" + id,
                            type: 'POST',
                            data: {
                                "id": id,
                                "_method": "DELETE",
                                "_token": token,
                            },
                            success: function() {}
                        });
                        Swal.fire({
                            icon: 'success',
                            title: 'تم الحذف!',
                            text: 'تم حذف الصورة.',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
