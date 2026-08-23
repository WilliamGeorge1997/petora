<link rel="apple-touch-icon" href="{{ asset('admin/images/ico/apple-icon-120.png') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin/css/all.min.css') }}">
<link rel="shortcut icon" type="image/x-icon" href="{{ asset('admin/images/ico/favicon.ico') }}">
{{-- <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet"> --}}
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;500&display=swap" rel="stylesheet">
<!-- BEGIN: Vendor CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('admin/vendors/css/vendors-rtl.min.css') }}">
{{-- <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/vendors/css/charts/apexcharts.css"> --}}
<link rel="stylesheet" type="text/css" href="{{ asset('admin/vendors/css/extensions/toastr.min.css') }}">
<!-- END: Vendor CSS-->

<!-- BEGIN: Theme CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('admin/css-rtl/bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin/css-rtl/bootstrap-extended.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin/css-rtl/colors.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin/css-rtl/components.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin/css-rtl/themes/dark-layout.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin/css-rtl/themes/bordered-layout.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin/css-rtl/themes/semi-dark-layout.css') }}">

<!-- BEGIN: Page CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('admin/css-rtl/core/menu/menu-types/vertical-menu.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin/css-rtl/pages/dashboard-ecommerce.css') }}">
{{-- <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/css-rtl/plugins/charts/chart-apex.css"> --}}
<link rel="stylesheet" type="text/css" href="{{ asset('admin/css-rtl/plugins/extensions/ext-component-toastr.css') }}">
<!-- END: Page CSS-->

<!-- BEGIN: Custom CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('admin/css-rtl/custom-rtl.css') }}">
{{-- <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/css/style-rtl.css"> --}}
<!-- END: Custom CSS-->
<link rel="stylesheet" type="text/css"
    href="{{ asset('admin/css-rtl/plugins/extensions/ext-component-sweet-alerts.css') }}">


<meta name="csrf-token" content="{{ csrf_token() }}">
@yield('css')
