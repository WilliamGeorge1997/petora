import { en } from "../lang/en.min.js";
import { ar } from "../lang/ar.min.js";

const translations = { en, ar };

// ===============================
// Helper functions
// ===============================
const DEFAULT_LANG = "ar";
const lang = localStorage.getItem("language") || DEFAULT_LANG;
let slider;
const updateLanguage = () => {
    if (!localStorage.getItem("language")) {
        localStorage.setItem("language", DEFAULT_LANG);
    }

    document.documentElement.lang = lang;
    document.documentElement.dir = lang === "ar" ? "rtl" : "ltr";
    document.title = translations[lang].title;
    document.querySelector('link[href*="bootstrap"]').href =
        lang === "ar"
            ? "home/css/bootstrap.rtl.min.css"
            : "home/css/bootstrap.min.css";

    $("[data-lang]").each(function () {
        let value = translations[lang];
        $(this)
            .data("lang")
            .split(".")
            .forEach((k) => (value = value[k]));
        $(this).html(value);
    });
    $("[data-lang-placeholder]").each(function () {
        let value = translations[lang];
        $(this)
            .data("lang-placeholder")
            .split(".")
            .forEach((k) => (value = value[k]));
        $(this).attr("placeholder", value);
    });

    $(".branch-title").each(function () {
        const $el = $(this);
        const dataKey = lang === "ar" ? "title-ar" : "title-en";
        const text = $el.data(dataKey);

        if (typeof text === "string" && text.length > 0) {
            $el.text(text);
        }
    });
};

const loadLanguage = (lang) => {
    localStorage.setItem("language", lang);
    location.reload();
};

const navbarScrollEffect = () => {
    let ticking = false;
    const navbar = $("#navbar");
    const navbarToggler = $(".navbar-toggler-icon");
    const heroHeight = $("#home").outerHeight();

    const updateNavbar = () => {
        const scrolled = window.scrollY > heroHeight;

        navbar
            .toggleClass("bg-dark", !scrolled)
            .toggleClass("bg-white shadow", scrolled);
        navbar
            .find(".navbar-brand, .nav-link")
            .toggleClass("text-white", !scrolled)
            .toggleClass("text-dark", scrolled);

        if (scrolled) {
            navbarToggler.css(
                "background-image",
                "url(\"data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%2833, 37, 41, 0.75%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e\")",
            );
        } else {
            navbarToggler.css(
                "background-image",
                "url(\"data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.75%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e\")",
            );
        }
        ticking = false;
    };

    updateNavbar();

    $(window).on("scroll", () => {
        if (!ticking) {
            requestAnimationFrame(updateNavbar);
            ticking = true;
        }
    });
};

// ===============================
// jQuery Ready (DOM only)
// ===============================
$(document).ready(function () {
    //Update language
    updateLanguage();
    //Navbar
    navbarScrollEffect();

    $("#btn-ar").on("click", () => {
        if (localStorage.getItem("language") !== "ar") {
            $("body").fadeOut(200, () => loadLanguage("ar"));
        }
    });

    $("#btn-en").on("click", () => {
        if (localStorage.getItem("language") !== "en") {
            $("body").fadeOut(200, () => loadLanguage("en"));
        }
    });

    //Footer
    $("#current-year").text(new Date().getFullYear());

    //Themese Modal
    $(document).on("click", ".theme-card-clickable", function (e) {
        if ($(e.target).closest(".theme-action-buttons").length) return;
        const imgSrc = $(this).data("theme-img");
        if (imgSrc) {
            $("#themeModalImage").attr("src", imgSrc);
            new bootstrap.Modal($("#themeImageModal")[0]).show();
        }
    });

    //QR Tooltip
    $(".theme-btn-qr").each(function () {
        new bootstrap.Tooltip(this,{
                html: true
        });
    });
});

// ===============================
// Page Load
// ===============================
//Remove loader container after page load.
$(window).on("load", function () {
    //Loader
    $("#loader-container").fadeOut(400, function () {
        $(this).remove();
        $("body").css({ overflow: "auto" }).fadeIn(400);
    });
    //Splide
    if ($("#slider").length > 0) {
        slider = new Splide(".splide", {
            type: "loop",
            perPage: 7,
            perMove: 1,
            gap: "1rem",
            pagination: false,
            arrows: false,
            direction: lang == "ar" ? "rtl" : "ltr",
            breakpoints: {
                1200: { perPage: 5 },
                1024: { perPage: 4 },
                768: { perPage: 3 },
                480: { perPage: 2 },
            },
            autoScroll: {
                speed: 0.5,
                pauseOnHover: false,
            },
        }).mount(window.splide.Extensions);
    }
});
