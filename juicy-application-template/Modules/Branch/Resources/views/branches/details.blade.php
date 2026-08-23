@extends('common::layouts.master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/css-rtl/plugins/extensions/ext-component-sweet-alerts.css">
@endsection
@section('content')

<div class="col-lg-12 col-md-12 col-12" style="margin-top: 50px">
    <div class="card card-profile">
        <div class="card-body">
            <div class="profile-image-wrapper">
                <div class="profile-image">
                    <div class="avatar">
                        <img src="{{$branch->image}}" alt="Profile Picture">
                    </div>
                </div>
            </div>
            <h3>{{$branch->title}}</h3>
            <h6 class="text-muted">أنشأ {{Carbon\Carbon::parse($branch->created_at)->diffForHumans()}}</h6>
            @if ($branch->is_active ==1)
            <span class="badge badge-light-success profile-badge">مفعل</span>
            @else
            <span class="badge badge-light-danger profile-badge">غير مفعل</span>
            @endif
            {{-- <hr class="mb-2"> --}}
        </div>

        <a href="{{url('admin/branches/'.$branch->id.'/edit')}}">
            <button type="button" class="btn btn-primary waves-effect waves-float waves-light" style="float: left">تعديل بيانات الفرع</button>
            </a>
    </div>
</div>



<div class="row" id="basic-table">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">المديرين</h4>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>البريد الالكتروني</th>
                            <th>انشأ في</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($branch->admins as $admin )
                            
                        <tr>
                            <td>
                                <img src="{{$admin->image}}" class="me-75" height="20" width="20" alt="">
                                <a href="{{url('admin/admins/'.$admin->id.'/edit')}}">
                                <span class="fw-bold">{{$admin->name}}</span>
                                </a>
                            </td>
                            <td>{{$admin->email}}</td>
                            <td>{{$admin->created_at}}</td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



   


<div class="row" id="basic-table">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">الموظفين</h4>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>البريد الالكتروني</th>
                            <th>انشأ في</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($branch->employees as $employee )
                        <tr>
                            <td>
                                <a href="{{url('admin/employees/'.$employee->id.'/edit')}}">
                                    <span class="fw-bold">{{$employee->name}}</span>
                                </a>
                            </td>
                            <td>{{$employee->email}}</td>
                            <td>{{$employee->created_at}}</td>
                            
                        
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<div class="row" id="basic-table">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">الطابعات المرتبطة</h4>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>عنوان بروتوكول الإنترنت</th>
                            <th>النوع</th>
                            <th>انشأ في</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($branch->printers as $printer )
                            
                        <tr>
                            <td>
                                <a href="{{url('admin/printers/'.$printer->id.'/edit')}}">
                                <span class="fw-bold">{{$printer->ip}}</span>
                                </a>
                            </td>
                            <td>{{$printer->type_name}}</td>
                            <td>{{$printer->created_at}}</td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="row" id="basic-table">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">الخصومات</h4>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>الكود</th>
                            <th>النوع</th>
                            <th>القيمة</th>
                            <th>انشأ في</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($branch->coupon as $coupon )
                            
                        <tr>
                            <td>
                                <a href="{{url('admin/coupons/'.$coupon->id.'/edit')}}">
                                <span class="fw-bold">{{$coupon->code}}</span>
                                </a>
                            </td>
                            <td>
                                @if ($coupon->type ==1)
                                <span class="badge badge-light-success profile-badge">قيمة ثابتة</span>
                                @else
                                <span class="badge badge-light-success profile-badge">نسبة مئوية</span>
                                @endif
                            </td>
                            <td>{{$coupon->value}}</td>
                            <td>{{$coupon->created_at}}</td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="row" id="basic-table">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">اقسام الطاولات</h4>
                <a href="{{url('admin/branch/'.$branch->id.'/tables')}}">
                    <button type="button" class="btn btn-primary waves-effect waves-float waves-light">عرض الطاولات</button>
                    </a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>عدد الطاولات</th>
                            <th>انشأ في</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($table_sections as $section )
                            
                        <tr>
                            <td>
                                <span class="fw-bold">{{$section->title}}</span>
                            </td>
                            <td>{{$section->tables_count}}</td>
                            <td>{{$section->created_at}}</td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection


@section('js')

    <script src="{{asset('')}}admin/vendors/js/extensions/sweetalert2.all.min.js"></script>

    {{--    <script src="{{asset('')}}admin/js/scripts/tables/table-datatables-basic.js"></script>--}}
<script>
    $(document).ready(function(){
        $('.submit').click(function(){
            $(".delete_form").submit();
        });
    })
</script>
@endsection
