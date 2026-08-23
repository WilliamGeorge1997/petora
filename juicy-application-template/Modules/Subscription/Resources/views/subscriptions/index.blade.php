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
    <!-- Basic table -->
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">الاشتراكات</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/admin/dashboard') }}">الرئيسية</a>
                            </li>
                            <li class="breadcrumb-item active">الاشتراكات
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (auth()->user()->hasRole('Super Admin'))
        <div class="content-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">تصفية النتائج</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ url('admin/subscriptions') }}" method="GET">
                                <div class="row align-items-center">
                                    <div class="col-lg-2 col-12">
                                        <div class="mb-2">
                                            <label class="form-label" for="subscription_type">نوع الاشتراك</label>
                                            <select class="form-select select2" id="subscription_type"
                                                name="subscription_type">
                                                <option value="">الكل</option>
                                                <option value="trial"
                                                    {{ request('subscription_type') == 'trial' ? 'selected' : '' }}>تجريبي
                                                </option>
                                                <option value="subscription"
                                                    {{ request('subscription_type') == 'subscription' ? 'selected' : '' }}>
                                                    اشتراك</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-12">
                                        <div class="mb-2">
                                            <label class="form-label" for="imei">IMEI</label>
                                            <input type="text" class="form-control" id="imei" name="imei"
                                                value="{{ request('imei') }}" placeholder="ادخل IMEI">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-12">
                                        <div class="mb-2">
                                            <label class="form-label" for="phone">رقم الهاتف</label>
                                            <input type="text" class="form-control" id="phone" name="phone"
                                                value="{{ request('phone') }}" placeholder="ادخل رقم الهاتف">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-12">
                                        <div class="mb-2">
                                            <label class="form-label" for="phone">الشركة</label>
                                            <select name="branch_id" id="branch_id" class="form-select select2">
                                                <option selected value="{{ null }}">اختر شركة</option>
                                                @foreach ($viewModel->branches() as $branch)
                                                    <option {{ request('branch_id') == $branch->id ? 'selected' : '' }}
                                                        value="{{ $branch->id }}">{{ $branch->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-12 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary me-1">تصفية</button>
                                        <a href="{{ url('admin/subscriptions') }}" class="btn btn-outline-secondary">إعادة
                                            تعيين</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
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
                                <th>{{ auth()->user()->hasRole('Super Admin') ? 'IMEI' : 'الاسم او رقم الهاتف' }}</th>
                                @if (auth()->user()->hasRole('Super Admin'))
                                    <th>نوع الاشتراك</th>
                                    <th>الكود</th>
                                    <th>تاريخ البداية</th>
                                    <th>تاريخ الانتهاء</th>
                                @endif
                                <th>تاريخ اخر الدخول</th>
                                <th>عدد مرات الدخول</th>
                                @if (auth()->user()->hasRole('Super Admin'))
                                    <th>الحالة</th>
                                    <th>العضوية</th>
                                @else
                                    <th>العضوية</th>
                                @endif
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!--/ Basic table -->
    {{ $subscriptions->withQueryString()->links() }}

    <!-- Change Name Modal -->
    <div class="modal fade" id="changeNameModal" tabindex="-1" aria-labelledby="changeNameModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changeNameModalLabel">تغيير اسم الاشتراك</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="changeNameForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="subscriptionName" class="form-label">الاسم الجديد</label>
                            <input type="text" class="form-control" id="subscriptionName" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('js')
    @include('common::includes.datatable')
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
                text: 'لقد انشاء الاشتراك بنجاح',
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
                text: 'لقد تم تعديل الاشتراك بنجاح',
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
            var ajaxRequest = 'subscriptions?';
            var subscription_type = url.searchParams.get('subscription_type');
            var page = url.searchParams.get('page');
            var imei = url.searchParams.get('imei');
            var phone = url.searchParams.get('phone');
            var branch_id = url.searchParams.get('branch_id');
            var is_active = url.searchParams.get('is_active');
            if (page == null) page = 1;
            if (page != null) ajaxRequest += 'page=' + page + '&';
            if (subscription_type != null) ajaxRequest += 'subscription_type=' + subscription_type + '&';
            if (imei != null) ajaxRequest += 'imei=' + imei + '&';
            if (phone != null) ajaxRequest += 'phone=' + phone + '&';
            if (branch_id != null) ajaxRequest += 'branch_id=' + branch_id + '&';
            if (is_active != null) ajaxRequest += 'is_active=' + is_active + '&';
            var dt_basic_table = $('.datatables-basic'),
                dt_date_table = $('.dt-date');

            var isSuperAdmin = {{ auth()->user()->hasRole('Super Admin') ? 'true' : 'false' }};

            // Define columns based on user role
            var columns = [{
                    data: 'id'
                }, // for responsive show
                {
                    data: 'id'
                }, // for checkbox
                {
                    data: 'id'
                }, // used for sorting so will hide this column
                {
                    data: isSuperAdmin ? 'imei' : 'name_or_phone'
                }, // IMEI or Name/Phone
            ];

            if (isSuperAdmin) {
                columns.push({
                        data: 'code_id'
                    }, // subscription type
                    {
                        data: 'code.code'
                    }, // code
                    {
                        data: 'start_date'
                    }, // start date
                    {
                        data: 'end_date'
                    } // end date
                );
            }

            columns.push({
                    data: 'last_login_at'
                }, // last login
                {
                    data: 'login_counter'
                }, // login counter
            );

            if (isSuperAdmin) {
                columns.push({
                        data: 'is_active'
                    }, // status
                    {
                        data: 'is_enabled'
                    } // membership
                );
            } else {
                columns.push({
                    data: 'is_enabled'
                }); // membership only for non-super admin
            }

            columns.push({
                data: 'id'
            }); // actions

            // Define column definitions based on user role
            var columnDefs = [{
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
                    // IMEI or Name/Phone column
                    targets: 3,
                    responsivePriority: 4,
                    render: function(data, type, full, meta) {
                        if (isSuperAdmin) {
                            // For Super Admin, show IMEI with phone
                            var $name = full['imei'],
                                $email = full['phone'];

                            // Truncate IMEI to 10 chars
                            var $displayName = $name.length > 10 ? $name.substring(0, 10) + '...' : $name;

                            if ($user_img) {
                                var $user_img = $user_img;
                                var $output = '<img src="' + $user_img +
                                    '" alt="Avatar" width="32" height="32">';
                            } else {
                                var stateNum = full['is_active'];
                                var states = ['info', 'primary'];
                                var $state = states[stateNum],
                                    $initials = $name.match(/\b\w/g) || [];
                                $initials = (($initials.shift() || '') + ($initials.pop() || ''))
                                    .toUpperCase();
                                $output = '<span class="avatar-content">' + $initials + '</span>';
                            }

                            var colorClass = $user_img === null ? ' bg-light-' + $state + ' ' : '';
                            var $row_output =
                                '<div class="d-flex justify-content-left align-items-center">' +
                                '<div class="avatar ' + colorClass + ' me-1">' +
                                $output +
                                '</div>' +
                                '<div class="d-flex flex-column">' +
                                '<span class="emp_name text-truncate fw-bold copy-text" style="cursor:pointer;" data-bs-toggle="tooltip" data-bs-placement="top" title="' +
                                $name + '" data-copy="' + $name + '">' + $displayName + '</span>' +
                                '<small class="emp_post text-truncate text-muted">' + $email + '</small>' +
                                '</div>' +
                                '</div>';
                            return $row_output;
                        } else {
                            // For non-Super Admin, show name and phone or just phone
                            if (full['name'] && full['phone']) {
                                var $displayName = full['name'].length > 10 ? full['name'].substring(0,
                                    10) + '...' : full['name'];
                                var $displayPhone = full['phone'].length > 10 ? full['phone'].substring(0,
                                    10) + '...' : full['phone'];

                                var $row_output =
                                    '<div class="d-flex flex-column">' +
                                    '<span class="fw-bold text-truncate copy-text" style="cursor:pointer;" data-bs-toggle="tooltip" data-bs-placement="top" title="' +
                                    full['name'] + '" data-copy="' + full['name'] + '">' + $displayName +
                                    '</span>' +
                                    '<small class="text-truncate text-muted copy-text" style="cursor:pointer;" data-bs-toggle="tooltip" data-bs-placement="top" title="' +
                                    full['phone'] + '" data-copy="' + full['phone'] + '">' + $displayPhone +
                                    '</small>' +
                                    '</div>';
                                return $row_output;
                            } else if (full['name']) {
                                var $displayName = full['name'].length > 10 ? full['name'].substring(0,
                                    10) + '...' : full['name'];
                                return '<span class="fw-bold copy-text" style="cursor:pointer;" data-bs-toggle="tooltip" data-bs-placement="top" title="' +
                                    full['name'] + '" data-copy="' + full['name'] + '">' + $displayName +
                                    '</span>';
                            } else {
                                var $displayPhone = full['phone'].length > 10 ? full['phone'].substring(0,
                                    10) + '...' : full['phone'];
                                return '<span class="fw-bold copy-text" style="cursor:pointer;" data-bs-toggle="tooltip" data-bs-placement="top" title="' +
                                    full['phone'] + '" data-copy="' + full['phone'] + '">' + $displayPhone +
                                    '</span>';
                            }
                        }
                    }
                }
            ];

            if (isSuperAdmin) {
                columnDefs.push({
                    // Subscription type
                    targets: 4,
                    render: function(data, type, full, meta) {
                        if (full['branch_id'] == null) {
                            var subscriptionType = data == null ? 'تجريبى' : 'اشتراك';
                            var badgeClass = data == null ? 'badge-light-warning' :
                                'badge-light-primary';
                            return '<span class="badge rounded-pill ' + badgeClass + '">' +
                                subscriptionType + '</span>';
                        } else {
                            var branchTitle = full.branch && full.branch.title ? full.branch.title
                                .ar : 'فرع';
                            return '<span class="badge rounded-pill badge-light-success" data-bs-toggle="tooltip" data-bs-placement="top" title="' +
                                branchTitle + '">' + 'فرع' + '</span>';
                        }
                    }
                }, {
                    // Code
                    targets: 5,
                    render: function(data, type, full, meta) {
                        return full.branch_id != null ? 'فرع' : (data == null ? 'تجريبى' : data);
                    }
                }, {
                    // Start date - no special formatting needed
                    targets: 6
                }, {
                    // End date - no special formatting needed
                    targets: 7
                }, {
                    // Last login
                    targets: 8,
                    render: function(data, type, full, meta) {
                        return data ? data : 'لا يوجد';
                    }
                }, {
                    // Login counter - no special formatting needed
                    targets: 9
                }, {
                    // Status (الحالة)
                    targets: 10,
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
                        return '<span class="badge rounded-pill ' + $status[$status_number].class +
                            '">' + $status[$status_number].title + '</span>';
                    }
                }, {
                    // Membership (العضوية)
                    targets: 11,
                    render: function(data, type, full, meta) {
                        var $status_number = full['is_enabled'];
                        var $status = {
                            0: {
                                title: 'معطل',
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
                        return '<span class="badge rounded-pill ' + $status[$status_number].class +
                            '">' + $status[$status_number].title + '</span>';
                    }
                }, {
                    // Actions
                    targets: 12,
                    title: 'Actions',
                    orderable: false,
                    render: function(data, type, full, meta) {
                        var activation = '';
                        var enabling = '';
                        if (full['is_active'] == 0) activation = 'تفعيل';
                        else activation = 'الغاء التفعيل';
                        if (full['is_enabled'] == 0) enabling = 'تفعيل المندوب';
                        else enabling = 'ايقاف المندوب';

                        var actions = '';

                        // For Super Admin - show all actions
                        @if (auth()->user()->hasRole('Super Admin'))
                            @can('Edit-subscription')
                                actions += '<a href="subscriptions/activate/' + data +
                                    '" class="dropdown-item">' +
                                    feather.icons['file-text'].toSvg({
                                        class: 'font-small-4 me-50'
                                    }) +
                                    activation + '</a>';
                            @endcan

                            actions += '<a href="subscriptions/enabling/' + data +
                                '" class="dropdown-item">' +
                                feather.icons['file-text'].toSvg({
                                    class: 'font-small-4 me-50'
                                }) +
                                enabling + '</a>';

                            actions +=
                                '<a href="javascript:;" class="dropdown-item change-name-btn" data-subscription-id="' +
                                data + '">' +
                                feather.icons['edit'].toSvg({
                                    class: 'font-small-4 me-50'
                                }) +
                                'تغيير الاسم</a>';

                            @can('Delete-subscription')
                                actions +=
                                    '<a href="javascript:;" class="dropdown-item delete-record">' +
                                    feather.icons['trash-2'].toSvg({
                                        class: 'font-small-4 me-50'
                                    }) +
                                    'مسح</a>';
                            @endcan
                        @elseif (auth()->user()->hasRole('Branch Admin'))
                            // For Branch Admin - only show enabling and change name
                            actions += '<a href="subscriptions/enabling/' + data +
                                '" class="dropdown-item">' +
                                feather.icons['file-text'].toSvg({
                                    class: 'font-small-4 me-50'
                                }) +
                                enabling + '</a>';

                            actions +=
                                '<a href="javascript:;" class="dropdown-item change-name-btn" data-subscription-id="' +
                                data + '">' +
                                feather.icons['edit'].toSvg({
                                    class: 'font-small-4 me-50'
                                }) +
                                'تغيير الاسم</a>';
                        @endif

                        var dropdown = '<div class="d-inline-flex">' +
                            '<a class="me-50 dropdown-toggle hide-arrow text-primary" data-bs-toggle="dropdown">' +
                            feather.icons['more-vertical'].toSvg({
                                class: 'font-small-4'
                            }) +
                            '</a>' +
                            '<div class="dropdown-menu dropdown-menu-end">' +
                            actions +
                            '</div>' +
                            '</div>';

                        var viewAction = '';
                        @can('Index-subscription')
                            viewAction = '<a href="subscriptions/' + data + '" class="item-edit">' +
                                feather.icons['eye'].toSvg({
                                    class: 'font-small-4'
                                }) +
                                '</a>';
                        @endcan

                        var analyticsAction = '';
                        @if (auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Branch Admin'))
                            analyticsAction = (full['database'] ?
                                '<a href="subscriptions/' + data +
                                '/analytics" class="item-analytics ms-1" title="عرض تحليلات البيانات">' +
                                feather.icons['bar-chart-2'].toSvg({
                                    class: 'font-small-4 text-info'
                                }) +
                                '</a>' : '');
                        @endif

                        return dropdown + viewAction + analyticsAction +
                            '<a href="https://wa.me/+2' + full['phone'] +
                            '" class="item-whatsapp ms-1" target="_blank" title="WhatsApp">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="#25D366" class="font-small-4">' +
                            '<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>' +
                            '</svg>' +
                            '</a>';
                    }
                });
            } else {
                // Column definitions for non-Super Admin
                columnDefs.push({
                    // Last login
                    targets: 4,
                    render: function(data, type, full, meta) {
                        return data ? data : 'لا يوجد';
                    }
                }, {
                    // Login counter - no special formatting needed
                    targets: 5
                }, {
                    // Membership (العضوية) - replaces Status for non-Super Admin
                    targets: 6,
                    render: function(data, type, full, meta) {
                        var $status_number = full['is_enabled'];
                        var $status = {
                            0: {
                                title: 'معطل',
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
                        return '<span class="badge rounded-pill ' + $status[$status_number].class +
                            '">' + $status[$status_number].title + '</span>';
                    }
                }, {
                    // Actions
                    targets: 7,
                    title: 'Actions',
                    orderable: false,
                    render: function(data, type, full, meta) {
                        var enabling = '';
                        if (full['is_enabled'] == 0) enabling = 'تفعيل المندوب';
                        else enabling = 'ايقاف المندوب';

                        var actions = '';

                        // For Branch Admin - only show enabling and change name
                        @if (auth()->user()->hasRole('Branch Admin'))
                            actions += '<a href="subscriptions/enabling/' + data +
                                '" class="dropdown-item">' +
                                feather.icons['file-text'].toSvg({
                                    class: 'font-small-4 me-50'
                                }) +
                                enabling + '</a>';

                            actions +=
                                '<a href="javascript:;" class="dropdown-item change-name-btn" data-subscription-id="' +
                                data + '">' +
                                feather.icons['edit'].toSvg({
                                    class: 'font-small-4 me-50'
                                }) +
                                'تغيير الاسم</a>';
                        @endif

                        var dropdown = '<div class="d-inline-flex">' +
                            '<a class="me-50 dropdown-toggle hide-arrow text-primary" data-bs-toggle="dropdown">' +
                            feather.icons['more-vertical'].toSvg({
                                class: 'font-small-4'
                            }) +
                            '</a>' +
                            '<div class="dropdown-menu dropdown-menu-end">' +
                            actions +
                            '</div>' +
                            '</div>';

                        var viewAction = '';
                        return dropdown + viewAction +
                            '<a href="https://wa.me/+2' + full['phone'] +
                            '" class="item-whatsapp ms-1" target="_blank" title="WhatsApp">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="#25D366" class="font-small-4">' +
                            '<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>' +
                            '</svg>' +
                            '</a>';
                    }
                });
            }

            if (dt_basic_table.length) {
                var dt_basic = dt_basic_table.DataTable({
                    ajax: ajaxRequest,
                    searching: false,
                    columns: columns,
                    columnDefs: columnDefs,
                    order: [
                        [2, 'desc']
                    ],
                    dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    displayLength: 100,
                    lengthMenu: [7, 10, 25, 50, 75, 100],
                    buttons: [{
                        text: feather.icons['clipboard'].toSvg({
                            class: 'me-50 font-small-4'
                        }) + 'تصدير',
                        className: 'create-new btn btn-primary',
                        action: function(e, dt, node, config) {
                            window.location.href = './subscriptions/export';
                        },
                        init: function(api, node, config) {
                            $(node).removeClass('btn-secondary');
                        }
                    }],
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
                                    return col.title !== '' ?
                                        '<tr data-dt-row="' + col.rowIdx +
                                        '" data-dt-column="' + col.columnIndex + '">' +
                                        '<td>' + col.title + ':' + '</td> ' +
                                        '<td>' + col.data + '</td>' +
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
                            previous: '&nbsp;',
                            next: '&nbsp;'
                        }
                    },
                    drawCallback: function() {
                        $('[data-bs-toggle="tooltip"]').tooltip();
                    }
                });
                $('div.head-label').html('<h6 class="mb-0">الاشتراكات</h6>');
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
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ms-1'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    dt_basic.row($(that).parents('tr')).remove().draw();
                    $.ajax({
                        url: window.location.pathname + '/' + id,
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
                            text: 'تم حذف الاشتراك.',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                    }
                });
            });

            // Change Name Modal Handler
            $('.datatables-basic tbody').on('click', '.change-name-btn', function() {
                var subscriptionId = $(this).data('subscription-id');
                var currentName = dt_basic.row($(this).parents('tr')).data().name || '';

                $('#subscriptionName').val(currentName);
                $('#changeNameForm').attr('action', 'subscriptions/update-name/' + subscriptionId);
                $('#changeNameModal').modal('show');
            });

            // Copy to clipboard functionality
            $(document).on('click', '.copy-text', function() {
                var textToCopy = $(this).data('copy');

                navigator.clipboard.writeText(textToCopy).then(function() {
                    toastr.success('تم النسخ بنجاح', 'نجاح', {
                        closeButton: true,
                        tapToDismiss: false,
                        rtl: true
                    });
                }).catch(function(err) {
                    var tempInput = $('<textarea>');
                    $('body').append(tempInput);
                    tempInput.val(textToCopy).select();
                    document.execCommand('copy');
                    tempInput.remove();

                    toastr.success('تم النسخ بنجاح', 'نجاح', {
                        closeButton: true,
                        tapToDismiss: false,
                        rtl: true
                    });
                });
            });
        });
    </script>
@endsection
