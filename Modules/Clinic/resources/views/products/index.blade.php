@php
    $locale = app()->getLocale();
@endphp
@extends('common::layouts.master')

@section('title', __('clinic::general.products'))

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/vendors/css/tables/datatable/buttons.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
@endsection

@section('content')
    {{-- Breadcrumb --}}
    <section id="default-breadcrumb">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('clinic::general.products') }} - {{ $clinic->getTranslation('title', $locale) }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                        href="{{ route('admin.dashboard') }}">{{ __('clinic::general.home') }}</a></li>
                                <li class="breadcrumb-item"><a
                                        href="{{ route('admin.clinic.index') }}">{{ __('clinic::general.clinics') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ __('clinic::general.products') }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- Breadcrumb --}}

    <!-- Filter Card -->
    <section class="card mb-2">
        <div class="card-body">
            <form>
                <div class="row g-2">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('product::attribute.title') }}</label>
                        <input type="text" name="title" class="form-control"
                            placeholder="{{ __('product::attribute.title') }}" value="{{ request('title') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('product::attribute.is_active') }}</label>
                        <select name="is_active" class="form-select">
                            <option value="">{{ __('clinic::general.select_status') ?? 'الكل' }}</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>مفعل</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>غير مفعل</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-1">{{ __('clinic::general.search') }}</button>
                        <a href="{{ route('admin.clinic.products.index', $clinic->id) }}"
                            class="btn btn-outline-secondary">{{ __('clinic::general.reset') }}</a>
                    </div>
                </div>
            </form>
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
                                <th>{{ __('product::attribute.id') }}</th>
                                <th>{{ __('product::attribute.title') }}</th>
                                <th>{{ __('product::attribute.category_id') ?? 'Category' }}</th>
                                <th>{{ __('product::attribute.price') ?? 'Price' }}</th>
                                <th>{{ __('product::attribute.is_active') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!--/ Basic table -->
    {{ $products->withQueryString()->links() }}

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-5 pb-5">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">{{ __('clinic::general.import') ?? 'Import Products' }}</h1>
                    </div>
                    <form method="POST" action="{{ route('admin.clinic.products.import', $clinic->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-1">
                            <label class="form-label" for="file">{{ __('clinic::general.excel_file') ?? 'Excel File' }}</label>
                            <input type="file" id="file" name="file" class="form-control" accept=".xlsx, .xls, .csv" required />
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mt-2">{{ __('clinic::general.submit') ?? 'Submit' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @include('common::includes.datatable')
    <script>
        $(function() {
            'use strict';
            var token = $('meta[name="csrf-token"]').attr('content');
            var url = new URL(window.location.href);
            var page = url.searchParams.get("page");
            var title = url.searchParams.get("title");
            var is_active = url.searchParams.get("is_active");
            var ajaxRequest = "{{ route('admin.clinic.products.index', $clinic->id) }}?";
            if (page != null) ajaxRequest += "page=" + page + '&';
            if (title != null) ajaxRequest += "title=" + title + '&';
            if (is_active != null) ajaxRequest += "is_active=" + is_active + '&';
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
                            data: 'category_id'
                        },
                        {
                            data: 'pivot.price'
                        },
                        {
                            data: 'pivot.is_active'
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
                            // Avatar image/badge, Name
                            targets: 3,
                            responsivePriority: 4,
                            render: function(data, type, full, meta) {
                                var $user_img = null;
                                if (full.seller_images && full.seller_images.length > 0) {
                                    $user_img = full.seller_images[0].image;
                                } else if (full.images && full.images.length > 0) {
                                    $user_img = full.images[0].image;
                                }

                                var $name = '';
                                if (full.pivot && full.pivot.title) {
                                    var pivotTitle = typeof full.pivot.title === 'object'
                                        ? (full.pivot.title['{{ $locale }}'] ?? full.pivot.title['ar'] ?? full.pivot.title['en'])
                                        : full.pivot.title;
                                    if (pivotTitle) {
                                        $name = pivotTitle;
                                    }
                                }
                                if (!$name) {
                                    $name = typeof data === 'object' ? (data['{{ $locale }}'] ?? data['ar'] ?? data['en'] ?? '') : data;
                                }

                                if ($user_img) {
                                    // For Avatar image
                                    var $output =
                                        '<img src="' + $user_img +
                                        '" alt="Avatar" width="32" height="32">';
                                } else {
                                    // For Avatar badge
                                    var isActive = full.pivot ? full.pivot.is_active : (full.is_active ?? 1);
                                    var stateNum = isActive ? 1 : 0;
                                    var states = ['info', 'primary'];
                                    var $state = states[stateNum];
                                    var $initials = ($name.match(/\b\w/g) || []).slice(0, 2).join('').toUpperCase();
                                    $output = '<span class="avatar-content">' + $initials +
                                        '</span>';
                                }

                                var colorClass = $user_img === null ? ' bg-light-' + $state + ' ' : '';
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
                                    '</div>' +
                                    '</div>';
                                return $row_output;
                            }
                        },
                        {
                            // Category
                            targets: 4,
                            render: function(data, type, full, meta) {
                                if (full.category) {
                                    var catTitle = typeof full.category.title === 'object'
                                        ? (full.category.title['{{ $locale }}'] ?? full.category.title['ar'] ?? full.category.title['en'] ?? '')
                                        : (full.category.title ?? '');
                                    return '<span class="badge badge-light-info">' + catTitle + '</span>';
                                }
                                return data ?? '-';
                            }
                        },
                        {
                            // Price
                            targets: 5,
                            render: function(data, type, full, meta) {
                                var price = (full.pivot && full.pivot.price !== undefined) ? full.pivot.price : (full.price ?? 0);
                                return '<span class="fw-bold">' + price + '</span>';
                            }
                        },
                        {
                            // Status Toggle
                            targets: -2,
                            render: function(data, type, full, meta) {
                                var isActive = full.pivot ? full.pivot.is_active : full.is_active;
                                var checked = isActive == 1 ? 'checked' : '';
                                return '<div class="form-check form-switch">' +
                                    '<input type="checkbox" class="form-check-input change-status" ' +
                                    checked + '>' +
                                    '</div>';
                            }
                        },
                        {
                            // Actions
                            targets: -1,
                            title: '{{ __('clinic::general.actions') }}',
                            orderable: false,
                            render: function(data, type, full, meta) {
                                var editUrl = "{{ route('admin.clinic.products.edit', [$clinic->id, ':id']) }}".replace(':id', data);
                                return (
                                    '<div class="d-inline-flex">' +
                                    '<a class="pe-1 dropdown-toggle hide-arrow text-primary" data-bs-toggle="dropdown">' +
                                    feather.icons['more-vertical'].toSvg({
                                        class: 'font-small-4'
                                    }) +
                                    '</a>' +
                                    '<div class="dropdown-menu dropdown-menu-end">' +
                                    '<a href="javascript:;" class="dropdown-item delete-record">' +
                                    feather.icons['trash-2'].toSvg({
                                        class: 'font-small-4 me-50'
                                    }) +
                                    '{{ __('clinic::general.delete') }}</a>' +
                                    '</div>' +
                                    '</div>' +
                                    '<a href="' + editUrl + '" class="item-edit">' +
                                    feather.icons['edit'].toSvg({
                                        class: 'font-small-4 me-50'
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
                    bPaginate: false,
                    buttons: [
                        {
                            text: feather.icons['download-cloud'].toSvg({
                                class: 'me-50 font-small-4'
                            }) + '{{ __('clinic::general.import_all_products') }}',
                            className: 'btn btn-outline-info me-1',
                            action: function(e, dt, node, config) {
                                var form = $(
                                    '<form method="POST" action="{{ route('admin.clinic.products.import-all', $clinic->id) }}" style="display:none;">' +
                                    '{{ csrf_field() }}' +
                                    '</form>');
                                $('body').append(form);
                                form.submit();
                            },
                            init: function(api, node, config) {
                                $(node).removeClass('btn-secondary');
                            }
                        },
                        {
                            text: feather.icons['upload'].toSvg({
                                class: 'me-50 font-small-4'
                            }) + '{{ __('clinic::general.import') ?? 'Import Excel' }}',
                            className: 'btn btn-outline-primary me-1',
                            attr: {
                                'data-bs-toggle': 'modal',
                                'data-bs-target': '#importModal'
                            },
                            init: function(api, node, config) {
                                $(node).removeClass('btn-secondary');
                            }
                        },
                        {
                            text: feather.icons['download'].toSvg({
                                class: 'me-50 font-small-4'
                            }) + '{{ __('clinic::general.export') ?? 'Export Excel' }}',
                            className: 'btn btn-outline-success',
                            action: function(e, dt, node, config) {
                                window.location.href = "{{ route('admin.clinic.products.export', $clinic->id) }}";
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
                                    var data = row.data();
                                    var title = typeof data['title'] === 'object'
                                        ? (data['title']['{{ $locale }}'] ?? data['title']['ar'] ?? data['title']['en'] ?? '')
                                        : data['title'];
                                    return 'Details of ' + title;
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
                            previous: '&nbsp;',
                            next: '&nbsp;'
                        }
                    }
                });
                $('div.head-label').html('<h6 class="mb-0">{{ __('clinic::general.products') }}</h6>');
            }

            // Toggle Status
            $('.datatables-basic tbody').on('change', '.change-status', function() {
                var id = dt_basic.row($(this).parents('tr')).data().id;
                $.ajax({
                    url: '/admin/clinics/{{ $clinic->id }}/products/' + id + '/activate',
                    type: 'PATCH',
                    data: {
                        _token: token
                    }
                }).done(function(response) {
                    successAlert(response.message);
                }).fail(function() {
                    errorAlert();
                });
            });

            // Delete Record
            $('.datatables-basic tbody').on('click', '.delete-record', function() {
                let that = this;
                var id = dt_basic.row($(this).parents('tr')).data().id;
                Swal.fire({
                    title: '{{ __('clinic::general.sure_delete') }}',
                    text: '{{ __('clinic::general.cant_revert') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{ __('clinic::general.yes_delete') }}',
                    cancelButtonText: '{{ __('clinic::general.cancel') }}',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ms-1'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            url: '/admin/clinics/{{ $clinic->id }}/products/' + id,
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: token
                            }
                        }).done(function(response) {
                            dt_basic.row($(that).parents('tr')).remove().draw();
                            successAlert(response.message);
                        }).fail(function() {
                            errorAlert();
                        });
                    }
                });
            });
        });
    </script>
@endsection
