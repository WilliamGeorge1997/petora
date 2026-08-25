@extends('common::layouts.master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin/vendors/css/tables/datatable/buttons.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('admin/vendors/css/pickers/flatpickr/flatpickr.min.css') }}"> --}}
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin/css-rtl/plugins/extensions/ext-component-sweet-alerts.css') }}">
@endsection
@section('content')
    {{-- Basic table --}}
    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <table class="datatables-basic table">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>{{ __('company::general.id') }}</th>
                                <th>{{ __('company::attribute.title_ar') }}</th>
                                <th>{{ __('company::attribute.title_en') }}</th>
                                <th>{{ __('company::attribute.phone') }}</th>
                                <th>{{ __('company::attribute.address_ar') }}</th>
                                <th>{{ __('company::general.date') }}</th>
                                <th>{{ __('company::general.status') }}</th>
                                <th>{{ __('company::general.actions') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
    {{-- / Basic table --}}
@endsection

@section('js')
    @include('common::includes.datatable')

    {{-- <script src="{{ asset('admin/js/scripts/tables/table-datatables-basic.js') }}"></script> --}}
    {{-- <script src="{{ asset('js/packages/script.js') }}"></script> --}}

    @if (session('updated'))
        <script>
            Swal.fire({
                title: 'احسنت!',
                text: '{{ session('updated') }}',
                icon: 'success',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            });
        </script>
    @endif

    @if (session('created'))
        <script>
            Swal.fire({
                title: 'احسنت!',
                text: '{{ session('created') }}',
                icon: 'success',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            });
        </script>
    @endif

    @if (session('success'))
        <script>
            Swal.fire({
                title: 'احسنت!',
                text: '{{ session('success') }}',
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
            var page = url.searchParams.get("page")
            var ajaxRequest = "company?";
            if (page != null) {
                ajaxRequest += "page=" + page + '&';
            }
            var dt_basic_table = $('.datatables-basic');

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
                            data: 'title', // Arabic Title and Image
                            render: function(data, type, full, meta) {
                                var $user_img = full['image'],
                                    $name = data ? (data['ar'] || data) : '';
                                if ($user_img) {
                                    if (!$user_img.startsWith('http')) {
                                        $user_img = window.location.origin + '/' + $user_img;
                                    }
                                    var $output = '<img src="' + $user_img +
                                        '" alt="Avatar" width="32" height="32">';
                                } else {
                                    var stateNum = full['is_active'];
                                    var states = ['info', 'primary'];
                                    var $state = states[stateNum] || 'primary',
                                        $initials = $name.match(/\b\w/g) || [];
                                    $initials = (($initials.shift() || '') + ($initials.pop() ||
                                        '')).toUpperCase();
                                    $output = '<span class="avatar-content">' + $initials +
                                        '</span>';
                                }

                                var colorClass = $user_img === null ? ' bg-light-' + $state + ' ' :
                                    '';
                                return '<div class="d-flex justify-content-left align-items-center">' +
                                    '<div class="avatar ' + colorClass + ' me-1">' + $output +
                                    '</div>' +
                                    '<div class="d-flex flex-column"><span class="emp_name text-truncate fw-bold">' +
                                    $name + '</span></div></div>';
                            }
                        },
                        {
                            data: 'title',
                            render: function(data, type, full, meta) {
                                return data ? (data['en'] || data) : '';
                            }
                        },
                        {
                            data: 'phone'
                        },
                        {
                            data: 'address',
                            render: function(data, type, full, meta) {
                                return data ? (data['ar'] || data) : '';
                            }
                        },
                        {
                            data: 'created_at',
                        },
                        {
                            data: 'is_active',
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
                                return '<span class="badge rounded-pill ' + $status[$status_number]
                                    .class + '">' + $status[$status_number].title + '</span>';
                            }
                        },
                        {
                            data: 'id', // Actions
                            orderable: false,
                            render: function(data, type, full, meta) {
                                var activation = '';
                                if (full['is_active'] == 0) activation =
                                    '{{ __('company::general.activate') }}';
                                else activation = '{{ __('company::general.deactivate') }}';

                                var editButton = '';
                                @can('Edit-company')
                                    editButton = '<a href="company/' + data +
                                        '/edit" class="item-edit">' +
                                        feather.icons['edit'].toSvg({
                                            class: 'font-small-4 me-50'
                                        }) +
                                        '</a>';
                                @endcan

                                var activateMenu = '';
                                @can('Edit-company')
                                    activateMenu =
                                        '<a href="javascript:;" class="dropdown-item activate-record" data-id="' +
                                        data + '">' +
                                        feather.icons['file-text'].toSvg({
                                            class: 'font-small-4 me-50'
                                        }) +
                                        activation + '</a>';
                                @endcan

                                var deleteMenu = '';
                                @can('Delete-company')
                                    deleteMenu =
                                        '<a href="javascript:;" class="dropdown-item delete-record" data-id="' +
                                        data + '">' +
                                        feather.icons['trash-2'].toSvg({
                                            class: 'font-small-4 me-50'
                                        }) +
                                        '{{ __('company::general.delete') }}</a>';
                                @endcan

                                return (
                                    '<div class="d-inline-flex">' +
                                    '<a class="pe-1 dropdown-toggle hide-arrow text-primary" data-bs-toggle="dropdown">' +
                                    feather.icons['more-vertical'].toSvg({
                                        class: 'font-small-4'
                                    }) +
                                    '</a>' +
                                    '<div class="dropdown-menu dropdown-menu-end">' +
                                    activateMenu + deleteMenu +
                                    '</div>' +
                                    '</div>' +
                                    editButton
                                );
                            }
                        }
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
                        @can('Create-company')
                            {
                                text: feather.icons['plus'].toSvg({
                                    class: 'me-50 font-small-4'
                                }) + '{{ __('company::general.create_company') }}',
                                className: 'create-new btn btn-primary',
                                action: function(e, dt, node, config) {
                                    window.location.href = './company/create';
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
                                    return 'تفاصيل ' + (data['title'] ? (data['title']['ar'] ||
                                        data['title']) : '');
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
                    }
                });
                $('div.head-label').html('<h6 class="mb-0">{{ __('company::general.main_data') }}</h6>');
            }

            // Activate Record
            $('.datatables-basic tbody').on('click', '.activate-record', function() {
                var id = dt_basic.row($(this).parents('tr')).data().id;
                var token = $("meta[name='csrf-token']").attr("content");
                $.ajax({
                    url: window.location.origin + "/admin/company/activate/" + id,
                    type: 'GET',
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: '{{ __('company::general.success') }}',
                            text: response.message,
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                        dt_basic.ajax.reload();
                    }
                });
            });

            // Delete Record
            $('.datatables-basic tbody').on('click', '.delete-record', function() {
                let that = this;
                var id = dt_basic.row($(this).parents('tr')).data().id;
                var token = $("meta[name='csrf-token']").attr("content");
                Swal.fire({
                    title: '{{ __('company::general.sure_delete') }}',
                    text: '{{ __('company::general.cant_revert') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{ __('company::general.yes_delete') }}',
                    cancelButtonText: '{{ __('company::general.cancel') }}',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ms-1'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            url: window.location.origin + "/admin/company/" + id,
                            type: 'POST',
                            data: {
                                "id": id,
                                "_method": "DELETE",
                                "_token": token,
                            },
                            success: function(response) {
                                dt_basic.row($(that).parents('tr')).remove().draw();
                                Swal.fire({
                                    icon: 'success',
                                    title: '{{ __('company::general.success') }}',
                                    text: response.message,
                                    customClass: {
                                        confirmButton: 'btn btn-success'
                                    }
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
