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
                        <h4 class="card-title">{{ __('clinic::general.services') ?? 'الخدمات' }} - {{ $clinic->getTranslation('title', $locale) }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('clinic::general.home') ?? 'الرئيسية' }}</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.clinic.index') }}">{{ __('clinic::general.clinics') ?? 'العيادات' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ __('clinic::general.services') ?? 'الخدمات' }}</li>
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
                        <div class="head-label"><h6 class="mb-0">{{ __('clinic::general.services') ?? 'الخدمات' }}</h6></div>
                        <div class="dt-action-buttons text-end">
                            <div class="dt-buttons d-inline-flex">
                                <form method="POST" action="{{ route('admin.clinic.services.import-all', $clinic->id) }}" class="d-inline me-1">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-info">
                                        <i data-feather="download-cloud"></i> {{ __('clinic::general.import_all_services') ?? 'استيراد كافة الخدمات' }}
                                    </button>
                                </form>
                                <button class="dt-button btn btn-outline-primary me-1" type="button" data-bs-toggle="modal" data-bs-target="#importModal">
                                    <span><i data-feather="upload"></i> {{ __('clinic::general.import') ?? 'استيراد Excel' }}</span>
                                </button>
                                <a href="{{ route('admin.clinic.services.export', $clinic->id) }}" class="dt-button btn btn-outline-success">
                                    <span><i data-feather="download"></i> {{ __('clinic::general.export') ?? 'تصدير Excel' }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <table class="datatables-basic table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>{{ __('service::attribute.id') ?? 'المعرف' }}</th>
                                <th>{{ __('service::attribute.title') ?? 'الخدمة' }}</th>
                                <th>{{ __('service::attribute.price') ?? 'السعر' }}</th>
                                <th>{{ __('service::attribute.duration') ?? 'المدة (بالدقائق)' }}</th>
                                <th>{{ __('service::attribute.is_active') ?? 'الحالة' }}</th>
                                <th></th>
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
                        <h1 class="mb-1">{{ __('clinic::general.import') ?? 'استيراد الخدمات' }}</h1>
                    </div>
                    <form method="POST" action="{{ route('admin.clinic.services.import', $clinic->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-1">
                            <label class="form-label" for="file">{{ __('clinic::general.excel_file') ?? 'ملف Excel' }}</label>
                            <input type="file" id="file" name="file" class="form-control" accept=".xlsx, .xls, .csv" required />
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mt-2">{{ __('clinic::general.submit') ?? 'تأكيد' }}</button>
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
            var ajaxRequest = "{{ route('admin.clinic.services.index', $clinic->id) }}";
            var dt_basic_table = $('.datatables-basic');
            if (dt_basic_table.length) {
                var dt_basic = dt_basic_table.DataTable({
                    ajax: ajaxRequest,
                    searching: false,
                    columns: [
                        { data: 'id' },
                        { data: 'id' },
                        { data: 'service.title', defaultContent: '-' },
                        { data: 'price' },
                        { data: 'duration' },
                        { data: 'is_active' },
                        { data: 'id' }
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
                                    var title = (typeof data === 'object') ? (data[locale] ?? data['ar'] ?? data['en'] ?? '') : data;
                                    var $initials = (typeof title === 'string') ? title.match(/\b\w/g) || [] : [];
                                    $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
                                    return '<div class="d-flex justify-content-left align-items-center">' +
                                        '<div class="avatar bg-light-primary me-1"><span class="avatar-content">' + $initials + '</span></div>' +
                                        '<div class="d-flex flex-column"><span class="emp_name text-truncate fw-bold">' + title + '</span></div></div>';
                                }
                                return '-';
                            }
                        },
                        {
                            targets: 3,
                            render: function(data, type, full) {
                                return '<strong>' + parseFloat(data).toFixed(2) + '</strong>';
                            }
                        },
                        {
                            targets: 4,
                            render: function(data, type, full) {
                                return data + ' دقيقة';
                            }
                        },
                        {
                            targets: 5,
                            render: function(data, type, full, meta) {
                                var checked = full['is_active'] == 1 ? 'checked' : '';
                                return '<div class="form-check form-switch">' +
                                    '<input type="checkbox" class="form-check-input change-status" data-id="' + full['id'] + '" ' + checked + '>' +
                                    '</div>';
                            }
                        },
                        {
                            targets: -1,
                            title: '{{ __('common::general.actions') ?? 'الإجراءات' }}',
                            orderable: false,
                            render: function(data, type, full, meta) {
                                return (
                                    '<a href="javascript:;" class="text-danger delete-record" data-id="' + data + '">' +
                                    feather.icons['trash-2'].toSvg({ class: 'font-medium-2' }) +
                                    '</a>'
                                );
                            }
                        }
                    ],
                    order: [[1, 'desc']],
                    dom: 't<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    displayLength: 50,
                    lengthMenu: [7, 10, 25, 50, 75, 100],
                    bPaginate: false,
                });

                // Toggle Status
                $('.datatables-basic tbody').on('change', '.change-status', function() {
                    var id = $(this).data('id');
                    $.ajax({
                        url: '/admin/clinics/{{ $clinic->id }}/services/' + id + '/activate',
                        type: 'PATCH',
                        data: { _token: token }
                    }).done(function(response) {
                        successAlert(response.message);
                    }).fail(function() {
                        errorAlert();
                    });
                });

                // Delete Record
                $('.datatables-basic tbody').on('click', '.delete-record', function() {
                    var id = $(this).data('id');
                    var that = this;
                    Swal.fire({
                        title: '{{ __('common::general.sure_delete') ?? 'هل أنت متأكد من الحذف؟' }}',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: '{{ __('common::general.yes_delete') ?? 'نعم، احذفه!' }}',
                        cancelButtonText: '{{ __('common::general.cancel') ?? 'إلغاء' }}',
                        customClass: {
                            confirmButton: 'btn btn-primary',
                            cancelButton: 'btn btn-outline-danger ms-1'
                        },
                        buttonsStyling: false
                    }).then(function(result) {
                        if (result.value) {
                            $.ajax({
                                url: '/admin/clinics/{{ $clinic->id }}/services/' + id,
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
            }
        });
    </script>
@endsection
