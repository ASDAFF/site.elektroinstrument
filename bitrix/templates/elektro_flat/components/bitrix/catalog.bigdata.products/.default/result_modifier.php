<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Type\Collection;

/***FUNCTION_SORT***/
function cmpBySortBigdata($array1, $array2) {
	if(!isset($array1["SORT"]) || !isset($array2["SORT"]))
		return -1;
	if($array1["SORT"] > $array2["SORT"])
		return 1;
	if($array1["SORT"] < $array2["SORT"])
		return -1;
	if($array1["SORT"] == $array2["SORT"])
		return 0;
}

if(!empty($arResult["ITEMS"])):
	/***ELEMENTS***/
	foreach($arResult["ITEMS"] as $key => $arElement):
		/***PREVIEW_TEXT***/
		if(!isset($arElement["PREVIEW_TEXT"])) {
			$obElement = CIBlockElement::GetByID($arElement["ID"]);
			if($arEl = $obElement->GetNext()):
				$arResult["ITEMS"][$key]["PREVIEW_TEXT"] = $arEl["PREVIEW_TEXT"];
			endif;
		}
		/***PREVIEW_IMG***/
		if(is_array($arElement["DETAIL_PICTURE"])) {
			$arFileTmp = CFile::ResizeImageGet(
				$arElement["DETAIL_PICTURE"],
				array("width" => $arParams["DISPLAY_IMG_WIDTH"], "height" => $arParams["DISPLAY_IMG_HEIGHT"]),
				BX_RESIZE_IMAGE_PROPORTIONAL,
				true
			);

			$arResult["ITEMS"][$key]["PREVIEW_IMG"] = array(
				"SRC" => $arFileTmp["src"],
				'WIDTH' => $arFileTmp["width"],
				'HEIGHT' => $arFileTmp["height"],
			);
		}
		
		/***MANUFACTURER***/
		if($arElement["PROPERTIES"]["MANUFACTURER"]["VALUE"]):
			$obElement = CIBlockElement::GetByID($arElement["PROPERTIES"]["MANUFACTURER"]["VALUE"]);
			if($arEl = $obElement->GetNext()):
				$arResult["ITEMS"][$key]["PROPERTIES"]["MANUFACTURER"]["NAME"] = $arEl["NAME"];

				$rsFile = CFile::ResizeImageGet(
					$arEl["PREVIEW_PICTURE"],
					array("width" => 69, "height" => 24),
					BX_RESIZE_IMAGE_PROPORTIONAL,
					true
				);
				$arResult["ITEMS"][$key]["PROPERTIES"]["MANUFACTURER"]["PREVIEW_IMG"] = array(
					"SRC" => $rsFile["src"],
					'WIDTH' => $rsFile["width"],
					'HEIGHT' => $rsFile["height"],
				);
			endif;
		endif;

		/***SELECT_PROPS***/
		if(is_array($arParams["PROPERTY_CODE_MOD"]) && !empty($arParams["PROPERTY_CODE_MOD"])) {
			$arResult["ITEMS"][$key]["SELECT_PROPS"] = array();
			foreach($arParams["PROPERTY_CODE_MOD"] as $pid) {
				if(!isset($arElement["PROPERTIES"][$pid]))
					continue;
				$prop = &$arElement["PROPERTIES"][$pid];
				$boolArr = is_array($prop["VALUE"]);
				if($prop["MULTIPLE"] == "Y" && $boolArr && !empty($prop["VALUE"])) {
					$arResult["ITEMS"][$key]["SELECT_PROPS"][$pid] = CIBlockFormatProperties::GetDisplayValue($arElement, $prop, "catalog_out");
				} elseif($prop["MULTIPLE"] == "N" && !$boolArr) {
					if($prop["PROPERTY_TYPE"] == "L") {
						$arResult["ITEMS"][$key]["SELECT_PROPS"][$pid] = $prop;
						$property_enums = CIBlockPropertyEnum::GetList(Array("SORT" => "ASC"), Array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "CODE" => $pid));
						while($enum_fields = $property_enums->GetNext()) {
							$arResult["ITEMS"][$key]["SELECT_PROPS"][$pid]["DISPLAY_VALUE"][] = $enum_fields["VALUE"];
						}
					}
				}
			}
			uasort($arResult["ITEMS"][$key]["SELECT_PROPS"], "cmpBySortBigdata");
		}

		/***OFFERS***/
		if(isset($arElement['OFFERS']) && !empty($arElement['OFFERS'])):
			/***MIN_PRICE***/
			$minId = false;
			$minPrice = false;
			$minProperties = false;
			$minDiscount = false;
			$minDiscountDiff = false;
			$minDiscountDiffPercent = false;
			$minCurr = false;
			$minMeasureRatio = false;
			$minMeasure = false;
			$minCanByu = false;
			$minQnt = false;
			foreach($arElement['OFFERS'] as $key_off => $arOffer):
				if($minPrice === false || $minPrice > $arOffer['MIN_PRICE']['VALUE']) {
					$minId = $arOffer["ID"];
					$minPrice = $arOffer['MIN_PRICE']['VALUE'];
					$minProperties = $arOffer["DISPLAY_PROPERTIES"];
					$minDiscount = $arOffer['MIN_PRICE']['DISCOUNT_VALUE'];
					$minDiscountDiff = $arOffer['MIN_PRICE']['PRINT_DISCOUNT_DIFF'];
					$minDiscountDiffPercent = $arOffer['MIN_PRICE']['DISCOUNT_DIFF_PERCENT'];
					$minCurr = $arOffer['MIN_PRICE']['CURRENCY'];
					$minMeasureRatio = $arOffer['CATALOG_MEASURE_RATIO'];
					$minMeasure = $arOffer['CATALOG_MEASURE_NAME'];
					if($arOffer['CATALOG_QUANTITY'] > 0) {
						$minCanByu = "Y";
						$minQnt = $arOffer['CATALOG_QUANTITY'];
					} else {
						$minCanByu = false;
						$minQnt = false;
					}
				}
			endforeach;
			$prices = array(
				"ID" => $minId,
				"VALUE" => $minPrice,
				"DISPLAY_PROPERTIES" => $minProperties,
				"DISCOUNT_VALUE" => $minDiscount,
				"PRINT_DISCOUNT_DIFF" => $minDiscountDiff,
				"DISCOUNT_DIFF_PERCENT" => $minDiscountDiffPercent,
				"CURRENCY" => $minCurr,
				"CATALOG_MEASURE_RATIO" => $minMeasureRatio,
				"CATALOG_MEASURE_NAME" => $minMeasure,
				"CAN_BUY" => $minCanByu,
				"CATALOG_QUANTITY" => $minQnt
			);
			$arResult["ITEMS"][$key]["OFFERS_MIN_PRICE"] = $prices;
			
			/***PREVIEW_IMG***/
			foreach($arElement['OFFERS'] as $key_off => $arOffer):	
				if(isset($arOffer["DETAIL_PICTURE"])) {
					$arFileTmp = CFile::ResizeImageGet(
						$arOffer["DETAIL_PICTURE"],
						array("width" => $arParams["DISPLAY_IMG_WIDTH"], "height" => $arParams["DISPLAY_IMG_HEIGHT"]),
						BX_RESIZE_IMAGE_PROPORTIONAL,
						true
					);

					$arResult["ITEMS"][$key]["OFFERS"][$key_off]["PREVIEW_IMG"] = array(
						"SRC" => $arFileTmp["src"],
						'WIDTH' => $arFileTmp["width"],
						'HEIGHT' => $arFileTmp["height"],
					);
				}
			endforeach;
			/***END_PREVIEW_IMG***/
		endif;
		/***END_OFFERS***/
	endforeach;
	/***END_ELEMENTS***/

	/***PROPERTIES_JS_OFFERS***/
	$arSKUPropList = array();
	$arSKUPropIDs = array();
	$arSKUPropKeys = array();
	$boolSKU = false;

	$skuPropList = array();
	$skuPropIds = array();
	$skuPropKeys = array();
		
	$catalogs = array();
	foreach($arResult['CATALOGS'] as $catalog) {
		$offersCatalogId = (int)$catalog['OFFERS_IBLOCK_ID'];
		$offersPropId = (int)$catalog['OFFERS_PROPERTY_ID'];
		$catalogId = (int)$catalog['IBLOCK_ID'];
		$sku = false;
		if($offersCatalogId > 0 && $offersPropId > 0)
			$sku = array("IBLOCK_ID" => $offersCatalogId, "SKU_PROPERTY_ID" => $offersPropId, "PRODUCT_IBLOCK_ID" => $catalogId);

		if(!empty($sku) && is_array($sku)) {
			$skuPropList[$catalogId] = CIBlockPriceTools::getTreeProperties(
				$sku,
				$arParams['OFFER_TREE_PROPS'][$offersCatalogId],
				array()
			);
			$needValues = array();
			CIBlockPriceTools::getTreePropertyValues($skuPropList[$catalogId], $needValues);
			$skuPropIds[$catalogId] = array_keys($skuPropList[$catalogId]);
			if(!empty($skuPropIds[$catalogId]))
				$skuPropKeys[$catalogId] = array_fill_keys($skuPropIds[$catalogId], false);
		}
	}

	$arNewItemsList = array();
	foreach($arResult['ITEMS'] as $key => $arItem) {
		$arItem['CATALOG'] = true;
		if(!isset($arItem['CATALOG_TYPE']))
			$arItem['CATALOG_TYPE'] = CCatalogProduct::TYPE_PRODUCT;
		if((CCatalogProduct::TYPE_PRODUCT == $arItem['CATALOG_TYPE'] || CCatalogProduct::TYPE_SKU == $arItem['CATALOG_TYPE']) && !empty($arItem['OFFERS'])) {
			$arItem['CATALOG_TYPE'] = CCatalogProduct::TYPE_SKU;
		}
		switch($arItem['CATALOG_TYPE']) {
			case CCatalogProduct::TYPE_SET:
				$arItem['OFFERS'] = array();
				break;
			case CCatalogProduct::TYPE_SKU:
				break;
			case CCatalogProduct::TYPE_PRODUCT:
			default:
				break;
		}
		
		if($arItem['CATALOG'] && isset($arItem['OFFERS']) && !empty($arItem['OFFERS'])) {
			$arSKUPropIDs = isset($skuPropIds[$arItem['IBLOCK_ID']]) ? $skuPropIds[$arItem['IBLOCK_ID']] : array();
			$arSKUPropList = isset($skuPropList[$arItem['IBLOCK_ID']]) ? $skuPropList[$arItem['IBLOCK_ID']] : array();
			$arSKUPropKeys = isset($skuPropKeys[$arItem['IBLOCK_ID']]) ? $skuPropKeys[$arItem['IBLOCK_ID']] : array();
			
			$arMatrixFields = $arSKUPropKeys;
			$arMatrix = array();

			$arNewOffers = array();
			$arItem['OFFERS_PROP'] = false;

			$arDouble = array();
			foreach($arItem['OFFERS'] as $keyOffer => $arOffer) {
				$arOffer['ID'] = intval($arOffer['ID']);
				if(isset($arDouble[$arOffer['ID']]))
					continue;
				$arRow = array();
				foreach($arSKUPropIDs as $propkey => $strOneCode) {
					$arCell = array(
						'VALUE' => 0,
						'SORT' => PHP_INT_MAX,
						'NA' => true
					);
					if(isset($arOffer['DISPLAY_PROPERTIES'][$strOneCode])) {
						$arMatrixFields[$strOneCode] = true;
						$arCell['NA'] = false;
						if('directory' == $arSKUPropList[$strOneCode]['USER_TYPE']) {
							$intValue = $arSKUPropList[$strOneCode]['XML_MAP'][$arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE']];
							$arCell['VALUE'] = $intValue;
						} elseif('L' == $arSKUPropList[$strOneCode]['PROPERTY_TYPE']) {
							$arCell['VALUE'] = intval($arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE_ENUM_ID']);
						} elseif('E' == $arSKUPropList[$strOneCode]['PROPERTY_TYPE']) {
							$arCell['VALUE'] = intval($arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE']);
						}
						$arCell['SORT'] = $arSKUPropList[$strOneCode]['VALUES'][$arCell['VALUE']]['SORT'];
					}
					$arRow[$strOneCode] = $arCell;
				}
				$arMatrix[$keyOffer] = $arRow;

				$arDouble[$arOffer['ID']] = true;
				$arNewOffers[$keyOffer] = $arOffer;
			}
			$arItem['OFFERS'] = $arNewOffers;
			
			$arUsedFields = array();
			$arSortFields = array();

			foreach($arSKUPropIDs as $propkey => $strOneCode) {
				$boolExist = $arMatrixFields[$strOneCode];
				foreach($arMatrix as $keyOffer => $arRow) {
					if($boolExist) {
						if(!isset($arItem['OFFERS'][$keyOffer]['TREE']))
							$arItem['OFFERS'][$keyOffer]['TREE'] = array();
						$arItem['OFFERS'][$keyOffer]['TREE']['PROP_'.$arSKUPropList[$strOneCode]['ID']] = $arMatrix[$keyOffer][$strOneCode]['VALUE'];
						$arItem['OFFERS'][$keyOffer]['SKU_SORT_'.$strOneCode] = $arMatrix[$keyOffer][$strOneCode]['SORT'];
						$arUsedFields[$strOneCode] = true;
						$arSortFields['SKU_SORT_'.$strOneCode] = SORT_NUMERIC;
					} else {
						unset($arMatrix[$keyOffer][$strOneCode]);
					}
				}
			}
			$arItem['OFFERS_PROP'] = $arUsedFields;
							
			Collection::sortByColumn($arItem['OFFERS'], $arSortFields);

			$arMatrix = array();
			$intSelected = -1;
			$arItem['MIN_PRICE'] = false;
			foreach($arItem['OFFERS'] as $keyOffer => $arOffer) {
				if($arItem['MIN_PRICE'] === false || $arItem['MIN_PRICE'] > $arOffer['MIN_PRICE']['VALUE']) {
					$intSelected = $keyOffer;
					$arItem['MIN_PRICE'] = $arOffer['MIN_PRICE']['VALUE'];
				}
				$arOneRow = array(
					'ID' => $arOffer['ID'],
					'NAME' => $arOffer['~NAME'],
					'TREE' => $arOffer['TREE']
				);
				$arMatrix[$keyOffer] = $arOneRow;
			}
			if(-1 == $intSelected)
				$intSelected = 0;
			$arItem['JS_OFFERS'] = $arMatrix;
			$arItem['OFFERS_SELECTED'] = $intSelected;
		}

		$arNewItemsList[$key] = $arItem;
	}
	$arResult['ITEMS'] = $arNewItemsList;
	
	foreach($skuPropList as $iblockId => $skuProp) {
		foreach($skuProp as $key => $arSKUProp) {
			if($arSKUProp["SHOW_MODE"] == "PICT") {
				foreach($arSKUProp["VALUES"] as $key2 => $arSKU) {
					$arSelect = Array("ID", "NAME", "PROPERTY_HEX", "PROPERTY_PICT");
					$arFilter = Array("IBLOCK_ID" => $arSKUProp["LINK_IBLOCK_ID"], "ID" => $arSKU["ID"]);
					$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
					if($ob = $res->GetNextElement()) {
						$arFields = $ob->GetFields();
						$skuPropList[$iblockId][$key]["VALUES"][$key2]["HEX"] = !empty($arFields["PROPERTY_HEX_VALUE"]) ? $arFields["PROPERTY_HEX_VALUE"] : array();
						$skuPropList[$iblockId][$key]["VALUES"][$key2]["PICT"] = !empty($arFields["PROPERTY_PICT_VALUE"]) ? CFile::ResizeImageGet($arFields["PROPERTY_PICT_VALUE"], array("width" => 24, "height" => 24), BX_RESIZE_IMAGE_PROPORTIONAL, true) : array();
					}
				}
			}
		}
	}	
	$arResult['SKU_PROPS'] = $skuPropList;
endif;?>