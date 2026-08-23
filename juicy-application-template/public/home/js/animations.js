$(window).on("load", function () {
  //AOS Initialize
  AOS.init();

  lottie.loadAnimation({
    container: document.getElementById("scan-animation"),
    renderer: "svg",
    loop: true,
    autoplay: true,
    path: "home/assets/animations/scan-animation.json",
  });
});
