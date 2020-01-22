$(function() {
	/***To_top***/
    $.scrollUp();
	
	/***Callback***/
	$('.callback_anch').click(function(e){
		e.preventDefault();
		$(window).resize(function () {
			modalHeight = ($(window).height() - $(".callback").height()) / 2;
			$(".callback").css({
				'top': modalHeight + 'px'
			});
		});
		$(window).resize();
		$('.callback_body').css({'display':'block'});
		$('.callback').css({'display':'block'});
	});
	$('.callback_close, .callback_body').click(function(e){
		e.preventDefault();
		$('.callback_body').css({'display':'none'});
		$('.callback').css({'display':'none'});
	});
	
	/***Top_panel_contacts***/
	$('.showcontacts').click(function() {
		var clickitem = $(this);
		if(clickitem.parent('li').hasClass('')) {
			clickitem.parent('li').addClass('active');
		} else {
			clickitem.parent('li').removeClass('active');
		}
		if($('.showsection').parent('li').hasClass('active')) {
			$('.showsection').parent('li').removeClass('active');
			$('.showsection').parent('li').find('.catalog-section-list').css({'display':'none'});
		}
		if($('.showsubmenu').parent('li').hasClass('active')) {
			$('.showsubmenu').parent('li').removeClass('active');
			$('.showsubmenu').parent('li').find('ul.submenu').css({'display':'none'});
		}
		if($('.showsearch').parent('li').hasClass('active')) {
			$('.showsearch').parent('li').removeClass('active');
			$('.header_2').css({'display':'none'});
			$('.title-search-result').css({'display':'none'});
		}
		$('.header_4').slideToggle();
	});
	
	/***Top_panel_search***/
	$('.showsearch').click(function() {
		var clickitem = $(this);
		if(clickitem.parent('li').hasClass('')) {
			clickitem.parent('li').addClass('active');
		} else {
			clickitem.parent('li').removeClass('active');
			$(".title-search-result").css({"display":"none"});
		}
		if($('.showsection').parent('li').hasClass('active')) {
			$('.showsection').parent('li').removeClass('active');
			$('.showsection').parent('li').find('.catalog-section-list').css({'display':'none'});
		}
		if($('.showsubmenu').parent('li').hasClass('active')) {
			$('.showsubmenu').parent('li').removeClass('active');
			$('.showsubmenu').parent('li').find('ul.submenu').css({'display':'none'});
		}
		if($('.showcontacts').parent('li').hasClass('active')) {
			$('.showcontacts').parent('li').removeClass('active');
			$('.header_4').css({'display':'none'});
		}
		$('.header_2').slideToggle();
	});
	
	/***Tabs***/
	if($(".ndl_tabs .box div").is(".new_empty") && $(".ndl_tabs .box div").is(".hit_empty") && $(".ndl_tabs .box div").is(".discount_empty"))
		$('.ndl_tabs').remove();
	if($(".ndl_tabs .box div").is(".new_empty")) $('.ndl_tabs .tabs li.new, .ndl_tabs .new.box').remove();
	if($(".ndl_tabs .box div").is(".hit_empty")) $('.ndl_tabs .tabs li.hit, .ndl_tabs .hit.box').remove();
	if($(".ndl_tabs .box div").is(".discount_empty")) $('.ndl_tabs .tabs li.discount, .ndl_tabs .discount.box').remove();
	$(".ndl_tabs .tabs li").first().addClass("current");
	$(".ndl_tabs .box").first().addClass("visible");
	
	$('ul.tabs').on('click', 'li:not(.current)', function() {
		$(this).addClass('current').siblings().removeClass('current')
			.parents('div.section').find('div.box').eq($(this).index()).fadeIn(150).siblings('div.box').hide();
	})
	
	var tabIndex = window.location.hash.replace('#tab','')-1;
	if (tabIndex != -1) $('ul.tabs li').eq(tabIndex).click();
	$('ul.tabs li a[href*=#tab]').click(function() {
		var tabIndex = $(this).attr('href').replace(/(.*)#tab/, '')-1;
		$('ul.tabs li').eq(tabIndex).click();
	});
	
	/***Show_delay***/
	var currPage = window.location.pathname;
	var delayIndex = window.location.search;
	if((currPage == '/personal/cart/') && (document.getElementById('id-shelve-list')) && (delayIndex == '?delay=Y')) {
		$('#id-shelve-list').show();
		$('#id-cart-list').hide();
	} else {
		$('#id-shelve-list').hide();
		$('#id-cart-list').show();
	}
	
	/***Custom_forms***/
	$('.custom-forms').customForms({});
});