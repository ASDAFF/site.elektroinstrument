<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
global $DB;
/** @global CUser $USER */
global $USER;
/** @global CMain $APPLICATION */
global $APPLICATION;

/*************************************************************************
	Processing of received parameters
*************************************************************************/
if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

unset($arParams["IBLOCK_TYPE"]); //was used only for IBLOCK_ID setup with Editor
$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);

if (empty($arParams["ELEMENT_SORT_FIELD"]))
	$arParams["ELEMENT_SORT_FIELD"] = "sort";
if (!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["ELEMENT_SORT_ORDER"]))
	$arParams["ELEMENT_SORT_ORDER"] = "asc";
if (empty($arParams["ELEMENT_SORT_FIELD2"]))
	$arParams["ELEMENT_SORT_FIELD2"] = "id";
if (!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["ELEMENT_SORT_ORDER2"]))
	$arParams["ELEMENT_SORT_ORDER2"] = "desc";

$arParams["SECTION_URL"]=trim($arParams["SECTION_URL"]);
$arParams["DETAIL_URL"]=trim($arParams["DETAIL_URL"]);
$arParams["BASKET_URL"]=trim($arParams["BASKET_URL"]);
if(strlen($arParams["BASKET_URL"])<=0)
	$arParams["BASKET_URL"] = "/personal/basket.php";

$arParams["ACTION_VARIABLE"]=trim($arParams["ACTION_VARIABLE"]);
if(strlen($arParams["ACTION_VARIABLE"])<=0|| !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["ACTION_VARIABLE"]))
	$arParams["ACTION_VARIABLE"] = "action";

$arParams["PRODUCT_ID_VARIABLE"]=trim($arParams["PRODUCT_ID_VARIABLE"]);
if(strlen($arParams["PRODUCT_ID_VARIABLE"])<=0|| !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["PRODUCT_ID_VARIABLE"]))
	$arParams["PRODUCT_ID_VARIABLE"] = "id";

$arParams["PRODUCT_QUANTITY_VARIABLE"]=trim($arParams["PRODUCT_QUANTITY_VARIABLE"]);
if(strlen($arParams["PRODUCT_QUANTITY_VARIABLE"])<=0|| !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["PRODUCT_QUANTITY_VARIABLE"]))
	$arParams["PRODUCT_QUANTITY_VARIABLE"] = "quantity";

$arParams["PRODUCT_PROPS_VARIABLE"]=trim($arParams["PRODUCT_PROPS_VARIABLE"]);
if(strlen($arParams["PRODUCT_PROPS_VARIABLE"])<=0|| !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["PRODUCT_PROPS_VARIABLE"]))
	$arParams["PRODUCT_PROPS_VARIABLE"] = "prop";

$arParams["SECTION_ID_VARIABLE"]=trim($arParams["SECTION_ID_VARIABLE"]);
if(strlen($arParams["SECTION_ID_VARIABLE"])<=0|| !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["SECTION_ID_VARIABLE"]))
	$arParams["SECTION_ID_VARIABLE"] = "SECTION_ID";

$arParams["SET_TITLE"] = $arParams["SET_TITLE"]!="N";
$arParams["DISPLAY_COMPARE"] = $arParams["DISPLAY_COMPARE"]=="Y";

$arParams["ELEMENT_COUNT"] = intval($arParams["ELEMENT_COUNT"]);
if($arParams["ELEMENT_COUNT"]<=0)
	$arParams["ELEMENT_COUNT"]=9;
$arParams["LINE_ELEMENT_COUNT"] = intval($arParams["LINE_ELEMENT_COUNT"]);
if($arParams["LINE_ELEMENT_COUNT"]<=0)
	$arParams["LINE_ELEMENT_COUNT"]=3;

if(!is_array($arParams["PROPERTY_CODE"]))
	$arParams["PROPERTY_CODE"] = array();
foreach($arParams["PROPERTY_CODE"] as $k=>$v)
	if($v==="")
		unset($arParams["PROPERTY_CODE"][$k]);
if(!is_array($arParams["PRICE_CODE"]))
	$arParams["PRICE_CODE"] = array();

if (empty($arParams['HIDE_NOT_AVAILABLE']))
	$arParams['HIDE_NOT_AVAILABLE'] = 'N';
elseif ('Y' != $arParams['HIDE_NOT_AVAILABLE'])
	$arParams['HIDE_NOT_AVAILABLE'] = 'N';

