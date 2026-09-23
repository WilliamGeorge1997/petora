@php
    $statusId = (int) $order->order_status_id;
    $statusClass = match($statusId) {
        1 => 'badge-light-primary',
        2 => 'badge-light-warning',
        3 => 'badge-light-info',
        4 => 'badge-light-success',
        6 => 'badge-light-danger',
        default => 'badge-light-secondary',
    };
    $clientName = $order->client?->name ?? __('order::general.guest_client', ['default' => 'Client']);
    $sellerTitle = $order->store?->title ?? $order->clinic?->title ?? null;
    
    // Quick Next Status mapping
    $nextStatusConfig = match($statusId) {
        1 => ['id' => 2, 'title' => 'Accept & Prepare', 'class' => 'btn-primary', 'icon' => 'check-circle'],
        2 => ['id' => 3, 'title' => 'Ready for Driver', 'class' => 'btn-info', 'icon' => 'arrow-right-circle'],
        3 => ['id' => 4, 'title' => 'Dispatch to Driver', 'class' => 'btn-success', 'icon' => 'truck'],
        4 => ['id' => 5, 'title' => 'Mark as Delivered (Done)', 'class' => 'btn-success', 'icon' => 'check'],
        6 => ['id' => 3, 'title' => 'Re-assign Driver', 'class' => 'btn-warning', 'icon' => 'refresh-cw'],
        default => null,
    };
@endphp

<!-- Mobile Back Button (< 992px) -->
<button class="btn btn-outline-secondary mb-2 d-lg-none w-100 d-flex align-items-center justify-content-center gap-1 py-1" 
        onclick="showMobileOrdersList()">
    <i data-feather="arrow-left"></i>
    <span class="fw-bold">Back to Orders Feed</span>
</button>

