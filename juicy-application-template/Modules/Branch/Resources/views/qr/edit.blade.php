@extends('common::layouts.master')

@section('css')
<style>
    #qr-canvas {
        min-height: 300px;
    }

    #qr-canvas canvas,
    #qr-canvas svg {
        border-radius: 12px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, .12);
    }

    .style-option {
        cursor: pointer;
        border: 2px solid transparent;
        border-radius: 8px;
        transition: border-color .2s;
    }

    .style-option.selected,
    .style-option:hover {
        border-color: var(--bs-primary);
    }

    .color-swatch {
        width: 36px;
        height: 36px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        padding: 2px;
    }

    .section-label {
        font-weight: 600;
        font-size: .8rem;
        text-transform: uppercase;
        color: #6e6b7b;
        margin-bottom: .4rem;
    }

    #qr-logo-preview {
        width: 64px;
        height: 64px;
        object-fit: contain;
        border-radius: 8px;
        border: 1px solid #ebe9f1;
    }

    .gradient-row {
        display: none;
    }

    .gradient-row.visible {
        display: flex;
    }

    .qr-sticky {
        position: sticky;
        top: 80px;
    }
</style>
@endsection

@section('content')
@php
$qr = $branch->qrSetting;
$saveRoute = route('branch.qr.update', ['id' => $branch->id]);
@endphp

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: 1rem;">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">QR كود</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                        @if (auth()->guard('admin')->user()->hasRole('Super Admin'))
                        <li class="breadcrumb-item">
                            <a href="{{ route('branches.index') }}">الفروع</a>
                        </li>
                        @endif
                        <li class="breadcrumb-item">{{ $branch->getTranslation('title', 'en') }}</li>
                        <li class="breadcrumb-item active">QR كود</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
