@php
    $appName = config('app.name');
    $locale = app()->getLocale();
@endphp
<!DOCTYPE html>
<html class="loading" lang="{{ $locale }}" data-textdirection="{{ $locale == 'ar' ? 'rtl' : 'ltr' }}"
    dir="{{ $locale == 'ar' ? 'rtl' : 'ltr' }}">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description" content="{{ $appName }} Admin Panel">
    <meta name="keywords" content="{{ $appName }}">
    <meta name="author" content="Icon Tech Digital Solutions">
    <title>{{ $appName }} - @yield('title')</title>
    @include('common::includes.css')
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern navbar-floating footer-static" data-open="click"
    data-menu="vertical-menu-modern" data-col="" data-framework="laravel" data-asset-path="{{ asset('admin/') }}">


    @include('common::includes.navbar')


    @include('common::includes.sidebar')


    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
            </div>
            <div class="content-body">
                @if (session('success'))
                    <x-common::alert type="success" :message="session('success')" />
                @endif
                @if (session('error'))
                    <x-common::alert type="danger" :message="session('error')" />
                @endif

                @yield('content')

            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    @include('common::includes.footer')

</body>
<!-- END: Body-->
@include('common::includes.js')

</html>
