$(document).ready(function () {
    $('.slider-body').slick({
        arrows: false,
        dots: true,
        pauseOnHover: true,
        autoplay: true,
        autoplaySpeed: 6000,
        cssEase: 'ease',
        adaptiveHeight: false,
    });

    $('.slider-item').hover(
        function () {
            $('.slider-text').addClass('non-hover');
            $('.slider-item').addClass('hover');
        },
        function () {
            $('.slider-text').removeClass('non-hover');
            $('.slider-item').removeClass('hover');
        },
    );
});
