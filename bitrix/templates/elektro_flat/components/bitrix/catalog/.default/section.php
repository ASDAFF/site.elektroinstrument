<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

global $arSetting;

/***SUBSECTION***/
$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "subsection",
	Array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
		"TOP_DEPTH" => 1,
		"DISPLAY_IMG_WIDTH"	 =>	50,
		"DISPLAY_IMG_HEIGHT" =>	50,
		"SHARPEN" =>	$arParams["SHARPEN"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
	),
	$component
);


/***CURRENT_SECTION_ID_NAME_DESCR***/
$arFilter = array(
	"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	"ACTIVE" => "Y",
	"GLOBAL_ACTIVE" => "Y",
);

if(0 < intval($arResult["VARIABLES"]["SECTION_ID"])) {
	$arFilter["ID"] = $arResult["VARIABLES"]["SECTION_ID"];
} elseif('' != $arResult["VARIABLES"]["SECTION_CODE"]) {
	$arFilter["=CODE"] = $arResult["VARIABLES"]["SECTION_CODE"];
}
			
$rsSections = CIBlockSection::GetList(array(), $arFilter);
if($arSection = $rsSections->Fetch()) {
	$arCurSection["ID"] = $arSection["ID"];
	$arCurSection["NAME"] = $arSection["NAME"];
}


/***FILTER***/
if('Y' == $arParams['USE_FILTER']):
	$APPLICATION->IncludeComponent("bitrix:catalog.smart.filter", "elektro",	
		Array(
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"SECTION_ID" => $arCurSection["ID"],
			"FILTER_NAME" => $arParams["FILTER_NAME"],
			"PRICE_CODE" => $arParams["PRICE_CODE"],
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"SAVE_IN_SESSION" => "N",
			"FILTER_VIEW_MODE" => "",			
			"XML_EXPORT" => "N",
			"SECTION_TITLE" => "NAME",
			"SECTION_DESCRIPTION" => "DESCRIPTION",
			"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
			"TEMPLATE_THEME" => "",
			"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
			"CURRENCY_ID" => $arParams["CURRENCY_ID"],
			"SEF_MODE" => $arParams["SEF_MODE"],
			"SEF_RULE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["smart_filter"],
			"SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
			"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
		),
		$component,
		array('HIDE_ICONS' => 'Y')
	);
endif;


/***COUNT***/
global $arSmartFilter;
$count = CIBlockElement::GetList(array(), $arSmartFilter, array(), false);?>

<div class="filter_indent<?=($arSetting['SMART_FILTER_LOCATION']['VALUE'] == 'VERTICAL') ? ' vertical' : '';?> clr"></div>
<div class="count_items">
	<label><?=GetMessage("COUNT_ITEMS")?></label>
	<span><?=$count?></span>
</div>


<?
/***SORT***/
$arAvailableSort = array(
	"default" => Array("sort", "asc"),
	"price" => Array("PROPERTY_MINIMUM_PRICE", "asc"),
	"rating" => Array("PROPERTY_rating", "desc"),
);

$sort = $APPLICATION->get_cookie("sort") ? $APPLICATION->get_cookie("sort") : "sort";
$sort_order = $APPLICATION->get_cookie("order") ? $APPLICATION->get_cookie("order") : "asc";

if($_REQUEST["sort"])
	$sort = "sort";
	$APPLICATION->set_cookie("sort", $sort);
if($_REQUEST["sort"] == "price")
	$sort = "PROPERTY_MINIMUM_PRICE";
	$APPLICATION->set_cookie("sort", $sort);
if($_REQUEST["sort"] == "rating")
	$sort = "PROPERTY_rating";
	$APPLICATION->set_cookie("sort", $sort);
if($_REQUEST["order"])
	$sort_order = "asc";
	$APPLICATION->set_cookie("order", $sort_order);
if($_REQUEST["order"] == "desc")
	$sort_order = "desc";
	$APPLICATION->set_cookie("order", $sort_order);
?>

<div class="catalog-item-sorting">
	<label><span class="full"><?=GetMessage("SECT_SORT_LABEL_FULL")?></span><span class="short"><?=GetMessage("SECT_SORT_LABEL_SHORT")?></span>:</label>
	<?foreach($arAvailableSort as $key => $val):
		$className = $sort == $val[0] ? "selected" : "";
		if($className) 
			$className .= $sort_order == "asc" ? " asc" : " desc";
		$newSort = $sort == $val[0] ? $sort_order == "desc" ? "asc" : "desc" : $arAvailableSort[$key][1];?>

		<a href="<?=$APPLICATION->GetCurPageParam("sort=".$key."&amp;order=".$newSort, array("sort", "order"))?>" class="<?=$className?>" rel="nofollow"><?=GetMessage("SECT_SORT_".$key)?></a>
	<?endforeach;?>
</div>


