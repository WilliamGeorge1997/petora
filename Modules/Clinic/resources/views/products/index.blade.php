@php
    $locale = app()->getLocale();
@endphp
@extends('common::layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/vendors/css/tables/datatable/buttons.bootstrap5.min.css') }}">
@endsection
@section('content')
    {{-- Breadcrumb --}}
    <section id="default-breadcrumb">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('clinic::general.products') ?? 'Products' }} - {{ $clinic->title }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('clinic::general.home') ?? 'Home' }}</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.clinic.index') }}">{{ __('clinic::general.clinics') ?? 'Clinics' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ __('clinic::general.products') ?? 'Products' }}</li>
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
                    <div class="card-header border-bottom p-1">
                        <div class="head-label"><h6 class="mb-0">{{ __('clinic::general.products') ?? 'Products' }}</h6></div>
                        <div class="dt-action-buttons text-end">
                            <div class="dt-buttons d-inline-flex">
                                <button class="dt-button btn btn-outline-primary me-2" type="button" data-bs-toggle="modal" data-bs-target="#importModal">
                                    <span><i data-feather="upload"></i> {{ __('clinic::general.import') ?? 'Import Excel' }}</span>
                                </button>
                                <a href="{{ route('admin.clinic.products.export', $clinic->id) }}" class="dt-button btn btn-outline-success">
                                    <span><i data-feather="download"></i> {{ __('clinic::general.export') ?? 'Export Excel' }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <table class="datatables-basic table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>{{ __('product::attribute.id') ?? 'ID' }}</th>
                                <th>{{ __('product::attribute.title') ?? 'Title' }}</th>
                                <th>{{ __('product::attribute.category_id') ?? 'Category' }}</th>
                                <th>{{ __('product::attribute.price') ?? 'Price (Pivot)' }}</th>
                                <th>{{ __('product::attribute.is_active') ?? 'Is Active (Pivot)' }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>

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
            var ajaxRequest = "{{ route('admin.clinic.products.index', $clinic->id) }}";
            var dt_basic_table = $('.datatables-basic');
            if (dt_basic_table.length) {
                var dt_basic = dt_basic_table.DataTable({
                    ajax: ajaxRequest,
                    searching: false,
                    columns: [
                        { data: 'id' }, // 0: responsive show
                        { data: 'id' },
                        { data: 'title' },
                        { data: 'category_id' }, // not loaded usually but we will just output empty or category if available
                        { data: 'pivot.price', render: function(data, type, full) { return full.pivot ? full.pivot.price : full.price; } },
                        { data: 'pivot.is_active', render: function(data, type, full) { return full.pivot ? (full.pivot.is_active ? 'Yes' : 'No') : ''; } }
                    ],
                    columnDefs: [
                        { className: 'control', orderable: false, responsivePriority: 2, targets: 0 },
                        { visible: false, targets: 1 },
                        {
                            targets: 2,
                            responsivePriority: 4,
                            render: function(data, type, full, meta) {
                                if (data) {
                                    var locale = '{{ $locale }}';
                                    var title = data[locale] ?? data['ar'] ?? data;
                                    var $initials = (typeof title === 'string') ? title.match(/\b\w/g) || [] : [];
                                    $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
                                    return '<div class="d-flex justify-content-left align-items-center">' +
                                        '<div class="avatar bg-light-primary me-1"><span class="avatar-content">' + $initials + '</span></div>' +
                                        '<div class="d-flex flex-column"><span class="emp_name text-truncate fw-bold">' + title + '</span></div></div>';
                                }
                                return '';
                            }
                        }
                    ],
                    order: [[1, 'desc']],
                    dom: 't<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    displayLength: 50,
                    lengthMenu: [7, 10, 25, 50, 75, 100],
                    bPaginate: false,
                    responsive: {
                        details: {
                            display: $.fn.dataTable.Responsive.display.modal({
                                header: function(row) {
                                    var data = row.data();
                                    var locale = '{{ $locale }}';
                                    return 'Details of ' + (data['title'][locale] ?? data['title']['ar'] ?? data['title']);
                                }
                            }),
                            type: 'column',
                            renderer: function(api, rowIdx, columns) {
                                var data = $.map(columns, function(col, i) {
                                    return col.title !== ''
                                        ? '<tr data-dt-row="' + col.rowIdx + '" data-dt-column="' + col.columnIndex + '"><td>' + col.title + ':</td> <td>' + col.data + '</td></tr>'
                                        : '';
                                }).join('');

                                return data ? $('<table class="table"/>').append('<tbody>' + data + '</tbody>') : false;
                            }
                        }
                    }
                });
            }
        });
    </script>
@endsection
