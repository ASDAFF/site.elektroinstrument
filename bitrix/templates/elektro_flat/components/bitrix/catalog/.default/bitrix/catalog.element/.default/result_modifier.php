<?use Bitrix\Main\Type\Collection;
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if($arParams["USE_COMPARE"]) {
	$arResult["COMPARE_URL"] = htmlspecialchars($APPLICATION->GetCurPageParam("action=ADD_TO_COMPARE_LIST&id=".$arResult["ID"], array("action", "id")));
}


/***KIT_ITEMS***/
$arConvertParams = array();
if($arParams["CONVERT_CURRENCY"] == "Y") {
	if(!Loader::includeModule("currency")) {
		$arParams["CONVERT_CURRENCY"] = "N";
		$arParams["CURRENCY_ID"] = "";
	} else {
		$arCurrencyInfo = CCurrency::GetByID($arParams["CURRENCY_ID"]);
		if(!(is_array($arCurrencyInfo) && !empty($arCurrencyInfo))) {
			$arParams["CONVERT_CURRENCY"] = "N";
			$arParams["CURRENCY_ID"] = "";
		} else {
			$arParams["CURRENCY_ID"] = $arCurrencyInfo["CURRENCY"];
			$arConvertParams["CURRENCY_ID"] = $arCurrencyInfo["CURRENCY"];
		}
	}
}

$arKitItemsID = array();
$arKitItems = CCatalogProductSet::getAllSetsByProduct($arResult["ID"], CCatalogProductSet::TYPE_SET);
foreach($arKitItems as $arItems) {
	Collection::sortByColumn($arItems["ITEMS"], array("SORT" => SORT_ASC));
	foreach($arItems["ITEMS"] as $arItem) {
		$arKitItemsID[] = $arItem["ITEM_ID"];
	}
}

$arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL", "DETAIL_PICTURE");

$arr["PRICES"] = CIBlockPriceTools::GetCatalogPrices($arParams["IBLOCK_ID"], $arParams["PRICE_CODE"]);
foreach($arr["PRICES"] as $key => $value) {
	if(!$value["CAN_VIEW"] && !$value["CAN_BUY"])
		continue;
	$arSelect[] = $value["SELECT"];
}

$arResult["KIT_ITEMS"] = array();

