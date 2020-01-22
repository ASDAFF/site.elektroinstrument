function catalogSetConstructDefault(arSetIds, ajax_path, price_currency, lid, element_id, detail_img, items_ratio) {
	this.arSetIDs = arSetIds;
	this.ajax_path = ajax_path;
	this.price_currency = price_currency;
	this.lid = lid;
	this.element_id = element_id;
	this.detail_img = detail_img;
	this.items_ratio = items_ratio;
}

catalogSetConstructDefault.prototype.DeleteItem = function(element, item_id) {
	var wrapObj = element.parentNode,
		i,
		l;
	BX.remove(element);

	for(i = 0, l = this.arSetIDs.length; i < l; i++) {
		if(this.arSetIDs[i] == item_id) {
			this.arSetIDs.splice(i,1);
		}
	}

	var sumPrice = +BX.firstChild(wrapObj).getAttribute("data-price");
	var sumOldPrice = +BX.firstChild(wrapObj).getAttribute("data-old-price");
	var sumDiffDiscountPrice = +BX.firstChild(wrapObj).getAttribute("data-discount-diff-price");

	var setItems = BX.findChildren(wrapObj, {className: "set_item"}, true);
	
	if(!!setItems && setItems.length > 0) {
		for(i = 0; i < setItems.length; i++) {			
			sumPrice += +setItems[i].getAttribute("data-price");
			sumOldPrice += +setItems[i].getAttribute("data-old-price");
			sumDiffDiscountPrice += +setItems[i].getAttribute("data-discount-diff-price");
		}
	}
	BX.ajax.post(
		this.ajax_path,
		{
			sessid : BX.bitrix_sessid(),
			action : "ajax_recount_prices",
			sumPrice : sumPrice,
			sumOldPrice : sumOldPrice,
			sumDiffDiscountPrice : sumDiffDiscountPrice,
			currency : this.price_currency
		},
		function(result) {
			var json = JSON.parse(result);
			if(json.sumValue) {
				BX.findChild(wrapObj, {className:"set-result-price"}, true, false).innerHTML = json.sumValue;
			}
			if(json.sumCurrency) {
				BX.findChild(wrapObj, {className:"set-result-price-currency"}, true, false).innerHTML = json.sumCurrency;
			}
			if(json.formatOldSum) {
				BX.findChild(wrapObj, {className:"set-result-price-old"}, true, false).innerHTML = json.formatOldSum;
			} else {
				BX.findChild(wrapObj, {className:"set-result-price-old"}, true, false).style.display = "none";
			}
			if(json.formatDiscDiffSum) {
				BX.findChild(wrapObj, {className:"set-result-price-discount"}, true, false).innerHTML = json.formatDiscDiffSum;
			} else {
				BX.findChild(wrapObj, {className:"set-result-price-percent"}, true, false).style.display = "none";
			}
		}
	);
}

catalogSetConstructDefault.prototype.Add2Basket = function() {
	var detail_img = this.detail_img;

	if(!!BX.CatalogSetConstructor)
		BX.CatalogSetConstructor.popup.close();

	$("#addItemInCart .item_image_full").html("<img class='item_image' src='"+detail_img+"' alt='"+BX.message('setItemAdded2Basket')+"' />");
	$("#addItemInCart .h1").text(BX.message("setItemAdded2Basket"));
	$("#addItemInCart .item_title").remove();

	var ModalName = $("#addItemInCart");
	CentriredModalWindow(ModalName);
	OpenModalWindow(ModalName);	

	BX.ajax.post(
		this.ajax_path,
		{
			sessid: BX.bitrix_sessid(),
			action: 'catalogSetAdd2Basket',
			set_ids: this.arSetIDs,
			lid: this.lid,
			iblockId: BX.message('setIblockId'),
			setOffersCartProps: BX.message('setOffersCartProps'),
			itemsRatio: this.items_ratio
		},
		function(result) {
			$.post("/ajax/basket_line.php", function(data) {
				$(".cart_line").replaceWith(data);
			});
			$.post("/ajax/delay_line.php", function(data) {
				$(".delay_line").replaceWith(data);
			});
			$(".set_result .btn_buy").addClass("hidden");
			$(".set_result .result").removeClass("hidden");			
		}
	);
}

/***POPUP_FUNCTIONS***/
function catalogSetConstructPopup(ItemsCount, Currency, DefaultItemPrice, DefaultItemDiscountPrice, DefaultItemDiscountDiffPrice, ajaxPath, setIds, lid, element_id, items_ratio, detail_img) {
	this.catalogSetItemsCount = ItemsCount;
	this.catalogCurrency = Currency;
	this.catalogDefaultItemPrice = DefaultItemPrice;
	this.catalogDefaultItemDiscountPrice = DefaultItemDiscountPrice;
	this.catalogDefaultItemDiscountDiffPrice = DefaultItemDiscountDiffPrice;
	this.ajaxPath = ajaxPath;
	this.catalogSetIds = setIds;
	this.lid = lid;
	this.element_id = element_id;
	this.items_ratio = items_ratio;
	this.detail_img = detail_img;
}