<!-- Order Header Card -->
<div class="card shadow-sm border-0 mb-0">
    <div class="card-body p-2 p-md-3">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2 pb-2 border-bottom">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                    <h3 class="fw-bolder mb-0 text-primary">Order #{{ $order->order_no }}</h3>
                    <span class="badge {{ $statusClass }} fs-6">
                        {{ $order->orderStatus?->title ?? 'Status ' . $order->order_status_id }}
                    </span>
                    @if($order->paymentMethod)
                        <span class="badge badge-light-secondary">
                            <i data-feather="credit-card" class="me-1 font-small-1"></i>{{ $order->paymentMethod->title }}
                        </span>
                    @endif
                </div>
                <div class="text-muted font-small-3 d-flex flex-wrap gap-2">
                    <span><i data-feather="calendar" class="me-1 font-small-2"></i>{{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : '' }}</span>
                    @if($sellerTitle)
                        <span>•</span>
                        <span><i data-feather="home" class="me-1 font-small-2"></i>{{ $sellerTitle }}</span>
                    @endif
                </div>
            </div>

            <div class="d-flex align-items-center gap-1">
                <!-- Live Timer Badge (Super Admin) -->
                <div class="text-end">
                    <span class="text-muted font-small-2 d-block">{{ __('order::general.time_elapsed', ['default' => 'Time Elapsed']) }}:</span>
                    <span class="badge badge-light-warning fs-5 fw-bold px-2 py-1 live-timer-badge d-inline-flex align-items-center" 
                          data-created-at="{{ $order->created_at ? $order->created_at->toIso8601String() : now()->toIso8601String() }}">
                        <i data-feather="clock" class="me-1 font-small-3"></i>
                        <span class="timer-text detail-timer-text">0m 00s</span>
                    </span>
                </div>

                <!-- Close Details Button -->
                <button type="button" 
                        class="btn btn-sm btn-icon btn-outline-danger ms-1" 
                        onclick="closeOrderDetail()" 
                        title="{{ __('order::general.close', ['default' => 'Close']) }}">
                    <i data-feather="x"></i>
                </button>
            </div>
        </div>

        <!-- DUAL STATUS ACTION BAR -->
        <div class="card bg-light-primary border border-primary p-2 mb-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <!-- Left: 1-Click Quick Next Button -->
                <div class="d-flex align-items-center gap-2">
                    @if($nextStatusConfig)
                        <button class="btn {{ $nextStatusConfig['class'] }} btn-lg d-flex align-items-center gap-1 shadow" 
                                onclick="submitStatusUpdate({{ $order->id }}, {{ $nextStatusConfig['id'] }})">
                            <i data-feather="{{ $nextStatusConfig['icon'] }}"></i>
                            <span class="fw-bold">{{ $nextStatusConfig['title'] }}</span>
                        </button>
                    @else
                        <span class="badge badge-light-secondary fs-6 py-1 px-2">Order Completed / Closed</span>
                    @endif
                </div>

                <!-- Right: Direct Status Override Dropdown -->
                <div class="d-flex align-items-center gap-1 flex-wrap">
                    <span class="font-small-2 text-dark fw-bold d-none d-sm-inline">Direct Jump:</span>
                    <select class="form-select form-select-sm" id="direct-status-select-{{ $order->id }}" style="min-width: 180px;">
                        <option value="" disabled selected>Jump Directly To...</option>
                        <option value="1" {{ $statusId == 1 ? 'selected' : '' }}>1. Sent (Pending)</option>
                        <option value="2" {{ $statusId == 2 ? 'selected' : '' }}>2. Accepted & Preparing</option>
                        <option value="3" {{ $statusId == 3 ? 'selected' : '' }}>3. Deliver to Driver</option>
                        <option value="4" {{ $statusId == 4 ? 'selected' : '' }}>4. On The Way</option>
                        <option value="5" {{ $statusId == 5 ? 'selected' : '' }} class="fw-bold text-success">5. Done (Delivered Directly)</option>
                        <option value="6" {{ $statusId == 6 ? 'selected' : '' }}>6. Refused by Driver</option>
                        <option value="7" {{ $statusId == 7 ? 'selected' : '' }}>7. Fail</option>
                        <option value="8" {{ $statusId == 8 ? 'selected' : '' }} class="text-danger">8. Cancelled</option>
                    </select>
                    <button class="btn btn-sm btn-outline-danger" 
                            onclick="submitDirectStatusUpdate({{ $order->id }})">
                        Apply Jump
                    </button>
                </div>
            </div>
        </div>

        <!-- VISUAL PROGRESS STEPPER -->
        <div class="mb-3 px-1">
            <div class="d-flex justify-content-between align-items-center">
                <div class="stepper-item {{ $statusId >= 1 ? 'completed' : '' }}">
                    <div class="stepper-circle"><i data-feather="check" style="width: 14px;"></i></div>
                    <div class="font-small-2 fw-bold mt-1">1. Sent</div>
                </div>
                <div class="stepper-item {{ $statusId > 2 ? 'completed' : ($statusId == 2 ? 'active' : '') }}">
                    <div class="stepper-circle">2</div>
                    <div class="font-small-2 fw-bold mt-1 {{ $statusId == 2 ? 'text-primary' : '' }}">2. Preparing</div>
                </div>
                <div class="stepper-item {{ $statusId > 3 && $statusId != 6 ? 'completed' : ($statusId == 3 ? 'active' : ($statusId == 6 ? 'active text-danger' : '')) }}">
                    <div class="stepper-circle">3</div>
                    <div class="font-small-2 fw-semibold mt-1">3. Driver</div>
                </div>
                <div class="stepper-item {{ $statusId > 4 && $statusId != 6 ? 'completed' : ($statusId == 4 ? 'active' : '') }}">
                    <div class="stepper-circle">4</div>
                    <div class="font-small-2 fw-semibold mt-1">4. On Way</div>
                </div>
                <div class="stepper-item {{ $statusId == 5 ? 'completed' : '' }}">
                    <div class="stepper-circle">5</div>
                    <div class="font-small-2 fw-semibold mt-1">5. Done</div>
                </div>
            </div>
        </div>

        <!-- DRIVER ASSIGNMENT SECTION (Visible for Driver handoff / re-assign) -->
        <div class="card border border-info bg-light-info mb-3 {{ in_array($statusId, [3, 6]) ? '' : 'd-none' }}" id="driver-assign-box">
            <div class="card-body p-2">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div class="d-flex align-items-center gap-1">
                        <i data-feather="truck" class="text-info fs-3"></i>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">{{ __('order::general.assign_driver', ['default' => 'Select Driver']) }}</h6>
                            <small class="text-muted">{{ __('order::general.select_driver', ['default' => 'Select available driver for delivery']) }}</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-1 flex-grow-1" style="max-width: 440px;">
                        <select class="form-select form-select-sm" id="driver-select-{{ $order->id }}">
                            <option value="">{{ __('order::general.select_driver', ['default' => 'Select Driver...']) }}</option>
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
                            <span>{{ __('order::general.dispatch', ['default' => 'Send']) }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2-COLUMN DETAILS -->
        <div class="row g-2">
            <!-- Customer & Delivery Column -->
            <div class="col-12 col-md-5">
                <div class="card border mb-2 h-100">
                    <div class="card-header bg-light py-1 px-2">
                        <h6 class="mb-0 fw-bold text-dark"><i data-feather="user" class="me-1"></i>Customer Details</h6>
                    </div>
                    <div class="card-body p-2 font-small-3">
                        <div class="mb-1">
                            <span class="text-muted d-block font-small-2">Client Name:</span>
                            <span class="fw-bold text-dark">{{ $clientName }}</span>
                        </div>
                        @if($order->client?->phone)
                            <div class="mb-1">
                                <span class="text-muted d-block font-small-2">Phone Number:</span>
                                <a href="tel:{{ $order->client->phone }}" class="fw-bold text-primary">
                                    <i data-feather="phone" class="me-1 font-small-1"></i>{{ $order->client->phone }}
                                </a>
                            </div>
                        @endif
                        <div class="mb-1">
                            <span class="text-muted d-block font-small-2">Delivery Address:</span>
                            <span class="text-dark">{{ $order->address?->address ?? __('order::general.no_address', ['default' => 'Address on record']) }}</span>
                        </div>
                        @if($order->delivery_date)
                            <div class="mb-1">
                                <span class="text-muted d-block font-small-2">Scheduled Delivery:</span>
                                <span class="badge badge-light-primary">
                                    {{ $order->delivery_date }} {{ $order->delivery_time_from ? "({$order->delivery_time_from} - {$order->delivery_time_to})" : '' }}
                                </span>
                            </div>
                        @endif
                        @if($order->notes)
                            <div>
                                <span class="text-muted d-block font-small-2">Customer Order Notes:</span>
                                <span class="fst-italic text-secondary">"{{ $order->notes }}"</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Items Table Column -->
            <div class="col-12 col-md-7">
                <div class="card border mb-2 h-100">
                    <div class="card-header bg-light py-1 px-2 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-dark">
                            <i data-feather="shopping-bag" class="me-1"></i>Order Items ({{ $order->details?->count() ?? 0 }})
                        </h6>
                        <span class="badge badge-light-primary">Total: ${{ number_format((float) $order->total, 2) }}</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-striped mb-0 font-small-3">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Price</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($order->details ?? [] as $detail)
                                        <tr>
                                            <td>
                                                <span class="fw-bold d-block text-dark">{{ $detail->product?->title ?? 'Product' }}</span>
                                                @if($detail->note)
                                                    <small class="text-muted">{{ $detail->note }}</small>
                                                @endif
                                            </td>
                                            <td class="text-center fw-bold">{{ $detail->quantity }}</td>
                                            <td class="text-end">${{ number_format((float) $detail->price, 2) }}</td>
                                            <td class="text-end fw-bold">${{ number_format((float) ($detail->price * $detail->quantity), 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-2">No item details available</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Price Breakdown Summary -->
                        <div class="p-2 bg-light border-top font-small-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Subtotal:</span>
                                <span class="fw-semibold text-dark">${{ number_format((float) $order->subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Delivery Fee:</span>
                                <span class="fw-semibold text-dark">${{ number_format((float) $order->delivery_fee, 2) }}</span>
                            </div>
                            @if($order->discount > 0)
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Discount:</span>
                                    <span class="text-success fw-semibold">-${{ number_format((float) $order->discount, 2) }}</span>
                                </div>
                            @endif
                            <div class="d-flex justify-content-between pt-1 border-top">
                                <span class="fw-bold fs-6 text-dark">Grand Total:</span>
                                <span class="fw-bolder fs-5 text-primary">${{ number_format((float) $order->total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