if(count($arKitItemsID) > 0) {
	$dbElement = CIBlockElement::GetList(
		array(),
		array("ID" => $arKitItemsID, "ACTIVE" => "Y"),
		false,
		false,
		$arSelect
	);
	while($arItem = $dbElement->GetNext()) {
		if($arItem["IBLOCK_ID"] > 0) {
			$arPrices = CIBlockPriceTools::GetItemPrices($arItem["IBLOCK_ID"], $arr["PRICES"], $arItem, $arParams["PRICE_VAT_INCLUDE"], $arConvertParams);
			foreach($arPrices as $arPrice) {
				if($arPrice["MIN_PRICE"] == "Y") {
					$arItem["PRICE_VALUE"] = $arPrice["VALUE"];				
					$arItem["PRICE_DISCOUNT_VALUE"] = $arPrice["DISCOUNT_VALUE"];
					$arItem["PRICE_CURRENCY"] = $arPrice["CURRENCY"];				
				}
			}
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

		$arResult["KIT_ITEMS"][] = $arItem;
	}

	Collection::sortByColumn($arResult["KIT_ITEMS"], array('SORT' => SORT_ASC));
}


/***DETAIL_PICTURE***/
if(is_array($arResult["DETAIL_PICTURE"])) {
	$arFileTmp = CFile::ResizeImageGet(
		$arResult["DETAIL_PICTURE"],
		array("width" => $arParams["DISPLAY_DETAIL_IMG_WIDTH"], "height" => $arParams["DISPLAY_DETAIL_IMG_HEIGHT"]),
		BX_RESIZE_IMAGE_PROPORTIONAL,
		true
	);

	$arResult["DETAIL_IMG"] = array(
		"SRC" => $arFileTmp["src"],
		"WIDTH" => $arFileTmp["width"],
		"HEIGHT" => $arFileTmp["height"],
	);

	$arFileTmp_prev = CFile::ResizeImageGet(
		$arResult["DETAIL_PICTURE"],
		array("width" => $arParams["DISPLAY_IMG_WIDTH"], "height" => $arParams["DISPLAY_IMG_HEIGHT"]),
		BX_RESIZE_IMAGE_PROPORTIONAL,
		true
	);

	$arResult["PREVIEW_IMG"] = array(
		"SRC" => $arFileTmp_prev["src"],
		"WIDTH" => $arFileTmp_prev["width"],
		"HEIGHT" => $arFileTmp_prev["height"],
	);
}


/***MORE_PICTURES_ALL***/
if(is_array($arResult["MORE_PHOTO"]) && count($arResult["MORE_PHOTO"]) > 0) {
	unset($arResult["DISPLAY_PROPERTIES"]["MORE_PHOTO"]);

	/***WATERMARK***/
	$detail_picture = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "FIELDS");
	$detail_picture = $detail_picture["DETAIL_PICTURE"]["DEFAULT_VALUE"];

	$arWaterMark = Array();

	if($detail_picture["USE_WATERMARK_FILE"] == "Y"):
		$arWaterMark[] = Array(
			"name" => "watermark",
			"position" => $detail_picture["WATERMARK_FILE_POSITION"] ? $detail_picture["WATERMARK_FILE_POSITION"] : "center",
			"size" => "real",
			"type" => "image",
			"alpha_level" => $detail_picture["WATERMARK_FILE_ALPHA"] ? $detail_picture["WATERMARK_FILE_ALPHA"] : 100,
			"file" => $_SERVER["DOCUMENT_ROOT"].$detail_picture["WATERMARK_FILE"],
			"fill" => "exact"
		);
	endif;

	if($detail_picture["USE_WATERMARK_TEXT"] == "Y"):
		$arWaterMark[] = Array(
			"name" => "watermark",
			"position" => $detail_picture["WATERMARK_TEXT_POSITION"] ? $detail_picture["WATERMARK_TEXT_POSITION"] : "center",
			"size" => "medium",
			"coefficient" => $detail_picture["WATERMARK_TEXT_SIZE"] ? $detail_picture["WATERMARK_TEXT_SIZE"] : 100,
			"type" => "text",
			"text" => $detail_picture["WATERMARK_TEXT"] ? $detail_picture["WATERMARK_TEXT"] : SITE_SERVER_NAME,
			"color" => $detail_picture["WATERMARK_TEXT_COLOR"] ? $detail_picture["WATERMARK_TEXT_COLOR"] : "000000",
			"font" => $detail_picture["WATERMARK_TEXT_FONT"] ? $_SERVER["DOCUMENT_ROOT"].$detail_picture["WATERMARK_TEXT_FONT"] : $_SERVER["DOCUMENT_ROOT"]."/bitrix/fonts/pt_sans-bold.ttf"
		);
	endif;

	/***MORE_PICTURES***/
	foreach($arResult["MORE_PHOTO"] as $key => $arFile) {
		if(!empty($arWaterMark)):
			$arFileTmp = CFile::ResizeImageGet(
				$arFile,
				array("width" => 10000, "height" => 10000),
				BX_RESIZE_IMAGE_PROPORTIONAL,
				true,
				$arWaterMark
			);

			$arResult["MORE_PHOTO"][$key] = array(
				"SRC" => $arFileTmp["src"],
				"WIDTH" => $arFileTmp["width"],
				"HEIGHT" => $arFileTmp["height"],
			);
		endif;

		$arFileTmp_prev = CFile::ResizeImageGet(
			$arFile,
			array("width" => $arParams["DISPLAY_MORE_PHOTO_WIDTH"], "height" => $arParams["DISPLAY_MORE_PHOTO_HEIGHT"]),
			BX_RESIZE_IMAGE_PROPORTIONAL,
			true
		);

		$arResult["MORE_PHOTO"][$key]["PREVIEW"] = array(
			"SRC" => $arFileTmp_prev["src"],
			"WIDTH" => $arFileTmp_prev["width"],
			"HEIGHT" => $arFileTmp_prev["height"],
		);
	}
}


/***MANUFACTURER***/
if($arResult["PROPERTIES"]["MANUFACTURER"]["VALUE"]):
	$obElement = CIBlockElement::GetByID($arResult["PROPERTIES"]["MANUFACTURER"]["VALUE"]);
	if($arEl = $obElement->GetNext()):
		$arResult["PROPERTIES"]["MANUFACTURER"]["NAME"] = $arEl["NAME"];

		$rsFile = CFile::ResizeImageGet(
			$arEl["PREVIEW_PICTURE"],
			array("width" => 69, "height" => 24),
			BX_RESIZE_IMAGE_PROPORTIONAL,
			true
		);
		$arResult["PROPERTIES"]["MANUFACTURER"]["PREVIEW_IMG"] = array(
			"SRC" => $rsFile["src"],
			"WIDTH" => $rsFile["width"],
			"HEIGHT" => $rsFile["height"],
		);
	endif;
endif;


