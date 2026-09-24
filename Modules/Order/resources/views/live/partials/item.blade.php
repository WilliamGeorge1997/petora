@php
    use Illuminate\Support\Str;

    $statusClass = match((int) $order->order_status_id) {
        1 => 'badge-light-primary',
        2 => 'badge-light-warning',
        3 => 'badge-light-info',
        4 => 'badge-light-success',
        6 => 'badge-light-danger',
        default => 'badge-light-secondary',
    };
    $clientName = $order->client?->name ?? __('order::general.guest_client', ['default' => 'Client']);
    $sellerTitle = $order->store?->title ?? $order->clinic?->title ?? null;
    $isSelected = $isSelected ?? false;
    $currency = config('currency.symbols.' . app()->getLocale(), config('currency.default', 'EGP'));
@endphp

<div class="card order-card mb-2 shadow-sm rounded-3 {{ $isSelected ? 'active-card' : '' }}" 
     data-order-id="{{ $order->id }}"
     data-status-id="{{ $order->order_status_id }}"
     data-search="{{ strtolower($order->order_no . ' ' . $clientName . ' ' . ($sellerTitle ?? '')) }}"
     onclick="selectOrderCard({{ $order->id }})">
    <div class="card-body p-2">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="fw-bolder text-primary fs-5 text-nowrap" dir="ltr" style="unicode-bidi: isolate;">#{{ $order->order_no }}</span>
            <span class="fw-bolder text-dark fs-5">{{ number_format((float) $order->total, 2) }} <small class="text-muted font-small-2">{{ $currency }}</small></span>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="text-truncate fw-semibold text-body" style="max-width: 180px;">
                <i data-feather="user" class="me-1 font-small-2"></i>{{ $clientName }}
            </span>
            <span class="badge {{ $statusClass }}">
                {{ $order->orderStatus?->title ?? 'Status ' . $order->order_status_id }}
            </span>
        </div>

        @if($sellerTitle)
            <div class="mb-1">
                <span class="badge badge-light-secondary font-small-2">
                    <i data-feather="home" class="me-1 font-small-1"></i>{{ $sellerTitle }}
                </span>
            </div>
        @endif

        @if($order->driver)
            <div class="d-flex justify-content-between align-items-center pt-1 border-top">
                <span class="font-small-2 text-primary fw-semibold" title="{{ $order->driver->name }}">
                    <i data-feather="truck" class="me-1 font-small-1"></i>{{ Str::limit($order->driver->name, 20) }}
                </span>
            </div>
        @endif
    </div>
</div>
