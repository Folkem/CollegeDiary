$(document).ready(function() {
	$('.slider-body').slick({
		arrows: false,
		dots: true,
		pauseOnHover: true,
		autoplay: true,
		autoplaySpeed: 10000,
	});
	
	$('.slider-item').hover(
		function(){ $('.slider-text').addClass('non-hover') },
		function(){ $('.slider-text').removeClass('non-hover') });

	$('.slider-item').hover(
		function(){ $('.slider-item').addClass('hover') },
		function(){ $('.slider-item').removeClass('hover') }
);
});