/***REVIEWS_COUNT***/
if(!empty($arParams["IBLOCK_ID_REVIEWS"])):
	$reviews_count = CIBlockElement::GetList(
		array(),
		array("IBLOCK_ID" => $arParams["IBLOCK_ID_REVIEWS"], "PROPERTY_OBJECT_ID"=> $arResult["ID"], "ACTIVE"=> "Y"),
		array()
	);
else:
	$reviews_count = CIBlockElement::GetList(
		array(),
		array("IBLOCK_CODE" => "comments_".SITE_ID, "PROPERTY_OBJECT_ID"=> $arResult["ID"], "ACTIVE"=> "Y"),
		array()
	);
endif;
$arResult["REVIEWS"]["COUNT"] = $reviews_count;


/***FUNCTION_SORT***/
function cmpBySort($array1, $array2) {
	if(!isset($array1["SORT"]) || !isset($array2["SORT"]))
		return -1;
	if($array1["SORT"] > $array2["SORT"])
		return 1;
	if($array1["SORT"] < $array2["SORT"])
		return -1;
	if($array1["SORT"] == $array2["SORT"])
		return 0;
}


/***PROPERTIES***/
if(!empty($arResult["DISPLAY_PROPERTIES"])) {
	foreach($arResult["DISPLAY_PROPERTIES"] as $propKey => $arDispProp) {
		if("F" == $arDispProp["PROPERTY_TYPE"]) {
			unset($arResult["DISPLAY_PROPERTIES"][$propKey]);
		}
	}
	uasort($arResult["DISPLAY_PROPERTIES"], "cmpBySort");
}


/***SELECT_PROPS***/
if(is_array($arParams["PROPERTY_CODE_MOD"]) && !empty($arParams["PROPERTY_CODE_MOD"])) {
	$arResult["SELECT_PROPS"] = array();
	foreach($arParams["PROPERTY_CODE_MOD"] as $pid) {
		if(!isset($arResult["PROPERTIES"][$pid]))
			continue;
		$prop = &$arResult["PROPERTIES"][$pid];
		$boolArr = is_array($prop["VALUE"]);
		if($prop["MULTIPLE"] == "Y" && $boolArr && !empty($prop["VALUE"])) {
			$arResult["SELECT_PROPS"][$pid] = CIBlockFormatProperties::GetDisplayValue($arResult, $prop, "catalog_out");
		} elseif($prop["MULTIPLE"] == "N" && !$boolArr) {
			if($prop["PROPERTY_TYPE"] == "L") {
				$arResult["SELECT_PROPS"][$pid] = $prop;
				$property_enums = CIBlockPropertyEnum::GetList(Array("SORT" => "ASC"), Array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "CODE" => $pid));
				while($enum_fields = $property_enums->GetNext()) {
					$arResult["SELECT_PROPS"][$pid]["DISPLAY_VALUE"][] = $enum_fields["VALUE"];
				}
			}
		}
	}
	uasort($arResult["SELECT_PROPS"], "cmpBySort");
}


/***OFFERS***/
if(is_array($arResult["OFFERS"]) && !empty($arResult["OFFERS"])) {
	foreach($arResult["OFFERS"] as $keyOffer => $arOffer) {
		/***DETAIL IMAGE***/
		if(isset($arOffer["DETAIL_PICTURE"])) {
			$arFile = CFile::GetFileArray($arOffer["DETAIL_PICTURE"]);
			$arResult["OFFERS"][$keyOffer]["DETAIL_PICTURE"] = $arFile;

			$arFileTmp = CFile::ResizeImageGet(
				$arOffer["DETAIL_PICTURE"],
				array("width" => $arParams["DISPLAY_DETAIL_IMG_WIDTH"], "height" => $arParams["DISPLAY_DETAIL_IMG_HEIGHT"]),
				BX_RESIZE_IMAGE_PROPORTIONAL,
				true
			);

			$arResult["OFFERS"][$keyOffer]["DETAIL_IMG"] = array(
				"SRC" => $arFileTmp["src"],
				"WIDTH" => $arFileTmp["width"],
				"HEIGHT" => $arFileTmp["height"],
			);

			$arFileTmp_prev = CFile::ResizeImageGet(
				$arOffer["DETAIL_PICTURE"],
				array("width" => $arParams["DISPLAY_IMG_WIDTH"], "height" => $arParams["DISPLAY_IMG_HEIGHT"]),
				BX_RESIZE_IMAGE_PROPORTIONAL,
				true
			);

			$arResult["OFFERS"][$keyOffer]["PREVIEW_IMG"] = array(
				"SRC" => $arFileTmp_prev["src"],
				"WIDTH" => $arFileTmp_prev["width"],
				"HEIGHT" => $arFileTmp_prev["height"],
			);
		}
		/***END_DETAIL_IMAGE***/
	}
}
/***END_OFFERS***/


