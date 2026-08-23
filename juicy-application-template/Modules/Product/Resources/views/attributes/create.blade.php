@extends('common::layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('') }}admin/css-rtl/plugins/extensions/ext-component-sweet-alerts.css">
@endsection
@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">اضافه خاصيه جديدة الي منتج {{ $product['title'] }}</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal invoice-repeater"
                    action="{{ url('admin/product/' . $product['id'] . '/add_attribute') }}" method="POST"
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
                                        <input type="text" class="form-control" value="{{ old('attribute_ar') }}"
                                            name="attribute_ar" placeholder="الاسم باللغة العربية"
                                            value="{{ old('attribute_ar') }}" />
                                        @error('attribute_ar')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" value="{{ $product['id'] }}" name="product_id">

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon"> الاسم باللغة الانجليزية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" id="fname-icon" value="{{ old('attribute_en') }}"
                                            class="form-control" name="attribute_en" placeholder="الاسم باللغة الانجليزية"
                                            value="{{ old('attribute_en') }}" />
                                        @error('attribute_en')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" value="1" name="required" class="form-check-input" />
                                    <label class="form-check-label">الزامي</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" value="1" name="multi_select" class="form-check-input" />
                                    <label class="form-check-label">اختيار متعدد</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" value="1" name="override_price" class="form-check-input" />
                                    <label class="form-check-label">تعديل علي السعر الاساسي</label>
                                </div>
                            </div>
                        </div>
                        <div class="divider divider-primary">
                            <div class="divider-text">قيم الخاصية</div>
                        </div>

                        <div data-repeater-list="attribute_values">
                            <div data-repeater-item>
                                <div class="row d-flex align-items-end">
                                    <div class="col-md-3 col-12">
                                        <div class="mb-1">
                                            <label class="form-label" for="itemname">قيمة الخاصية باللغة العربية</label>
                                            <input type="text" required name="value_ar" class="form-control"
                                                id="itemname" aria-describedby="itemname"
                                                placeholder="قيمة الخاصية باللغة العربية" />
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-12">
                                        <div class="mb-1">
                                            <label class="form-label" for="itemname">قيمة الخاصية باللغة الانجليزية</label>
                                            <input type="text" required name="value_en" class="form-control"
                                                id="itemname" aria-describedby="itemname"
                                                placeholder="قيمة الخاصية باللغة الانجليزية" />
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-12">
                                        <div class="mb-1">
                                            <label class="form-label" for="itemquantity">السعر</label>
                                            <input type="number" step=".01" required name="price"
                                                class="form-control" id="itemquantity" aria-describedby="itemquantity"
                                                placeholder="السعر" />
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-12">
                                        <div class="mb-1">
                                            <label class="form-label" for="itemquantity">الصورة</label>
                                            <input type="file" required name="image" class="form-control"
                                                id="image" aria-describedby="image" placeholder="image" />
                                        </div>
                                        @error('attribute_values.*.image')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-md-2 col-12">
                                        <div class="mb-1">
                                            <button class="btn btn-outline-danger text-nowrap px-1" data-repeater-delete
                                                type="button">
                                                <i data-feather="x" class="me-25"></i>
                                                <span>حذف</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-icon btn-primary" type="button" data-repeater-create>
                                    <i data-feather="plus" class="me-25"></i>
                                    <span>اضافة جديد</span>
                                </button>
                            </div>
                        </div>
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary me-1">اضافة</button>
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

    @if (session('error'))
        <script>
            Swal.fire({
                title: 'خطأ!',
                text: 'لا يمكن ادخال اكثر من خاصية تقوم بالتعديل علي قيمة سعر المنتج',
                icon: 'error',
                customClass: {
                    confirmButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });
        </script>
    @endif
@endsection
