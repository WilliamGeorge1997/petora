@extends('common::layouts.master')

@section('title', __('order::general.live_orders', ['default' => 'Live Orders Console']))

@section('css')
<style>
    .live-pulse {
        width: 10px;
        height: 10px;
        background-color: #28c76f;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 0 rgba(40, 199, 111, 0.7);
        animation: pulse-green 1.6s infinite;
    }
    @keyframes pulse-green {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(40, 199, 111, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(40, 199, 111, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(40, 199, 111, 0); }
    }
    .timer-pulse-danger {
        animation: pulse-red 1.2s infinite;
    }
    @keyframes pulse-red {
        0% { box-shadow: 0 0 0 0 rgba(234, 84, 85, 0.6); }
        70% { box-shadow: 0 0 0 6px rgba(234, 84, 85, 0); }
        100% { box-shadow: 0 0 0 0 rgba(234, 84, 85, 0); }
    }
    .timer-text {
        display: inline-block;
        font-variant-numeric: tabular-nums;
        text-align: center;
        min-width: 58px;
        white-space: nowrap;
    }
    .detail-timer-text {
        min-width: 76px;
    }
    .skeleton-shimmer {
        background: linear-gradient(90deg, #f0f0f2 25%, #e4e3e8 50%, #f0f0f2 75%);
        background-size: 200% 100%;
        animation: skeleton-glow 1.5s ease-in-out infinite;
        border-radius: 4px;
        display: inline-block;
    }
    @keyframes skeleton-glow {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    .order-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        cursor: pointer;
        border: 2px solid #ebe9f1;
    }
    .order-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px 0 rgba(34, 41, 47, 0.08) !important;
    }
    .order-card.active-card {
        border: 2px solid #7367f0 !important;
        background-color: #fcfcff;
    }
    .order-stream-scroll {
        padding: 6px 8px 8px 8px;
    }
    .btn-close-detail {
        background-color: #ea5455 !important;
        border-color: #ea5455 !important;
        color: #fff !important;
        transition: all 0.15s ease-in-out;
    }
    .btn-close-detail:hover {
        background-color: #d63031 !important;
        border-color: #d63031 !important;
        color: #fff !important;
        box-shadow: 0 8px 25px -8px #ea5455 !important;
    }
    @media (min-width: 992px) {
        #live-orders-grid {
            align-items: stretch;
        }
        #orders-list-pane,
        #order-workspace-pane {
            display: flex !important;
            flex-direction: column;
        }
        .order-feed-card,
        #order-workspace-pane > .card {
            margin-bottom: 0 !important;
        }
        .order-feed-card {
            display: flex !important;
            flex-direction: column !important;
            flex: 1 1 auto !important;
            height: 100% !important;
            min-height: 0 !important;
        }
        .order-feed-card .card-body {
            display: flex !important;
            flex-direction: column !important;
            flex: 1 1 auto !important;
            height: 100% !important;
            min-height: 0 !important;
            overflow: hidden !important;
        }
        .order-stream-scroll {
            flex: 1 1 0 !important;
            min-height: 0 !important;
            overflow-y: auto !important;
            overflow-anchor: none;
            padding: 6px 8px 8px 8px !important;
        }
        .order-workspace-empty {
            min-height: 520px;
            height: 100%;
            display: flex !important;
            flex-direction: column !important;
            flex-grow: 1 !important;
        }
        .order-workspace-empty .card-body {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            height: 100% !important;
        }
    }
    .order-stream-scroll::-webkit-scrollbar {
        width: 6px;
    }
    .order-stream-scroll::-webkit-scrollbar-thumb {
        background-color: #e0e0e0;
        border-radius: 4px;
    }
    .stepper-item {
        position: relative;
        text-align: center;
        flex: 1;
    }
    .stepper-item::after {
        content: '';
        position: absolute;
        top: 14px;
        left: 50%;
        width: 100%;
        height: 2px;
        background-color: #ebe9f1;
        z-index: 1;
    }
    .stepper-item:last-child::after {
        display: none;
    }
    .stepper-item.completed::after {
        background-color: #28c76f;
    }
    .stepper-circle {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
        position: relative;
        z-index: 2;
        background-color: #f8f8f8;
        border: 2px solid #ebe9f1;
        color: #b9b9c3;
    }
    .stepper-item.completed .stepper-circle {
        background-color: #28c76f;
        border-color: #28c76f;
        color: #fff;
    }
    .stepper-item.active .stepper-circle {
        background-color: #7367f0;
        border-color: #7367f0;
        color: #fff;
        box-shadow: 0 0 0 4px rgba(115, 103, 240, 0.2);
    }
</style>
@endsection

