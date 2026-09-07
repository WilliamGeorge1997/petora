@php
    $locale = app()->getLocale();
    $serviceTitle = $clinicService->service ? $clinicService->service->getTranslation('title', $locale) : '-';
@endphp
@extends('common::layouts.master')

@section('title', __('clinic::general.schedule.title'))

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/vendors/css/tables/datatable/buttons.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css') }}">
@endsection

@section('content')
    {{-- Breadcrumb --}}
    <section id="default-breadcrumb">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('clinic::general.schedule.title') }} - {{ $serviceTitle }} ({{ $clinic->getTranslation('title', $locale) }})</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('clinic::general.home') }}</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.clinic.index') }}">{{ __('clinic::general.clinics') }}</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.clinic.services.index', $clinic->id) }}">{{ __('clinic::general.services') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ __('clinic::general.schedule.title') }}</li>
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
                                <th>{{ __('common::general.id') }}</th>
                                <th>{{ __('clinic::general.schedule.day') }}</th>
                                <th>{{ __('clinic::general.schedule.times') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    @include('common::includes.datatable')
    <script>
        $(function() {
            'use strict';
            var token = $('meta[name="csrf-token"]').attr('content');
            var ajaxRequest = "{{ route('admin.clinic.services.schedules.index', [$clinic->id, $clinicService->id]) }}";
            var dayTranslations = {
                'saturday': '{{ __('clinic::general.schedule.days.saturday') }}',
                'sunday': '{{ __('clinic::general.schedule.days.sunday') }}',
                'monday': '{{ __('clinic::general.schedule.days.monday') }}',
                'tuesday': '{{ __('clinic::general.schedule.days.tuesday') }}',
                'wednesday': '{{ __('clinic::general.schedule.days.wednesday') }}',
                'thursday': '{{ __('clinic::general.schedule.days.thursday') }}',
                'friday': '{{ __('clinic::general.schedule.days.friday') }}'
            };

            var dt_basic_table = $('.datatables-basic');
            if (dt_basic_table.length) {
                var dt_basic = dt_basic_table.DataTable({
                    ajax: ajaxRequest,
                    searching: false,
                    columns: [
                        { data: 'id' }, // for responsive show
                        { data: 'id' }, // for checkbox
                        { data: 'id' }, // used for sorting so will hide this column
                        { data: 'day' },
                        { data: 'times' },
                        { data: 'id' }  // Actions
                    ],
                    columnDefs: [
                        {
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
                            targets: 3,
                            responsivePriority: 4,
                            render: function(data, type, full, meta) {
                                var dayName = dayTranslations[data] || data;
                                return '<span class="fw-bold fs-6">' + dayName + '</span>';
                            }
                        },
                        {
                            targets: 4,
                            render: function(data, type, full, meta) {
                                if (data && data.length > 0) {
                                    var badges = '';
                                    data.forEach(function(item) {
                                        var startTime = item.from ? item.from.substring(0, 5) : '';
                                        var endTime = item.to ? item.to.substring(0, 5) : '';
                                        badges += '<span class="badge rounded-pill bg-light-primary text-primary me-50 fs-6 py-50 px-1 mb-25">' +
                                            startTime + ' - ' + endTime +
                                            '</span>';
                                    });
                                    return badges;
                                }
                                return '<span class="text-muted">{{ __('clinic::general.schedule.empty') }}</span>';
                            }
                        },
                        {
                            // Actions
                            targets: -1,
                            title: '{{ __('common::general.actions') }}',
                            orderable: false,
                            render: function(data, type, full, meta) {
                                return (
                                    '<div class="d-inline-flex">' +
                                    '<a class="pe-1 dropdown-toggle hide-arrow text-primary" data-bs-toggle="dropdown">' +
                                    feather.icons['more-vertical'].toSvg({ class: 'font-small-4' }) +
                                    '</a>' +
                                    '<div class="dropdown-menu dropdown-menu-end">' +
                                    '<a href="javascript:;" class="dropdown-item delete-record">' +
                                    feather.icons['trash-2'].toSvg({ class: 'font-small-4 me-50' }) +
                                    '{{ __('common::general.delete') }}</a>' +
                                    '</div>' +
                                    '</div>' +
                                    '<a href="/admin/clinics/{{ $clinic->id }}/services/{{ $clinicService->id }}/schedules/' + data + '/edit" class="item-edit">' +
                                    feather.icons['edit'].toSvg({ class: 'font-small-4 me-50' }) +
                                    '</a>'
                                );
                            }
                        }
                    ],
                    order: [[2, 'desc']],
                    dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    displayLength: 50,
                    lengthMenu: [7, 10, 25, 50, 75, 100],
                    bPaginate: false,
                    buttons: [
                        {
                            text: feather.icons['plus'].toSvg({ class: 'me-50 font-small-4' }) + '{{ __('clinic::general.schedule.add') }}',
                            className: 'create-new btn btn-primary me-1',
                            action: function(e, dt, node, config) {
                                window.location.href = '{{ route('admin.clinic.services.schedules.create', [$clinic->id, $clinicService->id]) }}';
                            },
                            init: function(api, node, config) {
                                $(node).removeClass('btn-secondary');
                            }
                        },
                        {
                            text: feather.icons['arrow-left'].toSvg({ class: 'me-50 font-small-4' }) + '{{ __('common::general.back') }}',
                            className: 'btn btn-outline-secondary',
                            action: function(e, dt, node, config) {
                                window.location.href = '{{ route('admin.clinic.services.index', $clinic->id) }}';
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
                                    return 'Details of ' + (dayTranslations[data['day']] || data['day']);
                                }
                            }),
                            type: 'column',
                            renderer: function(api, rowIdx, columns) {
                                var data = $.map(columns, function(col, i) {
                                    return col.title !== ''
                                        ? '<tr data-dt-row="' + col.rowIdx + '" data-dt-column="' + col.columnIndex + '">' +
                                          '<td>' + col.title + ':' + '</td> ' +
                                          '<td>' + col.data + '</td>' +
                                          '</tr>' 
                                        : '';
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
                
                $('div.head-label').html('<h6 class="mb-0">{{ __('clinic::general.schedule.title') }}</h6>');
            }

            // Delete Record
            $('.datatables-basic tbody').on('click', '.delete-record', function() {
                var that = this;
                var id = dt_basic.row($(this).parents('tr')).data().id;
                Swal.fire({
                    title: '{{ __('common::general.sure_delete') }}',
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
                            url: '/admin/clinics/{{ $clinic->id }}/services/{{ $clinicService->id }}/schedules/' + id,
                            type: 'DELETE',
                            data: { _token: token }
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