<?
/***LIMIT***/
$arAvailableLimit = array("12", "48", "900");

$limit = $APPLICATION->get_cookie("limit") ? $APPLICATION->get_cookie("limit") : "12";

if($_REQUEST["limit"])
	$limit = "12";
	$APPLICATION->set_cookie("limit", $limit); 	
if($_REQUEST["limit"] == "48")
	$limit = "48";
	$APPLICATION->set_cookie("limit", $limit); 
if($_REQUEST["limit"] == "900")
	$limit = "900";
	$APPLICATION->set_cookie("limit", $limit);
?>

<div class="catalog-item-limit">
	<label><span class="full"><?=GetMessage("SECT_COUNT_LABEL_FULL")?></span><span class="short"><?=GetMessage("SECT_COUNT_LABEL_SHORT")?></span>:</label>
	<?foreach($arAvailableLimit as $val):?>
		<a href="<?=$APPLICATION->GetCurPageParam("limit=".$val, array("limit"))?>" <?if($limit==$val) echo " class='selected'";?> rel="nofollow"><?if($val=="900"): echo GetMessage("SECT_COUNT_ALL"); else: echo $val; endif;?></a>
	<?endforeach;?>
</div>


<?
/***VIEW***/
$arAvailableView = array("table", "list", "price");

$view = $APPLICATION->get_cookie("view") ? $APPLICATION->get_cookie("view") : "table";

if($_REQUEST["view"])
	$view = "table";
	$APPLICATION->set_cookie("view", $view); 	
if($_REQUEST["view"] == "list")
	$view = "list";
	$APPLICATION->set_cookie("view", $view); 
if($_REQUEST["view"] == "price")
	$view = "price";
	$APPLICATION->set_cookie("view", $view);
?>

<div class="catalog-item-view">
	<?foreach($arAvailableView as $val):?>
		<a href="<?=$APPLICATION->GetCurPageParam("view=".$val, array("view"))?>" class="<?=$val?><?if($view==$val) echo ' selected';?>" title="<?=GetMessage('SECT_VIEW_'.$val)?>" rel="nofollow">
			<?if($val == "table"):?>
				<i class="fa fa-th-large"></i>
			<?elseif($val == "list"):?>
				<i class="fa fa-list"></i>
			<?elseif($val == "price"):?>
				<i class="fa fa-align-justify"></i>
			<?endif?>
		</a>
	<?endforeach;?>
</div>
<div class="clr"></div>


