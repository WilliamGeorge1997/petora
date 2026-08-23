<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="لو عندك مطعم أو كافيه، أنشئ منيو إلكتروني عبر QR Code الآن ودّع المنيو الورقي وابدأ منيو إلكتروني تفاعلي يعرض أصنافك بشكل احترافي، ويتيح لعملائك تصفح القائمة وإرسال الطلبات بسهولة. تحكم كامل من خلال لوحة إدارة مرنة لتحديث الأسعار، الصور، والأصناف فورًا وفي أي وقت ومن أي مكان." />
    <meta name="keywords"
        content="QMenu, منيو الكتروني, Restaurant Menu, Cafe Menu, Bar Menu, Digital Menu, Food Ordering, Online Menu, Touchless Ordering, Contactless Menu, Fast and Easy, Dine-in QR, Menu QR code, Digital Restaurant, Food Delivery, Table Service, طلب طعام, طلب اوردر, مطعم, كافيه, مشروبات" />
    <meta name="author" content="QMenu Application">
    <meta name="robots" content="index, follow">
    <meta name="rating" content="general">
    <meta name="copyright" content="QMenu Application">
    <meta name="distribution" content="global">
    <meta property="og:title" content="QMenu | منيو الكتروني ">
    <meta property="og:description"
        content="لو عندك مطعم أو كافيه، أنشئ منيو إلكتروني عبر QR Code الآن ودّع المنيو الورقي وابدأ منيو إلكتروني تفاعلي يعرض أصنافك بشكل احترافي، ويتيح لعملائك تصفح القائمة وإرسال الطلبات بسهولة. تحكم كامل من خلال لوحة إدارة مرنة لتحديث الأسعار، الصور، والأصناف فورًا وفي أي وقت ومن أي مكان.">
    <meta property="og:image" content="{{ asset('') }}home/assets/images/og-image.png">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="ar_AR">
    <link rel="canonical" href="{{ url()->current() }}">
    {{-- <meta name="google-site-verification" content="d4Z6lfAtymplSn41b3BLNygoAJRgTW5Xu0j41K5dAcA" /> --}}
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GX25L70NHD"></script>
    {{-- <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-GX25L70NHD');
    </script> --}}
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XBMG11GQJL"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-XBMG11GQJL');
    </script>
    <link rel="icon" type="image/x-icon" href="{{ asset('') }}home/assets/images/favicon.ico" />
    <title>QMenu | منيو الكتروني </title>
    <link rel="stylesheet" href="{{ asset('') }}home/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('') }}home/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('') }}home/css/splide.min.css">
    <link rel="stylesheet" href="{{ asset('') }}home/css/aos.css">
    <link rel="stylesheet" href="{{ asset('') }}home/css/style.min.css">
</head>