<form id="qr-settings-form" action="{{ $saveRoute }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- Hidden inputs synced by JS before submit --}}
    <input type="hidden" name="dot_type" id="f-dot-type">
    <input type="hidden" name="dot_color" id="f-dot-color">
    <input type="hidden" name="dot_color_type" id="f-dot-color-type">
    <input type="hidden" name="dot_color_2" id="f-dot-color-2">
    <input type="hidden" name="dot_gradient_type" id="f-dot-gradient-type">
    <input type="hidden" name="dot_gradient_rotation" id="f-dot-gradient-rotation">
    <input type="hidden" name="bg_color" id="f-bg-color">
    <input type="hidden" name="bg_color_type" id="f-bg-color-type">
    <input type="hidden" name="bg_color_2" id="f-bg-color-2">
    <input type="hidden" name="bg_gradient_type" id="f-bg-gradient-type">
    <input type="hidden" name="bg_gradient_rotation" id="f-bg-gradient-rotation">
    <input type="hidden" name="corner_square_type" id="f-corner-square-type">
    <input type="hidden" name="corner_square_color" id="f-corner-square-color">
    <input type="hidden" name="corner_square_color_type" id="f-corner-square-color-type">
    <input type="hidden" name="corner_square_color_2" id="f-corner-square-color-2">
    <input type="hidden" name="corner_square_gradient_type" id="f-corner-square-gradient-type">
    <input type="hidden" name="corner_square_gradient_rotation" id="f-corner-square-gradient-rotation">
    <input type="hidden" name="corner_dot_type" id="f-corner-dot-type">
    <input type="hidden" name="corner_dot_color" id="f-corner-dot-color">
    <input type="hidden" name="corner_dot_color_type" id="f-corner-dot-color-type">
    <input type="hidden" name="corner_dot_color_2" id="f-corner-dot-color-2">
    <input type="hidden" name="corner_dot_gradient_type" id="f-corner-dot-gradient-type">
    <input type="hidden" name="corner_dot_gradient_rotation" id="f-corner-dot-gradient-rotation">
    <input type="hidden" name="width" id="f-width">
    <input type="hidden" name="height" id="f-height">
    <input type="hidden" name="margin" id="f-margin">
    <input type="hidden" name="hide_background_dots" id="f-hide-background-dots">
    <input type="hidden" name="image_size" id="f-image-size">
    <input type="hidden" name="image_margin" id="f-image-margin">
    <input type="hidden" name="use_branch_img" id="f-use-branch-img">
    <input type="hidden" name="is_qr_image_enabled" id="f-is-qr-image-enabled">
    <input type="hidden" name="type_number" id="f-type-number">
    <input type="hidden" name="mode" id="f-mode">
    <input type="hidden" name="error_correction_level" id="f-error-correction-level">
    <input type="file" name="qr_image" id="f-qr-image" style="display:none;">

    <div class="row">
        {{-- QR Preview --}}
        <div class="col-lg-5 col-12 mb-1">
            <div class="card qr-sticky">
                <div class="card-header py-1">
                    <h5 class="card-title mb-0">معاينة QR كود</h5>
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <div id="qr-canvas" class="d-flex align-items-center justify-content-center mb-1"></div>
                    <p class="text-muted text-center mb-1" id="qr-hint">
                        جاري التحميل...
                    </p>
                    <div id="download-buttons" class="d-none d-flex gap-1">
                        <button type="button" class="btn btn-outline-primary" id="btn-png">
                            <i data-feather="download" class="me-25"></i> PNG
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="btn-svg">
                            <i data-feather="download" class="me-25"></i> SVG
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="btn-pdf">
                            <i data-feather="download" class="me-25"></i> PDF
                        </button>
                    </div>

                    {{-- QR Link + Copy --}}
                    <div id="qr-link-wrap" class="d-none mt-1 w-100 px-1">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i data-feather="link"></i>
                            </span>
                            <input type="text" id="qr-link-display" class="form-control" readonly
                                placeholder="رابط QR كود">
                            <button class="btn btn-primary" type="button" id="btn-copy-qr" title="نسخ الرابط">
                                <i data-feather="copy" class="me-25"></i>
                                <span id="copy-qr-label">نسخ</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Style Controls --}}
        <div class="col-lg-7 col-12 mb-1">
            <div class="card h-100">
                <div class="card-header py-1">
                    <h5 class="card-title mb-0">تخصيص التصميم</h5>
                </div>
                <div class="card-body p-0">
                    <div class="accordion accordion-flush" id="qr-accordion">

                        {{-- ① تخصيص التصميم --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-design">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse-design" aria-expanded="true"
                                    aria-controls="collapse-design">
                                    <i data-feather="sliders" class="me-50" style="width:16px;height:16px;"></i>
                                    تخصيص التصميم
                                </button>
                            </h2>
                            <div id="collapse-design" class="accordion-collapse collapse show"
                                aria-labelledby="heading-design" data-bs-parent="#qr-accordion">
                                <div class="accordion-body">

                                    {{-- Dot Style --}}
                                    <div class="mb-2">
                                        <p class="section-label">شكل النقاط</p>
                                        <div class="d-flex flex-wrap gap-1">
                                            @php $dotStyles =
                                            ['square'=>'مربع','dots'=>'دائري','rounded'=>'مدور','classy'=>'أنيق','classy-rounded'=>'أنيق
                                            مدور','extra-rounded'=>'مدور كامل']; @endphp
                                            @foreach ($dotStyles as $val => $lbl)
                                            <div class="style-option text-center px-1 py-50 {{ $val === 'square' ? 'selected' : '' }}"
                                                data-type="dot" data-value="{{ $val }}">
                                                <small>{{ $lbl }}</small>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Corner Square Style --}}
                                    <div class="mb-2">
                                        <p class="section-label">زوايا المربعات الكبيرة</p>
                                        <div class="d-flex flex-wrap gap-1">
                                            @php $csStyles = ['square'=>'مربع','dot'=>'دائري','extra-rounded'=>'مدور'];
                                            @endphp
                                            @foreach ($csStyles as $val => $lbl)
                                            <div class="style-option text-center px-1 py-50 {{ $val === 'square' ? 'selected' : '' }}"
                                                data-type="cornerSquare" data-value="{{ $val }}">
                                                <small>{{ $lbl }}</small>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Corner Dot Style --}}
                                    <div class="mb-2">
                                        <p class="section-label">زوايا النقاط الصغيرة</p>
                                        <div class="d-flex flex-wrap gap-1">
                                            @php $cdStyles = ['square'=>'مربع','dot'=>'دائري']; @endphp
                                            @foreach ($cdStyles as $val => $lbl)
                                            <div class="style-option text-center px-1 py-50 {{ $val === 'square' ? 'selected' : '' }}"
                                                data-type="cornerDot" data-value="{{ $val }}">
                                                <small>{{ $lbl }}</small>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        {{-- end ① --}}

                        {{-- ② الألوان --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-colors">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse-colors" aria-expanded="false"
                                    aria-controls="collapse-colors">
                                    <i data-feather="droplet" class="me-50" style="width:16px;height:16px;"></i>
                                    الألوان
                                </button>
                            </h2>
                            <div id="collapse-colors" class="accordion-collapse collapse"
                                aria-labelledby="heading-colors" data-bs-parent="#qr-accordion">
                                <div class="accordion-body">

                                    {{-- Dots Color --}}
                                    <div class="mb-2">
                                        <p class="section-label">لون النقاط</p>
                                        <div class="d-flex align-items-center gap-1 mb-50">
                                            <div class="form-check form-check-inline mb-0">
                                                <input class="form-check-input" type="radio" name="dot-color-type"
                                                    id="dot-single" value="single" checked>
                                                <label class="form-check-label" for="dot-single">لون واحد</label>
                                            </div>
                                            <div class="form-check form-check-inline mb-0">
                                                <input class="form-check-input" type="radio" name="dot-color-type"
                                                    id="dot-gradient" value="gradient">
                                                <label class="form-check-label" for="dot-gradient">تدرج لوني</label>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-1">
                                            <input type="color" id="dot-color" class="color-swatch" value="#000000">
                                            <span class="text-muted" id="dot-single-label">اللون</span>
                                        </div>
                                        <div class="gradient-row align-items-center gap-1 mt-50" id="dot-gradient-row">
                                            <input type="color" id="dot-color-2" class="color-swatch" value="#ffffff">
                                            <span class="text-muted">اللون الثاني</span>
                                            <select id="dot-gradient-type" class="form-select" style="width:auto;">
                                                <option value="linear">خطي</option>
                                                <option value="radial">دائري</option>
                                            </select>
                                            <input type="number" id="dot-gradient-rotation" class="form-control"
                                                style="width:80px;" value="0" min="0" max="360" placeholder="°">
                                            <span class="text-muted">درجة</span>
                                        </div>
                                    </div>

                                    {{-- Background Color --}}
                                    <div class="mb-2">
                                        <p class="section-label">لون الخلفية</p>
                                        <div class="d-flex align-items-center gap-1 mb-50">
                                            <div class="form-check form-check-inline mb-0">
                                                <input class="form-check-input" type="radio" name="bg-color-type"
                                                    id="bg-single" value="single" checked>
                                                <label class="form-check-label" for="bg-single">لون واحد</label>
                                            </div>
                                            <div class="form-check form-check-inline mb-0">
                                                <input class="form-check-input" type="radio" name="bg-color-type"
                                                    id="bg-gradient" value="gradient">
                                                <label class="form-check-label" for="bg-gradient">تدرج لوني</label>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-1">
                                            <input type="color" id="bg-color" class="color-swatch" value="#ffffff">
                                        </div>
                                        <div class="gradient-row align-items-center gap-1 mt-50" id="bg-gradient-row">
                                            <input type="color" id="bg-color-2" class="color-swatch" value="#cccccc">
                                            <span class="text-muted">اللون الثاني</span>
                                            <select id="bg-gradient-type" class="form-select" style="width:auto;">
                                                <option value="linear">خطي</option>
                                                <option value="radial">دائري</option>
                                            </select>
                                            <input type="number" id="bg-gradient-rotation" class="form-control"
                                                style="width:80px;" value="0" min="0" max="360" placeholder="°">
                                            <span class="text-muted">درجة</span>
                                        </div>
                                    </div>

                                    {{-- Corners Square Color --}}
                                    <div class="mb-2">
                                        <p class="section-label">لون زوايا المربعات الكبيرة</p>
                                        <div class="d-flex align-items-center gap-1 mb-50">
                                            <div class="form-check form-check-inline mb-0">
                                                <input class="form-check-input" type="radio" name="cs-color-type"
                                                    id="cs-single" value="single" checked>
                                                <label class="form-check-label" for="cs-single">لون واحد</label>
                                            </div>
                                            <div class="form-check form-check-inline mb-0">
                                                <input class="form-check-input" type="radio" name="cs-color-type"
                                                    id="cs-gradient" value="gradient">
                                                <label class="form-check-label" for="cs-gradient">تدرج لوني</label>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-1">
                                            <input type="color" id="cs-color" class="color-swatch" value="#000000">
                                        </div>
                                        <div class="gradient-row align-items-center gap-1 mt-50" id="cs-gradient-row">
                                            <input type="color" id="cs-color-2" class="color-swatch" value="#ffffff">
                                            <span class="text-muted">اللون الثاني</span>
                                            <select id="cs-gradient-type" class="form-select" style="width:auto;">
                                                <option value="linear">خطي</option>
                                                <option value="radial">دائري</option>
                                            </select>
                                            <input type="number" id="cs-gradient-rotation" class="form-control"
                                                style="width:80px;" value="0" min="0" max="360" placeholder="°">
                                            <span class="text-muted">درجة</span>
                                        </div>
                                    </div>

                                    {{-- Corners Dot Color --}}
                                    <div class="mb-0">
                                        <p class="section-label">لون زوايا النقاط الصغيرة</p>
                                        <div class="d-flex align-items-center gap-1 mb-50">
                                            <div class="form-check form-check-inline mb-0">
                                                <input class="form-check-input" type="radio" name="cd-color-type"
                                                    id="cd-single" value="single" checked>
                                                <label class="form-check-label" for="cd-single">لون واحد</label>
                                            </div>
                                            <div class="form-check form-check-inline mb-0">
                                                <input class="form-check-input" type="radio" name="cd-color-type"
                                                    id="cd-gradient" value="gradient">
                                                <label class="form-check-label" for="cd-gradient">تدرج لوني</label>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-1">
                                            <input type="color" id="cd-color" class="color-swatch" value="#000000">
                                        </div>
                                        <div class="gradient-row align-items-center gap-1 mt-50" id="cd-gradient-row">
                                            <input type="color" id="cd-color-2" class="color-swatch" value="#ffffff">
                                            <span class="text-muted">اللون الثاني</span>
                                            <select id="cd-gradient-type" class="form-select" style="width:auto;">
                                                <option value="linear">خطي</option>
                                                <option value="radial">دائري</option>
                                            </select>
                                            <input type="number" id="cd-gradient-rotation" class="form-control"
                                                style="width:80px;" value="0" min="0" max="360" placeholder="°">
                                            <span class="text-muted">درجة</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        {{-- end ② --}}

                        {{-- ③ الصورة وخياراتها --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-image">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse-image" aria-expanded="false"
                                    aria-controls="collapse-image">
                                    <i data-feather="image" class="me-50" style="width:16px;height:16px;"></i>
                                    الصورة وخياراتها
                                </button>
                            </h2>
                            <div id="collapse-image" class="accordion-collapse collapse" aria-labelledby="heading-image"
                                data-bs-parent="#qr-accordion">
                                <div class="accordion-body">

                                    {{-- Image Options --}}
                                    <div class="mb-2">
                                        <p class="section-label">خيارات الصورة</p>
                                        <div class="row g-1">
                                            <div class="col-sm-4">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="hide-bg-dots"
                                                        role="switch" checked>
                                                    <label class="form-check-label" for="hide-bg-dots">إخفاء
                                                        النقاط خلف الصورة</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="form-label mb-25">حجم الصورة: <span
                                                        id="img-size-val">0.4</span></label>
                                                <input type="range" id="img-size" class="form-range" min="0.1" max="1"
                                                    step="0.05" value="0.4">
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="form-label mb-25">هامش الصورة: <span
                                                        id="img-margin-val">3</span></label>
                                                <input type="range" id="img-margin" class="form-range" min="0" max="20"
                                                    step="1" value="3">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Logo / QR Image --}}
                                    <div class="mb-0">
                                        <p class="section-label">شعار وسط QR كود</p>

                                        {{-- Toggle: use branch qr_image --}}
                                        <div class="form-check form-switch mb-1" id="branch-img-toggle-wrap">
                                            <input class="form-check-input" type="checkbox" id="use-branch-img"
                                                role="switch">
                                            <label class="form-check-label" for="use-branch-img">استخدام صورة QR
                                                الخاصة بالفرع</label>
                                        </div>

                                        {{-- Branch QR image preview (shown when toggle is on) --}}
                                        <div id="branch-img-preview-wrap" class="d-none mb-1">
                                            <img id="branch-img-preview" src="" alt="صورة الفرع"
                                                style="width:64px;height:64px;object-fit:contain;border-radius:8px;border:1px solid #ebe9f1;">
                                            <span class="text-muted ms-1">صورة QR المحفوظة للفرع</span>
                                        </div>

                                        <p class="section-label mt-1">أو ارفع صورة مخصصة</p>
                                        <div class="d-flex align-items-center gap-1">
                                            <input type="file" id="logo-upload" class="form-control"
                                                accept="image/png,image/jpeg,image/svg+xml">
                                            <button type="button" class="btn btn-outline-danger" id="btn-remove-logo"
                                                style="display:none;">
                                                <i data-feather="x" style="width:14px;height:14px;"></i> إزالة
                                            </button>
                                        </div>
                                        <div class="mt-1" id="logo-preview-wrap" style="display:none;">
                                            <img id="qr-logo-preview" src="" alt="شعار">
                                        </div>
                                        <p class="text-muted mt-50 mb-0">يُنصح باستخدام صورة PNG بخلفية شفافة
                                            لأفضل نتيجة.</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                        {{-- end ③ --}}

                        {{-- ④ الأحجام --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-sizes">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse-sizes" aria-expanded="false"
                                    aria-controls="collapse-sizes">
                                    <i data-feather="maximize-2" class="me-50" style="width:16px;height:16px;"></i>
                                    الأحجام
                                </button>
                            </h2>
                            <div id="collapse-sizes" class="accordion-collapse collapse" aria-labelledby="heading-sizes"
                                data-bs-parent="#qr-accordion">
                                <div class="accordion-body">
                                    <div class="row g-1">
                                        <div class="col-sm-6">
                                            <p class="section-label">الحجم: <span id="size-val">300</span> px</p>
                                            <input type="range" id="qr-size" class="form-range" min="150" max="500"
                                                step="10" value="300">
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="section-label">الهامش الخارجي: <span id="margin-val">0</span> px
                                            </p>
                                            <input type="range" id="qr-margin" class="form-range" min="0" max="50"
                                                step="1" value="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- end ④ --}}

                        {{-- ⑤ خيارات QR --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-qropts">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse-qropts" aria-expanded="false"
                                    aria-controls="collapse-qropts">
                                    <i data-feather="settings" class="me-50" style="width:16px;height:16px;"></i>
                                    خيارات QR
                                </button>
                            </h2>
                            <div id="collapse-qropts" class="accordion-collapse collapse"
                                aria-labelledby="heading-qropts" data-bs-parent="#qr-accordion">
                                <div class="accordion-body">
                                    <div class="row g-1">
                                        <div class="col-sm-4">
                                            <label class="form-label mb-25">رقم النوع (0 = تلقائي)</label>
                                            <input type="number" id="qr-type-number" class="form-control" min="0"
                                                max="40" value="0">
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="form-label mb-25">وضع الترميز</label>
                                            <select id="qr-mode" class="form-select">
                                                <option value="Byte" selected>Byte</option>
                                                <option value="Numeric">Numeric</option>
                                                <option value="Alphanumeric">Alphanumeric</option>
                                                <option value="Kanji">Kanji</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="form-label mb-25">مستوى تصحيح الخطأ</label>
                                            <select id="qr-ecl" class="form-select">
                                                <option value="L">L (منخفض)</option>
                                                <option value="M">M (متوسط)</option>
                                                <option value="Q">Q (جيد)</option>
                                                <option value="H" selected>H (عالي)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- end ⑤ --}}

                    </div>{{-- end accordion --}}

                    {{-- Actions --}}
                    <div class="d-flex align-items-center gap-1 flex-wrap p-1">
                        <button type="button" id="btn-reset" class="btn btn-outline-secondary">
                            <i data-feather="refresh-cw" class="me-25"></i> إعادة تعيين
                        </button>
                        <button id="btn-save-design" type="button" class="btn btn-primary">
                            <i data-feather="save" class="me-25"></i> حفظ الإعدادات
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>
</form>
@endsection

@section('js')
<script src="{{ asset('admin/vendors/js/jspdf.umd.min.js') }}"></script>
<script src="{{ asset('admin/vendors/js/qr-code-styling/qr-code-styling.js') }}"></script>
<script>
    $(function() {
            var BASE = "{{ config('app.frontend_url') }}/";
            var SLUG = "{{ $branch->slug }}";
            var QR_IMG = "{{ $qr->qr_image ?? '' }}";
            var QR_ON = {{ $qr?->is_qr_image_enabled ? 'true' : 'false' }};

            // ── State ────────────────────────────────────────────────────────────────
            var currentUrl = BASE + SLUG;
            var customLogoB64 = null;
            var branchQrImg = QR_IMG;
            var branchQrEnabled = QR_ON;
            var qrInstance = null;

            var opts = {
                width: 300,
                height: 300,
                margin: 0,
                data: currentUrl,
                dotsOptions: {
                    color: '#000000',
                    type: 'square'
                },
                backgroundOptions: {
                    color: '#ffffff'
                },
                cornersSquareOptions: {
                    color: '#000000',
                    type: 'square'
                },
                cornersDotOptions: {
                    color: '#000000',
                    type: 'square'
                },
                imageOptions: {
                    crossOrigin: 'anonymous',
                    margin: 3,
                    imageSize: 0.4,
                    hideBackgroundDots: true
                },
                qrOptions: {
                    typeNumber: 0,
                    mode: 'Byte',
                    errorCorrectionLevel: 'H'
                }
            };

            // ── Saved settings from DB ────────────────────────────────────────────────
            var SAVED = {!! $qr
                ? json_encode([
                    'dot_type' => $qr->dot_type,
                    'dot_color' => $qr->dot_color,
                    'dot_color_type' => $qr->dot_color_type,
                    'dot_color_2' => $qr->dot_color_2,
                    'dot_gradient_type' => $qr->dot_gradient_type,
                    'dot_gradient_rotation' => $qr->dot_gradient_rotation,
                    'bg_color' => $qr->bg_color,
                    'bg_color_type' => $qr->bg_color_type,
                    'bg_color_2' => $qr->bg_color_2,
                    'bg_gradient_type' => $qr->bg_gradient_type,
                    'bg_gradient_rotation' => $qr->bg_gradient_rotation,
                    'corner_square_type' => $qr->corner_square_type,
                    'corner_square_color' => $qr->corner_square_color,
                    'corner_square_color_type' => $qr->corner_square_color_type,
                    'corner_square_color_2' => $qr->corner_square_color_2,
                    'corner_square_gradient_type' => $qr->corner_square_gradient_type,
                    'corner_square_gradient_rotation' => $qr->corner_square_gradient_rotation,
                    'corner_dot_type' => $qr->corner_dot_type,
                    'corner_dot_color' => $qr->corner_dot_color,
                    'corner_dot_color_type' => $qr->corner_dot_color_type,
                    'corner_dot_color_2' => $qr->corner_dot_color_2,
                    'corner_dot_gradient_type' => $qr->corner_dot_gradient_type,
                    'corner_dot_gradient_rotation' => $qr->corner_dot_gradient_rotation,
                    'width' => $qr->width,
                    'height' => $qr->height,
                    'margin' => $qr->margin,
                    'hide_background_dots' => (bool) $qr->hide_background_dots,
                    'image_size' => (float) $qr->image_size,
                    'image_margin' => $qr->image_margin,
                    'type_number' => $qr->type_number,
                    'mode' => $qr->mode,
                    'error_correction_level' => $qr->error_correction_level,
                ])
                : 'null' !!};

            // ── Sync hidden form inputs ────────────────────────────────────────────────
            function syncForm() {
                var dotGradient = opts.dotsOptions.gradient;
                var bgGradient = opts.backgroundOptions.gradient;
                var csGradient = opts.cornersSquareOptions.gradient;
                var cdGradient = opts.cornersDotOptions.gradient;

                $('#f-dot-type').val(opts.dotsOptions.type);
                $('#f-dot-color').val(opts.dotsOptions.color || $('#dot-color').val());
                $('#f-dot-color-type').val(dotGradient ? 'gradient' : 'single');
                $('#f-dot-color-2').val(dotGradient ? (dotGradient.colorStops[1] || {}).color : '');
                $('#f-dot-gradient-type').val($('#dot-gradient-type').val());
                $('#f-dot-gradient-rotation').val($('#dot-gradient-rotation').val());

                $('#f-bg-color').val(opts.backgroundOptions.color || $('#bg-color').val());
                $('#f-bg-color-type').val(bgGradient ? 'gradient' : 'single');
                $('#f-bg-color-2').val(bgGradient ? (bgGradient.colorStops[1] || {}).color : '');
                $('#f-bg-gradient-type').val($('#bg-gradient-type').val());
                $('#f-bg-gradient-rotation').val($('#bg-gradient-rotation').val());

                $('#f-corner-square-type').val(opts.cornersSquareOptions.type);
                $('#f-corner-square-color').val(opts.cornersSquareOptions.color || $('#cs-color').val());
                $('#f-corner-square-color-type').val(csGradient ? 'gradient' : 'single');
                $('#f-corner-square-color-2').val(csGradient ? (csGradient.colorStops[1] || {}).color : '');
                $('#f-corner-square-gradient-type').val($('#cs-gradient-type').val());
                $('#f-corner-square-gradient-rotation').val($('#cs-gradient-rotation').val());

                $('#f-corner-dot-type').val(opts.cornersDotOptions.type);
                $('#f-corner-dot-color').val(opts.cornersDotOptions.color || $('#cd-color').val());
                $('#f-corner-dot-color-type').val(cdGradient ? 'gradient' : 'single');
                $('#f-corner-dot-color-2').val(cdGradient ? (cdGradient.colorStops[1] || {}).color : '');
                $('#f-corner-dot-gradient-type').val($('#cd-gradient-type').val());
                $('#f-corner-dot-gradient-rotation').val($('#cd-gradient-rotation').val());

                $('#f-width').val(opts.width);
                $('#f-height').val(opts.height);
                $('#f-margin').val(opts.margin);
                $('#f-hide-background-dots').val(opts.imageOptions.hideBackgroundDots ? 1 : 0);
                $('#f-image-size').val(opts.imageOptions.imageSize);
                $('#f-image-margin').val(opts.imageOptions.margin);
                $('#f-use-branch-img').val($('#use-branch-img').is(':checked') ? 1 : 0);
                $('#f-type-number').val(opts.qrOptions.typeNumber);
                $('#f-mode').val(opts.qrOptions.mode);
                $('#f-error-correction-level').val(opts.qrOptions.errorCorrectionLevel);
                $('#f-is-qr-image-enabled').val($('#use-branch-img').is(':checked') ? 1 : 0);
            }

            // ── Helpers ──────────────────────────────────────────────────────────────
            function activeLogoSrc() {
                if (customLogoB64) {
                    return customLogoB64;
                }
                if ($('#use-branch-img').is(':checked') && branchQrImg) {
                    return branchQrImg;
                }
                return '';
            }

            var renderTimer = null;

            function render() {
                clearTimeout(renderTimer);
                renderTimer = setTimeout(function() {
                    if (!currentUrl) {
                        return;
                    }
                    opts.data = currentUrl;
                    opts.image = activeLogoSrc();

                    // Always destroy and recreate to ensure gradient removal is fully applied.
                    // qrInstance.update() does not reliably clear a previously set gradient.
                    $('#qr-canvas').empty();
                    qrInstance = new QRCodeStyling(opts);
                    qrInstance.append($('#qr-canvas')[0]);

                    $('#qr-hint').hide();
                    $('#download-buttons').removeClass('d-none');
                    $('#qr-link-display').val(currentUrl);
                    $('#qr-link-wrap').removeClass('d-none');
                    syncForm();
                    feather.replace();
                }, 50);
            }

            function syncBranchImgUI() {
                if (branchQrImg) {
                    $('#branch-img-toggle-wrap').show();
                    $('#use-branch-img').prop('checked', branchQrEnabled);
                    if (branchQrEnabled) {
                        $('#branch-img-preview').attr('src', branchQrImg);
                        $('#branch-img-preview-wrap').removeClass('d-none');
                    } else {
                        $('#branch-img-preview-wrap').addClass('d-none');
                    }
                } else {
                    $('#branch-img-toggle-wrap').hide();
                    $('#use-branch-img').prop('checked', false);
                    $('#branch-img-preview-wrap').addClass('d-none');
                }
            }

            function buildGradient(prefix) {
                return {
                    type: $('#' + prefix + '-gradient-type').val(),
                    rotation: (parseFloat($('#' + prefix + '-gradient-rotation').val()) || 0) * Math.PI / 180,
                    colorStops: [{
                            offset: 0,
                            color: $('#' + prefix + '-color').val()
                        },
                        {
                            offset: 1,
                            color: $('#' + prefix + '-color-2').val()
                        }
                    ]
                };
            }

            function applyGradientToggle(prefix, optsKey) {
                var isGradient = $('input[name="' + prefix + '-color-type"]:checked').val() === 'gradient';
                $('#' + prefix + '-gradient-row').toggleClass('visible', isGradient);
                if (isGradient) {
                    opts[optsKey].gradient = buildGradient(prefix);
                    delete opts[optsKey].color;
                } else {
                    delete opts[optsKey].gradient;
                    var colorMap = {
                        dot: '#dot-color',
                        bg: '#bg-color',
                        cs: '#cs-color',
                        cd: '#cd-color'
                    };
                    opts[optsKey].color = $(colorMap[prefix]).val();
                }
            }

            // ── Load saved settings into opts + visible UI controls ──────────────────
            function loadSaved() {
                if (!SAVED) {
                    return;
                }

                // ── opts: sizes ───────────────────────────────────────────────────────
                opts.width = opts.height = SAVED.width;
                opts.margin = SAVED.margin;

                // ── opts: dots ────────────────────────────────────────────────────────
                opts.dotsOptions.type = SAVED.dot_type;
                if (SAVED.dot_color_type === 'gradient' && SAVED.dot_color_2) {
                    opts.dotsOptions.gradient = {
                        type: SAVED.dot_gradient_type,
                        rotation: SAVED.dot_gradient_rotation * Math.PI / 180,
                        colorStops: [{
                            offset: 0,
                            color: SAVED.dot_color
                        }, {
                            offset: 1,
                            color: SAVED.dot_color_2
                        }]
                    };
                    delete opts.dotsOptions.color;
                } else {
                    opts.dotsOptions.color = SAVED.dot_color;
                    delete opts.dotsOptions.gradient;
                }

                // ── opts: background ──────────────────────────────────────────────────
                if (SAVED.bg_color_type === 'gradient' && SAVED.bg_color_2) {
                    opts.backgroundOptions.gradient = {
                        type: SAVED.bg_gradient_type,
                        rotation: SAVED.bg_gradient_rotation * Math.PI / 180,
                        colorStops: [{
                            offset: 0,
                            color: SAVED.bg_color
                        }, {
                            offset: 1,
                            color: SAVED.bg_color_2
                        }]
                    };
                    delete opts.backgroundOptions.color;
                } else {
                    opts.backgroundOptions.color = SAVED.bg_color;
                    delete opts.backgroundOptions.gradient;
                }

                // ── opts: corner square ───────────────────────────────────────────────
                opts.cornersSquareOptions.type = SAVED.corner_square_type;
                if (SAVED.corner_square_color_type === 'gradient' && SAVED.corner_square_color_2) {
                    opts.cornersSquareOptions.gradient = {
                        type: SAVED.corner_square_gradient_type,
                        rotation: SAVED.corner_square_gradient_rotation * Math.PI / 180,
                        colorStops: [{
                            offset: 0,
                            color: SAVED.corner_square_color
                        }, {
                            offset: 1,
                            color: SAVED.corner_square_color_2
                        }]
                    };
                    delete opts.cornersSquareOptions.color;
                } else {
                    opts.cornersSquareOptions.color = SAVED.corner_square_color;
                    delete opts.cornersSquareOptions.gradient;
                }

                // ── opts: corner dot ──────────────────────────────────────────────────
                opts.cornersDotOptions.type = SAVED.corner_dot_type;
                if (SAVED.corner_dot_color_type === 'gradient' && SAVED.corner_dot_color_2) {
                    opts.cornersDotOptions.gradient = {
                        type: SAVED.corner_dot_gradient_type,
                        rotation: SAVED.corner_dot_gradient_rotation * Math.PI / 180,
                        colorStops: [{
                            offset: 0,
                            color: SAVED.corner_dot_color
                        }, {
                            offset: 1,
                            color: SAVED.corner_dot_color_2
                        }]
                    };
                    delete opts.cornersDotOptions.color;
                } else {
                    opts.cornersDotOptions.color = SAVED.corner_dot_color;
                    delete opts.cornersDotOptions.gradient;
                }

                // ── opts: image options ───────────────────────────────────────────────
                opts.imageOptions.hideBackgroundDots = SAVED.hide_background_dots;
                opts.imageOptions.imageSize = SAVED.image_size;
                opts.imageOptions.margin = SAVED.image_margin;

                // ── opts: qr options ──────────────────────────────────────────────────
                opts.qrOptions.typeNumber = SAVED.type_number;
                opts.qrOptions.mode = SAVED.mode;
                opts.qrOptions.errorCorrectionLevel = SAVED.error_correction_level;

                // ── UI: style option tiles ────────────────────────────────────────────
                $('.style-option[data-type="dot"]').removeClass('selected');
                $('.style-option[data-type="dot"][data-value="' + SAVED.dot_type + '"]').addClass('selected');
                $('.style-option[data-type="cornerSquare"]').removeClass('selected');
                $('.style-option[data-type="cornerSquare"][data-value="' + SAVED.corner_square_type + '"]')
                    .addClass('selected');
                $('.style-option[data-type="cornerDot"]').removeClass('selected');
                $('.style-option[data-type="cornerDot"][data-value="' + SAVED.corner_dot_type + '"]').addClass(
                    'selected');

                // ── UI: dot colors ────────────────────────────────────────────────────
                $('#dot-color').val(SAVED.dot_color);
                if (SAVED.dot_color_type === 'gradient') {
                    $('#dot-gradient').prop('checked', true);
                    $('#dot-color-2').val(SAVED.dot_color_2);
                    $('#dot-gradient-type').val(SAVED.dot_gradient_type);
                    $('#dot-gradient-rotation').val(SAVED.dot_gradient_rotation);
                    $('#dot-gradient-row').addClass('visible');
                } else {
                    $('#dot-single').prop('checked', true);
                    $('#dot-gradient-row').removeClass('visible');
                }

                // ── UI: background colors ─────────────────────────────────────────────
                $('#bg-color').val(SAVED.bg_color);
                if (SAVED.bg_color_type === 'gradient') {
                    $('#bg-gradient').prop('checked', true);
                    $('#bg-color-2').val(SAVED.bg_color_2);
                    $('#bg-gradient-type').val(SAVED.bg_gradient_type);
                    $('#bg-gradient-rotation').val(SAVED.bg_gradient_rotation);
                    $('#bg-gradient-row').addClass('visible');
                } else {
                    $('#bg-single').prop('checked', true);
                    $('#bg-gradient-row').removeClass('visible');
                }

                // ── UI: corner square colors ──────────────────────────────────────────
                $('#cs-color').val(SAVED.corner_square_color);
                if (SAVED.corner_square_color_type === 'gradient') {
                    $('#cs-gradient').prop('checked', true);
                    $('#cs-color-2').val(SAVED.corner_square_color_2);
                    $('#cs-gradient-type').val(SAVED.corner_square_gradient_type);
                    $('#cs-gradient-rotation').val(SAVED.corner_square_gradient_rotation);
                    $('#cs-gradient-row').addClass('visible');
                } else {
                    $('#cs-single').prop('checked', true);
                    $('#cs-gradient-row').removeClass('visible');
                }

                // ── UI: corner dot colors ─────────────────────────────────────────────
                $('#cd-color').val(SAVED.corner_dot_color);
                if (SAVED.corner_dot_color_type === 'gradient') {
                    $('#cd-gradient').prop('checked', true);
                    $('#cd-color-2').val(SAVED.corner_dot_color_2);
                    $('#cd-gradient-type').val(SAVED.corner_dot_gradient_type);
                    $('#cd-gradient-rotation').val(SAVED.corner_dot_gradient_rotation);
                    $('#cd-gradient-row').addClass('visible');
                } else {
                    $('#cd-single').prop('checked', true);
                    $('#cd-gradient-row').removeClass('visible');
                }

                // ── UI: sizes ─────────────────────────────────────────────────────────
                $('#qr-size').val(SAVED.width);
                $('#size-val').text(SAVED.width);
                $('#qr-margin').val(SAVED.margin);
                $('#margin-val').text(SAVED.margin);

                // ── UI: image options ─────────────────────────────────────────────────
                $('#hide-bg-dots').prop('checked', SAVED.hide_background_dots);
                $('#img-size').val(SAVED.image_size);
                $('#img-size-val').text(parseFloat(SAVED.image_size).toFixed(2));
                $('#img-margin').val(SAVED.image_margin);
                $('#img-margin-val').text(SAVED.image_margin);

                // ── UI: qr options ────────────────────────────────────────────────────
                $('#qr-type-number').val(SAVED.type_number);
                $('#qr-mode').val(SAVED.mode);
                $('#qr-ecl').val(SAVED.error_correction_level);
            }

            // ── Auto-render ───────────────────────────────────────────────────────────
            loadSaved();
            syncBranchImgUI();
            render();

            // ── Save: sync form then submit ────────────────────────────────────────────
            $('#btn-save-design').on('click', function() {
                syncForm();
                $('#qr-settings-form').submit();
            });

            // ── Copy QR Link ──────────────────────────────────────────────────────────
            $('#btn-copy-qr').on('click', function() {
                var val = $('#qr-link-display').val();
                if (!val) return;

                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(val).then(function() {
                        showCopied();
                    });
                } else {
                    var $temp = $('<textarea>');
                    $('body').append($temp);
                    $temp.val(val).select();
                    document.execCommand('copy');
                    $temp.remove();
                    showCopied();
                }
            });

            function showCopied() {
                $('#btn-copy-qr').find('i').replaceWith(
                    '<i data-feather="check" style="width:13px;height:13px;" class="me-25"></i>');
                $('#copy-qr-label').text('تم النسخ!');
                feather.replace();
                setTimeout(function() {
                    $('#btn-copy-qr').find('i').replaceWith(
                        '<i data-feather="copy" style="width:13px;height:13px;" class="me-25"></i>');
                    $('#copy-qr-label').text('نسخ');
                    feather.replace();
                }, 2000);
            }


            // ── Use Branch QR Image Toggle ────────────────────────────────────────────
            $('#use-branch-img').on('change', function() {
                if ($(this).is(':checked') && branchQrImg) {
                    $('#branch-img-preview').attr('src', branchQrImg);
                    $('#branch-img-preview-wrap').removeClass('d-none');
                }
                render();
            });

            // ── Custom Logo Upload ────────────────────────────────────────────────────
            $('#logo-upload').on('change', function() {
                var file = this.files[0];
                if (!file) {
                    return;
                }
                // Transfer file to the hidden form input so it submits with the form
                var dt = new DataTransfer();
                dt.items.add(file);
                document.getElementById('f-qr-image').files = dt.files;
                var reader = new FileReader();
                reader.onload = function(e) {
                    customLogoB64 = e.target.result;
                    $('#qr-logo-preview').attr('src', customLogoB64);
                    $('#logo-preview-wrap').show();
                    $('#btn-remove-logo').show();
                    render();
                };
                reader.readAsDataURL(file);
            });

            $('#btn-remove-logo').on('click', function() {
                customLogoB64 = null;
                $('#logo-upload').val('');
                $('#logo-preview-wrap').hide();
                $(this).hide();
                render();
            });

            // ── Style Options ─────────────────────────────────────────────────────────
            $(document).on('click', '.style-option', function() {
                var type = $(this).data('type'),
                    val = $(this).data('value');
                $('.style-option[data-type="' + type + '"]').removeClass('selected');
                $(this).addClass('selected');
                if (type === 'dot') {
                    opts.dotsOptions.type = val;
                } else if (type === 'cornerSquare') {
                    opts.cornersSquareOptions.type = val;
                } else if (type === 'cornerDot') {
                    opts.cornersDotOptions.type = val;
                }
                render();
            });

            // ── Colors ────────────────────────────────────────────────────────────────
            $('input[name="dot-color-type"]').on('change', function() {
                applyGradientToggle('dot', 'dotsOptions');
                render();
            });
            $('input[name="bg-color-type"]').on('change', function() {
                applyGradientToggle('bg', 'backgroundOptions');
                render();
            });
            $('input[name="cs-color-type"]').on('change', function() {
                applyGradientToggle('cs', 'cornersSquareOptions');
                render();
            });
            $('input[name="cd-color-type"]').on('change', function() {
                applyGradientToggle('cd', 'cornersDotOptions');
                render();
            });

            $('#dot-color').on('input', function() {
                if ($('#dot-single').is(':checked')) {
                    opts.dotsOptions.color = this.value;
                    render();
                } else {
                    opts.dotsOptions.gradient = buildGradient('dot');
                    render();
                }
            });
            $('#dot-color-2,#dot-gradient-type,#dot-gradient-rotation').on('input change', function() {
                if ($('#dot-gradient').is(':checked')) {
                    opts.dotsOptions.gradient = buildGradient('dot');
                    render();
                }
            });

            $('#bg-color').on('input', function() {
                if ($('#bg-single').is(':checked')) {
                    opts.backgroundOptions.color = this.value;
                    render();
                } else {
                    opts.backgroundOptions.gradient = buildGradient('bg');
                    render();
                }
            });
            $('#bg-color-2,#bg-gradient-type,#bg-gradient-rotation').on('input change', function() {
                if ($('#bg-gradient').is(':checked')) {
                    opts.backgroundOptions.gradient = buildGradient('bg');
                    render();
                }
            });

            $('#cs-color').on('input', function() {
                if ($('#cs-single').is(':checked')) {
                    opts.cornersSquareOptions.color = this.value;
                    render();
                } else {
                    opts.cornersSquareOptions.gradient = buildGradient('cs');
                    render();
                }
            });
            $('#cs-color-2,#cs-gradient-type,#cs-gradient-rotation').on('input change', function() {
                if ($('#cs-gradient').is(':checked')) {
                    opts.cornersSquareOptions.gradient = buildGradient('cs');
                    render();
                }
            });

            $('#cd-color').on('input', function() {
                if ($('#cd-single').is(':checked')) {
                    opts.cornersDotOptions.color = this.value;
                    render();
                } else {
                    opts.cornersDotOptions.gradient = buildGradient('cd');
                    render();
                }
            });
            $('#cd-color-2,#cd-gradient-type,#cd-gradient-rotation').on('input change', function() {
                if ($('#cd-gradient').is(':checked')) {
                    opts.cornersDotOptions.gradient = buildGradient('cd');
                    render();
                }
            });

            // ── Size & Margin ─────────────────────────────────────────────────────────
            $('#qr-size').on('input', function() {
                var s = parseInt(this.value);
                $('#size-val').text(s);
                opts.width = opts.height = s;
                render();
            });

            $('#qr-margin').on('input', function() {
                var m = parseInt(this.value);
                $('#margin-val').text(m);
                opts.margin = m;
                render();
            });

            // ── Image Options ─────────────────────────────────────────────────────────
            $('#hide-bg-dots').on('change', function() {
                opts.imageOptions.hideBackgroundDots = $(this).is(':checked');
                render();
            });

            $('#img-size').on('input', function() {
                var v = parseFloat(this.value).toFixed(2);
                $('#img-size-val').text(v);
                opts.imageOptions.imageSize = parseFloat(v);
                render();
            });

            $('#img-margin').on('input', function() {
                var v = parseInt(this.value);
                $('#img-margin-val').text(v);
                opts.imageOptions.margin = v;
                render();
            });

            // ── QR Options ────────────────────────────────────────────────────────────
            $('#qr-type-number').on('input', function() {
                opts.qrOptions.typeNumber = parseInt(this.value) || 0;
                render();
            });

            $('#qr-mode').on('change', function() {
                opts.qrOptions.mode = this.value;
                render();
            });

            $('#qr-ecl').on('change', function() {
                opts.qrOptions.errorCorrectionLevel = this.value;
                render();
            });

            // ── Download ──────────────────────────────────────────────────────────────
            $('#btn-png').on('click', function() {
                if (qrInstance) {
                    qrInstance.download({
                        name: 'qr-' + Date.now(),
                        extension: 'png'
                    });
                }
            });
            $('#btn-svg').on('click', function() {
                if (qrInstance) {
                    qrInstance.download({
                        name: 'qr-' + Date.now(),
                        extension: 'svg'
                    });
                }
            });
            $('#btn-pdf').on('click', function() {
                if (!qrInstance) {
                    return;
                }
                var canvas = document.querySelector('#qr-canvas canvas');
                if (!canvas) {
                    return;
                }

                var dataUrl = canvas.toDataURL('image/png');

                var logicalSize = opts.width || 300; 
                var PX_TO_MM    = 25.4 / 96;         
                var qrMm        = logicalSize * PX_TO_MM; 
                
                var pageW = 210, pageH = 297;

                var x = (pageW - qrMm) / 2;
                var y = (pageH - qrMm) / 2;

                var pdf = new jspdf.jsPDF({
                    orientation: 'p',
                    unit: 'mm',
                    format: 'a4'
                });
                pdf.addImage(dataUrl, 'PNG', x, y, qrMm, qrMm);
                pdf.save('qr-' + Date.now() + '.pdf');
            });

            // ── Reset ─────────────────────────────────────────────────────────────────
            $('#btn-reset').on('click', function() {
                opts.dotsOptions = {
                    color: '#000000',
                    type: 'square'
                };
                opts.backgroundOptions = {
                    color: '#ffffff'
                };
                opts.cornersSquareOptions = {
                    color: '#000000',
                    type: 'square'
                };
                opts.cornersDotOptions = {
                    color: '#000000',
                    type: 'square'
                };
                opts.imageOptions = {
                    crossOrigin: 'anonymous',
                    margin: 3,
                    imageSize: 0.4,
                    hideBackgroundDots: true
                };
                opts.qrOptions = {
                    typeNumber: 0,
                    mode: 'Byte',
                    errorCorrectionLevel: 'H'
                };
                opts.width = opts.height = 300;
                opts.margin = 0;
                customLogoB64 = null;

                $('#dot-color,#cs-color,#cd-color').val('#000000');
                $('#dot-color-2,#cs-color-2,#cd-color-2').val('#ffffff');
                $('#bg-color').val('#ffffff');
                $('#bg-color-2').val('#cccccc');
                $('input[name="dot-color-type"][value="single"]').prop('checked', true);
                $('input[name="bg-color-type"][value="single"]').prop('checked', true);
                $('input[name="cs-color-type"][value="single"]').prop('checked', true);
                $('input[name="cd-color-type"][value="single"]').prop('checked', true);
                $('.gradient-row').removeClass('visible');
                $('#dot-gradient-type,#bg-gradient-type,#cs-gradient-type,#cd-gradient-type').val('linear');
                $('#dot-gradient-rotation,#bg-gradient-rotation,#cs-gradient-rotation,#cd-gradient-rotation')
                    .val(0);
                $('#qr-size').val(300);
                $('#size-val').text(300);
                $('#qr-margin').val(0);
                $('#margin-val').text(0);
                $('#hide-bg-dots').prop('checked', true);
                $('#img-size').val(0.4);
                $('#img-size-val').text('0.40');
                $('#img-margin').val(3);
                $('#img-margin-val').text(3);
                $('#logo-upload').val('');
                $('#logo-preview-wrap').hide();
                $('#btn-remove-logo').hide();
                $('#qr-type-number').val(0);
                $('#qr-mode').val('Byte');
                $('#qr-ecl').val('H');
                $('.style-option[data-type="dot"][data-value="square"]').addClass('selected').siblings()
                    .removeClass('selected');
                $('.style-option[data-type="cornerSquare"][data-value="square"]').addClass('selected')
                    .siblings().removeClass('selected');
                $('.style-option[data-type="cornerDot"][data-value="square"]').addClass('selected')
                    .siblings().removeClass('selected');
                render();

            });
        });
</script>
@endsection