<?$intSectionID = $APPLICATION->IncludeComponent("bitrix:catalog.section", $view,
	Array(
		"AJAX_MODE" => $arParams["AJAX_MODE"],
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
        "ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
		"ELEMENT_SORT_FIELD2" => $sort,
        "ELEMENT_SORT_ORDER2" => $sort_order,
		"FILTER_NAME" => $arParams["FILTER_NAME"],
		"INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
		"SHOW_ALL_WO_SECTION" => "N",
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
		"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
		"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
		"SET_META_KEYWORDS" => "Y",
		"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
		"SET_META_DESCRIPTION" => "Y",
		"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
		"BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
		"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
		"ADD_SECTIONS_CHAIN" => "N",
		"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
		"SET_TITLE" => $arParams["SET_TITLE"],
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"PAGE_ELEMENT_COUNT" => $limit,
		"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
		"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
		"PROPERTY_CODE_MOD" => $arParams["PROPERTY_CODE_MOD"],
		"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
		"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
		"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
		"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
		"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
		"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
		"OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
		"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
		"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_NOTES" => $arParams["CACHE_NOTES"],
		"CACHE_FILTER" => $arParams["CACHE_FILTER"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
		"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
		"PAGER_TITLE" => $arParams["PAGER_TITLE"],
		"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
		"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
		"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
		"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
		"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
		"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
		"CURRENCY_ID" => $arParams["CURRENCY_ID"],
		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"AJAX_OPTION_JUMP" => $arParams["AJAX_OPTION_JUMP"],
		"AJAX_OPTION_STYLE" => $arParams["AJAX_OPTION_STYLE"],
		"AJAX_OPTION_HISTORY" => $arParams["AJAX_OPTION_HISTORY"],
		"DISPLAY_IMG_WIDTH"	 =>	$arParams["DISPLAY_IMG_WIDTH"],
		"DISPLAY_IMG_HEIGHT" =>	$arParams["DISPLAY_IMG_HEIGHT"],
		"SHARPEN" => $arParams["SHARPEN"],
	),
	$component
);?>


<?/***BIGDATA_ITEMS***/
$arRecomData = array();
if(Loader::includeModule("catalog")) {
	$arSKU = CCatalogSKU::GetInfoByProductIBlock($arParams["IBLOCK_ID"]);
	$arRecomData["OFFER_IBLOCK_ID"] = (!empty($arSKU) ? $arSKU["IBLOCK_ID"] : 0);
}

if(!empty($arRecomData)):
	if(ModuleManager::isModuleInstalled("sale") && (!isset($arParams["USE_BIG_DATA"]) || $arParams["USE_BIG_DATA"] != "N")):?>
		<?$APPLICATION->IncludeComponent("bitrix:catalog.bigdata.products", ".default", 
			array(
				"DISPLAY_IMG_WIDTH" => $arParams["DISPLAY_IMG_WIDTH"],
				"DISPLAY_IMG_HEIGHT" => $arParams["DISPLAY_IMG_HEIGHT"],
				"SHARPEN" => $arParams["SHARPEN"],
				"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
				"SHOW_POPUP" => "Y",
				"LINE_ELEMENT_COUNT" => "4",
				"TEMPLATE_THEME" => "",
				"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
				"BASKET_URL" => $arParams["BASKET_URL"],
				"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
				"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
				"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
				"ADD_PROPERTIES_TO_BASKET" => $arParams["ADD_PROPERTIES_TO_BASKET"],
				"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
				"PARTIAL_PRODUCT_PROPERTIES" => $arParams["PARTIAL_PRODUCT_PROPERTIES"],
				"SHOW_OLD_PRICE" => "",
				"SHOW_DISCOUNT_PERCENT" => "",
				"PRICE_CODE" => $arParams["PRICE_CODE"],
				"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
				"PRODUCT_SUBSCRIPTION" => "",
				"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
				"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
				"SHOW_NAME" => "Y",
				"SHOW_IMAGE" => "Y",
				"MESS_BTN_BUY" => "",
				"MESS_BTN_DETAIL" => "",
				"MESS_BTN_SUBSCRIBE" => "",
				"MESS_NOT_AVAILABLE" => "",
				"PAGE_ELEMENT_COUNT" => "4",
				"SHOW_FROM_SECTION" => "Y",
				"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				"DEPTH" => "2",
				"CACHE_TYPE" => $arParams["CACHE_TYPE"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
				"SHOW_PRODUCTS_".$arParams["IBLOCK_ID"] => "Y",
				"ADDITIONAL_PICT_PROP_".$arParams["IBLOCK_ID"] => "",
				"LABEL_PROP_".$arParams["IBLOCK_ID"] => "",
				"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
				"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
				"CURRENCY_ID" => $arParams["CURRENCY_ID"],
				"SECTION_ID" => $intSectionID,
				"SECTION_CODE" => "",
				"SECTION_ELEMENT_ID" => "",
				"SECTION_ELEMENT_CODE" => "",
				"ID" => "",
				"PROPERTY_CODE_".$arParams["IBLOCK_ID"] => $arParams["LIST_PROPERTY_CODE"],
				"PROPERTY_CODE_MOD" => $arParams["PROPERTY_CODE_MOD"],
				"CART_PROPERTIES_".$arParams["IBLOCK_ID"] => "",
				"RCM_TYPE" => $arParams["BIG_DATA_RCM_TYPE"],
				"OFFER_TREE_PROPS_".$arRecomData["OFFER_IBLOCK_ID"] => $arParams["LIST_OFFERS_PROPERTY_CODE"],
				"ADDITIONAL_PICT_PROP_".$arRecomData["OFFER_IBLOCK_ID"] => ""
			),
			false,
			array("HIDE_ICONS" => "Y")
		);?>
	<?endif;
endif;


/***PAGE_TITLE***/
if(!empty($_REQUEST['PAGEN_1']) && $_REQUEST['PAGEN_1']>1):
	$APPLICATION->SetPageProperty("title", $arCurSection['NAME']." | ".GetMessage('SECT_TITLE')." ".$_REQUEST['PAGEN_1']);
	$APPLICATION->SetPageProperty("keywords", "");
	$APPLICATION->SetPageProperty("description", "");
endif;

/***CANONICAL***/
if((!empty($_REQUEST['sort']) || !empty($_REQUEST['order']) || !empty($_REQUEST['limit']) || !empty($_REQUEST['view']) || !empty($_REQUEST['action']) || !empty($_REQUEST['set_filter'])) && empty($_REQUEST['PAGEN_1'])):
	$APPLICATION->AddHeadString("<link rel='canonical' href='".$APPLICATION->GetCurPage()."'>");
elseif((!empty($_REQUEST['sort']) || !empty($_REQUEST['order']) || !empty($_REQUEST['limit']) || !empty($_REQUEST['view']) || !empty($_REQUEST['action']) || !empty($_REQUEST['set_filter'])) && !empty($_REQUEST['PAGEN_1'])):
	$APPLICATION->AddHeadString("<link rel='canonical' href='".$APPLICATION->GetCurPage()."?PAGEN_1=".$_REQUEST['PAGEN_1']."'>");
endif;
?>