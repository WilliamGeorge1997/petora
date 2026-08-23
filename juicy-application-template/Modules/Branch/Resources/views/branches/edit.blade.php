@extends('common::layouts.master')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin/vendors/css/forms/select/select2.min.css">
    {{-- <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script> --}}
    <link rel="stylesheet" type="text/css"
        href="{{ asset('') }}admin/css-rtl/plugins/extensions/ext-component-sweet-alerts.css">
    <style>
        #map {
            height: 400px;
            width: 100%;
        }

        .btn-delete.btn-close {
            right: -8%;
            top: -10%;
            color: red !important;
            filter: none !important;
        }

        .btn-delete.btn-close {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='red'%3e%3cpath d='M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .293 1.707a1 1 0 010-1.414z'/%3e%3c/svg%3e") !important;
        }
    </style>
@endsection

@section('content')

    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">تعديل البيانات الخاصه بالفرع {{ $branch['title'] }}</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="{{ url('admin/branches/' . $branch->id) }}" method="POST"
                    enctype="multipart/form-data">
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
                                        <input type="text" id="fname-icon" value="{{ $branch->title }}"
                                            class="form-control" name="title_ar" placeholder="العنوان" />
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
                                        <input type="text" id="fname-icon"
                                            value="{{ $branch->getTranslations('title')['en'] }}" class="form-control"
                                            name="title_en" placeholder="title" />
                                        @error('title_en')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="pass-icon">طرق الطلب</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group-merge">
                                        <select name="order_methods[]" class="select2 form-select select2-hidden-accessible" multiple="" data-select2-id="select2-multiple" tabindex="-1" aria-hidden="true">
                                            @foreach ($order_methods as $method)
                                                <option @if (in_array($method->id, $branch_methods->toArray())) selected @endif value="{{$method->id}}">{{$method->title}}</option>
                                            @endforeach
                                        </select>
                                        @error('order_methods')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">رقم الهاتف الاول</label>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="smartphone"></i></span>
                                        <input type="number" id="contact-icon" required class="form-control" name="phone"
                                            value="{{ $branch->phone }}" placeholder="رقم الهاتف الاول" />
                                    </div>
                                </div>
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">رقم الهاتف الثاني (اختياري)</label>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="smartphone"></i></span>
                                        <input type="number" id="contact-icon" class="form-control" name="secondary_phone"
                                            value="{{ $branch->secondary_phone }}"
                                            placeholder="رقم الهاتف الثاني (اختياري)" />
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon">العنوان باللغة العربية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" id="fname-icon" class="form-control" name="address_ar"
                                            placeholder="address"
                                            value="{{ $branch->getTranslations('address')['ar'] ?? '' }}" />
                                        @error('address_ar')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon">العنوان باللغة الانجليزية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" id="fname-icon" class="form-control" name="address_en"
                                            placeholder="address"
                                            value="{{ $branch->getTranslations('address')['en'] ?? '' }}" />
                                        @error('address_en')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon">المدينة باللغة العربية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="map-pin"></i></span>
                                        <input type="text" class="form-control" name="city_ar"
                                            placeholder="المدينة باللغة العربية"
                                            value="{{ $branch->getTranslations('city')['ar'] ?? '' }}" />
                                        @error('city_ar')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon">المدينة باللغة الانجليزية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="map-pin"></i></span>
                                        <input type="text" id="fname-icon" class="form-control" name="city_en"
                                            placeholder="المدينة باللغة الانجليزية"
                                            value="{{ $branch->getTranslations('city')['en'] ?? '' }}" />
                                        @error('city_en')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon">الخريطة</label>
                                </div>
                                <div class="col-sm-9">
                                    <div id="map"></div>
                                    <input type="text" id="coords-input" class="form-control form-control mt-1"
                                        value="{{ $branch->lat && $branch->long ? $branch->lat . ', ' . $branch->long : '' }}"
                                        placeholder="31.267460208980072, 29.99968838882789" dir="ltr" lang="en"
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <input type="hidden" value="{{ $branch->lat }}" id="lat" name="lat">
                        <input type="hidden" value="{{ $branch->long }}" id="long" name="long">


                        <div class="col-12">
                            <div class="mb-1 row align-items-center">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="pass-icon">الصوره</label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="image"></i></span>
                                        <input type="file" id="pass-icon" class="form-control" name="image"
                                            placeholder="image" />
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    @if ($branch->image)
                                        <a href="{{ $branch->image }}" data-bs-toggle="modal"
                                            data-bs-target="#imageModal">
                                            <img src="{{ $branch->image }}" alt="image" class="img-fluid border p-1"
                                                width="150" height="150">
                                        </a>
                                        <!-- Modal -->
                                        <div class="modal fade" id="imageModal" tabindex="-1"
                                            aria-labelledby="imageModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="imageModalLabel">صورة الفرع</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <img src="{{ $branch->image }}" alt="image"
                                                            class="img-fluid">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>


                        {{-- Payment Methods --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="active_ingredient[]">طرق الدفع</label>
                                </div>
                                <div class="col-sm-9">
                                    <select required class="select2 form-select " name="payment_methods[]"
                                        id="payment_methods[]" multiple="multiple">
                                        @foreach ($viewModel->paymentMethods() as $payment_method)
                                            <option @if (in_array($payment_method['id'], $branch_payment_methods)) selected @endif
                                                value="{{ $payment_method['id'] }}">
                                                {{ $payment_method->getTranslations('title')['ar'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>


                        {{-- Order Methods --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="active_ingredient[]">طرق الطلب</label>
                                </div>
                                <div class="col-sm-9">
                                    <select required class="select2 form-select " name="order_methods[]"
                                        id="order_methods[]" multiple="multiple">
                                        @foreach ($viewModel->orderMethods() as $order_method)
                                            <option @if (in_array($order_method['id'], $branch_order_methods)) selected @endif
                                                value="{{ $order_method['id'] }}">
                                                {{ $order_method->getTranslations('title')['ar'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" value="1" @if ($branch->is_active == 1) checked @endif
                                        name="is_active" class="form-check-input" id="customCheck2" />
                                    <label class="form-check-label" for="customCheck2">تفعيل الفرع</label>
                                </div>
                            </div>
                        </div>

                        <div class="divider divider-primary">
                            <div class="divider-text">تكلفة التوصيل</div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon">طريقة حساب تكلفة التوصيل</label>
                                </div>
                                <div class="col-sm-9">
                                    <select required onchange="showDeliveryFeeMethod()" class="select2 form-select "
                                        name="delivery_fee_method" id="delivery_fee_method">
                                        <option value="{{ null }}">اختر طريقة حساب تكلفة التوصيل</option>
                                        <option @if ($branch->delivery_fee_method == 'fixed') selected @endif value="fixed">قيمة ثابتة
                                        </option>
                                        <option @if ($branch->delivery_fee_method == 'per_km') selected @endif value="per_km">قيمة لكل
                                            كيلومتر
                                        </option>
                                        <option @if ($branch->delivery_fee_method == 'per_charge') selected @endif value="per_charge">قيمة
                                            لكل
                                            مسافة
                                        </option>
                                        <option @if ($branch->delivery_fee_method == 'per_area') selected @endif value="per_area">قيمة
                                            لكل
                                            منطقة
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div style="display: none;" id="delivery_fee_fixed" class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon">قيمة ثابتة</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="dollar-sign"></i></span>
                                        <input type="number" id="fname-icon" class="form-control"
                                            name="delivery_fee_fixed" placeholder="قيمة ثابتة"
                                            value="{{ $branch->delivery_fee_fixed }}" />
                                        @error('delivery_fee_fixed')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="display: none;" id="delivery_fee_per_km" class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon">قيمة لكل كيلومتر</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="dollar-sign"></i></span>
                                        <input type="number" id="fname-icon" class="form-control"
                                            name="delivery_fee_per_km" placeholder="قيمة لكل كيلومتر"
                                            value="{{ $branch->delivery_fee_per_km }}" />
                                        @error('delivery_fee_per_km')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div style="display: none;" id="delivery_fee_per_charge" class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon">تكلفة التوصيل بالمسافات</label>
                                </div>
                                <div class="col-sm-9 repeater-default">
                                    <div>
                                        @if ($branch_delivery_charges->count() > 0)
                                            @foreach ($branch_delivery_charges as $delivery_charge)
                                                <div data-repeater-item>
                                                    <div class="row d-flex align-items-end">
                                                        <div class="col-md-4 col-12">
                                                            <div class="mb-1">
                                                                <label class="form-label" for="itemname"> المسافة
                                                                    بالكيلو</label>
                                                                <input type="text" name="distance"
                                                                    value="{{ $delivery_charge['distance'] }}"
                                                                    class="form-control" id="itemname"
                                                                    aria-describedby="itemname"
                                                                    placeholder="المسافة بالكيلو" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 col-12">
                                                            <div class="mb-1">
                                                                <label class="form-label" for="itemquantity">السعر</label>
                                                                <input type="text" name="price"
                                                                    value="{{ $delivery_charge['price'] }}"
                                                                    class="form-control" id="itemquantity"
                                                                    aria-describedby="itemquantity" placeholder="" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 col-12">
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
                                        @else
                                            <div data-repeater-list="delivery_charges">
                                                <div data-repeater-item>
                                                    <div class="row d-flex align-items-end">
                                                        <div class="col-md-4 col-12">
                                                            <div class="mb-1">
                                                                <label class="form-label" for="itemname"> المسافة
                                                                    بالكيلو</label>
                                                                <input type="text" name="distance"
                                                                    class="form-control" id="itemname"
                                                                    aria-describedby="itemname"
                                                                    placeholder="المسافة بالكيلو" />
                                                            </div>
                                                        </div>


                                                        <div class="col-md-2 col-12">
                                                            <div class="mb-1">
                                                                <label class="form-label" for="itemquantity">السعر</label>
                                                                <input type="text" name="price" class="form-control"
                                                                    id="itemquantity" aria-describedby="itemquantity"
                                                                    placeholder="السعر" />
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2 col-12">
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
                                            </div>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-icon btn-primary" type="button" data-repeater-create>
                                                <i data-feather="plus" class="me-25"></i>
                                                <span>اضافة جديد</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="display: none;" id="delivery_fee_per_area" class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="area_title">تكلفة التوصيل بالمناطق</label>
                                </div>
                                <div class="col-sm-9 repeater-default">
                                    <div data-repeater-list="delivery_areas">
                                        @if ($branch->deliveryAreas->count() > 0)
                                            @foreach ($branch->deliveryAreas as $delivery_area)
                                                <div data-repeater-item>
                                                    <div class="row d-flex align-items-end">
                                                        <div class="col-md-4 col-12">
                                                            <div class="mb-1">
                                                                <label class="form-label" for="area_title">اسم
                                                                    المنطقة</label>
                                                                <input type="text" name="title"
                                                                    value="{{ $delivery_area['title'] }}"
                                                                    class="form-control" id="area_title"
                                                                    aria-describedby="area_title"
                                                                    placeholder="اسم المنطقة" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 col-12">
                                                            <div class="mb-1">
                                                                <label class="form-label" for="area_price">السعر</label>
                                                                <input type="text" name="price"
                                                                    value="{{ $delivery_area['price'] }}"
                                                                    class="form-control" id="area_price"
                                                                    aria-describedby="area_price" placeholder="السعر" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 col-12">
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
                                        @else
                                            <div data-repeater-item>
                                                <div class="row d-flex align-items-end">
                                                    <div class="col-md-4 col-12">
                                                        <div class="mb-1">
                                                            <label class="form-label" for="area_title">اسم
                                                                المنطقة</label>
                                                            <input type="text" name="title" class="form-control"
                                                                id="area_title" aria-describedby="area_title"
                                                                placeholder="اسم المنطقة" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-12">
                                                        <div class="mb-1">
                                                            <label class="form-label" for="area_price">السعر</label>
                                                            <input type="text" name="price" class="form-control"
                                                                id="area_price" aria-describedby="area_price"
                                                                placeholder="السعر" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-12">
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
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-icon btn-primary" type="button" data-repeater-create>
                                                <i data-feather="plus" class="me-25"></i>
                                                <span>اضافة جديد</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Working Hours --}}
                        <div class="divider divider-primary">
                            <div class="divider-text">اوقات العمل</div>
                        </div>

                        <div id="working_hours" class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="pass-icon">اوقات عمل الفرع</label>
                                </div>
                                <div class="col-sm-9 repeater-default">
                                    <div data-repeater-list="working_hours">
                                        @if ($branch_working_hours->count() > 0)
                                            @foreach ($branch_working_hours as $working_hour)
                                                <div data-repeater-item="day_select_option">
                                                    <div class="row d-flex align-items-end">
                                                        <div class="col-md-3 col-12">
                                                            <div class="mb-1">
                                                                <label class="form-label"
                                                                    for="day_select_option">اليوم</label>
                                                                <select name="day" class="form-control" required
                                                                    id="day_select_option">
                                                                    <option
                                                                        @if ($working_hour->day == 'saturday') selected @endif
                                                                        value="saturday">السبت
                                                                    </option>
                                                                    <option
                                                                        @if ($working_hour->day == 'sunday') selected @endif
                                                                        value="sunday">الاحد
                                                                    </option>
                                                                    <option
                                                                        @if ($working_hour->day == 'monday') selected @endif
                                                                        value="monday">الاثنين
                                                                    </option>
                                                                    <option
                                                                        @if ($working_hour->day == 'tuesday') selected @endif
                                                                        value="tuesday">الثلاثاء
                                                                    </option>
                                                                    <option
                                                                        @if ($working_hour->day == 'wednesday') selected @endif
                                                                        value="wednesday">الاربعاء
                                                                    </option>
                                                                    <option
                                                                        @if ($working_hour->day == 'thursday') selected @endif
                                                                        value="thursday">الخميس
                                                                    </option>
                                                                    <option
                                                                        @if ($working_hour->day == 'friday') selected @endif
                                                                        value="friday">الجمعة
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2 col-12">
                                                            <div class="mb-1">
                                                                <label class="form-label d-block">24 ساعة</label>
                                                                <div class="form-check mt-50">
                                                                    <input type="checkbox" name="is_open_24_hours"
                                                                        value="1"
                                                                        class="form-check-input working-hours-24h"
                                                                        @if ($working_hour->is_open_24_hours == 1) checked @endif />
                                                                    <label class="form-check-label">مفتوح</label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2 col-12">
                                                            <div class="mb-1">
                                                                <label class="form-label" for="from">من</label>
                                                                <input type="time" name="from"
                                                                    class="form-control working-hours-from" required
                                                                    id="from" aria-describedby="from"
                                                                    placeholder="من" value="{{ $working_hour->from }}" />
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2 col-12">
                                                            <div class="mb-1">
                                                                <label class="form-label" for="to">الي</label>
                                                                <input type="time" name="to"
                                                                    class="form-control working-hours-to" required
                                                                    id="to" aria-describedby="to"
                                                                    placeholder="الي" value="{{ $working_hour->to }}" />
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2 col-12">
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
                                        @else
                                            <div data-repeater-item="day_select_option">
                                                <div class="row d-flex align-items-end">
                                                    <div class="col-md-3 col-12">
                                                        <div class="mb-1">
                                                            <label class="form-label"
                                                                for="day_select_option">اليوم</label>
                                                            <select name="day" class="form-control" required
                                                                id="day_select_option">
                                                                <option value="saturday">السبت</option>
                                                                <option value="sunday">الاحد</option>
                                                                <option value="monday">الاثنين</option>
                                                                <option value="tuesday">الثلاثاء</option>
                                                                <option value="wednesday">الاربعاء</option>
                                                                <option value="thursday">الخميس</option>
                                                                <option value="friday">الجمعة</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2 col-12">
                                                        <div class="mb-1">
                                                            <label class="form-label d-block">24 ساعة</label>
                                                            <div class="form-check mt-50">
                                                                <input type="checkbox" name="is_open_24_hours"
                                                                    value="1"
                                                                    class="form-check-input working-hours-24h" />
                                                                <label class="form-check-label">مفتوح</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2 col-12">
                                                        <div class="mb-1">
                                                            <label class="form-label" for="from">من</label>
                                                            <input type="time" name="from"
                                                                class="form-control working-hours-from" required
                                                                id="from" aria-describedby="from"
                                                                placeholder="من" />
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2 col-12">
                                                        <div class="mb-1">
                                                            <label class="form-label" for="to">الي</label>
                                                            <input type="time" name="to"
                                                                class="form-control working-hours-to" required
                                                                id="to" aria-describedby="to" placeholder="الي" />
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2 col-12">
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
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-icon btn-primary" type="button"
                                                data-repeater-create="day_select_option">
                                                <i data-feather="plus" class="me-25"></i>
                                                <span>اضافة جديد</span>
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>






                        {{-- <div class="divider divider-primary">
                            <div class="divider-text">اعدادت الفرع</div>
                        </div> --}}

                        {{-- Currency --}}
                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">العملة باللغه العربية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="dollar-sign"></i></span>
                                        <input type="text" id="contact-icon" required class="form-control"
                                            name="currency_ar" value="{{ $branch->settings?->currency_ar ?? '' }}"
                                            placeholder="العمله باللغه العربية" />
                                    </div>
                                    @error('currency_ar')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">العملة باللغه الانجيليزية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="dollar-sign"></i></span>
                                        <input type="text" id="contact-icon" required class="form-control"
                                            name="currency_en" value="{{ $branch->settings?->currency_en ?? '' }}"
                                            placeholder="العمله باللغه العربية" />
                                    </div>
                                    @error('currency_en')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}
                        {{-- About --}}
                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">عن الفرع باللغه العربية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <textarea placeholder="عن الفرع باللغه العربية" class="form-control" name="about_ar">{{ $branch->settings?->about_ar ?? '' }}</textarea>
                                    </div>
                                    @error('about_ar')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">عن الفرع باللغه الانجيليزية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <textarea placeholder="عن الفرع باللغه الانجيليزية" class="form-control" name="about_en">{{ $branch->settings?->about_en ?? '' }}</textarea>
                                    </div>
                                    @error('about_en')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}

                        {{-- Terms --}}
                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">الشروط و الاحكام باللغه
                                        العربية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <textarea placeholder="الشروط و الاحكام باللغه" class="form-control" name="terms_ar">{{ $branch->settings?->terms_ar ?? '' }}</textarea>
                                    </div>
                                    @error('terms_ar')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}
                        {{-- 
                        <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">الشروط و الاحكام باللغه
                                        الانجيليزية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <textarea placeholder="الشروط و الاحكام باللغه" class="form-control" name="terms_en">{{ $branch->settings?->terms_en ?? '' }}</textarea>
                                    </div>
                                    @error('terms_en')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}

                        {{-- @if ($branch->settings?->theme == 1 || $branch->settings?->theme == 4)
                            <div class="col-12 col-xl-6">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="contact-icon">الوصف في البداية باللغه
                                            العربية</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group input-group-merge">
                                            <textarea placeholder="الوصف في البداية باللغه العربية" class="form-control" name="intro_message_ar">{{ $branch->settings?->intro_message_ar ?? '' }}</textarea>
                                        </div>
                                        @error('intro_message_ar')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>





                            <div class="col-12 col-xl-6">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="contact-icon">الوصف في البداية باللغه
                                            الانجيليزية</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group input-group-merge">
                                            <textarea placeholder="الوصف في البداية باللغه الانجيليزية" class="form-control" name="intro_message_en">{{ $branch->settings?->intro_message_en ?? '' }}</textarea>
                                        </div>
                                        @error('intro_message_en')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif --}}


                        {{-- Tax Message --}}
                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">رسالة الضريبة باللغه العربية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <textarea placeholder="رسالة الضريبة باللغه العربية" class="form-control" name="tax_message_ar">{{ $branch->settings?->tax_message_ar ?? '' }}</textarea>
                                    </div>
                                    @error('tax_message_ar')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}





                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">رسالة الضريبة باللغه
                                        الانجيليزية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <textarea placeholder="رسالة الضريبة باللغه الانجيليزية" class="form-control" name="tax_message_en">{{ $branch->settings?->tax_message_en ?? '' }}</textarea>
                                    </div>
                                    @error('tax_message_en')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}


                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">رابط تقييم جوجل
                                    </label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <textarea placeholder="رابط تقييم جوجل" class="form-control" name="google_rate_url">{{ $branch->settings?->google_rate_url ?? '' }}</textarea>
                                    </div>
                                    @error('google_rate_url')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
 --}}




                        {{-- Tax --}}
                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">الضريبة</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="percent"></i></span>
                                        <input type="number" id="contact-icon" class="form-control" name="tax"
                                            value="{{ $branch->settings?->tax }}" placeholder="الضريبة" />
                                    </div>
                                    @error('tax')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">الرقم الضريبي</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="hash"></i></span>
                                        <input type="text" id="contact-icon" class="form-control" name="tax_number"
                                            value="{{ $branch->settings?->tax_number }}" placeholder="الرقم الضريبي" />
                                    </div>
                                    @error('tax_number')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}

                        {{-- Images --}}
                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row align-items-center">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="pass-icon">صورة الخلفية</label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="image"></i></span>
                                        <input type="file" id="pass-icon" class="form-control"
                                            name="app_background_image" placeholder="app_background_image" />
                                        @error('app_background_image')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                @if ($branch->settings?->app_background_image)
                                    <div class="col-sm-3 delete-image-container">
                                        <div class="border p-1 position-relative">

                                            <button type="button" class="btn-close btn-delete position-absolute"
                                                aria-label="Close"
                                                onclick="deleteImage('app_background_image', this)"></button>

                                            <a href="{{ $branch->image }}" data-bs-toggle="modal"
                                                data-bs-target="#appBackgroundImageModal">
                                                <img src="{{ $branch->settings?->app_background_image }}" alt="image"
                                                    class="img-fluid " width="150" height="150">
                                            </a>
                                        </div>
                                        <!-- Modal -->
                                        <div class="modal fade" id="appBackgroundImageModal" tabindex="-1"
                                            aria-labelledby="appBackgroundImageModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="appBackgroundImageModalLabel">صورة
                                                            الخلفية</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <img src="{{ $branch->settings?->app_background_image }}"
                                                            alt="image" class="img-fluid">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div> --}}

                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row align-items-center">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="pass-icon">صورة العرض</label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="image"></i></span>
                                        <input type="file" id="pass-icon" class="form-control" name="app_offer_image"
                                            placeholder="app_offer_image" />
                                    </div>
                                </div>
                                @if ($branch->settings?->app_offer_image)
                                    <div class="col-sm-3 delete-image-container">
                                        <div class="border p-1 position-relative">
                                            <button type="button" class="btn-close btn-delete position-absolute"
                                                aria-label="Close"
                                                onclick="deleteImage('app_offer_image', this)"></button>

                                            <a href="{{ $branch->settings?->app_offer_image }}" data-bs-toggle="modal"
                                                data-bs-target="#appOfferImageModal">
                                                <img src="{{ $branch->settings?->app_offer_image }}" alt="image"
                                                    class="img-fluid " width="150" height="150">
                                            </a>
                                        </div>

                                        <!-- Modal -->
                                        <div class="modal fade" id="appOfferImageModal" tabindex="-1"
                                            aria-labelledby="appOfferImageModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="appOfferImageModalLabel">صورة الخلفية
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <img src="{{ $branch->settings?->app_offer_image }}"
                                                            alt="image" class="img-fluid">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div> --}}

                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row align-items-center">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="pass-icon">صورة الشعار</label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="image"></i></span>
                                        <input type="file" id="pass-icon" class="form-control" name="logo"
                                            placeholder="logo" />
                                        @error('logo')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                @if ($branch->settings?->logo)
                                    <div class="col-sm-3 delete-image-container">
                                        <div class="border p-1 position-relative">

                                            <button type="button" class="btn-close btn-delete position-absolute"
                                                aria-label="Close" onclick="deleteImage('logo', this)"></button>

                                            <a href="{{ $branch->settings?->logo }}" data-bs-toggle="modal"
                                                data-bs-target="#logoModal">
                                                <img src="{{ $branch->settings?->logo }}" alt="image"
                                                    class="img-fluid " width="150" height="150">
                                            </a>

                                        </div>
                                        <!-- Modal -->
                                        <div class="modal fade" id="logoModal" tabindex="-1"
                                            aria-labelledby="logoModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="logoModalLabel">صورة الشعار</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <img src="{{ $branch->settings?->logo }}" alt="image"
                                                            class="img-fluid">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div> --}}
                        {{-- 
                        @if ($branch->settings?->theme == 2 || $branch->settings->theme == 3 || $branch->settings->theme == 5)
                            <div class="col-12 col-xl-6">
                                <div class="mb-1 row align-items-center">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="pass-icon">صورة رأس الصفحة</label>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i data-feather="image"></i></span>
                                            <input type="file" id="pass-icon" class="form-control"
                                                name="header_image" placeholder="header_image" />
                                            @error('header_image')
                                                <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    @if ($branch->settings?->header_image)
                                        <div class="col-sm-3 delete-image-container">
                                            <div class="border p-1 position-relative">

                                                <button type="button" class="btn-close btn-delete position-absolute"
                                                    aria-label="Close"
                                                    onclick="deleteImage('header_image', this)"></button>

                                                <a href="{{ $branch->settings?->header_image }}" data-bs-toggle="modal"
                                                    data-bs-target="#headerImageModal">
                                                    <img src="{{ $branch->settings?->header_image }}" alt="image"
                                                        class="img-fluid " width="150" height="150">
                                                </a>

                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="headerImageModal" tabindex="-1"
                                                aria-labelledby="headerImageModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="headerImageModal">صورة رأس الصفحة
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <img src="{{ $branch->settings?->header_image }}"
                                                                alt="image" class="img-fluid">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif --}}
                        {{-- 
                        @if (auth('admin')->user()->hasRole('Super Admin'))
                            <div class="col-12 col-xl-6">
                                <div class="mb-1 row align-items-center">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="pass-icon">صورة ال QR</label>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i data-feather="image"></i></span>
                                            <input type="file" id="pass-icon" class="form-control" name="qr_image"
                                                placeholder="qr_image" />
                                            @error('qr_image')
                                                <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    @if ($branch->settings?->qr_image)
                                        <div class="col-sm-3 delete-image-container">

                                            <div class="border p-1 position-relative">

                                                <button type="button" class="btn-close btn-delete position-absolute"
                                                    aria-label="Close" onclick="deleteImage('qr_image', this)"></button>


                                                <a href="{{ $branch->settings?->qr_image }}" data-bs-toggle="modal"
                                                    data-bs-target="#qrImageModal">
                                                    <img src="{{ $branch->settings?->qr_image }}" alt="image"
                                                        class="img-fluid " width="150" height="150">
                                                </a>

                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="qrImageModal" tabindex="-1"
                                                aria-labelledby="qrImageModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="qrImageModalLabel">صورة ال QR</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <img src="{{ $branch->settings?->qr_image }}" alt="image"
                                                                class="img-fluid">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif --}}




                        {{-- Wifi --}}
                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">اسم الواي فاي</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="wifi"></i></span>
                                        <input type="text" id="contact-icon" class="form-control"
                                            name="wifi_username" value="{{ $branch->settings?->wifi_username ?? '' }}"
                                            placeholder="اسم الواي فاي" />
                                    </div>
                                    @error('wifi_username')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">باسورد الواي فاي</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="key"></i></span>
                                        <input type="text" id="contact-icon" class="form-control"
                                            name="wifi_password" value="{{ $branch->settings?->wifi_password ?? '' }}"
                                            placeholder="باسورد الواي فاي" />
                                    </div>
                                    @error('wifi_password')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}


                        {{-- Social Media --}}
                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">فيس بوك</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="link"></i></span>
                                        <input type="text" id="contact-icon" class="form-control" name="facebook"
                                            value="{{ $branch->settings?->facebook }}" placeholder="فيس بوك" />
                                    </div>
                                    @error('facebook')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}
                        {{-- 
                        <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">يوتيوب</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="link"></i></span>
                                        <input type="text" id="contact-icon" class="form-control" name="youtube"
                                            value="{{ $branch->settings?->youtube }}" placeholder="يوتيوب" />
                                    </div>
                                    @error('youtube')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">انستاجرام</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="link"></i></span>
                                        <input type="text" id="contact-icon" class="form-control" name="instagram"
                                            value="{{ $branch->settings?->instagram }}" placeholder="انستاجرام" />
                                    </div>
                                    @error('instagram')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">اكس</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="link"></i></span>
                                        <input type="text" id="contact-icon" class="form-control" name="x"
                                            value="{{ $branch->settings?->x }}" placeholder="اكس" />
                                    </div>
                                    @error('x')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}
                        {{-- 
                        <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">سناب شات</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="link"></i></span>
                                        <input type="text" id="contact-icon" class="form-control" name="snapchat"
                                            value="{{ $branch->settings?->snapchat }}" placeholder="سناب شات" />
                                    </div>
                                    @error('snapchat')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">تيك توك</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="link"></i></span>
                                        <input type="text" id="contact-icon" class="form-control" name="tiktok"
                                            value="{{ $branch->settings?->tiktok }}" placeholder="تيك توك" />
                                    </div>
                                    @error('tiktok')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">واتس اب</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="hash`"></i></span>
                                        <input type="text" id="contact-icon" class="form-control" name="whatsapp"
                                            value="{{ $branch->settings?->whatsapp }}" placeholder="واتس اب" />
                                    </div>
                                    @error('whatsapp')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}
                        {{-- 
                        <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">تيليجرام</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="hash"></i></span>
                                        <input type="text" id="contact-icon" class="form-control" name="telegram"
                                            value="{{ $branch->settings?->telegram }}" placeholder="تيليجرام" />
                                    </div>
                                    @error('telegram')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}
                        {{-- 
                        <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">الايميل</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="mail"></i></span>
                                        <input type="text" id="contact-icon" class="form-control" name="email"
                                            value="{{ $branch->settings?->email }}" placeholder="الايميل" />
                                    </div>
                                    @error('email')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                        </div> --}}




                        {{-- Colors --}}
                        {{-- <div class="col-12 col-xl-3">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">اللون الاول</label>
                                </div>
                                <div class="col-sm-3 col-xl-9">
                                    <div class="input-group input-group-merge">
                                        <input type="color" id="contact-icon" class="form-control"
                                            name="app_primary_color"
                                            value="{{ $branch->settings?->app_primary_color }}" />
                                    </div>
                                    @error('app_primary_color')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}
                        {{-- 
                        <div class="col-12 col-xl-3">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">اللون الثاني</label>
                                </div>
                                <div class="col-sm-3 col-xl-9">
                                    <div class="input-group input-group-merge">
                                        <input type="color" id="contact-icon" class="form-control"
                                            name="app_secondary_color"
                                            value="{{ $branch->settings?->app_secondary_color }}" />
                                    </div>
                                    @error('app_secondary_color')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div class="col-12 col-xl-3">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">لون المؤشر</label>
                                </div>
                                <div class="col-sm-3 col-xl-9">
                                    <div class="input-group input-group-merge">
                                        <input type="color" id="contact-icon" class="form-control"
                                            name="app_indicator_color"
                                            value="{{ $branch->settings?->app_indicator_color }}" />
                                    </div>
                                    @error('app_indicator_color')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div class="col-12 col-xl-3">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">اللون النصوص</label>
                                </div>
                                <div class="col-sm-3 col-xl-9">
                                    <div class="input-group input-group-merge">
                                        <input type="color" id="contact-icon" class="form-control"
                                            name="app_text_color" value="{{ $branch->settings?->app_text_color }}" />
                                    </div>
                                    @error('app_text_color')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}

                        {{-- Language --}}
                        {{-- <div class="col-12 col-xl-5">
                            <div class="mb-1 row">
                                <div class="col-sm-6 text-center">
                                    <label class="col-form-label text-nowrap" for="lang">
                                        لغه التطبيق الاساسية</label>
                                </div>
                                <div class="col-sm-6">
                                    <select class="select2 form-select" name="lang" id="lang">
                                        <option @if ($branch->settings->lang == 'en') selected @endif value="en">English
                                        </option>
                                        <option @if ($branch->settings->lang == 'ar') selected @endif value="ar">العربية
                                        </option>
                                    </select>

                                </div>
                            </div>
                        </div> --}}

                        {{-- Is Order Enabled --}}
                        {{-- <div class="col-12 col-xl-3">
                            <div class="mb-1 row align-items-center">
                                <div class="col-sm-5 text-center">
                                    <label class="col-form-label text-nowrap" for="is_order_enabled">تفعيل
                                        الطلبات</label>
                                </div>
                                <div class="col-sm-3 col-xl-7 d-flex align-items-center">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_order_enabled"
                                            name="is_order_enabled" value="1"
                                            {{ old('is_order_enabled', $branch->settings?->is_order_enabled ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_order_enabled"></label>
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                        {{-- Is QR Image Enabled --}}
                        {{-- <div class="col-12 col-xl-3">
                            <div class="mb-1 row align-items-center">
                                <div class="col-sm-5 text-center">
                                    <label class="col-form-label text-nowrap" for="is_qr_image_enabled">تفعيل صورة ال
                                        QR</label>
                                </div>
                                <div class="col-sm-3 col-xl-7 d-flex align-items-center">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_qr_image_enabled"
                                            name="is_qr_image_enabled" value="1"
                                            {{ old('is_qr_image_enabled', $branch->settings?->is_qr_image_enabled ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_qr_image_enabled"></label>
                                    </div>
                                </div>
                            </div>
                        </div> --}}


                        {{-- Send Orders To Whatsapp --}}
                        {{-- <div class="col-12 col-xl-3">
                            <div class="mb-1 row align-items-center">
                                <div class="col-sm-5 text-center">
                                    <label class="col-form-label text-nowrap" for="send_orders_to_whatsapp">
                                        ارسال الطلبات الي واتس اب</label>
                                </div>
                                <div class="col-sm-3 col-xl-7 d-flex align-items-center">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="send_orders_to_whatsapp"
                                            name="send_orders_to_whatsapp" value="1"
                                            {{ old('send_orders_to_whatsapp', $branch->settings?->send_orders_to_whatsapp ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="send_orders_to_whatsapp"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                         --}}


                        <div class="col-sm-9 offset-sm-3 mt-2">
                            <button type="submit" class="btn btn-primary me-1">تعديل</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('') }}admin/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="{{ asset('') }}admin/vendors/js/extensions/sweetalert2.all.min.js"></script>
    @if (session('updated'))
        <script>
            Swal.fire({
                title: 'أحسنت!',
                text: 'لقد تم تعديل الفرع بنجاح',
                icon: 'success',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            });
        </script>
    @endif
    <script>
        var select = $('.select2');

        select.each(function() {
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

    <script src="{{ asset('') }}admin/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
    <script src="{{ asset('') }}admin/js/scripts/forms/form-repeater.js"></script>

    <script>
        window.initMap = function() {
            var $latitude = $('#lat');
            var $longitude = $('#long');
            var $coords = $('#coords-input');
            var latitude = parseFloat($latitude.val()) || 31.267460208980072;
            var longitude = parseFloat($longitude.val()) || 29.99968838882789;

            var map = new google.maps.Map($('#map')[0], {
                zoom: $latitude.val() ? 15 : 8,
                center: {
                    lat: latitude,
                    lng: longitude
                }
            });

            var marker = new google.maps.Marker({
                position: {
                    lat: latitude,
                    lng: longitude
                },
                map: map,
                draggable: true
            });

            function setCoords(latitude, longitude) {
                if (isNaN(latitude) || isNaN(longitude)) {
                    $latitude.val('');
                    $longitude.val('');
                } else {
                    $latitude.val(latitude);
                    $longitude.val(longitude);
                    $coords.val(latitude + ', ' + longitude);
                    marker.setPosition({
                        lat: latitude,
                        lng: longitude
                    });
                    map.panTo({
                        lat: latitude,
                        lng: longitude
                    });
                }
            }

            marker.addListener('dragend', function() {
                var position = marker.getPosition();
                $latitude.val(position.lat());
                $longitude.val(position.lng());
                $coords.val(position.lat() + ', ' + position.lng());
            });

            map.addListener('click', function(event) {
                setCoords(event.latLng.lat(), event.latLng.lng());
            });

            $coords.on('input', function() {
                var parts = $coords.val().split(/[,،]/);
                setCoords(parseFloat(parts[0]), parseFloat(parts[1]));
            });
        };
    </script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGnjcgnojm_7IimdzBTnlhUgpwzhfv-1I&callback=initMap&v=weekly"
        defer></script>

    <script>
        function showDeliveryFeeMethod() {
            var delivery_fee_method = document.getElementById('delivery_fee_method').value;
            const fixed = document.getElementById('delivery_fee_fixed')
            const per_km = document.getElementById('delivery_fee_per_km')
            const per_charge = document.getElementById('delivery_fee_per_charge')
            const per_area = document.getElementById('delivery_fee_per_area')
            if (delivery_fee_method == 'fixed') {
                fixed.style.display = 'block';
                per_km.style.display = 'none';
                per_charge.style.display = 'none';
                per_area.style.display = 'none';
            } else if (delivery_fee_method == 'per_km') {
                fixed.style.display = 'none';
                per_km.style.display = 'block';
                per_charge.style.display = 'none';
                per_area.style.display = 'none';
            } else if (delivery_fee_method == 'per_charge') {
                fixed.style.display = 'none';
                per_km.style.display = 'none';
                per_charge.style.display = 'block';
                per_area.style.display = 'none';
            } else if (delivery_fee_method == 'per_area') {
                fixed.style.display = 'none';
                per_km.style.display = 'none';
                per_charge.style.display = 'none';
                per_area.style.display = 'block';
            } else {
                fixed.style.display = 'none';
                per_km.style.display = 'none';
                per_charge.style.display = 'none';
                per_area.style.display = 'none';
            }
        }

        $(document).ready(function() {
            showDeliveryFeeMethod();
            initWorkingHours24h();
        });

        function toggleWorkingHours24h(row) {
            const checkbox = row.find('.working-hours-24h');
            const fromInput = row.find('.working-hours-from');
            const toInput = row.find('.working-hours-to');
            const is24h = checkbox.is(':checked');

            if (is24h) {
                fromInput.val('').prop('disabled', true).prop('required', false);
                toInput.val('').prop('disabled', true).prop('required', false);
            } else {
                fromInput.prop('disabled', false).prop('required', true);
                toInput.prop('disabled', false).prop('required', true);
            }
        }

        function initWorkingHours24h() {
            $('#working_hours [data-repeater-item]').each(function() {
                toggleWorkingHours24h($(this));
            });
        }

        $(document).on('change', '#working_hours .working-hours-24h', function() {
            toggleWorkingHours24h($(this).closest('[data-repeater-item]'));
        });

        $('form.form-horizontal').on('submit', function() {
            $('#working_hours [data-repeater-item]').each(function() {
                const row = $(this);
                if (row.find('.working-hours-24h').is(':checked')) {
                    row.find('.working-hours-from').prop('disabled', false).val('00:00');
                    row.find('.working-hours-to').prop('disabled', false).val('00:00');
                }
            });
        });
    </script>
    {{-- <script>
        function deleteImage(field, clickedButton) {
            console.log(field, clickedButton);
            if (confirm('هل أنت متأكد من حذف الصورة؟')) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('branches.delete-setting-image') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "branch_id": "{{ $branch->id }}",
                        "field": field,
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status) {
                            $(clickedButton).closest('.delete-image-container').remove();
                            Swal.fire({
                                title: 'أحسنت!',
                                text: 'لقد تم حذف الصورة بنجاح',
                                icon: 'success',
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                },
                                buttonsStyling: false
                            });
                        }
                    },
                    error: function(response) {
                        Swal.fire({
                            title: 'خطأ!',
                            text: 'لم تتم حذف الصورة',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });
                    }
                });
            }
        }
    </script> --}}
@endsection
