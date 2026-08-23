@extends('common::layouts.master')

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" type="text/css" href="{{asset('')}}admin/vendors/css/forms/wizard/bs-stepper.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/vendors/css/forms/select/select2.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/css-rtl/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/css-rtl/plugins/forms/form-wizard.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin/css-rtl/plugins/extensions/ext-component-sweet-alerts.css">

@endsection

@section('content')

    <!-- BEGIN: Content-->
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">انشاء فرع جديد</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Horizontal Wizard -->
                <section class="horizontal-wizard">
                    <div class="bs-stepper horizontal-wizard-example">
                        <div class="bs-stepper-header" role="tablist">
                            <div class="step" data-target="#account-details" role="tab" id="account-details-trigger">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-box">1</span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">بيانات الفرع</span>
                                    </span>
                                </button>
                            </div>
                            <div class="line">
                                <i data-feather="chevron-right" class="font-medium-2"></i>
                            </div>
                            <div class="step" data-target="#personal-info" role="tab" id="personal-info-trigger">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-box">2</span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">بيانات مدير الفرع</span>
                                    </span>
                                </button>
                            </div>
                            
                        </div>
                        <div class="bs-stepper-content">
                            <div id="account-details" class="content" role="tabpanel" aria-labelledby="account-details-trigger">
                                <div class="content-header">
                                    <h5 class="mb-0">بيانات الفرع</h5>
                                    <small class="text-muted">من فضلك ادخل بيانات الفرع</small>
                                </div>
                                <form>
                                    <div class="row">
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="username">الاسم باللغة العربية</label>
                                            <input type="text" name="title_ar" id="title_ar" class="form-control" placeholder="فرع" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="username">الاسم باللغة الانجليزية</label>
                                            <input type="text" name="title_en" id="title_en" class="form-control" placeholder="branch" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="order_methods">طرق الطلب</label>
                                            <select class="select2 w-100" name="order_methods" id="order_methods" multiple>
                                                @foreach($order_methods as $method)
                                                <option value="{{$method->id}}">{{$method->title}}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        {{-- <div class="mb-1 col-md-6">
                                            <label class="form-label" for="tables_number"> عدد الطاولات</label>
                                            <input type="number" name="tables_number" id="tables_number" class="form-control" placeholder="عدد الطاولات" />
                                        </div> --}}

                                        <div class="mb-1 col-md-6">
                                            <label class="form-label"> الصورة</label>
                                            <input type="file" name="image" id="image" class="form-control" placeholder="image" />
                                        </div>

                                        <div class="mb-1 col-md-6">
                                          <input type="checkbox" value="1" name="is_active" class="form-check-input" id="customCheck2" />
                                          <label class="form-check-label" for="customCheck2">تفعيل</label>
                                      </div>
                                    </div>
                                </form>
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-outline-secondary btn-prev" disabled>
                                        <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                        <span class="align-middle d-sm-inline-block d-none">السابق</span>
                                    </button>
                                    <button class="btn btn-primary btn-next">
                                        <span class="align-middle d-sm-inline-block d-none">التالي</span>
                                        <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="personal-info" class="content" role="tabpanel" aria-labelledby="personal-info-trigger">
                                <div class="content-header">
                                    <h5 class="mb-0">بيانات شخصية</h5>
                                    <small>من فضلك ادخل بيانات مدير الفرع</small>
                                </div>
                                <form>
                                    <div class="row">
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="name">اسم مدير الفرع</label>
                                            <input type="text" name="name" id="name" class="form-control" placeholder="John" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="email">البريد الالكتروني</label>
                                            <input type="email" name="email" id="email" class="form-control" placeholder="john.doe@email.com" aria-label="john.doe" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="name">رقم الجوال</label>
                                            <input type="number" name="phone" id="phone" class="form-control" placeholder="John" />
                                        </div>
                                        <div class="mb-1 form-password-toggle col-md-6">
                                            <label class="form-label" for="password">كلمة المرور</label>
                                            <input type="password" name="password" id="password" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                        </div>
                                    </div>
                                </form>
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-primary btn-prev">
                                        <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                        <span class="align-middle d-sm-inline-block d-none">السابق</span>
                                    </button>
                                    <button class="btn btn-success btn-submit">تأكيد</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- /Horizontal Wizard -->

            </div>
        </div>
    <!-- END: Content-->

@endsection

