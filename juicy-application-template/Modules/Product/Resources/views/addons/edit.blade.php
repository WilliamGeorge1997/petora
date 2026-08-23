@extends('common::layouts.master')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin/vendors/css/forms/select/select2.min.css">
@endsection
@section('content')

    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">تعديل اضافة </h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal invoice-repeater" action="{{ url('admin/addons/' . $addon['id']) }}"
                    method="POST" enctype="multipart/form-data">
                    {{ method_field('PUT') }}
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
                                        <input type="text" value="{{ $addon->getTranslations('title')['ar'] }}"
                                            class="form-control" name="title_ar" placeholder="الاسم" />
                                        @error('title_ar')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
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
                                        <input type="text" value="{{ $addon->getTranslations('title')['en'] }}"
                                            id="fname-icon" class="form-control" name="title_en" placeholder="title" />
                                        @error('title_en')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon"> الاسم في لوحه التحكم باللغه
                                        العربية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" id="fname-icon"
                                            value="{{ $addon->getTranslations('display')['ar'] ?? '' }}" class="form-control"
                                            name="display_ar" placeholder="الاسم في لوحه التحكم باللغه العربية" />
                                        @error('display_ar')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon"> الاسم في لوحه التحكم باللغه
                                        الانجليزية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" id="fname-icon"
                                            value="{{ $addon->getTranslations('display')['en'] ?? '' }}" class="form-control"
                                            name="display_en" placeholder="الاسم في لوحه التحكم باللغه الانجليزية" />
                                        @error('display_en')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
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
                                                @foreach (\Modules\Branch\Entities\Branch::active()->get() as $branch)
                                                    <option value="{{ $branch->id }}"
                                                        @if ($addon['branch_id'] == $branch->id) selected @endif>
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
                                    <input type="checkbox" @if ($addon['multi_select'] == 1) checked @endif value="1"
                                        name="multi_select" class="form-check-input" />
                                    <label class="form-check-label">اختيار متعدد</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" @if ($addon['is_active'] == 1) checked @endif value="1"
                                        name="is_active" class="form-check-input" />
                                    <label class="form-check-label">تفعيل</label>
                                </div>
                            </div>
                        </div>


                        <div class="divider divider-primary">
                            <div class="divider-text">قيم الخاصية</div>
                        </div>

                        <div data-repeater-list="addon_values">
                            @foreach ($addon['values'] as $value)
                                <div data-repeater-item>
                                    <div class="row d-flex align-items-end">
                                        <div class="col-md-2 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="itemname">قيمة الخاصية باللغة
                                                    العربية</label>
                                                <input type="text" value="{{ $value['title'] }}" name="value_ar"
                                                    class="form-control" id="itemname" aria-describedby="itemname"
                                                    placeholder="قيمة الخاصية باللغة العربية" />
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="itemname">قيمة الخاصية باللغة
                                                    الانجليزية</label>
                                                <input type="text"
                                                    value="{{ $value->getTranslations('title')['en'] }}" name="value_en"
                                                    class="form-control" id="itemname" aria-describedby="itemname"
                                                    placeholder="قيمة الخاصية باللغة الانجليزية" />
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="itemquantity">السعر</label>
                                                <input type="number" step=".01" value="{{ $value['price'] }}"
                                                    name="price" class="form-control" id="itemquantity"
                                                    aria-describedby="itemquantity" placeholder="" />
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="itemquantity">الصورة</label>
                                                <input type="file" class="form-control" name="image"
                                                    placeholder="image" />
                                            </div>
                                        </div>

                                        @if ($value->image != null)
                                            <div class="col-md-2 col-12">
                                                <div class="mb-1">
                                                    <div class="image-container position-relative"
                                                        style="width: 100px; height: 100px;margin: 2px 7px;margin-top:10px">
                                                        <img style="width: 100%; height: 100%"
                                                            src="{{ asset($value->image) }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <input type="hidden" name="id" value="{{ $value['id'] }}">

                                        <div class="col-md-2 col-12 mb-50">
                                            <div class="mb-1">
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
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-icon btn-primary" type="button" data-repeater-create>
                                    <i data-feather="plus" class="me-25"></i>
                                    <span>اضافة الجديد</span>
                                </button>
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
    <script src="{{ asset('') }}admin/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
    <script src="{{ asset('') }}admin/js/scripts/forms/form-repeater.js"></script>

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
