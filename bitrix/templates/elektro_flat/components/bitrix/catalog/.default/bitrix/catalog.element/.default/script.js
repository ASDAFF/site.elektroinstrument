(function (window) {

	if(!!window.JCCatalogElement) {
		return;
	}

	window.JCCatalogElement = function (arParams) {
		this.productType = 0;

		this.config = {
			useCatalog: true,
		};

		this.currentIsSet = false;
		this.updateViewedCount = false;

		this.visual = {
			ID: '',
			PICT_ID: '',
			PRICE_ID: '',
			BUY_ID: '',
			DELAY_ID: '',
			STORE_ID: '',
		};

		this.product = {
			name: '',
			id: 0,
		};
		
		this.offers = [];
		this.offerNum = 0;
		this.treeProps = [];
		this.obTreeRows = [];
		this.selectedValues = {};
		this.selectProps = [];
		this.obSelectRows = [];
		
		this.obProduct = null;
		this.obPict = null;
		this.obPrice = null;
		this.obBuy = null;
		this.obDelay = null;
		this.obTree = null;
		this.obSelect = null;
		this.obStore = null;
		
		this.viewedCounter = {
			path: '/bitrix/components/bitrix/catalog.element/ajax.php',
			params: {
				AJAX: 'Y',
				SITE_ID: '',
				PRODUCT_ID: 0,
				PARENT_ID: 0
			}
		};

		this.errorCode = 0;

		if(typeof arParams === 'object') {
			this.params = arParams;
			this.initConfig();

			switch(this.productType) {
				case 0:// no catalog
				case 1://product
				case 2://set
					this.initProductData();
					break;
				case 3://sku
					this.initOffersData();
					break;
				default:
					this.errorCode = -1;
			}
		}
		if(0 === this.errorCode) {
			BX.ready(BX.delegate(this.Init,this));
		}
		this.params = {};
	};

	window.JCCatalogElement.prototype.Init = function() {
		var i = 0,
		strPrefix = '',
		selPrefix = '',
		TreeItems = null;
		SelectItems = null;

		this.obProduct = BX(this.visual.ID);
		if(!this.obProduct) {
			this.errorCode = -1;
		}

		if(3 === this.productType) {
			this.obPict = BX(this.visual.PICT_ID);
			if(!this.obPict && this.config.useCatalog) {
				this.errorCode = -16;
			}

			this.obPrice = BX(this.visual.PRICE_ID);
			if(!this.obPrice && this.config.useCatalog) {
				this.errorCode = -16;
			}

			this.obBuy = BX(this.visual.BUY_ID);
			if(!this.obBuy && this.config.useCatalog) {
				this.errorCode = -16;
			}

			this.obDelay = BX(this.visual.DELAY_ID);
			if(!this.obDelay && this.config.useCatalog) {
				this.errorCode = -16;
			}

			this.obStore = BX(this.visual.STORE_ID);
			if(!this.obStore && this.config.useCatalog) {
				this.errorCode = -16;
			}

			if(!!this.visual.TREE_ID) {
				this.obTree = BX(this.visual.TREE_ID);
				if(!this.obTree) {
					this.errorCode = -256;
				}
				strPrefix = this.visual.TREE_ITEM_ID;
				for(i = 0; i < this.treeProps.length; i++) {
					this.obTreeRows[i] = {
						LIST: BX(strPrefix+this.treeProps[i].ID+'_list'),
						CONT: BX(strPrefix+this.treeProps[i].ID+'_cont')
					};
					if(!this.obTreeRows[i].LIST || !this.obTreeRows[i].CONT) {
						this.errorCode = -512;
						break;
					}
				}
			}
		}

		if(!!this.visual.SELECT_PROP_ID) {
			this.obSelect = BX(this.visual.SELECT_PROP_ID);
			if(!this.obSelect && this.config.useCatalog) {
				this.errorCode = -256;
			}
			selPrefix = this.visual.SELECT_PROP_ITEM_ID;
			for(i = 0; i < this.selectProps.length; i++) {
				this.obSelectRows[i] = BX(selPrefix+this.selectProps[i].ID);
				if(!this.obSelectRows[i]) {
					this.errorCode = -512;
					break;
				}
			}
		}
				
		if(0 === this.errorCode) {
			switch(this.productType) {
				case 0://no catalog
				case 1://product
				case 2://set
					if(!!this.obSelect) {
						SelectItems = BX.findChildren(this.obSelect, {tagName: 'li'}, true);
						if(!!SelectItems && 0 < SelectItems.length) {
							for(i = 0; i < SelectItems.length; i++) {
								BX.bind(SelectItems[i], 'click', BX.delegate(this.SelectProp, this));
							}
							this.SetSelectCurrent();
						}						
					}
					break;
				case 3://sku
					TreeItems = BX.findChildren(this.obTree, {tagName: 'li'}, true);
					if(!!TreeItems && 0 < TreeItems.length) {
						for(i = 0; i < TreeItems.length; i++) {
							BX.bind(TreeItems[i], 'click', BX.delegate(this.SelectOfferProp, this));
						}
					}
					this.SetCurrent();
					
					if(!!this.obSelect) {
						SelectItems = BX.findChildren(this.obSelect, {tagName: 'li'}, true);
						if(!!SelectItems && 0 < SelectItems.length) {
							for(i = 0; i < SelectItems.length; i++) {
								BX.bind(SelectItems[i], 'click', BX.delegate(this.SelectProp, this));
							}
							this.SetSelectCurrent();
						}						
					}
					break;
			}
		}
	};

	window.JCCatalogElement.prototype.initConfig = function() {
		this.productType = parseInt(this.params.PRODUCT_TYPE, 10);
		if(!!this.params.CONFIG && typeof(this.params.CONFIG) === 'object') {
			if(this.params.CONFIG.USE_CATALOG !== 'undefined' && BX.type.isBoolean(this.params.CONFIG.USE_CATALOG)) {
				this.config.useCatalog = this.params.CONFIG.USE_CATALOG;
			}
		} else {
			// old version
			if(this.params.USE_CATALOG !== 'undefined' && BX.type.isBoolean(this.params.USE_CATALOG)) {
				this.config.useCatalog = this.params.USE_CATALOG;
			}
		}
		if(!this.params.VISUAL || typeof(this.params.VISUAL) !== 'object' || !this.params.VISUAL.ID) {
			this.errorCode = -1;
			return;
		}
		this.visual = this.params.VISUAL;
	};

	window.JCCatalogElement.prototype.initProductData = function() {
		if(!!this.params.PRODUCT && 'object' === typeof(this.params.PRODUCT)) {
			this.product.id = this.params.PRODUCT.ID;
			this.product.name = this.params.PRODUCT.NAME;
			if(!!this.params.SELECT_PROPS) {
				this.selectProps = this.params.SELECT_PROPS;
			}
		} else {
			this.errorCode = -1;
		}
	};

	window.JCCatalogElement.prototype.initOffersData = function() {
		if(!!this.params.OFFERS && BX.type.isArray(this.params.OFFERS)) {
			this.offers = this.params.OFFERS;
			this.offerNum = 0;
			if(!!this.params.OFFER_SELECTED) {
				this.offerNum = parseInt(this.params.OFFER_SELECTED, 10);
			}
			if(isNaN(this.offerNum)) {
				this.offerNum = 0;
			}
			if(!!this.params.TREE_PROPS) {
				this.treeProps = this.params.TREE_PROPS;
			}
			if(!!this.params.PRODUCT && typeof(this.params.PRODUCT) === 'object') {
				this.product.id = parseInt(this.params.PRODUCT.ID, 10);
				this.product.name = this.params.PRODUCT.NAME;
				if(!!this.params.SELECT_PROPS) {
					this.selectProps = this.params.SELECT_PROPS;
				}
			}	
		} else {
			this.errorCode = -1;
		}
	};

	window.JCCatalogElement.prototype.SelectProp = function() {
		var i = 0,
		RowItems = null,
		ActiveItems = null,
		selPropValueArr = [],
		SelectName = null,
		SelectValue = null,		
		selAskOrderValueArr = [],
		selPropValue = null,		
		selAskOrderValue = null,
		selDelayOnclick = null,
		selDelayOnclickArr = [],
		selDelayOnclickNew = null,
		target = BX.proxy_context;
		
		if(!!target && target.hasAttribute('data-select-onevalue')) {
			RowItems = BX.findChildren(target.parentNode, {tagName: 'li'}, false);
			if(!!RowItems && 0 < RowItems.length) {
				for(i = 0; i < RowItems.length; i++) {
					BX.removeClass(RowItems[i], 'active');
				}
			}
			BX.addClass(target, 'active');
		}
		
		ActiveItems = BX.findChildren(this.obSelect, {tagName: 'li', className: 'active'}, true);
		if(!!ActiveItems && 0 < ActiveItems.length) {
			for(i = 0; i < ActiveItems.length; i++) {
				selPropValueArr[i] = ActiveItems[i].getAttribute('data-select-onevalue');
				/*ASK_ORDER*/
				SelectName = BX.findChildren(ActiveItems[i].parentNode.parentNode, {className: 'h3'}, true);
				SelectValue = BX.findChildren(ActiveItems[i], {tagName: 'span'}, true);
				if((!!SelectName && 0 < SelectName.length) && (!!SelectValue && 0 < SelectValue.length)) {					
					selAskOrderValueArr[i] = SelectName[0].innerHTML+': '+SelectValue[0].innerHTML;
				}
			}
		}

		selPropValue = selPropValueArr.join('||');		
		selAskOrderValue = selAskOrderValueArr.join('; ');
		
		if(!!this.offers && 0 < this.offers.length) {
			for(i = 0; i < this.offers.length; i++) {
				/*CART*/
				if(!!BX('select_props_'+this.offers[i].ID))
					BX('select_props_'+this.offers[i].ID).value = selPropValue;				
				/*DELAY*/
				if(!!BX('catalog-item-delay-'+this.offers[i].ID)) {
					selDelayOnclick = BX('catalog-item-delay-'+this.offers[i].ID).getAttribute('onclick');
					selDelayOnclickArr = selDelayOnclick.split("',");
					selDelayOnclickArr[3] = " '"+selPropValue;
					selDelayOnclickNew = selDelayOnclickArr.join("',");
					BX('catalog-item-delay-'+this.offers[i].ID).setAttribute('onclick', selDelayOnclickNew);
				}
				/*BOC*/
				if(!!BX('boc_element_select_props_'+this.offers[i].ID))
					BX('boc_element_select_props_'+this.offers[i].ID).value = selPropValue;
				/*ASK_PRICE*/
				if(!!BX('ask_price_message_'+this.offers[i].ID))
					BX('ask_price_message_'+this.offers[i].ID).innerHTML = this.AskPriceMessageNew[i]+'; '+selAskOrderValue+')';
				/*ORDER*/
				if(!!BX('order_message_'+this.offers[i].ID))
					BX('order_message_'+this.offers[i].ID).innerHTML = this.OrderMessageNew[i]+'; '+selAskOrderValue+')';
			}
		} else {
			/*CART*/
			if(!!BX('select_props_'+this.product.id))
				BX('select_props_'+this.product.id).value = selPropValue;			
			/*DELAY*/
			if(!!BX('catalog-item-delay-'+this.product.id)) {
				selDelayOnclick = BX('catalog-item-delay-'+this.product.id).getAttribute('onclick');
				selDelayOnclickArr = selDelayOnclick.split("',");
				selDelayOnclickArr[3] = " '"+selPropValue;
				selDelayOnclickNew = selDelayOnclickArr.join("',");
				BX('catalog-item-delay-'+this.product.id).setAttribute('onclick', selDelayOnclickNew);
			}
			/*BOC*/
			if(!!BX('boc_element_select_props_'+this.product.id))
				BX('boc_element_select_props_'+this.product.id).value = selPropValue;
			/*ASK_PRICE*/
			if(!!BX('ask_price_message_'+this.product.id))
				BX('ask_price_message_'+this.product.id).innerHTML = this.AskPriceMessage+' ('+selAskOrderValue+')';
			/*ORDER*/
			if(!!BX('order_message_'+this.product.id))
				BX('order_message_'+this.product.id).innerHTML = this.OrderMessage+' ('+selAskOrderValue+')';
		}
	};

	window.JCCatalogElement.prototype.SelectOfferProp = function() {
		var i = 0,
		strTreeValue = '',
		arTreeItem = [],
		RowItems = null,
		target = BX.proxy_context;

		if(!!target && target.hasAttribute('data-treevalue')) {
			strTreeValue = target.getAttribute('data-treevalue');
			arTreeItem = strTreeValue.split('_');
			this.SearchOfferPropIndex(arTreeItem[0], arTreeItem[1]);
			RowItems = BX.findChildren(target.parentNode, {tagName: 'li'}, false);
			if(!!RowItems && 0 < RowItems.length) {
				for(i = 0; i < RowItems.length; i++) {
					BX.removeClass(RowItems[i], 'active');
				}
			}
			BX.addClass(target, 'active');
		}
	};

	window.JCCatalogElement.prototype.SearchOfferPropIndex = function(strPropID, strPropValue) {
		var strName = '',
		arShowValues = false,
		arCanBuyValues = [],
		index = -1,
		i, j,
		arFilter = {},
		tmpFilter = [];

		for(i = 0; i < this.treeProps.length; i++) {
			if(this.treeProps[i].ID === strPropID) {
				index = i;
				break;
			}
		}

		if(-1 < index) {
			for(i = 0; i < index; i++) {
				strName = 'PROP_'+this.treeProps[i].ID;
				arFilter[strName] = this.selectedValues[strName];
			}
			strName = 'PROP_'+this.treeProps[index].ID;
			arFilter[strName] = strPropValue;
			for(i = index+1; i < this.treeProps.length; i++) {
				strName = 'PROP_'+this.treeProps[i].ID;
				arShowValues = this.GetRowValues(arFilter, strName);
				if(!arShowValues) {
					break;
				}
				arCanBuyValues = arShowValues;
				if(!!this.selectedValues[strName] && BX.util.in_array(this.selectedValues[strName], arCanBuyValues)) {
					arFilter[strName] = this.selectedValues[strName];
				} else {
					arFilter[strName] = arCanBuyValues[0];
				}
				this.UpdateRow(i, arFilter[strName], arShowValues, arCanBuyValues);
			}
			this.selectedValues = arFilter;
			this.ChangeInfo();
		}
	};

	window.JCCatalogElement.prototype.UpdateRow = function(intNumber, activeID, showID, canBuyID) {
		var i = 0,
		showI = 0,
		value = '',
		obData = {},
		RowItems = null,
		isCurrent = false,
		selectIndex = 0;

		if(-1 < intNumber && intNumber < this.obTreeRows.length) {
			RowItems = BX.findChildren(this.obTreeRows[intNumber].LIST, {tagName: 'li'}, false);
			if(!!RowItems && 0 < RowItems.length) {
				obData = {
					props: { className: '' },
					style: {}
				};
				for(i = 0; i < RowItems.length; i++) {
					value = RowItems[i].getAttribute('data-onevalue');
					isCurrent = (value === activeID);
					if(BX.util.in_array(value, canBuyID)) {
						obData.props.className = (isCurrent ? 'active' : '');
					} else {
						obData.props.className = (isCurrent ? 'active hidden' : 'hidden');
					}
					obData.style.display = 'none';
					if(BX.util.in_array(value, showID)) {
						obData.style.display = '';
						if(isCurrent) {
							selectIndex = showI;
						}
						showI++;
					}
					BX.adjust(RowItems[i], obData);
				}

				obData = {
					style: {}
				};

				BX.adjust(this.obTreeRows[intNumber].LIST, obData);
			}
		}
	};

	window.JCCatalogElement.prototype.GetRowValues = function(arFilter, index) {
		var arValues = [],
		i = 0,
		j = 0,
		boolSearch = false,
		boolOneSearch = true;

		if(0 === arFilter.length) {
			for(i = 0; i < this.offers.length; i++) {
				if(!BX.util.in_array(this.offers[i].TREE[index], arValues)) {
					arValues[arValues.length] = this.offers[i].TREE[index];
				}
			}
			boolSearch = true;
		} else {
			for(i = 0; i < this.offers.length; i++) {
				boolOneSearch = true;
				for(j in arFilter) {
					if(arFilter[j] !== this.offers[i].TREE[j]) {
						boolOneSearch = false;
						break;
					}
				}
				if(boolOneSearch) {
					if(!BX.util.in_array(this.offers[i].TREE[index], arValues)) {
						arValues[arValues.length] = this.offers[i].TREE[index];
					}
					boolSearch = true;
				}
			}
		}
		return (boolSearch ? arValues : false);
	};

	window.JCCatalogElement.prototype.SetSelectCurrent = function() {
		var i = 0,
		SelectItems = null,
		selPropValueArr = [],
		SelectName = null,
		SelectValue = null,		
		selAskOrderValueArr = [],
		selPropValue = null,		
		selAskOrderValue = null,
		selDelayOnclick = null,
		selDelayOnclickArr = [],
		selDelayOnclickNew = null;
		this.AskPriceMessageNew = [];
		this.OrderMessageNew = [];

		for(i = 0; i < this.obSelectRows.length; i++) {
			SelectItems = BX.findChildren(this.obSelectRows[i], {tagName: 'li'}, true);
			if(!!SelectItems && 0 < SelectItems.length) {
				BX.addClass(SelectItems[0], 'active');
				selPropValueArr[i] = SelectItems[0].getAttribute('data-select-onevalue');
				/*ASK_ORDER*/
				SelectName = BX.findChildren(this.obSelectRows[i], {className: 'h3'}, true);
				SelectValue = BX.findChildren(SelectItems[0], {tagName: 'span'}, true);
				if((!!SelectName && 0 < SelectName.length) && (!!SelectValue && 0 < SelectValue.length)) {					
					selAskOrderValueArr[i] = SelectName[0].innerHTML+': '+SelectValue[0].innerHTML;
				}
			}
		}
		
		selPropValue = selPropValueArr.join('||');		
		selAskOrderValue = selAskOrderValueArr.join('; ');
		
		if(!!this.offers && 0 < this.offers.length) {
			for(i = 0; i < this.offers.length; i++) {
				/*CART*/
				if(!!BX('select_props_'+this.offers[i].ID))
					BX('select_props_'+this.offers[i].ID).value = selPropValue;				
				/*DELAY*/
				if(!!BX('catalog-item-delay-'+this.offers[i].ID)) {
					selDelayOnclick = BX('catalog-item-delay-'+this.offers[i].ID).getAttribute('onclick');
					selDelayOnclickArr = selDelayOnclick.split("',");
					selDelayOnclickArr[3] = " '"+selPropValue;
					selDelayOnclickNew = selDelayOnclickArr.join("',");
					BX('catalog-item-delay-'+this.offers[i].ID).setAttribute('onclick', selDelayOnclickNew);
				}
				/*BOC*/
				if(!!BX('boc_element_select_props_'+this.offers[i].ID))
					BX('boc_element_select_props_'+this.offers[i].ID).value = selPropValue;
				/*ASK_PRICE*/
				if(!!BX('ask_price_message_'+this.offers[i].ID)) {
					this.AskPriceMessage = BX('ask_price_message_'+this.offers[i].ID).innerHTML;
					this.AskPriceMessageArr = this.AskPriceMessage.split(')');
					this.AskPriceMessageNew[i] = this.AskPriceMessageArr[0];
					BX('ask_price_message_'+this.offers[i].ID).innerHTML = this.AskPriceMessageNew[i]+'; '+selAskOrderValue+')';
				}
				/*ORDER*/
				if(!!BX('order_message_'+this.offers[i].ID)) {
					this.OrderMessage = BX('order_message_'+this.offers[i].ID).innerHTML;
					this.OrderMessageArr = this.OrderMessage.split(')');
					this.OrderMessageNew[i] = this.OrderMessageArr[0];
					BX('order_message_'+this.offers[i].ID).innerHTML = this.OrderMessageNew[i]+'; '+selAskOrderValue+')';
				}
			}
		} else {
			/*CART*/
			if(!!BX('select_props_'+this.product.id))
				BX('select_props_'+this.product.id).value = selPropValue;			
			/*DELAY*/
			if(!!BX('catalog-item-delay-'+this.product.id)) {
				selDelayOnclick = BX('catalog-item-delay-'+this.product.id).getAttribute('onclick');
				selDelayOnclickArr = selDelayOnclick.split("',");
				selDelayOnclickArr[3] = " '"+selPropValue;
				selDelayOnclickNew = selDelayOnclickArr.join("',");
				BX('catalog-item-delay-'+this.product.id).setAttribute('onclick', selDelayOnclickNew);
			}
			/*BOC*/
			if(!!BX('boc_element_select_props_'+this.product.id))
				BX('boc_element_select_props_'+this.product.id).value = selPropValue;
			/*ASK_PRICE*/
			if(!!BX('ask_price_message_'+this.product.id)) {
				this.AskPriceMessage = BX('ask_price_message_'+this.product.id).innerHTML;
				BX('ask_price_message_'+this.product.id).innerHTML = this.AskPriceMessage+' ('+selAskOrderValue+')';
			}
			/*ORDER*/
			if(!!BX('order_message_'+this.product.id)) {
				this.OrderMessage = BX('order_message_'+this.product.id).innerHTML;
				BX('order_message_'+this.product.id).innerHTML = this.OrderMessage+' ('+selAskOrderValue+')';
			}
		}
	}

	window.JCCatalogElement.prototype.SetCurrent = function() {
		var i = 0,
		j = 0,
		strName = '',
		arShowValues = false,
		arCanBuyValues = [],
		arFilter = {},
		tmpFilter = [],
		current = this.offers[this.offerNum].TREE;

		for(i = 0; i < this.treeProps.length; i++) {
			strName = 'PROP_'+this.treeProps[i].ID;
			arShowValues = this.GetRowValues(arFilter, strName);
			if(!arShowValues) {
				break;
			}
			if(BX.util.in_array(current[strName], arShowValues)) {
				arFilter[strName] = current[strName];
			} else {
				arFilter[strName] = arShowValues[0];
				this.offerNum = 0;
			}
			arCanBuyValues = arShowValues;
			this.UpdateRow(i, arFilter[strName], arShowValues, arCanBuyValues);
		}
		this.selectedValues = arFilter;
		this.ChangeInfo();
	};

	window.JCCatalogElement.prototype.ChangeInfo = function() {
		var index = -1,
		i = 0,
		j = 0,
		boolOneSearch = true;

		for(i = 0; i < this.offers.length; i++) {
			boolOneSearch = true;
			for(j in this.selectedValues) {
				if(this.selectedValues[j] !== this.offers[i].TREE[j]) {
					boolOneSearch = false;
					break;
				}
			}
			if(boolOneSearch) {
				index = i;
				break;
			}
		}
		if(-1 < index) {
			this.setPict(this.offers[index].ID);
			this.setPrice(this.offers[index].ID);
			this.setBuy(this.offers[index].ID);
			this.setDelay(this.offers[index].ID);
			this.setStore(this.offers[index].ID);
			this.offerNum = index;
			this.incViewedCounter();
			BX.onCustomEvent('onCatalogStoreProductChange', [this.offers[this.offerNum].ID]);
		}
	};

	window.JCCatalogElement.prototype.setPict = function(offer_id) {
		PictItems = BX.findChildren(this.obPict, {className: 'detail_picture'}, true);
		if(!!PictItems && 0 < PictItems.length) {
			for(i = 0; i < PictItems.length; i++) {
				BX.addClass(PictItems[i], 'hidden');
			}
		}
		BX.removeClass(BX('detail_picture_'+offer_id), 'hidden');
		
		PictItemsA = BX.findChildren(this.obPict, {className: 'catalog-detail-images'}, true);
		if(!!PictItemsA && 0 < PictItemsA.length) {
			for(i = 0; i < PictItemsA.length; i++) {
				BX.adjust(PictItemsA[i], {props: {rel: ''}});
			}
		}
		BX.adjust(BX('catalog-detail-images-'+offer_id), {props: {rel: 'lightbox'}});
	};

	window.JCCatalogElement.prototype.setPrice = function(offer_id) {
		PriceItems = BX.findChildren(this.obPrice, {className: 'detail_price'}, true);
		if(!!PriceItems && 0 < PriceItems.length) {
			for(i = 0; i < PriceItems.length; i++) {
				BX.addClass(PriceItems[i], 'hidden');
			}
		}
		BX.removeClass(BX('detail_price_'+offer_id), 'hidden');
	};

	window.JCCatalogElement.prototype.setBuy = function(offer_id) {
		BuyItems = BX.findChildren(this.obBuy, {className: 'buy_more_detail'}, true);
		if(!!BuyItems && 0 < BuyItems.length) {
			for(i = 0; i < BuyItems.length; i++) {
				BX.addClass(BuyItems[i], 'hidden');
			}
		}
		BX.removeClass(BX('buy_more_detail_'+offer_id), 'hidden');
	};

	window.JCCatalogElement.prototype.setDelay = function(offer_id) {
		DelayItems = BX.findChildren(this.obDelay, {className: 'delay'}, true);
		if(!!DelayItems && 0 < DelayItems.length) {
			for(i = 0; i < DelayItems.length; i++) {
				BX.addClass(DelayItems[i], 'hidden');
			}
		}
		BX.removeClass(BX('delay_'+offer_id), 'hidden');
	};

	window.JCCatalogElement.prototype.setStore = function(offer_id) {
		StoreItems = BX.findChildren(this.obStore, {className: 'catalog-detail-stores'}, true);
		if(!!StoreItems && 0 < StoreItems.length) {
			for(i = 0; i < StoreItems.length; i++) {
				BX.addClass(StoreItems[i], 'hidden');
			}
		}
		BX.removeClass(BX('catalog-detail-stores-'+offer_id), 'hidden');
	};

	window.JCCatalogElement.prototype.incViewedCounter = function() {
		if(this.currentIsSet && !this.updateViewedCount) {
			switch(this.productType) {
				case 1:
				case 2:
					this.viewedCounter.params.PRODUCT_ID = this.product.id;
					this.viewedCounter.params.PARENT_ID = this.product.id;
					break;
				case 3:
					this.viewedCounter.params.PARENT_ID = this.product.id;
					this.viewedCounter.params.PRODUCT_ID = this.offers[this.offerNum].ID;
					break;
				default:
					return;
			}
			this.viewedCounter.params.SITE_ID = BX.message('SITE_ID');
			this.updateViewedCount = true;
			BX.ajax.post(
				this.viewedCounter.path,
				this.viewedCounter.params,
				BX.delegate(function(){ this.updateViewedCount = false; }, this)
			);
		}
	};

	window.JCCatalogElement.prototype.allowViewedCount = function(update) {
		update = !!update;
		this.currentIsSet = true;
		if(update) {
			this.incViewedCounter();
		}
	};
})(window);