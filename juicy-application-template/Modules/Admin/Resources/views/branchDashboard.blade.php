@extends('common::layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin/vendors/css/charts/apexcharts.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin/css-rtl/plugins/charts/chart-apex.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin/vendors/css/animate/animate.min.css">
@endsection

@section('content')
    <div id="loader" style="display:none;">
        <div class="position-fixed top-0 start-0 w-100 vh-100 bg-dark d-flex justify-content-center align-items-center"
            style="z-index: 1070; opacity: .5;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    @can('Index-dashboard')
        <div id="toast-container" style="position:fixed; top:13%; right:1rem; z-index: 1080;"></div>
        <audio id="notification-sound" src="{{ asset('') }}sounds/notification-sound.mp3" preload="auto"></audio>
        <section id="dashboard-ecommerce-branch" class="branch_dashboard">

            <section id="nav-filled" class="sticky-top" style="top:2%; z-index:10">
                <div class="row match-height">
                    <!-- Filled Tabs starts -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body p-1">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs nav-fill m-0" id="myTab" role="tablist">
                                    @php
                                        $countRecentPlusTwoMinute = count($recentPlusTwoMinute);
                                        $countRecentMinusOneMinute = count($recentMinusOneMinute);
                                        $countRecentMinusTwoMinutes = count($recentMinusTwoMinutes);

                                        $countTotalRecent =
                                            $countRecentPlusTwoMinute +
                                            $countRecentMinusOneMinute +
                                            $countRecentMinusTwoMinutes;

                                        $countAcceptMinus15Minute = count($acceptMinus15Minute);
                                        $countAcceptPlus15Minus20Minute = count($acceptPlus15Minus20Minute);
                                        $countAcceptPlus20Minute = count($acceptPlus20Minute);

                                        $countTotalAccept =
                                            $countAcceptMinus15Minute +
                                            $countAcceptPlus15Minus20Minute +
                                            $countAcceptPlus20Minute;

                                        $countMainReadyData = count($MainReadyData);

                                        $countDeliveryMinus15Minute = count($deliveryMinus15Minute);
                                        $countDeliveryPlus15Minus20Minute = count($deliveryPlus15Minus20Minute);
                                        $countDeliveryPlus20Minute = count($deliveryPlus20Minute);

                                        $countDeliveryTotal =
                                            $countDeliveryMinus15Minute +
                                            $countDeliveryPlus15Minus20Minute +
                                            $countDeliveryPlus20Minute;
                                    @endphp

                                    @if ($countRecentPlusTwoMinute != 0 || $countRecentMinusOneMinute != 0 || $countRecentMinusTwoMinutes != 0)
                                        <li class="nav-item">

                                            <a class="nav-link position-relative {{ $countTotalRecent > 0 ? 'active' : '' }} "
                                                id="new-tab-fill" data-bs-toggle="tab" href="#new-fill" role="tab"
                                                aria-controls="new-fill" aria-selected="true">جديد</a>
                                            <span
                                                class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger"
                                                style="right: 20% !important;">
                                                {{ $countRecentPlusTwoMinute + $countRecentMinusOneMinute + $countRecentMinusTwoMinutes }}
                                            </span>
                                        </li>
                                    @endif




                                    @if ($countAcceptMinus15Minute != 0 || $countAcceptPlus15Minus20Minute != 0 || $countAcceptPlus20Minute != 0)
                                        <li class="nav-item">
                                            <a class="nav-link position-relative {{ $countTotalAccept > 0 && $countTotalRecent === 0 ? 'active' : '' }}"
                                                id="preparing-tab-fill" data-bs-toggle="tab" href="#preparing-fill"
                                                role="tab" aria-controls="preparing-fill" aria-selected="false">
                                                تحضير</a>
                                            <span
                                                class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-success"
                                                style="right: 20% !important;">
                                                {{ $countAcceptMinus15Minute + $countAcceptPlus15Minus20Minute + $countAcceptPlus20Minute }}
                                            </span>
                                        </li>
                                    @endif



                                    @if ($countMainReadyData != 0)
                                        <li class="nav-item">
                                            <a class="nav-link position-relative {{ $countMainReadyData > 0 && $countTotalAccept === 0 && $countTotalRecent === 0 ? 'active' : '' }}"
                                                id="ready-tab-fill" data-bs-toggle="tab" href="#ready-fill" role="tab"
                                                aria-controls="ready-fill" aria-selected="false">تسليم</a>
                                            <span
                                                class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-info"
                                                style="right: 20% !important;">
                                                {{ $countMainReadyData }}
                                            </span>
                                        </li>
                                    @endif



                                    @if ($countDeliveryTotal != 0)
                                        <li class="nav-item">
                                            <a class="nav-link position-relative {{ $countDeliveryTotal > 0 && $countMainReadyData === 0 && $countTotalAccept === 0 && $countTotalRecent === 0 ? 'active' : '' }}"
                                                id="delivery-tab-fill" data-bs-toggle="tab" href="#delivery-fill" role="tab"
                                                aria-controls="delivery-fill" aria-selected="false">توصيل</a>
                                            <span
                                                class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-warning"
                                                style="right: 20% !important;">
                                                {{ $countDeliveryTotal }}
                                            </span>
                                        </li>
                                    @endif
                                    {{-- <li class="nav-item">
                                        <a class="nav-link" id="delivery-tab-fill" data-bs-toggle="tab" href="#delivery-fill"
                                            role="tab" aria-controls="delivery-fill" aria-selected="false">في التوصيل</a>
                                    </li> --}}
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- Filled Tabs ends -->
                </div>
            </section>

            <div class="tab-content pt-1">
                <!--case new order-->
                <div class="tab-pane {{ $countTotalRecent > 0 ? 'active' : '' }}" id="new-fill" role="tabpanel"
                    aria-labelledby="new-tab-fill">
                    @if ($countRecentPlusTwoMinute != 0 || $countRecentMinusOneMinute != 0 || $countRecentMinusTwoMinutes != 0)
                        <h5 class="mt-3 mb-2">طلبات جديدة</h5>
                    @endif
                    <div class="row">
                        @foreach ($recentMinusOneMinute as $key => $value)
                            @include('admin::cards.Cardbody')
                            @include('admin::cards.CardPopUp')
                        @endforeach
                    </div>
                    <div class="row">
                        @foreach ($recentMinusTwoMinutes as $key => $value)
                            @include('admin::cards.Cardbody')
                            @include('admin::cards.CardPopUp')
                        @endforeach
                    </div>

                    <div class="row">
                        @foreach ($recentPlusTwoMinute as $key => $value)
                            @include('admin::cards.Cardbody')
                            @include('admin::cards.CardPopUp')
                        @endforeach
                    </div>
                </div>

                <!----end case new order -->

                <!--case making ready order-->
                <div class="tab-pane {{ $countTotalAccept > 0 && $countTotalRecent === 0 ? 'active' : '' }}"
                    id="preparing-fill" role="tabpanel" aria-labelledby="preparing-tab-fill">
                    @if ($countAcceptMinus15Minute != 0 || $countAcceptPlus15Minus20Minute != 0 || $countAcceptPlus20Minute != 0)
                        <h5 class="mt-3 mb-2">طلبات في التحضير</h5>
                    @endif
                    <div class="row">
                        @foreach ($acceptMinus15Minute as $key => $value)
                            @include('admin::cards.Cardbody')
                            @include('admin::cards.CardPopUp')
                        @endforeach
                    </div>
                    <div class="row">
                        @foreach ($acceptPlus15Minus20Minute as $key => $value)
                            @include('admin::cards.Cardbody')
                            @include('admin::cards.CardPopUp')
                        @endforeach
                    </div>
                    <div class="row">
                        @foreach ($acceptPlus20Minute as $key => $value)
                            @include('admin::cards.Cardbody')
                            @include('admin::cards.CardPopUp')
                        @endforeach
                    </div>
                </div>
                <!----end  making ready order -->

                <!--case ready for delivery order-->
                <div class="tab-pane {{ $countMainReadyData > 0 && $countTotalAccept === 0 && $countTotalRecent === 0 ? 'active' : '' }}"
                    id="ready-fill" role="tabpanel" aria-labelledby="ready-tab-fill">
                    @if ($countMainReadyData != 0)
                        <h5 class="mt-3 mb-2">طلبات جاهزة للتسليم</h5>
                    @endif
                    <div class="row">
                        @foreach ($MainReadyData as $key => $value)
                            @include('admin::cards.Cardbody')
                            @include('admin::cards.CardPopUp')
                        @endforeach
                    </div>
                </div>
                <!----end ready for delivery order -->
                <!----end ready for delivery order -->
                <!--case in delivery (home delivery only)-->
                <div class="tab-pane {{ $countDeliveryTotal > 0 && $countMainReadyData === 0 && $countTotalAccept === 0 && $countTotalRecent === 0 ? 'active' : '' }}"
                    id="delivery-fill" role="tabpanel" aria-labelledby="delivery-tab-fill">
                    @if ($countDeliveryTotal != 0)
                        <h5 class="mt-3 mb-2">طلبات قيد التوصيل</h5>
                    @endif

                    @if ($countDeliveryMinus15Minute != 0)
                        <div class="row">
                            <h6 class="mb-2">أقل من 15 دقيقة</h6>
                            @foreach ($deliveryMinus15Minute as $key => $value)
                                @include('admin::cards.Cardbody')
                                @include('admin::cards.CardPopUp')
                            @endforeach
                        </div>
                    @endif

                    @if ($countDeliveryPlus15Minus20Minute != 0)
                        <div class="row">
                            <h6 class="mb-2">بين 15 و 20 دقيقة</h6>
                            @foreach ($deliveryPlus15Minus20Minute as $key => $value)
                                @include('admin::cards.Cardbody')
                                @include('admin::cards.CardPopUp')
                            @endforeach
                        </div>
                    @endif

                    @if ($countDeliveryPlus20Minute != 0)
                        <div class="row">
                            <h6 class="mb-2">أكثر من 20 دقيقة</h6>
                            @foreach ($deliveryPlus20Minute as $key => $value)
                                @include('admin::cards.Cardbody')
                                @include('admin::cards.CardPopUp')
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>




            <!--case customer in front of  branch order-->
            {{-- @if (count($FrontOfBranchMinusOneMinute) != 0 || count($FrontOfBranchMinusTwoMinutes) != 0 || count($FrontOfBranchPlusTwoMinute) != 0)
                <h5 class="mt-3 mb-2">العميل في الفرع</h5>
            @endif
            <div class="row">
                @foreach ($FrontOfBranchMinusOneMinute as $key => $value)
                    @include('admin::cards.Cardbody')
                    @include('admin::cards.CardPopUp')
                @endforeach
            </div>
            <div class="row">
                @foreach ($FrontOfBranchMinusTwoMinutes as $key => $value)
                    @include('admin::cards.Cardbody')
                    @include('admin::cards.CardPopUp')
                @endforeach
            </div>
            <div class="row">
                @foreach ($FrontOfBranchPlusTwoMinute as $key => $value)
                    @include('admin::cards.Cardbody')
                    @include('admin::cards.CardPopUp')
                @endforeach
            </div> --}}

        </section>
    @else
        <div class="col-xl-4 col-md-6 col-12">
            <div class="card card-congratulation-medal">
                <div class="card-body">
                    <h5>Congratulations 🎉 !</h5>
                    <p class="card-text font-small-3">Welcome in Juicy application</p>
                    {{--                    <a href="{{url('admin/orders')}}" type="button" class="btn btn-primary">Orders</a> --}}
                    <img src="{{ asset('') }}admin/images/illustration/badge.svg" class="congratulation-medal"
                        alt="Medal Pic" />
                </div>
            </div>
        </div>
    @endcan
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.18/vue.min.js"></script>

    <script>
        const app = new Vue({
            el: '#dashboard-ecommerce-branch',
            created() {
                // Update active managers every 30 seconds
                // window.setInterval(() => {
                //     this.updateActiveManagers();
                // }, 30000);

                // Existing dashboard refresh logic
                window.setInterval(() => {
                    const activeId = $('.tab-pane.active').attr('id') || null;
                    $('.allDataModal').modal('hide');
                    $.ajax({
                        url: '{{ url('admin/dashboard') }}',
                        type: "GET",
                        data: {
                            "_token": "{{ csrf_token() }}",
                        },
                        success: function(data) {
                            $('.branch_dashboard').html(data);
                            const $trigger = $(`a[data-bs-toggle="tab"][href="#${activeId}"]`);
                            if ($trigger.length) {
                                if (window.bootstrap && bootstrap.Tab) {
                                    new bootstrap.Tab($trigger[0]).show();
                                } else {
                                    $('.nav-link').removeClass('active');
                                    $('.tab-pane').removeClass('active show');
                                    $trigger.addClass('active');
                                    $(`#${activeId}`).addClass('active show');
                                }
                            }
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });


                }, 30000);
            }
        });
    </script>

    {{-- <script src="{{ asset('') }}admin/vendors/js/extensions/sweetalertdashboard.all.min.js"></script> --}}
    {{-- <script src="{{ asset('') }}admin/js/core/app-menu.js"></script>
    <script src="{{ asset('') }}admin/js/core/app.js"></script> --}}

    <script src="https://js.pusher.com/4.1/pusher.min.js"></script>
    <script>
        let notificationSound = document.getElementById('notification-sound');
        var pusher = new Pusher('{{ env('MIX_PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            encrypted: true
        });

        var channel = pusher.subscribe('createOrder-notify-channel');
        channel.bind('Modules\\Order\\Events\\CreateOrderNotify', function(dataaaa) {
            if ('{{ Auth::user()['branch_id'] }}' == dataaaa['branch_id']) {
                const activeId = $('.tab-pane.active').attr('id') || null;
                $.ajax({
                    url: '{{ url('admin/dashboard') }}',
                    type: "GET",
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        $('.branch_dashboard').html(data);
                        if (notificationSound) {
                            notificationSound.play();
                        }
                        if (activeId) {
                            const $trigger = $(`a[data-bs-toggle="tab"][href="#${activeId}"]`);
                            if (window.bootstrap && bootstrap.Tab) {
                                new bootstrap.Tab($trigger[0]).show();
                            } else {
                                $('.nav-link').removeClass('active');
                                $('.tab-pane').removeClass('active show');
                                $trigger.addClass('active');
                                $(`#${activeId}`).addClass('active show');
                            }
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
        });



        // var cancelChannel = pusher.subscribe('cancelOrder-notify-channel');
        // cancelChannel.bind('Modules\\Order\\Events\\CancelOrderNotify', function(cancelData) {
        //     $.ajax({
        //         url: '{{ url('admin/dashboard') }}',
        //         type: "GET",
        //         data: {
        //             "_token": "{{ csrf_token() }}",
        //         },
        //         success: function(data) {
        //             $('.branch_dashboard').html(data);
        //         },
        //         error: function(error) {
        //             console.log(error);
        //         }
        //     });
        // });


        // var failChannel = pusher.subscribe('failOrder-notify-channel');
        // failChannel.bind('Modules\\Order\\Events\\FailOrderNotify', function(failData) {
        //     $.ajax({
        //         url: '{{ url('admin/dashboard') }}',
        //         type: "GET",
        //         data: {
        //             "_token": "{{ csrf_token() }}",
        //         },
        //         success: function(data) {
        //             $('.branch_dashboard').html(data);
        //         },
        //         error: function(error) {
        //             console.log(error);
        //         }
        //     });
        // });
    </script>

    @if (session('print_receipt'))
        <script>
            window.open("{{ route('orders.print', session('order_id')) }}", '_blank', 'width=800,height=600');
        </script>
    @endif

    <script>
        window.showToast = function(message, type = true) {
            const id = 't' + Date.now();
            const text = type === true ? 'text-success' : (type === false ? 'text-danger' : 'text-secondary');
            const bg = 'bg-white'
            const el = $(`
            <div id="${id}" class="${text} ${bg}"
                 style="min-width:260px; margin-bottom:.5rem; padding:.75rem 1rem; border-radius:.25rem; box-shadow:0 0.5rem 1rem rgba(0,0,0,.15);">
                ${message}
            </div>
        `);
            $('#toast-container').append(el);
            setTimeout(() => {
                el.fadeOut(200, () => el.remove());
            }, 2000);
        };

        window.refreshDashboard = function() {
            const activeId = $('.tab-pane.active').attr('id') || null;
            $('.allDataModal').modal('hide');
            $.ajax({
                url: '{{ url('admin/dashboard') }}',
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function(data) {
                    $('.branch_dashboard').html(data);
                    if (activeId) {
                        const $trigger = $(`a[data-bs-toggle="tab"][href="#${activeId}"]`);
                        if ($trigger.length) {
                            if (window.bootstrap && bootstrap.Tab) {
                                new bootstrap.Tab($trigger[0]).show();
                            } else {
                                $('.nav-link').removeClass('active');
                                $('.tab-pane').removeClass('active show');
                                $trigger.addClass('active');
                                $(`#${activeId}`).addClass('active show');
                            }
                        }
                    }
                },
                error: function(xhr) {
                    console.error(xhr);
                    showToast('حدث خطأ أثناء تحديث الصفحة', 'error');
                },
                complete: function() {
                    $('#loader').hide();
                }
            });
        };

        $(document).on('submit', 'form[action="{{ route('admin.updateCardStatus') }}"]', function(e) {
            e.preventDefault();
            const $form = $(this);
            const formData = $form.serialize();
            const action = $form.attr('action');
            $('#loader').show();
            $.ajax({
                url: action,
                type: 'POST',
                data: formData,
                success: function(res) {
                    const $modal = $form.closest('.modal');
                    if ($modal.length) {
                        if (window.bootstrap && bootstrap.Modal) {
                            const instance = bootstrap.Modal.getInstance($modal[0]) || new bootstrap
                                .Modal($modal[0]);
                            instance.hide();
                        } else {
                            $modal.modal('hide');
                        }
                    }
                    showToast(res.msg, res.status);
                    refreshDashboard();
                },
                error: function(xhr) {
                    console.error(xhr);
                    showToast('تعذر تنفيذ الإجراء', false);
                },
            });
        });
    </script>
@endsection
