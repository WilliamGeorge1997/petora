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
    <style>
        .theme-filter-card {
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
            border: 2px solid transparent;
        }
        .theme-filter-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
        }
        .theme-filter-card.selected {
            border-color: #7367f0;
            box-shadow: 0 0 0 3px rgba(115, 103, 240, 0.25);
        }
    </style>
@endsection
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">الفروع</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                            <li class="breadcrumb-item">ادارة العضويات</li>
                            <li class="breadcrumb-item active">الفروع</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden form used to submit the theme filter as a GET param --}}
    <form id="theme-filter-form" method="GET" action="branches">
        <input type="hidden" id="theme-filter-input" name="theme" value="{{ request('theme') }}">
    </form>

    <div class="row mb-2 align-items-center">
        @if (isset($themeCounts) && count($themeCounts))
            @foreach ($themeCounts as $theme => $count)
                <div class="col-md-2 col-6 mb-2">
                    <div class="card text-center theme-filter-card @if(request('theme') == $theme) selected @endif"
                         data-theme="{{ $theme }}">
                        <div class="card-body">
                            <h5 class="card-title mb-1">ستايل {{ $theme }}</h5>
                            <span class="badge rounded-pill bg-primary"
                                style="font-size: 1.3rem;">{{ $count }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
            @if(request('theme'))
                <div class="col-md-2 col-6 mb-2">
                    <a href="branches" class="btn btn-outline-danger w-100">
                        <i class="fa-solid fa-xmark me-1"></i> إزالة الفلتر
                    </a>
                </div>
            @endif
        @endif
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
                                <th>الاشتراك الحالي</th>
                                <th>عدد مرات مسح QR</th>
                                <th>ستايل</th>
                                <th>تاريخ الانشاء</th>
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
    {{ $branches->withQueryString()->links() }}
    <!-- Modal -->
    <div class="modal fade" id="updateThemeModal" tabindex="-1" aria-labelledby="updateThemeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="updateThemeForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="updateThemeModalLabel">تحديث ستايل الفـرع</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="branchTheme" class="form-label">اختر ستايل الفرع</label>
                            <select class="form-select" name="theme" id="branchTheme" required>
                                <option value="1">ستايل 1</option>
                                <option value="2">ستايل 2</option>
                                <option value="3">ستايل 3</option>
                                <option value="4">ستايل 4</option>
                                <option value="5">ستايل 5</option>
                                <option value="6">ستايل 6</option>
                            </select>
                        </div>
                        <input type="hidden" name="branch_id" id="themeBranchId" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('js')
    @include('common::includes.datatable')

    {{--    <script src="{{asset('')}}admin/js/scripts/tables/table-datatables-basic.js"></script> --}}

    {{-- <script src="{{asset('')}}js/branches/script.js"></script> --}}

    @if (session('updated'))
        <script>
            Swal.fire({
                title: 'أحسنت!',
                text: 'لقد تم تعديل الفرع بنجاح',
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
                text: 'لقد تم انشاء الفرع بنجاح',
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
            var theme = url.searchParams.get('theme');
            var ajaxRequest = 'branches?';
            if (page) ajaxRequest += 'page=' + page + '&';
            if (theme) ajaxRequest += 'theme=' + theme + '&';

            // Theme filter cards — set hidden input and submit form (full page reload)
            $(document).on('click', '.theme-filter-card', function() {
                var clickedTheme = $(this).data('theme');
                // Toggle: clicking the active theme clears the filter
                if (clickedTheme == theme) {
                    $('#theme-filter-input').val('');
                } else {
                    $('#theme-filter-input').val(clickedTheme);
                }
                $('#theme-filter-form').submit();
            });
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
                            data: 'active_subscription'
                        },
                        {
                            data: 'scan_counter'
                        },
                        {
                            data: 'settings.theme',
                            render: function(data, type, full, meta) {
                                let theme = data ?? 'لم يتم الاخيتار'
                                return `<span class="badge bg-primary">${theme}</span>`;
                            }
                        },
                        {
                            data: 'created_at'
                        },
                        {
                            data: 'status'
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
                            targets: 4,
                            render: function(data, type, full, meta) {
                                let html = '';
                                if (data && data.start_date && data.end_date) {
                                    html += '<div class="text-nowrap">من <strong>' + data.start_date +
                                        '</strong> إلى <strong>' + data.end_date + '</strong></div>';
                                    if (data.product_count !== null && data.product_count !== undefined) {
                                        html += '<small class="text-info mt-1 d-block">المنتجات: ' + (full.branch_products_count || 0) + ' / ' + data.product_count + '</small>';
                                    }
                                    return html;
                                }
                                return '<span class="text-muted text-nowrap">لا يوجد اشتراك فعال</span>';
                            }
                        },
                        {
                            // Avatar image/badge, Name and email
                            targets: 3,
                            responsivePriority: 4,
                            render: function(data, type, full, meta) {
                                var $user_img = full['image'],
                                    $name = data['ar'],
                                    $nameEn = data['en'];
                                if ($user_img) {
                                    $user_img = $user_img;
                                    // For Avatar image
                                    var $output =
                                        '<img src="' + $user_img +
                                        '" alt="Avatar" width="32" height="32">';
                                } else {
                                    // For Avatar badge
                                    var stateNum = full['is_active'];
                                    var states = ['info', 'primary'];
                                    var $state = states[stateNum],
                                        // $name = full['title']['ar'],
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
                                    (($name && $name.length > 27) ? $name.substring(0, 27) + '...' :
                                        ($name || '')) +
                                    '</span>' +
                                    '<small class="text-muted text-truncate">' +
                                    (($nameEn && $nameEn.length > 27) ? $nameEn.substring(0, 27) +
                                        '...' : ($nameEn || '')) +
                                    '</small>' +
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
                            title: 'Actions',
                            orderable: false,
                            render: function(data, type, full, meta) {
                                var activation = '';
                                if (full['is_active'] == 0) activation = 'تفعيل';
                                else activation = 'الغاء التفعيل';
                                return (
                                    '<div class="d-inline-flex">' +
                                    '<a class="me-1 dropdown-toggle hide-arrow text-primary" data-bs-toggle="dropdown">' +
                                    feather.icons['more-vertical'].toSvg({
                                        class: 'font-small-4'
                                    }) +
                                    '</a>' +
                                    '<div class="dropdown-menu dropdown-menu-end">' +
                                    '<a href="products?branch_id=' + data +
                                    '" class="dropdown-item">' +
                                    feather.icons['package'].toSvg({
                                        class: 'font-small-4 me-50'
                                    }) +
                                    'المنتجات</a>'
                                    @can('Edit-branch')
                                        +
                                        '<a href="branches/activate/' + data +
                                            '" class="dropdown-item">' +
                                            feather.icons['power'].toSvg({
                                                class: 'font-small-4 me-50'
                                            }) +
                                            activation + '</a>'
                                    @endcan
                                    @can('Index-tables')
                                        +
                                        '<a href="branch/' + data +
                                            '/tables" class="dropdown-item">' +
                                            feather.icons['grid'].toSvg({
                                                class: 'font-small-4 me-50'
                                            }) +
                                            'الجداول' + '</a>'
                                    @endcan
                                    @can('Delete-branch')
                                        +
                                        '<a href="javascript:;" class="dropdown-item delete-record">' +
                                        feather.icons['trash-2'].toSvg({
                                                class: 'font-small-4 me-50'
                                            }) +
                                            'حذف</a>'
                                    @endcan +
                                    '</div>' +
                                    '</div>'
                                    @can('Edit-branch')

                                        +
                                        '<a href="branches/' + data +
                                            '/edit" class="item-edit">' +
                                            feather.icons['edit'].toSvg({
                                                class: 'font-small-4 me-1'
                                            }) +
                                            '</a>'
                                    @endcan
                                    @if (auth('admin')->user()->hasRole('Super Admin'))
                                        +
                                        '<a href="branches/' + data +
                                            '/settings" class="item-edit" title="الاعدادات">' +
                                            feather.icons['settings'].toSvg({
                                                class: 'font-small-4 me-1'
                                            }) +
                                            '</a>' +
                                            '<a href="branches/' + data +
                                            '/qr" class="item-edit" title="QR كود">' +
                                            '<i class="fa-solid fa-qrcode font-small-4 me-1"></i>' +
                                            '</a>' +
                                            '<a href="branches/' + data +
                                            '/discounts" class="item-edit" title="خصم بقيمة الطلب">' +
                                            feather.icons['percent'].toSvg({
                                                class: 'font-small-4 me-1'
                                            }) +
                                            '</a>'
                                    @endif
                                    @can('Index-subscription')
                                        +
                                        '<a href="branches/' + data +
                                            '/subscriptions" class="item-edit">' +
                                            feather.icons['box'].toSvg({
                                                class: 'font-small-4 me-1'
                                            }) +
                                            '</a>'
                                    @endcan
                                    @if (auth('admin')->user()->hasRole('Super Admin'))
                                        +
                                        '<a href="#" class="text-primary open-theme-modal" data-branch-theme="' +
                                        (full.settings && typeof full.settings.theme !==
                                            'undefined' ? full.settings.theme : 1) +
                                        '" data-bs-toggle="modal" data-branch-id="' +
                                        data + '" data-bs-target="#updateThemeModal">' +
                                            feather.icons['monitor'].toSvg({
                                                class: 'font-small-4 me-1'
                                            }) +
                                            '</a>' +
                                            (full.slug ?
                                                '<a href="javascript:;" class="item-edit copy-short-url" data-url="' +
                                                "{{ rtrim(config('app.frontend_url'), '/') }}/" +
                                                full.slug + '">' +
                                                feather.icons['link-2'].toSvg({
                                                    class: 'font-small-4 me-1'
                                                }) +
                                                '</a>' : '')
                                    @endif
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
                        //     extend: 'collection',
                        //     className: 'btn btn-outline-secondary dropdown-toggle me-2',
                        //     text: feather.icons['share'].toSvg({ class: 'font-small-4 me-50' }) + 'Export',
                        //     buttons: [
                        //         {
                        //             extend: 'print',
                        //             text: feather.icons['printer'].toSvg({ class: 'font-small-4 me-50' }) + 'Print',
                        //             className: 'dropdown-item',
                        //             exportOptions: { columns: [3, 4, 5, 6, 7] }
                        //         },
                        //         {
                        //             extend: 'csv',
                        //             text: feather.icons['file-text'].toSvg({ class: 'font-small-4 me-50' }) + 'Csv',
                        //             className: 'dropdown-item',
                        //             exportOptions: { columns: [3, 4, 5, 6, 7] }
                        //         },
                        //         {
                        //             extend: 'excel',
                        //             text: feather.icons['file'].toSvg({ class: 'font-small-4 me-50' }) + 'Excel',
                        //             className: 'dropdown-item',
                        //             exportOptions: { columns: [3, 4, 5, 6, 7] }
                        //         },
                        //         {
                        //             extend: 'pdf',
                        //             text: feather.icons['clipboard'].toSvg({ class: 'font-small-4 me-50' }) + 'Pdf',
                        //             className: 'dropdown-item',
                        //             exportOptions: { columns: [3, 4, 5, 6, 7] }
                        //         },
                        //         {
                        //             extend: 'copy',
                        //             text: feather.icons['copy'].toSvg({ class: 'font-small-4 me-50' }) + 'Copy',
                        //             className: 'dropdown-item',
                        //             exportOptions: { columns: [3, 4, 5, 6, 7] }
                        //         }
                        //     ],
                        //     init: function (api, node, config) {
                        //         $(node).removeClass('btn-secondary');
                        //         $(node).parent().removeClass('btn-group');
                        //         setTimeout(function () {
                        //             $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex');
                        //         }, 50);
                        //     }
                        // },
                        @can('Create-branch')


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
                                    window.location.href = './branches/create';
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
                $('div.head-label').html('<h6 class="mb-0">الفروع</h6>');
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
                            url: window.location.origin + "/admin/branches/" + id,
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
                            text: 'تم حذف القسم.',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.open-theme-modal', function() {
                var branchId = $(this).data('branch-id');
                var branchTheme = $(this).data('branch-theme');
                $('#themeBranchId').val(branchId);
                $('#branchTheme').val(branchTheme);
                var actionUrl = "{{ url('admin/branches') }}/" + branchId + "/update-theme";
                $('#updateThemeForm').attr('action', actionUrl);
            });
            $(document).on('click', '.copy-short-url', function() {
                var url = $(this).data('url');

                var tempInput = document.createElement('input');
                tempInput.value = url;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);

                Swal.fire({
                    text: 'تم نسخ الرابط',
                    icon: 'success',
                    toast: true,
                    position: 'top-end',
                    timer: 1500,
                    showConfirmButton: false
                });
            });
        });
    </script>
@endsection
