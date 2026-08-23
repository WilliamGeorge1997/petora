@extends('common::layouts.master')


@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/vendors/css/forms/select/select2.min.css">
@endsection

@section('content')

    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">تعديل البيانات الخاصه بالطلب رقم  {{$order->id}}</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="{{url('admin/orders/'.$order->id)}}" method="POST" enctype="multipart/form-data">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="pass-icon">حالات الطلب</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group-merge">
                                        <select name="order_status_id" id="status" class="select2 form-select select2-hidden-accessible"  tabindex="-1" aria-hidden="true">
                                            @foreach($viewModel->orderStatuses() as $status)
                                                <option @if ($order->order_status_id == $status->id)
                                                            selected
                                                        @endif
                                                        value="{{$status->id}}">{{$status->title}}</option>
                                            @endforeach
                                        </select>
                                        @error('order_status_id')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="pass-icon">الوقت المتوقع لاتمام الطلب</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group-merge">
                                        <select name="duration_time" class="select2 form-select select2-hidden-accessible"  tabindex="-1" aria-hidden="true">
                                                <option @if ($order->duration_time == 5) selected @endif value="5">5 دقائق</option>
                                                <option @if ($order->duration_time == 10) selected @endif value="10">10 دقائق</option>
                                                <option @if ($order->duration_time == 15) selected @endif value="15">15 دقيقة</option>
                                                <option @if ($order->duration_time == 20) selected @endif value="20">20 دقيقة</option>
                                                <option @if ($order->duration_time == 25) selected @endif value="25">25 دقيقة</option>
                                                <option @if ($order->duration_time == 30) selected @endif value="30">30 دقيقة</option>
                                                <option @if ($order->duration_time == 35) selected @endif value="35">35 دقيقة</option>
                                                <option @if ($order->duration_time == 40) selected @endif value="40">40 دقيقة</option>
                                                <option @if ($order->duration_time == 45) selected @endif value="45">45 دقيقة</option>
                                                <option @if ($order->duration_time == 50) selected @endif value="50">50 دقيقة</option>
                                                <option @if ($order->duration_time == 55) selected @endif value="55">55 دقيقة</option>
                                                <option @if ($order->duration_time == 60) selected @endif value="60">60 دقيقة</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12" id="driver">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="pass-icon">السائق</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group-merge">
                                        <select name="driver_id" class="select2 form-select select2-hidden-accessible" id="select2" data-select2-id="select2" tabindex="-1" aria-hidden="true">
                                            @foreach($viewModel->drivers() as $driver)
                                                <option
                                                @if ($order->driver_id == $driver->id)
                                                            selected
                                                        @endif
                                                         value="{{$driver->id}}">{{$driver->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('driver_id')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon"> الملاحظة</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" class="form-control" name="notes" placeholder="الملاحظة" />
                                        @error('notes')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                   
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary me-1">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection



@section('js')

    <script src="{{asset('')}}admin/vendors/js/forms/select/select2.full.min.js"></script>

    <script>

        var select = $('.select2');

        select.each(function () {
            var $this = $(this);
            $this.wrap('<div class="position-relative"></div>');
            $this.select2({
                // the following code is used to disable x-scrollbar when click in select input and
                // take 100% width in responsive also
                dropdownAutoWidth: true,
                width: '100%',
                dropdownParent: $this.parent()
            });
        });
        if(document.getElementById('status').value == 4 ){
                    document.getElementById('driver').style.display = 'block';
                }else{
                    document.getElementById('driver').style.display = 'none'; 
                }

            document.getElementById('status').onchange = function() {
                if(document.getElementById('status').value == 4 ){
                    document.getElementById('driver').style.display = 'block';
                }else{
                    document.getElementById('driver').style.display = 'none'; 
                }
};
    </script>


@endsection

