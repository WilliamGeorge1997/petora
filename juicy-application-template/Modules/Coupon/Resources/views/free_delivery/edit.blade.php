@extends('common::layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/vendors/css/forms/select/select2.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/vendors/css/pickers/pickadate/pickadate.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/vendors/css/pickers/flatpickr/flatpickr.min.css">

@endsection

@section('content')

    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">تعديل البيانات  </h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="{{url('admin/freeDelivery/'.$freeDelivery->id)}}" method="POST">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon"> تاريخ البدايه</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="calendar"></i></span>
                                        <input type="text" value="{{$freeDelivery['date_from']}}" name="date_from" id="fp-default" class="form-control flatpickr-basic flatpickr-input active" placeholder="YYYY-MM-DD" readonly="readonly">
                                        @error('date_from')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon"> تاريخ النهاية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="calendar"></i></span>
                                        <input type="text" value="{{$freeDelivery['date_to']}}" name="date_to" id="fp-default" class="form-control flatpickr-basic flatpickr-input active" placeholder="YYYY-MM-DD" readonly="readonly">
                                        @error('date_to')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" value="1" @if($freeDelivery->is_active==1) checked @endif name="is_active" class="form-check-input" id="customCheck2" />
                                    <label class="form-check-label" for="customCheck2">تفعيل</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary me-1">تعديل</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')

    <script src="{{asset('')}}admin/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="{{asset('')}}admin/vendors/js/pickers/pickadate/picker.date.js"></script>
    <script src="{{asset('')}}admin/js/scripts/forms/pickers/form-pickers.js"></script>



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


        $(document).ready(function() {
            $("#checkbox").click(function(){
                if($("#checkbox").is(':checked') ){ //select all
                    $("#select2-multiple").find('option').prop("selected",true);
                    $("#select2-multiple").trigger('change');
                } else { //deselect all
                    $("#select2-multiple").find('option').prop("selected",false);
                    $("#select2-multiple").trigger('change');
                }
            });
        });
    </script>


@endsection
