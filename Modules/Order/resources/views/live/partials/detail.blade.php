@use('Modules\Order\Enums\OrderStatus')
@use('Modules\Order\Enums\PaymentMethod')
@use('Illuminate\Support\Str')

@php
    $currency        = config('currency.symbols.' . app()->getLocale(), 'EGP');
    $visitedStatuses = $order->histories->pluck('order_status_id')->toArray();
    $currentFlowPos  = [
        OrderStatus::Sent->value                 => 1,
        OrderStatus::AcceptedAndPreparing->value => 2,
        OrderStatus::DeliverToDriver->value      => 3,
        OrderStatus::OnTheWay->value             => 4,
        OrderStatus::Done->value                 => 5,
    ][$order->order_status_id] ?? 0;
@endphp

{{-- Sticky Floating Mobile Back Button (< 992px) --}}
<div class="position-fixed bottom-0 start-50 translate-middle-x mb-2 d-lg-none sticky-mobile-back-btn">
    <button type="button" 
            class="btn btn-danger btn-lg shadow-lg rounded-pill px-3 py-1 d-flex align-items-center justify-content-center gap-1 text-nowrap fw-bolder" 
            onclick="showMobileOrdersList()">
        <i data-feather="{{ app()->getLocale() === 'ar' ? 'arrow-right' : 'arrow-left' }}"></i>
        <span>{{ __('order::general.back_to_orders') }}</span>
    </button>
</div>

