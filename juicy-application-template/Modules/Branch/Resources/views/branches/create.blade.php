@extends('common::layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin/vendors/css/forms/select/select2.min.css">
    <style>
        #map {
            height: 400px;
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">انشاء فرع جديد</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="{{ url('admin/branches/') }}" method="POST"
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
                                        <input type="text" class="form-control" name="title_ar"
                                            placeholder="العنوان باللغة العربية" value="{{ old('title_ar') }}" />
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
                                        <input type="text" id="fname-icon" class="form-control" name="title_en"
                                            placeholder="العنوان باللغة الانجليزية" value="{{ old('title_en') }}" />
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
                                    <label class="col-form-label" for="contact-icon">رقم الهاتف</label>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="smartphone"></i></span>
                                        <input type="number" id="contact-icon" required class="form-control" name="phone"
                                            value="{{ old('phone') }}" placeholder="رقم الهاتف" />
                                        @error('phone')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">رقم الهاتف الثاني (اختياري)</label>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="smartphone"></i></span>
                                        <input type="number" id="contact-icon" class="form-control" name="secondary_phone"
                                            value="{{ old('secondary_phone') }}"
                                            placeholder="رقم الهاتف الثاني (اختياري)" />
                                        @error('secondary_phone')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
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
                                            placeholder="العنوان" value="{{ old('address_ar') }}" />
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
                                            placeholder="العنوان" value="{{ old('address_en') }}" />
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
                                            placeholder="المدينة باللغة العربية" value="{{ old('city_ar') }}" />
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
                                            placeholder="المدينة باللغة الانجليزية" value="{{ old('city_en') }}" />
                                        @error('city_en')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-12">
                            <div class="mb-1 row align-items-center">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon">الخريطة</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="mb-2" id="map"></div>
                                    <input type="text" id="coords-input" class="form-control form-control"
                                        value="{{ old('lat') && old('long') ? old('lat') . ', ' . old('long') : '31.267460208980072, 29.99968838882789' }}"
                                        placeholder="31.267460208980072, 29.99968838882789" dir="ltr" lang="en"
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <input type="hidden" id="lat" name="lat" value="{{ old('lat') }}">
                        <input type="hidden" id="long" name="long" value="{{ old('long') }}">
                        {{-- <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="pass-icon">طرق الطلب</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group-merge">
                                        <select name="order_methods[]" class="select2 form-select select2-hidden-accessible" multiple="" data-select2-id="select2-multiple" tabindex="-1" aria-hidden="true">
                                            @foreach ($order_methods as $method)
                                                <option value="{{$method->id}}">{{$method->title}}</option>
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
                                    <label class="col-form-label" for="pass-icon">الصوره</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="image"></i></span>
                                        <input type="file" id="pass-icon" class="form-control" name="image"
                                            placeholder="image" />
                                    </div>
                                    @error('image')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
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
                                            <option @if (in_array($payment_method['id'], old('payment_methods', []))) selected @endif
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
                                            <option @if (in_array($order_method['id'], old('order_methods', []))) selected @endif
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
                                    <input type="checkbox" value="1" name="is_active" class="form-check-input"
                                        id="customCheck2" />
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
                                    <label class="col-form-label" for="delivery_fee_method">طريقة حساب تكلفة
                                        التوصيل</label>
                                </div>
                                <div class="col-sm-9">
                                    <select required onchange="showDeliveryFeeMethod()" class="select2 form-select "
                                        name="delivery_fee_method" id="delivery_fee_method">
                                        <option value="{{ null }}">اختر طريقة حساب تكلفة التوصيل</option>
                                        <option @if (old('delivery_fee_method') == 'fixed') selected @endif value="fixed">قيمة ثابتة
                                        </option>
                                        <option @if (old('delivery_fee_method') == 'per_km') selected @endif value="per_km">قيمة لكل
                                            كيلومتر
                                        </option>
                                        <option @if (old('delivery_fee_method') == 'per_charge') selected @endif value="per_charge">قيمة
                                            لكل
                                            مسافة
                                        </option>
                                        <option @if (old('delivery_fee_method') == 'per_area') selected @endif value="per_area">قيمة
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
                                            value="{{ old('delivery_fee_fixed') }}" />
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
                                            value="{{ old('delivery_fee_per_km') }}" />
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
                                    <label class="col-form-label" for="pass-icon">تكلفة التوصيل بالمسافات</label>
                                </div>
                                <div class="col-sm-9 repeater-default">
                                    <div data-repeater-list="delivery_charges">
                                        <div data-repeater-item>
                                            <div class="row d-flex align-items-end">
                                                <div class="col-md-4 col-12">
                                                    <div class="mb-1">
                                                        <label class="form-label" for="distance"> المسافة
                                                            بالكيلو</label>
                                                        <input type="text" name="distance" class="form-control"
                                                            id="distance" aria-describedby="distance"
                                                            placeholder="المسافة بالكيلو" />
                                                    </div>
                                                </div>


                                                <div class="col-md-2 col-12">
                                                    <div class="mb-1">
                                                        <label class="form-label" for="price">السعر</label>
                                                        <input type="text" name="price" class="form-control"
                                                            id="price" aria-describedby="price"
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
                                    <label class="col-form-label" for="pass-icon">تكلفة التوصيل بالمناطق</label>
                                </div>
                                <div class="col-sm-9 repeater-default">
                                    <div data-repeater-list="delivery_areas">
                                        <div data-repeater-item>
                                            <div class="row d-flex align-items-end">
                                                <div class="col-md-4 col-12">
                                                    <div class="mb-1">
                                                        <label class="form-label" for="area_title">اسم المنطقة</label>
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
                                        <div data-repeater-item="day_select_option">
                                            <div class="row d-flex align-items-end">
                                                <div class="col-md-3 col-12">
                                                    <div class="mb-1">
                                                        <label class="form-label" for="day_select_option">اليوم</label>
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
                                                            <input type="checkbox" name="is_open_24_hours" value="1"
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
                                                            id="from" aria-describedby="from" placeholder="من" />
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-12">
                                                    <div class="mb-1">
                                                        <label class="form-label" for="to">الي</label>
                                                        <input type="time" name="to"
                                                            class="form-control working-hours-to" required id="to"
                                                            aria-describedby="to" placeholder="الي" />
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


                        {{-- Branch Manager --}}
                        <div class="divider divider-primary">
                            <div class="divider-text">مدير الفرع</div>
                        </div>

                        {{-- Name --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">الاسم</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" id="contact-icon" required class="form-control"
                                            name="manager_name" value="{{ old('manager_name') }}" placeholder="الاسم" />
                                    </div>
                                    @error('manager_name')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Phone --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">رقم الهاتف</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="smartphone"></i></span>
                                        <input type="number" id="contact-icon" required class="form-control"
                                            name="manager_phone" value="{{ old('manager_phone') }}"
                                            placeholder="رقم الهاتف" />
                                    </div>
                                    @error('manager_phone')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        {{-- Email --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">البريد الالكتروني</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="mail"></i></span>
                                        <input type="email" id="contact-icon" required class="form-control"
                                            name="manager_email" value="{{ old('manager_email') }}"
                                            placeholder="البريد الالكتروني" />
                                    </div>
                                    @error('manager_email')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        {{-- Password --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">الباسورد</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="lock"></i></span>
                                        <input type="password" id="contact-icon" required class="form-control"
                                            name="manager_password" value="{{ old('manager_password') }}"
                                            placeholder="الباسورد" />
                                    </div>
                                    @error('manager_password')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        {{-- Image --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="pass-icon">الصوره الشخصية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="image"></i></span>
                                        <input type="file" id="pass-icon" class="form-control" name="manager_image"
                                            placeholder="image" />
                                    </div>
                                    @error('manager_image')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="manager_is_active" value="1">

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
                                            name="currency_ar" value="{{ old('currency_ar') }}"
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
                                            name="currency_en" value="{{ old('currency_en') }}"
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
                                        <textarea placeholder="عن الفرع باللغه العربية" class="form-control" name="about_ar">{{ old('about_ar') }}</textarea>
                                    </div>
                                    @error('about_ar')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}
                        {{-- 
                        <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">عن الفرع باللغه الانجيليزية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <textarea placeholder="عن الفرع باللغه الانجيليزية" class="form-control" name="about_en">{{ old('about_en') }}</textarea>
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
                                        <textarea placeholder="الشروط و الاحكام باللغه" class="form-control" name="terms_ar">{{ old('terms_ar') }}</textarea>
                                    </div>
                                    @error('terms_ar')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">الشروط و الاحكام باللغه
                                        الانجيليزية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <textarea placeholder="الشروط و الاحكام باللغه" class="form-control" name="terms_en">{{ old('terms_en') }}</textarea>
                                    </div>
                                    @error('terms_en')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}



                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">الوصف في البداية باللغه
                                        العربية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <textarea placeholder="الوصف في البداية باللغه العربية" class="form-control" name="intro_message_ar">{{ old('intro_message_ar') }}</textarea>
                                    </div>
                                    @error('intro_message_ar')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}





                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">الوصف في البداية باللغه
                                        الانجيليزية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <textarea placeholder="الوصف في البداية باللغه الانجيليزية" class="form-control" name="intro_message_en">{{ old('intro_message_en') }}</textarea>
                                    </div>
                                    @error('intro_message_en')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}


                        {{-- Tax Message --}}
                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">رسالة الضريبة باللغه العربية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <textarea placeholder="رسالة الضريبة باللغه العربية" class="form-control" name="tax_message_ar">{{ old('tax_message_ar') }}</textarea>
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
                                        <textarea placeholder="رسالة الضريبة باللغه الانجيليزية" class="form-control" name="tax_message_en">{{ old('tax_message_en') }}</textarea>
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
                                        <textarea placeholder="رابط تقييم جوجل" class="form-control" name="google_rate_url">{{ old('google_rate_url') }}</textarea>
                                    </div>
                                    @error('google_rate_url')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}






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
                                            value="{{ old('tax') }}" placeholder="الضريبة" />
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
                                            value="{{ old('tax_number') }}" placeholder="الرقم الضريبي" />
                                    </div>
                                    @error('tax_number')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}

                        {{-- Images --}}
                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="pass-icon">صورة الخلفية </label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="image"></i></span>
                                        <input type="file" id="pass-icon" class="form-control"
                                            name="app_background_image" placeholder="app_background_image" />
                                    </div>
                                    @error('app_background_image')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="pass-icon">صورة العرض </label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="image"></i></span>
                                        <input type="file" id="pass-icon" class="form-control" name="app_offer_image"
                                            placeholder="app_offer_image" />
                                    </div>
                                    @error('app_offer_image')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="pass-icon">صورة الشعار </label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="image"></i></span>
                                        <input type="file" id="pass-icon" class="form-control" name="logo"
                                            placeholder="logo" />
                                    </div>
                                    @error('logo')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}


                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="pass-icon">صورة ال QR </label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="image"></i></span>
                                        <input type="file" id="pass-icon" class="form-control" name="qr_image"
                                            placeholder="QR Image" />
                                    </div>
                                    @error('qr_image')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}



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
                                            name="wifi_username" value="{{ old('wifi_username') }}"
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
                                            name="wifi_password" value="{{ old('wifi_password') }}"
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
                                            value="{{ old('facebook') }}" placeholder="فيس بوك" />
                                    </div>
                                    @error('facebook')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">يوتيوب</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="link"></i></span>
                                        <input type="text" id="contact-icon" class="form-control" name="youtube"
                                            value="{{ old('youtube') }}" placeholder="يوتيوب" />
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
                                            value="{{ old('instagram') }}" placeholder="انستاجرام" />
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
                                            value="{{ old('x') }}" placeholder="اكس" />
                                    </div>
                                    @error('x')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">سناب شات</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="link"></i></span>
                                        <input type="text" id="contact-icon" class="form-control" name="snapchat"
                                            value="{{ old('snapchat') }}" placeholder="سناب شات" />
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
                                            value="{{ old('tiktok') }}" placeholder="تيك توك" />
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
                                        <span class="input-group-text"><i data-feather="hash"></i></span>
                                        <input type="text" id="contact-icon" class="form-control" name="whatsapp"
                                            value="{{ old('whatsapp') }}" placeholder="واتس اب" />
                                    </div>
                                    @error('whatsapp')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">تيليجرام</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="hash"></i></span>
                                        <input type="text" id="contact-icon" class="form-control" name="telegram"
                                            value="{{ old('telegram') }}" placeholder="تيليجرام" />
                                    </div>
                                    @error('telegram')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div class="col-12 col-xl-6">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">الايميل</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="mail"></i></span>
                                        <input type="text" id="contact-icon" class="form-control" name="email"
                                            value="{{ old('email') }}" placeholder="الايميل" />
                                    </div>
                                    @error('email')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                        </div> --}}


                        {{-- <div class="col-12 col-xl-6">


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
                                            name="app_primary_color" value="{{ old('app_primary_color') }}" />
                                    </div>
                                    @error('app_primary_color')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div class="col-12 col-xl-3">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">اللون الثاني</label>
                                </div>
                                <div class="col-sm-3 col-xl-9">
                                    <div class="input-group input-group-merge">
                                        <input type="color" id="contact-icon" class="form-control"
                                            name="app_secondary_color" value="{{ old('app_secondary_color') }}" />
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
                                            name="app_indicator_color" value="{{ old('app_indicator_color') }}" />
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
                                            name="app_text_color" value="{{ old('app_text_color') }}" />
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
                                        <option @if (old('lang') == 'en') selected @endif value="en">English
                                        </option>
                                        <option @if (old('lang') == 'ar') selected @endif value="ar">العربية
                                        </option>
                                    </select>

                                </div>
                            </div>
                        </div> --}}



                        {{-- Is Order Enabled --}}
                        {{-- <div class="col-12 col-xl-3">
                            <div class="mb-1 row align-items-center">
                                <div class="col-sm-5 text-center">
                                    <label class="col-form-label text-nowrap" for="is_order_enabled">تفعيل الطلبات</label>
                                </div>
                                <div class="col-sm-3 col-xl-7 d-flex align-items-center">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_order_enabled"
                                            name="is_order_enabled" value="1"
                                            {{ old('is_order_enabled', true) ? 'checked' : '' }}>
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
                                            {{ old('is_qr_image_enabled', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_qr_image_enabled"></label>
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                        {{-- Send Orders To Whatsapp --}}
                        {{-- <div class="col-12 col-xl-3">
                            <div class="mb-1 row align-items-center">
                                <div class="col-sm-5 text-center">
                                    <label class="col-form-label text-nowrap" for="send_orders_to_whatsapp">ارسال الطلبات
                                        الي واتس اب
                                    </label>
                                </div>
                                <div class="col-sm-3 col-xl-7 d-flex align-items-center">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="send_orders_to_whatsapp"
                                            name="send_orders_to_whatsapp" value="1"
                                            {{ old('send_orders_to_whatsapp', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="send_orders_to_whatsapp"></label>
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                        <div class="col-sm-9 offset-sm-3 mt-2">
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
    <script src="{{ asset('') }}admin/vendors/js/forms/select/select2.full.min.js"></script>
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

            $coords.val(latitude + ', ' + longitude);

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
@endsection
