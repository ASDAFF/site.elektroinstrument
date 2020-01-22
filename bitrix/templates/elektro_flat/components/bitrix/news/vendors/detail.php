<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?$APPLICATION->IncludeComponent("bitrix:news.detail", "",
	Array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"FIELD_CODE" => $arParams["DETAIL_FIELD_CODE"],
		"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
		"META_KEYWORDS" => $arParams["META_KEYWORDS"],
		"META_DESCRIPTION" => $arParams["META_DESCRIPTION"],
		"BROWSER_TITLE" => $arParams["BROWSER_TITLE"],
		"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
		"SET_TITLE" => $arParams["SET_TITLE"],
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
		"ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
		"ACTIVE_DATE_FORMAT" => $arParams["DETAIL_ACTIVE_DATE_FORMAT"],
		"CACHE_TYPE" => "N",
		"CACHE_TYPE_CATALOG" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
		"GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
		"DISPLAY_TOP_PAGER" => $arParams["DETAIL_DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER" => $arParams["DETAIL_DISPLAY_BOTTOM_PAGER"],
		"PAGER_TITLE" => $arParams["DETAIL_PAGER_TITLE"],
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => $arParams["DETAIL_PAGER_TEMPLATE"],
		"PAGER_SHOW_ALL" => $arParams["DETAIL_PAGER_SHOW_ALL"],
		"CHECK_DATES" => $arParams["CHECK_DATES"],

		"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
		"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
		"IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],

		"IBLOCK_TYPE_CATALOG" => $arParams["IBLOCK_TYPE_CATALOG"],
		"IBLOCK_ID_CATALOG" => $arParams["IBLOCK_ID_CATALOG"],
		"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
		"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
		"SECTION_URL" => $arParams["SECTION_URL"],
		"DETAIL_URL" => $arParams["DETAIL_URL"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
		"PROPERTY_CODE" => $arParams["PROPERTY_CODE"],
		"PROPERTY_CODE_MOD" => $arParams["PROPERTY_CODE_MOD"],
		"OFFERS_FIELD_CODE" => $arParams["OFFERS_FIELD_CODE"],
		"OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
		"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
		"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
		"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
		"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
		"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
		"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
		"CURRENCY_ID" => $arParams["CURRENCY_ID"],
		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"DISPLAY_IMG_WIDTH"	 =>	$arParams["DISPLAY_IMG_WIDTH"],
		"DISPLAY_IMG_HEIGHT" =>	$arParams["DISPLAY_IMG_HEIGHT"],
		"USE_BIG_DATA" => $arParams["USE_BIG_DATA"],
		"BIG_DATA_RCM_TYPE" => $arParams["BIG_DATA_RCM_TYPE"]
	),
	$component,
	array("HIDE_ICONS" => "Y")
);?>


<?
/***CURRENT_VENDOR_NAME***/
$arFilter = array(
	"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	"ACTIVE" => "Y",
	"GLOBAL_ACTIVE" => "Y",
);

if(0 < intval($arResult["VARIABLES"]["ELEMENT_ID"])) {
	$arFilter["ID"] = $arResult["VARIABLES"]["ELEMENT_ID"];
} elseif('' != $arResult["VARIABLES"]["ELEMENT_CODE"]) {
	$arFilter["=CODE"] = $arResult["VARIABLES"]["ELEMENT_CODE"];
}

$rsElement = CIBlockElement::GetList(array(), $arFilter, false, false, array());
if($obElement = $rsElement->GetNextElement()) {
	$arFields = $obElement->GetFields();
	$arCurVendor["NAME"] = $arFields["NAME"];
}


/***PAGE_TITLE***/
if(!empty($_REQUEST['PAGEN_2']) && $_REQUEST['PAGEN_2']>1):
	$APPLICATION->SetPageProperty("title", $arCurVendor["NAME"]." | ".GetMessage('SECT_TITLE')." ".$_REQUEST['PAGEN_2']);
	$APPLICATION->SetPageProperty("keywords", "");
	$APPLICATION->SetPageProperty("description", "");
endif;


/***CANONICAL***/
if((!empty($_REQUEST['sort']) || !empty($_REQUEST['order']) || !empty($_REQUEST['limit']) || !empty($_REQUEST['view']) || !empty($_REQUEST['action'])) && empty($_REQUEST['PAGEN_2'])):
	$APPLICATION->AddHeadString("<link rel='canonical' href='".$APPLICATION->GetCurPage()."'>");
elseif((!empty($_REQUEST['sort']) || !empty($_REQUEST['order']) || !empty($_REQUEST['limit']) || !empty($_REQUEST['view']) || !empty($_REQUEST['action'])) && !empty($_REQUEST['PAGEN_2'])):
	$APPLICATION->AddHeadString("<link rel='canonical' href='".$APPLICATION->GetCurPage()."?PAGEN_2=".$_REQUEST['PAGEN_2']."'>");
endif;
?>