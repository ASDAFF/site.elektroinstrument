<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/***ELEMENT_PREVIEW_IMG***/
if($arResult["ELEMENT"]["DETAIL_PICTURE"]) {
	$arFileTmp = CFile::ResizeImageGet(
		$arResult["ELEMENT"]["DETAIL_PICTURE"],
		array("width" => 160, "height" => 160),
		BX_RESIZE_IMAGE_PROPORTIONAL,
		true
	);	

	$arResult["ELEMENT"]["PREVIEW_IMG"] = array(
		"SRC" => $arFileTmp["src"],
		"WIDTH" => $arFileTmp["width"],
		"HEIGHT" => $arFileTmp["height"],
	);
}

$arResult["SET_ITEMS"]["PRICE_VALUE"] = 0;
$arResult["SET_ITEMS"]["OLD_PRICE_VALUE"] = 0;

/***SET_ITEMS***/
$arDefaultSetIDs = array($arResult["ELEMENT"]["ID"]);
foreach(array("DEFAULT", "OTHER") as $type) {
	foreach($arResult["SET_ITEMS"][$type] as $key => $arItem) {
		if($type == "DEFAULT") {
			$arDefaultSetIDs[] = $arItem["ID"];
		}
		
		if($arItem["DETAIL_PICTURE"]) {
			$arFileTmp = CFile::ResizeImageGet(
				$arItem["DETAIL_PICTURE"],
				array("width" => 160, "height" => 160),
				BX_RESIZE_IMAGE_PROPORTIONAL,
				true
			);

			$arItem["PREVIEW_IMG"] = array(
				"SRC" => $arFileTmp["src"],
				"WIDTH" => $arFileTmp["width"],
				"HEIGHT" => $arFileTmp["height"],
			);			
		}

		$arResult["SET_ITEMS"][$type][$key] = $arItem;		
	}
}
$arResult["DEFAULT_SET_IDS"] = $arDefaultSetIDs;

foreach($arResult["SET_ITEMS"]["DEFAULT"] as $key => $arItem) {
	$arResult["SET_ITEMS"]["PRICE_VALUE"] += $arItem["PRICE_DISCOUNT_VALUE"];
	$arResult["SET_ITEMS"]["OLD_PRICE_VALUE"] += $arItem["PRICE_VALUE"];	
}

$arResult["SET_ITEMS"]["PRICE_VALUE"] = $arResult["ELEMENT"]["PRICE_DISCOUNT_VALUE"] + $arResult["SET_ITEMS"]["PRICE_VALUE"];
$arResult["SET_ITEMS"]["OLD_PRICE_VALUE"] = $arResult["ELEMENT"]["PRICE_VALUE"] + $arResult["SET_ITEMS"]["OLD_PRICE_VALUE"];
$arResult["SET_ITEMS"]["PRICE_CURRENCY"] = $arResult["ELEMENT"]["PRICE_CURRENCY"];
?>