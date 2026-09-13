@php
    $locale = app()->getLocale();
@endphp
@extends('common::layouts.master')

@section('title', __('driver::general.index'))

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
                        <h4 class="card-title">{{ __('driver::general.drivers') }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                        href="{{ route('admin.dashboard') }}">{{ __('driver::general.home') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ __('driver::general.drivers') }}</li>
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
                    <div class="col-md-3">
                        <label class="form-label">{{ __('driver::attribute.name') }}</label>
                        <input type="text" name="name" class="form-control"
                            placeholder="{{ __('driver::attribute.name') }}" value="{{ request('name') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('driver::attribute.phone') }}</label>
                        <input type="text" name="phone" class="form-control"
                            placeholder="{{ __('driver::attribute.phone') }}" value="{{ request('phone') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">{{ __('driver::attribute.is_available') }}</label>
                        <select name="is_available" class="form-select">
                            <option value="">{{ __('driver::general.select_availability') }}</option>
                            <option value="1" {{ request('is_available') === '1' ? 'selected' : '' }}>
                                {{ __('driver::general.available') }}</option>
                            <option value="0" {{ request('is_available') === '0' ? 'selected' : '' }}>
                                {{ __('driver::general.unavailable') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">{{ __('driver::attribute.is_active') }}</label>
                        <select name="is_active" class="form-select">
                            <option value="">{{ __('driver::general.select_status') }}</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>
                                {{ __('driver::general.active') }}</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>
                                {{ __('driver::general.inactive') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-1">{{ __('driver::general.search') }}</button>
                        <a href="{{ route('admin.driver.index') }}"
                            class="btn btn-outline-secondary">{{ __('driver::general.reset') }}</a>
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
                                <th>{{ __('driver::attribute.id') }}</th>
                                <th>{{ __('driver::attribute.name') }}</th>
                                <th>{{ __('driver::attribute.phone') }}</th>
                                <th>{{ __('driver::attribute.license_id') }}</th>
                                <th>{{ __('driver::attribute.created_at') }}</th>
                                <th>{{ __('driver::attribute.is_active') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!--/ Basic table -->
    {{ $drivers->withQueryString()->links() }}
@endsection

@section('js')
    @include('common::includes.datatable')
    <script>
        $(function() {
            'use strict';
            var token = $('meta[name="csrf-token"]').attr('content');
            var url = new URL(window.location.href);
            var page = url.searchParams.get("page");
            var name = url.searchParams.get("name");
            var phone = url.searchParams.get("phone");
            var is_available = url.searchParams.get("is_available");
            var is_active = url.searchParams.get("is_active");
            var ajaxRequest = "drivers?";
            if (page != null) ajaxRequest += "page=" + page + '&';
            if (name != null) ajaxRequest += "name=" + name + '&';
            if (phone != null) ajaxRequest += "phone=" + phone + '&';
            if (is_available != null) ajaxRequest += "is_available=" + is_available + '&';
            if (is_active != null) ajaxRequest += "is_active=" + is_active + '&';
            var dt_basic_table = $('.datatables-basic'),
                dt_date_table = $('.dt-date');
            if (dt_basic_table.length) {
                var dt_basic = dt_basic_table.DataTable({
                    ajax: ajaxRequest,
                    searching: false,
                    columns: [{
                            data: 'id'
                        },
                        {
                            data: 'id'
                        },
                        {
                            data: 'id'
                        },
                        {
                            data: 'name'
                        },
                        {
                            data: 'phone'
                        },
                        {
                            data: 'license_id',
                            defaultContent: '-'
                        },
                        {
                            data: 'created_at'
                        },
                        {
                            data: 'is_active'
                        },
                        {
                            data: 'id'
                        },
                    ],
                    columnDefs: [{
                            className: 'control',
                            orderable: false,
                            responsivePriority: 2,
                            targets: 0
                        },
                        {
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
                                var $user_img = full['image'],
                                    $name = data || '';
                                if ($user_img) {
                                    var $output =
                                        '<img src="' + $user_img +
                                        '" alt="Avatar" width="32" height="32">';
                                } else {
                                    var stateNum = full['is_active'] ? 1 : 0;
                                    var states = ['secondary', 'primary'];
                                    var $state = states[stateNum],
                                        $initials = $name.match(/\b\w/g) || [];
                                    $initials = (($initials.shift() || '') + ($initials.pop() ||
                                        '')).toUpperCase();
                                    $output = '<span class="avatar-content">' + $initials +
                                        '</span>';
                                }

                                var colorClass = $user_img === null ? ' bg-light-' + $state + ' ' : '';
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
                            // Status Toggle
                            targets: -2,
                            render: function(data, type, full, meta) {
                                var checked = full['is_active'] == 1 ? 'checked' : '';
                                return '<div class="form-check form-switch">' +
                                    '<input type="checkbox" class="form-check-input change-status" ' +
                                    checked + '>' +
                                    '</div>';
                            }
                        },
                        {
                            // Actions
                            targets: -1,
                            title: '{{ __('driver::general.actions') }}',
                            orderable: false,
                            render: function(data, type, full, meta) {
                                return (
                                    '<div class="d-inline-flex">' +
                                    '<a class="pe-1 dropdown-toggle hide-arrow text-primary" data-bs-toggle="dropdown">' +
                                    feather.icons['more-vertical'].toSvg({
                                        class: 'font-small-4'
                                    }) +
                                    '</a>' +
                                    '<div class="dropdown-menu dropdown-menu-end">'
                                    @can('Delete-driver')
                                        +
                                        '<a href="javascript:;" class="dropdown-item delete-record">' +
                                        feather.icons['trash-2'].toSvg({
                                                class: 'font-small-4 me-50'
                                            }) +
                                            '{{ __('driver::general.delete') }}</a>'
                                    @endcan +
                                    '</div>' +
                                    '</div>'
                                    @can('Edit-driver')
                                        +
                                        '<a href="drivers/' + data +
                                            '/edit" class="item-edit">' +
                                            feather.icons['edit'].toSvg({
                                                class: 'font-small-4 me-50'
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
                    bPaginate: false,
                    buttons: [
                        @can('Create-driver')
                            {
                                text: feather.icons['plus'].toSvg({
                                    class: 'me-50 font-small-4'
                                }) + '{{ __('driver::general.create') }}',
                                className: 'create-new btn btn-primary',
                                action: function(e, dt, node, config) {
                                    window.location.href = './drivers/create';
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
                                    return 'Details of ' + data.name;
                                }
                            }),
                            type: 'column',
                            renderer: function(api, rowIdx, columns) {
                                var data = $.map(columns, function(col, i) {
                                    return col.title !== '' ?
                                        '<tr data-dt-row="' + col.rowIdx + '" data-dt-column="' + col.columnIndex + '">' +
                                        '<td>' + col.title + ':</td> ' +
                                        '<td>' + col.data + '</td>' +
                                        '</tr>' : '';
                                }).join('');

                                return data ? $('<table class="table"/>').append('<tbody>' + data + '</tbody>') : false;
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
                $('div.head-label').html('<h6 class="mb-0">{{ __('driver::general.main_data') }}</h6>');
            }

            // Toggle Status
            $('.datatables-basic tbody').on('change', '.change-status', function() {
                var id = dt_basic.row($(this).parents('tr')).data().id;
                $.ajax({
                    url: '/admin/drivers/' + id + '/activate',
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
                Swal.fire({
                    title: '{{ __('driver::general.sure_delete') }}',
                    text: '{{ __('driver::general.cant_revert') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{ __('driver::general.yes_delete') }}',
                    cancelButtonText: '{{ __('driver::general.cancel') }}',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ms-1'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            url: '/admin/drivers/' + id,
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
