<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة الطلب - {{ $order->order_no }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            background-color: #f5f5f5;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            direction: ltr;
            /* Force LTR for body */
        }

        .receipt-container {
            width: 80mm;
            max-width: 80mm;
            background: white;
            padding: 10mm;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            direction: ltr;
            /* Force LTR for container */
            overflow: hidden;
            /* Prevent text overflow */
        }

        .receipt-header {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
            direction: ltr;
        }

        .receipt-header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .receipt-header p {
            font-size: 12px;
            margin: 2px 0;
        }

        .receipt-info {
            font-size: 11px;
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 8px;
            direction: rtl;
        }

        .receipt-info p {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
            direction: rtl;
            width: 100%;
        }

        .receipt-info .label {
            font-weight: bold;
        }

        .items-section {
            margin-bottom: 10px;
            direction: rtl;
        }

        .item {
            margin-bottom: 8px;
            font-size: 11px;
            direction: rtl;
            width: 100%;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            margin-bottom: 3px;
            direction: rtl;
            width: 100%;
        }

        .item-details {
            padding-right: 10px;
            color: #666;
            font-size: 10px;
            margin: 2px 0;
            text-align: right;
        }

        .item-addon,
        .item-attribute {
            display: flex;
            justify-content: space-between;
            padding-right: 15px;
            margin: 2px 0;
            direction: rtl;
            width: 100%;
        }

        .item-note {
            padding-right: 15px;
            font-style: italic;
            color: #888;
            margin-top: 3px;
            text-align: right;
        }

        .totals-section {
            border-top: 1px dashed #000;
            padding-top: 8px;
            margin-top: 8px;
            font-size: 11px;
            direction: rtl;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 4px 0;
            direction: rtl;
            width: 100%;
        }

        .total-row.grand-total {
            font-size: 14px;
            font-weight: bold;
            border-top: 2px solid #000;
            padding-top: 6px;
            margin-top: 6px;
        }

        .receipt-footer {
            text-align: center;
            border-top: 2px dashed #000;
            padding-top: 10px;
            margin-top: 10px;
            font-size: 11px;
            direction: rtl;
        }

        .receipt-footer p {
            margin: 3px 0;
        }

        .thank-you {
            font-weight: bold;
            font-size: 13px;
            margin-top: 8px;
        }

        .zatca-qr-code {
            text-align: center;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed #ccc;
        }

        .zatca-qr-code svg,
        .zatca-qr-code img {
            max-width: 100%;
            height: auto;
        }

        @page {
            size: 80mm auto;
            margin: 0;
        }

        @media print {

            html,
            body {
                width: 80mm;
                height: auto;
                margin: 0;
                padding: 0;
                background: white;
                direction: ltr;
            }

            body {
                padding: 0;
                display: block;
                min-height: auto;
            }

            .receipt-container {
                width: 80mm;
                max-width: 80mm;
                box-shadow: none;
                margin: 0;
                padding: 5mm;
                page-break-after: avoid;
                direction: ltr;
            }

            /* Hide everything except receipt */
            body * {
                visibility: hidden;
            }

            .receipt-container,
            .receipt-container * {
                visibility: visible;
            }

            .receipt-container {
                position: absolute;
                left: 0;
                top: 0;
            }
        }
    </style>
</head>

