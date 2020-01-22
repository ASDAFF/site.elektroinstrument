$(function(){
	$(".close").live("click", function() {
		CloseModalWindow("#addItemInCart")
	});
	$(document).keyup(function(event){
		if(event.keyCode == 27) {
			CloseModalWindow("#addItemInCart")
		}
	});
});

function CentriredModalWindow(ModalName){
	$(window).resize(function () {
		modalHeight = ($(window).height() - $(ModalName).height()) / 2;
		$(ModalName).css({
			'top': modalHeight + 'px'
		});
	});
	$(window).resize();
}

function OpenModalWindow(ModalName){
	$(ModalName).css({"display":"block","opacity":0});
	$(ModalName).animate({"opacity":1},300);	
	$("#bgmod").css("display","block");
}

function CloseModalWindow(ModalName){
	$("#bgmod").css("display","none");
	$(ModalName).css({"opacity":1});
	$(ModalName).animate({"opacity":0},300);
	setTimeout(function() { $(ModalName).css({"display":"none"}); }, 500)	
}

function addToCompare(href, btn) {
	$.get(
		href + '&ajax_compare=1&backurl=' + decodeURIComponent(window.location.pathname),
		$.proxy(
			function(data) {
				$.post("/ajax/compare_line.php", function(data) {
					$(".compare_line").replaceWith(data);
				});
				$("#" + btn).removeClass("catalog-item-compare").addClass("catalog-item-compared").unbind('click').removeAttr("href").css("cursor", "default");
			}
		)
	);
	return false;
}

function addToDelay(id, qnt, props, select_props, btn) {
	$.ajax({
		type: "POST",
		url: "/ajax/add2delay.php",
		data: "id=" + id + "&qnt=" + qnt + "&props=" + props + "&select_props=" +select_props,
		success: function(html){
			$.post("/ajax/delay_line.php", function(data) {
				$(".delay_line").replaceWith(data);
			});
			$.post("/ajax/basket_line.php", function(data) {
				$(".cart_line").replaceWith(data);
			});
			$("#" + btn).removeClass("catalog-item-delay").addClass("catalog-item-delayed").unbind('click').removeAttr("href").css("cursor", "default");
		}
	});
	return false;
}