@php
    $route = Route::current()->getName();
    $admin = auth('admin')->user();
    $isTheme6 = $admin->hasRole('Branch Manager') && optional(optional($admin->branch)->settings)->theme == 6;
@endphp
<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item me-auto"><a class="navbar-brand" href="{{ url('admin/dashboard') }}">
                    @php
                        $isBranchManager = $admin->hasRole('Branch Manager');
                        $branchLogo = null;

                        if ($isBranchManager) {
                            $branch = $admin->branch ?? null;
                            $logo = $branch->settings->logo ?? null;
                            $image = $branch->image ?? null;
                            if (!empty($logo)) {
                                $branchLogo = $logo;
                            } elseif (!empty($image)) {
                                $branchLogo = $image;
                            }
                        }
                    @endphp
                    <span class="brand-logo">
                        @if ($isBranchManager && !empty($branchLogo))
                            <img src="{{ $branchLogo }}" alt="Branch Logo" style="height: 40px; max-width: 100%;">
                        @else
                            <svg viewBox="0 0 139 95" version="1.1" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" height="24">
                                <defs>
                                    <linearGradient id="linearGradient-1" x1="100%" y1="10.5120544%" x2="50%"
                                        y2="89.4879456%">
                                        <stop stop-color="#000000" offset="0%"></stop>
                                        <stop stop-color="#FFFFFF" offset="100%"></stop>
                                    </linearGradient>
                                    <linearGradient id="linearGradient-2" x1="64.0437835%" y1="46.3276743%"
                                        x2="37.373316%" y2="100%">
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
                        @endif
                    </span>

                    <h2 class="brand-text">
                        @if ($admin->hasRole('Branch Manager'))
                            {{ $admin->branch->getTranslations('title')['ar'] ?? 'Home' }}
                        @else
                            QMenu
                        @endif
                    </h2>
                </a></li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse"><i
                        class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i
                        class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc"
                        data-ticon="disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li
                class="{{ $isBranchManager ? ($route == 'admin.statistics' ? 'active' : '') : ($route == 'admin.dashboard' ? 'active' : '') }}">
                <a class="d-flex align-items-center"
                    href="{{ $isBranchManager ? route('admin.statistics') : route('admin.dashboard') }}"><i
                        data-feather="home"></i><span class="menu-item text-truncate"
                        data-i18n="eCommerce">الرئيسية</span></a>
            </li>
            <li class=" navigation-header"><span data-i18n="Apps &amp; Pages">Apps &amp; Pages</span><i
                    data-feather="more-horizontal"></i>
            </li>



            @if (!$isTheme6)
                @if (auth('admin')->user()->hasRole('Branch Manager'))
                    @php
                        $branch_id = auth('admin')->user()->branch_id;
                    @endphp
                    <li class="nav-item {{ $route == 'admin.dashboard' ? 'active' : '' }}"><a
                            class="d-flex align-items-center" href="{{ url('admin/dashboard') }}"><i
                                data-feather="bell"></i><span class="menu-title text-truncate" data-i18n="Email">الطلبات
                                الجديدة</span></a>
                    </li>
                    <li
                        class="nav-item {{ in_array($route, ['branches.edit', 'branch.settings', 'branch.qr']) ? 'sidebar-group-active open' : '' }}">
                        <a class="d-flex align-items-center" href="#">
                            <i data-feather="map-pin"></i>
                            <span class="menu-title text-truncate" data-i18n="Invoice">بيانات الفرع</span>
                        </a>
                        <ul class="menu-content">
                            <li>
                                <a class="d-flex align-items-center"
                                    href="{{ url('admin/branches/' . $branch_id . '/edit') }}">
                                    <i data-feather="edit"></i>
                                    <span class="menu-item text-truncate" data-i18n="EditBranch">تعديل بيانات
                                        الفرع</span>
                                </a>
                            </li>

                            <li class="nav-item {{ $route == 'branch.settings' ? 'active' : '' }}"><a
                                    class="d-flex align-items-center"
                                    href="{{ route('branch.settings', ['id' => $branch_id]) }}"><i
                                        data-feather="settings"></i><span class="menu-title text-truncate"
                                        data-i18n="Settings">الاعدادات</span></a>
                            </li>
                            <li class="nav-item {{ $route == 'branch.qr' ? 'active' : '' }}"><a
                                    class="d-flex align-items-center"
                                    href="{{ route('branch.qr', ['id' => $branch_id]) }}"><i
                                        data-feather="code"></i><span class="menu-title text-truncate"
                                        data-i18n="QRCode">QR كود</span></a>
                            </li>
                        </ul>
                    </li>
                @endif









                @if (auth()->user()->can('Index-admin') ||
                        auth()->user()->can('Index-branch') ||
                        auth()->user()->can('Index-driver') ||
                        auth()->user()->can('Index-role') ||
                        auth()->user()->can('Index-employee') ||
                        auth()->user()->can('Index-client'))
                    <li class=" nav-item "><a class="d-flex align-items-center" href="#"><i
                                data-feather="users"></i><span class="menu-title text-truncate"
                                data-i18n="Invoice">ادارة العضويات</span></a>
                        <ul class="menu-content">
                            @can('Index-admin')
                                <li class="nav-item {{ $route == 'admins.index' ? 'active' : '' }}"><a
                                        class="d-flex align-items-center" href="{{ url('admin/admins') }}"><i
                                            data-feather="user-check"></i><span class="menu-title text-truncate"
                                            data-i18n="Email">المديرين</span></a>
                                </li>
                            @endcan
                            @can('Index-branch')
                                <li class="nav-item {{ $route == 'branches.index' ? 'active' : '' }}"><a
                                        class="d-flex align-items-center" href="{{ url('admin/branches') }}"><i
                                            data-feather="map-pin"></i><span class="menu-title text-truncate"
                                            data-i18n="Email">الفروع</span></a>
                                </li>
                                <li class="nav-item {{ $route == 'registrations.index' ? 'active' : '' }}"><a
                                        class="d-flex align-items-center" href="{{ url('admin/registrations') }}"><i
                                            data-feather="list"></i><span class="menu-title text-truncate"
                                            data-i18n="Email">طلبات الفروع</span></a>
                                </li>
                            @endcan
                            {{-- @can('Index-driver')
            <li class="nav-item {{$route == 'drivers.index'?'active' :''}}"><a class="d-flex align-items-center" href="{{url('admin/drivers')}}"><i data-feather="user-check"></i><span class="menu-title text-truncate" data-i18n="Email">السائقين</span></a>
            </li>
            @endcan --}}
                            @can('Index-role')
                                <li class="nav-item {{ $route == 'roles.index' ? 'active' : '' }}"><a
                                        class="d-flex align-items-center" href="{{ url('admin/roles') }}"><i
                                            data-feather='shield'></i><span class="menu-title text-truncate"
                                            data-i18n="Email">الوظائف</span></a>
                                </li>
                            @endcan

                            @can('Index-employee')
                                <li class="nav-item {{ $route == 'employees.index' ? 'active' : '' }}"><a
                                        class="d-flex align-items-center" href="{{ url('admin/employees') }}"><i
                                            data-feather="briefcase"></i><span class="menu-title text-truncate"
                                            data-i18n="Email">الموظفين</span></a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif

                @can('Index-order')
                    <li class=" nav-item {{ $route == 'orders.index' ? 'sidebar-group-active open' : '' }} "><a
                            class="d-flex align-items-center" href="#"><i data-feather="shopping-bag"></i><span
                                class="menu-title text-truncate" data-i18n="Invoice">الطلبات</span></a>
                        <ul class="menu-content">
                            <li class="@if (!app('request')->input('order_status_id') && $route == 'orders.index') active @endif">
                                <a class="d-flex align-items-center" href="{{ url('admin/orders') }}"><i
                                        data-feather="list"></i><span class="menu-item text-truncate" data-i18n="List">كل
                                        الطلبات</span></a>
                            </li>
                            <li class="@if (app('request')->input('order_status_id') && app('request')->input('order_status_id') == 1) active @endif">
                                <a class="d-flex align-items-center"
                                    href="{{ url('admin/orders?order_status_id=1') }}"><i data-feather="bell"></i><span
                                        class="menu-item text-truncate" data-i18n="Preview">
                                        الطلبات الجديدة</span></a>
                            </li>
                            <li class="@if (app('request')->input('order_status_id') && app('request')->input('order_status_id') == 2) active @endif">
                                <a class="d-flex align-items-center"
                                    href="{{ url('admin/orders?order_status_id=2') }}"><i data-feather="clock"></i><span
                                        class="menu-item text-truncate" data-i18n="Edit">مقبول وجاري التحضير</span></a>
                            </li>
                            <li class="@if (app('request')->input('order_status_id') && app('request')->input('order_status_id') == 3) active @endif">
                                <a class="d-flex align-items-center"
                                    href="{{ url('admin/orders?order_status_id=3') }}"><i
                                        data-feather="package"></i><span class="menu-item text-truncate"
                                        data-i18n="Add">الطلب
                                        جاهز للتسليم</span></a>
                            </li>
                            <li class="@if (app('request')->input('order_status_id') && app('request')->input('order_status_id') == 4) active @endif">
                                <a class="d-flex align-items-center"
                                    href="{{ url('admin/orders?order_status_id=4') }}"><i
                                        data-feather="check-circle"></i><span class="menu-item text-truncate"
                                        data-i18n="Add">تم
                                        التسليم بنجاح</span></a>
                            </li>
                            <li class="@if (app('request')->input('order_status_id') && app('request')->input('order_status_id') == 5) active @endif">
                                <a class="d-flex align-items-center"
                                    href="{{ url('admin/orders?order_status_id=5') }}"><i
                                        data-feather="x-circle"></i><span class="menu-item text-truncate"
                                        data-i18n="Add">فشل
                                        الطلب</span></a>
                            </li>
                            <li class="@if (app('request')->input('order_status_id') && app('request')->input('order_status_id') == 6) active @endif">
                                <a class="d-flex align-items-center"
                                    href="{{ url('admin/orders?order_status_id=6') }}"><i data-feather="slash"></i><span
                                        class="menu-item text-truncate" data-i18n="Add">تم
                                        الغاء الطلب</span></a>
                            </li>
                        </ul>
                    </li>
                @endcan

                @if (auth()->user()->can('Index-category') || auth()->user()->can('Index-product'))
                    <li class=" nav-item "><a class="d-flex align-items-center" href="#"><i
                                data-feather="box"></i><span class="menu-title text-truncate"
                                data-i18n="Invoice">ادارة المنتجات</span></a>
                        <ul class="menu-content">
                            @can('Index-category')
                                <li class="nav-item {{ $route == 'categories.index' ? 'active' : '' }}"><a
                                        class="d-flex align-items-center" href="{{ url('admin/categories') }}"><i
                                            data-feather="grid"></i><span class="menu-title text-truncate"
                                            data-i18n="Email">الاقسام</span></a>
                                </li>
                            @endcan

                            @can('Index-product')
                                <li class="nav-item {{ $route == 'products.index' ? 'active' : '' }}"><a
                                        class="d-flex align-items-center" href="{{ url('admin/products') }}"><i
                                            data-feather="package"></i><span class="menu-title text-truncate"
                                            data-i18n="Email">المنتجات</span></a>
                                </li>
                            @endcan

                            @can('Index-addon')
                                <li class="nav-item {{ $route == 'addons.index' ? 'active' : '' }}"><a
                                        class="d-flex align-items-center" href="{{ url('admin/addons') }}"><i
                                            data-feather="plus-circle"></i><span class="menu-title text-truncate"
                                            data-i18n="Email">الاضافات</span></a>
                                </li>
                            @endcan
                            @can('Index-side')
                                <li class="nav-item {{ $route == 'sides.index' ? 'active' : '' }}"><a
                                        class="d-flex align-items-center" href="{{ url('admin/sides') }}"><i
                                            data-feather="coffee"></i><span class="menu-title text-truncate"
                                            data-i18n="Email">الاطباق الجانبية</span></a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
            @endif

            @if ($isTheme6)
                @php $branch_id = auth('admin')->user()->branch_id; @endphp
                <li
                    class="nav-item {{ in_array($route, ['branches.edit', 'branch.settings', 'branch.qr']) ? 'sidebar-group-active open' : '' }}">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather="map-pin"></i>
                        <span class="menu-title text-truncate">بيانات الفرع</span>
                    </a>
                    <ul class="menu-content">
                        <li>
                            <a class="d-flex align-items-center"
                                href="{{ url('admin/branches/' . $branch_id . '/edit') }}">
                                <i data-feather="edit"></i>
                                <span class="menu-item text-truncate">تعديل بيانات الفرع</span>
                            </a>
                        </li>
                        <li>
                            <a class="d-flex align-items-center"
                                href="{{ route('branch.settings', ['id' => $branch_id]) }}">
                                <i data-feather="settings"></i>
                                <span class="menu-item text-truncate">الاعدادات</span>
                            </a>
                        </li>
                        <li>
                            <a class="d-flex align-items-center"
                                href="{{ route('branch.qr', ['id' => $branch_id]) }}">
                                <i data-feather="smartphone"></i>
                                <span class="menu-item text-truncate">QR كود</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            @if ($isTheme6 || auth('admin')->user()->hasRole('Super Admin'))
                <li class="nav-item {{ $route == 'galleries.index' ? 'active' : '' }}"><a
                        class="d-flex align-items-center" href="{{ url('admin/galleries') }}"><i
                            data-feather='image'></i><span class="menu-title text-truncate" data-i18n="Email">صور
                            المنيو</span></a>
                </li>
            @endif

            @if (auth('admin')->user()->hasRole('Super Admin'))
                <li class="nav-item {{ $route == 'import.index' ? 'active' : '' }}"><a
                        class="d-flex align-items-center" href="{{ route('import.index') }}"><i
                            data-feather="upload"></i><span class="menu-title text-truncate"
                            data-i18n="Email">استيراد المنيو</span></a>
                </li>
            @endif

            @if (!$isTheme6)

                @if (auth()->user()->can('Index-coupon') || auth('admin')->user()->hasRole('Branch Manager'))
                    <li
                        class="nav-item {{ in_array($route, ['coupons.index', 'branch.discounts']) ? 'sidebar-group-active open' : '' }}">
                        <a class="d-flex align-items-center" href="#">
                            <i data-feather="percent"></i>
                            <span class="menu-title text-truncate" data-i18n="Discounts">الخصومات</span>
                        </a>
                        <ul class="menu-content">
                            @can('Index-coupon')
                                <li class="nav-item {{ $route == 'coupons.index' ? 'active' : '' }}"><a
                                        class="d-flex align-items-center" href="{{ url('admin/coupons') }}"><i
                                            data-feather="tag"></i><span class="menu-item text-truncate" data-i18n="Email">كوبونات الخصم</span></a>
                                </li>
                            @endcan

                            @if (auth('admin')->user()->hasRole('Branch Manager'))
                                @php $branch_id = auth('admin')->user()->branch_id; @endphp
                                <li class="nav-item {{ $route == 'branch.discounts' ? 'active' : '' }}"><a
                                        class="d-flex align-items-center" href="{{ route('branch.discounts', ['id' => $branch_id]) }}"><i
                                            data-feather="percent"></i><span class="menu-item text-truncate" data-i18n="Email">خصم قيمة الطلب</span></a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @can('Edit-branch')
                    <li class="nav-item {{ $route == 'branches.offer' ? 'active' : '' }}"><a
                            class="d-flex align-items-center" href="{{ url('admin/branches/offer') }}"><i
                                data-feather="gift"></i><span class="menu-title text-truncate"
                                data-i18n="Email">العرض</span></a>
                    </li>
                @endcan

                <li class="nav-item {{ $route == 'reviews.index' ? 'active' : '' }}"><a
                        class="d-flex align-items-center" href="{{ url('admin/reviews') }}"><i
                            data-feather='star'></i><span class="menu-title text-truncate" data-i18n="Email">تقييمات
                            العملاء</span></a>
                </li>
                {{-- @can('Create-notification')
                <li class="nav-item {{ $route == 'notifications.create' ? 'active' : '' }}"><a
                        class="d-flex align-items-center" href="{{ url('admin/notifications/create') }}"><i
                            data-feather='users'></i><span class="menu-title text-truncate" data-i18n="Email">ارسال
                            اشعار</span></a>
                </li>
            @endcan --}}

                @can('Index-report')
                    <li class=" nav-item "><a class="d-flex align-items-center" href="#"><i
                                data-feather="bar-chart-2"></i><span class="menu-title text-truncate"
                                data-i18n="Invoice">التقارير</span></a>
                        <ul class="menu-content">
                            {{-- <li class="nav-item {{ $route == 'reports.clients' ? 'active' : '' }}"><a
                                class="d-flex align-items-center" href="{{ url('admin/clientReport') }}"><i
                                    data-feather='user'></i><span class="menu-title text-truncate"
                                    data-i18n="Email">العملاء</span></a>
                        </li> --}}
                            {{-- <li class="nav-item {{ $route == 'reports.drivers' ? 'active' : '' }}"><a
                                class="d-flex align-items-center" href="{{ url('admin/driverReport') }}"><i
                                    data-feather='user'></i><span class="menu-title text-truncate"
                                    data-i18n="Email">السائقين</span></a>
                        </li> --}}
                            <li class="nav-item {{ $route == 'reports.products' ? 'active' : '' }}"><a
                                    class="d-flex align-items-center" href="{{ url('admin/productReport') }}"><i
                                        data-feather="trending-up"></i><span class="menu-title text-truncate"
                                        data-i18n="Email">الاصناف الاعلى مبيعا</span></a>
                            </li>
                            <li class="nav-item {{ $route == 'reports.categories' ? 'active' : '' }}"><a
                                    class="d-flex align-items-center" href="{{ url('admin/categoriesReport') }}"><i
                                        data-feather="pie-chart"></i><span class="menu-title text-truncate"
                                        data-i18n="Email">الاقسام الاعلى مبيعا</span></a>
                            </li>

                            <li class="nav-item {{ $route == 'reports.branches' ? 'active' : '' }}"><a
                                    class="d-flex align-items-center" href="{{ url('admin/branchesReport') }}"><i
                                        data-feather="map-pin"></i><span class="menu-title text-truncate"
                                        data-i18n="Email">تقارير الفروع</span></a>
                            </li>
                            <li class="nav-item {{ $route == 'reports.ordersReport' ? 'active' : '' }}"><a
                                    class="d-flex align-items-center" href="{{ url('admin/ordersReport') }}"><i
                                        data-feather="shopping-bag"></i><span class="menu-title text-truncate"
                                        data-i18n="Email">تقارير الطلبات</span></a>
                            </li>
                        </ul>
                    </li>
                @endcan


                @if (auth()->user()->can('Index-ordermethod') ||
                        auth()->user()->can('Index-paymentmethods') ||
                        auth()->user()->can('Index-orderstatus'))
                    <li class=" nav-item "><a class="d-flex align-items-center" href="#"><i
                                data-feather="database"></i><span class="menu-title text-truncate"
                                data-i18n="Invoice">بيانات اساسية</span></a>
                        <ul class="menu-content">
                            @can('Index-ordermethod')
                                <li class="nav-item {{ $route == 'ordermethods.index' ? 'active' : '' }}"><a
                                        class="d-flex align-items-center" href="{{ url('admin/ordermethods') }}"><i
                                            data-feather="navigation"></i><span class="menu-title text-truncate"
                                            data-i18n="Email">طرق الطلب</span></a>
                                </li>
                            @endcan

                            @can('Index-orderstatus')
                                <li class="nav-item {{ $route == 'orderstatus.index' ? 'active' : '' }}"><a
                                        class="d-flex align-items-center" href="{{ url('admin/orderstatus') }}"><i
                                            data-feather="flag"></i><span class="menu-title text-truncate"
                                            data-i18n="Email">حالات الطلب</span></a>
                                </li>
                            @endcan

                            @can('Index-paymentmethods')
                                <li class="nav-item {{ $route == 'paymentmethods.index' ? 'active' : '' }}"><a
                                        class="d-flex align-items-center" href="{{ url('admin/paymentmethods') }}"><i
                                            data-feather="credit-card"></i><span class="menu-title text-truncate"
                                            data-i18n="Email">طرق الدفع</span></a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif

                @can('Index-package')
                    <li class="nav-item {{ $route == 'packages.index' ? 'active' : '' }}"><a
                            class="d-flex align-items-center" href="{{ url('admin/packages') }}"><i
                                data-feather="package"></i><span class="menu-title text-truncate"
                                data-i18n="Email">الباقات</span></a>
                    </li>
                @endcan

                @if (auth()->user()->can('Index-setting'))
                    <li class=" nav-item "><a class="d-flex align-items-center" href="#"><i
                                data-feather="settings"></i><span class="menu-title text-truncate"
                                data-i18n="Invoice">الاعدادات</span></a>
                        <ul class="menu-content">
                            @can('Index-setting')
                                <li class="nav-item {{ $route == 'setting.index' ? 'active' : '' }}"><a
                                        class="d-flex align-items-center" href="{{ url('admin/setting') }}"><i
                                            data-feather='settings'></i><span class="menu-title text-truncate"
                                            data-i18n="Email">الاعدادات العامة</span></a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif



                @can('Index-log')
                    <li class="nav-item {{ $route == 'logs.index' ? 'active' : '' }}"><a
                            class="d-flex align-items-center" href="{{ url('admin/logs') }}"><i
                                data-feather="file-text"></i><span class="menu-title text-truncate"
                                data-i18n="Email">السجل</span></a>
                    </li>
                @endcan

            @endif {{-- end !$isTheme6 --}}

        </ul>

    </div>
</div>
<!-- END: Main Menu-->