$arParams["USE_PRICE_COUNT"] = $arParams["USE_PRICE_COUNT"]=="Y";
$arParams["SHOW_PRICE_COUNT"] = intval($arParams["SHOW_PRICE_COUNT"]);
if($arParams["SHOW_PRICE_COUNT"]<=0)
	$arParams["SHOW_PRICE_COUNT"]=1;
$arParams["USE_PRODUCT_QUANTITY"] = $arParams["USE_PRODUCT_QUANTITY"]==="Y";

if(!is_array($arParams["PRODUCT_PROPERTIES"]))
	$arParams["PRODUCT_PROPERTIES"] = array();
foreach($arParams["PRODUCT_PROPERTIES"] as $k=>$v)
	if($v==="")
		unset($arParams["PRODUCT_PROPERTIES"][$k]);

if (!is_array($arParams["OFFERS_CART_PROPERTIES"]))
	$arParams["OFFERS_CART_PROPERTIES"] = array();
foreach($arParams["OFFERS_CART_PROPERTIES"] as $i => $pid)
	if ($pid === "")
		unset($arParams["OFFERS_CART_PROPERTIES"][$i]);

if (empty($arParams["OFFERS_SORT_FIELD"]))
	$arParams["OFFERS_SORT_FIELD"] = "sort";
if (!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["OFFERS_SORT_ORDER"]))
	$arParams["OFFERS_SORT_ORDER"] = "asc";
if (empty($arParams["OFFERS_SORT_FIELD2"]))
	$arParams["OFFERS_SORT_FIELD2"] = "id";
if (!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["OFFERS_SORT_ORDER2"]))
	$arParams["OFFERS_SORT_ORDER2"] = "desc";

$arParams["PRICE_VAT_INCLUDE"] = $arParams["PRICE_VAT_INCLUDE"] !== "N";

$arrFilter=array();
if(strlen($arParams["FILTER_NAME"])>0)
{
	global ${$arParams["FILTER_NAME"]};
	if (is_array(${$arParams["FILTER_NAME"]}))
		$arrFilter = ${$arParams["FILTER_NAME"]};
}

$arParams["CACHE_FILTER"]=$arParams["CACHE_FILTER"]=="Y";
if(!$arParams["CACHE_FILTER"] && count($arrFilter)>0)
	$arParams["CACHE_TIME"] = 0;

$arParams['CONVERT_CURRENCY'] = (isset($arParams['CONVERT_CURRENCY']) && 'Y' == $arParams['CONVERT_CURRENCY'] ? 'Y' : 'N');
$arParams['CURRENCY_ID'] = trim(strval($arParams['CURRENCY_ID']));
if ('' == $arParams['CURRENCY_ID'])
{
	$arParams['CONVERT_CURRENCY'] = 'N';
}
elseif ('N' == $arParams['CONVERT_CURRENCY'])
{
	$arParams['CURRENCY_ID'] = '';
}

$arParams["OFFERS_LIMIT"] = intval($arParams["OFFERS_LIMIT"]);
if (0 > $arParams["OFFERS_LIMIT"])
	$arParams["OFFERS_LIMIT"] = 0;

$arParams['CACHE_GROUPS'] = trim($arParams['CACHE_GROUPS']);
if ('N' != $arParams['CACHE_GROUPS'])
	$arParams['CACHE_GROUPS'] = 'Y';

