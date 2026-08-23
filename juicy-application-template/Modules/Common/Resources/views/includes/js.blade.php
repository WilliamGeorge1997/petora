<!-- BEGIN: Vendor JS-->
<script src="{{ asset('') }}admin/vendors/js/vendors.min.js"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
{{-- <script src="{{asset('')}}admin/vendors/js/charts/apexcharts.min.js"></script> --}}
<script src="{{ asset('') }}admin/vendors/js/extensions/toastr.min.js"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="{{ asset('') }}admin/js/core/app-menu.js"></script>
<script src="{{ asset('') }}admin/js/core/app.js"></script>
<script src="{{ asset('') }}admin/vendors/js/extensions/sweetalert2.all.min.js"></script>
<script src="{{ asset('') }}admin/js/scripts/button/button-submit-loading.js"></script>

<!-- END: Theme JS-->

<!-- BEGIN: Page JS-->
{{-- <script src="{{asset('')}}admin/js/scripts/pages/dashboard-ecommerce.js"></script> --}}
<!-- END: Page JS-->

<script>
    $(window).on('load', function() {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    })
</script>

<script src="https://js.pusher.com/4.1/pusher.min.js"></script>
<script>
    var pusher = new Pusher('{{ env('MIX_PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        encrypted: true
    });
</script>

@if (!empty(Auth::user()['branch_id']))
    <script>
        var channel = pusher.subscribe('createOrder-notify-channel');
        channel.bind('Modules\\Order\\Events\\CreateOrderNotify', function(data) {
            if ('{{ Auth::user()['branch_id'] }}' == data['branch_id']) {
                // document.getElementById('new_notification_count').innerHTML = parseInt(document.getElementById(
                //     "new_notification_count").innerText) + 1;

                $("#orderModal").click();
                // var myAudio = document.getElementById("myAudio");
                // myAudio.play();
                document.getElementById('Orderval').value = data['id'];
                document.getElementById('OrderId').innerHTML = data['id'];

            }

        });

        function closemodal() {
            $('#orderModal2').modal('hide');
        }
    </script>
@endif



@if (empty(Auth::user()['branch_id']))
    <script>
        var branchRegistrationChannel = pusher.subscribe('branch-registration-notify-channel');
        branchRegistrationChannel.bind('Modules\\Branch\\Events\\BranchRegistrationNotify', function(data) {
            toastr.success('تم استلام طلب تسجيل فرع جديد: ' + data.name, 'فرع جديد', {
                closeButton: true,
                timeOut: 0,
                extendedTimeOut: 0,
                positionClass: 'toast-bottom-right'
            });
        });
    </script>
@endif

@if (!empty(Auth::user()['branch_id']))
    <script>
        window.callWaiterResolveRoute = "{{ route('api.call-waiter.resolve', ':id', false) }}";
        window.userBranchId = "{{ Auth::user()['branch_id'] }}";
    </script>
    <script src="{{ asset('admin/js/scripts/call-waiter.js') }}"></script>
@endif

@yield('js')
