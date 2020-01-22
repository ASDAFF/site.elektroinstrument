<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule("iblock"))
	return;

foreach($arResult["BASKET"] as $key => $arBasketItems):
	$ar = CIBlockElement::GetList(
		array(), 
		array("=ID" => $arBasketItems["PRODUCT_ID"]), 
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
		$ar["DETAIL_PICTURE"] = CFile::ResizeImageGet($ar["DETAIL_PICTURE"], array("width" => 30, "height" => 30), BX_RESIZE_IMAGE_PROPORTIONAL, true);
		$arResult["BASKET"][$key]["DETAIL_PICTURE"] = $ar["DETAIL_PICTURE"];
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
				$ar2["DETAIL_PICTURE"] = CFile::ResizeImageGet($ar2["DETAIL_PICTURE"], array("width" => 30, "height" => 30), BX_RESIZE_IMAGE_PROPORTIONAL, true);
				$arResult["BASKET"][$key]["DETAIL_PICTURE"] = $ar2["DETAIL_PICTURE"];
			}
		}
	}
endforeach;?>