@section('content')
<!-- Top Control Bar -->
<div class="card mb-2 shadow-sm border-0">
    <div class="card-body p-2 d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="live-pulse me-1"></span>
            <div>
                <h4 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                    <span>{{ __('order::general.live_orders', ['default' => 'Live Orders Console']) }}</span>
                </h4>
                <small class="text-muted">{{ __('order::general.live_orders_subtitle') }}</small>
            </div>
        </div>
        
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button id="sound-btn" 
                    type="button" 
                    class="btn btn-outline-primary d-inline-flex align-items-center justify-content-center gap-1" 
                    style="min-width: 180px;" 
                    onclick="toggleSound()">
                <span id="icon-sound-on"><i data-feather="volume-2"></i></span>
                <span id="icon-sound-off" class="d-none"><i data-feather="volume-x"></i></span>
                <span id="sound-text">{{ __('order::general.notification_on', ['default' => 'Notification: ON']) }}</span>
            </button>
        </div>
    </div>
</div>

<!-- Main Split Grid -->
<div class="row g-2 align-items-stretch" id="live-orders-grid">
    <!-- LEFT PANE: Live Stream Feed (35% Desktop / 100% Mobile) -->
    <div id="orders-list-pane" class="col-12 col-lg-4 col-xl-4 d-flex flex-column mb-2">
        <div class="card shadow-sm border-0 order-feed-card flex-grow-1">
            <div class="card-body p-2 d-flex flex-column flex-grow-1 overflow-hidden" style="min-height: 0;">
                <!-- Search Input -->
                <div class="input-group input-group-merge mb-2 flex-shrink-0">
                    <span class="input-group-text"><i data-feather="search"></i></span>
                    <input type="text" id="order-search" class="form-control" placeholder="Search Order #, Client, Store..." onkeyup="filterOrdersList()">
                </div>

                <!-- Status Filter Pills -->
                <div class="d-flex gap-1 overflow-auto pb-1 mb-2 flex-shrink-0" style="white-space: nowrap;">
                    <button class="btn btn-sm btn-primary filter-pill active" onclick="setLiveStatusFilter('all', this)">All</button>
                    <button class="btn btn-sm btn-outline-secondary filter-pill" onclick="setLiveStatusFilter('1', this)">New</button>
                    <button class="btn btn-sm btn-outline-secondary filter-pill" onclick="setLiveStatusFilter('2', this)">Preparing</button>
                    <button class="btn btn-sm btn-outline-secondary filter-pill" onclick="setLiveStatusFilter('3', this)">Driver</button>
                    <button class="btn btn-sm btn-outline-secondary filter-pill" onclick="setLiveStatusFilter('4', this)">On Way</button>
                    <button class="btn btn-sm btn-outline-secondary filter-pill" onclick="setLiveStatusFilter('6', this)">Refused</button>
                </div>

                <!-- Order Cards Stream -->
                <div class="order-stream-scroll flex-grow-1" id="order-cards-container" style="overflow-y: auto; min-height: 0;">
                    @forelse($orders ?? [] as $order)
                        @include('order::live.partials.item', ['order' => $order, 'isSelected' => false])
                    @empty
                        <div class="text-center py-4 text-muted" id="no-orders-msg">
                            <i data-feather="inbox" class="fs-1 d-block mb-1"></i>
                            <p class="mb-0 fw-semibold">{{ __('order::general.no_orders_msg') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT PANE: Active Workspace (65% Desktop / 100% Mobile) -->
    <div id="order-workspace-pane" class="col-12 col-lg-8 col-xl-8 d-flex flex-column mb-2">
        @if(isset($selectedOrder) && $selectedOrder)
            @include('order::live.partials.detail', ['order' => $selectedOrder, 'viewModel' => $viewModel ?? null])
        @else
            <div class="card shadow-sm border-0 order-workspace-empty flex-grow-1">
                <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-3 p-md-5 text-muted">
                    <h5 class="fw-bold text-dark mb-1">{{ __('order::general.no_order_selected', ['default' => 'No Order Selected']) }}</h5>
                    <p class="mb-0 text-muted">{{ __('order::general.select_order_prompt', ['default' => 'Click any order card from the left feed to view order details.']) }}</p>
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
        startLiveTimers();
    });

    // Live Ticking Timers in JavaScript (Zero DB overhead)
    function startLiveTimers() {
        setInterval(() => {
            document.querySelectorAll('.live-timer-badge').forEach(badge => {
                const createdAtStr = badge.getAttribute('data-created-at');
                if (!createdAtStr) return;

                const createdAt = new Date(createdAtStr).getTime();
                const now = new Date().getTime();
                const diffSeconds = Math.max(0, Math.floor((now - createdAt) / 1000));

                const mins = Math.floor(diffSeconds / 60);
                const secs = diffSeconds % 60;
                const timerText = badge.querySelector('.timer-text');
                if (timerText) {
                    const formatted = `${mins}m ${secs < 10 ? '0' : ''}${secs}s`;
                    if (timerText.innerText !== formatted) {
                        timerText.innerText = formatted;
                    }
                }

                // Dynamic color shifting WITHOUT wiping badge classes
                const isDanger = diffSeconds >= 180;
                const isWarning = diffSeconds >= 90 && diffSeconds < 180;
                const isSuccess = diffSeconds < 90;

                badge.classList.toggle('badge-light-danger', isDanger);
                badge.classList.toggle('timer-pulse-danger', isDanger);
                badge.classList.toggle('badge-light-warning', isWarning);
                badge.classList.toggle('badge-light-success', isSuccess);
            });
        }, 1000);
    }

    let currentOrderXhr = null;

    // Select Order Card (AJAX load workspace)
    function selectOrderCard(orderId) {
        document.querySelectorAll('.order-card').forEach(c => c.classList.remove('active-card'));
        const selectedCard = document.querySelector(`.order-card[data-order-id="${orderId}"]`);
        if (selectedCard) selectedCard.classList.add('active-card');

        // Mobile responsive: toggle from list view to workspace view
        if (window.innerWidth < 992) {
            document.getElementById('orders-list-pane').classList.add('d-none');
            document.getElementById('order-workspace-pane').classList.remove('d-none');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Lock feed scroll position & container heights to completely prevent collapse / jump
        const feedContainer = document.getElementById('order-cards-container');
        const listPane = document.getElementById('orders-list-pane');
        const workspacePane = document.getElementById('order-workspace-pane');

        const savedScrollTop = feedContainer ? feedContainer.scrollTop : 0;
        const currentListHeight = listPane ? listPane.offsetHeight : 0;
        const currentWorkspaceHeight = workspacePane ? workspacePane.offsetHeight : 0;
        const currentFeedHeight = feedContainer ? feedContainer.offsetHeight : 0;

        // Synchronously lock min-height on all containers so layout cannot shrink during swap
        if (listPane && currentListHeight > 0) {
            listPane.style.minHeight = `${currentListHeight}px`;
        }
        if (feedContainer && currentFeedHeight > 0) {
            feedContainer.style.minHeight = `${currentFeedHeight}px`;
        }
        if (workspacePane && currentWorkspaceHeight > 0) {
            workspacePane.style.minHeight = `${currentWorkspaceHeight}px`;
        }

        // Render Facebook-style Skeleton Loader with matched height
        renderOrderSkeleton(currentWorkspaceHeight);

        // Keep feed scroll locked in place
        if (feedContainer) feedContainer.scrollTop = savedScrollTop;

        // Cancel previous pending request if user clicks quickly
        if (currentOrderXhr) {
            currentOrderXhr.abort();
        }

        // Fetch detail partial via reusable detail route
        const url = `/admin/orders/live/${orderId}`;
        currentOrderXhr = $.get(url, function (html) {
            $('#order-workspace-pane').html(html);
            if (typeof feather !== 'undefined') feather.replace();

            if (feedContainer) feedContainer.scrollTop = savedScrollTop;

            // Release temporary min-height locks cleanly in next animation frame
            requestAnimationFrame(() => {
                if (listPane) listPane.style.minHeight = '';
                if (feedContainer) feedContainer.style.minHeight = '';
                if (workspacePane) workspacePane.style.minHeight = '';
                if (feedContainer) feedContainer.scrollTop = savedScrollTop;
            });
        });
    }

    // Facebook-style Skeleton Placeholder
    function renderOrderSkeleton(minHeight = null) {
        const heightStyle = minHeight && minHeight > 200 ? `min-height: ${minHeight}px;` : '';
        const skeletonHtml = `
            <div class="card shadow-sm border-0 mb-0 h-100" style="${heightStyle}">
                <div class="card-body p-2 p-md-3">
                    <div class="d-flex justify-content-between align-items-start mb-2 pb-2 border-bottom">
                        <div>
                            <div class="d-flex align-items-center gap-1 mb-1">
                                <span class="skeleton-shimmer" style="width: 170px; height: 28px; border-radius: 6px;"></span>
                                <span class="skeleton-shimmer" style="width: 90px; height: 24px; border-radius: 12px;"></span>
                            </div>
                            <span class="skeleton-shimmer" style="width: 220px; height: 16px;"></span>
                        </div>
                        <div class="text-end">
                            <span class="skeleton-shimmer d-block mb-50" style="width: 70px; height: 12px;"></span>
                            <span class="skeleton-shimmer" style="width: 100px; height: 32px; border-radius: 16px;"></span>
                        </div>
                    </div>

                    <div class="card bg-light border p-2 mb-3">
                        <div class="d-flex justify-content-between align-items-center gap-2">
                            <span class="skeleton-shimmer" style="width: 180px; height: 40px; border-radius: 6px;"></span>
                            <span class="skeleton-shimmer" style="width: 190px; height: 36px; border-radius: 6px;"></span>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-12 col-md-5">
                            <div class="card border mb-2 h-100 p-2">
                                <span class="skeleton-shimmer mb-2" style="width: 120px; height: 18px;"></span>
                                <span class="skeleton-shimmer mb-1" style="width: 80%; height: 14px;"></span>
                                <span class="skeleton-shimmer mb-1" style="width: 60%; height: 14px;"></span>
                                <span class="skeleton-shimmer mb-1" style="width: 90%; height: 14px;"></span>
                                <span class="skeleton-shimmer" style="width: 70%; height: 14px;"></span>
                            </div>
                        </div>
                        <div class="col-12 col-md-7">
                            <div class="card border mb-2 h-100 p-2">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="skeleton-shimmer" style="width: 130px; height: 18px;"></span>
                                    <span class="skeleton-shimmer" style="width: 70px; height: 18px;"></span>
                                </div>
                                <span class="skeleton-shimmer mb-1" style="width: 100%; height: 28px;"></span>
                                <span class="skeleton-shimmer mb-1" style="width: 100%; height: 28px;"></span>
                                <span class="skeleton-shimmer mb-2" style="width: 100%; height: 28px;"></span>
                                <div class="pt-1 border-top d-flex justify-content-between">
                                    <span class="skeleton-shimmer" style="width: 80px; height: 20px;"></span>
                                    <span class="skeleton-shimmer" style="width: 100px; height: 24px;"></span>
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
        document.getElementById('orders-list-pane').classList.remove('d-none');
        document.getElementById('order-workspace-pane').classList.add('d-none');
    }

    // Close Details Panel
    function closeOrderDetail() {
        document.querySelectorAll('.order-card').forEach(c => c.classList.remove('active-card'));
        const emptyHtml = `
            <div class="card shadow-sm border-0 order-workspace-empty flex-grow-1">
                <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-3 p-md-5 text-muted">
                    <h5 class="fw-bold text-dark mb-1">{{ __('order::general.no_order_selected', ['default' => 'No Order Selected']) }}</h5>
                    <p class="mb-0 text-muted">{{ __('order::general.select_order_prompt', ['default' => 'Click any order card from the left feed to view order details.']) }}</p>
                </div>
            </div>
        `;
        $('#order-workspace-pane').html(emptyHtml);
        if (typeof feather !== 'undefined') feather.replace();

        if (window.innerWidth < 992) {
            showMobileOrdersList();
        }
    }

    // Search filter in feed
    function filterOrdersList() {
        const query = document.getElementById('order-search').value.toLowerCase();
        document.querySelectorAll('.order-card').forEach(card => {
            const data = card.getAttribute('data-search') || '';
            card.style.display = data.includes(query) ? 'block' : 'none';
        });
    }

    // Status filter pills
    function setLiveStatusFilter(status, btn) {
        document.querySelectorAll('.filter-pill').forEach(b => {
            b.classList.remove('btn-primary', 'active');
            b.classList.add('btn-outline-secondary');
        });
        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('btn-primary', 'active');

        document.querySelectorAll('.order-card').forEach(card => {
            if (status === 'all' || card.getAttribute('data-status-id') === status) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
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
        submitStatusUpdate(orderId, 4, select.value); // Status 4 = On The Way
    }

    // Audio Notification Toggle & Web Audio Synth
    function toggleSound() {
        soundEnabled = !soundEnabled;
        const text = document.getElementById('sound-text');
        const btn = document.getElementById('sound-btn');
        const iconOn = document.getElementById('icon-sound-on');
        const iconOff = document.getElementById('icon-sound-off');
        const onText = "{{ __('order::general.notification_on', ['default' => 'Notification: ON']) }}";
        const offText = "{{ __('order::general.notification_off', ['default' => 'Notification: OFF']) }}";

        if (text) text.innerText = soundEnabled ? onText : offText;

        if (btn) {
            btn.classList.toggle('btn-outline-primary', soundEnabled);
            btn.classList.toggle('btn-outline-secondary', !soundEnabled);
        }

        if (iconOn && iconOff) {
            iconOn.classList.toggle('d-none', !soundEnabled);
            iconOff.classList.toggle('d-none', soundEnabled);
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
