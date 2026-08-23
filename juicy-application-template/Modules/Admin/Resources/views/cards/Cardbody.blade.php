<div class="col-md-6 col-xl-3">
    <div class="card bg-{{ $value['ButtonColor'] }} text-white">

        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4 class="card-title mb-0 text-white">{{ $value['order_no'] }}</h4>
                @if ($value['payment_method_id'] == 2)
                    <div>
                        <svg width="60" height="38" viewBox="0 0 192.756 192.756" xmlns="http://www.w3.org/2000/svg">
                            <g fill-rule="evenodd" clip-rule="evenodd">
                                <path
                                    d="M189.922 50.809c0-8.986-4.67-13.444-13.729-13.444H16.562c-4.528 0-7.854 1.203-10.048 3.679-2.476 2.477-3.68 5.661-3.68 9.765v91.138c0 4.104 1.204 7.217 3.68 9.764 2.548 2.477 5.803 3.68 10.048 3.68h159.631c9.059 0 13.729-4.527 13.729-13.443V50.809zm-13.729-11.321c7.5 0 11.322 3.821 11.322 11.321v91.138c0 7.57-3.822 11.32-11.322 11.32H16.562c-3.609 0-6.368-1.061-8.42-3.184-2.123-2.053-3.184-4.883-3.184-8.137V50.809c0-7.5 3.75-11.321 11.604-11.321h159.631z"
                                    fill="#315881"></path>
                                <path
                                    d="M17.835 44.724c-3.042 0-4.953.495-6.014 1.557-.92 1.203-1.344 3.184-1.344 6.085v19.741h171.802V52.366c0-5.165-2.549-7.642-7.643-7.642H17.835z"
                                    fill="#315881"></path>
                                <path
                                    d="M10.477 140.107c0 5.234 2.476 7.924 7.358 7.924h156.801c5.094 0 7.643-2.689 7.643-7.924v-19.742H10.477v19.742z"
                                    fill="#dfa43b"></path>
                                <path
                                    d="M67.367 80.528c0 .92-.142 1.627-.495 2.123l-12.383 21.582-.779-26.323H33.898l6.651 3.184c1.91 1.203 2.901 2.759 2.901 4.741l1.839 27.951h9.694l23.21-35.876H66.306c.707.637 1.061 1.627 1.061 2.618zM147.467 78.971l.777-1.062h-12.1c.424.424.566.637.566.778-.143.565-.426.92-.566 1.344l-17.619 32.124c-.424.566-.85 1.062-1.344 1.629h9.977l-.496-1.062c0-.92.496-2.617 1.557-5.023l2.123-3.963h10.26c.426 3.326.709 6.086.85 8.139l-.85 1.91h12.383l-1.84-2.689-3.678-32.125zm-7.36 19.742h-7.359l6.297-12.1 1.062 12.1zM109.539 76.07c-3.82 0-7.076 1.062-9.977 3.184-3.185 1.84-4.741 4.175-4.741 7.077 0 3.326 1.132 6.227 3.396 8.42l6.865 4.74c2.477 1.77 3.68 3.326 3.68 4.742 0 1.344-.639 2.547-1.84 3.467-1.203.92-2.549 1.344-4.246 1.344-2.477 0-6.722-1.768-12.595-5.023v6.58c4.599 2.76 9.058 4.176 13.373 4.176 4.105 0 7.572-1.133 10.545-3.68 3.184-2.336 4.74-5.094 4.74-8.137 0-2.549-1.133-4.883-3.68-7.36l-6.582-4.741c-2.191-1.769-3.395-3.326-3.395-4.528 0-2.759 1.627-4.175 4.953-4.175 2.264 0 5.59 1.274 10.047 3.963l1.346-6.864c-3.752-2.124-7.643-3.185-11.889-3.185zM83.217 113.785c-.142-1.486-.425-2.83-.567-4.246l8.987-29.011 2.123-2.618H80.811c.142.637.283 1.486.425 2.123 0 .637 0 1.416-.142 2.123l-8.986 28.728-1.84 2.902h12.949v-.001z"
                                    fill="#315881"></path>
                            </g>
                        </svg>
                    </div>
                @endif
                <span>{{ $value['delivery_time_order'] }}</span>
            </div>

            <!-- Invoice Details -->
            <div class="invoice-details"
                style="background: rgba(255,255,255,0.1); padding: 12px; border-radius: 6px; margin-bottom: 10px;">
                <!-- Order Info -->
                <div class="mb-2" style="font-size: 13px;">
                    <div class="d-flex justify-content-between mb-1">
                        <span>طريقة الدفع:</span>
                        <strong>{{ $value['payment_method_title'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>طريقة الطلب:</span>
                        <strong>{{ $value['order_method_title'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>رقم الهاتف:</span>
                        <strong>{{ $value['client_phone'] }}</strong>
                    </div>

                    @php
                        $addressEn = $value['address_en'] ?? ($value['address']['en'] ?? null);
                        $addressAr = $value['address_ar'] ?? ($value['address']['ar'] ?? null);
                    @endphp

                    {{-- Receipt from branch: show table number --}}
                    @if (
                        $value['order_method_id'] === \Modules\Order\Entities\OrderMethod::RECEIPT_FROM_BRANCH &&
                            !empty($value['table_no']))
                        <div class="d-flex justify-content-between mt-1">
                            <span>رقم الطاولة:</span>
                            <strong>{{ $value['table_no'] }}</strong>
                        </div>
                    @endif

                    {{-- Receipt in car: show car details --}}
                    @if ($value['order_method_id'] === \Modules\Order\Entities\OrderMethod::RECEIPT_IN_CAR)
                        @if (!empty($value['car_no']))
                            <div class="d-flex justify-content-between mt-1">
                                <span>رقم السيارة:</span>
                                <strong>{{ $value['car_no'] }}</strong>
                            </div>
                        @endif
                        @if (!empty($value['car_color']))
                            <div class="d-flex justify-content-between mt-1">
                                <span>لون السيارة:</span>
                                <strong>{{ $value['car_color'] }}</strong>
                            </div>
                        @endif
                        @if (!empty($value['parking_no']))
                            <div class="d-flex justify-content-between mt-1">
                                <span>رقم الموقف:</span>
                                <strong>{{ $value['parking_no'] }}</strong>
                            </div>
                        @endif
                    @endif

                    {{-- Receipt in home: show address (EN/AR) --}}
                    @if ($value['order_method_id'] === \Modules\Order\Entities\OrderMethod::RECEIPT_IN_HOME)
                        @if (!empty($addressAr))
                            <div class="d-flex justify-content-between mt-1">
                                <span>العنوان (عربي):</span>
                                <strong>{{ $addressAr }}</strong>
                            </div>
                        @endif
                        @if (!empty($addressEn))
                            <div class="d-flex justify-content-between mt-1">
                                <span>العنوان (انجليزي):</span>
                                <strong>{{ $addressEn }}</strong>
                            </div>
                        @endif
                    @endif
                </div>

                <hr style="border-color: rgba(255,255,255,0.3); margin: 10px 0;">

                           <!-- Products -->
                @foreach ($value['OrderDetails'] as $detail)
                    <div class="product-item mb-2">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div style="flex: 1;">
                                <strong>{{ $detail['title'] }}</strong>
                                <span class="badge bg-light text-dark ms-1">x{{ $detail['quantity'] }}</span>
                                @php
                                    $sideLabels = [];
                                    foreach ($detail['sides'] ?? [] as $side) {
                                        foreach ($side['values'] ?? [] as $sideValue) {
                                            $sideLabels[] = $sideValue['title'] ?? '';
                                        }
                                    }
                                @endphp
                                @if (!empty($sideLabels))
                                    <small style="display: block; margin-top: 3px; color: #ffffff;">
                                        {{ implode(' - ', $sideLabels) }}
                                    </small>
                                @endif
                            </div>
                            <strong>{{ number_format($detail['total'], 2) }}</strong>
                        </div>

                        <!-- Attributes -->
                        @if (!empty($detail['attributes']))
                            @foreach ($detail['attributes'] as $attribute)
                                <div style="padding-right: 10px; margin-top: 5px; opacity: 0.95;">
                                    <div style="font-size: 11px; font-weight: 600; margin-bottom: 3px;">
                                        {{ $attribute['title'] }}:
                                    </div>
                                    @foreach ($attribute['values'] as $attr_value)
                                        <div class="d-flex justify-content-between"
                                            style="padding-right: 15px; font-size: 10px; margin-bottom: 2px;">
                                            <span>• {{ $attr_value['title'] }}</span>
                                            @if (isset($attribute['override_price']) && $attribute['override_price'] == 1)
                                                <span>{{ number_format($attr_value['price'], 2) }}</span>
                                            @else
                                                <span>+{{ number_format($attr_value['price'], 2) }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        @endif

                        <!-- Addons -->
                        @if (!empty($detail['addons']))
                            @foreach ($detail['addons'] as $addon)
                                <div style="padding-right: 10px; margin-top: 5px; opacity: 0.95;">
                                    <div style="font-size: 11px; font-weight: 600; margin-bottom: 3px;">
                                        {{ $addon['title'] }}:
                                    </div>
                                    @foreach ($addon['values'] as $addon_value)
                                        <div class="d-flex justify-content-between"
                                            style="padding-right: 15px; font-size: 10px; margin-bottom: 2px;">
                                            <span>• {{ $addon_value['title'] }}</span>
                                            <span>+{{ number_format($addon_value['price'], 2) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        @endif

                        @if ($detail['note'])
                            <div
                                style="padding-right: 10px; margin-top: 5px; font-size: 11px; font-style: italic; opacity: 0.85;">
                                <strong>ملاحظة:</strong> {{ $detail['note'] }}
                            </div>
                        @endif

                        @if (!$loop->last)
                            <hr style="border-color: rgba(255,255,255,0.2); margin: 8px 0;">
                        @endif
                    </div>
                @endforeach

                <hr style="border-color: rgba(255,255,255,0.3); margin: 10px 0;">

                <!-- Totals -->
                <div style="font-size: 12px;">
                    <div class="d-flex justify-content-between mb-1">
                        <span>المجموع الفرعي:</span>
                        <span>{{ $value['subtotal'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>الضريبة:</span>
                        <span>{{ $value['tax'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>الخصم:</span>
                        <span>{{ $value['discount'] }}</span>
                    </div>
                    <hr style="border-color: rgba(255,255,255,0.5); margin: 8px 0;">
                    <div class="d-flex justify-content-between">
                        <strong style="font-size: 14px;">الإجمالي:</strong>
                        <strong style="font-size: 14px;">{{ number_format($value['total'], 2) }}</strong>
                    </div>
                </div>

                @if ($value['notes'])
                    <hr style="border-color: rgba(255,255,255,0.3); margin: 10px 0;">
                    <div style="font-size: 11px; font-style: italic;">
                        <strong>ملاحظات الطلب:</strong> {{ $value['notes'] }}
                    </div>
                @endif
            </div>

            <div class="text-center" style="font-size: 12px; opacity: 0.9;">
                <span class="badge" style="background: rgba(255,255,255,0.2); padding: 5px 10px;">
                    {{ $value['OrderStatusVal'] }}
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 px-2 pb-2">
                <button type="button" class="btn w-100 font-weight-bold" style="background-color: rgba(255,255,255,0.2); color: white;" data-bs-toggle="modal"
                    data-bs-target="#actionsModal{{ $value['id'] }}">
                    إجراءات
                </button>
            </div>
        </div>
    </div>

    <!-- Actions Modal -->
    <div class="modal fade" id="actionsModal{{ $value['id'] }}" tabindex="-1"
        aria-labelledby="actionsModalLabel{{ $value['id'] }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content text-dark">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="actionsModalLabel{{ $value['id'] }}">إجراءات الطلب #{{ $value['order_no'] }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-2">
                    <!-- Quick Status Update Button -->
                    @if ($value['order_status_id'] < 5 && $value['order_status_id'] != 6 && $value['order_status_id'] != 7)
                        @php
                            $nextStatusId = $value['order_status_id'] + 1;
                            if ($value['order_status_id'] == \Modules\Order\Entities\OrderStatus::ORDER_READY) {
                                if ($value['order_method_id'] === \Modules\Order\Entities\OrderMethod::RECEIPT_IN_HOME) {
                                    $nextStatusId = \Modules\Order\Entities\OrderStatus::ORDER_IN_DELIVERY;
                                } else {
                                    $nextStatusId = \Modules\Order\Entities\OrderStatus::DONE;
                                }
                            }
                            $nextStatus = \Modules\Order\Entities\OrderStatus::find($nextStatusId);
                        @endphp
                        @if ($nextStatus)
                            <form action="{{ route('admin.updateCardStatus') }}" method="POST" class="mb-2">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $value['id'] }}">
                                <input type="hidden" name="order_status_id" value="{{ $value['order_status_id'] }}">
                                <input type="hidden" name="order_method_id" value="{{ $value['order_method_id'] }}">
                                <button type="submit"
                                    style="color:white; background-color:#099347 !important; border-color:#099347 !important;"
                                    class="btn w-100">
                                    <span style="margin-left: 5px">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="9 11 12 14 22 4"></polyline>
                                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                        </svg>
                                    </span>
                                    {{ $nextStatus->title }}
                                </button>
                            </form>
                        @endif
                    @endif

                    <button type="button" class="btn btn-outline-primary w-100 mb-2" 
                        onclick="$('#actionsModal{{ $value['id'] }}').modal('hide'); setTimeout(() => $('#recentOrderModal{{ $value['id'] }}').modal('show'), 300);">
                        <span style="margin-left: 5px">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </span>
                        تفاصيل
                    </button>

                    <button type="button" class="btn btn-outline-secondary w-100 mb-2"
                        onclick="window.open('{{ route('orders.print', $value['id']) }}', '_blank', 'width=800,height=600')">
                        <span style="margin-left: 5px">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer">
                                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                                <rect x="6" y="14" width="12" height="8"></rect>
                            </svg>
                        </span>
                        طباعة
                    </button>

                    <hr class="m-0 mb-2" />

                    <button type="button" class="btn btn-outline-danger w-100"
                        onclick="$('#actionsModal{{ $value['id'] }}').modal('hide'); setTimeout(() => $('#rejectOrderModal{{ $value['id'] }}').modal('show'), 300);">
                        رفض الطلب
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Order Modal -->
    <div class="modal fade" id="rejectOrderModal{{ $value['id'] }}" tabindex="-1"
        aria-labelledby="rejectOrderLabel{{ $value['id'] }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered text-dark">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectOrderLabel{{ $value['id'] }}">تأكيد رفض الطلب</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.updateCardStatus') }}" method="POST">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $value['id'] }}">
                    <input type="hidden" name="reject" value="1">
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="submit" class="btn btn-danger" onclick="setTimeout(() => { $('.modal-backdrop').remove(); $('body').removeClass('modal-open'); }, 200);">تأكيد الرفض</button>
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