/*************************************************************************
			Processing of the Buy link
*************************************************************************/
$strError = "";
if(array_key_exists($arParams["ACTION_VARIABLE"], $_REQUEST) && array_key_exists($arParams["PRODUCT_ID_VARIABLE"], $_REQUEST))
{
	if(array_key_exists($arParams["ACTION_VARIABLE"]."BUY", $_REQUEST))
		$action = "BUY";
	elseif(array_key_exists($arParams["ACTION_VARIABLE"]."ADD2BASKET", $_REQUEST))
		$action = "ADD2BASKET";
	else
		$action = strtoupper($_REQUEST[$arParams["ACTION_VARIABLE"]]);

	$productID = intval($_REQUEST[$arParams["PRODUCT_ID_VARIABLE"]]);
	if (($action == "ADD2BASKET" || $action == "BUY") && $productID > 0)
	{
		if (CModule::IncludeModule("sale") && CModule::IncludeModule("catalog"))
		{
			$QUANTITY = 0;
			$product_properties = array();
			$intProductIBlockID = intval(CIBlockElement::GetIBlockByID($productID));
			if (0 < $intProductIBlockID)
			{
				if ($intProductIBlockID == $arParams["IBLOCK_ID"])
				{
					if (!empty($arParams["PRODUCT_PROPERTIES"]))
					{
						if (
							array_key_exists($arParams["PRODUCT_PROPS_VARIABLE"], $_REQUEST)
							&& is_array($_REQUEST[$arParams["PRODUCT_PROPS_VARIABLE"]])
						)
						{
							$product_properties = CIBlockPriceTools::CheckProductProperties(
								$arParams["IBLOCK_ID"],
								$productID,
								$arParams["PRODUCT_PROPERTIES"],
								$_REQUEST[$arParams["PRODUCT_PROPS_VARIABLE"]]
							);
							if (!is_array($product_properties))
								$strError = GetMessage("CATALOG_ERROR2BASKET").".";
						}
						else
						{
							$strError = GetMessage("CATALOG_ERROR2BASKET").".";
						}
					}
				}
				else
				{
					if (!empty($arParams["OFFERS_CART_PROPERTIES"]))
					{
						$product_properties = CIBlockPriceTools::GetOfferProperties(
							$productID,
							$arParams["IBLOCK_ID"],
							$arParams["OFFERS_CART_PROPERTIES"]
						);
					}
				}
				if ($arParams["USE_PRODUCT_QUANTITY"])
				{
					if (isset($_REQUEST[$arParams["PRODUCT_QUANTITY_VARIABLE"]]))
					{
						$QUANTITY = doubleval($_REQUEST[$arParams["PRODUCT_QUANTITY_VARIABLE"]]);
					}
				}
				if (0 >= $QUANTITY)
				{
					$rsRatios = CCatalogMeasureRatio::getList(
						array(),
						array('PRODUCT_ID' => $productID),
						false,
						false,
						array('PRODUCT_ID', 'RATIO')
					);
					if ($arRatio = $rsRatios->Fetch())
					{
						$intRatio = intval($arRatio['RATIO']);
						$dblRatio = doubleval($arRatio['RATIO']);
						$QUANTITY = ($dblRatio > $intRatio ? $dblRatio : $intRatio);
					}
				}
				if (0 >= $QUANTITY)
					$QUANTITY = 1;
			}
			else
			{
				$strError = GetMessage('CATALOG_PRODUCT_NOT_FOUND').".";
			}

			if (isset($_REQUEST['ajax_basket']) && 'Y' == $_REQUEST['ajax_basket'])
			{
				if(!$strError && Add2BasketByProductID($productID, $QUANTITY, $product_properties))
				{
					$arAddResult = array(
						'STATUS' => 'OK',
						'MESSAGE' => ''
					);
				}
				else
				{
					$arAddResult = array(
						'STATUS' => 'ERROR',
						'MESSAGE' => $strError
					);
				}
				$APPLICATION->RestartBuffer();
				echo CUtil::PhpToJSObject($arAddResult);
				die();
			}
			else
			{
				if(!$strError && Add2BasketByProductID($productID, $QUANTITY, $product_properties))
				{
					if ($action == "BUY")
						LocalRedirect($arParams["BASKET_URL"]);
					else
						LocalRedirect($APPLICATION->GetCurPageParam("", array($arParams["PRODUCT_ID_VARIABLE"], $arParams["ACTION_VARIABLE"])));
				}
				else
				{
					if ($ex = $APPLICATION->GetException())
						$strError = $ex->GetString();
					else
						$strError = GetMessage("CATALOG_ERROR2BASKET").".";
				}
			}
		}
	}
}
if(strlen($strError)>0)
{
	ShowError($strError);
	return;
}

