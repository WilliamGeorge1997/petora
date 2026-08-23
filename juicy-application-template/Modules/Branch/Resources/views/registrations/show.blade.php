@extends('common::layouts.master')

@section('css')
@endsection

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">تفاصيل الطلب</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('admin/registrations') }}">طلبات الفروع</a></li>
                            <li class="breadcrumb-item active">تفاصيل الطلب</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header border-bottom">
            <h4 class="card-title">تفاصيل طلب الفرع: {{ $registration->name }}</h4>
            <div class="heading-elements">
                @if($registration->is_completed)
                    <span class="badge bg-success fs-6">مكتمل</span>
                @else
                    <span class="badge bg-warning fs-6">قيد الانتظار</span>
                @endif
            </div>
        </div>
        <div class="card-body pt-2">
            <div class="text-center mb-3 mt-2">
                @if($registration->image)
                    <img src="{{ $registration->image }}" class="rounded-circle shadow-sm" style="width: 150px; height: 150px; object-fit: cover;" alt="Branch Image">
                @else
                    <div class="avatar bg-light-primary rounded-circle shadow-sm" style="width: 150px; height: 150px; display: inline-flex; align-items: center; justify-content: center;">
                        <span class="avatar-content display-4 text-primary" style="font-size: 4rem !important;">{{ mb_substr($registration->getTranslations('name')['ar'] ?? 'ف', 0, 1) }}</span>
                    </div>
                @endif
            </div>

            <h3 class="border-bottom pb-1 mb-2 mt-3 text-primary"><i data-feather="info" class="me-50"></i> المعلومات الأساسية</h3>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <h5 class="fw-bolder">الاسم (عربي)</h5>
                    <p class="mb-0">{{ $registration->getTranslations('name')['ar'] ?? '' }}</p>
                </div>
                <div class="col-md-6 mb-2">
                    <h5 class="fw-bolder">الاسم (انجليزي)</h5>
                    <p class="mb-0">{{ $registration->getTranslations('name')['en'] ?? '' }}</p>
                </div>
                <div class="col-md-6 mb-2">
                    <h5 class="fw-bolder">العنوان (عربي)</h5>
                    <p class="mb-0">{{ $registration->getTranslations('address')['ar'] ?? '' }}</p>
                </div>
                <div class="col-md-6 mb-2">
                    <h5 class="fw-bolder">العنوان (انجليزي)</h5>
                    <p class="mb-0">{{ $registration->getTranslations('address')['en'] ?? '' }}</p>
                </div>
                <div class="col-md-6 mb-2">
                    <h5 class="fw-bolder">رقم الهاتف</h5>
                    <p class="mb-0" dir="ltr" style="text-align: right;">{{ $registration->phone }}</p>
                </div>
                <div class="col-md-6 mb-2">
                    <h5 class="fw-bolder">رابط اللوكيشن</h5>
                    @if($registration->location_url)
                        <div class="d-flex align-items-center" style="gap: 0.5rem;">
                            <a href="{{ $registration->location_url }}" target="_blank" class="btn btn-sm btn-outline-primary">عرض على الخريطة</a>
                            <button type="button" class="btn btn-sm btn-icon btn-outline-secondary copy-location-url" data-url="{{ $registration->location_url }}" title="نسخ الرابط">
                                <i data-feather="copy" style="width: 14px; height: 14px;"></i>
                            </button>
                        </div>
                    @else
                        <p class="text-muted mb-0">غير متوفر</p>
                    @endif
                </div>
                <div class="col-md-6 mb-2">
                    <h5 class="fw-bolder">تاريخ تقديم الطلب</h5>
                    <p class="mb-0">{{ $registration->created_at }}</p>
                </div>
            </div>

            <h3 class="border-bottom pb-1 mb-2 mt-3 text-primary"><i data-feather="user" class="me-50"></i> معلومات المدير</h3>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <h5 class="fw-bolder">مدير الفرع</h5>
                    <p class="mb-0">{{ $registration->manager_name }}</p>
                </div>
                <div class="col-md-6 mb-2">
                    <h5 class="fw-bolder">رقم هاتف المدير</h5>
                    <p class="mb-0" dir="ltr" style="text-align: right;">{{ $registration->manager_phone }}</p>
                </div>
            </div>

            <h3 class="border-bottom pb-1 mb-2 mt-3 text-primary"><i data-feather="settings" class="me-50"></i> الإعدادات ومواعيد العمل</h3>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <h5 class="fw-bolder">الستايل (الثيم) المختار</h5>
                    <p class="mb-0"><span class="badge bg-primary">ستايل {{ $registration->theme }}</span></p>
                </div>
                <div class="col-md-6 mb-2">
                    <h5 class="fw-bolder">تفعيل الطلبات من المنيو؟</h5>
                    <p class="mb-0">
                        @if($registration->is_order_enabled)
                            <span class="badge badge-light-success">نعم</span>
                        @else
                            <span class="badge badge-light-danger">لا</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6 mb-2">
                    <h5 class="fw-bolder">مواعيد العمل</h5>
                    @if($registration->is_working_24_hours)
                        <p class="mb-0"><span class="badge badge-light-success">مفتوح 24 ساعة</span></p>
                    @else
                        <p class="mb-0">
                            <strong>من:</strong> <span dir="ltr">{{ $registration->working_from }}</span> 
                            <strong>إلى:</strong> <span dir="ltr">{{ $registration->working_to }}</span>
                        </p>
                    @endif
                </div>
            </div>
            
            <div class="mt-4 pt-2 border-top">
                <a href="{{ url('admin/registrations') }}" class="btn btn-secondary">الرجوع</a>
                @if(!$registration->is_completed)
                    <a href="{{ url('admin/registrations/completed/' . $registration->id) }}" class="btn btn-success ms-1">تعيين كمكتمل</a>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('.copy-location-url').on('click', function() {
            var url = $(this).data('url');

            var tempInput = document.createElement('input');
            tempInput.value = url;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);

            Swal.fire({
                text: 'تم نسخ الرابط',
                icon: 'success',
                toast: true,
                position: 'top-end',
                timer: 1500,
                showConfirmButton: false
            });
        });
    });
</script>
@endsection
