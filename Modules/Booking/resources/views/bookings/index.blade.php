@php
    $locale = app()->getLocale();
@endphp
@extends('common::layouts.master')

@section('title', __('booking::general.booking.index'))

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css" 
        href="{{ asset('admin/vendors/css/tables/datatable/buttons.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css" 
        href="{{ asset('admin/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
@endsection

@section('content')
    {{-- Breadcrumb --}}
    <section id="default-breadcrumb">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('booking::general.booking.index') }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                        href="{{ route('admin.dashboard') }}">{{ __('booking::general.home') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ __('booking::general.booking.index') }}</li>
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
        <div class="card-header">
            <h3>{{ __('booking::general.booking.filter') }}</h3>
        </div>
        <div class="card-body">
            <form>
                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label" for="booking_no">{{ __('booking::attribute.booking_no') }}</label>
                        <input id="booking_no" type="search" class="form-control" placeholder="{{ __('booking::attribute.booking_no') }}"
                            name="booking_no" value="{{ request('booking_no') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('booking::attribute.status') }}</label>
                        <select name="booking_status_id" class="form-select">
                            <option value="">{{ __('booking::general.select_status') }}</option>
                            @foreach($viewModel->bookingStatuses() as $bookingStatus)
                                <option value="{{ $bookingStatus->id }}" {{ request('booking_status_id') == $bookingStatus->id ? 'selected' : '' }}>
                                    {{ $bookingStatus->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-1">{{ __('booking::general.search') }}</button>
                        <a href="{{ route('admin.booking.index') }}" class="btn btn-outline-secondary">{{ __('booking::general.reset') }}</a>
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
                                <th></th>
                                <th>{{ __('booking::attribute.booking_no') }}</th>
                                <th>{{ __('booking::attribute.clinic_id') }}</th>
                                <th>{{ __('booking::attribute.total') }}</th>
                                <th>{{ __('booking::attribute.payment_method_id') }}</th>
                                <th>{{ __('booking::attribute.status') }}</th>
                                <th>{{ __('booking::attribute.created_at') }}</th>
                                <th>{{ __('booking::general.actions') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!--/ Basic table -->
    {{ $bookings->withQueryString()->links() }}
@endsection

@section('js')
    @include('common::includes.datatable')

    <script>
        $(function() {
            'use strict';
            var token = $('meta[name="csrf-token"]').attr('content');
            var url = new URL(window.location.href);
            var booking_status_id = url.searchParams.get("booking_status_id");
            var clinic_id = url.searchParams.get("clinic_id");
            var client_id = url.searchParams.get("client_id");
            var booking_no = url.searchParams.get("booking_no");
            var page = url.searchParams.get("page");
            var ajaxRequest = "bookings?";
            if (page != null) ajaxRequest += "page=" + page + '&';
            if (booking_status_id != null) ajaxRequest += 'booking_status_id=' + booking_status_id + '&';
            if (clinic_id != null) ajaxRequest += 'clinic_id=' + clinic_id + '&';
            if (client_id != null) ajaxRequest += 'client_id=' + client_id + '&';
            if (booking_no != null) ajaxRequest += 'booking_no=' + booking_no + '&';

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
                            data: 'booking_no'
                        },
                        {
                            data: 'clinic.title.{{ $locale }}'
                        },
                        {
                            data: 'total'
                        },
                        {
                            data: 'payment_method.title.{{ $locale }}'
                        },
                        {
                            data: 'booking_status.title.{{ $locale }}'
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
                            // Actions
                            targets: -1,
                            title: '{{ __('booking::general.actions') }}',
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
                                    @can('Edit-booking')
                                        +
                                        '<a href="bookings/' + data + '/edit" class="dropdown-item">' +
                                        feather.icons['edit'].toSvg({
                                                class: 'font-small-4 me-50'
                                            }) +
                                            '{{ __('booking::general.edit') }}</a>'
                                    @endcan
                                    @can('Delete-booking')
                                        +
                                        '<a href="javascript:;" class="dropdown-item delete-record">' +
                                        feather.icons['trash-2'].toSvg({
                                                class: 'font-small-4 me-50'
                                            }) +
                                            '{{ __('booking::general.delete') }}</a>'
                                    @endcan +
                                    '</div>' +
                                    '</div>' +
                                    '<a href="bookings/' + data + '" class="pe-1 text-primary">' +
                                    feather.icons['eye'].toSvg({
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
                    bPaginate: false,
                    buttons: [],
                    responsive: {
                        details: {
                            display: $.fn.dataTable.Responsive.display.modal({
                                header: function(row) {
                                    var data = row.data();
                                    return 'Details of ' + (data['booking_no'] || data['id']);
                                }
                            }),
                            type: 'column',
                            renderer: function(api, rowIdx, columns) {
                                var data = $.map(columns, function(col, i) {
                                    return col.title !== '' ?
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
                $('div.head-label').html('<h6 class="mb-0">{{ __('booking::general.main_data') }}</h6>');
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
                Swal.fire({
                    title: '{{ __('booking::general.sure_delete') }}',
                    text: '{{ __('booking::general.cant_revert') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{ __('booking::general.yes_delete') }}',
                    cancelButtonText: '{{ __('booking::general.cancel') }}',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ms-1'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            url: '/admin/bookings/' + id,
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
