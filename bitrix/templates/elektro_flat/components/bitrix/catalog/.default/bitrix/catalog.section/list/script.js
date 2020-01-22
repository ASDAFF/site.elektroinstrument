(function (window) {

	if(!!window.JCCatalogSection) {
		return;
	}

	window.JCCatalogSection = function (arParams) {
		this.productType = 0;
		this.visual = {
			ID: '',
			PICT_ID: '',
			PRICE_ID: '',
			BUY_ID: '',
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
		this.obTree = null;
		this.obSelect = null;
			
		this.errorCode = 0;

		if('object' === typeof arParams) {
			this.productType = parseInt(arParams.PRODUCT_TYPE, 10);
			this.visual = arParams.VISUAL;

			switch (this.productType) {
				case 1://product
				case 2://set
					if(!!arParams.PRODUCT && 'object' === typeof(arParams.PRODUCT)) {
						this.product.name = arParams.PRODUCT.NAME;
						this.product.id = arParams.PRODUCT.ID;
						if(!!arParams.SELECT_PROPS) {
							this.selectProps = arParams.SELECT_PROPS;
						}
					} else {
						this.errorCode = -1;
					}
					break;
				case 3://sku
					if(!!arParams.OFFERS && BX.type.isArray(arParams.OFFERS)) {
						if(!!arParams.PRODUCT && 'object' === typeof(arParams.PRODUCT)) {
							this.product.name = arParams.PRODUCT.NAME;
							this.product.id = arParams.PRODUCT.ID;
							if(!!arParams.SELECT_PROPS) {
								this.selectProps = arParams.SELECT_PROPS;
							}
						}
						this.offers = arParams.OFFERS;
						this.offerNum = 0;
						if(!!arParams.OFFER_SELECTED) {
							this.offerNum = parseInt(arParams.OFFER_SELECTED, 10);
						}
						if(isNaN(this.offerNum)) {
							this.offerNum = 0;
						}
						if(!!arParams.TREE_PROPS) {
							this.treeProps = arParams.TREE_PROPS;
						}
					} else {
						this.errorCode = -1;
					}
					break;
				default:
					this.errorCode = -1;
			}
		}
		if(0 === this.errorCode) {
			BX.ready(BX.delegate(this.Init,this));
		}
	};

	window.JCCatalogSection.prototype.Init = function() {
		var i = 0,
		strPrefix = '',
		selPrefix = '',
		TreeItems = null,
		SelectItems = null;

		this.obProduct = BX(this.visual.ID);
		if(!this.obProduct) {
			this.errorCode = -1;
		}
		
		if(3 === this.productType) {
			this.obPict = BX(this.visual.PICT_ID);
			if(!this.obPict) {
				this.errorCode = -16;
			}

			this.obPrice = BX(this.visual.PRICE_ID);
			if(!this.obPrice) {
				this.errorCode = -16;
			}

			this.obBuy = BX(this.visual.BUY_ID);
			if(!this.obBuy) {
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
			if(!this.obSelect) {
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
			switch (this.productType) {
				case 1://product
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

	window.JCCatalogSection.prototype.SelectProp = function() {
		var i = 0,
		RowItems = null,
		ActiveItems = null,
		selPropValueArr = [],
		SelectName = null,
		SelectValue = null,		
		selAskOrderValueArr = [],
		selPropValue = null,		
		selAskOrderValue = null,
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
				SelectName = BX.findChildren(ActiveItems[i].parentNode.parentNode.parentNode, {className: 'h3'}, true);
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
		}
	}

	window.JCCatalogSection.prototype.SelectOfferProp = function() {
		var i = 0,
		value = '',
		strTreeValue = '',
		arTreeItem = [],
		RowItems = null,
		target = BX.proxy_context;

		if(!!target && target.hasAttribute('data-treevalue')) {
			strTreeValue = target.getAttribute('data-treevalue');
			arTreeItem = strTreeValue.split('_');
			if(this.SearchOfferPropIndex(arTreeItem[0], arTreeItem[1])) {
				RowItems = BX.findChildren(target.parentNode, {tagName: 'li'}, false);
				if(!!RowItems && 0 < RowItems.length) {
					for(i = 0; i < RowItems.length; i++) {
						value = RowItems[i].getAttribute('data-onevalue');
						if(value === arTreeItem[1]) {
							BX.addClass(RowItems[i], 'active');
						} else {
							BX.removeClass(RowItems[i], 'active');
						}
					}
				}
			}
		}
	};

	window.JCCatalogSection.prototype.SearchOfferPropIndex = function(strPropID, strPropValue) {
		var strName = '',
		arShowValues = false,
		i, j,
		arCanBuyValues = [],
		index = -1,
		arFilter = {};
		
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
			arShowValues = this.GetRowValues(arFilter, strName);
			if(!arShowValues) {
				return false;
			}
			if(!BX.util.in_array(strPropValue, arShowValues)) {
				return false;
			}
			arFilter[strName] = strPropValue;
			for(i = index+1; i < this.treeProps.length; i++) {
				strName = 'PROP_'+this.treeProps[i].ID;
				arShowValues = this.GetRowValues(arFilter, strName);
				if(!arShowValues) {
					return false;
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
		return true;
	};

	window.JCCatalogSection.prototype.UpdateRow = function(intNumber, activeID, showID, canBuyID) {
		var i = 0,
		showI = 0,
		value = '',
		obData = {},
		isCurrent = false,
		selectIndex = 0,
		RowItems = null;

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

	window.JCCatalogSection.prototype.GetRowValues = function(arFilter, index) {
		var i = 0,
		j,
		arValues = [],
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

	window.JCCatalogSection.prototype.SetSelectCurrent = function() {
		var i = 0,
		SelectItems = null,
		selPropValueArr = [],
		SelectName = null,
		SelectValue = null,		
		selAskOrderValueArr = [],
		selPropValue = null,		
		selAskOrderValue = null,
		MinselDelayOnclick = null,
		MinselDelayOnclickArr = [],
		MinselDelayOnclickNew = null,
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
			}
			/*ASK_ORDER*/
			SelectName = BX.findChildren(this.obSelectRows[i], {className: 'h3'}, true);
			SelectValue = BX.findChildren(SelectItems[0], {tagName: 'span'}, true);
			if((!!SelectName && 0 < SelectName.length) && (!!SelectValue && 0 < SelectValue.length)) {				
				selAskOrderValueArr[i] = SelectName[0].innerHTML+': '+SelectValue[0].innerHTML;
			}
		}

		selPropValue = selPropValueArr.join('||');		
		selAskOrderValue = selAskOrderValueArr.join('; ');
		
		if(!!this.offers && 0 < this.offers.length) {
			for(i = 0; i < this.offers.length; i++) {
				/*CART*/
				if(!!BX('select_props_'+this.offers[i].ID))
					BX('select_props_'+this.offers[i].ID).value = selPropValue;				
				/*MIN_DELAY*/
				if(!!BX('catalog-item-delay-'+this.offers[i].ID)) {
					MinselDelayOnclick = BX('catalog-item-delay-'+this.offers[i].ID).getAttribute('onclick');
					MinselDelayOnclickArr = MinselDelayOnclick.split("',");
					MinselDelayOnclickArr[3] = " '"+selPropValue;
					MinselDelayOnclickNew = MinselDelayOnclickArr.join("',");
					BX('catalog-item-delay-'+this.offers[i].ID).setAttribute('onclick', MinselDelayOnclickNew);
				}
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
		}
	}

	window.JCCatalogSection.prototype.SetCurrent = function() {
		var i = 0,
		j = 0,
		arCanBuyValues = [],
		strName = '',
		arShowValues = false,
		arFilter = {},
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

	window.JCCatalogSection.prototype.ChangeInfo = function() {
		var i = 0,
		j,
		index = -1,
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
			this.setPict(this.product.id, this.offers[index].ID);
			this.setPrice(this.product.id, this.offers[index].ID);
			this.setBuy(this.product.id, this.offers[index].ID);
			this.offerNum = index;
		}
	};

	window.JCCatalogSection.prototype.setPict = function(item_id, offer_id) {
		PictItems = BX.findChildren(this.obPict, {className: 'img'}, true);
		if(!!PictItems && 0 < PictItems.length) {
			for(i = 0; i < PictItems.length; i++) {
				BX.addClass(PictItems[i], 'hidden');
			}
		}
		BX.removeClass(BX('img_'+item_id+'_'+offer_id), 'hidden');
	};

	window.JCCatalogSection.prototype.setPrice = function(item_id, offer_id) {
		PriceItems = BX.findChildren(this.obPrice, {className: 'price'}, true);
		if(!!PriceItems && 0 < PriceItems.length) {
			for(i = 0; i < PriceItems.length; i++) {
				BX.addClass(PriceItems[i], 'hidden');
			}
		}
		BX.removeClass(BX('price_'+item_id+'_'+offer_id), 'hidden');
	};

	window.JCCatalogSection.prototype.setBuy = function(item_id, offer_id) {
		BuyItems = BX.findChildren(this.obBuy, {className: 'buy_more'}, true);
		if(!!BuyItems && 0 < BuyItems.length) {
			for(i = 0; i < BuyItems.length; i++) {
				BX.addClass(BuyItems[i], 'hidden');
			}
		}
		BX.removeClass(BX('buy_more_'+item_id+'_'+offer_id), 'hidden');
	};
})(window);