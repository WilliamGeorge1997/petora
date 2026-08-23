@extends('common::layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('') }}admin/css-rtl/plugins/extensions/ext-component-sweet-alerts.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin/vendors/css/forms/select/select2.min.css">
@endsection
@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">اضافه جديدة </h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal invoice-repeater" action="{{ url('admin/sides') }}" method="POST"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon"> الاسم باللغة العربية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" class="form-control" value="{{ old('title_ar') }}"
                                            name="title_ar" placeholder="الاسم" value="{{ old('title_ar') }}" />
                                    </div>
                                    @error('title_ar')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon"> الاسم باللغة الانجليزية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" id="fname-icon" value="{{ old('title_en') }}"
                                            class="form-control" name="title_en" placeholder="title"
                                            value="{{ old('title_en') }}" />
                                    </div>
                                    @error('title_en')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon"> الاسم في لوحه التحكم باللغه العربية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" id="fname-icon" value="{{ old('display_ar') }}"
                                            class="form-control" name="display_ar" placeholder="الاسم في لوحه التحكم باللغه العربية"
                                            value="{{ old('display_ar') }}" />
                                    </div>
                                    @error('display_ar')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon"> الاسم في لوحه التحكم باللغه الانجليزية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" id="fname-icon" value="{{ old('display_en') }}"
                                            class="form-control" name="display_en" placeholder="الاسم في لوحه التحكم باللغه الانجليزية"
                                            value="{{ old('display_en') }}" />
                                    </div>
                                    @error('display_en')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon">اقصي عدد للاختيارات</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="number" step="1" id="fname-icon"
                                            value="{{ old('max_selection') }}" class="form-control" name="max_selection"
                                            placeholder="اقصي عدد للاختيارات" value="{{ old('max_selection') }}" />
                                    </div>
                                    @error('max_selection')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @if (auth()->guard('admin')->user()->hasRole('Super Admin'))
                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="branch-select">الفرع</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group-merge">
                                            <select class="select2 form-select" id="branch-select" name="branch_id">
                                                <option value="{{ null }}" selected>اختر الفرع</option>
                                                @foreach (\Modules\Branch\Entities\Branch::active()->get() as $branch)
                                                    <option value="{{ $branch->id }}">
                                                        {{ $branch->title ?? $branch->getTranslations('title')['ar'] . ' - ' . $branch->getTranslations('title')['en'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('branch_id')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif


                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" value="1" name="is_active" class="form-check-input" />
                                    <label class="form-check-label">تفعيل</label>
                                </div>
                            </div>
                        </div>
                        <div class="divider divider-primary">
                            <div class="divider-text">قيم الطبق الجانبي</div>
                        </div>

                        <div data-repeater-list="side_values">
                            @php
                                $sideValues = old('side_values', [[]]);
                            @endphp

                            @foreach ($sideValues as $i => $row)
                                <div data-repeater-item>
                                    <div class="row d-flex align-items-center">
                                        <div class="col-md-3 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="itemname">اسم الطبق الجانبي باللغة
                                                    العربية</label>
                                                <input type="text" name="value_ar" class="form-control" id="itemname"
                                                    aria-describedby="itemname"
                                                    placeholder="اسم الطبق الجانبي باللغة العربية"
                                                    value="{{ $row['value_ar'] ?? '' }}" />
                                                @error("side_values.$i.value_ar")
                                                    <small class="text-danger d-block">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="itemname">اسم الطبق الجانبي باللغة الانجيليزية
                                                    الانجليزية</label>
                                                <input type="text" name="value_en" class="form-control"
                                                    id="itemname" aria-describedby="itemname"
                                                    placeholder="اسم الطبق الجانبي باللغة الانجيليزية"
                                                    value="{{ $row['value_en'] ?? '' }}" />
                                                @error("side_values.$i.value_en")
                                                    <small class="text-danger d-block">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="itemquantity">الصورة</label>
                                                <input type="file" class="form-control" name="image"
                                                    placeholder="image" />
                                                @error("side_values.$i.image")
                                                    <small class="text-danger d-block">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-12 mt-50">
                                            <div>
                                                <button class="btn btn-outline-danger text-nowrap px-1"
                                                    data-repeater-delete type="button">
                                                    <i data-feather="x" class="me-25"></i>
                                                    <span>حذف</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                </div>
                            @endforeach
                        </div>

                        @error('side_values')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-icon btn-primary" type="button" data-repeater-create>
                                    <i data-feather="plus" class="me-25"></i>
                                    <span>اضافة جديد</span>
                                </button>
                            </div>
                        </div>
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary me-1">انشاء</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('') }}admin/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
    <script src="{{ asset('') }}admin/js/scripts/forms/form-repeater.js"></script>
    <script src="{{ asset('') }}admin/vendors/js/extensions/sweetalert2.all.min.js"></script>

    <script src="{{ asset('') }}admin/vendors/js/forms/select/select2.full.min.js"></script>
    <script>
        var select = $('.select2');
        select.each(function() {
            var $this = $(this);
            $this.wrap('<div class="position-relative"></div>');
            $this.select2({
                dropdownAutoWidth: true,
                width: '100%',
                dropdownParent: $this.parent()
            });
        });
    </script>
@endsection
