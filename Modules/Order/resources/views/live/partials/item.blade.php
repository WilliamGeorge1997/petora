@use('Modules\Order\Enums\PaymentMethod')

<div class="card order-card w-100 mb-2 shadow-sm rounded-3 {{ !empty($isSelected) ? 'active-card' : '' }}" 
     data-order-id="{{ $order->id }}"
     data-status-id="{{ $order->order_status_id }}"
     data-search="{{ strtolower($order->order_no . ' ' . ($order->client?->name ?? '') . ' ' . ($order->store?->title ?? $order->clinic?->title ?? '')) }}"
     onclick="selectOrderCard({{ $order->id }})">
    <div class="card-body p-2 d-flex flex-column justify-content-between h-100">
        <!-- Row 1: Order # & Status with space between -->
        <div class="d-flex justify-content-between align-items-center gap-1 mb-1 flex-wrap">
            <span class="fw-bolder text-primary font-medium-1 text-nowrap" dir="ltr">{{ $order->order_no }}</span>
            <span class="badge badge-light-{{ $order->status_color }} font-small-2 py-25 px-50 text-truncate">
                {{ $order->orderStatus?->title ?? 'Status ' . $order->order_status_id }}
            </span>
        </div>

        <!-- Row 2: Price & Payment Method Icon -->
        <div class="d-flex justify-content-between align-items-center mb-1 pb-1 border-bottom">
            <span class="fw-bolder text-dark font-medium-1">{{ number_format((float) $order->total, 2) }} <small class="text-muted font-small-2">{{ config('currency.symbols.' . app()->getLocale(), 'EGP') }}</small></span>
            @if((int) $order->payment_method_id === PaymentMethod::CashOnDelivery->value)
                <i data-feather="dollar-sign" class="font-medium-3 text-success flex-shrink-0" title="{{ $order->paymentMethod?->title ?? __('order::payment_method.cash_on_delivery') }}" data-bs-toggle="tooltip"></i>
            @else
                <i data-feather="credit-card" class="font-medium-3 text-warning flex-shrink-0" title="{{ $order->paymentMethod?->title ?? __('order::payment_method.payment_online') }}" data-bs-toggle="tooltip"></i>
            @endif
        </div>

        <!-- Row 3: Client, Store/Clinic, & Driver (Unified Font & Design) -->
        <div class="d-flex flex-column gap-50 font-small-2 text-muted">
            <div class="d-flex align-items-center">
                <i data-feather="user" class="me-1 font-small-2 text-secondary flex-shrink-0"></i>
                <span class="text-truncate fw-semibold text-dark">{{ $order->client?->name ?? __('order::general.guest_client') }}</span>
            </div>

            @if($order->store?->title ?? $order->clinic?->title)
                <div class="d-flex align-items-center">
                    <i data-feather="home" class="me-1 font-small-2 text-secondary flex-shrink-0"></i>
                    <span class="text-truncate fw-semibold text-dark">{{ $order->store?->title ?? $order->clinic?->title }}</span>
                </div>
            @endif

            @if($order->driver)
                <div class="d-flex align-items-center">
                    <i data-feather="truck" class="me-1 font-small-2 text-secondary flex-shrink-0"></i>
                    <span class="text-truncate fw-semibold text-dark" title="{{ $order->driver->name }}">
                        {{ $order->driver->name }}
                    </span>
                </div>
            @endif
        </div>
    </div>
</div>
