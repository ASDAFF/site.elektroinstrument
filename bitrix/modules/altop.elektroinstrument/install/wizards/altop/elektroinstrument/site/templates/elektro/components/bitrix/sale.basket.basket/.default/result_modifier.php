<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("sale"))
	return;

/***BASKET_ITEMS***/
if(is_array($arResult["ITEMS"]["AnDelCanBuy"])) {
	foreach($arResult["ITEMS"]["AnDelCanBuy"] as $key => $arItem) {		
		$ar = CIBlockElement::GetList(
			array(), 
			array("=ID" => $arItem["PRODUCT_ID"]), 
			false, 
			false, 
			array(
				"ID", 
				"IBLOCK_ID", 
				"NAME", 
				"DETAIL_PICTURE",
				"PROPERTY_CML2_LINK"
			)
		)->Fetch();
		if($ar["DETAIL_PICTURE"] > 0) {
			$ar["DETAIL_PICTURE"] = CFile::ResizeImageGet($ar["DETAIL_PICTURE"], array("width" => 65, "height" => 65), BX_RESIZE_IMAGE_PROPORTIONAL, true);
			$arResult["ITEMS"]["AnDelCanBuy"][$key]["DETAIL_PICTURE"] = $ar["DETAIL_PICTURE"];
		} else {
			if(!empty($ar["PROPERTY_CML2_LINK_VALUE"])) {
				$ar2 = CIBlockElement::GetList(
					array(), 
					array("=ID" => $ar["PROPERTY_CML2_LINK_VALUE"]), 
					false, 
					false, 
					array(
						"ID", 
						"IBLOCK_ID", 
						"NAME", 
						"DETAIL_PICTURE"
					)
				)->Fetch();
				if($ar2["DETAIL_PICTURE"] > 0) {
					$ar2["DETAIL_PICTURE"] = CFile::ResizeImageGet($ar2["DETAIL_PICTURE"], array("width" => 65, "height" => 65), BX_RESIZE_IMAGE_PROPORTIONAL, true);
					$arResult["ITEMS"]["AnDelCanBuy"][$key]["DETAIL_PICTURE"] = $ar2["DETAIL_PICTURE"];
				}
			}
		}


		/***MEASURE_RATIO***/
		if(!isset($arItem["MEASURE_RATIO"]))
		$arResult["ITEMS"]["AnDelCanBuy"][$key]["MEASURE_RATIO"] = 1;
				
		
		/***CART_ACCESSORIES***/
		$mxResult = CCatalogSku::GetProductInfo($arItem["PRODUCT_ID"]);
		if(!empty($mxResult["ID"])):
			$PARENT_PRODUCT_ID = $mxResult["ID"];
		else:
			$PARENT_PRODUCT_ID = $arItem["PRODUCT_ID"];
		endif;
		
		$arResult["ITEMS"]["PARENT_PRODUCT_IDS"][] = $PARENT_PRODUCT_ID;

		$arr_access = CIBlockElement::GetList(
			Array("sort"=>"asc"), 
			Array("ACTIVE"=>"Y", "ID" => $PARENT_PRODUCT_ID), 
			false, 
			false, 
			Array("PROPERTY_ACCESSORIES")
		);
		
		while($arr_acces = $arr_access->GetNextElement()) {
			$arElement = $arr_acces->GetFields();
			
			if(!empty($arElement["PROPERTY_ACCESSORIES_VALUE"])):
				$arResult["ITEMS"]["ACCESSORIES"][] = $arElement["PROPERTY_ACCESSORIES_VALUE"];
			endif;
		}
	}
}

/***CLEAR_BASKET_ITEMS***/
if(isset($_REQUEST["BasketClear"]) && $_REQUEST["BasketClear"] == "Y") {	
	if(is_array($arResult["ITEMS"]["AnDelCanBuy"])) {
		foreach($arResult["ITEMS"]["AnDelCanBuy"] as $key => $arItem) {
			CSaleBasket::Delete($arItem["ID"]);
		}
		
		LocalRedirect($APPLICATION->GetCurPage());
	}
}

