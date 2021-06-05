$(document).ready(function () {
	$('.slider-body').slick({
		arrows: false,
		dots: true,
		pauseOnHover: true,
		autoplay: true,
		autoplaySpeed: 6000,
		cssEase: 'ease',
	});

	$('.slider-item').hover(
		function () { $('.slider-text').addClass('non-hover') },
		function () { $('.slider-text').removeClass('non-hover') });

	$('.slider-item').hover(
		function () { $('.slider-item').addClass('hover') },
		function () { $('.slider-item').removeClass('hover') }
	);
});