<body data-bs-spy="scroll" data-bs-target="#navbar" data-bs-offset="70">
    <!-- Loader -->
    <div id="loader-container"
        class="d-flex justify-content-center align-items-center position-fixed top-0 bottom-0 start-0 end-0 z-3 bg-white">
        <div class="w-25 text-center">
            <svg class="w-50" fill="#F68914D3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <circle cx="4" cy="12" r="3">
                    <animate id="spinner_qFRN" begin="0;spinner_OcgL.end+0.25s" attributeName="cy" calcMode="spline"
                        dur="0.6s" values="12;6;12" keySplines=".33,.66,.66,1;.33,0,.66,.33" />
                </circle>
                <circle cx="12" cy="12" r="3">
                    <animate begin="spinner_qFRN.begin+0.1s" attributeName="cy" calcMode="spline" dur="0.6s"
                        values="12;6;12" keySplines=".33,.66,.66,1;.33,0,.66,.33" />
                </circle>
                <circle cx="20" cy="12" r="3">
                    <animate id="spinner_OcgL" begin="spinner_qFRN.begin+0.2s" attributeName="cy" calcMode="spline"
                        dur="0.6s" values="12;6;12" keySplines=".33,.66,.66,1;.33,0,.66,.33" />
                </circle>
            </svg>
        </div>
        <!-- <p class="text-dark m-0">Loading...</p> -->
    </div>
    <!-- Loader -->

    <!-- Navbar -->
    <nav id="navbar" class="navbar navbar-expand-lg bg-dark fixed-top z-2">
        <div class="container">
            <div class="d-flex justify-content-between">
                <div>
                    <a class="navbar-brand text-white fw-bold ms-3" href="#home">
                        <img class="w-25" loading='lazy' src="{{ asset('') }}home/assets/images/navbar.webp"
                            alt="QMenu Logo">
                        <span data-lang="nav.title">QMenu</span>
                    </a>
                </div>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#home" data-lang="nav.home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#themes" data-lang="nav.themes">Themes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features" data-lang="nav.features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#how-it-works" data-lang="nav.howItWorks">How It Works</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" target="_blank" href="https://qr.iconapp.site/blogs/"
                            data-lang="nav.blogs">Blogs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact" data-lang="nav.contact">Contact Us</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <span data-lang="nav.language">Language</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><button id="btn-en" class="dropdown-item">English</button></li>
                            <li><button id="btn-ar" class="dropdown-item">العربية</button></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Navbar -->


    <!-- Hero -->
    <section id="home" class="vh-100 position-relative overflow-hidden mb-0">
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-75" aria-hidden="true"></div>
        <div class="position-relative z-1 container h-100 d-flex align-items-center text-white">
            <div class="col-12 col-lg-6 text-center" data-aos="fade-right" data-aos-delay="150"
                data-aos-duration="1000" data-aos-easing="ease-in-out" data-aos-once="true">
                <h2 class="display-4 fw-bold mb-3" data-lang="hero.title">Discover Delicious Moments with <span
                        class="primary-color">QR
                        Menu</span>!</h2>
                <p data-lang="hero.description">Satisfy every craving your way. discover irresistible dishes, customize
                    your favorites, and choose how
                    you
                    enjoy them:
                    served hot at the table, delivered to your door, or brought straight to your car. Fresh flavors,
                    quick
                    service, zero
                    hassle. Your perfect meal is just a tap away.</p>
                <a href="#themes" class="btn btn-custom  btn-md mt-4 px-5 py-3 fw-bold">
                    <!-- <i class="fa-solid fa-eye me-2"></i> -->
                    <span data-lang="hero.viewThemes">Explore Live Demo</span>
                </a>
                <a href="#contact" class="btn btn-custom-outline btn-md mt-4 px-5 py-3 fw-bold ms-sm-3">
                    <span data-lang="hero.contact">Contact Us</span>
                </a>
            </div>
            <div id="scan-animation" class="d-none d-lg-block col-12 col-lg-6 text-center">
            </div>
        </div>
    </section>
    <!-- Hero -->



    <!-- Header -->
    <div id="header" class="py-5 bg-light mt-0 ">
        <div class="container my-5 ">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3" data-lang="header.title">
                    Turn your menu into a
                    <span class="primary-color"> unique digital experience</span>
                </h2>
                <p class="lead text-muted col-lg-8 mx-auto" data-lang="header.description">Create a professional
                    digital menu with a QR code in minutes. 5 amazing themes, easy management, and an exceptional
                    customer experience.</p>
                <a href="#themes" class="btn btn-custom  btn-md mt-4 px-5 py-3 fw-bold">
                    <span data-lang="header.view">View Themes</span>
                </a>
                <a href="#features" class="btn btn-custom-outline btn-md mt-4 px-5 py-3 fw-bold ms-sm-3">
                    <span data-lang="header.discover">Discover Features</span>
                </a>
                <div class="row mx-auto mt-5 header-stats-row">
                    <div class="col-4">
                        <div class="d-flex flex-column">
                            <span class="primary-color">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-smartphone">
                                    <rect width="14" height="20" x="5" y="2" rx="2" ry="2">
                                    </rect>
                                    <path d="M12 18h.01"></path>
                                </svg>
                            </span>
                            <span class="fw-bold mt-2">5</span>
                            <small data-lang="header.designs" class="text-muted">Ready Designs</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="d-flex flex-column">
                            <span class="primary-color">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-qr-code">
                                    <rect width="5" height="5" x="3" y="3" rx="1"></rect>
                                    <rect width="5" height="5" x="16" y="3" rx="1"></rect>
                                    <rect width="5" height="5" x="3" y="16" rx="1"></rect>
                                    <path d="M21 16h-3a2 2 0 0 0-2 2v3"></path>
                                    <path d="M21 21v.01"></path>
                                    <path d="M12 7v3a2 2 0 0 1-2 2H7"></path>
                                    <path d="M3 12h.01"></path>
                                    <path d="M12 3h.01"></path>
                                    <path d="M12 16v.01"></path>
                                    <path d="M16 12h1"></path>
                                    <path d="M21 12v.01"></path>
                                    <path d="M12 21v-1"></path>
                                </svg>
                            </span>
                            <span class="fw-bold mt-2">QR</span>
                            <small data-lang="header.code" class="text-muted">Instant Code</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="d-flex flex-column">
                            <span class="primary-color">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-utensils">
                                    <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"></path>
                                    <path d="M7 2v20"></path>
                                    <path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"></path>
                                </svg>
                            </span>
                            <span class="fw-bold mt-2">∞</span>
                            <small data-lang="header.products" class="text-muted">Unlimited Products</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header -->


    @if (count($branches) > 0)
        <!-- Slider -->
        <section id="slider" class="splide " aria-label="Splide Basic HTML Example my-5">
            <div class="text-center">
                <h2 class="display-5 fw-bold mb-5" data-lang="slider.title">
                    Brands That
                    <span class="primary-color"> Trusted Us</span>
                </h2>
            </div>
            <div class="splide__track mt-4">
                <ul role="list" class="splide__list">
                    @foreach ($branches as $id => $branch)
                        @php
                            $titles = $branch->getTranslations('title') ?? [];
                            $fallbackTitle = $titles['ar'] ?? ($titles['en'] ?? '');
                        @endphp
                        <li class="splide__slide">
                            @if ($branch->shortUrl && $branch->shortUrl->code)
                                <a href="{{ url('') . '/' . $branch->shortUrl->code }}" target="_blank"
                                    class="d-flex flex-column align-items-center text-decoration-none h-100 w-100 justify-content-center">
                                    <div
                                        class="d-flex flex-column align-items-center justify-content-center mb-2 slider-logo-box">
                                        @if ($branch->settings?->logo)
                                            <img loading='lazy' width="300" height="300"
                                                src="{{ $branch->settings->logo }}" alt="logo"
                                                class="img-fluid slider-logo-img">
                                        @else
                                            <div
                                                class="bg-light d-flex align-items-center justify-content-center slider-logo-placeholder">
                                                <i class="fa-solid fa-utensils fa-2x text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <span
                                        class="fw-bold text-center d-flex justify-content-center align-items-center branch-title slider-branch-title"
                                        data-title-en="{{ $titles['en'] ?? '' }}"
                                        data-title-ar="{{ $titles['ar'] ?? '' }}">
                                        {{ $fallbackTitle }}
                                    </span>
                                </a>
                            @else
                                <div class="d-flex flex-column align-items-center h-100 w-100 justify-content-center">
                                    <div
                                        class="d-flex flex-column align-items-center justify-content-center mb-2 slider-logo-box">
                                        @if ($branch->settings?->logo)
                                            <img loading='lazy' width="300" height="300"
                                                src="{{ $branch->settings->logo }}" alt="logo"
                                                class="img-fluid slider-logo-img">
                                        @else
                                            <div
                                                class="bg-light d-flex align-items-center justify-content-center slider-logo-placeholder">
                                                <i class="fa-solid fa-utensils fa-2x text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <span
                                        class="fw-bold text-center d-flex justify-content-center align-items-center branch-title slider-branch-title"
                                        data-title-en="{{ $titles['en'] ?? '' }}"
                                        data-title-ar="{{ $titles['ar'] ?? '' }}">
                                        {{ $fallbackTitle }}
                                    </span>
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
        <!-- Slider -->
    @endif

    <!-- Themes -->
    <section id="themes" class="py-5 bg-gradient position-relative">
        <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
            <div class="bg-blur-circle bg-blur-circle-1"></div>
            <div class="bg-blur-circle bg-blur-circle-2"></div>
        </div>

        <div class="container position-relative z-1">
            <div class="text-center mb-5">
                <h2 class="display-4 fw-bold mb-3" data-lang="themes.menuDesignsTitle">
                    Menu <span class="primary-color">Themes</span>
                </h2>
                <p class="lead text-muted col-lg-8 mx-auto" data-lang="themes.menuDesignsSubtitle">
                    Choose the appropriate theme for your restaurant from 5 professional themes.
                </p>
            </div>

            <div class="row g-4 justify-content-center">
                <!-- Theme 1 -->
                <div class="col-12 col-sm-6 col-lg-4" data-aos="zoom-in" data-aos-delay="100"
                    data-aos-duration="800" data-aos-easing="ease-in-out" data-aos-once="true">
                    <div class="card h-100 border-0 rounded-4 shadow-lg theme-card theme-card-clickable"
                        data-theme-img="{{ asset('') }}home/assets/images/1.webp">
                        <div class="card-body p-4 text-center d-flex flex-column">
                            <div class="theme-phone-mockup mb-3">
                                <img loading='lazy' src="{{ asset('') }}home/assets/images/1.webp"
                                    alt="Theme 1 - Classic Elegance" class="theme-phone-img">
                            </div>
                            <h3 class="card-title fw-bold mb-3 fs-5" data-lang="themes.theme1.title">
                                <span data-lang="themes.theme1.title">Theme <span class="theme-badge">1</span></span>
                            </h3>
                            <div class="theme-action-buttons d-flex justify-content-center gap-2 mt-auto">
                                <a target="_blank"
                                    href="https://wa.me/201285644414?text=Hello%2C%20I%20am%20interested%20in%20QR%20menu%20Theme%201.%20Could%20you%20please%20provide%20more%20information%20and%20pricing%20details%3F"
                                    class="btn theme-btn-chat rounded-3 px-3 py-2" title="Contact">
                                    <i class="fab fa-whatsapp text-white fa-xl"></i>
                                </a>
                                <a href="{{ url('') . '/6W.5hSDv' }}"
                                    class="btn theme-btn-link rounded-3 px-3 py-2 d-flex align-items-center gap-1"
                                    title="Link">
                                    <i class="fa-solid fa-link text-dark"></i>
                                    <span data-lang="themes.btnLink">View Theme</span>
                                </a>
                                <span class="btn theme-btn-qr rounded-3 px-3 py-2 d-flex align-items-center gap-1"
                                    data-bs-toggle="tooltip" data-bs-placement="bottom"
                                    title='<img loading="lazy" src="{{ asset('') }}home/assets/images/qr-1.webp" alt="Theme 1 QR Code">'>
                                    <i class="fa-solid fa-qrcode text-white"></i>
                                    <span class="text-white">QR</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Theme 2 -->
                <div class="col-12 col-sm-6 col-lg-4" data-aos="zoom-in" data-aos-delay="200"
                    data-aos-duration="800" data-aos-easing="ease-in-out" data-aos-once="true">
                    <div class="card h-100 border-0 rounded-4 shadow-lg theme-card theme-card-clickable"
                        data-theme-img="{{ asset('') }}home/assets/images/2.webp">
                        <div class="card-body p-4 text-center d-flex flex-column">
                            <div class="theme-phone-mockup mb-3">
                                <img loading='lazy' src="{{ asset('') }}home/assets/images/2.webp"
                                    alt="Theme 2 - Modern Vibes" class="theme-phone-img">
                            </div>
                            <h3 class="card-title fw-bold mb-3 fs-5" data-lang="themes.theme2.title">Theme <span
                                    class="theme-badge">2</span></h3>
                            <div class="theme-action-buttons d-flex justify-content-center gap-2 mt-auto">
                                <a target="_blank"
                                    href="https://wa.me/201285644414?text=Hello%2C%20I%20am%20interested%20in%20QR%20menu%20Theme%202.%20Could%20you%20please%20provide%20more%20information%20and%20pricing%20details%3F"
                                    class="btn theme-btn-chat rounded-3 px-3 py-2" title="Contact">
                                    <i class="fab fa-whatsapp text-white fa-xl"></i>
                                </a>
                                <a href="{{ url('') . '/pA.gQ7xt' }}"
                                    class="btn theme-btn-link rounded-3 px-3 py-2 d-flex align-items-center gap-1"
                                    title="Link">
                                    <i class="fa-solid fa-link text-dark"></i>
                                    <span data-lang="themes.btnLink">View Theme</span>
                                </a>
                                <span class="btn theme-btn-qr rounded-3 px-3 py-2 d-flex align-items-center gap-1"
                                    data-bs-toggle="tooltip" data-bs-placement="bottom"
                                    title='<img loading="lazy" src="{{ asset('') }}home/assets/images/qr-2.webp" alt="Theme 2 QR Code">'>
                                    <i class="fa-solid fa-qrcode text-white"></i>
                                    <span class="text-white">QR</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Theme 3 -->
                <div class="col-12 col-sm-6 col-lg-4" data-aos="zoom-in" data-aos-delay="300"
                    data-aos-duration="800" data-aos-easing="ease-in-out" data-aos-once="true">
                    <div class="card h-100 border-0 rounded-4 shadow-lg theme-card theme-card-clickable"
                        data-theme-img="{{ asset('') }}home/assets/images/3.webp">
                        <div class="card-body p-4 text-center d-flex flex-column">
                            <div class="theme-phone-mockup mb-3">
                                <img loading='lazy' src="{{ asset('') }}home/assets/images/3.webp"
                                    alt="Theme 3" class="theme-phone-img">
                            </div>
                            <h3 class="card-title fw-bold mb-3 fs-5" data-lang="themes.theme3.title">Theme <span
                                    class="theme-badge">3</span></h3>
                            <div class="theme-action-buttons d-flex justify-content-center gap-2 mt-auto">
                                <a target="_blank"
                                    href="https://wa.me/201285644414?text=Hello%2C%20I%20am%20interested%20in%20QR%20menu%20Theme%203.%20Could%20you%20please%20provide%20more%20information%20and%20pricing%20details%3F"
                                    class="btn theme-btn-chat rounded-3 px-3 py-2" title="Contact">
                                    <i class="fab fa-whatsapp text-white fa-xl"></i>
                                </a>
                                <a href="{{ url('') . '/sN.jPw0Y' }}"
                                    class="btn theme-btn-link rounded-3 px-3 py-2 d-flex align-items-center gap-1"
                                    title="Link">
                                    <i class="fa-solid fa-link text-dark"></i>
                                    <span data-lang="themes.btnLink">View Theme</span>
                                </a>
                                <span class="btn theme-btn-qr rounded-3 px-3 py-2 d-flex align-items-center gap-1"
                                    data-bs-toggle="tooltip" data-bs-placement="bottom"
                                    title='<img loading="lazy" src="{{ asset('') }}home/assets/images/qr-3.webp" alt="Theme 3 QR Code">'>
                                    <i class="fa-solid fa-qrcode text-white"></i>
                                    <span class="text-white">QR</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Theme 4 -->
                <div class="col-12 col-sm-6 col-lg-4" data-aos="zoom-in" data-aos-delay="400"
                    data-aos-duration="800" data-aos-easing="ease-in-out" data-aos-once="true">
                    <div class="card h-100 border-0 rounded-4 shadow-lg theme-card theme-card-clickable"
                        data-theme-img="{{ asset('') }}home/assets/images/4.webp">
                        <div class="card-body p-4 text-center d-flex flex-column">
                            <div class="theme-phone-mockup mb-3">
                                <img loading='lazy' src="{{ asset('') }}home/assets/images/4.webp"
                                    alt="Theme 4" class="theme-phone-img">
                            </div>
                            <h3 class="card-title fw-bold mb-3 fs-5" data-lang="themes.theme4.title">Theme <span
                                    class="theme-badge">4</span></h3>
                            <div class="theme-action-buttons d-flex justify-content-center gap-2 mt-auto">
                                <a target="_blank"
                                    href="https://wa.me/201285644414?text=Hello%2C%20I%20am%20interested%20in%20QR%20menu%20Theme%204.%20Could%20you%20please%20provide%20more%20information%20and%20pricing%20details%3F"
                                    class="btn theme-btn-chat rounded-3 px-3 py-2" title="Contact">
                                    <i class="fab fa-whatsapp text-white fa-xl"></i>
                                </a>
                                <a href="{{ url('') . '/Nq.2L010' }}"
                                    class="btn theme-btn-link rounded-3 px-3 py-2 d-flex align-items-center gap-1"
                                    title="Link">
                                    <i class="fa-solid fa-link text-dark"></i>
                                    <span data-lang="themes.btnLink">View Theme</span>
                                </a>
                                <span class="btn theme-btn-qr rounded-3 px-3 py-2 d-flex align-items-center gap-1"
                                    data-bs-toggle="tooltip" data-bs-placement="bottom"
                                    title='<img loading="lazy" src="{{ asset('') }}home/assets/images/qr-4.webp" alt="Theme 4 QR Code">'>
                                    <i class="fa-solid fa-qrcode text-white"></i>
                                    <span class="text-white">QR</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Theme 5 -->
                <div class="col-12 col-sm-6 col-lg-4" data-aos="zoom-in" data-aos-delay="500"
                    data-aos-duration="800" data-aos-easing="ease-in-out" data-aos-once="true">
                    <div class="card h-100 border-0 rounded-4 shadow-lg theme-card theme-card-clickable"
                        data-theme-img="{{ asset('') }}home/assets/images/5.webp">
                        <div class="card-body p-4 text-center d-flex flex-column">
                            <div class="theme-phone-mockup mb-3">
                                <img loading='lazy' src="{{ asset('') }}home/assets/images/5.webp"
                                    alt="Theme 5 image" class="theme-phone-img">
                            </div>
                            <h3 class="card-title fw-bold mb-3 fs-5" data-lang="themes.theme5.title">Theme <span
                                    class="theme-badge">5</span></h3>
                            <div class="theme-action-buttons d-flex justify-content-center gap-2 mt-auto">
                                <a target="_blank"
                                    href="https://wa.me/201285644414?text=Hello%2C%20I%20am%20interested%20in%20QR%20menu%20Theme%205.%20Could%20you%20please%20provide%20more%20information%20and%20pricing%20details%3F"
                                    class="btn theme-btn-chat rounded-3 px-3 py-2" title="Contact">
                                    <i class="fab fa-whatsapp text-white fa-xl"></i>
                                </a>
                                <a href="{{ url('') . '/qk.NhQEU' }}" target="_blank"
                                    class="btn theme-btn-link rounded-3 px-3 py-2 d-flex align-items-center gap-1"
                                    title="Link">
                                    <i class="fa-solid fa-link text-dark"></i>
                                    <span data-lang="themes.btnLink">View Theme</span>
                                </a>
                                <span class="btn theme-btn-qr rounded-3 px-3 py-2 d-flex align-items-center gap-1"
                                    data-bs-toggle="tooltip" data-bs-placement="bottom"
                                    title='<img loading="lazy" src="{{ asset('') }}home/assets/images/qr-5.webp" alt="Theme 5 QR Code">'>
                                    <i class="fa-solid fa-qrcode text-white"></i>
                                    <span class="text-white">QR</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- CTA Section -->
            <div class="text-center mt-5 pt-4" data-aos="fade-up" data-aos-delay="300" data-aos-duration="800">
                <p class="lead mb-4 text-muted">
                    <span data-lang="themes.cta.text">Choose the right theme and start now with your own digital
                        menu!</span>
                    <i class="fa-solid fa-heart text-danger me-2"></i>
                </p>
                <a href="https://wa.me/201285644414" target="_blank"
                    class="btn btn-custom btn-lg px-5 py-3 fw-bold shadow-lg">
                    <i class="fa-solid fa-rocket me-2"></i>
                    <span data-lang="themes.cta.button">Get Started Now</span>
                </a>
            </div>
        </div>
    </section>
    <!-- Themes V2-->

    <!-- Theme Image Modal -->
    <div class="modal fade" id="themeImageModal" tabindex="-1" aria-labelledby="themeImageModalLabel"
        aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-3">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 d-flex justify-content-center align-items-center bg-light">
                    <img id="themeModalImage" loading='lazy' src="" alt="Theme preview"
                        class="img-fluid theme-modal-img">
                </div>
            </div>
        </div>
    </div>
    <!-- Theme Image Modal -->

    <!-- Features -->
    <section id="features" class="py-5 bg-light mt-0">
        <div class="container my-5">
            <!-- Section Header -->
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3" data-lang="features.title">Powerful Features for <span
                        class="primary-color">Your
                        Business</span>
                </h2>
                <p class="lead text-muted col-lg-8 mx-auto" data-lang="features.description">Everything you need to
                    run
                    a modern, efficient restaurant or
                    cafe. Manage your menu, track orders, and delight customers with seamless digital ordering.</p>
            </div>

            <!-- Feature Cards -->
            <div class="row g-4">

                <div class="col-12 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="150"
                    data-aos-duration="1000" data-aos-easing="ease-in-out" data-aos-once="true">
                    <div class="card h-100 border-0 rounded-4 shadow-sm hover-lift">
                        <div class="card-body p-4">
                            <div class="rounded-circle bg-custom bg-opacity-10 d-inline-flex p-3 mb-3">
                                <i class="fa-solid fa-utensils fa-2x text-custom"></i>
                            </div>
                            <h3 class="card-title fw-bold mb-3" data-lang="features.card1.title">Complete Menu Control
                            </h3>
                            <p class="card-text text-muted" data-lang="features.card1.description">Create and
                                customize
                                unlimited products with add-ons,
                                attributes, and variations. Update prices, descriptions, and images in real-time across
                                all
                                locations.</p>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="250"
                    data-aos-duration="1000" data-aos-easing="ease-in-out" data-aos-once="true">
                    <div class="card h-100 border-0 rounded-4 shadow-sm hover-lift">
                        <div class="card-body p-4">
                            <div class="rounded-circle bg-custom bg-opacity-10 d-inline-flex p-3 mb-3">
                                <i class="fa-solid fa-code-branch fa-2x text-custom"></i>
                            </div>
                            <h3 class="card-title fw-bold mb-3" data-lang="features.card2.title">Smart Branch System
                            </h3>
                            <p class="card-text text-muted" data-lang="features.card2.description">Manage multiple
                                locations effortlessly. Each branch gets its
                                own
                                unique QR code automatically, making it simple to track orders and performance per
                                location.
                            </p>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="350"
                    data-aos-duration="1000" data-aos-easing="ease-in-out" data-aos-once="true">
                    <div class="card h-100 border-0 rounded-4 shadow-sm hover-lift">
                        <div class="card-body p-4">
                            <div class="rounded-circle bg-custom bg-opacity-10 d-inline-flex p-3 mb-3">
                                <i class="fa-solid fa-qrcode fa-2x text-custom"></i>
                            </div>
                            <h3 class="card-title fw-bold mb-3" data-lang="features.card3.title">Contactless QR
                                Ordering
                            </h3>
                            <p class="card-text text-muted" data-lang="features.card3.description">Customers scan and
                                order instantly—no app downloads
                                required.
                                Fast, secure, and hygienically safe for the modern dining experience.</p>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="450"
                    data-aos-duration="1000" data-aos-easing="ease-in-out" data-aos-once="true">
                    <div class="card h-100 border-0 rounded-4 shadow-sm hover-lift">
                        <div class="card-body p-4">
                            <div class="rounded-circle bg-custom bg-opacity-10 d-inline-flex p-3 mb-3">
                                <i class="fa-solid fa-location-dot fa-2x text-custom"></i>
                            </div>
                            <h3 class="card-title fw-bold mb-3" data-lang="features.card4.title">Multiple Order
                                Methods
                            </h3>
                            <p class="card-text text-muted" data-lang="features.card4.description">Serve customers
                                their
                                way: dine-in, delivery, or curbside
                                pickup. One platform handles all order types seamlessly, maximizing convenience and
                                sales.
                            </p>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="550"
                    data-aos-duration="1000" data-aos-easing="ease-in-out" data-aos-once="true">
                    <div class="card h-100 border-0 rounded-4 shadow-sm hover-lift">
                        <div class="card-body p-4">
                            <div class="rounded-circle bg-custom bg-opacity-10 d-inline-flex p-3 mb-3">
                                <i class="fa-solid fa-bolt fa-2x text-custom"></i>
                            </div>
                            <h3 class="card-title fw-bold mb-3" data-lang="features.card5.title">Instant
                                Synchronization
                            </h3>
                            <p class="card-text text-muted" data-lang="features.card5.description">Changes sync
                                instantly across all branches and devices.
                                Update
                                your menu once and watch it reflect everywhere in seconds.</p>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="650"
                    data-aos-duration="1000" data-aos-easing="ease-in-out" data-aos-once="true">
                    <div class="card h-100 border-0 rounded-4 shadow-sm hover-lift">
                        <div class="card-body p-4">
                            <div class="rounded-circle bg-custom bg-opacity-10 d-inline-flex p-3 mb-3">
                                <i class="fa-solid fa-sliders fa-2x text-custom"></i>
                            </div>
                            <h3 class="card-title fw-bold mb-3" data-lang="features.card6.title">Full Customization
                            </h3>
                            <p class="card-text text-muted" data-lang="features.card6.description">Let customers
                                personalize their orders with custom add-ons,
                                sizes, and attributes. Boost average order value with upsell opportunities built-in.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Feature Cards -->
        </div>
    </section>
    <!-- Features -->





    <!-- How It Works -->
    <section id="how-it-works" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3" data-lang="howItWorks.title">How It <span
                        class="primary-color">Works</span></h2>
                <p class="lead text-muted col-lg-8 mx-auto" data-lang="howItWorks.description">Get your restaurant up
                    and running in minutes with our
                    simple
                    three-step process</p>
            </div>

            <div class="row gy-5 align-items-center">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="150" data-aos-duration="1000"
                    data-aos-easing="ease-in-out" data-aos-once="true">
                    <div class="text-center position-relative">
                        <div class="step-circle mb-4">
                            <i class="fa-solid fa-qrcode fa-3x"></i>
                        </div>
                        <div class="step-badge" data-lang="howItWorks.step1.name">Step 1</div>
                        <h3 class="fw-bold mb-3" data-lang="howItWorks.step1.title">Scan QR Code</h3>
                        <p class="text-muted" data-lang="howItWorks.step1.description">Customers simply scan the
                            unique
                            QR code placed at the branch with their
                            smartphone camera</p>
                    </div>
                </div>

                <div class="col-md-4" data-aos="fade-up" data-aos-delay="150" data-aos-duration="1000"
                    data-aos-easing="ease-in-out" data-aos-once="true">
                    <div class="text-center position-relative">
                        <div class="step-circle mb-4">
                            <i class="fa-solid fa-mobile-screen-button fa-3x"></i>
                        </div>
                        <div class="step-badge" data-lang="howItWorks.step2.name">Step 2</div>
                        <h3 class="fw-bold mb-3" data-lang="howItWorks.step2.title">Browse & Customize</h3>
                        <p class="text-muted" data-lang="howItWorks.step2.description">Customers can browse menu with
                            prices,
                            and descriptions. Add items
                            and
                            customize orders instantly</p>
                    </div>
                </div>

                <div class="col-md-4" data-aos="fade-up" data-aos-delay="150" data-aos-duration="1000"
                    data-aos-easing="ease-in-out" data-aos-once="true">
                    <div class="text-center position-relative">
                        <div class="step-circle mb-4">
                            <i class="fa-solid fa-check-circle fa-3x"></i>
                        </div>
                        <div class="step-badge" data-lang="howItWorks.step3.name">Step 3</div>
                        <h3 class="fw-bold mb-3" data-lang="howItWorks.step3.title">Order & Enjoy</h3>
                        <p class="text-muted" data-lang="howItWorks.step3.description">Orders sent directly to your
                            kitchen or cafe in real-time. Fast service, happy
                            customers, zero contact</p>
                    </div>
                </div>
            </div>



        </div>
    </section>
    <!-- How It Works -->



    <!-- Contact -->
    <section id="contact" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3" data-lang="contact.title">Get In <span
                        class="primary-color">Touch</span></h2>
                <p class="lead text-muted col-lg-8 mx-auto" data-lang="contact.description">Have questions? We'd love
                    to
                    hear from you. Send us a
                    message
                    and we'll respond as soon as possible.</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="card border-0 rounded-4 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h3 class="fw-bold mb-4" data-lang="contact.information">Contact Information</h3>

                            <div class="d-flex align-items-start mb-3">
                                <i class="fa-solid fa-location-dot text-custom fa-lg mt-1 me-3"></i>
                                <div>
                                    <h4 class="fw-bold mb-1" data-lang="contact.address.title">Address</h4>
                                    <p class="text-muted mb-0" data-lang="contact.address.description">45 El-Geish
                                        Road,
                                        Miami, Alexandria, Egypt</p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-3">
                                <i class="fa-solid fa-phone text-custom fa-lg mt-1 me-3"></i>
                                <div>
                                    <h4 class="fw-bold mb-1" data-lang="contact.phone.title">Phone</h4>
                                    <p class="text-muted mb-0" data-lang="contact.phone.description">+20 111 665 7728,
                                        +20 128 564 4414</p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-3">
                                <i class="fa-brands fa-whatsapp text-custom fa-xl mt-1 me-3 "></i>
                                <div>
                                    <h4 class="fw-bold mb-1" data-lang="contact.whatsapp.title">WhatsApp</h4>
                                    <a data-lang="contact.whatsapp.description" href="https://wa.me/201285644414"
                                        target="_blank" rel="noopener" class="text-muted text-decoration-none">
                                        +20 128 564 4414
                                    </a>

                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-3">
                                <i class="fa-solid fa-envelope text-custom fa-lg mt-1 me-3"></i>
                                <div>
                                    <h4 class="fw-bold mb-1" data-lang="contact.email.title">Email</h4>
                                    <p class="text-muted mb-0" data-lang="contact.email.description">info@icontds.com
                                    </p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start">
                                <i class="fa-solid fa-clock text-custom fa-lg mt-1 me-3"></i>
                                <div>
                                    <h4 class="fw-bold mb-1" data-lang="contact.workingHours.title">Working Hours</h4>
                                    <p class="text-muted mb-0" data-lang="contact.workingHours.description">Sun - Thu:
                                        9:00 AM - 4:00 PM</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card border-0 rounded-4 shadow-sm">
                        <div class="card-body p-4">
                            <div class="map-embed">
                                <iframe title="Icon Tech Digital Solutions Google Map"
                                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d109130.08340641197!2d29.999731000000004!3d31.267378000000004!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14f5db58bc050bf1%3A0x73a0efea0ff487f1!2z2KfZitmD2YjZhiDYqtmDINmE2YTYrdmE2YjZhCDYp9mE2LHZgtmF2YrYqSDigJNJY29uIFRlY2ggRGlnaXRhbCBTb2x1dGlvbnM!5e0!3m2!1sen!2sus!4v1771492718987!5m2!1sen!2sus"
                                    allowfullscreen="" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                            {{-- <form>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold" for="name"
                                            data-lang="contact.inputs.name.title">Name</label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Your name" name="name" required autocomplete="on"
                                            data-lang-placeholder="contact.inputs.name.placeholder">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold" for="email"
                                            data-lang="contact.inputs.email.title">Email</label>
                                        <input type="email" class="form-control" id="email"
                                            placeholder="your@email.com" name="email" required autocomplete="on"
                                            data-lang-placeholder="contact.inputs.email.placeholder">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold" for="subject"
                                            data-lang="contact.inputs.subject.title">Subject</label>
                                        <input type="text" class="form-control" id="subject"
                                            placeholder="How can we help?" name="subject" required
                                            data-lang-placeholder="contact.inputs.subject.placeholder">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold" for="message"
                                            data-lang="contact.inputs.message.title">Message</label>
                                        <textarea class="form-control" rows="5" id="message" placeholder="Your message..." name="message" required
                                            data-lang-placeholder="contact.inputs.message.placeholder"></textarea>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-custom btn-lg w-100">
                                            <i class="fa-solid fa-paper-plane me-2"></i>
                                            <span data-lang="contact.submit.title">Send Message</span>
                                        </button>
                                    </div>
                                </div>
                            </form> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact -->

    <!-- Footer -->
    <footer>
        <div class="bg-dark">
            <div class="container text-white">
                <div class="row gy-5 py-5">
                    <div class="col-12 col-sm-12 col-md-6 col-xl-4">
                        <h3 data-lang="footer.qrMenu.title">QMenu</h3>
                        <p data-lang="footer.qrMenu.description">Experience the future of dining with QMenu.
                            Seamlessly browse, order, and
                            enjoy your favorite meals and juices with just a scan. Fast, convenient, and deliciously
                            simple.
                        </p>
                        <ul class="list-unstyled d-flex flex-wrap gap-3 mb-0">
                            <li><i class="fa-brands fa-facebook fa-lg"></i></li>
                            <li><i class="fa-brands fa-instagram fa-lg"></i></li>
                            <li><i class="fa-brands fa-youtube fa-lg"></i></li>
                            <li><i class="fa-brands fa-x-twitter fa-lg"></i></li>
                        </ul>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-xl-4">
                        <h3 data-lang="footer.quickLinks.title">Quick Links</h3>
                        <ul class="list-unstyled mb-0">
                            <li><a href="#home" class="quick-link my-2" data-lang="nav.home">Home</a></li>
                            <li><a href="#themes" class="quick-link my-2" data-lang="nav.themes">Themes</a></li>
                            <li><a href="#features" class="quick-link my-2" data-lang="nav.features">Features</a>
                            </li>
                            <li><a href="#how-it-works" class="quick-link my-2" data-lang="nav.howItWorks">How It
                                    Works</a></li>
                            <li><a target="_blank" href="https://qr.iconapp.site/blogs/" class="quick-link my-2"
                                    data-lang="nav.blogs">Blogs</a></li>
                            <li><a href="#contact" class="quick-link my-2" data-lang="nav.contact">Contact</a></li>
                        </ul>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-xl-4">
                        <h3 data-lang="footer.contactUs.title">Contact Us</h3>
                        <ul class="list-unstyled">
                            <li><i class="fa-solid fa-location-dot me-2 my-2"></i><span
                                    data-lang="contact.address.description">45 El-Geish Road, Miami,
                                    Alexandria, Egypt</span></li>
                            <li><i class="fa-solid fa-phone me-2 my-2"></i>
                                <span data-lang="contact.phone.description">
                                    +20 111 665 7728, +20 128 564 4414
                                </span>
                            </li>
                            <li><i class="fa-solid fa-envelope me-2 my-2"></i><span
                                    data-lang="contact.email.description">info@icontds.com</span></li>
                            <li><i class="fa-solid fa-clock me-2 my-2"></i><span
                                    data-lang="contact.workingHours.description">Sun - Thu: 9:00 AM - 4:00 PM</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <hr class="border-secondary my-0">
                <div class="container text-center text-white py-3">
                    <p class="mb-0">© <span id="current-year"></span> <span data-lang="footer.copyright.title">QR
                            Menu.
                            All rights reserved.</span></p>
                </div>
                <hr class="border-secondary my-0">
                <div class="container text-center text-white py-3">
                    <p class="mb-0"><span id="current-year"></span> <span
                            data-lang="footer.copyright.company.title">Copyright © QMenu. Made with ❤ by </span><a
                            target="_blank" href="https://icontds.com" class="quick-link"
                            data-lang="footer.copyright.company.link">Icon
                            Tech Digital Solutions</a></p>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer -->

    <script src="{{ asset('') }}home/js/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('') }}home/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('') }}home/js/lottie.min.js"></script>
    <script src="{{ asset('') }}home/js/animations.js"></script>
    <script src="{{ asset('') }}home/js/splide.min.js"></script>
    <script src="{{ asset('') }}home/js/splide-extension-auto-scroll.min.js"></script>
    <script src="{{ asset('') }}home/js/aos.js"></script>
    <script type="module" src="{{ asset('') }}home/js/main.js"></script>
</body>

</html>
