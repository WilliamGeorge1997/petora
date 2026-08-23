@extends('common::layouts.master')

@section('bodyAttributes')
    data-bs-spy="scroll" data-bs-target="#settings-nav" data-bs-offset="120"
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin/vendors/css/forms/select/select2.min.css">
    <style>
        /* Sidebar nav */
        #settings-nav {
            top: 7rem;
        }

        .settings-aside .card {
            max-height: calc(100vh - 8rem);
            overflow-y: auto;
        }

        /* Shared nav-link styles for sidebar + offcanvas */
        .settings-aside .nav-link,
        #settingsOffcanvas .nav-link {
            border-radius: .5rem;
            color: var(--bs-body-color);
            transition: background .15s, color .15s;
        }

        .settings-aside .nav-link {
            font-size: .88rem;
        }

        #settingsOffcanvas .nav-link {
            font-size: .92rem;
            padding: .55rem .75rem;
        }

        .settings-aside .nav-link i,
        #settingsOffcanvas .nav-link i {
            width: 20px;
            text-align: center;
            opacity: .7;
        }

        .settings-aside .nav-link:hover,
        #settingsOffcanvas .nav-link:hover {
            background: rgba(115, 103, 240, .08);
            color: var(--bs-body-color);
        }

        .settings-aside .nav-link.active,
        #settingsOffcanvas .nav-link.active {
            background: var(--bs-primary);
            color: #fff;
            font-weight: 600;
        }

        .settings-aside .nav-link.active i,
        #settingsOffcanvas .nav-link.active i {
            opacity: 1;
        }

        /* Mobile FAB */
        .settings-mobile-fab {
            position: fixed;
            bottom: 6rem;
            left: 1.9rem;
            z-index: 1050;
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: linear-gradient(118deg, var(--bs-primary), rgba(115, 103, 240, .75));
            color: #fff;
            border: none;
            box-shadow: 0 4px 18px rgba(115, 103, 240, .45);
            transition: transform .15s, box-shadow .15s;
        }

        .settings-mobile-fab:hover {
            transform: scale(1.08);
            box-shadow: 0 6px 22px rgba(115, 103, 240, .55);
        }

        /* Settings card */
        .settings-card {
            border-radius: 1rem;
        }

        .settings-card .card-header {
            background: linear-gradient(118deg, var(--bs-primary), rgba(115, 103, 240, .75));
            border-bottom: none;
            border-radius: 1rem 1rem 0 0 !important;
        }

        /* Section divider */
        .settings-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #ebe9f1;
        }

        .settings-divider .divider-icon {
            width: 34px;
            height: 34px;
            background: linear-gradient(118deg, var(--bs-primary), rgba(115, 103, 240, .75));
        }

        /* Page title icon */
        .settings-page-title .title-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(118deg, var(--bs-primary), rgba(115, 103, 240, .75));
        }

        /* Image upload areas */
        .h-6 {
            height: 6.25rem;
        }

        .h-18 {
            height: 18.75rem;
        }

        .bottom-minus-20 {
            bottom: -20%;
        }

        .right-5 {
            right: 5%;
        }

        .cover-upload,
        .logo-upload {
            border: 2px dashed #d8d6de;
            border-radius: .857rem;
            transition: border-color .2s, background .2s;
            overflow: visible;
        }

        .cover-upload:hover {
            border-color: var(--bs-primary);
            background: rgba(115, 103, 240, .03);
        }

        .logo-upload:hover {
            border-color: var(--bs-primary);
        }

        /* App background upload (phone preview) */
        .app-bg-wrapper {
            position: absolute;
            bottom: -20%;
            left: 5%;
            z-index: 3;
        }

        .app-bg-upload {
            border: 2px dashed #d8d6de;
            border-radius: 1.5rem;
            transition: border-color .2s, background .2s;
            width: 90px;
            height: 158px;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            background: #f0f0f0;
            overflow: visible;
        }

        .app-bg-upload:hover {
            border-color: var(--bs-primary);
        }

        .app-bg-upload::before {
            content: '';
            position: absolute;
            top: 6px;
            left: 50%;
            transform: translateX(-50%);
            width: 24px;
            height: 4px;
            background: rgba(255, 255, 255, .5);
            border-radius: 3px;
            z-index: 4;
            pointer-events: none;
        }

        .app-bg-inner {
            position: absolute;
            inset: 0;
            border-radius: 1.4rem;
            overflow: hidden;
        }

        .app-bg-upload .app-bg-preview-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 1.4rem;
            filter: brightness(0.6);
        }

        .app-bg-upload .app-bg-overlay {
            position: absolute;
            inset: 0;
            border-radius: 1.4rem;
            z-index: 2;
            pointer-events: none;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 6px 5px;
            gap: 4px;
        }

        /* Skeleton product cards */
        .app-bg-skel-row {
            display: flex;
            flex-direction: column;
            gap: 4px;
            width: 100%;
        }

        .app-bg-skel {
            width: 100%;
            background: rgba(255, 255, 255, .18);
            border-radius: .4rem;
            padding: 4px;
            backdrop-filter: blur(2px);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .app-bg-skel .skel-img {
            width: 22px;
            height: 22px;
            flex-shrink: 0;
            background: rgba(255, 255, 255, .25);
            border-radius: .3rem;
        }

        .app-bg-skel .skel-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .app-bg-skel .skel-line {
            height: 4px;
            background: rgba(255, 255, 255, .3);
            border-radius: 3px;
        }

        .app-bg-skel .skel-line.short {
            width: 55%;
            background: rgba(255, 255, 255, .2);
        }

        /* Remove buttons on images */
        .app-bg-remove-btn,
        .logo-remove-btn,
        .cover-remove-btn {
            position: absolute;
            top: -6px;
            right: -6px;
            z-index: 10;
            width: 18px;
            height: 18px;
            padding: 0;
            font-size: .6rem;
            line-height: 1;
            border-radius: 50% !important;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endsection

@section('content')
    @php
        $hasTheme = filled($branch->settings?->theme);

        if ($hasTheme) {
            $theme = (int) $branch->settings->theme;
            $limitedColors = in_array($theme, [2, 3, 4, 5, 6], true);
            $showsAllColors = !$limitedColors;
            $showsSecondaryColor = $showsAllColors || $theme === 4;
            $showsIntro = in_array($theme, [1, 4], true);
            $showsTheme14Images = in_array($theme, [2, 3, 5], true);
            $showsTax = $theme !== 6;
            $showsOtherSettings = true;
            $isTheme6 = $theme === 6;
        } else {
            $showsAllColors = true;
            $showsSecondaryColor = true;
            $showsIntro = true;
            $showsTheme14Images = true;
            $showsTax = true;
            $showsOtherSettings = true;
            $isTheme6 = false;
        }
    @endphp
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">الاعدادت</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                            @if (auth()->guard('admin')->user()->hasRole('Super Admin'))
                                <li class="breadcrumb-item">
                                    <a href="{{ route('branches.index') }}">الفروع</a>
                                </li>
                            @endif
                            <li class="breadcrumb-item">{{ $branch->getTranslation('title', 'ar') }}</li>
                            <li class="breadcrumb-item active">الاعدادات</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-3 mx-0">
        <aside class="col-12 col-lg-4 col-xxl-3 settings-aside order-2 order-lg-1 d-none d-lg-block">
            <nav id="settings-nav" class="card p-2 position-sticky">
                <div class="px-1 pt-1 pb-2 mb-1 border-bottom">
                    <span class="fw-bold small text-muted text-uppercase nav-section-label">القائمة</span>
                </div>
                <ul class="nav flex-column list-unstyled mb-0 gap-1">
                    <li><a class="nav-link" href="#section-images"><i class="fa-solid fa-image me-2"></i>الصور</a></li>
                    <li><a class="nav-link" href="#section-general"><i class="fa-solid fa-gear me-2"></i>عن الفرع</a></li>
                    @if ($showsTax)
                        <li><a class="nav-link" href="#section-tax"><i class="fa-solid fa-percent me-2"></i>الضريبة</a></li>
                    @endif
                    <li><a class="nav-link" href="#section-wifi"><i class="fa-solid fa-wifi me-2"></i>الواي فاي</a></li>
                    <li><a class="nav-link" href="#section-social"><i class="fa-solid fa-share-nodes me-2"></i>مواقع
                            التواصل</a></li>
                    <li><a class="nav-link" href="#section-visual"><i class="fa-regular fa-eye me-2"></i>الهوية البصرية</a>
                    </li>
                    <li><a class="nav-link" href="#section-language"><i class="fa-solid fa-language me-2"></i>اللغة
                            والعملة</a></li>
                    @if ($showsOtherSettings)
                        <li><a class="nav-link" href="#section-other"><i class="fa-solid fa-square-check me-2"></i>إعدادات
                                أخرى</a></li>
                    @endif
                </ul>
            </nav>
        </aside>
        <div class="col-12 col-lg-8 col-xxl-9 order-1 order-lg-2">
            <div class="d-lg-none">
                <button class="settings-mobile-fab d-flex align-items-center justify-content-center fs-5" type="button"
                    data-bs-toggle="offcanvas" data-bs-target="#settingsOffcanvas" aria-controls="settingsOffcanvas">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="offcanvas offcanvas-start" tabindex="-1" id="settingsOffcanvas"
                    aria-labelledby="settingsOffcanvasLabel">
                    <div class="offcanvas-header border-bottom">
                        <h6 class="offcanvas-title fw-bold" id="settingsOffcanvasLabel">
                            <i class="fa-solid fa-gear me-2 text-primary"></i>الإعدادات
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body p-2">
                        <ul class="nav flex-column list-unstyled mb-0 gap-1" id="settings-mobile-nav">
                            <li><a class="nav-link" href="#section-images"><i class="fa-solid fa-image me-2"></i>الصور</a>
                            </li>
                            <li><a class="nav-link" href="#section-general"><i class="fa-solid fa-gear me-2"></i>عن
                                    الفرع</a></li>
                            @if ($showsTax)
                                <li><a class="nav-link" href="#section-tax"><i
                                            class="fa-solid fa-percent me-2"></i>الضريبة</a>
                                </li>
                            @endif
                            <li><a class="nav-link" href="#section-wifi"><i class="fa-solid fa-wifi me-2"></i>الواي فاي</a>
                            </li>
                            <li><a class="nav-link" href="#section-social"><i class="fa-solid fa-share-nodes me-2"></i>مواقع
                                    التواصل</a></li>
                            <li><a class="nav-link" href="#section-visual"><i class="fa-regular fa-eye me-2"></i>الهوية
                                    البصرية</a></li>
                            <li><a class="nav-link" href="#section-language"><i class="fa-solid fa-language me-2"></i>اللغة
                                    والعملة</a></li>
                            @if ($showsOtherSettings)
                                <li><a class="nav-link" href="#section-other"><i
                                            class="fa-solid fa-square-check me-2"></i>إعدادات أخرى</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <form enctype="multipart/form-data" method="POST"
                action="{{ route('branch.settings.update', ['id' => $branch->id]) }}">
                @csrf

                <div id="section-images" class="settings-divider d-flex align-items-center gap-3 my-4">
                    <div
                        class="divider-icon d-flex align-items-center justify-content-center rounded-2 text-white flex-shrink-0">
                        <i class="fa-solid fa-image"></i>
                    </div>
                    <span class="fw-bold">إعدادات الصور</span>
                </div>

                <div class="w-xl-75 position-relative my-5">
                    <div class="cover-upload bg-light h-18 d-flex justify-content-center align-items-center position-relative {{ $showsTheme14Images ? 'cursor-pointer' : '' }}"
                        @if ($showsTheme14Images) onclick="document.getElementById('header_image').click()" @endif>
                        @if ($showsTheme14Images)
                            <input type="file" id="header_image" name="header_image" class="d-none"
                                accept="image/jpeg,image/png,image/jpg">
                            <button type="button" id="cover_remove"
                                class="btn btn-danger cover-remove-btn {{ $branch->settings?->header_image ? '' : 'd-none' }}"
                                data-db-image="{{ $branch->settings?->header_image ? '1' : '' }}"
                                data-field="header_image" data-branch-id="{{ $branch->id }}">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                            <img id="cover_preview"
                                src="{{ $branch->settings?->header_image ? $branch->settings?->header_image : '' }}"
                                alt=""
                                class="{{ $branch->settings?->header_image ? '' : 'd-none' }} position-absolute top-0 start-0 w-100 h-100 object-fit-cover rounded-3">
                            <div id="cover_placeholder"
                                class="d-flex flex-column align-items-center {{ $branch->settings?->header_image ? 'd-none' : '' }}">
                                <i class="fa-solid fa-image fa-3x mb-2"></i>
                                <p class="mb-0">مقاس الصورة المفضل 1024 × 480، نقبل jpg, png, jpeg</p>
                            </div>
                        @endif
                    </div>

                    <div class="col-3 col-sm-2 bg-light logo-upload h-6 d-flex justify-content-center align-items-center position-absolute bottom-minus-20 right-5 cursor-pointer"
                        onclick="document.getElementById('logo').click()">
                        <input type="file" id="logo" name="logo" class="d-none"
                            accept="image/jpeg,image/png,image/jpg">
                        <button type="button" id="logo_remove"
                            class="btn btn-danger logo-remove-btn {{ $branch->settings?->logo ? '' : 'd-none' }}"
                            data-db-image="{{ $branch->settings?->logo ? '1' : '' }}" data-field="logo"
                            data-branch-id="{{ $branch->id }}">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                        <img id="logo_preview" src="{{ $branch->settings?->logo ? $branch->settings?->logo : '' }}"
                            alt=""
                            class="{{ $branch->settings?->logo ? '' : 'd-none' }} position-absolute top-0 start-0 w-100 h-100 object-fit-cover rounded-3">
                        <div id="logo_placeholder"
                            class="d-flex flex-column align-items-center {{ $branch->settings?->logo ? 'd-none' : '' }}">
                            <i class="fa-solid fa-image fa-2x"></i>
                            <span class="mt-1">الشعار</span>
                        </div>
                    </div>


                    <div class="app-bg-wrapper">
                        <div class="app-bg-upload" onclick="document.getElementById('app_background_image').click()">
                            <input type="file" id="app_background_image" name="app_background_image" class="d-none"
                                accept="image/jpeg,image/png,image/jpg">


                            <button type="button" id="app_bg_remove"
                                class="btn btn-danger app-bg-remove-btn {{ $branch->settings?->app_background_image ? '' : 'd-none' }}"
                                data-db-image="{{ $branch->settings?->app_background_image ? '1' : '' }}"
                                data-field="app_background_image" data-branch-id="{{ $branch->id }}">
                                <i class="fa-solid fa-xmark"></i>
                            </button>


                            <div class="app-bg-inner">
                                <img id="app_bg_preview" src="{{ $branch->settings?->app_background_image ?? '' }}"
                                    alt=""
                                    class="{{ $branch->settings?->app_background_image ? '' : 'd-none' }} app-bg-preview-img">

                                <div class="app-bg-overlay {{ $branch->settings?->app_background_image ? '' : 'd-none' }}"
                                    id="app_bg_skeleton_overlay">
                                    <div class="app-bg-skel-row">
                                        @foreach (range(1, 3) as $i)
                                            <div class="app-bg-skel">
                                                <div class="skel-img"></div>
                                                <div class="skel-body">
                                                    <div class="skel-line"></div>
                                                    <div class="skel-line short"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div id="app_bg_placeholder"
                                class="d-flex flex-column align-items-center text-center px-2 {{ $branch->settings?->app_background_image ? 'd-none' : '' }}">
                                <i class="fa-solid fa-mobile-screen fa-2x mb-1"></i>
                                <span style="font-size:.65rem;color:#6e6b7b">خلفية التطبيق</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    @error('app_background_image')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror

                    @error('header_image')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror

                    @error('logo')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div id="section-general" class="settings-divider d-flex align-items-center gap-3 my-4">
                    <div
                        class="divider-icon d-flex align-items-center justify-content-center rounded-2 text-white flex-shrink-0">
                        <i class="fa-solid fa-gear"></i>
                    </div>
                    <span class="fw-bold">إعدادات عامة</span>
                </div>

                {{-- About --}}
                <div class="w-xl-75">
                    <div class="card settings-card">
                        <div class="card-header">
                            <h3 class="text-white fs-6 mb-0">اعدادات عامة</h3>
                        </div>
                        <div class="card-body pt-4">
                            {{-- About --}}
                            <div class="col-12 lang-input-group">
                                <div class="mb-1 row align-items-center">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label">عن الفرع</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="lang-textarea-wrap">
                                            <div class="lang-flag-toggle">
                                                <button type="button" class="lang-flag-btn active" data-lang="ar"
                                                    onclick="handleLangInputChange2(this)">
                                                    <img src="{{ asset('admin/images/flags/sa.webp') }}" alt="AR">
                                                    AR
                                                </button>
                                                <button type="button" class="lang-flag-btn" data-lang="en"
                                                    onclick="handleLangInputChange2(this)">
                                                    <img src="{{ asset('admin/images/flags/us.webp') }}" alt="EN">
                                                    EN
                                                </button>
                                            </div>
                                            <textarea placeholder="عن الفرع باللغه العربية" class="form-control" name="about_ar">{{ old('about_ar', $branch->settings?->about_ar ?? '') }}</textarea>
                                            <textarea placeholder="عن الفرع باللغه الانجليزية" class="form-control d-none" name="about_en">{{ old('about_en', $branch->settings?->about_en ?? '') }}</textarea>
                                        </div>
                                        @error('about_ar')
                                            <p class="alert alert-danger lang-error lang-error-ar">{{ $message }}</p>
                                        @enderror
                                        @error('about_en')
                                            <p class="alert alert-danger lang-error lang-error-en d-none">{{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Terms --}}
                            <div class="col-12 lang-input-group mt-2">
                                <div class="mb-1 row align-items-center">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label">الشروط و الاحكام</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="lang-textarea-wrap">
                                            <div class="lang-flag-toggle">
                                                <button type="button" class="lang-flag-btn active" data-lang="ar"
                                                    onclick="handleLangInputChange2(this)">
                                                    <img src="{{ asset('admin/images/flags/sa.webp') }}" alt="AR">
                                                    AR
                                                </button>
                                                <button type="button" class="lang-flag-btn" data-lang="en"
                                                    onclick="handleLangInputChange2(this)">
                                                    <img src="{{ asset('admin/images/flags/us.webp') }}" alt="EN">
                                                    EN
                                                </button>
                                            </div>
                                            <textarea placeholder="الشروط و الاحكام باللغه العربية" class="form-control" name="terms_ar">{{ old('terms_ar', $branch->settings?->terms_ar ?? '') }}</textarea>
                                            <textarea placeholder="الشروط و الاحكام باللغه الانجليزية" class="form-control d-none" name="terms_en">{{ old('terms_en', $branch->settings?->terms_en ?? '') }}</textarea>
                                        </div>
                                        @error('terms_ar')
                                            <p class="alert alert-danger lang-error lang-error-ar">{{ $message }}</p>
                                        @enderror
                                        @error('terms_en')
                                            <p class="alert alert-danger lang-error lang-error-en d-none">{{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            @if ($showsIntro)
                                {{-- Intro Message --}}
                                <div class="col-12 lang-input-group mt-2">
                                    <div class="mb-1 row align-items-center">
                                        <div class="col-sm-3 text-center">
                                            <label class="col-form-label">الوصف في البداية</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="lang-textarea-wrap">
                                                <div class="lang-flag-toggle">
                                                    <button type="button" class="lang-flag-btn active" data-lang="ar"
                                                        onclick="handleLangInputChange2(this)">
                                                        <img src="{{ asset('admin/images/flags/sa.webp') }}"
                                                            alt="AR">
                                                        AR
                                                    </button>
                                                    <button type="button" class="lang-flag-btn" data-lang="en"
                                                        onclick="handleLangInputChange2(this)">
                                                        <img src="{{ asset('admin/images/flags/us.webp') }}"
                                                            alt="EN">
                                                        EN
                                                    </button>
                                                </div>
                                                <textarea placeholder="الوصف في البداية باللغه العربية" class="form-control" name="intro_message_ar">{{ old('intro_message_ar', $branch->settings?->intro_message_ar ?? '') }}</textarea>
                                                <textarea placeholder="الوصف في البداية باللغه الانجليزية" class="form-control d-none" name="intro_message_en">{{ old('intro_message_en', $branch->settings?->intro_message_en ?? '') }}</textarea>
                                            </div>
                                            @error('intro_message_ar')
                                                <p class="alert alert-danger lang-error lang-error-ar">{{ $message }}</p>
                                            @enderror
                                            @error('intro_message_en')
                                                <p class="alert alert-danger lang-error lang-error-en d-none">
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if ($showsTax)
                    {{-- Tax Settings --}}
                    <div id="section-tax" class="settings-divider d-flex align-items-center gap-3 my-4">
                        <div
                            class="divider-icon d-flex align-items-center justify-content-center rounded-2 text-white flex-shrink-0">
                            <i class="fa-solid fa-percent"></i>
                        </div>
                        <span class="fw-bold">إعدادات الضريبة</span>
                    </div>
                    <div class="w-xl-75">
                        <div class="card settings-card">
                            <div class="card-header">
                                <h3 class="text-white fs-6 mb-0">اعدادات الضريبة</h3>
                            </div>
                            <div class="card-body pt-4">
                                {{-- Tax Message --}}
                                <div class="col-12 lang-input-group">
                                    <div class="mb-1 row align-items-center">
                                        <div class="col-sm-3 text-center">
                                            <label class="col-form-label">رسالة الضريبة</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="lang-textarea-wrap">
                                                <div class="lang-flag-toggle">
                                                    <button type="button" class="lang-flag-btn active" data-lang="ar"
                                                        onclick="handleLangInputChange2(this)">
                                                        <img src="{{ asset('admin/images/flags/sa.webp') }}"
                                                            alt="AR">
                                                        AR
                                                    </button>
                                                    <button type="button" class="lang-flag-btn" data-lang="en"
                                                        onclick="handleLangInputChange2(this)">
                                                        <img src="{{ asset('admin/images/flags/us.webp') }}"
                                                            alt="EN">
                                                        EN
                                                    </button>
                                                </div>
                                                <textarea placeholder="رسالة الضريبة باللغه العربية" class="form-control" name="tax_message_ar">{{ old('tax_message_ar', $branch->settings?->tax_message_ar ?? '') }}</textarea>
                                                <textarea placeholder="رسالة الضريبة باللغه الانجليزية" class="form-control d-none" name="tax_message_en">{{ old('tax_message_en', $branch->settings?->tax_message_en ?? '') }}</textarea>
                                            </div>
                                            @error('tax_message_ar')
                                                <p class="alert alert-danger lang-error lang-error-ar">{{ $message }}</p>
                                            @enderror
                                            @error('tax_message_en')
                                                <p class="alert alert-danger lang-error lang-error-en d-none">
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Tax --}}
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3 text-center">
                                            <label class="col-form-label" for="tax-input">الضريبة</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text"><i data-feather="percent"></i></span>
                                                <input type="number" id="tax-input" class="form-control" name="tax"
                                                    value="{{ old('tax', $branch->settings?->tax ?? '') }}"
                                                    placeholder="الضريبة" />
                                            </div>
                                            @error('tax')
                                                <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Service --}}
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3 text-center">
                                            <label class="col-form-label" for="service-input">الخدمة</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text"><i data-feather="percent"></i></span>
                                                <input type="number" id="service-input" class="form-control"
                                                    name="service"
                                                    value="{{ old('service', $branch->settings?->service ?? '') }}"
                                                    placeholder="الخدمة" />
                                            </div>
                                            @error('service')
                                                <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Tax Number --}}

                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3 text-center">
                                            <label class="col-form-label" for="tax-number-input">الرقم الضريبي</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text"><i data-feather="hash"></i></span>
                                                <input type="text" id="tax-number-input" class="form-control"
                                                    name="tax_number"
                                                    value="{{ old('tax_number', $branch->settings?->tax_number ?? '') }}"
                                                    placeholder="الرقم الضريبي" />
                                            </div>
                                            @error('tax_number')
                                                <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Wifi --}}
                <div id="section-wifi" class="settings-divider d-flex align-items-center gap-3 my-4">
                    <div
                        class="divider-icon d-flex align-items-center justify-content-center rounded-2 text-white flex-shrink-0">
                        <i class="fa-solid fa-wifi"></i>
                    </div>
                    <span class="fw-bold">إعدادات الواي فاي</span>
                </div>
                <div class="w-xl-75">
                    <div class="card settings-card">
                        <div class="card-header">
                            <h3 class="text-white fs-6 mb-0">اعدادات الواي فاي</h3>
                        </div>
                        <div class="card-body pt-4">
                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="wifi_username">اسم الواي فاي</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="fa-solid fa-wifi"></i></span>
                                            <input type="text" id="wifi_username" class="form-control"
                                                name="wifi_username"
                                                value="{{ old('wifi_username', $branch->settings?->wifi_username ?? '') }}"
                                                placeholder="اسم الواي فاي" />
                                        </div>
                                        @error('wifi_username')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>


                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="wiif_password">باسورد الواي فاي</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                                            <input type="text" id="wiif_password" class="form-control"
                                                name="wifi_password"
                                                value="{{ old('wifi_password', $branch->settings?->wifi_password ?? '') }}"
                                                placeholder="باسورد الواي فاي" />
                                        </div>
                                        @error('wifi_password')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Social Media --}}
                <div id="section-social" class="settings-divider d-flex align-items-center gap-3 my-4">
                    <div
                        class="divider-icon d-flex align-items-center justify-content-center rounded-2 text-white flex-shrink-0">
                        <i class="fa-solid fa-share-nodes"></i>
                    </div>
                    <span class="fw-bold">مواقع التواصل الاجتماعي</span>
                </div>
                <div class="w-xl-75">
                    <div class="card settings-card">
                        <div class="card-header">
                            <h3 class="text-white fs-6 mb-0">اعدادات مواقع التواصل الاجتماعي</h3>
                        </div>
                        <div class="card-body pt-4">
                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="facebook">فيس بوك</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="fa-brands fa-facebook-f"></i></span>
                                            <input type="text" id="facebook" class="form-control" name="facebook"
                                                value="{{ old('facebook', $branch->settings?->facebook ?? '') }}"
                                                placeholder="فيس بوك" />
                                        </div>
                                        @error('facebook')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="youtube">يوتيوب</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="fa-brands fa-youtube"></i></span>
                                            <input type="text" id="youtube" class="form-control" name="youtube"
                                                value="{{ old('youtube', $branch->settings?->youtube ?? '') }}"
                                                placeholder="يوتيوب" />
                                        </div>
                                        @error('youtube')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="instagram">انستاجرام</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="fa-brands fa-instagram"></i></span>
                                            <input type="text" id="instagram" class="form-control" name="instagram"
                                                value="{{ old('instagram', $branch->settings?->instagram ?? '') }}"
                                                placeholder="انستاجرام" />
                                        </div>
                                        @error('instagram')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="x">اكس</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="fa-brands fa-x-twitter"></i></span>
                                            <input type="text" id="x" class="form-control" name="x"
                                                value="{{ old('x', $branch->settings?->x ?? '') }}" placeholder="اكس" />
                                        </div>
                                        @error('x')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="snapchat">سناب شات</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="fa-brands fa-snapchat"></i></span>
                                            <input type="text" id="snapchat" class="form-control" name="snapchat"
                                                value="{{ old('snapchat', $branch->settings?->snapchat ?? '') }}"
                                                placeholder="سناب شات" />
                                        </div>
                                        @error('snapchat')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="tiktok">تيك توك</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="fa-brands fa-tiktok"></i></span>
                                            <input type="text" id="tiktok" class="form-control" name="tiktok"
                                                value="{{ old('tiktok', $branch->settings?->tiktok ?? '') }}"
                                                placeholder="تيك توك" />
                                        </div>
                                        @error('tiktok')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="whatsapp">واتس اب</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="fa-brands fa-whatsapp"></i></span>
                                            <input type="text" id="whatsapp" class="form-control" name="whatsapp"
                                                value="{{ old('whatsapp', $branch->settings?->whatsapp ?? '') }}"
                                                placeholder="واتس اب" />
                                        </div>
                                        @error('whatsapp')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="telegram">تيليجرام</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="fa-brands fa-telegram"></i></span>
                                            <input type="text" id="telegram" class="form-control" name="telegram"
                                                value="{{ old('telegram', $branch->settings?->telegram ?? '') }}"
                                                placeholder="تيليجرام" />
                                        </div>
                                        @error('telegram')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="email">الايميل</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                                            <input type="text" id="email" class="form-control " name="email"
                                                value="{{ old('email', $branch->settings?->email ?? '') }}"
                                                placeholder="الايميل" />
                                        </div>
                                        @error('email')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="google_rate_url">رابط تقييم جوجل</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="fa-brands fa-google"></i></span>
                                            <input type="text" id="google_rate_url" class="form-control"
                                                name="google_rate_url"
                                                value="{{ old('google_rate_url', $branch->settings?->google_rate_url ?? '') }}"
                                                placeholder="رابط تقييم جوجل" />
                                        </div>
                                        @error('google_rate_url')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                {{-- Colors --}}
                <div id="section-visual" class="settings-divider d-flex align-items-center gap-3 my-4">
                    <div
                        class="divider-icon d-flex align-items-center justify-content-center rounded-2 text-white flex-shrink-0">
                        <i class="fa-regular fa-eye"></i>
                    </div>
                    <span class="fw-bold">الهوية البصرية</span>
                </div>
                <div class="w-xl-75">
                    <div class="card settings-card">
                        <div class="card-header">
                            <h3 class="text-white fs-6 mb-0">اعدادات الهوية البصرية</h3>
                        </div>
                        <div class="card-body row g-3 pt-4">
                            <div class="col-12">
                                <div class="mb-1 row align-items-center">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label text-nowrap" for="font">الخط</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <select class="select2 form-select" name="font" id="font">
                                            <option value=""
                                                {{ old('font', $branch->settings?->font ?? '') === '' ? 'selected' : '' }}>
                                                — اختر الخط —
                                            </option>
                                            <option value="zain"
                                                {{ old('font', $branch->settings?->font ?? '') == 'zain' ? 'selected' : '' }}>
                                                Zain
                                            </option>
                                            <option value="baloobhaijaan2"
                                                {{ old('font', $branch->settings?->font ?? '') == 'baloobhaijaan2' ? 'selected' : '' }}>
                                                Baloo Bhaijaan 2
                                            </option>
                                            <option value="rubik"
                                                {{ old('font', $branch->settings?->font ?? '') == 'rubik' ? 'selected' : '' }}>
                                                Rubik
                                            </option>
                                            <option value="notosansarabic"
                                                {{ old('font', $branch->settings?->font ?? '') == 'notosansarabic' ? 'selected' : '' }}>
                                                Noto Sans Arabic
                                            </option>
                                            <option value="notokufiarabic"
                                                {{ old('font', $branch->settings?->font ?? '') == 'notokufiarabic' ? 'selected' : '' }}>
                                                Noto Kufi Arabic
                                            </option>
                                            <option value="elmessiri"
                                                {{ old('font', $branch->settings?->font ?? '') == 'elmessiri' ? 'selected' : '' }}>
                                                El Messiri
                                            </option>
                                        </select>
                                        @error('font')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-6">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label text-nowrap" for="app_primary_color">اللون
                                            الاساسي</label>
                                    </div>
                                    <div class="col-sm-3 col-lg-6">
                                        <div class="input-group input-group-merge">
                                            <input type="color" id="app_primary_color" class="form-control"
                                                name="app_primary_color"
                                                value="{{ old('app_primary_color', $branch->settings?->app_primary_color ?? '') }}" />
                                        </div>
                                        @error('app_primary_color')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            @if ($showsSecondaryColor)
                                <div class="col-12 col-lg-6">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3 text-center">
                                            <label class="col-form-label text-nowrap" for="app_secondary_color">اللون
                                                الثانوي</label>
                                        </div>
                                        <div class="col-sm-3 col-lg-6">
                                            <div class="input-group input-group-merge">
                                                <input type="color" id="app_secondary_color" class="form-control"
                                                    name="app_secondary_color"
                                                    value="{{ old('app_secondary_color', $branch->settings?->app_secondary_color ?? '') }}" />
                                            </div>
                                            @error('app_secondary_color')
                                                <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($showsAllColors)
                                <div class="col-12 col-lg-6">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3 text-center">
                                            <label class="col-form-label text-nowrap" for="app_indicator_color">لون
                                                المؤشر</label>
                                        </div>
                                        <div class="col-sm-3 col-lg-6">
                                            <div class="input-group input-group-merge">
                                                <input type="color" id="app_indicator_color" class="form-control"
                                                    name="app_indicator_color"
                                                    value="{{ old('app_indicator_color', $branch->settings?->app_indicator_color ?? '') }}" />
                                            </div>
                                            @error('app_indicator_color')
                                                <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-6">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3 text-center">
                                            <label class="col-form-label text-nowrap" for="app_text_color">لون
                                                النص</label>
                                        </div>
                                        <div class="col-sm-3 col-lg-6">
                                            <div class="input-group input-group-merge">
                                                <input type="color" id="app_text_color" class="form-control"
                                                    name="app_text_color"
                                                    value="{{ old('app_text_color', $branch->settings?->app_text_color ?? '') }}" />
                                            </div>
                                            @error('app_text_color')
                                                <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- Language --}}
                <div id="section-language" class="settings-divider d-flex align-items-center gap-3 my-4">
                    <div
                        class="divider-icon d-flex align-items-center justify-content-center rounded-2 text-white flex-shrink-0">
                        <i class="fa-solid fa-language"></i>
                    </div>
                    <span class="fw-bold">اللغة والعملة</span>
                </div>
                <div class="w-xl-75">
                    <div class="card settings-card">
                        <div class="card-header">
                            <h3 class="text-white fs-6 mb-0">اعدادات اللغة والعملة</h3>
                        </div>
                        <div class="card-body pt-4">
                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label text-nowrap" for="lang">
                                            لغه التطبيق الاساسية</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <select class="select2 form-select" name="lang" id="lang"
                                            data-select2-flags="1">
                                            <option value="ar" data-flag="sa"
                                                {{ old('lang', $branch->settings?->lang ?? 'ar') == 'ar' ? 'selected' : '' }}>
                                                العربية
                                            </option>
                                            <option value="en" data-flag="us"
                                                {{ old('lang', $branch->settings?->lang ?? 'ar') == 'en' ? 'selected' : '' }}>
                                                English
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 lang-input-group mt-2">
                                <div class="mb-1 row align-items-center">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label">العملة</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="lang-textarea-wrap">
                                            <div class="lang-flag-toggle">
                                                <button type="button" class="lang-flag-btn active" data-lang="ar"
                                                    onclick="handleLangInputChange2(this)">
                                                    <img src="{{ asset('admin/images/flags/sa.webp') }}" alt="AR">
                                                    AR
                                                </button>
                                                <button type="button" class="lang-flag-btn" data-lang="en"
                                                    onclick="handleLangInputChange2(this)">
                                                    <img src="{{ asset('admin/images/flags/us.webp') }}" alt="EN">
                                                    EN
                                                </button>
                                            </div>
                                            <input type="text" placeholder="العملة باللغة العربية"
                                                class="form-control" name="currency_ar" required
                                                value="{{ old('currency_ar', $branch->settings?->currency_ar ?? '') }}">
                                            <input type="text" placeholder="العملة باللغة الانجليزية"
                                                class="form-control d-none" name="currency_en" required
                                                value="{{ old('currency_en', $branch->settings?->currency_en ?? '') }}">
                                        </div>
                                        @error('currency_ar')
                                            <p class="alert alert-danger lang-error lang-error-ar">{{ $message }}</p>
                                        @enderror
                                        @error('currency_en')
                                            <p class="alert alert-danger lang-error lang-error-en d-none">{{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                @if ($showsOtherSettings)
                    {{-- Other Settings --}}
                    <div id="section-other" class="settings-divider d-flex align-items-center gap-3 my-4">
                        <div
                            class="divider-icon d-flex align-items-center justify-content-center rounded-2 text-white flex-shrink-0">
                            <i class="fa-solid fa-square-check"></i>
                        </div>
                        <span class="fw-bold">إعدادات أخرى</span>
                    </div>
                    <div class="w-xl-75">
                        <div class="card settings-card">
                            <div class="card-header">
                                <h3 class="text-white fs-6 mb-0">إعدادات أخرى </h3>
                            </div>
                            <div class="row card-body pt-4">


                                @if (!$isTheme6)
                                    {{-- Is Order Enabled --}}
                                    <div class="col-12 col-lg-6">
                                        <div class="mb-1 row align-items-center">
                                            <div class="col-8 col-lg-7 text-center">
                                                <label class="col-form-label text-nowrap" for="is_order_enabled">تفعيل
                                                    الطلبات</label>
                                            </div>
                                            <div class="col-4 col-lg-5 d-flex align-items-center">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="is_order_enabled"
                                                        name="is_order_enabled" value="1"
                                                        @if (old('is_order_enabled', $branch->settings?->is_order_enabled)) checked @endif <label
                                                        class="form-check-label" for="is_order_enabled"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Is Checkout Enabled --}}
                                    <div class="col-12 col-lg-6">
                                        <div class="mb-1 row align-items-center">
                                            <div class="col-8 col-lg-7 text-center">
                                                <label class="col-form-label text-nowrap" for="is_checkout_enabled">تفعيل
                                                    السله</label>
                                            </div>
                                            <div class="col-4 col-lg-5 d-flex align-items-center">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="is_checkout_enabled"
                                                        name="is_checkout_enabled" value="1"
                                                        @if (old('is_checkout_enabled', $branch->settings?->is_checkout_enabled)) checked @endif <label
                                                        class="form-check-label" for="is_checkout_enabled"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif


                                {{-- Is Call Waiter Enabled --}}
                                <div class="col-12 col-lg-6">
                                    <div class="mb-1 row align-items-center">
                                        <div class="col-8 col-lg-7 text-center">
                                            <label class="col-form-label text-nowrap" for="is_call_waiter_enabled">تفعيل
                                                طلب النادل</label>
                                        </div>
                                        <div class="col-4 col-lg-5 d-flex align-items-center">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox"
                                                    id="is_call_waiter_enabled" name="is_call_waiter_enabled"
                                                    value="1" @if (old('is_call_waiter_enabled', $branch->settings?->is_call_waiter_enabled)) checked @endif <label
                                                    class="form-check-label" for="is_call_waiter_enabled"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if (!$isTheme6)
                                    {{-- Send Orders To Whatsapp --}}
                                    <div class="col-12 col-lg-6">
                                        <div class="mb-1 row align-items-center">
                                            <div class="col-8 text-center">
                                                <label class="col-form-label text-nowrap" for="send_orders_to_whatsapp">ارسال
                                                    الطلبات
                                                    الي واتس اب
                                                </label>
                                            </div>
                                            <div class="col-4 d-flex align-items-center">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="send_orders_to_whatsapp" name="send_orders_to_whatsapp"
                                                        value="1" @if (old('send_orders_to_whatsapp', $branch->settings?->send_orders_to_whatsapp)) checked @endif <label
                                                        class="form-check-label" for="send_orders_to_whatsapp"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-12 col-lg-6">
                                        <div class="mb-1 row align-items-center">
                                            <div class="col-8 text-center">
                                                <label class="col-form-label text-nowrap" for="dark_buttons">أزرار داكنة
                                                </label>
                                            </div>
                                            <div class="col-4 d-flex align-items-center">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="dark_buttons"
                                                        name="dark_buttons" value="1"
                                                        @if (old('dark_buttons', $branch->settings?->dark_buttons)) checked @endif <label
                                                        class="form-check-label" for="dark_buttons"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif


                            </div>
                        </div>
                    </div>
                @endif


                <div class="mt-5">
                    <button class="btn btn-primary w-xl-75" type="submit">حفظ الاعدادات</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    @if (session('success'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'احسنت',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'حسناً'
                });
            });
        </script>
    @endif
    <script src="{{ asset('') }}admin/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="{{ asset('') }}admin/js/select2-flags.js"></script>
    <script>
        function handleLangInputChange2(btn) {
            var lang = $(btn).data('lang');
            var $wrap = $(btn).closest('.lang-textarea-wrap');
            $wrap.find('.lang-flag-btn').removeClass('active');
            $(btn).addClass('active');
            var $group = $(btn).closest('.lang-input-group');
            $group.find('textarea, input[type="text"]').addClass('d-none');
            $group.find('textarea[name$="_' + lang + '"], input[type="text"][name$="_' + lang + '"]')
                .removeClass('d-none');
            $group.find('.lang-error').addClass('d-none');
            $group.find('.lang-error-' + lang).removeClass('d-none');
        }

        $(function() {
            function bindImage(input, preview, placeholder, removeBtn, overlay) {
                // When user picks a new file, switch to "new-upload" mode
                $(input).on('change', function() {
                    var file = this.files[0];
                    if (!file) return;
                    var url = URL.createObjectURL(file);
                    $(preview).attr('src', url).removeClass('d-none');
                    $(placeholder).addClass('d-none');
                    $(removeBtn).removeClass('d-none').removeAttr('data-db-image');
                    if (overlay) {
                        $(overlay).removeClass('d-none');
                    }
                });
                $(removeBtn).on('click', function(e) {
                    e.stopPropagation();
                    var $btn = $(this);
                    var isDbImage = $btn.data('db-image');
                    if (isDbImage) {
                        if (!confirm('هل أنت متأكد من حذف هذه الصورة؟')) return;
                        var field = $btn.data('field');
                        var branchId = $btn.data('branch-id');
                        $.ajax({
                            url: '{{ route('branches.delete-setting-image') }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                field: field,
                                branch_id: branchId
                            },
                            success: function(res) {
                                if (res.status) {
                                    $(input).val('');
                                    $(preview).attr('src', '').addClass('d-none');
                                    $(placeholder).removeClass('d-none');
                                    $btn.removeAttr('data-db-image').addClass('d-none');
                                    if (overlay) {
                                        $(overlay).addClass('d-none');
                                    }
                                }
                            },
                            error: function() {
                                alert('حدث خطأ أثناء الحذف');
                            }
                        });
                    } else {
                        $(input).val('');
                        $(preview).attr('src', '').addClass('d-none');
                        $(placeholder).removeClass('d-none');
                        $btn.addClass('d-none');
                        if (overlay) {
                            $(overlay).addClass('d-none');
                        }
                    }
                });
            }

            if ($('#header_image').length) {
                bindImage('#header_image', '#cover_preview', '#cover_placeholder', '#cover_remove');
            }
            bindImage('#logo', '#logo_preview', '#logo_placeholder', '#logo_remove');
            bindImage('#app_background_image', '#app_bg_preview', '#app_bg_placeholder', '#app_bg_remove',
                '#app_bg_skeleton_overlay');

            // Mobile offcanvas nav — close then scroll to section
            $('#settings-mobile-nav .nav-link').on('click', function(e) {
                e.preventDefault();
                var $target = $($(this).attr('href'));
                var offcanvasEl = document.getElementById('settingsOffcanvas');
                var offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl);
                if (offcanvas) {
                    $(offcanvasEl).one('hidden.bs.offcanvas', function() {
                        if ($target.length) {
                            $target[0].scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    });
                    offcanvas.hide();
                } else if ($target.length) {
                    $target[0].scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });


            var select = $('.select2');

            select.each(function() {
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
        });
    </script>
@endsection
