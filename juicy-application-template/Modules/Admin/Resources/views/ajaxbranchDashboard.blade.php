     <section id="nav-filled" class="sticky-top" style="top:2%">
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

                                 $countAcceptMinus15Minute = count($acceptMinus15Minute);
                                 $countAcceptPlus15Minus20Minute = count($acceptPlus15Minus20Minute);
                                 $countAcceptPlus20Minute = count($acceptPlus20Minute);

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

                                     <a class="nav-link position-relativ active" id="new-tab-fill"
                                         data-bs-toggle="tab" href="#new-fill" role="tab" aria-controls="new-fill"
                                         aria-selected="true">جديد</a>
                                     <span
                                         class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger"
                                         style="right: 20% !important;">
                                         {{ $countRecentPlusTwoMinute + $countRecentMinusOneMinute + $countRecentMinusTwoMinutes }}
                                     </span>
                                 </li>
                             @endif

                             @if ($countAcceptMinus15Minute != 0 || $countAcceptPlus15Minus20Minute != 0 || $countAcceptPlus20Minute != 0)
                                 <li class="nav-item">
                                     <a class="nav-link position-relative" id="preparing-tab-fill" data-bs-toggle="tab"
                                         href="#preparing-fill" role="tab" aria-controls="preparing-fill"
                                         aria-selected="false">تحضير</a>
                                     <span
                                         class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-success"
                                         style="right: 20% !important;">
                                         {{ $countAcceptMinus15Minute + $countAcceptPlus15Minus20Minute + $countAcceptPlus20Minute }}
                                     </span>
                                 </li>
                             @endif

                             @if ($countMainReadyData != 0)
                                 <li class="nav-item">
                                     <a class="nav-link position-relative" id="ready-tab-fill" data-bs-toggle="tab"
                                         href="#ready-fill" role="tab" aria-controls="ready-fill"
                                         aria-selected="false">تسليم</a>
                                     <span
                                         class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-info"
                                         style="right: 20% !important;">
                                         {{ $countMainReadyData }}
                                     </span>
                                 </li>
                                 @endif

                                 @if ($countDeliveryTotal != 0)
                                     <li class="nav-item">
                                         <a class="nav-link position-relative" id="delivery-tab-fill"
                                             data-bs-toggle="tab" href="#delivery-fill" role="tab"
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

                         <!-- Tab panes -->

                     </div>
                 </div>
             </div>
             <!-- Filled Tabs ends -->
         </div>
     </section>
     <div class="tab-content pt-1">
         <!--case new order-->
         <div class="tab-pane active" id="new-fill" role="tabpanel" aria-labelledby="new-tab-fill">
             @if (count($recentPlusTwoMinute) != 0 || count($recentMinusOneMinute) != 0 || count($recentMinusTwoMinutes) != 0)
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
         <div class="tab-pane" id="preparing-fill" role="tabpanel" aria-labelledby="preparing-tab-fill">
             @if (count($acceptMinus15Minute) != 0 || count($acceptPlus15Minus20Minute) != 0 || count($acceptPlus20Minute) != 0)
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
         <div class="tab-pane" id="ready-fill" role="tabpanel" aria-labelledby="ready-tab-fill">
             @if (count($MainReadyData) != 0)
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


         <!--case in delivery (home delivery only)-->
         <div class="tab-pane" id="delivery-fill" role="tabpanel" aria-labelledby="delivery-tab-fill">
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
     <div id="confirm-text"></div>