/***PROPERTIES_JS_OFFERS***/
$arParams["OFFER_TREE_PROPS"] = $arParams["OFFERS_PROPERTY_CODE"];
if(!is_array($arParams["OFFER_TREE_PROPS"]))
	$arParams["OFFER_TREE_PROPS"] = array($arParams["OFFER_TREE_PROPS"]);
foreach($arParams["OFFER_TREE_PROPS"] as $key => $value) {
	$value = (string)$value;
	if("" == $value || "-" == $value)
		unset($arParams["OFFER_TREE_PROPS"][$key]);
}
if(empty($arParams["OFFER_TREE_PROPS"]) && isset($arParams["OFFERS_CART_PROPERTIES"]) && is_array($arParams["OFFERS_CART_PROPERTIES"])) {
	$arParams["OFFER_TREE_PROPS"] = $arParams["OFFERS_CART_PROPERTIES"];
	foreach($arParams["OFFER_TREE_PROPS"] as $key => $value) {
		$value = (string)$value;
		if("" == $value || "-" == $value)
			unset($arParams["OFFER_TREE_PROPS"][$key]);
	}
}

$arSKUPropList = array();
$arSKUPropIDs = array();
$arSKUPropKeys = array();
$boolSKU = false;

if($arResult["MODULES"]["catalog"]) {
	$arSKU = CCatalogSKU::GetInfoByProductIBlock($arParams["IBLOCK_ID"]);
	$boolSKU = !empty($arSKU) && is_array($arSKU);

	if($boolSKU && !empty($arParams["OFFER_TREE_PROPS"])) {
		$arSKUPropList = CIBlockPriceTools::getTreeProperties(
			$arSKU,
			$arParams["OFFER_TREE_PROPS"],
			array()
		);
		$arSKUPropIDs = array_keys($arSKUPropList);
	}
}

if($arResult["MODULES"]["catalog"]) {
	$arResult["CATALOG"] = true;
	if(!isset($arResult["CATALOG_TYPE"]))
		$arResult["CATALOG_TYPE"] = CCatalogProduct::TYPE_PRODUCT;
	if((CCatalogProduct::TYPE_PRODUCT == $arResult["CATALOG_TYPE"] || CCatalogProduct::TYPE_SKU == $arResult["CATALOG_TYPE"]) && !empty($arResult["OFFERS"])) {
		$arResult["CATALOG_TYPE"] = CCatalogProduct::TYPE_SKU;
	}
	switch($arResult["CATALOG_TYPE"]) {
		case CCatalogProduct::TYPE_SET:
			$arResult["OFFERS"] = array();
			break;
		case CCatalogProduct::TYPE_SKU:
			break;
		case CCatalogProduct::TYPE_PRODUCT:
		default:
			break;
	}
} else {
	$arResult["CATALOG_TYPE"] = 0;
	$arResult["OFFERS"] = array();
}