{{-- Order Header Card --}}
<div class="card shadow-sm border-0 mb-0 order-workspace-card d-flex flex-column">
    <div class="card-body p-2 pb-5 pb-lg-2">

        {{-- Header: order # + status + payment + date (no store) --}}
        <div class="d-flex justify-content-between align-items-start gap-2 mb-2 pb-1 border-bottom">
            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                    <h3 class="fw-bolder mb-0 text-primary text-nowrap" dir="ltr">{{ $order->order_no }}</h3>
                    <span class="badge badge-light-{{ $order->status_color }} fs-6">
                        {{ $order->orderStatus?->title ?? 'Status ' . $order->order_status_id }}
                    </span>
                    @if($order->paymentMethod)
                        @if((int) $order->payment_method_id === PaymentMethod::CashOnDelivery->value)
                            <i data-feather="dollar-sign" class="font-medium-3 text-success flex-shrink-0"
                               title="{{ $order->paymentMethod->title }}"
                               data-bs-toggle="tooltip"></i>
                        @else
                            <i data-feather="credit-card" class="font-medium-3 text-warning flex-shrink-0"
                               title="{{ $order->paymentMethod->title }}"
                               data-bs-toggle="tooltip"></i>
                        @endif
                    @endif
                </div>
                {{-- Date row: creation + scheduled delivery (if set) --}}
                <div class="d-flex flex-wrap align-items-center gap-3 mt-1">
                    <div class="d-flex align-items-center gap-1 text-dark fw-semibold fs-6">
                        <i data-feather="calendar" class="font-small-3 text-secondary flex-shrink-0"></i>
                        <span dir="ltr">{{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : '' }}</span>
                    </div>
                    @if($order->delivery_date)
                        @php
                            $deliveryFormatted = \Carbon\Carbon::parse($order->delivery_date)->format('d M Y');
                            if ($order->delivery_time_from) {
                                $fromTime = \Carbon\Carbon::parse($order->delivery_time_from)->format('h:i A');
                                if ($order->delivery_time_to) {
                                    $toTime = \Carbon\Carbon::parse($order->delivery_time_to)->format('h:i A');
                                    $deliveryFormatted .= ", {$fromTime} - {$toTime}";
                                } else {
                                    $deliveryFormatted .= ", {$fromTime}";
                                }
                            }
                        @endphp
                        <div class="d-flex align-items-center gap-1 text-dark fw-semibold fs-6">
                            <i data-feather="truck" class="font-small-3 text-secondary flex-shrink-0"></i>
                            <span dir="ltr">{{ $deliveryFormatted }}</span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="flex-shrink-0 ms-2">
                <button type="button"
                        class="btn btn-sm btn-icon btn-danger btn-close-detail text-white shadow-sm"
                        onclick="closeOrderDetail()"
                        title="{{ __('order::general.close') }}">
                    <i data-feather="x"></i>
                </button>
            </div>
        </div>

        {{-- Dual Status Action Bar --}}
        <div class="card bg-light-primary border border-primary p-1 mb-2">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                {{-- Next action btn: normal size (no btn-lg) --}}
                <div class="d-flex align-items-center gap-2">
                    @php $nextAction = isset($viewModel) ? $viewModel->nextAction($order) : null; @endphp
                    @if($nextAction)
                        <button class="btn btn-{{ $nextAction['color'] }} d-flex align-items-center gap-1 shadow"
                                onclick="submitStatusUpdate({{ $order->id }}, {{ $nextAction['id'] }})">
                            <i data-feather="{{ $nextAction['icon'] }}"></i>
                            <span class="fw-bold">{{ $nextAction['title'] }}</span>
                        </button>
                    @else
                        <span class="badge badge-light-secondary fs-6 py-1 px-2">{{ __('order::general.live.completed') }}</span>
                    @endif
                </div>

                {{-- Direct status: normal-size select, primary apply btn --}}
                <div class="d-flex align-items-center gap-1 flex-wrap">
                    <span class="font-small-2 text-dark fw-bold d-none d-sm-inline">{{ __('order::general.direct_jump') }}:</span>
                    <select class="form-select w-auto" id="direct-status-select-{{ $order->id }}">
                        <option value="" disabled selected>{{ __('order::general.jump_to') }}</option>
                        <option value="{{ OrderStatus::Sent->value }}"                 {{ $order->order_status_id == OrderStatus::Sent->value                 ? 'selected' : '' }}>{{ OrderStatus::Sent->label() }}</option>
                        <option value="{{ OrderStatus::AcceptedAndPreparing->value }}" {{ $order->order_status_id == OrderStatus::AcceptedAndPreparing->value ? 'selected' : '' }}>{{ OrderStatus::AcceptedAndPreparing->label() }}</option>
                        <option value="{{ OrderStatus::DeliverToDriver->value }}"      {{ $order->order_status_id == OrderStatus::DeliverToDriver->value      ? 'selected' : '' }}>{{ OrderStatus::DeliverToDriver->label() }}</option>
                        <option value="{{ OrderStatus::OnTheWay->value }}"             {{ $order->order_status_id == OrderStatus::OnTheWay->value             ? 'selected' : '' }}>{{ OrderStatus::OnTheWay->label() }}</option>
                        <option value="{{ OrderStatus::Done->value }}"                 {{ $order->order_status_id == OrderStatus::Done->value                 ? 'selected' : '' }} class="fw-bold text-success">{{ OrderStatus::Done->label() }}</option>
                        <option value="{{ OrderStatus::RefusedByDriver->value }}"      {{ $order->order_status_id == OrderStatus::RefusedByDriver->value      ? 'selected' : '' }}>{{ OrderStatus::RefusedByDriver->label() }}</option>
                        <option value="{{ OrderStatus::Fail->value }}"                 {{ $order->order_status_id == OrderStatus::Fail->value                 ? 'selected' : '' }}>{{ OrderStatus::Fail->label() }}</option>
                        <option value="{{ OrderStatus::Cancelled->value }}"            {{ $order->order_status_id == OrderStatus::Cancelled->value            ? 'selected' : '' }} class="text-danger">{{ OrderStatus::Cancelled->label() }}</option>
                    </select>
                    <button class="btn btn-primary" onclick="submitDirectStatusUpdate({{ $order->id }})">
                        {{ __('order::general.apply') }}
                    </button>
                </div>
            </div>
        </div>

        {{-- Visual Progress Stepper (History-based) --}}
        <div class="mb-2 px-1">
            <div class="d-flex justify-content-between align-items-center">

                @php
                    $s1 = OrderStatus::Sent->value;
                    $isStep1Completed = in_array($s1, $visitedStatuses) || $currentFlowPos > 1;
                    $step1Class = $order->order_status_id == $s1 ? 'active' : ($isStep1Completed ? 'completed' : '');
                @endphp
                <div class="stepper-item position-relative text-center flex-fill {{ $step1Class }}">
                    <div class="stepper-circle d-inline-flex align-items-center justify-content-center rounded-circle fw-bold position-relative font-small-2">
                        @if($isStep1Completed)
                            <i data-feather="check" class="font-small-1"></i>
                        @else
                            1
                        @endif
                    </div>
                    <div class="font-small-1 fw-bold mt-50 lh-1">{{ OrderStatus::Sent->label() }}</div>
                </div>

                @php
                    $s2 = OrderStatus::AcceptedAndPreparing->value;
                    $isStep2Completed = in_array($s2, $visitedStatuses) || $currentFlowPos > 2;
                    $step2Class = $order->order_status_id == $s2 ? 'active' : ($isStep2Completed ? 'completed' : '');
                @endphp
                <div class="stepper-item position-relative text-center flex-fill {{ $step2Class }}">
                    <div class="stepper-circle d-inline-flex align-items-center justify-content-center rounded-circle fw-bold position-relative font-small-2">
                        @if($isStep2Completed)
                            <i data-feather="check" class="font-small-1"></i>
                        @else
                            2
                        @endif
                    </div>
                    <div class="font-small-1 fw-bold mt-50 lh-1 {{ $order->order_status_id == $s2 ? 'text-primary' : '' }}">{{ OrderStatus::AcceptedAndPreparing->label() }}</div>
                </div>

                @php
                    $s3 = OrderStatus::DeliverToDriver->value;
                    $isRefused = $order->order_status_id == OrderStatus::RefusedByDriver->value;
                    $isStep3Completed = in_array($s3, $visitedStatuses) || $currentFlowPos > 3;
                    $step3Class = $order->order_status_id == $s3 ? 'active'
                        : ($isRefused ? 'active text-danger'
                        : ($isStep3Completed ? 'completed' : ''));
                @endphp
                <div class="stepper-item position-relative text-center flex-fill {{ $step3Class }}">
                    <div class="stepper-circle d-inline-flex align-items-center justify-content-center rounded-circle fw-bold position-relative font-small-2">
                        @if($isStep3Completed)
                            <i data-feather="check" class="font-small-1"></i>
                        @else
                            3
                        @endif
                    </div>
                    <div class="font-small-1 fw-semibold mt-50 lh-1">{{ OrderStatus::DeliverToDriver->label() }}</div>
                </div>

                @php
                    $s4 = OrderStatus::OnTheWay->value;
                    $isStep4Completed = in_array($s4, $visitedStatuses) || $currentFlowPos > 4;
                    $step4Class = $order->order_status_id == $s4 ? 'active' : ($isStep4Completed ? 'completed' : '');
                @endphp
                <div class="stepper-item position-relative text-center flex-fill {{ $step4Class }}">
                    <div class="stepper-circle d-inline-flex align-items-center justify-content-center rounded-circle fw-bold position-relative font-small-2">
                        @if($isStep4Completed)
                            <i data-feather="check" class="font-small-1"></i>
                        @else
                            4
                        @endif
                    </div>
                    <div class="font-small-1 fw-semibold mt-50 lh-1">{{ OrderStatus::OnTheWay->label() }}</div>
                </div>

                @php
                    $s5 = OrderStatus::Done->value;
                    $isStep5Completed = in_array($s5, $visitedStatuses) && $order->order_status_id != $s5;
                    $step5Class = $order->order_status_id == $s5 ? 'active' : ($isStep5Completed ? 'completed' : '');
                @endphp
                <div class="stepper-item position-relative text-center flex-fill {{ $step5Class }}">
                    <div class="stepper-circle d-inline-flex align-items-center justify-content-center rounded-circle fw-bold position-relative font-small-2">
                        @if($isStep5Completed)
                            <i data-feather="check" class="font-small-1"></i>
                        @else
                            5
                        @endif
                    </div>
                    <div class="font-small-1 fw-semibold mt-50 lh-1">{{ OrderStatus::Done->label() }}</div>
                </div>

            </div>
        </div>

        {{-- Driver Assignment Section --}}
        <div class="card border border-info bg-light-info mb-2 {{ in_array($order->order_status_id, [OrderStatus::DeliverToDriver->value, OrderStatus::RefusedByDriver->value]) ? '' : 'd-none' }}" id="driver-assign-box">
            <div class="card-body p-2">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div class="d-flex align-items-center gap-1">
                        <i data-feather="truck" class="text-info fs-3"></i>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">{{ __('order::general.assign_driver') }}</h6>
                            <small class="text-muted">{{ __('order::general.select_driver') }}</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-1 flex-grow-1 col-md-5">
                        <select class="form-select form-select-sm" id="driver-select-{{ $order->id }}">
                            <option value="">{{ __('order::general.select_driver') }}</option>
                            @if(isset($viewModel))
                                @foreach($viewModel->drivers() as $driver)
                                    <option value="{{ $driver->id }}" {{ $order->driver_id == $driver->id ? 'selected' : '' }}>
                                        {{ $driver->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <button class="btn btn-sm btn-success text-nowrap d-flex align-items-center gap-1"
                                onclick="submitDriverDispatch({{ $order->id }})">
                            <i data-feather="send"></i>
                            <span>{{ __('order::general.dispatch') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3 Compact Info Cards: Client / Store-Clinic / Driver --}}
        <div class="row g-2 mb-2">

            {{-- Client Card --}}
            @php $hasStore = (bool)($order->store?->title ?? $order->clinic?->title); $hasDriver = (bool)$order->driver; @endphp
            @php $colSize = ($hasStore && $hasDriver) ? 'col-12 col-md-4' : (($hasStore || $hasDriver) ? 'col-12 col-md-6' : 'col-12'); @endphp
            <div class="{{ $colSize }}">
                <div class="card border-start border-4 border-primary h-100 rounded mb-0">
                    <div class="card-header py-1 px-2 bg-light-primary text-center">
                        <small class="fw-bold text-primary d-inline-flex align-items-center gap-1">
                            <i data-feather="user" class="font-small-2"></i>
                            {{ __('client::attribute.client_id') }}
                        </small>
                    </div>
                    <div class="card-body p-1 px-2 d-flex flex-column align-items-center text-center">
                        <div class="avatar bg-light-primary rounded mb-1 flex-shrink-0">
                            @if($order->client?->image && file_exists(public_path('storage/' . $order->client->image)))
                                <img src="{{ asset('storage/' . $order->client->image) }}" class="rounded" width="40" height="40" alt="">
                            @else
                                <div class="avatar-content rounded">
                                    <i data-feather="user" class="avatar-icon text-primary"></i>
                                </div>
                            @endif
                        </div>
                        <div class="w-100 font-small-2">
                            @php
                                $clientName = $order->client?->name ?? __('order::general.guest_client');
                                $isClientNameLong = mb_strlen($clientName) > 18;
                            @endphp
                            <div class="fw-bold text-dark font-small-3 text-truncate"
                                 @if($isClientNameLong)
                                     data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $clientName }}"
                                 @endif>
                                {{ Str::limit($clientName, 18) }}
                            </div>
                            @if($order->client?->phone)
                                <div>
                                    <a href="tel:{{ $order->client->phone }}" class="text-primary d-inline-flex align-items-center gap-1 font-small-2">
                                        <i data-feather="phone" class="font-small-1"></i>{{ $order->client->phone }}
                                    </a>
                                </div>
                            @endif
                            @if($order->address?->address)
                                @php
                                    $clientAddr = $order->address->address;
                                    $isClientAddrLong = mb_strlen($clientAddr) > 22;
                                @endphp
                                <div class="text-muted text-truncate font-small-2"
                                     @if($isClientAddrLong)
                                         data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $clientAddr }}"
                                     @endif>
                                    {{ Str::limit($clientAddr, 22) }}
                                </div>
                            @endif
                            @if($order->notes)
                                @php $isNotesLong = mb_strlen($order->notes) > 22; @endphp
                                <div class="fst-italic text-secondary text-truncate font-small-1"
                                     @if($isNotesLong)
                                         data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $order->notes }}"
                                     @endif>
                                    {{ Str::limit($order->notes, 22) }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Store / Clinic Card --}}
            @if($hasStore)
                <div class="{{ $hasDriver ? 'col-12 col-md-4' : 'col-12 col-md-6' }}">
                    <div class="card border-start border-4 border-warning h-100 rounded mb-0">
                        <div class="card-header py-1 px-2 bg-light-warning text-center">
                            <small class="fw-bold text-warning d-inline-flex align-items-center gap-1">
                                <i data-feather="home" class="font-small-2"></i>
                                {{ $order->store ? __('driver::attribute.store') : __('driver::attribute.clinic') }}
                            </small>
                        </div>
                        <div class="card-body p-1 px-2 d-flex flex-column align-items-center text-center">
                            @php $storeImg = $order->store?->image ?? $order->clinic?->image; @endphp
                            <div class="avatar bg-light-warning rounded mb-1 flex-shrink-0">
                                @if($storeImg && file_exists(public_path('storage/' . $storeImg)))
                                    <img src="{{ asset('storage/' . $storeImg) }}" class="rounded" width="40" height="40" alt="">
                                @else
                                    <div class="avatar-content rounded">
                                        <i data-feather="home" class="avatar-icon text-warning"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="w-100 font-small-2">
                                @php
                                    $storeTitle = $order->store?->title ?? $order->clinic?->title ?? '';
                                    $isStoreTitleLong = mb_strlen($storeTitle) > 18;
                                @endphp
                                <div class="fw-bold text-dark font-small-3 text-truncate"
                                     @if($isStoreTitleLong)
                                         data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $storeTitle }}"
                                     @endif>
                                    {{ Str::limit($storeTitle, 18) }}
                                </div>
                                @php
                                    $storeAddr = $order->store?->address ?? $order->clinic?->address;
                                    $isStoreAddrLong = $storeAddr && mb_strlen($storeAddr) > 22;
                                @endphp
                                @if($storeAddr)
                                    <div class="text-muted text-truncate font-small-2"
                                         @if($isStoreAddrLong)
                                             data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $storeAddr }}"
                                         @endif>
                                        {{ Str::limit($storeAddr, 22) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Driver Card --}}
            @if($hasDriver)
                <div class="{{ $hasStore ? 'col-12 col-md-4' : 'col-12 col-md-6' }}">
                    <div class="card border-start border-4 border-success h-100 rounded mb-0">
                        <div class="card-header py-1 px-2 bg-light-success text-center">
                            <small class="fw-bold text-success d-inline-flex align-items-center gap-1">
                                <i data-feather="truck" class="font-small-2"></i>
                                {{ __('driver::general.driver') }}
                            </small>
                        </div>
                        <div class="card-body p-1 px-2 d-flex flex-column align-items-center text-center">
                            <div class="avatar bg-light-success rounded mb-1 flex-shrink-0">
                                @if($order->driver->image && file_exists(public_path('storage/' . $order->driver->image)))
                                    <img src="{{ asset('storage/' . $order->driver->image) }}" class="rounded" width="40" height="40" alt="">
                                @else
                                    <div class="avatar-content rounded">
                                        <i data-feather="truck" class="avatar-icon text-success"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="w-100 font-small-2">
                                @php
                                    $driverName = $order->driver->name;
                                    $isDriverNameLong = mb_strlen($driverName) > 18;
                                @endphp
                                <div class="fw-bold text-dark font-small-3 text-truncate"
                                     @if($isDriverNameLong)
                                         data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $driverName }}"
                                     @endif>
                                    {{ Str::limit($driverName, 18) }}
                                </div>
                                @if($order->driver->phone)
                                    <div>
                                        <a href="tel:{{ $order->driver->phone }}" class="text-primary d-inline-flex align-items-center gap-1 font-small-2">
                                            <i data-feather="phone" class="font-small-1"></i>{{ $order->driver->phone }}
                                        </a>
                                    </div>
                                @endif
                                @if($order->driver->license_id)
                                    <div class="text-muted font-small-2 text-truncate">{{ __('driver::attribute.license_id') }}: {{ $order->driver->license_id }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        {{-- Full-Width: Order Items Table --}}
        <div class="card border mb-2">
            <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-1">
                    <i data-feather="shopping-bag" class="font-small-3"></i>
                    {{ __('order::general.order_items') }} ({{ $order->details?->count() ?? 0 }})
                </h6>
                <span class="badge badge-light-primary fs-6">
                    {{ __('order::general.total') }}: {{ number_format((float) $order->total, 2) }} {{ $currency }}
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0 font-small-2">
                        <thead class="table-light">
                            <tr>
                                <th class="py-1 px-2">{{ __('order::general.item') }}</th>
                                <th class="text-center py-1 text-nowrap">{{ __('order::general.quantity') }}</th>
                                <th class="text-end py-1 text-nowrap">{{ __('order::general.price') }}</th>
                                <th class="text-end py-1 px-2 text-nowrap">{{ __('order::general.total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->details ?? [] as $detail)
                                <tr>
                                    <td class="py-1 px-2">
                                        <span class="fw-bold d-block text-dark">{{ $detail->product?->title ?? 'Product' }}</span>
                                        @if($detail->note)
                                            <small class="text-muted">{{ $detail->note }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center py-1">
                                        <span class="badge badge-light-primary">{{ $detail->quantity }}</span>
                                    </td>
                                    <td class="text-end py-1">{{ number_format((float) $detail->price, 2) }} {{ $currency }}</td>
                                    <td class="text-end py-1 px-2 fw-bold text-dark">{{ number_format((float) ($detail->price * $detail->quantity), 2) }} {{ $currency }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-2">{{ __('order::general.no_items') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Price Breakdown (compact) --}}
                <div class="px-2 py-1 bg-light border-top font-small-2">
                    <div class="d-flex justify-content-end">
                        <div class="d-flex flex-column gap-1 col-12 col-sm-6 col-md-5 col-lg-4">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">{{ __('order::general.subtotal') }}</span>
                                <span class="fw-semibold">{{ number_format((float) $order->subtotal, 2) }} {{ $currency }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">{{ __('order::general.delivery_fee') }}</span>
                                <span class="fw-semibold">{{ number_format((float) $order->delivery_fee, 2) }} {{ $currency }}</span>
                            </div>
                            @if($order->tax > 0)
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">{{ __('order::general.tax') }}</span>
                                    <span class="fw-semibold">{{ number_format((float) $order->tax, 2) }} {{ $currency }}</span>
                                </div>
                            @endif
                            @if($order->discount > 0)
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">{{ __('order::general.discount') }}</span>
                                    <span class="text-success fw-semibold">-{{ number_format((float) $order->discount, 2) }} {{ $currency }}</span>
                                </div>
                            @endif
                            <div class="d-flex justify-content-between pt-1 border-top mt-1">
                                <span class="fw-bold text-dark">{{ __('order::general.total') }}</span>
                                <span class="fw-bolder text-primary">{{ number_format((float) $order->total, 2) }} {{ $currency }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
