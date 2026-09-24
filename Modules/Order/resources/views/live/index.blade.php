@extends('common::layouts.master')

@use('Modules\Order\Enums\OrderStatus')

@section('title', __('order::general.live_orders'))


@section('content')
<!-- Top Control Bar -->
<div class="card mb-2 shadow-sm border-0 d-none d-lg-block">
    <div class="card-body p-1 p-sm-2 d-flex flex-wrap justify-content-between align-items-center gap-1 gap-sm-2">
        <div class="d-flex align-items-center gap-1">
            <span class="live-pulse me-50"></span>
            <div>
                <h4 class="mb-0 fw-bold text-dark fs-5 fs-sm-4 d-flex align-items-center gap-1">
                    <span>{{ __('order::general.live.title') }}</span>
                </h4>
                <small class="text-muted font-small-2">{{ __('order::general.live.subtitle') }}</small>
            </div>
        </div>
        
        <div class="d-flex align-items-center gap-1 flex-wrap">
            <button id="sound-btn" 
                    type="button" 
                    class="btn btn-sm btn-outline-primary d-inline-flex align-items-center justify-content-center gap-50 px-1 py-50" 
                    onclick="toggleSound()">
                <span id="icon-sound-on"><i data-feather="volume-2" class="font-small-3"></i></span>
                <span id="icon-sound-off" class="d-none"><i data-feather="volume-x" class="font-small-3"></i></span>
                <span id="sound-text" class="font-small-2">{{ __('order::general.live.notify_on') }}</span>
            </button>
        </div>
    </div>
</div>

