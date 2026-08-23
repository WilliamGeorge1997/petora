@extends('common::layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/css-rtl/plugins/extensions/ext-component-sweet-alerts.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/vendors/css/forms/select/select2.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/vendors/css/charts/apexcharts.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/vendors/css/pickers/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/css-rtl/plugins/charts/chart-apex.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/css-rtl/plugins/forms/pickers/form-flat-pickr.css">
      <script src="{{asset('admin/vendors/js/charts/apexcharts.min.js')}}"></script>

    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/vendors/css/forms/select/select2.min.css">

@endsection

@section('content')

    <!--Bar Chart Start -->
         <div class="col-lg-12 col-12">
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
                                 <h4 class="card-title mb-25"> تقارير المبيعات   للفروع هذا العام     </h4>

                                    </div>

                                </div>
                                <div class="card-body">

                                          <div class="card-body">
                                        <form class="dt_adv_search" >
                                    <div class="row g-1 mb-md-1">

                                        <div  class="col-md-6">
                                            <label class="form-label">من تاريخ</label>
                                            <input type="text" name="from_date" id="fp-default" value="{{@$_GET['from_date']}}" class="form-control flatpickr-basic flatpickr-input active" placeholder="YYYY-MM-DD" readonly="readonly">
                                        </div>
                                        <div   class="col-md-6">
                                            <label class="form-label">الى تاريخ</label>
                                           <input type="text" name="to_date"  id="fp-default" value="{{@$_GET['to_date']}}" class="form-control flatpickr-basic flatpickr-input active" placeholder="YYYY-MM-DD" readonly="readonly">
                                        </div>
                                       
                                        <div  class="col-md-8 col-12">
                                        <label class="form-label" for="default-select-multi">الفروع</label>
                                        <div >
                                            
                                        <select class="select2 form-select" name="branch[]" multiple="multiple" id="default-select-multi">
                                        @foreach ($Branches as $Branch)
                                            <option  @if(in_array($Branch['id'],$branchArray)) selected @endif  value="{{ $Branch['id'] }}">{{ $Branch['title'] }}</option>
                                        @endforeach
                                        </select>
                                        </div>
                      
                                            
                                        </div>
                                      
                                        <input type="hidden" name="type" value="search">
                                        <div style=" margin-top: 3%" class="col-md-1">
                                            <button  type="submit"  class="btn btn-outline-primary">
                                                <i data-feather="search" ></i>
                                                <span>بحث</span>
                                            </button>
                                        </div>


                                </form>
                        </div>
                                    <div id="line-chart"></div>
                                </div>
                            </div>
                        </div>
 


           <div class="card">
    <!-- Bar Chart End -->
    <div class="row" id="basic-table">
        <div class="col-12">
            <div class="card">
                 <a  href="{{ url("export-branchreport/xlsx?$Parameters") }}"> <button type="button"  style="float:left" class="btn btn-outline-primary ">
                                    <i data-feather="download" class="mr-25"></i>
                                    <span> تصدير البيانات</span>
                                </button></a>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>اسم الفرع</th>
                            <th>عدد مرات الطلب</th>
                            <th> 	اجمالي المبيعات</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($branches as $branch)
                        <tr>
                            <td>
                                <span class="fw-bold">{{$branch['title']}}</span>
                            </td>
                            <td>{{(int)$branch['orders_count']}}</td>
                            <td>{{(int)$branch['orders_sum_total']}}</td>
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
    <script src="{{asset('')}}admin/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="{{asset('')}}admin/vendors/js/pickers/pickadate/picker.date.js"></script>
    <script src="{{asset('')}}admin/js/scripts/forms/pickers/form-pickers.js"></script>
     <script src="{{asset('')}}admin/vendors/js/forms/select/select2.full.min.js"></script>
   <script src="{{asset('')}}admin/js/scripts/forms/form-select2.js"></script>

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






          $(function () {
            'use strict';

            var flatPicker = $('.flat-picker'),
                isRtl = $('html').attr('data-textdirection') === 'rtl',
                chartColors = {
                    column: {
                        series1: '#826af9',
                        series2: '#d2b0ff',
                        bg: '#f8d3ff'
                    },
                    success: {
                        shade_100: '#7eefc7',
                        shade_200: '#06774f'
                    },
                    donut: {
                        series1: '#ffe700',
                        series2: '#00d4bd',
                        series3: '#826bf8',
                        series4: '#2b9bf4',
                        series5: '#FFA1A1'
                    },
                    area: {
                        series3: '#a4f8cd',
                        series2: '#60f2ca',
                        series1: '#2bdac7'
                    }
                };

            // heat chart data generator
            function generateDataHeat(count, yrange) {
                var i = 0;
                var series = [];
                while (i < count) {
                    var x = 'w' + (i + 1).toString();
                    var y = Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;

                    series.push({
                        x: x,
                        y: y
                    });
                    i++;
                }
                return series;
            }

            // Init flatpicker
            if (flatPicker.length) {
                var date = new Date();
                flatPicker.each(function () {
                    $(this).flatpickr({
                        mode: 'range',
                        defaultDate: ['2022-05-01', '2022-05-10']
                    });
                });
            }

            // Line Chart
            // --------------------------------------------------------------------
            var lineChartEl = document.querySelector('#line-chart'),
                lineChartConfig = {
                    chart: {
                        height: 400,
                        type: 'line',
                        zoom: {
                            enabled: false
                        },
                        parentHeightOffset: 0,
                        toolbar: {
                            show: false
                        }
                    },
                    series: [
                        {
                            data:

                            [
                             @foreach($BranchesSales as $value)
{{$value}},
                              @endforeach
                            ]

                        }
                    ],
                    markers: {
                        strokeWidth: 7,
                        strokeOpacity: 1,
                        strokeColors: [window.colors.solid.white],
                        colors: [window.colors.solid.warning]
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'straight'
                    },
                    colors: [window.colors.solid.warning],
                    grid: {
                        xaxis: {
                            lines: {
                                show: true
                            }
                        },
                        padding: {
                            top: -20
                        }
                    },
                    tooltip: {
                        custom: function (data) {
                            return (
                                '<div class="px-1 py-50">' +
                                '<span>' +
                                data.series[data.seriesIndex][data.dataPointIndex] +
                                '%</span>' +
                                '</div>'
                            );
                        }
                    },
                    xaxis: {


                        categories: [

                                     @foreach($BranchesNames as $value)
 "{{$value}}",
                          
                              @endforeach

                        ],
                    },
                    yaxis: {
                        opposite: isRtl
                    }
                };
            if (typeof lineChartEl !== undefined && lineChartEl !== null) {
                var lineChart = new ApexCharts(lineChartEl, lineChartConfig);
                lineChart.render();
            }


        });


    </script>


@endsection
