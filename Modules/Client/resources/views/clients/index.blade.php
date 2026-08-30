@php
    $locale = app()->getLocale();
@endphp
@extends('common::layouts.master')

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
                        <h4 class="card-title">{{ __('client::general.clients') }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                        href="{{ route('admin.dashboard') }}">{{ __('client::general.home') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ __('client::general.clients') }}</li>
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
                        <label class="form-label">{{ __('client::attribute.name') }}</label>
                        <input type="text" name="name" class="form-control"
                            placeholder="{{ __('client::attribute.name') }}" value="{{ request('name') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('client::attribute.is_active') }}</label>
                        <select name="is_active" class="form-select">
                            <option value="">{{ __('client::general.select_status') ?? 'الكل' }}</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>مفعل</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>غير مفعل</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-1">{{ __('client::general.search') }}</button>
                        <a href="{{ route('admin.client.index') }}"
                            class="btn btn-outline-secondary">{{ __('client::general.reset') }}</a>
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
                                <th>{{ __('client::attribute.id') }}</th>
                                <th>{{ __('client::attribute.name') }}</th>
                                <th>{{ __('client::attribute.email') }}</th>
                                <th>{{ __('client::attribute.phone') }}</th>
                                <th>{{ __('client::attribute.created_at') }}</th>
                                <th>{{ __('client::attribute.is_active') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!--/ Basic table -->
    {{ $clients->withQueryString()->links() }}
@endsection


@section('js')
    @include('common::includes.datatable')
    <script>
        $(function() {
            'use strict';
            var dt_basic_table = $('.datatables-basic'),
                dt_basic;
            if (dt_basic_table.length) {
                dt_basic = dt_basic_table.DataTable({
                    ajax: '{{ route('admin.client.index') }}',
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
                            data: 'email'
                        },
                        {
                            data: 'phone'
                        },
                        {
                            data: 'created_at'
                        },
                        {
                            data: 'is_active'
                        },
                        {
                            data: ''
                        }
                    ],
                    columnDefs: [{
                            className: 'control',
                            orderable: false,
                            responsivePriority: 2,
                            targets: 0,
                            render: function(data, type, full, meta) {
                                return '';
                            }
                        },
                        {
                            targets: 1,
                            orderable: false,
                            responsivePriority: 3,
                            render: function(data, type, full, meta) {
                                return (
                                    '<div class="form-check"> <input class="form-check-input dt-checkboxes" type="checkbox" value="' +
                                    data +
                                    '" id="checkbox' +
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
                            targets: 3,
                            render: function(data, type, full, meta) {
                                return `
                                    <div class="d-flex justify-content-left align-items-center">
                                        <div class="avatar-wrapper">
                                            <div class="avatar me-1">
                                                <img src="${full.image}" alt="Avatar" height="32" width="32">
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="emp_name text-truncate fw-bold">${full.name}</span>
                                        </div>
                                    </div>
                                `;
                            }
                        },
                        {
                            targets: -2,
                            render: function(data, type, full, meta) {
                                var route = "{{ route('admin.client.activate', ':id') }}";
                                route = route.replace(':id', full.id);
                                var checked = data ? 'checked' : '';
                                return (
                                    `
                                @can('Edit-client')
                                <div class="form-check form-switch form-check-success">
                                    <input type="checkbox" class="form-check-input switch-active" id="customSwitch${full.id}" data-id="${full.id}" data-url="${route}" ${checked} />
                                    <label class="form-check-label" for="customSwitch${full.id}">
                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                    </label>
                                </div>
                                @endcan
                                `
                                );
                            }
                        },
                        {
                            targets: -1,
                            title: '{{ __('client::general.actions') }}',
                            orderable: false,
                            render: function(data, type, full, meta) {
                                var editUrl = "{{ route('admin.client.edit', ':id') }}".replace(':id', full.id);
                                var deleteUrl = "{{ route('admin.client.destroy', ':id') }}".replace(':id', full.id);
                                return (
                                    `<div class="d-inline-flex">
                                        <a class="pe-1 dropdown-toggle hide-arrow text-primary" data-bs-toggle="dropdown">
                                            <i data-feather="more-vertical" class="font-small-4"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            @can('Edit-client')
                                            <a href="${editUrl}" class="dropdown-item">
                                                <i data-feather="edit-2" class="font-small-4 me-50"></i>
                                                {{ __('client::general.edit') }}
                                            </a>
                                            @endcan
                                            @can('Delete-client')
                                            <a href="javascript:;" class="dropdown-item delete-record" data-id="${full.id}" data-url="${deleteUrl}">
                                                <i data-feather="trash-2" class="font-small-4 me-50"></i>
                                                {{ __('client::general.delete') }}
                                            </a>
                                            @endcan
                                        </div>
                                    </div>`
                                );
                            }
                        }
                    ],
                    order: [
                        [2, 'desc']
                    ],
                    dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    displayLength: 10,
                    lengthMenu: [10, 25, 50, 75, 100],
                    buttons: [
                        @can('Create-client')
                            {
                                text: '<i data-feather="plus"></i> {{ __('client::general.add_new') }}',
                                className: 'create-new btn btn-primary',
                                action: function(e, dt, node, config) {
                                    window.location.href = '{{ route('admin.client.create') }}';
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
                                    return 'Details of ' + data['name'];
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
                $('div.head-label').html('<h6 class="mb-0">{{ __('client::general.clients') }}</h6>');
            }
        });
    </script>
@endsection
