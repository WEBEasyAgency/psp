$(document).ready(function(){
	$('a.popup').on('click', function(){
		var href = $(this).attr('href');
		$('.popupblock'+href).fadeIn('300');
		return false;
	});

	$('.popupblock .close').on('click', function(){
		$(this).parents('.popupblock').fadeOut("300");
  });

	$(document).on('keyup', function(e) {
    if ( e.key == "Escape" ) {
      $('.popupblock').fadeOut("300");
      $('.videopopup').fadeOut("300");
    }
  });

	$('.more-btn a').on('click', function(){
		if($(this).hasClass('active')){
			$(this).removeClass('active').parents('.menu').find('.more').fadeOut(300);
		} else{
			$(this).addClass('active').parents('.menu').find('.more').fadeIn(300);
		}
		return false;
	});

	$(document).mouseup(function (e) {
		var div = $(".menu .more");
		var btn = $('.menu .more-btn a')
		if (!div.is(e.target) && !btn.is(e.target) && div.has(e.target).length === 0) {
			div.fadeOut(300);
			btn.removeClass('active');
		}
	});

	var swiperMousewheel = { forceToAxis: true, releaseOnEdges: true };
	var swiperFreeMode = { enabled: true, sticky: false };

	var swiper = new Swiper(".construction-slider", {
		slidesPerView: "auto",
		spaceBetween: 12,
		mousewheel: swiperMousewheel,
		freeMode: swiperFreeMode,
		navigation: {
			nextEl: ".construction-type .next",
			prevEl: ".construction-type .prev",
		},
	});

	var swiper2 = new Swiper(".cases-slider", {
		slidesPerView: "auto",
		spaceBetween: 12,
		mousewheel: swiperMousewheel,
		freeMode: swiperFreeMode,
		navigation: {
			nextEl: ".cases-block .next",
			prevEl: ".cases-block .prev",
		},
	});

	var swiper = new Swiper(".feedback-slider", {
		slidesPerView: "auto",
		spaceBetween: 12,
		mousewheel: swiperMousewheel,
		freeMode: swiperFreeMode,
		navigation: {
			nextEl: ".feedback-block .next",
			prevEl: ".feedback-block .prev",
		},
	});

	var swiper = new Swiper(".clients-slider", {
		slidesPerView: "auto",
		spaceBetween: 8,
		mousewheel: swiperMousewheel,
		freeMode: swiperFreeMode,
		navigation: {
			nextEl: ".clients-block .next",
			prevEl: ".clients-block .prev",
		},
	});

	$('.cases-type-list .item').on('click', function(){
		if(!$(this).hasClass('active')){
			var tab = $(this).attr('href');
			$('.cases-type-list .item').removeClass('active');
			$('.cases-block .tab').hide(300);
			$(this).addClass('active').parents('.cases-block').find('.tab'+tab).show(300);
		}
		return false;
	})

	var scrollPos = $(document).scrollTop();

	$(window).scroll(function() {
		var scrollTop = $(document).scrollTop();
		if(scrollTop < scrollPos){
			$(".to-top").fadeIn(300);
		}
		else if(scrollTop > scrollPos){
				$(".to-top").fadeOut(300);
		}
		scrollPos = scrollTop;
	}); 

	$('.to-top').on('click', function() {
		$('html, body').animate({
				scrollTop: 0
		}, 500);
		return false;
	});

	$('.btn-catalog').on('click', function(){
		if($(this).hasClass('active')){
			$(this).removeClass('active').parents('.catalog-btn').find('.catalog-menu').fadeOut(300);
			$('.popup-bg').fadeOut(300);
		}else{
			$(this).addClass('active').parents('.catalog-btn').find('.catalog-menu').fadeIn(300);
			$('.popup-bg').fadeIn(300);
		}
		return false;
	});

	$('.popup-bg').on('click', function(){
		$('.btn-catalog').removeClass('active');
		$('.catalog-menu').fadeOut(300).find('.parent a').removeClass('active');
		$('.catalog-menu .child').fadeOut(300);
		$('.inner-popup').removeClass('active');
		$(this).fadeOut(300);
	});

	if ($(window).width() <= '1000'){
		$('.main-page .top-block .text-block .feed-btn-block').append($('.main-page .top-block .text-block .line'));
		$('.main-page .top-block .text-block .feed-btn-block').append($('.main-page .top-block .img-block'));
	}

	if ($(window).width() <= '900'){
		$('footer .logo-block').append($('footer .footer-bot .descriptor'));
	}

	$('.burger').on('click', function(){
		if($(this).hasClass('active')){
			$(this).removeClass('active');
			$('.popupmenu').slideUp(300);
			$('.catalog-mobile-menu').hide();
			$('.catalog-mobile-menu .child').hide()
		}else{
			$(this).addClass('active');
			$('.popupmenu').slideDown(300);
		}
	});

	$('.popupmenu .catalog-btn').on('click', function(){
		$(this).parents('.popupmenu').find('.catalog-mobile-menu').slideDown(300);
		return false;
	})

	$('.catalog-mobile-menu .back').on('click', function(){
		$(this).parents('.catalog-mobile-menu').slideUp(300);
		return false;
	})

	$('.catalog-mobile-menu .menu li.parent>a').on('click', function(){
		console.log('123')
		$(this).parents('.parent').find('.child').slideDown(300);
		return false;
	});

	$('.catalog-mobile-menu .child .child-back').on('click', function(){
		$(this).parents('.child').slideUp(300);
		return false;
	});

	$(document).mouseup(function (e) {
		var div = $(".popupmenu");
		var btn = $('.burger')
		if (!div.is(e.target) && !btn.is(e.target) && div.has(e.target).length === 0 && btn.has(e.target).length === 0) {
			div.slideUp(300);
			btn.removeClass('active');
			$('.catalog-mobile-menu .child').hide();
			$('.catalog-mobile-menu').hide();
		}
	});

	$('label.file input[type="file"]').on('change', function(e){
		var fileName = e.target.files[0].name;
		$(this).parents('label.file').find('.text').html(fileName);
	});

	$('.link').on('click', function(e) {
    e.preventDefault();
    var href = $(this).attr('href');
    $('html, body').animate({
      scrollTop: $(href).offset().top
    }, 1000);
  });

	$('.inner-popup').on('click', function(){
		var id = $(this).attr('href');
		$('.feed-popup'+id).fadeIn(300);
		return false;
	})

	$(document).mouseup(function (e) {
		var div = $(".feed-popup .inner");
		if (!div.is(e.target) && div.has(e.target).length === 0) {
			$('.feed-popup').fadeOut(300);
		}
	});

	var selector = $('input[type="tel"]');

	var im = new Inputmask("+7 (999) 999 99-99");
	im.mask(selector);

});