@extends('common::layouts.master')

@section('content')

    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">تعديل البيانات الخاصه بالوظيفة {{$role['name']}}</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="{{url('admin/roles/'.$role->id)}}" method="POST">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon">الاسم</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" id="fname-icon" class="form-control" value="{{$role['name']}}" name="name" placeholder="Name" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                @foreach($cat_permissions as $key => $permissions)
                                <div class="col-3 text-center">
                                    <label class="col-form-label @if($key !='Admin') hidden @endif" for="fname-icon">الصلاحيات</label>
                                </div>
                                    <div class="col-9">
                                    <tr>
                                        <td class="text-nowrap fw-bolder">{{$key}}</td>

                                        <td>
                                            <div class="d-flex">
                                                @foreach($permissions as $permission)
                                                    <div class="form-check me-3 me-lg-5">
                                                        <input class="form-check-input" name="permission[]" type="checkbox" @if(in_array($permission->id, $rolePermissions)) checked  @endif value="{{$permission->name}}" />
                                                        <label class="form-check-label" for="userManagementRead"> {{$permission['display']}} </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <hr>
                                        </td>
                                    </tr>
                                    </div>
                                @endforeach
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