@section('js')

    <script src="{{asset('')}}admin/vendors/js/forms/wizard/bs-stepper.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <script src="{{asset('')}}admin/vendors/js/extensions/sweetalert2.all.min.js"></script>

    {{-- <script src="{{asset('')}}admin/js/scripts/forms/form-wizard.js"></script> --}}
    <script>
        var bsStepper = document.querySelectorAll('.bs-stepper'),
    horizontalWizard = document.querySelector('.horizontal-wizard-example');

  // Adds crossed class
  if (typeof bsStepper !== undefined && bsStepper !== null) {
    for (var el = 0; el < bsStepper.length; ++el) {
      bsStepper[el].addEventListener('show.bs-stepper', function (event) {
        var index = event.detail.indexStep;
        var numberOfSteps = $(event.target).find('.step').length - 1;
        var line = $(event.target).find('.step');

        // The first for loop is for increasing the steps,
        // the second is for turning them off when going back
        // and the third with the if statement because the last line
        // can't seem to turn off when I press the first item. ¯\_(ツ)_/¯

        for (var i = 0; i < index; i++) {
          line[i].classList.add('crossed');

          for (var j = index; j < numberOfSteps; j++) {
            line[j].classList.remove('crossed');
          }
        }
        if (event.detail.to == 0) {
          for (var k = index; k < numberOfSteps; k++) {
            line[k].classList.remove('crossed');
          }
          line[0].classList.remove('crossed');
        }
      });
    }
  }
        // Horizontal Wizard
  // --------------------------------------------------------------------
  if (typeof horizontalWizard !== undefined && horizontalWizard !== null) {
    var numberedStepper = new Stepper(horizontalWizard),
      $form = $(horizontalWizard).find('form');
    $form.each(function () {
      var $this = $(this);
      $this.validate({
        rules: {
          title_ar: {
            required: true
          },
          title_en: {
            required: true
          },
          password: {
            required: true
          },
          order_methods: {
            required: true
          },
          // tables_number: {
          //   required: true
          // },
          image: {
            required: true
          },
          language: {
            required: true
          },
          twitter: {
            required: true,
            url: true
          },
          facebook: {
            required: true,
            url: true
          },
          google: {
            required: true,
            url: true
          },
          linkedin: {
            required: true,
            url: true
          }
        }
      });
    });

    $(horizontalWizard)
      .find('.btn-next')
      .each(function () {
        $(this).on('click', function (e) {
          var isValid = $(this).parent().siblings('form').valid();
          if (isValid) {
            numberedStepper.next();
          } else {
            e.preventDefault();
          }
        });
      });

    $(horizontalWizard)
      .find('.btn-prev')
      .on('click', function () {
        numberedStepper.previous();
      });

    $(horizontalWizard)
      .find('.btn-submit')
      .on('click', function () {
        var isValid = $(this).parent().siblings('form').valid();
        if (isValid) {
            var token = $("meta[name='csrf-token']").attr("content");
            $image = $('#image')[0].files;
            if (document.getElementById('customCheck2').checked) {
              $is_active = 1 ;
            }else{
              $is_active = 0 ;
            }
            var formData = new FormData();
            formData.append("title_ar", document.getElementById("title_ar").value);
            formData.append("title_en", document.getElementById("title_en").value);
            formData.append("order_methods", document.getElementById("order_methods").value);
            // formData.append("tables_number", document.getElementById("tables_number").value);
            formData.append("image", $image[0]);
            formData.append("name", document.getElementById("name").value);
            formData.append("phone", document.getElementById("phone").value);
            formData.append("email", document.getElementById("email").value);
            formData.append("password", document.getElementById("password").value);
            formData.append("is_active", $is_active);
            formData.append('_token',token);
            $.ajax(
                {
                    url: "/admin/branches",
                    type: 'POST',
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: function (response){
                        console.log(response);
                        Swal.fire({
                          title: 'أحسنت!',
                          text: 'لقد تم انشاء الفرع بنجاح',
                          icon: 'success',
                          customClass: {
                              confirmButton: 'btn btn-primary'
                          },
                          buttonsStyling: false
                      }).then(function() {
                        window.location.href = "/admin/branches";
                      });
                    },
                    

                });          
        }
      });
  }

    </script>
    <script>

        var select = $('.select2');

        select.each(function () {
            var $this = $(this);
            $this.wrap('<div class="position-relative"></div>');
            $this.select2({
                // the following code is used to disable x-scrollbar when click in select input and
                // take 100% width in responsive also
                dropdownAutoWidth: true,
                width: '100%',
                dropdownParent: $this.parent()
            });
        });


    </script>


@endsection