if($arResult["CATALOG"] && isset($arResult["OFFERS"]) && !empty($arResult["OFFERS"])) {
	$arNeedValues = array();
	
	CIBlockPriceTools::getTreePropertyValues($arSKUPropList, $arNeedValues);
	$arSKUPropIDs = array_keys($arSKUPropList);
	$arSKUPropKeys = array_fill_keys($arSKUPropIDs, false);

	$arMatrixFields = $arSKUPropKeys;
	$arMatrix = array();
	$arNewOffers = array();
	$arResult["OFFERS_PROP"] = false;
	$arDouble = array();
	
	foreach($arResult["OFFERS"] as $keyOffer => $arOffer) {
		$arOffer["ID"] = (int)$arOffer["ID"];
		if(isset($arDouble[$arOffer["ID"]]))
			continue;
		$arRow = array();
		foreach($arSKUPropIDs as $propkey => $strOneCode) {
			$arCell = array(
				"VALUE" => 0,
				"SORT" => PHP_INT_MAX,
				"NA" => true
			);
			if(isset($arOffer["DISPLAY_PROPERTIES"][$strOneCode])) {
				$arMatrixFields[$strOneCode] = true;
				$arCell["NA"] = false;
				if("directory" == $arSKUPropList[$strOneCode]["USER_TYPE"]) {
					$intValue = $arSKUPropList[$strOneCode]["XML_MAP"][$arOffer["DISPLAY_PROPERTIES"][$strOneCode]["VALUE"]];
					$arCell["VALUE"] = $intValue;
				} elseif ("L" == $arSKUPropList[$strOneCode]["PROPERTY_TYPE"]) {
					$arCell["VALUE"] = (int)$arOffer["DISPLAY_PROPERTIES"][$strOneCode]["VALUE_ENUM_ID"];
				} elseif ("E" == $arSKUPropList[$strOneCode]["PROPERTY_TYPE"]) {
					$arCell["VALUE"] = (int)$arOffer["DISPLAY_PROPERTIES"][$strOneCode]["VALUE"];
				}
				$arCell["SORT"] = $arSKUPropList[$strOneCode]["VALUES"][$arCell["VALUE"]]["SORT"];
			}
			$arRow[$strOneCode] = $arCell;
		}
		$arMatrix[$keyOffer] = $arRow;

		$arDouble[$arOffer["ID"]] = true;
		$arNewOffers[$keyOffer] = $arOffer;
	}
	$arResult["OFFERS"] = $arNewOffers;
	
	$arUsedFields = array();
	$arSortFields = array();
	
	foreach($arSKUPropIDs as $propkey => $strOneCode) {
		$boolExist = $arMatrixFields[$strOneCode];
		foreach($arMatrix as $keyOffer => $arRow) {
			if($boolExist) {
				if(!isset($arResult["OFFERS"][$keyOffer]["TREE"]))
					$arResult["OFFERS"][$keyOffer]["TREE"] = array();
				$arResult["OFFERS"][$keyOffer]["TREE"]["PROP_".$arSKUPropList[$strOneCode]["ID"]] = $arMatrix[$keyOffer][$strOneCode]["VALUE"];
				$arResult["OFFERS"][$keyOffer]["SKU_SORT_".$strOneCode] = $arMatrix[$keyOffer][$strOneCode]["SORT"];
				$arUsedFields[$strOneCode] = true;
				$arSortFields["SKU_SORT_".$strOneCode] = SORT_NUMERIC;
			} else {
				unset($arMatrix[$keyOffer][$strOneCode]);
			}
		}
	}
	$arResult["OFFERS_PROP"] = $arUsedFields;
	
	Collection::sortByColumn($arResult["OFFERS"], $arSortFields);

	$arMatrix = array();
	$intSelected = -1;
	$arResult["MIN_PRICE"] = false;
	foreach($arResult["OFFERS"] as $keyOffer => $arOffer) {
		if($arResult["MIN_PRICE"] === false || $arResult["MIN_PRICE"] > $arOffer["MIN_PRICE"]["VALUE"]) {
			$intSelected = $keyOffer;
			$arResult["MIN_PRICE"] = $arOffer["MIN_PRICE"]["VALUE"];
		}
		$arOneRow = array(
			"ID" => $arOffer["ID"],
			"NAME" => $arOffer["~NAME"],
			"TREE" => $arOffer["TREE"],
		);
		$arMatrix[$keyOffer] = $arOneRow;
	}
	if(-1 == $intSelected)
		$intSelected = 0;
	$arResult["JS_OFFERS"] = $arMatrix;
	$arResult["OFFERS_SELECTED"] = $intSelected;
}

foreach($arSKUPropList as $key => $arSKUProp) {
	if($arSKUProp["SHOW_MODE"] == "PICT") {
		foreach($arSKUProp["VALUES"] as $key2 => $arSKU) {
			$arSelect = Array("ID", "NAME", "PROPERTY_HEX", "PROPERTY_PICT");
			$arFilter = Array("IBLOCK_ID" => $arSKUProp["LINK_IBLOCK_ID"], "ID" => $arSKU["ID"]);
			$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
			if($ob = $res->GetNextElement()) {
				$arFields = $ob->GetFields();
				$arSKUPropList[$key]["VALUES"][$key2]["HEX"] = !empty($arFields["PROPERTY_HEX_VALUE"]) ? $arFields["PROPERTY_HEX_VALUE"] : array();
				$arSKUPropList[$key]["VALUES"][$key2]["PICT"] = !empty($arFields["PROPERTY_PICT_VALUE"]) ? CFile::ResizeImageGet($arFields["PROPERTY_PICT_VALUE"], array("width" => 24, "height" => 24), BX_RESIZE_IMAGE_PROPORTIONAL, true) : array();
			}
		}
	}
}

$arResult["SKU_PROPS"] = $arSKUPropList;


/***CACHE_KEYS***/
$this->__component->SetResultCacheKeys(
	array(
		"NAME",
		"PREVIEW_TEXT",
		"DETAIL_PICTURE",
		"MORE_PHOTO",
		"OFFERS"
	)
);?>