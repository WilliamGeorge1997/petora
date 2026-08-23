@extends('common::layouts.master')

@section('css')
    
@endsection
@section('content')


    <!-- Bordered table start -->
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">السجل الخاص ب لوحة التحكم</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Type</th>
                            <th>Description</th>
                            <th>User Type</th>
                            <th>User Name</th>
                            <th>Old Values</th>
                            <th>New Values</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($logs as $log)
                        <tr>
                            <td>
                                <span class="fw-bold">{{$log['log_name']}}</span>
                            </td>
                            <td>
                                @if($log['description'] == "created")
                                <span class="badge rounded-pill badge-light-primary me-1">انشاء جديد</span>
                                @elseif ($log['description'] == "updated")
                                <span class="badge rounded-pill badge-light-info me-1">تعديل</span>
                                @elseif ($log['description'] == "deleted")
                                <span class="badge rounded-pill badge-light-danger me-1">حذف</span>
                                @endif
                            </td>
                            <td>
                                
                                <span class="fw-bold">
                                    @if ($log['causer_type'])
                                    {{substr($log['causer_type'],strpos($log['causer_type'],'Entities')+9)}}</span>
                                    @else
                                    بيانات اساسية
                                    @endif
                            </td>

                            <td>
                                <span class="fw-bold">
                                    @if ($log['causer'])
                                    {{$log['causer']['name']}}
                                    @else
                                    بيانات اساسية
                                    @endif
                                    
                                </span>
                            </td>

                            <td>
                                <span class="fw-bold">
                                    @if (isset($log['properties']['old']))   
                                    @foreach ($log['properties']['old'] as $key => $attribute)
                                    {{__('attributes.'.$key)}} =>   {{Str::limit($attribute, 20)}}  <br>
                                    @endforeach
                                    @else
                                    لا يوجد بيانات قديمة
                                    @endif
                                </span>
                            </td>

                            <td>
                                <span class="fw-bold">
                                    @foreach ($log['properties']['attributes'] as $key => $attribute)
                                    {{__('attributes.'.$key)}} =>  {{Str::limit($attribute, 20)}} <br>
                                    @endforeach

                                </span>
                            </td>

                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
            {{ $logs->links() }}
        </div>
    </div>
    <!-- Bordered table end -->


@endsection


@section('js')

    {{--    <script src="{{asset('')}}admin/js/scripts/tables/table-datatables-basic.js"></script>--}}
@endsection
