@extends('common::layouts.master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/vendors/css/tables/datatable/responsive.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/vendors/css/tables/datatable/buttons.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/vendors/css/pickers/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/css-rtl/plugins/extensions/ext-component-sweet-alerts.css">
@endsection
@section('content')
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
                            <th>id</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!--/ Basic table -->

@endsection


@section('js')

    <script src="{{asset('')}}admin/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/tables/datatable/dataTables.bootstrap5.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/tables/datatable/responsive.bootstrap5.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/tables/datatable/jszip.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/tables/datatable/pdfmake.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/tables/datatable/vfs_fonts.js"></script>
    <script src="{{asset('')}}admin/vendors/js/tables/datatable/buttons.html5.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/tables/datatable/buttons.print.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/tables/datatable/dataTables.rowGroup.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/extensions/sweetalert2.all.min.js"></script>

    {{--    <script src="{{asset('')}}admin/js/scripts/tables/table-datatables-basic.js"></script>--}}

  <script src="{{asset('')}}js/branches/products.js"></script>

    @if(session('updated'))
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


@endsection
