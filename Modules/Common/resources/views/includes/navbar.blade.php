@php
    $admin = auth('admin')->user();
    $role = $admin?->roles->first();
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

            {{-- Language switch --}}
            <li class="nav-item dropdown dropdown-language">
                <a class="nav-link dropdown-toggle" id="dropdown-flag" href="#" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="flag-icon {{ app()->getLocale() == 'ar' ? 'flag-icon-sa' : 'flag-icon-us' }}"></i>
                    <span class="selected-language">{{ app()->getLocale() == 'ar' ? 'العربية' : 'English' }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-flag">
                    <a class="dropdown-item" href="{{ route('admin.lang.switch', 'en') }}" data-language="en">
                        <i class="flag-icon flag-icon-us"></i> English
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.lang.switch', 'ar') }}" data-language="ar">
                        <i class="flag-icon flag-icon-sa"></i> العربية
                    </a>
                </div>
            </li>

            {{-- Dark mode toggle --}}
            <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i class="ficon"
                        data-feather="moon"></i></a></li>

            {{-- User dropdown --}}
            <li class="nav-item dropdown dropdown-user">
                <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="#"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="user-nav d-sm-flex d-none">
                        <span class="user-name fw-bolder">{{ $admin->name }}</span>
                        <span class="user-status">{{ $role?->name }}</span>
                    </div>
                    <span class="avatar">
                        <img class="round" alt="avatar" height="40" width="40"
                            src="{{ $admin->image ?? asset('admin/images/portrait/small/avatar-s-11.jpg') }}">
                        <span class="avatar-status-online"></span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">
                    <a class="dropdown-item" href="#"><i class="me-50" data-feather="user"></i>
                        {{ __('common::navbar.profile') }}</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="me-50" data-feather="power"></i> {{ __('common::navbar.logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="GET" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>
<!-- END: Header-->