catalogSetConstructPopup.prototype.catalogSetDelete = function(element) {
	var empty_obj = element.parentNode;

	var objImg = BX.findChild(empty_obj, {className:"item-image"}, true, false);
	var objName = BX.findChild(empty_obj, {className:"item-all-title"}, true, false);
	var itemID = objName.getAttribute("data-item-id");
	var objPrice = BX.findChild(empty_obj, {className:"item-price-cont"}, true, false);

	var _this = this;
	
	var objAddIcon =  BX.create('a', {
		props: {className: "pop-up-add", href: "javascript:void(0)"},
		text: "+",
		events: {click: function() {_this.catalogSetAdd(this);}}
	});	

	var newSetItem = BX.create('DIV', {
		props: {className: "catalog-item-info"},
		children: [objImg, objName, objPrice]
	});	

	var objDiv = BX.create('DIV', {
		props: {className: "catalog-item-card set_item_other"},
		children: [newSetItem, objAddIcon]
	});
	
	BX("bx_catalog_set_construct_slider_"+this.element_id).appendChild(objDiv);

	empty_obj.innerHTML = "";
	BX.addClass(empty_obj, "item_empty");	

	this.recountSlider("delete");
	this.recountPrices();
	
	for(var i = 0, l = this.catalogSetIds.length; i < l; i++) {
		if(this.catalogSetIds[i] == itemID) {
			this.catalogSetIds.splice(i,1);
		}
	}
}

catalogSetConstructPopup.prototype.catalogSetAdd = function(element, emptyObj) {
	if(!emptyObj)
		emptyObj = BX.findChild(BX("bx_catalog_set_construct_popup_"+this.element_id), {className:"item_empty"}, true, false);
	if(emptyObj) {
		var add_obj = element.parentNode;

		var objImg = BX.findChild(add_obj, {className:"item-image"}, true, false);
		var objName = BX.findChild(add_obj, {className:"item-all-title"}, true, false);
		var itemID = objName.getAttribute("data-item-id");
		var objPrice = BX.findChild(add_obj, {className:"item-price-cont"}, true, false);
		
		var _this = this;
		
		var objDeleteIcon =  BX.create('a', {
			props: {className: "bx_item_set_del pop-up-close", href: "javascript:void(0)"},
			html: "<i class='fa fa-times'></i>",
			events: {click: function() {_this.catalogSetDelete(this);}}
		});

		var newSetItem = BX.create('DIV', {
			props: {className: "catalog-item-info"},
			children: [objImg, objName, objPrice]
		});		

		emptyObj.appendChild(newSetItem);
		emptyObj.appendChild(objDeleteIcon);
		
		BX.removeClass(emptyObj, "item_empty");		

		BX.remove(add_obj);

		this.recountSlider("add");
		this.recountPrices();

		this.catalogSetIds.push(itemID);
	}
}

catalogSetConstructPopup.prototype.visibilitySlider = function() {
	var SliderCont = $(".set_construct_slider_cont"),
		SliderContPos = SliderCont.offset().left + SliderCont.outerWidth(),
		SliderContPosLeft = SliderCont.offset().left,
		SliderItemPos = [],
		SliderItemPosLeft = [],
		SliderItems = BX.findChildren(BX("bx_catalog_set_construct_slider_"+this.element_id), {className: "set_item_other"}, true);
		
	$(".set_construct_slider .set_item_other").each(function(){
	  SliderItemPos.push($(this).offset().left + $(this).outerWidth());
	  SliderItemPosLeft.push($(this).offset().left);
	});
	
	if(!!SliderItems && SliderItems.length > 0) {
		for(i = 0; i < SliderItems.length; i++) {
			if(SliderItemPosLeft[i] < SliderContPosLeft || SliderItemPos[i] > SliderContPos) {					
				SliderItems[i].style.visibility = "hidden";
			} else {				
				SliderItems[i].style.visibility = "visible";
			}			
		}
	}
}

catalogSetConstructPopup.prototype.scrollItems = function(direction) {
	var curLeftPercent,
		leftPercent;
	
	if(direction == 'left') {
		curLeftPercent = BX("bx_catalog_set_construct_slider_"+this.element_id).getAttribute('data-style-left');
		if(curLeftPercent >= 0)
			return;
		leftPercent = +(curLeftPercent) + 144;		
	} else {
		curLeftPercent = BX("bx_catalog_set_construct_slider_"+this.element_id).getAttribute('data-style-left');
		if(-curLeftPercent >= (this.catalogSetItemsCount - 5) * 144)
			return;
		leftPercent = +(curLeftPercent) - 144;		
	}
	BX("bx_catalog_set_construct_slider_"+this.element_id).setAttribute('data-style-left', leftPercent);
	BX("bx_catalog_set_construct_slider_"+this.element_id).style.left = leftPercent + 'px';
	
	this.visibilitySlider();
}

