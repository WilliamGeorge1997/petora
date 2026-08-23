@extends('common::layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/css-rtl/plugins/extensions/ext-component-sweet-alerts.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/vendors/css/forms/select/select2.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/vendors/css/charts/apexcharts.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/vendors/css/pickers/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/css-rtl/plugins/charts/chart-apex.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/css-rtl/plugins/forms/pickers/form-flat-pickr.css">


@endsection

@section('content')

    <!--Bar Chart Start -->

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-sm-center align-items-start flex-sm-row flex-column">
                <div class="header-left">
                    <h4 class="card-title">اكثر المنتجات مبيعا علي مدار العام</h4>
                </div>
            </div>
            <div class="card-body">
                  <form class="dt_adv_search" >
                                    <div class="row g-1 mb-md-1">

                                         <div style="margin-top: 5%" class="col-md-3">
                                            <label class="form-label">من تاريخ</label>
                                            <input type="text" name="from_date" id="fp-default" value="{{@$_GET['from_date']}}" class="form-control flatpickr-basic flatpickr-input active" placeholder="YYYY-MM-DD" readonly="readonly">
                                        </div>
                                        <div style="margin-top: 5%"  class="col-md-3">
                                            <label class="form-label">الى تاريخ</label>
                                           <input type="text" name="to_date" id="fp-default" value="{{@$_GET['to_date']}}" class="form-control flatpickr-basic flatpickr-input active" placeholder="YYYY-MM-DD" readonly="readonly">
                                        </div>
                                            <div style="margin-top: 5%"  class="col-md-3">
                                                <label class="form-label">الفرع</label>

                                                <select  name="branch"  class="form-select" id="name5">
                                                   <option selected  value="{{ null }}">الكل</option>

                                                    @foreach ($Branches as $Branch)
                                                        <option @if(  $Branch['id']==@$_GET['branch'])  {{'selected'}}  @endif value="{{ $Branch['id'] }}">{{ $Branch['title'] }}</option>
                                                    @endforeach

                                                </select>

                                            </div>
                                      
                                        <input type="hidden" name="type" value="search">
                                        <div style=" margin-top: 7%" class="col-md-1">
                                            <button  type="submit"  class="btn btn-outline-primary">
                                                <i data-feather="search" ></i>
                                                <span>بحث</span>
                                            </button>
                                        </div>


                                </form>
                        </div>
                    </div>


           <div class="card">
                     
    

      

 

    <!-- Bar Chart End -->
    <div class="row" id="basic-table">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">تقارير المنتجات الاعلي مبيعاً</h4>
                </div>
                      <a  href="{{ url("export-productreport/xlsx?$Parameters") }}"> <button type="button"  style="float:left" class="btn btn-outline-primary ">
                                    <i data-feather="download" class="mr-25"></i>
                                    <span> تصدير البيانات</span>
                                </button></a>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>اسم المنتج</th>
                            <th>عدد مرات الطلب</th>
                            <th> 	اجمالي المبيعات</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($productsreport as $Report)
                        <tr>
                            <td>
                                <span class="fw-bold">{{$Report['title']}}</span>
                            </td>
                            <td>{{(int)$Report['order_details_count']}}</td>
                            <td>{{(int)$Report['order_details_sum_total']}}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
      {{ $productsreport->appends($_GET)->links()}}
    <!-- Basic Tables end -->

@endsection


@section('js')

   <script src="{{asset('')}}admin/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="{{asset('')}}admin/vendors/js/pickers/pickadate/picker.date.js"></script>
    <script src="{{asset('')}}admin/js/scripts/forms/pickers/form-pickers.js"></script>
    <script>
        /*=========================================================================================
    File Name: chart-apex.js
    Description: Apexchart Examples
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

$(window).on('load', function () {
    'use strict';
    initializeOrdersChart()

});

function osama() {
   const date = document.getElementById('date_from_to').value;
   const from = date.slice(0, 10);
   const to = date.slice(14, 24);
   if (to !== null && to !== ''){
       initializeOrdersChart(from,to);
   }
}

function initializeOrdersChart( from = null , to = null ) {
    var flatPicker = $('.flat-picker'),
        isRtl = $('html').attr('data-textdirection') === 'rtl',
        chartColors = {
            area: {
                series3: '#a4f8cd',
                series2: '#60f2ca',
                series1: '#2bdac7'
            }
        };
    // Init flatpicker
    if (flatPicker.length) {
        if (from == null && to == null){
            var now = new Date();
            var dd = now.getDate();

            var mm = now.getMonth()+1;
            var yyyy = now.getFullYear();

            var from = yyyy+'-'+(mm-1)+'-'+dd;
            var to = yyyy+'-'+mm+'-'+dd;

        }else{
            var from = from;
            var to = to;
        }
        flatPicker.each(function () {
            $(this).flatpickr({
                mode: 'range',
                defaultDate: [from,to]
            });
        });
    }
   
}

    </script>

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

    </script>


@endsection
