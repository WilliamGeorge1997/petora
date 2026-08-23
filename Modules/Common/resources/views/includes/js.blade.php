<!-- BEGIN: Vendor JS-->
<script src="{{ asset('admin/vendors/js/vendors.min.js') }}"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="{{ asset('admin/vendors/js/extensions/toastr.min.js') }}"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="{{ asset('admin/js/core/app-menu.js') }}"></script>
<script src="{{ asset('admin/js/core/app.js') }}"></script>
<script src="{{ asset('admin/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('admin/js/scripts/button/button-submit-loading.js') }}"></script>
<!-- END: Theme JS-->

<script>
    $(window).on('load', function() {
        if (typeof feather !== 'undefined') {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    })
</script>

@yield('js')
