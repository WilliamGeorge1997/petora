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
@endsection
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">الخصائص</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                            <li class="breadcrumb-item">اداره المنتجات</li>
                            <li class="breadcrumb-item"><a href="{{ url('admin/products') }}">المنتجات</a></li>
                            <li class="breadcrumb-item">ادارة خصائص {{ $product['title'] }}</li>
                            <li class="breadcrumb-item active">الخصائص</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bordered table start -->
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">خصائص منتج {{ $product['title'] }}</h4>
                    <div>
                        <a href="{{ url('admin/products/' . request()->route('id') . '/edit') }}">
                            <button type="button" class="btn btn-danger waves-effect waves-float waves-light">
                                <i data-feather="arrow-right"></i>
                                الرجوع لتعديل المنتج
                            </button>
                        </a>


                        <a href="{{ url('admin/product/' . request()->route('id') . '/add_attribute') }}">
                            <button type="button" class="btn btn-primary waves-effect waves-float waves-light">اضافة
                                جديد</button>
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>الزامي</th>
                                <th>اختيار متعدد</th>
                                <th>تعديل علي السعر الرئيسي</th>
                                <th>الاجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($product_attrributes as $product_attribute)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ $product_attribute['title'] }}</span>
                                    </td>
                                    <td>
                                        @if ($product_attribute['required'] == 1)
                                            <span class="badge rounded-pill badge-light-primary me-1">الزامي</span>
                                        @else
                                            <span class="badge rounded-pill badge-light-danger me-1">غير الزامي</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($product_attribute['multi_select'] == 1)
                                            <span class="badge rounded-pill badge-light-warning me-1">اختيار متعدد</span>
                                        @else
                                            <span class="badge rounded-pill badge-light-info me-1">اختيار واحد</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($product_attribute['override_price'] == 1)
                                            <span class="badge rounded-pill badge-light-primary me-1">تعديل علي السعر
                                                الرئيسي </span>
                                        @else
                                            <span class="badge rounded-pill badge-light-warning me-1">اضافة الي السعر
                                                الرئيسي</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ url('admin/attribute/' . $product_attribute['id'] . '/edit') }}"
                                                class="btn btn-sm btn-outline-primary me-1">
                                                <i data-feather="edit-2" class="me-50"></i>
                                                <span>تعديل</span>
                                            </a>
                                            <form class="delete_form"
                                                action="{{ url('admin/attribute/' . $product_attribute['id'] . '/delete') }}"
                                                method="POST">
                                                {{ method_field('DELETE') }} {!! csrf_field() !!}
                                                <button class="btn btn-sm btn-outline-danger" type="submit"
                                                    onclick="return confirm('هل أنت متأكد من حذف هذه الخاصية؟');">
                                                    <i data-feather="trash" class="me-50"></i>
                                                    <span>حذف</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Bordered table end -->
@endsection


@section('js')
    @include('common::includes.datatable')


    {{--    <script src="{{asset('')}}admin/js/scripts/tables/table-datatables-basic.js"></script> --}}
    {{-- <script>
    $(document).ready(function(){
        $('.submit').click(function(){
            $(".delete_form").submit();
        });
    })
</script> --}}
    @if (session('updated'))
        <script>
            Swal.fire({
                title: 'أحسنت!',
                text: 'لقد تم تعديل المنتج بنجاح',
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
                title: 'أحسنت!',
                text: 'لقد تم انشاء المنتج بنجاح',
                icon: 'success',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                title: 'خطأ!',
                text: '{{ session('error') }}',
                icon: 'error',
                customClass: {
                    confirmButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });
        </script>
    @endif
@endsection
