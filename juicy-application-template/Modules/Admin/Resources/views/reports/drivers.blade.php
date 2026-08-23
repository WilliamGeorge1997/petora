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
    <div class="row">
        <div class="col-lg-3 col-md-3 col-3">
            <div class="card earnings-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="card-title mb-2">اجمالي سائقين اليوم</h4>
                            <h5 class="font-small-2">{{\Carbon\Carbon::now()->toDateString()}}</h5>
                            <h5 class="mb-1">{{\Modules\Driver\Entities\Driver::whereDate('created_at',\Carbon\Carbon::now()->toDateString())->count()}}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-3 col-3">
            <div class="card earnings-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="card-title mb-2">اجمالي السائقين </h4>
                            <h5 class="font-small-2"></h5>
                            <h5 class="mb-1">{{\Modules\Driver\Entities\Driver::count()}}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-3 col-md-3 col-3">
            <div class="card earnings-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="card-title mb-2">اجمالي السائقين المفعلين </h4>
                            <h5 class="font-small-2"></h5>
                            <h5 class="mb-1">{{\Modules\Driver\Entities\Driver::active()->count()}}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-3 col-3">
            <div class="card earnings-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="card-title mb-2">اجمالي السائقين الغير مفعلين </h4>
                            <h5 class="font-small-2"></h5>
                            <h5 class="mb-1">{{\Modules\Driver\Entities\Driver::inactive()->count()}}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>




    </div>

    <div class="col-12">
        <div class="card">
            <div class="
            card-header
            d-flex
            flex-sm-row flex-column
            justify-content-md-between
            align-items-start
            justify-content-start
          ">
                <div>
                    <h4 class="card-title">السائقين</h4>
                    <span class="card-subtitle text-muted">رسم بياني يوضح عدد السائقين خلال مده زمنية محددة</span>
                </div>
                <div class="d-flex align-items-center">
                    <i class="font-medium-2" data-feather="calendar"></i>
                    <input type="text" onchange="osama()" id="date_from_to" class="form-control flat-picker bg-transparent border-0 shadow-none" placeholder="YYYY-MM-DD" />
                </div>
            </div>
            <div class="card-body" id="client_chart">
                <div id="line-area-chart"></div>
            </div>
        </div>
    </div>

    <!-- Bar Chart End -->
    <div class="row" id="basic-table">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">تقارير السائقين الاعلي طلباً</h4>
                </div>
                     <a  href="{{ url('export-driverreport/xlsx') }}"> <button type="button"  style="float:left" class="btn btn-outline-primary ">
                                    <i data-feather="download" class="mr-25"></i>
                                    <span> تصدير البيانات</span>
                                </button></a>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>اسم السائق</th>
                            <th>رقم هاتف السائق</th>
                            <th>اجمالي عدد الطلبات</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($popularDrivers as $driver)
                        <tr>
                            <td>
                                <span class="fw-bold">{{$driver['name']}}</span>
                            </td>
                            <td>{{(int)$driver['phone']}}</td>
                            <td>{{(int)$driver['orders_count']}}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Basic Tables end -->

@endsection


@section('js')

    <script src="{{asset('')}}admin/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/charts/chart.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/charts/apexcharts.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
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
    $.ajax({ url: window.location.origin+'/admin/driverChart?from='+from+'&to='+to,
        success: function(response) {
            // Area Chart
            // --------------------------------------------------------------------
            $('#line-area-chart').remove(); // this is my <div> element
            $('#client_chart').append('<div id="line-area-chart"></div>');
            var areaChartEl = document.querySelector('#line-area-chart'),
                areaChartConfig = {
                    chart: {
                        height: 400,
                        type: 'area',
                        parentHeightOffset: 0,
                        toolbar: {
                            show: false
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: false,
                        curve: 'straight'
                    },
                    legend: {
                        show: true,
                        position: 'top',
                        horizontalAlign: 'start'
                    },
                    grid: {
                        xaxis: {
                            lines: {
                                show: true
                            }
                        }
                    },
                    colors: [chartColors.area.series3, chartColors.area.series2, chartColors.area.series1],
                    series: [
                        {
                            name: 'كل السائقين',
                            data: response.total_all_drivers_count
                        },
                        {
                            name: 'السائقين المفعلين',
                            data: response.total_active_drivers_count
                        },
                        {
                            name: 'السائقين الغير مفعلين',
                            data: response.total_in_active_drivers_count
                        }
                    ],
                    xaxis: {
                        categories: response.dates
                    },
                    fill: {
                        opacity: 1,
                        type: 'solid'
                    },
                    tooltip: {
                        shared: false
                    },
                    yaxis: {
                        opposite: isRtl
                    }
                };
            if (typeof areaChartEl !== undefined && areaChartEl !== null) {
                var areaChart = new ApexCharts(areaChartEl, areaChartConfig);
                areaChart.render();
            }

        }
    });
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
