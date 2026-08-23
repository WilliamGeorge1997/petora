@extends('common::layouts.master')

@php
    $isSuperAdmin = auth()->guard('admin')->user()->hasRole('Super Admin');
@endphp

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
                    <h2 class="content-header-title float-start mb-0">الطلبات</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                            <li class="breadcrumb-item">الطلبات</li>
                            <li class="breadcrumb-item active">
                                @if (request('order_status_id'))
                                    {{ $viewModel->orderStatuses()->where('id', request('order_status_id'))->first()->getTranslations('title')['ar'] ?? 'كل الطلبات' }}
                                @else
                                    كل الطلبات
                                @endif
                            </li>
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
                        <h4 class="card-title">فلتره الطلبات</h4>
                    </div>
                    <div class="card-body">
                        <form id="filter-form">
                            @if (request('order_status_id'))
                                <input type="hidden" name="order_status_id" value="{{ request('order_status_id') }}">
                            @endif
                            <div class="row">
                                @if ($isSuperAdmin)
                                    <div class="col-md-4">
                                        <div class="mb-1">
                                            <label class="form-label" for="branch-filter">الفرع</label>
                                            <select id="branch-filter" name="branch_id" class="form-select">
                                                <option value="">جميع الفروع</option>
                                                @foreach ($viewModel->branches() as $branch)
                                                    <option {{ $branch->id == request('branch_id') ? 'selected' : '' }}
                                                        value="{{ $branch->id }}">
                                                        {{ $branch->getTranslations('title')['ar'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-4">
                                    <div class="mb-1">
                                        <label class="form-label" for="date-filter">التاريخ</label>
                                        <input type="date" id="date-filter" name="date" class="form-control"
                                            value="{{ request('date') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" id="apply-filter" class="btn btn-primary me-1">تطبيق
                                        الفلتره</button>
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
                                <th></th>
                                <th>رقم الطلب</th>
                                <th>رقم العميل</th>
                                @if ($isSuperAdmin)
                                    <th>الفرع</th>
                                @endif
                                <th>الاجمالي</th>
                                <th>الكمية</th>
                                <th>طريقة الدفع</th>
                                <th>حالة الطلب</th>
                                <th>تاريخ الانشاء</th>
                                <th>الخيارات</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>



    <div class="modal fade text-start" id="changeModal" tabindex="-1" aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">

            <form action="{{ url('admin/orders/updateStatus') }}" method="POST" enctype="multipart/form-data">
                {{ method_field('PUT') }}
                {{ csrf_field() }}

                <input type="hidden" value="" id="order_id" name="order_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel120">تعديل البيانات الخاصه بالطلب </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="pass-icon">حالات الطلب</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group-merge">
                                            <select name="order_status_id" id="status"
                                                class="select2 form-select select2-hidden-accessible" tabindex="-1"
                                                aria-hidden="true">
                                                @foreach ($viewModel->orderStatuses() as $status)
                                                    @if ($status->id == 1)
                                                        @continue
                                                    @endif
                                                    <option value="{{ $status->id }}">{{ $status->title }}</option>
                                                @endforeach
                                            </select>
                                            @error('order_status_id')
                                                <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="pass-icon">الوقت المتوقع لاتمام الطلب</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group-merge">
                                            <select name="duration_time"
                                                class="select2 form-select select2-hidden-accessible" tabindex="-1"
                                                aria-hidden="true">
                                                <option value="5">5 دقائق</option>
                                                <option value="10">10 دقائق</option>
                                                <option value="15">15 دقيقة</option>
                                                <option value="20">20 دقيقة</option>
                                                <option value="25">25 دقيقة</option>
                                                <option value="30">30 دقيقة</option>
                                                <option value="35">35 دقيقة</option>
                                                <option value="40">40 دقيقة</option>
                                                <option value="45">45 دقيقة</option>
                                                <option value="50">50 دقيقة</option>
                                                <option value="55">55 دقيقة</option>
                                                <option value="60">60 دقيقة</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}

                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="fname-icon"> الملاحظة</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i data-feather="user"></i></span>
                                            <input type="text" class="form-control" name="notes"
                                                placeholder="الملاحظة" />
                                            @error('notes')
                                                <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">الغاء</button>
                        <button type="submit" class="btn btn-success" data-bs-dismiss="modal">تاكيد</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--/ Basic table -->
    {{ $orders->withQueryString()->links() }}
@endsection


@section('js')
    <script src="{{ asset('') }}admin/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
    <script src="{{ asset('') }}admin/vendors/js/tables/datatable/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('') }}admin/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
    <script src="{{ asset('') }}admin/vendors/js/tables/datatable/responsive.bootstrap5.min.js"></script>
    <script src="{{ asset('') }}admin/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
    <script src="{{ asset('') }}admin/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
    <script src="{{ asset('') }}admin/vendors/js/tables/datatable/jszip.min.js"></script>
    <script src="{{ asset('') }}admin/vendors/js/tables/datatable/pdfmake.min.js"></script>
    <script src="{{ asset('') }}admin/vendors/js/tables/datatable/vfs_fonts.js"></script>
    <script src="{{ asset('') }}admin/vendors/js/tables/datatable/buttons.html5.min.js"></script>
    <script src="{{ asset('') }}admin/vendors/js/tables/datatable/buttons.print.min.js"></script>
    <script src="{{ asset('') }}admin/vendors/js/tables/datatable/dataTables.rowGroup.min.js"></script>
    <script src="{{ asset('') }}admin/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
    <script src="{{ asset('') }}admin/vendors/js/extensions/sweetalert2.all.min.js"></script>

    {{--    <script src="{{asset('')}}admin/js/scripts/tables/table-datatables-basic.js"></script> --}}

    {{-- <script src="{{asset('')}}js/order/order.js"></script> --}}

    @if (session('updated'))
        <script>
            Swal.fire({
                title: 'أحسنت!',
                text: 'لقد تم تعديل الطلب بنجاح',
                icon: 'success',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            });
        </script>
    @endif
    <script>
        function getorderId(val) {

            document.getElementById("order_id").value = val;
        }
        $(function() {

            'use strict';
            var url = new URL(window.location.href);
            var order_status_id = url.searchParams.get("order_status_id");
            var page = url.searchParams.get("page");
            var date = url.searchParams.get("date");
            var ajaxRequest;
            if (order_status_id == null) ajaxRequest = 'orders?';
            else ajaxRequest = 'orders?order_status_id=' + order_status_id + '&';
            if (page) ajaxRequest += 'page=' + page + '&';
            @if ($isSuperAdmin)
                var branch_id = url.searchParams.get("branch_id");
                if (branch_id) ajaxRequest += 'branch_id=' + branch_id + '&';
            @endif
            if (date) ajaxRequest += 'date=' + date + '&';
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
                            data: 'order_no'
                        },
                        {
                            data: 'phone'
                        },
                        @if ($isSuperAdmin)
                            {
                                data: 'branch.title.ar'
                            },
                        @endif {
                            data: 'total'
                        },
                        {
                            data: 'quantity'
                        },
                        {
                            data: 'payment_method.title.ar'
                        },
                        {
                            data: 'order_status'
                        },
                        {
                            data: 'created_at'
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
                            // Total
                            targets: {{ $isSuperAdmin ? 6 : 5 }},
                            render: function(data, type, full, meta) {
                                return Math.round(data * 100) / 100;

                            }
                        },
                        {
                            // Label
                            targets: -4,
                            render: function(data, type, full, meta) {
                                var $payment_method_title = data;
                                if ($payment_method_title == null) {
                                    return 'لم يتم التحديد بعد'
                                }
                                return $payment_method_title;

                            }
                        },
                        {
                            // Order Status
                            targets: -3,
                            render: function(data, type, full, meta) {
                                var badgeColors = {
                                    1: 'warning',
                                    2: 'info',
                                    3: 'primary',
                                    4: 'secondary',
                                    5: 'success',
                                    6: 'danger',
                                    7: 'danger'
                                };

                                var badgeColor = badgeColors[data.id] || 'secondary';
                                var titleAr = data.title.ar;

                                return '<span class="badge rounded-pill badge-light-' + badgeColor +
                                    '">' + titleAr + '</span>';
                            }
                        },
                        {
                            // Actions
                            targets: -2,
                            title: 'تاريخ الانشاء',
                            orderable: false,
                            render: function(data, type, full, meta) {
                                var date = new Date(data).setTime(new Date(data).getTime() + 3 *
                                    60 * 60 * 1000);
                                var createdDate = new Date(date);
                                var date = createdDate.toLocaleDateString();

                                var day = createdDate.getDate();
                                var month = createdDate.getMonth() + 1; //months are zero based
                                var year = createdDate.getFullYear();

                                var time = createdDate.toLocaleTimeString().replace(/(.*)\D\d+/,
                                    '$1');

                                return year + '-' + month + '-' + day + ' ' + time;
                            }
                        },
                        {
                            // Actions
                            targets: -1,
                            title: 'Actions',
                            orderable: false,
                            render: function(data, type, full, meta) {
                                return (
                                    '<div class="d-inline-flex">' +
                                    '<a href="orders/' + data + '" class="pe-1">' +
                                    feather.icons['eye'].toSvg({
                                        class: 'font-small-4'
                                    }) +
                                    '</a>' +
                                    '</div>' +

                                    // '<a href="orders/'+data+'/edit">' +
                                    '<a onclick="getorderId(' + full['id'] +
                                    ')" href="#"  data-bs-toggle="modal" data-bs-target="#changeModal">' +
                                    feather.icons['edit'].toSvg({
                                        class: 'font-small-4'
                                    }) +
                                    '</a>'
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
                                    columns: [
                                        {{ $isSuperAdmin ? '3, 4, 5, 6, 7' : '3, 4, 5, 6' }}
                                    ]
                                }
                            },
                            {
                                extend: 'csv',
                                text: feather.icons['file-text'].toSvg({
                                    class: 'font-small-4 me-50'
                                }) + 'Csv',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: [
                                        {{ $isSuperAdmin ? '3, 4, 5, 6, 7' : '3, 4, 5, 6' }}
                                    ]
                                }
                            },
                            {
                                extend: 'excel',
                                text: feather.icons['file'].toSvg({
                                    class: 'font-small-4 me-50'
                                }) + 'Excel',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: [
                                        {{ $isSuperAdmin ? '3, 4, 5, 6, 7' : '3, 4, 5, 6' }}
                                    ]
                                }
                            },
                            {
                                extend: 'pdf',
                                text: feather.icons['clipboard'].toSvg({
                                    class: 'font-small-4 me-50'
                                }) + 'Pdf',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: [
                                        {{ $isSuperAdmin ? '3, 4, 5, 6, 7' : '3, 4, 5, 6' }}
                                    ]
                                }
                            },
                            {
                                extend: 'copy',
                                text: feather.icons['copy'].toSvg({
                                    class: 'font-small-4 me-50'
                                }) + 'Copy',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: [
                                        {{ $isSuperAdmin ? '3, 4, 5, 6, 7' : '3, 4, 5, 6' }}
                                    ]
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
                    }, ],
                    responsive: {
                        details: {
                            display: $.fn.dataTable.Responsive.display.modal({
                                header: function(row) {
                                    var data = row.data();
                                    return 'Details of ' + data['uuid'];
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
                $('div.head-label').html('<h6 class="mb-0">DataTable with Buttons</h6>');
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
                    title: 'هل انت متأكد من الالغاء ؟ ',
                    text: "لن تتمكن من التراجع عن هذا!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'نعم ، الغاء!',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ms-1'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    // dt_basic.row($(that).parents('tr')).remove().draw();
                    $.ajax({
                        url: window.location.origin + "/admin/orders/" + id,
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
                            title: 'تم الالغاء!',
                            text: 'تم الغاء الطلب.',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                    }
                    dt_basic.ajax.reload();
                });
            });
        });
    </script>
@endsection
