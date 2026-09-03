@php
    $appName = config('app.name', 'Petora');
@endphp
<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item me-auto">
                <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                    <span class="brand-logo">
                        <svg viewBox="0 0 139 95" version="1.1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" height="24">
                            <defs>
                                <linearGradient id="linearGradient-1" x1="100%" y1="10.5120544%" x2="50%"
                                    y2="89.4879456%">
                                    <stop stop-color="#000000" offset="0%"></stop>
                                    <stop stop-color="#FFFFFF" offset="100%"></stop>
                                </linearGradient>
                                <linearGradient id="linearGradient-2" x1="64.0437835%" y1="46.3276743%" x2="37.373316%"
                                    y2="100%">
                                    <stop stop-color="#EEEEEE" stop-opacity="0" offset="0%"></stop>
                                    <stop stop-color="#FFFFFF" offset="100%"></stop>
                                </linearGradient>
                            </defs>
                            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Artboard" transform="translate(-400.000000, -178.000000)">
                                    <g id="Group" transform="translate(400.000000, 178.000000)">
                                        <path class="text-primary" id="Path"
                                            d="M-5.68434189e-14,2.84217094e-14 L39.1816085,2.84217094e-14 L69.3453773,32.2519224 L101.428699,2.84217094e-14 L138.784583,2.84217094e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L6.71554594,44.4188507 C2.46876683,39.9813776 0.345377275,35.1089553 0.345377275,29.8015838 C0.345377275,24.4942122 0.230251516,14.560351 -5.68434189e-14,2.84217094e-14 Z"
                                            style="fill:currentColor"></path>
                                        <path id="Path1"
                                            d="M69.3453773,32.2519224 L101.428699,1.42108547e-14 L138.784583,1.42108547e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L32.8435758,70.5039241 L69.3453773,32.2519224 Z"
                                            fill="url(#linearGradient-1)" opacity="0.2"></path>
                                        <polygon id="Path-2" fill="#000000" opacity="0.049999997"
                                            points="69.3922914 32.4202615 32.8435758 70.5039241 54.0490008 16.1851325">
                                        </polygon>
                                        <polygon id="Path-21" fill="#000000" opacity="0.099999994"
                                            points="69.3922914 32.4202615 32.8435758 70.5039241 58.3683556 20.7402338">
                                        </polygon>
                                        <polygon id="Path-3" fill="url(#linearGradient-2)" opacity="0.099999994"
                                            points="101.428699 0 83.0667527 94.1480575 130.378721 47.0740288">
                                        </polygon>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </span>

                    <h2 class="brand-text">{{ $appName }}</h2>
                </a>
            </li>
            <li class="nav-item nav-toggle">
                <a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse">
                    <i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i>
                    <i class="d-none d-xl-block collapse-toggle-icon font-medium-4 text-primary" data-feather="disc"
                        data-ticon="disc"></i>
                </a>
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="nav-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                    <i data-feather="home"></i>
                    <span class="menu-item text-truncate">{{ __('common::sidebar.dashboard') }}</span>
                </a>
            </li>

            <li class="navigation-header">
                <span>{{ __('common::sidebar.apps_and_pages') }}</span>
                <i data-feather="more-horizontal"></i>
            </li>

            @can('Index-admin')
            <li class="nav-item {{ Route::is('admin.admins.*', 'admin.users.*') ? 'sidebar-group-active open' : '' }}">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="users"></i>
                    <span class="menu-title text-truncate">{{ __('common::sidebar.user_management') }}</span>
                </a>
                <ul class="menu-content">
                    <li class="nav-item {{ Route::is('admin.admins.*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.admins.index') }}">
                            <i data-feather="user-check"></i>
                            <span class="menu-item text-truncate">{{ __('common::sidebar.admins') }}</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan

            @canany(['Index-company', 'Index-store'])
            <li
                class="nav-item {{ Route::is('admin.company.*', 'admin.store.*') ? 'sidebar-group-active open' : '' }}">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="briefcase"></i>
                    <span class="menu-title text-truncate">{{ __('common::sidebar.store_management') }}</span>
                </a>
                <ul class="menu-content">
                    @can('Index-company')
                    <li class="nav-item {{ Route::is('admin.company.*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.company.index') }}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate">{{ __('common::sidebar.companies') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('Index-store')
                    <li class="nav-item {{ Route::is('admin.store.*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.store.index') }}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate">{{ __('store::general.stores') }}</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcanany

            @canany(['Index-clinic', 'Index-doctor', 'Index-service'])
            <li
                class="nav-item {{ Route::is('admin.clinic.*', 'admin.doctor.*', 'admin.service.*') ? 'sidebar-group-active open' : '' }}">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="activity"></i>
                    <span class="menu-title text-truncate">{{ __('common::sidebar.clinic_management') ?? 'ادارة العيادات' }}</span>
                </a>
                <ul class="menu-content">
                    @can('Index-clinic')
                    <li class="nav-item {{ Route::is('admin.clinic.*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.clinic.index') }}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate">{{ __('clinic::general.clinics') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('Index-doctor')
                    <li class="nav-item {{ Route::is('admin.doctor.*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.doctor.index') }}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate">{{ __('doctor::general.doctors') ?? 'الاطباء' }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('Index-service')
                    <li class="nav-item {{ Route::is('admin.service.*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.service.index') }}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate">{{ __('service::general.services') ?? 'الخدمات' }}</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcanany

            @canany(['Index-category', 'Index-product'])
            <li class="nav-item {{ Route::is('admin.category.*', 'admin.product.*') ? 'sidebar-group-active open' : '' }}">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="grid"></i>
                    <span class="menu-title text-truncate">{{ __('common::sidebar.product_management') ?? 'ادارة المنتجات' }}</span>
                </a>
                <ul class="menu-content">
                    @can('Index-category')
                    <li class="nav-item {{ Route::is('admin.category.*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.category.index') }}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate">{{ __('category::general.categories') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('Index-product')
                    <li class="nav-item {{ Route::is('admin.product.*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.product.index') }}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate">{{ __('product::general.products') ?? 'المنتجات' }}</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcanany

            @canany(['Index-pet', 'Index-pettype'])
            <li class="nav-item {{ Route::is('admin.pet.*', 'admin.pet_type.*') ? 'sidebar-group-active open' : '' }}">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="heart"></i>
                    <span class="menu-title text-truncate">{{ __('pet::general.pet_management') ?? 'ادارة الحيوانات الأليفة' }}</span>
                </a>
                <ul class="menu-content">
                    @can('Index-pettype')
                    <li class="nav-item {{ Route::is('admin.pet_type.*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.pet_type.index') }}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate">{{ __('pet::general.pet_types') ?? 'أنواع الحيوانات' }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('Index-pet')
                    <li class="nav-item {{ Route::is('admin.pet.*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.pet.index') }}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate">{{ __('pet::general.pets') ?? 'الحيوانات الأليفة' }}</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcanany

            @can('Index-country')
            <li class="navigation-header">
                <span>{{ __('common::sidebar.basic_data') }}</span>
                <i data-feather="more-horizontal"></i>
            </li>

            <li
                class="nav-item {{ Route::is('admin.countries.*', 'admin.cities.*', 'admin.zones.*') ? 'sidebar-group-active open' : '' }}">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="map"></i>
                    <span class="menu-title text-truncate">{{ __('common::sidebar.locations') }}</span>
                </a>
                <ul class="menu-content">
                    <li class="nav-item {{ Route::is('admin.countries.*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.countries.index') }}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate">{{ __('country::general.countries') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{ Route::is('admin.cities.*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.cities.index') }}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate">{{ __('country::general.cities') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{ Route::is('admin.zones.*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.zones.index') }}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate">{{ __('country::general.zones') }}</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan

            @role(\Modules\Admin\Enums\AdminRole::SuperAdmin->value)
            <li class="navigation-header">
                <span>{{ __('common::sidebar.system_tools') }}</span>
                <i data-feather="more-horizontal"></i>
            </li>
            <li class="nav-item">
                <a class="d-flex align-items-center" href="{{ url('admin/telescope') }}" target="_blank">
                    <i data-feather="monitor"></i>
                    <span class="menu-title text-truncate">Telescope</span>
                </a>
            </li>
            @endrole
        </ul>
    </div>
</div>
<!-- END: Main Menu-->
