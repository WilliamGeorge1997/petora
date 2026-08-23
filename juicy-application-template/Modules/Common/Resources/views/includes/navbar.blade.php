{{-- @php
    $new_notifications = Auth::user()->notifications()->latest()->whereNull('read_at')->whereRelation('order','order_status_id','!=',6)->take(10);
    $front_branch_notifications = Auth::user()->notifications()->latest()->whereNull('read_at')->whereRelation('order','order_status_id',6)->take(10);
@endphp --}}
@php
    $call_waiter_count = 0;
    $call_waiters = collect();

    if (!empty(Auth::user()['branch_id'])) {
        $call_waiter_query = \Modules\Branch\Entities\CallWaiter::where('status', 'pending')->where(
            'branch_id',
            Auth::user()['branch_id'],
        );
        $call_waiter_count = $call_waiter_query->count();
        $call_waiters = $call_waiter_query->latest()->limit(10)->get();
    }
@endphp
<!-- BEGIN: Header-->
<nav
    class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-xxl">
    <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon"
                            data-feather="menu"></i></a></li>
            </ul>
        </div>
        <ul class="nav navbar-nav align-items-center ms-auto">
            <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i class="ficon"
                        data-feather="moon"></i></a></li>

            @if (!empty(Auth::user()['branch_id']))
                <li class="nav-item dropdown dropdown-notification me-25">
                    <a class="nav-link" href="#" data-bs-toggle="dropdown">
                        <i class="ficon" data-feather="bell"></i>
                        <span
                            class="badge rounded-pill bg-danger badge-up call-waiter-count {{ $call_waiter_count > 0 ? 'pulse-point' : '' }}">{{ $call_waiter_count }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
                        <li class="scrollable-container media-list" id="call-waiter-list">
                            <div class="list-item d-flex align-items-center">
                                <h6 class="fw-bolder me-auto mb-0">طلبات النادل</h6>
                            </div>
                            @foreach ($call_waiters as $cw)
                                <div class="list-item d-flex align-items-start call-waiter-item-{{ $cw->id }}">
                                    <div class="me-1">
                                        <div class="avatar bg-light-info">
                                            <div class="avatar-content"><i class="avatar-icon" data-feather="user"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading"><span class="fw-bolder">طلب نادل لطاولة
                                                {{ $cw->table }}</span></p>
                                    </div>
                                    <div class="ms-1">
                                        <button class="btn btn-sm btn-icon btn-success btn-resolve-waiter"
                                            data-id="{{ $cw->id }}">
                                            ✔
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </li>
                    </ul>
                </li>
            @endif


            {{-- <li class="nav-item dropdown dropdown-notification me-25"><a class="nav-link" href="#" data-bs-toggle="dropdown"><i class="ficon" data-feather="archive"></i><span class="badge rounded-pill bg-danger badge-up">{{$front_branch_notifications->count()}}</span></a> --}}
            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">

                <li class="scrollable-container media-list">
                    <div class="list-item d-flex align-items-center">
                        <h6 class="fw-bolder me-auto mb-0">اشعارات الطلبات امام الفرع</h6>
                        {{-- <div class="form-check form-check-primary form-switch">
                                <input class="form-check-input" id="systemNotification" type="checkbox" checked="">
                                <label class="form-check-label" for="systemNotification"></label>
                            </div> --}}
                    </div>
                    {{-- @foreach ($front_branch_notifications->get() as $new_notification)

                        <a class="d-flex" href="{{url('admin/notification/read/'.$new_notification->id)}}">
                            <div class="list-item d-flex align-items-start">
                                <div class="me-1">
                                    <div class="avatar bg-light-success">
                                        <div class="avatar-content"><i class="avatar-icon" data-feather="check"></i></div>
                                    </div>
                                </div>
                                <div class="list-item-body flex-grow-1">
                                    <p class="media-heading"><span class="fw-bolder">{{$new_notification->title}}</span></p><small class="notification-text"> {{$new_notification->description}}</small>
                                </div>
                            </div>
                        </a>
                        @endforeach --}}
                </li>
                {{-- <li class="dropdown-menu-footer"><a class="btn btn-primary w-100" href="#">Read all notifications</a></li> --}}
            </ul>
            </li>


            {{-- <li class="nav-item dropdown dropdown-notification me-25"><a class="nav-link" href="#" data-bs-toggle="dropdown"><i class="ficon" data-feather="bell"></i><span class="badge rounded-pill bg-danger badge-up" id="new_notification_count">{{$new_notifications->count()}}</span></a> --}}
            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">

                <li class="scrollable-container media-list">
                    <div class="list-item d-flex align-items-center">
                        <h6 class="fw-bolder me-auto mb-0">اشعارات الطلبات الجديدة</h6>
                        {{-- <div class="form-check form-check-primary form-switch">
                                <input class="form-check-input" id="systemNotification" type="checkbox" checked="">
                                <label class="form-check-label" for="systemNotification"></label>
                            </div> --}}
                    </div>
                    {{-- @foreach ($new_notifications->get() as $new_notification)

                        <a class="d-flex" href="{{url('admin/notification/read/'.$new_notification->id)}}">
                            <div class="list-item d-flex align-items-start">
                                <div class="me-1">
                                    <div class="avatar bg-light-success">
                                        <div class="avatar-content"><i class="avatar-icon" data-feather="check"></i></div>
                                    </div>
                                </div>
                                <div class="list-item-body flex-grow-1">
                                    <p class="media-heading"><span class="fw-bolder">{{$new_notification->title}}</span></p><small class="notification-text"> {{$new_notification->description}}</small>
                                </div>
                            </div>
                        </a>
                        @endforeach --}}
                </li>
                {{-- <li class="dropdown-menu-footer"><a class="btn btn-primary w-100" href="#">Read all notifications</a></li> --}}
            </ul>
            </li>

            <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link"
                    id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <div class="user-nav d-sm-flex d-none"><span
                            class="user-name fw-bolder">{{ \Illuminate\Support\Facades\Auth::user()['name'] }}</span><span
                            class="user-status">Admin</span></div><span class="avatar"><img class="round"
                            alt="avatar" height="40" width="40"
                            @if (\Illuminate\Support\Facades\Auth::user()['image'] ?? null) src="{{ asset('') }}uploads/admin/{{ \Illuminate\Support\Facades\Auth::user()['image'] }}"
                        @else
                        src="{{ asset('') }}admin/images/portrait/small/avatar-s-11.jpg"><span class="avatar-status-online" @endif></span></span>

                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user"><a class="dropdown-item"
                        href="{{ url('admin/profile') }}"><i class="me-50" data-feather="user"></i> Profile</a>
                    <div class="dropdown-divider"></div><a class="dropdown-item" href="{{ route('admin.logout') }}"
                        onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();"><i
                            class="me-50" data-feather="power"></i> Logout</a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="GET" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>
<!-- END: Header-->