<!-- Main Split Grid -->
<div class="row g-2 align-items-stretch" id="live-orders-grid">
    <!-- LEFT PANE: Live Stream Feed (35% Desktop / 100% Mobile) -->
    <div id="orders-list-pane" class="col-12 col-lg-4 col-xl-4 {{ (isset($selectedOrder) && $selectedOrder) ? 'd-none' : 'd-flex' }} d-lg-flex flex-column mb-1 mb-lg-2">
        <div class="card shadow-sm border-0 order-feed-card flex-grow-1 d-flex flex-column mb-0">
            <div class="card-body p-1 p-lg-2 d-flex flex-column flex-grow-1 h-100">
                <!-- Unified Filter Row (Single row on mobile, stacked on desktop) -->
                <div class="d-flex flex-row flex-lg-column align-items-center align-items-lg-stretch gap-50 gap-lg-1 mb-1 flex-shrink-0">
                    <!-- Search Input (takes remaining ~54% width on mobile) -->
                    <div class="input-group input-group-merge flex-grow-1" style="min-width: 0;">
                        <span class="input-group-text px-50 py-25 px-lg-1 py-lg-50"><i data-feather="search" class="font-small-3"></i></span>
                        <input type="text" id="order-search" class="form-control form-control-sm px-50 px-lg-1" placeholder="{{ __('order::general.live.search') }}" onkeyup="filterOrdersList()">
                    </div>

                    <!-- Status Filter Dropdown (compact ~36% width on mobile) -->
                    <div class="flex-shrink-0 order-status-filter-wrapper">
                        <select class="form-select form-select-sm text-truncate px-50 px-lg-1" id="live-status-filter" onchange="filterOrdersList()">
                            <option value="all" selected>{{ __('common::general.all') }}</option>
                            <option value="{{ OrderStatus::Sent->value }}">{{ OrderStatus::Sent->label() }}</option>
                            <option value="{{ OrderStatus::AcceptedAndPreparing->value }}">{{ OrderStatus::AcceptedAndPreparing->label() }}</option>
                            <option value="{{ OrderStatus::DeliverToDriver->value }}">{{ OrderStatus::DeliverToDriver->label() }}</option>
                            <option value="{{ OrderStatus::OnTheWay->value }}">{{ OrderStatus::OnTheWay->label() }}</option>
                            <option value="{{ OrderStatus::RefusedByDriver->value }}">{{ OrderStatus::RefusedByDriver->label() }}</option>
                        </select>
                    </div>

                    <!-- Sound Toggle Icon Button (mobile only, d-lg-none, ~10% width) -->
                    <button id="sound-btn-mobile" 
                            type="button" 
                            class="btn btn-sm btn-outline-primary d-inline-flex d-lg-none align-items-center justify-content-center p-50 flex-shrink-0" 
                            title="{{ __('order::general.live.notify_on') }}"
                            onclick="toggleSound()">
                        <span id="icon-sound-on-mobile"><i data-feather="volume-2" class="font-small-3"></i></span>
                        <span id="icon-sound-off-mobile" class="d-none"><i data-feather="volume-x" class="font-small-3"></i></span>
                    </button>
                </div>

                <!-- Order Cards Stream -->
                <div class="order-stream-scroll w-100 flex-grow-1 overflow-auto px-1 py-1" id="order-cards-container">
                    @forelse($orders ?? [] as $order)
                        @include('order::live.partials.item', ['order' => $order, 'isSelected' => false])
                    @empty
                        <div class="text-center py-4 text-muted" id="no-orders-msg">
                            <i data-feather="inbox" class="fs-1 d-block mb-1"></i>
                            <p class="mb-0 fw-semibold">{{ __('order::general.live.no_orders') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT PANE: Active Workspace (65% Desktop / 100% Mobile) -->
    <div id="order-workspace-pane" class="col-12 col-lg-8 col-xl-8 {{ (isset($selectedOrder) && $selectedOrder) ? 'd-flex' : 'd-none' }} d-lg-flex flex-column mb-1 mb-lg-2">
        @if(isset($selectedOrder) && $selectedOrder)
            @include('order::live.partials.detail', ['order' => $selectedOrder, 'viewModel' => $viewModel ?? null])
        @else
            <div class="card shadow-sm border-0 order-workspace-card order-workspace-empty flex-grow-1 d-flex flex-column mb-0">
                <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-3 p-md-5 text-muted">
                    <h5 class="fw-bold text-dark mb-1">{{ __('order::general.live.no_selected') }}</h5>
                    <p class="mb-0 text-muted">{{ __('order::general.live.select_prompt') }}</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('js')
<script>
    let soundEnabled = true;
    let audioContext = null;

    document.addEventListener("DOMContentLoaded", function () {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            const tooltipElements = document.querySelectorAll('#order-workspace-pane [data-bs-toggle="tooltip"]');
            tooltipElements.forEach(el => new bootstrap.Tooltip(el));
        }
    });

    let currentOrderXhr = null;

    // Select Order Card (AJAX load workspace)
    function selectOrderCard(orderId) {
        document.querySelectorAll('.order-card').forEach(c => c.classList.remove('active-card'));
        const selectedCard = document.querySelector(`.order-card[data-order-id="${orderId}"]`);
        if (selectedCard) selectedCard.classList.add('active-card');

        // Mobile responsive: toggle from list view to workspace view
        if (window.innerWidth < 992) {
            const listPane = document.getElementById('orders-list-pane');
            if (listPane) {
                listPane.classList.add('d-none', 'd-lg-flex');
                listPane.classList.remove('d-flex');
            }
            const wsPane = document.getElementById('order-workspace-pane');
            if (wsPane) {
                wsPane.classList.remove('d-none');
                wsPane.classList.add('d-flex', 'd-lg-flex');
            }
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Cancel previous pending request if user clicks quickly
        if (currentOrderXhr) {
            currentOrderXhr.abort();
        }

        // Render Skeleton Loader inside the fixed workspace pane
        renderOrderSkeleton();

        // Fetch detail partial via reusable detail route
        const url = `/admin/orders/live/${orderId}`;
        currentOrderXhr = $.get(url, function (html) {
            $('#order-workspace-pane').html(html);
            if (typeof feather !== 'undefined') feather.replace();
            if (typeof bootstrap !== 'undefined') {
                if (bootstrap.Tooltip) {
                    const tooltipElements = document.querySelectorAll('#order-workspace-pane [data-bs-toggle="tooltip"]');
                    tooltipElements.forEach(el => new bootstrap.Tooltip(el));
                }
                if (bootstrap.Popover) {
                    const popoverElements = document.querySelectorAll('#order-workspace-pane [data-bs-toggle="popover"]');
                    popoverElements.forEach(el => new bootstrap.Popover(el));
                }
            }
        }).fail(function (xhr) {
            if (xhr.statusText !== 'abort') {
                $('#order-workspace-pane').html(`
                    <div class="card shadow-sm border-0 order-workspace-card flex-grow-1">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-4">
                            <i data-feather="alert-circle" class="text-danger fs-1 mb-2"></i>
                            <h5 class="fw-bold mb-1">{{ __('order::general.live.error') }}</h5>
                            <button class="btn btn-sm btn-outline-primary mt-2" onclick="selectOrderCard(${orderId})">
                                <i data-feather="refresh-cw" class="me-1"></i> {{ __('order::general.live.retry') }}
                            </button>
                        </div>
                    </div>
                `);
                if (typeof feather !== 'undefined') feather.replace();
            }
        });
    }

    // Native Bootstrap Placeholder Skeleton
    function renderOrderSkeleton() {
        const skeletonHtml = `
            <div class="card shadow-sm border-0 mb-0 order-feed-card order-workspace-card placeholder-glow d-flex flex-column overflow-auto">
                <div class="card-body p-2 p-md-3 overflow-auto flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                        <div class="w-50">
                            <div class="d-flex align-items-center gap-1 mb-1">
                                <span class="placeholder col-6 rounded"></span>
                                <span class="placeholder col-3 rounded"></span>
                            </div>
                            <span class="placeholder col-8"></span>
                        </div>
                        <div class="text-end w-25">
                            <span class="placeholder col-8 rounded"></span>
                        </div>
                    </div>

                    <div class="card bg-light border p-2 mb-3">
                        <div class="d-flex justify-content-between align-items-center gap-2">
                            <span class="placeholder col-4 rounded py-1"></span>
                            <span class="placeholder col-3 rounded py-1"></span>
                        </div>
                    </div>

                    <div class="mb-3 px-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="stepper-item position-relative text-center flex-fill"><div class="stepper-circle d-inline-flex align-items-center justify-content-center rounded-circle fw-bold position-relative font-small-2 bg-light border"></div><div class="placeholder col-8 mt-1"></div></div>
                            <div class="stepper-item position-relative text-center flex-fill"><div class="stepper-circle d-inline-flex align-items-center justify-content-center rounded-circle fw-bold position-relative font-small-2 bg-light border"></div><div class="placeholder col-8 mt-1"></div></div>
                            <div class="stepper-item position-relative text-center flex-fill"><div class="stepper-circle d-inline-flex align-items-center justify-content-center rounded-circle fw-bold position-relative font-small-2 bg-light border"></div><div class="placeholder col-8 mt-1"></div></div>
                            <div class="stepper-item position-relative text-center flex-fill"><div class="stepper-circle d-inline-flex align-items-center justify-content-center rounded-circle fw-bold position-relative font-small-2 bg-light border"></div><div class="placeholder col-8 mt-1"></div></div>
                            <div class="stepper-item position-relative text-center flex-fill"><div class="stepper-circle d-inline-flex align-items-center justify-content-center rounded-circle fw-bold position-relative font-small-2 bg-light border"></div><div class="placeholder col-8 mt-1"></div></div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-12 col-md-5">
                            <div class="card border mb-2 h-100 p-2">
                                <span class="placeholder col-6 mb-2"></span>
                                <span class="placeholder col-10 mb-1"></span>
                                <span class="placeholder col-8 mb-1"></span>
                                <span class="placeholder col-11 mb-1"></span>
                                <span class="placeholder col-7"></span>
                            </div>
                        </div>
                        <div class="col-12 col-md-7">
                            <div class="card border mb-2 h-100 p-2">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="placeholder col-5"></span>
                                    <span class="placeholder col-3"></span>
                                </div>
                                <span class="placeholder col-12 mb-1"></span>
                                <span class="placeholder col-12 mb-1"></span>
                                <span class="placeholder col-12 mb-2"></span>
                                <div class="pt-1 border-top d-flex justify-content-between">
                                    <span class="placeholder col-4"></span>
                                    <span class="placeholder col-4"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $('#order-workspace-pane').html(skeletonHtml);
    }

    // Mobile Back Button
    function showMobileOrdersList() {
        const listPane = document.getElementById('orders-list-pane');
        if (listPane) {
            listPane.classList.remove('d-none');
            listPane.classList.add('d-flex', 'd-lg-flex');
        }
        const wsPane = document.getElementById('order-workspace-pane');
        if (wsPane) {
            wsPane.classList.add('d-none', 'd-lg-flex');
            wsPane.classList.remove('d-flex');
        }
    }

    // Close Details Panel
    function closeOrderDetail() {
        document.querySelectorAll('.order-card').forEach(c => c.classList.remove('active-card'));
        const emptyHtml = `
            <div class="card shadow-sm border-0 order-workspace-card order-workspace-empty flex-grow-1 d-flex flex-column h-100 mb-0">
                <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-3 p-md-5 text-muted">
                    <h5 class="fw-bold text-dark mb-1">{{ __('order::general.live.no_selected') }}</h5>
                    <p class="mb-0 text-muted">{{ __('order::general.live.select_prompt') }}</p>
                </div>
            </div>
        `;
        $('#order-workspace-pane').html(emptyHtml);
        if (typeof feather !== 'undefined') feather.replace();

        if (window.innerWidth < 992) {
            showMobileOrdersList();
        } else {
            const listPane = document.getElementById('orders-list-pane');
            if (listPane) {
                listPane.classList.remove('d-none');
                listPane.classList.add('d-flex', 'd-lg-flex');
            }
            const wsPane = document.getElementById('order-workspace-pane');
            if (wsPane) {
                wsPane.classList.remove('d-none');
                wsPane.classList.add('d-flex', 'd-lg-flex');
            }
        }
    }

    // Responsive Viewport Breakpoint Synchronization (Desktop >= 992px vs Mobile < 992px)
    const desktopBreakpoint = window.matchMedia('(min-width: 992px)');
    function syncPanesOnBreakpoint(e) {
        const listPane = document.getElementById('orders-list-pane');
        const wsPane = document.getElementById('order-workspace-pane');
        if (!listPane || !wsPane) return;

        if (e.matches) {
            // >= 992px Desktop: ALWAYS display both panes side-by-side
            listPane.classList.remove('d-none');
            listPane.classList.add('d-flex', 'd-lg-flex');
            wsPane.classList.remove('d-none');
            wsPane.classList.add('d-flex', 'd-lg-flex');
        } else {
            // < 992px Mobile: Single pane mode depending on whether an order is active
            const hasActiveOrder = document.querySelector('.order-card.active-card') !== null;
            if (hasActiveOrder) {
                listPane.classList.add('d-none', 'd-lg-flex');
                listPane.classList.remove('d-flex');
                wsPane.classList.remove('d-none');
                wsPane.classList.add('d-flex', 'd-lg-flex');
            } else {
                listPane.classList.remove('d-none');
                listPane.classList.add('d-flex', 'd-lg-flex');
                wsPane.classList.add('d-none', 'd-lg-flex');
                wsPane.classList.remove('d-flex');
            }
        }
    }
    if (desktopBreakpoint.addEventListener) {
        desktopBreakpoint.addEventListener('change', syncPanesOnBreakpoint);
    } else if (desktopBreakpoint.addListener) {
        desktopBreakpoint.addListener(syncPanesOnBreakpoint);
    }

    // Search filter in feed
    // Combined Feed Filter (Search Query + Status Dropdown)
    function filterOrdersList() {
        const query = (document.getElementById('order-search')?.value || '').toLowerCase();
        const status = document.getElementById('live-status-filter')?.value || 'all';

        document.querySelectorAll('.order-card').forEach(card => {
            const data = (card.getAttribute('data-search') || '').toLowerCase();
            const cardStatus = card.getAttribute('data-status-id');
            const matchesQuery = !query || data.includes(query);
            const matchesStatus = status === 'all' || cardStatus === status;

            card.style.display = (matchesQuery && matchesStatus) ? 'block' : 'none';
        });
    }

    function setLiveStatusFilter(status) {
        const select = document.getElementById('live-status-filter');
        if (select) select.value = status;
        filterOrdersList();
    }

    // Reusable Status Update (Submits to standard OrderController update route)
    function submitStatusUpdate(orderId, newStatusId, driverId = null, notes = null) {
        const token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: `/admin/orders/${orderId}`,
            type: 'POST',
            data: {
                _method: 'PUT',
                _token: token,
                order_status_id: newStatusId,
                driver_id: driverId,
                notes: notes
            },
            success: function (res) {
                if (typeof successAlert === 'function') {
                    successAlert(res.message || 'Status updated successfully');
                }
                // Refresh workspace
                selectOrderCard(orderId);
            },
            error: function () {
                if (typeof errorAlert === 'function') {
                    errorAlert('Failed to update status');
                }
            }
        });
    }

    // Direct Status Override Handler
    function submitDirectStatusUpdate(orderId) {
        const select = document.getElementById(`direct-status-select-${orderId}`);
        if (!select || !select.value) {
            alert('Please select a status first');
            return;
        }
        submitStatusUpdate(orderId, select.value);
    }

    // Driver Dispatch Handler
    function submitDriverDispatch(orderId) {
        const select = document.getElementById(`driver-select-${orderId}`);
        if (!select || !select.value) {
            alert('Please select a driver');
            return;
        }
        submitStatusUpdate(orderId, {{ OrderStatus::OnTheWay->value }}, select.value); // Status OnTheWay
    }

    // Audio Notification Toggle & Web Audio Synth
    function toggleSound() {
        soundEnabled = !soundEnabled;
        const text = document.getElementById('sound-text');
        const btn = document.getElementById('sound-btn');
        const iconOn = document.getElementById('icon-sound-on');
        const iconOff = document.getElementById('icon-sound-off');
        const onText = "{{ __('order::general.live.notify_on') }}";
        const offText = "{{ __('order::general.live.notify_off') }}";

        if (text) text.innerText = soundEnabled ? onText : offText;

        if (btn) {
            btn.classList.toggle('btn-outline-primary', soundEnabled);
            btn.classList.toggle('btn-outline-secondary', !soundEnabled);
        }

        const btnMobile = document.getElementById('sound-btn-mobile');
        const iconOnMobile = document.getElementById('icon-sound-on-mobile');
        const iconOffMobile = document.getElementById('icon-sound-off-mobile');

        if (btnMobile) {
            btnMobile.classList.toggle('btn-outline-primary', soundEnabled);
            btnMobile.classList.toggle('btn-outline-secondary', !soundEnabled);
        }

        if (iconOn && iconOff) {
            iconOn.classList.toggle('d-none', !soundEnabled);
            iconOff.classList.toggle('d-none', soundEnabled);
        }

        if (iconOnMobile && iconOffMobile) {
            iconOnMobile.classList.toggle('d-none', !soundEnabled);
            iconOffMobile.classList.toggle('d-none', soundEnabled);
        }
    }

    function playNotificationChime() {
        if (!soundEnabled) return;
        try {
            if (!audioContext) audioContext = new (window.AudioContext || window.webkitAudioContext)();
            if (audioContext.state === 'suspended') audioContext.resume();

            const osc1 = audioContext.createOscillator();
            const osc2 = audioContext.createOscillator();
            const gain = audioContext.createGain();

            osc1.type = 'sine';
            osc1.frequency.setValueAtTime(880, audioContext.currentTime);
            osc2.type = 'sine';
            osc2.frequency.setValueAtTime(659.25, audioContext.currentTime + 0.15);

            gain.gain.setValueAtTime(0.2, audioContext.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, audioContext.currentTime + 0.6);

            osc1.connect(gain);
            osc2.connect(gain);
            gain.connect(audioContext.destination);

            osc1.start(audioContext.currentTime);
            osc1.stop(audioContext.currentTime + 0.15);
            osc2.start(audioContext.currentTime + 0.15);
            osc2.stop(audioContext.currentTime + 0.6);
        } catch (e) {
            console.log('Audio chime played');
        }
    }
</script>
@endsection
