@extends('common::layouts.master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('') }}admin/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('') }}admin/vendors/css/tables/datatable/responsive.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('') }}admin/vendors/css/tables/datatable/buttons.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('') }}admin/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin/vendors/css/pickers/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('') }}admin/css-rtl/plugins/extensions/ext-component-sweet-alerts.css">
    <style>
        .review-stars svg {
            width: 14px;
            height: 14px;
        }
    </style>
@endsection
@section('content')
    @if ($averageRatings)
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">تقييمات العملاء</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/admin/dashboard') }}">الرئيسية</a></li>
                                <li class="breadcrumb-item active">تقيممات العملاء</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="mb-2">
            <div class="row match-height">
                @php
                    $ratingCards = [
                        [
                            'key' => 'food_quality',
                            'label' => 'جودة الطعام',
                            'icon' => 'coffee',
                            'color' => 'primary',
                        ],
                        [
                            'key' => 'service_speed',
                            'label' => 'سرعة الخدمة',
                            'icon' => 'clock',
                            'color' => 'danger',
                        ],
                        ['key' => 'staff', 'label' => 'طاقم العمل', 'icon' => 'users', 'color' => 'success'],
                        ['key' => 'cleanliness', 'label' => 'نظافة المكان', 'icon' => 'sun', 'color' => 'info'],
                        [
                            'key' => 'will_revisit',
                            'label' => 'الزيارة مرة أخرى',
                            'icon' => 'repeat',
                            'color' => 'warning',
                        ],
                    ];
                @endphp
                @foreach ($ratingCards as $card)
                    <div class="col-xl col-md-4 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h6 class="mb-0">{{ $card['label'] }}</h6>
                                    <div class="avatar bg-light-{{ $card['color'] }}">
                                        <div class="avatar-content">
                                            <i data-feather="{{ $card['icon'] }}" class="avatar-icon"></i>
                                        </div>
                                    </div>
                                </div>
                                <h2 class="fw-bolder mb-25 mt-1">
                                    {{ number_format($averageRatings[$card['key']], 1) }}
                                </h2>
                                <div class="review-card-stars" data-rating="{{ $averageRatings[$card['key']] }}"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <table class="datatables-basic table">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>id</th>
                                <th>العميل</th>
                                <th>الفرع</th>
                                <th>جودة الطعام</th>
                                <th>سرعة الخدمة</th>
                                <th>طاقم العمل</th>
                                <th>نظافة المكان</th>
                                <th>الزيارة مرة أخرى</th>
                                <th>التعليق</th>
                                <th>التاريخ</th>
                                <th>الادوات</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade text-start" id="commentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">التعليق</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>

    {{ $reviews->withQueryString()->links() }}
@endsection

@section('js')
    @include('common::includes.datatable')

    <script>
        function renderStars(value) {
            var rating = Math.round(Number(value)) || 0;
            var html = '<span class="review-stars d-inline-flex align-items-center gap-25">';
            for (var i = 1; i <= 5; i++) {
                html += feather.icons['star'].toSvg({
                    class: 'font-small-3 ' + (i <= rating ? 'text-warning' : 'text-muted opacity-50')
                });
            }
            html += '</span>';
            return html;
        }

        $(function() {
            'use strict';

            $('.review-card-stars').each(function() {
                $(this).html(renderStars($(this).data('rating')));
            });

            var url = new URL(window.location.href);
            var page = url.searchParams.get("page")
            var ajaxRequest = "reviews?";
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
                            data: 'branch.title'
                        },
                        {
                            data: 'food_quality'
                        },
                        {
                            data: 'service_speed'
                        },
                        {
                            data: 'staff'
                        },
                        {
                            data: 'cleanliness'
                        },
                        {
                            data: 'will_revisit'
                        },
                        {
                            data: 'comment'
                        },
                        {
                            data: 'created_at'
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
                            render: function(data) {
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
                            render: function(data, type, full) {
                                var name = $('<div>').text(full.name || '—').html();
                                var phone = full.phone ? $('<div>').text(full.phone).html() : '';
                                return (
                                    '<div class="d-flex flex-column">' +
                                    '<span class="fw-bold text-truncate">' + name + '</span>' +
                                    (phone ? '<small class="text-muted">' + phone + '</small>' :
                                        '') +
                                    '</div>'
                                );
                            }
                        },
                        {
                            targets: 4,
                            render: function(data) {
                                return data.ar;
                            }
                        },
                        {
                            targets: [5, 6, 7, 8, 9],
                            render: function(data) {
                                return renderStars(data);
                            }
                        },
                        {
                            targets: 10,
                            render: function(data) {
                                if (!data) return '—';
                                var text = $('<div>').text(data).html();
                                return '<a href="#" class="review-comment" data-bs-content="' +
                                    text + '">عرض التعليق</a>';
                            }
                        },
                        {
                            targets: -1,
                            title: 'الادوات',
                            orderable: false,
                            render: function(data) {
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
                                    'حذف</a>' +
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
                    displayLength: 100,
                    lengthMenu: [7, 10, 25, 50, 75, 100],
                    bPaginate: false,
                    buttons: [],
                    responsive: {
                        details: {
                            display: $.fn.dataTable.Responsive.display.modal({
                                header: function(row) {
                                    var data = row.data();
                                    return 'تفاصيل تقييم ' + data['name'];
                                }
                            }),
                            type: 'column',
                            renderer: function(api, rowIdx, columns) {
                                var data = $.map(columns, function(col) {
                                    return col.title !== '' ?
                                        '<tr data-dt-row="' + col.rowIdx +
                                        '" data-dt-column="' +
                                        col.columnIndex + '">' +
                                        '<td>' + col.title + ':</td> ' +
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
                    },
                });
                $('div.head-label').html('<h6 class="mb-0">تقييمات العملاء</h6>');
            }

            $('.datatables-basic tbody').on('click', '.review-comment', function(e) {
                e.preventDefault();
                $('#commentModal .modal-body').html($(this).attr('data-bs-content'));
                $('#commentModal').modal('show');
            });

            $('.datatables-basic tbody').on('click', '.delete-record', function() {
                let that = this;
                var id = dt_basic.row($(this).parents('tr')).data().id
                var token = $("meta[name='csrf-token']").attr("content");
                Swal.fire({
                    title: 'هل انت متأكد من الحذف ؟ ',
                    text: "لن تتمكن من التراجع عن هذا!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'نعم ، احذفها!',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ms-1'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.value) {
                        dt_basic.row($(that).parents('tr')).remove().draw();
                        $.ajax({
                            url: window.location.origin + "/admin/reviews/" + id,
                            type: 'POST',
                            data: {
                                "id": id,
                                "_method": "DELETE",
                                "_token": token,
                            },
                            success: function() {}
                        });
                        Swal.fire({
                            icon: 'success',
                            title: 'تم الحذف!',
                            text: 'تم حذف التقييم.',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