/***DELAY_ITEMS***/
if(is_array($arResult["ITEMS"]["DelDelCanBuy"])) {
	foreach($arResult["ITEMS"]["DelDelCanBuy"] as $key => $arItem) {
		$ar = CIBlockElement::GetList(
			array(), 
			array("=ID" => $arItem["PRODUCT_ID"]), 
			false, 
			false, 
			array(
				"ID", 
				"IBLOCK_ID", 
				"NAME", 
				"DETAIL_PICTURE",
				"PROPERTY_CML2_LINK"
			)
		)->Fetch();
		if($ar["DETAIL_PICTURE"] > 0) {
			$ar["DETAIL_PICTURE"] = CFile::ResizeImageGet($ar["DETAIL_PICTURE"], array("width" => 65, "height" => 65), BX_RESIZE_IMAGE_PROPORTIONAL, true);
			$arResult["ITEMS"]["DelDelCanBuy"][$key]["DETAIL_PICTURE"] = $ar["DETAIL_PICTURE"];
		} else {
			if(!empty($ar["PROPERTY_CML2_LINK_VALUE"])) {
				$ar2 = CIBlockElement::GetList(
					array(), 
					array("=ID" => $ar["PROPERTY_CML2_LINK_VALUE"]), 
					false, 
					false, 
					array(
						"ID", 
						"IBLOCK_ID", 
						"NAME", 
						"DETAIL_PICTURE"
					)
				)->Fetch();
				if($ar2["DETAIL_PICTURE"] > 0) {
					$ar2["DETAIL_PICTURE"] = CFile::ResizeImageGet($ar2["DETAIL_PICTURE"], array("width" => 65, "height" => 65), BX_RESIZE_IMAGE_PROPORTIONAL, true);
					$arResult["ITEMS"]["DelDelCanBuy"][$key]["DETAIL_PICTURE"] = $ar2["DETAIL_PICTURE"];
				}
			}
		}
	}
}

/***CLEAR_DELAY_ITEMS***/
if(isset($_REQUEST["DelayClear"]) && $_REQUEST["DelayClear"] == "Y") {	
	if(is_array($arResult["ITEMS"]["DelDelCanBuy"])) {
		foreach($arResult["ITEMS"]["DelDelCanBuy"] as $key => $arItem) {
			CSaleBasket::Delete($arItem["ID"]);
		}
		
		LocalRedirect($APPLICATION->GetCurPage());
	}
}

/***SUBSCRIBE_ITEMS***/
if(is_array($arResult["ITEMS"]["ProdSubscribe"])) {
	foreach($arResult["ITEMS"]["ProdSubscribe"] as $key => $arItem) {
		$ar = CIBlockElement::GetList(
			array(), 
			array("=ID" => $arItem["PRODUCT_ID"]), 
			false, 
			false, 
			array(
				"ID", 
				"IBLOCK_ID", 
				"NAME", 
				"DETAIL_PICTURE",
				"PROPERTY_CML2_LINK"
			)
		)->Fetch();
		if($ar["DETAIL_PICTURE"] > 0) {
			$ar["DETAIL_PICTURE"] = CFile::ResizeImageGet($ar["DETAIL_PICTURE"], array("width" => 65, "height" => 65), BX_RESIZE_IMAGE_PROPORTIONAL, true);
			$arResult["ITEMS"]["ProdSubscribe"][$key]["DETAIL_PICTURE"] = $ar["DETAIL_PICTURE"];
		} else {
			if(!empty($ar["PROPERTY_CML2_LINK_VALUE"])) {
				$ar2 = CIBlockElement::GetList(
					array(), 
					array("=ID" => $ar["PROPERTY_CML2_LINK_VALUE"]), 
					false, 
					false, 
					array(
						"ID", 
						"IBLOCK_ID", 
						"NAME", 
						"DETAIL_PICTURE"
					)
				)->Fetch();
				if($ar2["DETAIL_PICTURE"] > 0) {
					$ar2["DETAIL_PICTURE"] = CFile::ResizeImageGet($ar2["DETAIL_PICTURE"], array("width" => 65, "height" => 65), BX_RESIZE_IMAGE_PROPORTIONAL, true);
					$arResult["ITEMS"]["ProdSubscribe"][$key]["DETAIL_PICTURE"] = $ar2["DETAIL_PICTURE"];
				}
			}
		}
	}
}

/***CLEAR_SUBSCRIBE_ITEMS***/
if(isset($_REQUEST["SubscribeClear"]) && $_REQUEST["SubscribeClear"] == "Y") {	
	if(is_array($arResult["ITEMS"]["ProdSubscribe"])) {
		foreach($arResult["ITEMS"]["ProdSubscribe"] as $key => $arItem) {
			CSaleBasket::Delete($arItem["ID"]);
		}
		
		LocalRedirect($APPLICATION->GetCurPage());
	}
}?>