catalogSetConstructPopup.prototype.recountSlider = function(action) {
	if(action == 'add') {
		this.catalogSetItemsCount -= 1;
	} else if(action == 'delete') {
		this.catalogSetItemsCount += 1;
	}
	
	BX("bx_catalog_set_construct_slider_"+this.element_id).style.width = this.catalogSetItemsCount <=5 ? "100%" : (144 * this.catalogSetItemsCount) - 2 + "px";	

	if(this.catalogSetItemsCount > 5) {
		BX("bx_catalog_set_construct_slider_left_"+this.element_id).style.display = "block";
		BX("bx_catalog_set_construct_slider_right_"+this.element_id).style.display = "block";		
	} else {
		BX("bx_catalog_set_construct_slider_left_"+this.element_id).style.display = "none";
		BX("bx_catalog_set_construct_slider_right_"+this.element_id).style.display = "none";
		BX("bx_catalog_set_construct_slider_"+this.element_id).style.left = "0px";
		BX("bx_catalog_set_construct_slider_"+this.element_id).setAttribute("data-style-left", 0);
	}
	
	this.visibilitySlider();
}

catalogSetConstructPopup.prototype.recountPrices = function() {
	var sumPrice = +this.catalogDefaultItemDiscountPrice;
	var sumOldPrice = +this.catalogDefaultItemPrice;
	var sumDiffDiscountPrice = +this.catalogDefaultItemDiscountDiffPrice;

	var setObj = BX.findChildren(BX("bx_catalog_set_construct_popup_"+this.element_id), {className:"set_item"}, true);
	for(var i=0; i < setObj.length; i++) {
		if(!BX.hasClass(setObj[i], "item_empty")) {
			var priceObj = BX.findChild(setObj[i], {className:"item-price"}, true, false);
			var price = priceObj.getAttribute("data-discount-price");
			if(price)
				sumPrice += +price;
			var oldPrice = priceObj.getAttribute("data-price");
			if(oldPrice)
				sumOldPrice += +oldPrice;
			var discDiffprice = priceObj.getAttribute("data-discount-diff-price");
			if(discDiffprice)
				sumDiffDiscountPrice += +discDiffprice;
		}
	}

	var element_id = this.element_id;

	BX.ajax.post(
		this.ajaxPath,
		{
			sessid : BX.bitrix_sessid(),
			action : "ajax_recount_prices",
			sumPrice : sumPrice,
			sumOldPrice : sumOldPrice,
			sumDiffDiscountPrice : sumDiffDiscountPrice,
			currency : this.catalogCurrency
		},
		function(result)
		{
			var json = JSON.parse(result);
			
			if(json.sumValue) {				
				BX("set-result-price-"+element_id).innerHTML = json.sumValue;
			}
			if(json.sumCurrency) {				
				BX("set-result-price-currency-"+element_id).innerHTML = json.sumCurrency;
			}
			if(json.formatOldSum) {
				BX("set-result-price-old-"+element_id).innerHTML = json.formatOldSum;				
			} else {
				BX("set-result-price-old-"+element_id).style.display = "none";
			}
			if(json.formatDiscDiffSum) {
				BX("set-result-price-discount-"+element_id).innerHTML = json.formatDiscDiffSum;				
			} else {
				BX("set-result-price-percent-"+element_id).style.display = "none";
			}						
		}
	);
}

catalogSetConstructPopup.prototype.Add2Basket = function() {
	var detail_img = this.detail_img;	

	BX.CatalogSetConstructor.popup.close();

	$("#addItemInCart .item_image_full").html("<img class='item_image' src='"+detail_img+"' alt='"+BX.message('setItemAdded2Basket')+"' />");
	$("#addItemInCart .h1").text(BX.message("setItemAdded2Basket"));
	$("#addItemInCart .item_title").remove();

	var ModalName = $("#addItemInCart");
	CentriredModalWindow(ModalName);
	OpenModalWindow(ModalName);
	
	BX.ajax.post(
		this.ajaxPath,
		{
			sessid: BX.bitrix_sessid(),
			action: 'catalogSetAdd2Basket',
			set_ids: this.catalogSetIds,
			itemsRatio: this.items_ratio,
			lid: this.lid,
			iblockId: BX.message('setIblockId'),
			setOffersCartProps: BX.message('setOffersCartProps')
		},
		function(result) {			
			$.post("/ajax/basket_line.php", function(data) {
				$(".cart_line").replaceWith(data);
			});
			$.post("/ajax/delay_line.php", function(data) {
				$(".delay_line").replaceWith(data);
			});
			$(".popup-set .set_result .btn_buy").addClass("hidden");
			$(".popup-set .set_result .result").removeClass("hidden");
		}
	);
}