<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="receipt-header">
            @if (!empty($settings['logo']))
                <div class="text-center mb-2">
                    <img src="{{ $settings['logo'] }}" alt="Logo" style="width: 100px; height: auto;">
                </div>
            @endif
            @if (!empty($settings['name']))
                <h1>{{ $settings['name'] }}</h1>
            @endif
            <p>{{ $order->branch->getTranslations('title')['ar'] }}</p>
            @if ($order->branch->phone ?? false)
                <p>{{ $order->branch->phone }}</p>
            @endif
            @if ($order->branch->address ?? false)
                <p>{{ $order->branch->getTranslations('address')['ar'] }}</p>
            @endif
        </div>

        <!-- Order Info -->
        <div class="receipt-info">
            <p>
                <span class="label">رقم الطلب:</span>
                <span>{{ $order->order_no }}</span>
            </p>
            <p>
                <span class="label">التاريخ:</span>
                <span>{{ $order->created_at->format('Y-m-d') }}</span>
            </p>
            <p>
                <span class="label">الوقت:</span>
                <span>{{ $order->created_at->format('h:i A') }}</span>
            </p>
            @if ($order->orderMethod)
                <p>
                    <span class="label">طريقة الطلب:</span>
                    <span>{{ $order->orderMethod->getTranslations('title')['ar'] }}</span>
                </p>
            @endif
            @if ($order->paymentMethod)
                <p>
                    <span class="label">طريقة الدفع:</span>
                    <span>{{ $order->paymentMethod->getTranslations('title')['ar'] }}</span>
                </p>
            @endif
        </div>

        <!-- Items Section -->
        <div class="items-section">
            @foreach ($order->details as $detail)
                <div class="item">
                    <div class="item-header">
                        <span>x{{ $detail->quantity }} {{ $detail->product->getTranslations('title')['ar'] }} </span>
                        <span>{{ number_format($detail->total, 2) }}</span>
                    </div>

                    @if ($detail->productType)
                        <div class="item-details">
                            النوع: {{ $detail->productType->getTranslations('title')['ar'] }}
                            @if ($detail->product_type_price > 0)
                                ({{ number_format($detail->product_type_price, 2) }} ج.م)
                            @endif
                        </div>
                    @endif

                    @if ($detail->attributes && $detail->attributes->count() > 0)
                        @foreach ($detail->attributes as $attribute)
                            <div class="item-attribute">
                                <span>{{ $attribute->attribute->getTranslations('title')['ar'] }}:
                                    {{ $attribute->attributeValue->getTranslations('attribute_value')['ar'] }}</span>
                                @if ($attribute->price > 0)
                                    <span>+{{ number_format($attribute->price, 2) }}</span>
                                @endif
                            </div>
                        @endforeach
                    @endif

                    @if ($detail->addons && $detail->addons->count() > 0)
                        @foreach ($detail->addons as $addon)
                            <div class="item-addon">
                                <span>{{ $addon->addon->getTranslations('title')['ar'] }}:
                                    {{ $addon->addonValue->getTranslations('title')['ar'] }}</span>
                                @if ($addon->price > 0)
                                    <span>+{{ number_format($addon->price, 2) }}</span>
                                @endif
                            </div>
                        @endforeach
                    @endif

                    @if ($detail->note)
                        <div class="item-note">
                            ملاحظة: {{ $detail->note }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Totals Section -->
        <div class="totals-section">
            <div class="total-row">
                <span>المجموع الفرعي:</span>
                <span>{{ number_format($order->subtotal, 2) }} ج.م</span>
            </div>

            @if ($order->discount > 0)
                <div class="total-row">
                    <span>
                        @if ($order->discount_type == \Modules\Order\Entities\Order::DISCOUNT_WITH_COUPON)
                            خصم (كوبون):
                        @elseif ($order->discount_type == \Modules\Order\Entities\Order::DISCOUNT_WITH_POINTS)
                            خصم (نقاط):
                        @elseif ($order->discount_type == \Modules\Order\Entities\Order::DISCOUNT_WITH_BRANCH)
                            خصم (قيمة الطلب):
                        @else
                            الخصم:
                        @endif
                    </span>
                    <span>-{{ number_format($order->discount, 2) }} ج.م</span>
                </div>
            @endif

            @if ($order->delivery_fee > 0)
                <div class="total-row">
                    <span>رسوم التوصيل:</span>
                    <span>{{ number_format($order->delivery_fee, 2) }} ج.م</span>
                </div>
            @endif

            @if ($order->tax > 0)
                <div class="total-row">
                    <span>الضريبة:</span>
                    <span>{{ number_format($order->tax, 2) }} ج.م</span>
                </div>
            @endif

            <div class="total-row grand-total">
                <span>الإجمالي:</span>
                <span>{{ number_format($order->total, 2) }} ج.م</span>
            </div>

            @if ($order->notes)
                <div style="margin-top: 10px; padding-top: 8px; border-top: 1px dashed #000;">
                    <p style="font-weight: bold; margin-bottom: 3px;">ملاحظات:</p>
                    <p style="color: #666;">{{ $order->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Order Method Details -->
        @if ($order->orderMethod)
            <div
                style="margin-top: 10px; padding-top: 8px; border-top: 1px dashed #000; font-size: 11px; direction: rtl;">
                <h4 style="font-weight: bold; margin-bottom: 5px; text-align: center;">تفاصيل الاستلام</h4>

                @if ($order->order_method_id == \Modules\Order\Entities\OrderMethod::RECEIPT_IN_HOME)
                    <!-- Home Delivery Address -->
                    @if ($order->address)
                        <p
                            style="display: flex; justify-content: space-between; margin: 3px 0; direction: rtl; width: 100%;">
                            <span style="font-weight: bold;">العنوان:</span>
                            <span style="color: #666;">{{ $order->address }}</span>
                        </p>
                    @endif
                @elseif ($order->order_method_id == \Modules\Order\Entities\OrderMethod::RECEIPT_IN_CAR)
                    <!-- Car Details -->
                    @if ($order->car_no)
                        <p
                            style="display: flex; justify-content: space-between; margin: 3px 0; direction: rtl; width: 100%;">
                            <span style="font-weight: bold;">رقم السيارة:</span>
                            <span style="color: #666;">{{ $order->car_no }}</span>
                        </p>
                    @endif
                    @if ($order->car_color)
                        <p
                            style="display: flex; justify-content: space-between; margin: 3px 0; direction: rtl; width: 100%;">
                            <span style="font-weight: bold;">لون السيارة:</span>
                            <span style="color: #666;">{{ $order->car_color }}</span>
                        </p>
                    @endif
                    @if ($order->parking_no)
                        <p
                            style="display: flex; justify-content: space-between; margin: 3px 0; direction: rtl; width: 100%;">
                            <span style="font-weight: bold;">رقم الموقف:</span>
                            <span style="color: #666;">{{ $order->parking_no }}</span>
                        </p>
                    @endif
                @endif
            </div>
        @endif



        <!-- Footer -->
        <div class="receipt-footer">
            @if (!empty($settings['name']))
                <p class="thank-you">شكراً لطلبكم من {{ $settings['name'] }}</p>
            @endif
            <p>نتمنى لكم تجربة ممتعة</p>
        </div>

        {{-- Zatca QR Code --}}
        @if ($order->qr_code)
            <div class="zatca-qr-code text-center">
                {!! $order->qr_code !!}
            </div>
        @endif
    </div>

    <script>
        window.onload = function() {
            window.print();
            setTimeout(function() {
                window.close();
            }, 100);
        };
    </script>
</body>

</html>
