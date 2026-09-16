@php
    $locale = app()->getLocale();
@endphp
@extends('common::layouts.master')

@section('title', __('order::general.orders'))

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
    {{-- Edit Modal --}}
    @include('order::ordermodals.edit')

    {{-- Breadcrumb --}}
    <section id="default-breadcrumb">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('order::general.orders') }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                        href="{{ route('admin.dashboard') }}">{{ __('order::general.home') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ __('order::general.orders') }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- Breadcrumb --}}

    {{-- Statistics --}}
    <section id="statistics">
        <div class="card">
            <div class="card-header pb-0">
                <h3>{{ __('order::general.statistics') }}</h3>
            </div>
            <div class="card-body p-0">
                <div class="col-12">
                    <div class="card card-statistics m-0">
                        <div class="card-body statistics-body">
                            <div class="row">
                                <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                    <div class="d-flex flex-row">
                                        <div class="avatar bg-light-primary me-2">
                                            <div class="avatar-content">
                                                <i data-feather="box" class="avatar-icon"></i>
                                            </div>
                                        </div>
                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0">{{ $totalCount ?? 0 }}</h4>
                                            <p class="card-text font-small-3 mb-0">{{ __('order::general.total') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                    <div class="d-flex flex-row">
                                        <div class="avatar bg-light-warning me-2">
                                            <div class="avatar-content">
                                                <i data-feather="clock" class="avatar-icon"></i>
                                            </div>
                                        </div>
                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0">{{ $sentCount ?? 0 }}</h4>
                                            <p class="card-text font-small-3 mb-0">{{ __('order::general.sent') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0">
                                    <div class="d-flex flex-row">
                                        <div class="avatar bg-light-success me-2">
                                            <div class="avatar-content">
                                                <i data-feather="check-circle" class="avatar-icon"></i>
                                            </div>
                                        </div>
                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0">{{ $doneCount ?? 0 }}</h4>
                                            <p class="card-text font-small-3 mb-0">{{ __('order::general.done') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-sm-6 col-12">
                                    <div class="d-flex flex-row">
                                        <div class="avatar bg-light-danger me-2">
                                            <div class="avatar-content">
                                                <i data-feather="x-circle" class="avatar-icon"></i>
                                            </div>
                                        </div>
                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0">{{ $cancelCount ?? 0 }}</h4>
                                            <p class="card-text font-small-3 mb-0">{{ __('order::general.cancelled') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filter Card -->
    <section class="card mb-2">
        <div class="card-header">
            <h3>{{ __('order::general.filter') }}</h3>
        </div>
        <div class="card-body">
            <form>
                <div class="row g-2">
                    <div class="col-md-4">
                        <label class="form-label" for="order_no">{{ __('order::attribute.order_no') }}</label>
                        <input id="order_no" type="search" class="form-control" placeholder="{{ __('order::attribute.order_no') }}"
                            name="order_no" value="{{ request('order_no') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('order::attribute.status') }}</label>
                        <select name="order_status_id" class="form-select">
                            <option value="">{{ __('order::general.select_status') }}</option>
                            @foreach($viewModel->orderStatuses() as $orderStatus)
                                <option value="{{ $orderStatus->id }}" {{ request('order_status_id') == $orderStatus->id ? 'selected' : '' }}>
                                    {{ $orderStatus->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-1">{{ __('order::general.search') }}</button>
                        <a href="{{ route('admin.order.index') }}" class="btn btn-outline-secondary">{{ __('order::general.reset') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </section>

    @if (!request()->has('order_status_id'))
        <section class="card">
            <div class="card-header d-flex justify-content-between align-items-sm-center align-items-start flex-sm-row flex-column">
                <div class="header-left">
                    <h4 class="card-title">{{ __('order::general.done_stats') }}</h4>
                </div>
                <div class="header-right d-flex align-items-center mt-sm-0 mt-1">
                    <i data-feather="calendar"></i>
                    <input type="text" onchange="getDateValues()"
                        class="form-control flat-picker border-0 shadow-none bg-transparent pe-0"
                        placeholder="YYYY-MM-DD" id="date_from_to" />
                </div>
            </div>
            <div class="card-body">
                <canvas class="bar-chart-ex chartjs" data-height="400"></canvas>
            </div>
        </section>
    @endif

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
                                <th>{{ __('order::attribute.order_no') }}</th>
                                <th>{{ __('order::attribute.store') }}</th>
                                <th>{{ __('order::attribute.total') }}</th>
                                <th>{{ __('order::attribute.quantity') }}</th>
                                <th>{{ __('order::attribute.payment_method_id') }}</th>
                                <th>{{ __('order::attribute.status') }}</th>
                                <th>{{ __('order::attribute.type') }}</th>
                                <th>{{ __('order::attribute.created_at') }}</th>
                                <th>{{ __('order::general.actions') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!--/ Basic table -->
    {{ $orders->withQueryString()->links() }}
@endsection

@section('js')
    @include('common::includes.datatable')
    <script src="{{ asset('admin/vendors/js/charts/chart.min.js') }}"></script>

    <script>
        $(function() {
            'use strict';
            var token = $('meta[name="csrf-token"]').attr('content');
            var url = new URL(window.location.href);
            var order_status_id = url.searchParams.get("order_status_id");
            var store_id = url.searchParams.get("store_id");
            var clinic_id = url.searchParams.get("clinic_id");
            var client_id = url.searchParams.get("client_id");
            var order_no = url.searchParams.get("order_no");
            var page = url.searchParams.get("page");
            var ajaxRequest = "orders?";
            if (page != null) ajaxRequest += "page=" + page + '&';
            if (order_status_id != null) ajaxRequest += 'order_status_id=' + order_status_id + '&';
            if (store_id != null) ajaxRequest += 'store_id=' + store_id + '&';
            if (clinic_id != null) ajaxRequest += 'clinic_id=' + clinic_id + '&';
            if (client_id != null) ajaxRequest += 'client_id=' + client_id + '&';
            if (order_no != null) ajaxRequest += 'order_no=' + order_no + '&';

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
                            data: 'store.title.{{ $locale }}'
                        },
                        {
                            data: 'total'
                        },
                        {
                            data: 'quantity'
                        },
                        {
                            data: 'payment_method.title.{{ $locale }}'
                        },
                        {
                            data: 'order_status.title.{{ $locale }}'
                        },
                        {
                            data: 'order_method.title.{{ $locale }}'
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
                            // Payment method label
                            targets: -5,
                            render: function(data, type, full, meta) {
                                return data ? data : 'Not defined yet.';
                            }
                        },
                        {
                            // Actions
                            targets: -1,
                            title: '{{ __('order::general.actions') }}',
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
                                    @can('Edit-order')
                                        +
                                        '<a href="orders/' + data + '/edit" class="dropdown-item">' +
                                        feather.icons['edit'].toSvg({
                                                class: 'font-small-4 me-50'
                                            }) +
                                            '{{ __('order::general.edit') }}</a>'
                                    @endcan
                                    @can('Delete-order')
                                        +
                                        '<a href="javascript:;" class="dropdown-item delete-record">' +
                                        feather.icons['trash-2'].toSvg({
                                                class: 'font-small-4 me-50'
                                            }) +
                                            '{{ __('order::general.delete') }}</a>'
                                    @endcan +
                                    '</div>' +
                                    '</div>' +
                                    '<a href="orders/' + data + '" class="pe-1 text-primary">' +
                                    feather.icons['eye'].toSvg({
                                        class: 'font-small-4'
                                    }) +
                                    '</a>' +
                                    '<button onclick="getOrder(' + full['order_status_id'] +
                                    ',' + full['id'] + ',' + (full['driver_id'] || 0) +
                                    ')" type="button" class="btn btn-icon btn-flat-primary" data-bs-toggle="modal" data-bs-target="#changeModal">' +
                                    feather.icons['edit'].toSvg({
                                        class: 'font-small-4'
                                    }) +
                                    '</button>'
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
                                    return 'Details of ' + (data['order_no'] || data['id']);
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
                $('div.head-label').html('<h6 class="mb-0">{{ __('order::general.main_data') }}</h6>');
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
                    title: '{{ __('order::general.sure_delete') }}',
                    text: '{{ __('order::general.cant_revert') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{ __('order::general.yes_delete') }}',
                    cancelButtonText: '{{ __('order::general.cancel') }}',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ms-1'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            url: '/admin/orders/' + id,
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

    <script>
        // Modal Order Details
        function getOrder(orderStatusId, orderId, driverId) {
            document.getElementById("order_id").value = orderId;
            let selectStatuses = document.getElementById("statuses");
            let driversSelect = document.getElementById("driver_id");
            let cancelButton = document.getElementById("cancel-button");

            if (selectStatuses) {
                selectStatuses.value = orderStatusId;
            }

            if (driversSelect) {
                driversSelect.value = driverId || '';
            }

            checkStatus();

            // Display cancel button only when status is pending / sent (1)
            if (orderStatusId == 1 && cancelButton) {
                cancelButton.style.display = "block";
            } else if (cancelButton) {
                cancelButton.style.display = "none";
            }
        }

        function enableButton() {
            let button = document.getElementById("next-status");
            if (button) {
                button.disabled = false;
            }
        }

        function checkStatus() {
            let selectStatuses = document.getElementById("statuses");
            if (!selectStatuses) return;
            let statusId = selectStatuses.value;
            let driversDiv = document.getElementById("drivers");
            if (driversDiv) {
                if (statusId == 3) {
                    driversDiv.style.display = 'block';
                } else {
                    driversDiv.style.display = 'none';
                }
            }
        }

        @if (!request()->has('order_status_id'))
            $(window).on('load', function() {
                'use strict';
                initializeOrderChart();
            });
        @endif

        function getDateValues() {
            const date = document.getElementById('date_from_to').value;
            const from = date.slice(0, 10);
            const to = date.slice(14, 24);
            if (to !== null && to !== '') {
                initializeOrderChart(from, to);
            }
        }

        function initializeOrderChart(from = null, to = null) {
            const barChartEx = $('.bar-chart-ex'),
                tooltipShadow = 'rgba(0, 0, 0, 0.25)',
                grid_line_color = 'rgba(200, 200, 200, 0.2)',
                labelColor = '#6e6b7b',
                successColorShade = '#28dac6',
                flatPicker = $('.flat-picker');

            if (flatPicker.length) {
                if (from == null && to == null) {
                    var now = new Date();
                    var dd = String(now.getDate()).padStart(2, '0');
                    var mm = String(now.getMonth() + 1).padStart(2, '0');
                    var yyyy = now.getFullYear();

                    var from = yyyy + '-' + String(now.getMonth()).padStart(2, '0') + '-' + dd;
                    var to = yyyy + '-' + mm + '-' + dd;
                }
                flatPicker.each(function() {
                    $(this).flatpickr({
                        mode: 'range',
                        defaultDate: [from, to]
                    });
                });
            }

            $.ajax({
                url: `{{ url('admin/ordersChart') }}?from=${from}&to=${to}`,
                type: 'GET',
                success: function(response) {
                    if (barChartEx.length) {
                        new Chart(barChartEx, {
                            type: 'bar',
                            options: {
                                elements: {
                                    rectangle: {
                                        borderWidth: 2,
                                        borderSkipped: 'bottom'
                                    }
                                },
                                responsive: true,
                                maintainAspectRatio: false,
                                responsiveAnimationDuration: 500,
                                legend: {
                                    display: false
                                },
                                tooltips: {
                                    shadowOffsetX: 1,
                                    shadowOffsetY: 1,
                                    shadowBlur: 8,
                                    shadowColor: tooltipShadow,
                                    backgroundColor: window.colors?.solid?.white || '#fff',
                                    titleFontColor: window.colors?.solid?.black || '#000',
                                    bodyFontColor: window.colors?.solid?.black || '#000'
                                },
                                scales: {
                                    xAxes: [{
                                        display: true,
                                        gridLines: {
                                            display: true,
                                            color: grid_line_color,
                                            zeroLineColor: grid_line_color
                                        },
                                        scaleLabel: {
                                            display: false
                                        },
                                        ticks: {
                                            fontColor: labelColor
                                        }
                                    }],
                                    yAxes: [{
                                        display: true,
                                        gridLines: {
                                            color: grid_line_color,
                                            zeroLineColor: grid_line_color
                                        },
                                        ticks: {
                                            stepSize: 5,
                                            min: 0,
                                            fontColor: labelColor
                                        }
                                    }]
                                }
                            },
                            data: {
                                labels: response.dates,
                                datasets: [{
                                    data: response.counts,
                                    barThickness: 15,
                                    backgroundColor: successColorShade,
                                    borderColor: 'transparent'
                                }]
                            }
                        });
                    }
                }
            });
        }
    </script>
@endsection
