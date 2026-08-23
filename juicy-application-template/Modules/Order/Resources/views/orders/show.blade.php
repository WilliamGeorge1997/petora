@extends('common::layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin/css-rtl/pages/app-invoice.css">
@endsection

@section('content')
    <div class="content-body">
        <section class="invoice-preview-wrapper">
            <div class="row invoice-preview">
                <!-- Invoice -->
                <div class="col-xl-12 col-md-8 col-12">
                    <div class="card invoice-preview-card">
                        <div class="card-body invoice-padding pb-0">
                            <!-- Header starts -->
                            <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                                <div>
                                    <div class="logo-wrapper">
                                        <svg viewBox="0 0 139 95" version="1.1" xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" height="24">
                                            <defs>
                                                <linearGradient id="invoice-linearGradient-1" x1="100%"
                                                    y1="10.5120544%" x2="50%" y2="89.4879456%">
                                                    <stop stop-color="#000000" offset="0%"></stop>
                                                    <stop stop-color="#FFFFFF" offset="100%"></stop>
                                                </linearGradient>
                                                <linearGradient id="invoice-linearGradient-2" x1="64.0437835%"
                                                    y1="46.3276743%" x2="37.373316%" y2="100%">
                                                    <stop stop-color="#EEEEEE" stop-opacity="0" offset="0%"></stop>
                                                    <stop stop-color="#FFFFFF" offset="100%"></stop>
                                                </linearGradient>
                                            </defs>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-400.000000, -178.000000)">
                                                    <g transform="translate(400.000000, 178.000000)">
                                                        <path class="text-primary"
                                                            d="M-5.68434189e-14,2.84217094e-14 L39.1816085,2.84217094e-14 L69.3453773,32.2519224 L101.428699,2.84217094e-14 L138.784583,2.84217094e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L6.71554594,44.4188507 C2.46876683,39.9813776 0.345377275,35.1089553 0.345377275,29.8015838 C0.345377275,24.4942122 0.230251516,14.560351 -5.68434189e-14,2.84217094e-14 Z"
                                                            style="fill: currentColor"></path>
                                                        <path
                                                            d="M69.3453773,32.2519224 L101.428699,1.42108547e-14 L138.784583,1.42108547e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L32.8435758,70.5039241 L69.3453773,32.2519224 Z"
                                                            fill="url(#invoice-linearGradient-1)" opacity="0.2"></path>
                                                        <polygon fill="#000000" opacity="0.049999997"
                                                            points="69.3922914 32.4202615 32.8435758 70.5039241 54.0490008 16.1851325">
                                                        </polygon>
                                                        <polygon fill="#000000" opacity="0.099999994"
                                                            points="69.3922914 32.4202615 32.8435758 70.5039241 58.3683556 20.7402338">
                                                        </polygon>
                                                        <polygon fill="url(#invoice-linearGradient-2)" opacity="0.099999994"
                                                            points="101.428699 0 83.0667527 94.1480575 130.378721 47.0740288">
                                                        </polygon>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                        {{-- <h3 class="text-primary invoice-logo">QMenu</h3> --}}
                                    </div>
                                    <p class="card-text mb-25">الفرع : {{ $order['branch']['title'] }}</p>
                                    {{-- @isset($order['client'])
                                        <p class="card-text mb-25">اسم العميل : {{ $order['client']['name'] }}</p>
                                        <p class="card-text mb-25">رقم هاتف العميل : {{ $order['client']['phone'] }}</p>
                                    @endisset --}}

                                    <div class="mb-25">
                                        <a href="javascript:void(0)"
                                            onclick="window.open('{{ route('orders.print', $order->id) }}', '_blank', 'width=800,height=600')">
                                            <button type="button"
                                                class="btn btn-primary waves-effect waves-float waves-light">
                                                <span style="margin-left: 5px">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-printer">
                                                        <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                                        <path
                                                            d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2">
                                                        </path>
                                                        <rect x="6" y="14" width="12" height="8"></rect>
                                                    </svg>
                                                </span>
                                                طباعة الفاتورة
                                            </button>
                                        </a>
                                    </div>
                                </div>
                                <div class="mt-md-0 mt-2">
                                    <h4 class="invoice-title">
                                        Order
                                        <span class="invoice-number">{{ $order['uuid'] }}</span>
                                    </h4>
                                    <div class="invoice-date-wrapper">
                                        تغيير حالة الطلب

                                        <a onclick="getorderId({{ $order->id }})" href="#" data-bs-toggle="modal"
                                            data-bs-target="#changeModal">

                                            <button class="btn btn-info btn-icon" data-bs-toggle="modal"
                                                style="margin-right: 20px" data-bs-target="#changeModal"> <svg
                                                    xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-activity">
                                                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                                                </svg></button>

                                        </a>



                                    </div>
                                    <div class="invoice-date-wrapper">
                                        <p class="invoice-date-title">تــاريــخ الانشـــاء:</p>
                                        <p class="invoice-date">{{ date('d-m-Y', strtotime($order->created_at)) }}</p>
                                    </div>
                                    <div class="invoice-date-wrapper">
                                        <p class="invoice-date-title">وقـــــت الانشـــاء:</p>
                                        <p class="invoice-date">
                                            {{ date('H:i A', strtotime($order->created_at)) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- Header ends -->
                        </div>

                        <hr class="invoice-spacing" />

                        <!-- Address and Contact starts -->
                        <div class="card-body invoice-padding pt-0">
                            <div class="row invoice-spacing">
                                <div class="col-xl-8 p-0">
                                    <h5 class="mb-2">تفاصيل الطلب:</h6>
                                        @if (!empty($order['name']))
                                            <h6 class="mb-25">اسم العميل : {{ $order['name'] }}</h6>
                                        @endif
                                        @if (!empty($order['phone']))
                                            <h6 class="mb-25">رقم العميل : {{ $order['phone'] }}</h6>
                                        @endif
                                        <h6 class="mb-25">حالة الطلب : {{ $order['orderStatus']['title'] }}</h6>
                                        <h6 class="mb-25">طريقة الطلب : {{ $order['orderMethod']['title'] }}</h6>
                                        @if ($order->order_method_id == Modules\Order\Entities\OrderMethod::RECEIPT_FROM_BRANCH)
                                            @if (!empty($order->table_no))
                                                <h6 class="mb-25">
                                                    رقم الطاولة:
                                                    <span class="badge bg-primary"
                                                        style="font-size: 1rem;">{{ $order->table_no }}</span>
                                                </h6>
                                            @endif
                                        @elseif($order->order_method_id == Modules\Order\Entities\OrderMethod::RECEIPT_IN_CAR)
                                            <div class="d-flex flex-row mb-25 gap-1 align-items-center">
                                                @if (!empty($order->car_no))
                                                    <h6 class="mb-0">رقم السيارة:
                                                        <span class="badge bg-primary"
                                                            style="font-size: 1rem;">{{ $order->car_no }}</span>
                                                    </h6>
                                                @endif
                                                @if (!empty($order->car_color))
                                                    <h6 class="mb-0 ms-3">لون السيارة:
                                                        <span class="badge bg-primary"
                                                            style="font-size: 1rem;">{{ $order->car_color }}</span>
                                                    </h6>
                                                @endif
                                                @if (!empty($order->parking_no))
                                                    <h6 class="mb-0 ms-3">رقم الموقف:
                                                        <span class="badge bg-primary"
                                                            style="font-size: 1rem;">{{ $order->parking_no }}</span>
                                                    </h6>
                                                @endif
                                            </div>
                                        @else
                                            @if (!empty($order->address))
                                                <div class="d-flex flex-row mb-25 gap-1 align-items-center">
                                                    <h6 class="mb-0">
                                                        عنوان التوصيل (عربي):
                                                        <span style="font-size: 1rem;">
                                                            {{ $order->getTranslations('address')['ar'] ?? '' }}
                                                        </span>
                                                    </h6>
                                                    <h6 class="mb-0 ms-3">
                                                        عنوان التوصيل (إنجليزي):
                                                        <span style="font-size: 1rem;">
                                                            {{ $order->getTranslations('address')['en'] ?? '' }}
                                                        </span>
                                                    </h6>
                                                </div>
                                            @endif
                                            @if (!empty($order->lat) && !empty($order->long))
                                                <div class="mt-1">
                                                    <a href="https://maps.google.com/?q={{ $order->lat }},{{ $order->long }}"
                                                        target="_blank" class="btn btn-primary">
                                                        عرض الموقع علي الخريطة
                                                    </a>
                                                </div>
                                            @endif
                                        @endif
                                </div>
                                <div class="col-xl-4 p-0 mt-xl-0 mt-2">
                                    <h5 class="mb-2">تفاصيل الدفع:</h5>
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td class="pe-1">طريقة الدفع:</td>

                                                <td><span
                                                        class="fw-bold">{{ $order['paymentMethod']->getTranslations('title')['ar'] }}</span>
                                                </td>
                                            </tr>


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Address and Contact ends -->

                        <!-- Invoice Description starts -->
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="py-1">المنتج</th>
                                        <th class="py-1">السعر</th>
                                        <th class="py-1">الكمية</th>
                                        <th class="py-1">سعر المنتجات</th>
                                        <th class="py-1">الخصائص</th>
                                        <th class="py-1">الإضافات</th>
                                        <th class="py-1">الإجمالي</th>
                                        <th class="py-1">الملاحظات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @dd($order) --}}
                                    @foreach ($order['details'] as $detail)
                                        <tr>
                                            <td class="py-1">
                                                <p class="card-text fw-bold mb-25">{{ $detail['product']['title'] }}</p>
                                                @if ($detail['sides']->count() > 0)
                                                    <div class="d-flex align-items-center flex-wrap small text-muted">
                                                        @foreach ($detail['sides'] as $side)
                                                            <div class="d-flex align-items-center">
                                                                <img src="{{ $side->sideValue->image ?? asset('admin/images/placeholder.png') }}"
                                                                    alt="{{ $side->sideValue->getTranslations('title')['ar'] ?? '' }}"
                                                                    style="width: 28px; height: 28px; object-fit: cover; border-radius: 6px; margin-left: 6px;">
                                                                <span>
                                                                    {{ $side->sideValue->getTranslations('title')['ar'] ?? '' }}
                                                                </span>
                                                            </div>
                                                            @if (!$loop->last)
                                                                <span class="mx-1">-</span>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="py-1">
                                                <span class="fw-bold">{{ $detail['product_price'] }}</span>
                                            </td>
                                            <td class="py-1">
                                                <span class="fw-bold">{{ $detail['quantity'] }}</span>
                                            </td>
                                            <td class="py-1">
                                                <span
                                                    class="fw-bold">{{ $detail['product_price'] * $detail['quantity'] }}</span>
                                            </td>

                                            <td class="py-1">
                                                @if ($detail['attributes']->count() > 0)
                                                    {{-- @dd($detail['attributes']) --}}
                                                    @foreach ($detail['attributes'] as $attribute)
                                                        <div class="mb-1 small">
                                                            <strong>{{ $attribute->attribute->getTranslations('title')['ar'] }}</strong><br>
                                                            <span class="text-muted">القيمة:</span>
                                                            {{ $attribute->attributeValue->getTranslations('attribute_value')['ar'] }}<br>
                                                            <span class="text-muted">السعر:</span>
                                                            {{ $attribute->price }} * {{ $detail['quantity'] }} =
                                                            {{ $attribute->price * $detail['quantity'] }} <br>
                                                            <span class="text-muted">النوع:</span>
                                                            @if ($attribute->attribute->override_price)
                                                                <span class="badge bg-warning">تبديل السعر</span>
                                                            @else
                                                                <span class="badge bg-info">إضافة للسعر</span>
                                                            @endif
                                                        </div>
                                                        @if (!$loop->last)
                                                            <hr class="my-1">
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>

                                            <td class="py-1">
                                                @if ($detail['addons']->count() > 0)
                                                    @foreach ($detail['addons'] as $addon)
                                                        <div class="mb-1 small">
                                                            <strong>{{ $addon->addonValue->addon->getTranslations('title')['ar'] }}</strong><br>
                                                            <span class="text-muted">القيمة:</span>
                                                            {{ $addon->addonValue->getTranslations('title')['ar'] }}<br>
                                                            <span class="text-muted">السعر:</span> {{ $addon->price }} *
                                                            {{ $detail['quantity'] }} =
                                                            {{ $addon->price * $detail['quantity'] }} <br>
                                                        </div>
                                                        @if (!$loop->last)
                                                            <hr class="my-1">
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>

                                            <td class="py-1">
                                                <span class="fw-bold text-success">{{ $detail['total'] }}</span>
                                            </td>
                                            <td class="py-1">
                                                <span class="fw-bold" style="color: red">
                                                    {{ $detail['note'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="card-body invoice-padding pb-0">
                            <div class="row invoice-sales-total-wrapper">
                                <div class="col-md-6 order-md-1 order-2 mt-md-0 mt-3">

                                    @if ($order['notes'])
                                        <p class="card-text mb-0">
                                            <span class="fw-bold">الملاحظات:</span> <span class="ms-75"
                                                style="color: red">{{ $order['notes'] }}</span>
                                        </p>
                                    @endif
                                </div>

                                <div class="col-md-6 d-flex justify-content-end order-md-2 order-1">
                                    <div class="invoice-total-wrapper">
                                        <div class="invoice-total-item">
                                            <p class="invoice-total-title">السعر قبل الخصم:</p>
                                            <p class="invoice-total-amount">+ {{ $order['subtotal'] }}</p>
                                        </div>
                                        <div class="invoice-total-item">
                                            <p class="invoice-total-title">الضريبة:</p>
                                            <p class="invoice-total-amount">+ {{ $order['tax'] }}</p>
                                        </div>
                                        <div class="invoice-total-item">
                                            <p class="invoice-total-title">رسوم الخدمة:</p>
                                            <p class="invoice-total-amount">+ {{ $order['service'] ?? 0 }}</p>
                                        </div>
                                        <div class="invoice-total-item">
                                            <p class="invoice-total-title">
                                                @if ($order['discount_type'] == \Modules\Order\Entities\Order::DISCOUNT_WITH_COUPON)
                                                    خصم (كوبون):
                                                @elseif ($order['discount_type'] == \Modules\Order\Entities\Order::DISCOUNT_WITH_POINTS)
                                                    خصم (نقاط):
                                                @elseif ($order['discount_type'] == \Modules\Order\Entities\Order::DISCOUNT_WITH_BRANCH)
                                                    خصم (قيمة الطلب):
                                                @else
                                                    الخصم:
                                                @endif
                                            </p>
                                            <p class="invoice-total-amount">- {{ $order['discount'] }}</p>
                                        </div>
                                        <hr class="my-50" />
                                        <div class="invoice-total-item">
                                            <p class="invoice-total-title">الاجمالي:</p>
                                            <p class="invoice-total-amount">{{ $order['total'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Invoice Description ends -->

                        <hr class="invoice-spacing" />


                    </div>
                </div>
                <!-- /Invoice -->
                <!-- Invoice Description ends -->

                @if ($order['rate'])
                    <hr class="invoice-spacing" />


                    <div class="col-xl-8 p-0">
                        <h5 class="mb-2 py-1">التقييم</h6>
                    </div>


                    <!-- Invoice Description starts -->
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    {{-- <th class="py-1">اسم الشخص</th> --}}
                                    <th class="py-1">تقييم الفرع</th>
                                    <th class="py-1">تقييم الطلب</th>
                                    <th class="py-1">الملاحظة</th>
                                    <th class="py-1">تاريخ الانشاء</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    {{-- <td class="py-1"> --}}
                                    {{-- <p class="card-text fw-bold mb-25">{{ $order['client']['name'] }}</p> --}}
                                    {{-- <p class="card-text text-nowrap"> --}}
                                    {{-- {{$detail['product']['description']}} --}}
                                    {{-- </p> --}}
                                    {{-- </td> --}}
                                    <td class="py-1">
                                        <span class="fw-bold">{{ $order['rate']['branch_rate'] . ' / 5' }}</span>
                                    </td>

                                    <td class="py-1">
                                        <span class="fw-bold">{{ $order['rate']['order_rate'] . ' / 5' }}</span>
                                    </td>

                                    <td class="py-1">
                                        <span class="fw-bold">{{ $order['rate']['comment'] }}</span>
                                    </td>

                                    <td class="py-1">
                                        <span class="fw-bold">{{ $order['rate']['created_at'] }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif

                <hr class="invoice-spacing" />


                <div class="col-xl-8 p-0">
                    <h5 class="mb-2 py-1">السجل الخاص بالطلب</h6>
                </div>


                <!-- Invoice Description starts -->
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                {{-- <th class="py-1">اسم الشخص</th>
                                <th class="py-1">النوع</th> --}}
                                <th class="py-1">حالة الطلب</th>
                                <th class="py-1">الملاحظة</th>
                                <th class="py-1">تاريخ الانشاء</th>
                            </tr>
                        </thead>
                        {{-- @dd($order['histories']) --}}
                        <tbody>
                            @foreach ($order['histories'] as $history)
                                <tr>
                                    {{-- <td class="py-1">
                                        <p class="card-text fw-bold mb-25">{{ $history['historible']['name'] ?? 'Client' }}</p> --}}
                                    {{-- <p class="card-text text-nowrap"> --}}
                                    {{-- {{$detail['product']['description']}} --}}
                                    {{-- </p> --}}
                                    {{-- </td> --}}
                                    {{-- <td class="py-1">
                                        <span
                                            class="fw-bold">{{ substr($history['historible_type'], strpos($history['historible_type'], 'Entities') + 9) }}</span>
                                    </td> --}}

                                    <td class="py-1">
                                        <span class="fw-bold">{{ $history['status']['title'] }}</span>
                                    </td>

                                    <td class="py-1">
                                        <span class="fw-bold">{{ $history['notes'] }}</span>
                                    </td>

                                    <td class="py-1">
                                        <span class="fw-bold">{{ $history['created_at'] }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Invoice Note starts -->

            </div>
        </section>


    </div>




    <div class="modal fade text-start" id="changeModal" tabindex="-1" aria-labelledby="myModalLabel33"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">

            <form action="{{ url('admin/orders/updateStatus') }}" method="POST" enctype="multipart/form-data">
                {{ method_field('PUT') }}
                {{ csrf_field() }}

                <input type="hidden" value="" id="order_id" name="order_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel120">تعديل البيانات الخاصه بالطلب </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="pass-icon">حالات الطلب</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group-merge">
                                            <select name="order_status_id" id="status"
                                                class="select2 form-select select2-hidden-accessible order_status"
                                                tabindex="-1" aria-hidden="true">

                                            </select>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--
                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="pass-icon">الوقت المتوقع لاتمام الطلب</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group-merge">
                                            <select name="duration_time"
                                                class="select2 form-select select2-hidden-accessible order_time"
                                                tabindex="-1" aria-hidden="true">

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}


                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="fname-icon"> الملاحظة</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i data-feather="user"></i></span>
                                            <input type="text" class="form-control" name="notes"
                                                placeholder="الملاحظة" />

                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">الغاء</button>
                        <button type="submit" class="btn btn-success" data-bs-dismiss="modal">تاكيد</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function getorderId(val, status) {
            document.getElementById("order_id").value = val;
            // $.ajax({
            //     url: '{{ url('admin/ajax_order_time') }}',
            //     type: "POST",
            //     data: {
            //         "_token": "{{ csrf_token() }}",
            //         id: val
            //     },
            //     success: function(data) {
            //         $('.order_time').html(data);
            //     },
            //     error: function(error) {
            //         console.log(error);
            //     }
            // });

            $.ajax({
                url: '{{ url('admin/ajax_order_status') }}',
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: val
                },
                success: function(data) {

                    $('.order_status').html(data);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    </script>
@endsection
