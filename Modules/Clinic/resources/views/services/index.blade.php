@php
    $locale = app()->getLocale();
@endphp
@extends('common::layouts.master')

@section('title', __('clinic::general.services'))

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/vendors/css/tables/datatable/buttons.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css') }}">
@endsection

@section('content')
    {{-- Breadcrumb --}}
    <section id="default-breadcrumb">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('clinic::general.services') }} -
                            {{ $clinic->getTranslation('title', $locale) }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                        href="{{ route('admin.dashboard') }}">{{ __('clinic::general.home') }}</a></li>
                                <li class="breadcrumb-item"><a
                                        href="{{ route('admin.clinic.index') }}">{{ __('clinic::general.clinics') }}</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">{{ __('clinic::general.services') }}
                                </li>
                            </ol>
                        </nav>
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
                                <th>{{ __('service::attribute.id') }}</th>
                                <th>{{ __('service::attribute.title') }}</th>
                                <th>{{ __('service::attribute.price') }}</th>
                                <th>{{ __('service::attribute.duration') }}</th>
                                <th>{{ __('service::attribute.is_active') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <x-common::modal id="importModal" :title="__('clinic::general.import')">
        <form method="POST" action="{{ route('admin.clinic.services.import', $clinic->id) }}"
            enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="mb-1">
                <label class="form-label" for="file">{{ __('clinic::general.excel_file') }}</label>
                <input type="file" id="file" name="file" class="form-control" accept=".xlsx, .xls, .csv"
                    required />
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-2">{{ __('clinic::general.submit') }}</button>
        </form>
    </x-common::modal>
@endsection

@section('js')
    @include('common::includes.datatable')
    <script>
        $(function() {
            'use strict';
            var token = $('meta[name="csrf-token"]').attr('content');
            var ajaxRequest = "{{ route('admin.clinic.services.index', $clinic->id) }}";
            var dt_basic_table = $('.datatables-basic');
            if (dt_basic_table.length) {
                var dt_basic = dt_basic_table.DataTable({
                    ajax: ajaxRequest,
                    searching: false,
                    columns: [{
                            data: 'id'
                        }, // for responsive
                        {
                            data: 'id'
                        }, // for checkbox
                        {
                            data: 'id'
                        }, // hidden, for sorting
                        {
                            data: 'service.title'
                        },
                        {
                            data: 'price'
                        },
                        {
                            data: 'duration'
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
                                var $user_img = full['service'] ? full['service']['image'] : null,
                                    $name = data.{{ $locale }};
                                if ($user_img) {
                                    // For Avatar image
                                    var $output =
                                        '<img src="' + $user_img +
                                        '" alt="Avatar" width="32" height="32">';
                                } else {
                                    // For Avatar badge
                                    var stateNum = full['is_active'];
                                    var states = ['info', 'primary'];
                                    var $state = states[stateNum],
                                        $initials = $name.match(/\b\w/g) || [];
                                    $initials = (($initials.shift() || '') + ($initials.pop() ||
                                        '')).toUpperCase();
                                    $output = '<span class="avatar-content">' + $initials +
                                        '</span>';
                                }

                                var colorClass = $user_img === null ? ' bg-light-' + $state + ' ' :
                                    '';
                                var $row_output =
                                    '<div class="d-flex justify-content-left align-items-center">' +
                                    '<div class="avatar ' + colorClass + ' me-1">' +
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
                            targets: 4,
                            render: function(data, type, full) {
                                return '<strong>' + parseFloat(data).toFixed(2) + '</strong>';
                            }
                        },
                        {
                            targets: 5,
                            render: function(data, type, full) {
                                return data ? data + ' {{ __('clinic::general.minute') }}' : '-';
                            }
                        },
                        {
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
                            targets: -1,
                            title: '{{ __('common::general.actions') }}',
                            orderable: false,
                            render: function(data, type, full, meta) {
                                var schedulesUrl = '/admin/clinics/{{ $clinic->id }}/services/' +
                                    full['id'] + '/schedules';
                                return (
                                    '<div class="d-inline-flex">' +
                                    '<a class="pe-1 dropdown-toggle hide-arrow text-primary" data-bs-toggle="dropdown">' +
                                    feather.icons['more-vertical'].toSvg({
                                        class: 'font-small-4'
                                    }) +
                                    '</a>' +
                                    '<div class="dropdown-menu dropdown-menu-end">' +
                                    '<a href="' + schedulesUrl + '" class="dropdown-item">' +
                                    feather.icons['clock'].toSvg({
                                        class: 'font-small-4 me-50'
                                    }) +
                                    '{{ __('clinic::general.schedule.title') }}</a>' +
                                    '<a href="/admin/clinics/{{ $clinic->id }}/services/' + full['id'] + '/edit" class="dropdown-item">' +
                                    feather.icons['edit'].toSvg({
                                        class: 'font-small-4 me-50'
                                    }) +
                                    '{{ __('clinic::general.update') }}</a>' +
                                    '<a href="javascript:;" class="dropdown-item delete-record">' +
                                    feather.icons['trash-2'].toSvg({
                                        class: 'font-small-4 me-50'
                                    }) +
                                    '{{ __('clinic::general.delete') }}</a>' +
                                    '</div>' +
                                    '</div>'
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
                    buttons: [{
                            text: feather.icons['download-cloud'].toSvg({
                                class: 'me-50 font-small-4'
                            }) + '{{ __('clinic::general.import_all') }}',
                            className: 'btn btn-outline-info',
                            action: function(e, dt, node, config) {
                                var form = $(
                                    '<form method="POST" action="{{ route('admin.clinic.services.import-all', $clinic->id) }}" style="display:none;">' +
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
                            }) + '{{ __('clinic::general.import') }}',
                            className: 'btn btn-outline-primary',
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
                            }) + '{{ __('clinic::general.export') }}',
                            className: 'btn btn-outline-success',
                            action: function(e, dt, node, config) {
                                window.location.href =
                                    '{{ route('admin.clinic.services.export', $clinic->id) }}';
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
                                    return '{{ __('clinic::general.services') }}';
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
                $('div.head-label').html('<h6 class="mb-0">{{ __('clinic::general.services') }}</h6>');
            }

            // Toggle Status
            $('.datatables-basic tbody').on('change', '.change-status', function() {
                var id = dt_basic.row($(this).parents('tr')).data().id;
                $.ajax({
                    url: '/admin/clinics/{{ $clinic->id }}/services/' + id + '/activate',
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
                    title: '{{ __('common::general.sure_delete') }}',
                    text: '{{ __('common::general.cant_revert') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{ __('common::general.yes_delete') }}',
                    cancelButtonText: '{{ __('common::general.cancel') }}',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ms-1'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            url: '/admin/clinics/{{ $clinic->id }}/services/' + id,
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