/*************************************************************************
			Work with cache
*************************************************************************/
if($this->StartResultCache(false, array($arrFilter, ($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()))))
{
	if (!CModule::IncludeModule("iblock"))
	{
		$this->AbortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}

	$arResultModules = array(
		'iblock' => true,
		'catalog' => false,
		'currency' => false
	);

	global $CACHE_MANAGER;
	$arConvertParams = array();
	if ('Y' == $arParams['CONVERT_CURRENCY'])
	{
		if (!CModule::IncludeModule('currency'))
		{
			$arParams['CONVERT_CURRENCY'] = 'N';
			$arParams['CURRENCY_ID'] = '';
		}
		else
		{
			$arResultModules['currency'] = true;
			$arCurrencyInfo = CCurrency::GetByID($arParams['CURRENCY_ID']);
			if (!(is_array($arCurrencyInfo) && !empty($arCurrencyInfo)))
			{
				$arParams['CONVERT_CURRENCY'] = 'N';
				$arParams['CURRENCY_ID'] = '';
			}
			else
			{
				$arParams['CURRENCY_ID'] = $arCurrencyInfo['CURRENCY'];
				$arConvertParams['CURRENCY_ID'] = $arCurrencyInfo['CURRENCY'];
			}
		}
	}
	$arResult['CONVERT_CURRENCY'] = $arConvertParams;

	$bIBlockCatalog = false;
	$arCatalog = false;
	$boolNeedCatalogCache = false;
	$bCatalog = CModule::IncludeModule('catalog');
	if ($bCatalog)
	{
		$arResultModules['catalog'] = true;
		$arCatalog = CCatalogSKU::GetInfoByIBlock($arParams["IBLOCK_ID"]);
		if (!empty($arCatalog) && is_array($arCatalog))
		{
			$bIBlockCatalog = $arCatalog['CATALOG_TYPE'] != CCatalogSKU::TYPE_PRODUCT;
			$boolNeedCatalogCache = true;
		}
	}
	$arResult['CATALOG'] = $arCatalog;
	//This function returns array with prices description and access rights
	//in case catalog module n/a prices get values from element properties
	$arResult["PRICES"] = CIBlockPriceTools::GetCatalogPrices($arParams["IBLOCK_ID"], $arParams["PRICE_CODE"]);
	$arResult['PRICES_ALLOW'] = CIBlockPriceTools::GetAllowCatalogPrices($arResult["PRICES"]);

	if ($bCatalog && $boolNeedCatalogCache && !empty($arResult['PRICES_ALLOW']))
	{
		$boolNeedCatalogCache = CIBlockPriceTools::SetCatalogDiscountCache($arResult['PRICES_ALLOW'], $USER->GetUserGroupArray());
	}

	/************************************
			Elements
	************************************/
	//SELECT
	$arSelect = array(
		"ID",
		"IBLOCK_ID",
		"CODE",
		"XML_ID",
		"NAME",
		"ACTIVE",
		"DATE_ACTIVE_FROM",
		"DATE_ACTIVE_TO",
		"SORT",
		"PREVIEW_TEXT",
		"PREVIEW_TEXT_TYPE",
		"DETAIL_TEXT",
		"DETAIL_TEXT_TYPE",
		"DATE_CREATE",
		"CREATED_BY",
		"TIMESTAMP_X",
		"MODIFIED_BY",
		"TAGS",
		"IBLOCK_SECTION_ID",
		"DETAIL_PAGE_URL",
		"DETAIL_PICTURE",
		"PREVIEW_PICTURE",
		"PROPERTY_*",
	);
	//WHERE
	if($arParams["IBLOCK_ID"] > 0)
		$arrFilter["IBLOCK_ID"] = $arParams["IBLOCK_ID"];
	$arrFilter["IBLOCK_LID"] = SITE_ID;
	$arrFilter["IBLOCK_ACTIVE"] = "Y";
	$arrFilter["ACTIVE_DATE"] = "Y";
	$arrFilter["ACTIVE"] = "Y";
	$arrFilter["SECTION_GLOBAL_ACTIVE"] = "Y";
	$arrFilter["CHECK_PERMISSIONS"] = "Y";
	if ($bIBlockCatalog && 'Y' == $arParams['HIDE_NOT_AVAILABLE'])
		$arrFilter['CATALOG_AVAILABLE'] = 'Y';

	//ORDER BY
	$arSort = array(
		$arParams["ELEMENT_SORT_FIELD"] => $arParams["ELEMENT_SORT_ORDER"],
		$arParams["ELEMENT_SORT_FIELD2"] => $arParams["ELEMENT_SORT_ORDER2"],
	);
	//PRICES
	$arPriceTypeID = array();
	if (!$arParams["USE_PRICE_COUNT"])
	{
		foreach($arResult["PRICES"] as &$value)
		{
			if (!$value['CAN_VIEW'] && !$value['CAN_BUY'])
				continue;
			$arSelect[] = $value["SELECT"];
			$arrFilter["CATALOG_SHOP_QUANTITY_".$value["ID"]] = $arParams["SHOW_PRICE_COUNT"];
		}
		if (isset($value))
			unset($value);
	}
	else
	{
		foreach($arResult["PRICES"] as &$value)
		{
			if (!$value['CAN_VIEW'] && !$value['CAN_BUY'])
				continue;
			$arPriceTypeID[] = $value["ID"];
		}
		if (isset($value))
			unset($value);
	}

	$arDefaultMeasure = array();
	if ($bIBlockCatalog)
		$arDefaultMeasure = CCatalogMeasure::getDefaultMeasure(true, true);
	$arCurrencyList = array();

	$bGetPropertyCodes = !empty($arParams["PROPERTY_CODE"]);
	$bGetProductProperties = !empty($arParams["PRODUCT_PROPERTIES"]);
	$bGetProperties = $bGetPropertyCodes || $bGetProductProperties;

	if($arParams['FLAG_PROPERTY_CODE']) {
		$arrFilter['!PROPERTY_'.$arParams['FLAG_PROPERTY_CODE']] = false;
	}

	$arResult["ITEMS"] = array();
	$arKeyMap = array();
	$arMeasureMap = array();
	$intKey = 0;
	$rsElements = CIBlockElement::GetList($arSort, $arrFilter, false, array("nTopCount" => $arParams["ELEMENT_COUNT"]), $arSelect);
	$rsElements->SetUrlTemplates($arParams["DETAIL_URL"]);

	while($obElement = $rsElements->GetNextElement())
	{
		$arItem = $obElement->GetFields();

		$arItem['ACTIVE_FROM'] = $arItem['DATE_ACTIVE_FROM'];
		$arItem['ACTIVE_TO'] = $arItem['DATE_ACTIVE_TO'];

		$arButtons = CIBlock::GetPanelButtons(
			$arItem["IBLOCK_ID"],
			$arItem["ID"],
			$arItem["IBLOCK_SECTION_ID"],
			array("SECTION_BUTTONS"=>false, "SESSID"=>false, "CATALOG"=>true)
		);
		$arItem["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
		$arItem["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];

		$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arItem["IBLOCK_ID"], $arItem["ID"]);
		$arItem["IPROPERTY_VALUES"] = $ipropValues->getValues();

		$arItem["PREVIEW_PICTURE"] = (0 < $arItem["PREVIEW_PICTURE"] ? CFile::GetFileArray($arItem["PREVIEW_PICTURE"]) : false);
		if ($arItem["PREVIEW_PICTURE"])
		{
			$arItem["PREVIEW_PICTURE"]["ALT"] = $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"];
			if ($arItem["PREVIEW_PICTURE"]["ALT"] == "")
				$arItem["PREVIEW_PICTURE"]["ALT"] = $arItem["NAME"];
			$arItem["PREVIEW_PICTURE"]["TITLE"] = $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"];
			if ($arItem["PREVIEW_PICTURE"]["TITLE"] == "")
				$arItem["PREVIEW_PICTURE"]["TITLE"] = $arItem["NAME"];
		}
		$arItem["DETAIL_PICTURE"] = (0 < $arItem["DETAIL_PICTURE"] ? CFile::GetFileArray($arItem["DETAIL_PICTURE"]) : false);
		if ($arItem["DETAIL_PICTURE"])
		{
			$arItem["DETAIL_PICTURE"]["ALT"] = $arItem["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"];
			if ($arItem["DETAIL_PICTURE"]["ALT"] == "")
				$arItem["DETAIL_PICTURE"]["ALT"] = $arItem["NAME"];
			$arItem["DETAIL_PICTURE"]["TITLE"] = $arItem["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"];
			if ($arItem["DETAIL_PICTURE"]["TITLE"] == "")
				$arItem["DETAIL_PICTURE"]["TITLE"] = $arItem["NAME"];
		}

		$arItem["PROPERTIES"] = array();
		if ($bGetProperties)
		{
			$arItem["PROPERTIES"] = $obElement->GetProperties();
			if ($bCatalog && $boolNeedCatalogCache)
			{
				CCatalogDiscount::SetProductPropertiesCache($arItem['ID'], $arItem["PROPERTIES"]);
			}
		}

		$arItem["DISPLAY_PROPERTIES"] = array();
		foreach($arParams["PROPERTY_CODE"] as $pid)
		{
			if (!isset($arItem["PROPERTIES"][$pid]))
				continue;
			$prop = &$arItem["PROPERTIES"][$pid];
			$boolArr = is_array($prop["VALUE"]);
			if(
				($boolArr && !empty($prop["VALUE"]))
				|| (!$boolArr && strlen($prop["VALUE"]) > 0)
			)
			{
				$arItem["DISPLAY_PROPERTIES"][$pid] = CIBlockFormatProperties::GetDisplayValue($arItem, $prop, "catalog_out");
			}
		}

		$arItem["PRODUCT_PROPERTIES"] = array();
		if ($bGetProductProperties)
		{
			$arItem["PRODUCT_PROPERTIES"] = CIBlockPriceTools::GetProductProperties(
				$arParams["IBLOCK_ID"],
				$arItem["ID"],
				$arParams["PRODUCT_PROPERTIES"],
				$arItem["PROPERTIES"]
			);
		}

		if ($bIBlockCatalog)
		{
			if (!isset($arItem["CATALOG_MEASURE_RATIO"]))
				$arItem["CATALOG_MEASURE_RATIO"] = 1;
			if (!isset($arItem['CATALOG_MEASURE']))
				$arItem['CATALOG_MEASURE'] = 0;
			$arItem['CATALOG_MEASURE'] = intval($arItem['CATALOG_MEASURE']);
			if (0 > $arItem['CATALOG_MEASURE'])
				$arItem['CATALOG_MEASURE'] = 0;
			if (!isset($arItem['CATALOG_MEASURE_NAME']))
				$arItem['CATALOG_MEASURE_NAME'] = '';

			$arItem['CATALOG_MEASURE_NAME'] = $arDefaultMeasure['SYMBOL_RUS'];
			$arItem['~CATALOG_MEASURE_NAME'] = $arDefaultMeasure['~SYMBOL_RUS'];
			if (0 < $arItem['CATALOG_MEASURE'])
			{
				if (!isset($arMeasureMap[$arItem['CATALOG_MEASURE']]))
					$arMeasureMap[$arItem['CATALOG_MEASURE']] = array();
				$arMeasureMap[$arItem['CATALOG_MEASURE']][] = $intKey;
			}
		}
		$arResult["ITEMS"][$intKey] = $arItem;
		$arResult["ELEMENTS"][$intKey] = $arItem["ID"];
		$arKeyMap[$arItem['ID']] = $intKey;
		$intKey++;
	}
	$arResult['MODULES'] = $arResultModules;

	if ($bIBlockCatalog)
	{
		if (!empty($arKeyMap))
		{
			$rsRatios = CCatalogMeasureRatio::getList(
				array(),
				array('@PRODUCT_ID' => array_keys($arKeyMap)),
				false,
				false,
				array('PRODUCT_ID', 'RATIO')
			);
			while ($arRatio = $rsRatios->Fetch())
			{
				if (isset($arKeyMap[$arRatio['PRODUCT_ID']]))
				{
					$intRatio = intval($arRatio['RATIO']);
					$dblRatio = doubleval($arRatio['RATIO']);
					$mxRatio = ($dblRatio > $intRatio ? $dblRatio : $intRatio);
					if (CATALOG_VALUE_EPSILON > abs($mxRatio))
						$mxRatio = 1;
					elseif (0 > $mxRatio)
						$mxRatio = 1;
					$arResult["ITEMS"][$arKeyMap[$arRatio['PRODUCT_ID']]]['CATALOG_MEASURE_RATIO'] = $mxRatio;
				}
			}
		}
		if (!empty($arMeasureMap))
		{
			$rsMeasures = CCatalogMeasure::getList(
				array(),
				array('@ID' => array_keys($arMeasureMap)),
				false,
				false,
				array()
			);
			while ($arMeasure = $rsMeasures->GetNext())
			{
				$arMeasure['ID'] = intval($arMeasure['ID']);
				if (isset($arMeasureMap[$arMeasure['ID']]) && !empty($arMeasureMap[$arMeasure['ID']]))
				{
					foreach ($arMeasureMap[$arMeasure['ID']] as &$intOneKey)
					{
						$arResult['ITEMS'][$intOneKey]['CATALOG_MEASURE_NAME'] = $arMeasure['SYMBOL_RUS'];
						$arResult['ITEMS'][$intOneKey]['~CATALOG_MEASURE_NAME'] = $arMeasure['~SYMBOL_RUS'];
					}
					unset($intOneKey);
				}
			}
		}
	}
	if ($bCatalog && $boolNeedCatalogCache && !empty($arResult["ELEMENTS"]))
	{
		CCatalogDiscount::SetProductSectionsCache($arResult["ELEMENTS"]);
	}
	if (isset($arItem))
		unset($arItem);
	foreach ($arResult["ITEMS"] as &$arItem)
	{
		$arItem["PRICES"] = array();
		$arItem["PRICE_MATRIX"] = false;
		$arItem['MIN_PRICE'] = false;
		if($arParams["USE_PRICE_COUNT"])
		{
			if ($bCatalog)
			{
				$arItem["PRICE_MATRIX"] = CatalogGetPriceTableEx($arItem["ID"], 0, $arPriceTypeID, 'Y', $arConvertParams);
				foreach($arItem["PRICE_MATRIX"]["COLS"] as $keyColumn=>$arColumn)
					$arItem["PRICE_MATRIX"]["COLS"][$keyColumn]["NAME_LANG"] = htmlspecialcharsbx($arColumn["NAME_LANG"]);
			}
		}
		else
		{
			$arItem["PRICES"] = CIBlockPriceTools::GetItemPrices($arParams["IBLOCK_ID"], $arResult["PRICES"], $arItem, $arParams['PRICE_VAT_INCLUDE'], $arConvertParams);
			if (!empty($arItem["PRICES"]))
			{
				foreach ($arItem['PRICES'] as &$arOnePrice)
				{
					if ('Y' == $arOnePrice['MIN_PRICE'])
					{
						$arItem['MIN_PRICE'] = $arOnePrice;
						break;
					}
				}
				unset($arOnePrice);
			}
		}
		$arItem["CAN_BUY"] = CIBlockPriceTools::CanBuy($arParams["IBLOCK_ID"], $arResult["PRICES"], $arItem);
		$arItem["~BUY_URL"] = $APPLICATION->GetCurPageParam($arParams["ACTION_VARIABLE"]."=BUY&".$arParams["PRODUCT_ID_VARIABLE"]."=".$arItem["ID"], array($arParams["PRODUCT_ID_VARIABLE"], $arParams["ACTION_VARIABLE"]));
		$arItem["BUY_URL"] = htmlspecialcharsbx($arItem["~BUY_URL"]);
		$arItem["~ADD_URL"] = $APPLICATION->GetCurPageParam($arParams["ACTION_VARIABLE"]."=ADD2BASKET&".$arParams["PRODUCT_ID_VARIABLE"]."=".$arItem["ID"], array($arParams["PRODUCT_ID_VARIABLE"], $arParams["ACTION_VARIABLE"]));
		$arItem["ADD_URL"] = htmlspecialcharsbx($arItem["~ADD_URL"]);
		$arItem["~COMPARE_URL"] = $APPLICATION->GetCurPageParam("action=ADD_TO_COMPARE_LIST&id=".$arItem["ID"], array($arParams["PRODUCT_ID_VARIABLE"], $arParams["ACTION_VARIABLE"]));
		$arItem["COMPARE_URL"] = htmlspecialcharsbx($arItem["~COMPARE_URL"]);

		if ('Y' == $arParams['CONVERT_CURRENCY'])
		{
			if ($arParams["USE_PRICE_COUNT"])
			{
				if (is_array($arItem["PRICE_MATRIX"]) && !empty($arItem["PRICE_MATRIX"]))
				{
					if (isset($arItem["PRICE_MATRIX"]['CURRENCY_LIST']) && is_array($arItem["PRICE_MATRIX"]['CURRENCY_LIST']))
						$arCurrencyList = array_merge($arCurrencyList, $arItem["PRICE_MATRIX"]['CURRENCY_LIST']);
				}
			}
			else
			{
				if (!empty($arItem["PRICES"]))
				{
					foreach ($arItem["PRICES"] as &$arOnePrices)
					{
						if (isset($arOnePrices['ORIG_CURRENCY']))
							$arCurrencyList[] = $arOnePrices['ORIG_CURRENCY'];
					}
					if (isset($arOnePrices))
						unset($arOnePrices);
				}
			}
		}
	}
	if (isset($arItem))
		unset($arItem);

	if(!isset($arParams["OFFERS_FIELD_CODE"]))
		$arParams["OFFERS_FIELD_CODE"] = array();
	elseif (!is_array($arParams["OFFERS_FIELD_CODE"]))
		$arParams["OFFERS_FIELD_CODE"] = array($arParams["OFFERS_FIELD_CODE"]);
	foreach($arParams["OFFERS_FIELD_CODE"] as $key => $value)
		if($value === "")
			unset($arParams["OFFERS_FIELD_CODE"][$key]);

	if(!isset($arParams["OFFERS_PROPERTY_CODE"]))
		$arParams["OFFERS_PROPERTY_CODE"] = array();
	elseif (!is_array($arParams["OFFERS_PROPERTY_CODE"]))
		$arParams["OFFERS_PROPERTY_CODE"] = array($arParams["OFFERS_PROPERTY_CODE"]);
	foreach($arParams["OFFERS_PROPERTY_CODE"] as $key => $value)
		if($value === "")
			unset($arParams["OFFERS_PROPERTY_CODE"][$key]);

	if(
		$bCatalog
		&& !empty($arResult["ELEMENTS"])
		&& (
			!empty($arParams["OFFERS_FIELD_CODE"])
			|| !empty($arParams["OFFERS_PROPERTY_CODE"])
		)
	)
	{
		$arOffers = CIBlockPriceTools::GetOffersArray(
			array(
				'IBLOCK_ID' => $arParams["IBLOCK_ID"],
				'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
			)
			,$arResult["ELEMENTS"]
			,array(
				$arParams["OFFERS_SORT_FIELD"] => $arParams["OFFERS_SORT_ORDER"],
				$arParams["OFFERS_SORT_FIELD2"] => $arParams["OFFERS_SORT_ORDER2"],
			)
			,$arParams["OFFERS_FIELD_CODE"]
			,$arParams["OFFERS_PROPERTY_CODE"]
			,$arParams["OFFERS_LIMIT"]
			,$arResult["PRICES"]
			,$arParams['PRICE_VAT_INCLUDE']
			,$arConvertParams
		);
		if(!empty($arOffers))
		{
			$arElementOffer = array();
			foreach($arResult["ELEMENTS"] as $i => $id)
			{
				$arResult["ITEMS"][$i]["OFFERS"] = array();
				$arElementOffer[$id] = &$arResult["ITEMS"][$i]["OFFERS"];
			}

			foreach($arOffers as $arOffer)
			{
				if(isset($arElementOffer[$arOffer["LINK_ELEMENT_ID"]]))
				{
					$arOffer["~BUY_URL"] = $APPLICATION->GetCurPageParam($arParams["ACTION_VARIABLE"]."=BUY&".$arParams["PRODUCT_ID_VARIABLE"]."=".$arOffer["ID"], array($arParams["PRODUCT_ID_VARIABLE"], $arParams["ACTION_VARIABLE"]));
					$arOffer["BUY_URL"] = htmlspecialcharsbx($arOffer["~BUY_URL"]);
					$arOffer["~ADD_URL"] = $APPLICATION->GetCurPageParam($arParams["ACTION_VARIABLE"]."=ADD2BASKET&".$arParams["PRODUCT_ID_VARIABLE"]."=".$arOffer["ID"], array($arParams["PRODUCT_ID_VARIABLE"], $arParams["ACTION_VARIABLE"]));
					$arOffer["ADD_URL"] = htmlspecialcharsbx($arOffer["~ADD_URL"]);
					$arOffer["~COMPARE_URL"] = $APPLICATION->GetCurPageParam("action=ADD_TO_COMPARE_LIST&id=".$arOffer["ID"], array($arParams["PRODUCT_ID_VARIABLE"], $arParams["ACTION_VARIABLE"]));
					$arOffer["COMPARE_URL"] = htmlspecialcharsbx($arOffer["~COMPARE_URL"]);

					$arElementOffer[$arOffer["LINK_ELEMENT_ID"]][] = $arOffer;

					if ('Y' == $arParams['CONVERT_CURRENCY'])
					{
						if (!empty($arOffer['PRICES']))
						{
							foreach ($arOffer['PRICES'] as &$arOnePrices)
							{
								if (isset($arOnePrices['ORIG_CURRENCY']))
									$arCurrencyList[] = $arOnePrices['ORIG_CURRENCY'];
							}
							if (isset($arOnePrices))
								unset($arOnePrices);
						}
					}
				}
			}
		}
	}

	if (
		'Y' == $arParams['CONVERT_CURRENCY']
		&& !empty($arCurrencyList)
		&& defined("BX_COMP_MANAGED_CACHE")
	)
	{
		$arCurrencyList[] = $arConvertParams['CURRENCY_ID'];
		$arCurrencyList = array_unique($arCurrencyList);
		$CACHE_MANAGER->StartTagCache($this->GetCachePath());
		foreach ($arCurrencyList as &$strOneCurrency)
		{
			$CACHE_MANAGER->RegisterTag("currency_id_".$strOneCurrency);
		}
		if (isset($strOneCurrency))
			unset($strOneCurrency);
		$CACHE_MANAGER->EndTagCache();
	}

	$this->SetResultCacheKeys(array(
	));
	$this->IncludeComponentTemplate();

	if ($bCatalog && $boolNeedCatalogCache)
	{
		CCatalogDiscount::ClearDiscountCache(array(
			'PRODUCT' => true,
			'SECTIONS' => true,
			'PROPERTIES' => true
		));
	}
}
?>