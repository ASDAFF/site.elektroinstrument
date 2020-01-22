(function (factory) {
	if(typeof define === 'function' && define.amd) {		
		define(['jquery'], factory);
	} else {		
		factory(jQuery);
	}
}(function ($) {
	var moreObjects = [];
	
	function adjustMoreMenu() {
		$(moreObjects).each(function () {
			$(this).moreMenu({
				'undo' : true
			}).moreMenu(this.options);
		});
	}	

	$(window).resize(function () {		
		adjustMoreMenu();
	});

	$.fn.moreMenu = function (options) {
		var checkMoreObject,
			s = $.extend({
				'threshold': 2,				
				'linkText': '...',				
				'undo': false
			}, options);
		this.options = s;
		checkMoreObject = $.inArray(this, moreObjects);
		if(checkMoreObject >= 0) {
			moreObjects.splice(checkMoreObject, 1);
		} else {
			moreObjects.push(this);
		}
		return this.each(function () {
			var $this = $(this),
				$items = $this.find('> li'),
				$self = $this,
				$firstItem = $items.first(),
				$lastItem = $items.last(),
				numItems = $this.find('li').length,
				firstItemTop = Math.floor($firstItem.offset().top),
				firstItemHeight = Math.floor($firstItem.outerHeight(true)),
				$lastChild,
				keepLooking,
				$moreItem,				
				numToRemove,				
				$menu,
				i;
			
			function needsMenu($itemOfInterest) {
				var result = (Math.ceil($itemOfInterest.offset().top) >= (firstItemTop + firstItemHeight)) ? true : false;				
				return result;
			}

			if(needsMenu($lastItem) && numItems > s.threshold && !s.undo && $this.is(':visible')) {
				var $popup = $('<ul class="submenuMore"></ul>');
				
				for(i = numItems; i > 1; i--) {					
					$lastChild = $this.find('> li:last-child');
					keepLooking = (needsMenu($lastChild));
					if(keepLooking) {
						$lastChild.appendTo($popup);
					} else {
						break;
					}					
				}				
				$this.append('<li class="parentMore"><a href="javascript:void(0)">' + s.linkText + '</a><span class="arrow"></span></li>');				
				
				$moreItem = $this.find('> li.parentMore');				
				if(needsMenu($moreItem)) {
					$this.find('> li:nth-last-child(2)').appendTo($popup);
				}				
				
				$popup.children().each(function (i, li) {
					$popup.prepend(li);
				});
				
				$moreItem.append($popup);				
				eval('var timeOutMore');
				$moreItem.hover(function() {
					var uid = $(this).attr("id"),
						pos = $(this).position(),
						top = pos.top + $(this).height() + 13;
						if($this.width() - pos.left < $popup.width()) {
							var left = "auto",
								right = 10 + "px";						
						} else {
							var left = pos.left + "px",
								right = "auto";
						}
					var	arrowTop = pos.top + $(this).height() + 3,
					arrowLeft = pos.left + ($(this).width() / 2);

					eval("timeInMore = setTimeout(function(){ $popup.show(15).css({'top': top + 'px', 'left': left, 'right': right});$('li.parentMore > .arrow').show(15).css({'top': arrowTop + 'px', 'left': arrowLeft + 'px'}); }, 200);");					
					eval("clearTimeout(timeOutMore)");						
				}, function() {
					eval("clearTimeout(timeInMore)");
					eval("timeOutMore = setTimeout(function(){ $popup.hide(15);$('li.parentMore > .arrow').hide(15); }, 200);");	
				});				
			
			} else if(s.undo && $this.find('ul.submenuMore')) {
				$menu = $this.find('ul.submenuMore');
				numToRemove = $menu.find('li').length;
				for(i = 1; i <= numToRemove; i++) {
					$menu.find('> li:first-child').appendTo($this);
				}
				$menu.remove();
				$this.find('> li.parentMore').remove();
			}
		});
	};
}));