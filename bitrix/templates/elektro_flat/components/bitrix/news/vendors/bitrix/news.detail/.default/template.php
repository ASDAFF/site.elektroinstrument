<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

global $arrFilter;
$arrFilter = array(
	"PROPERTY_MANUFACTURER" => $arResult["ID"]
);

$count = CIBlockElement::GetList(
    Array(),
	$arrFilter,
	array(),
	false,
	array("ID", "NAME")
);?>

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

<?$APPLICATION->IncludeComponent("bitrix:catalog.section", $view,
	Array(
		"AJAX_MODE" => "N",
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE_CATALOG"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID_CATALOG"],
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
		"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
		"ELEMENT_SORT_FIELD2" => $sort,
		"ELEMENT_SORT_ORDER2" => $sort_order,
		"FILTER_NAME" => "arrFilter",
		"INCLUDE_SUBSECTIONS" => "Y",
		"SHOW_ALL_WO_SECTION" => "Y",
		"SECTION_URL" => $arParams["SECTION_URL"],
		"DETAIL_URL" => $arParams["DETAIL_URL"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SET_META_KEYWORDS" => "N",
		"META_KEYWORDS" => "",
		"SET_META_DESCRIPTION" => "N",
		"META_DESCRIPTION" => "",
		"BROWSER_TITLE" => "",
		"ADD_SECTIONS_CHAIN" => "",
		"DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
		"SET_TITLE" => "Y",
		"SET_STATUS_404" => "Y",
		"PAGE_ELEMENT_COUNT" => $limit,
		"LINE_ELEMENT_COUNT" => "",
		"PROPERTY_CODE" => $arParams["PROPERTY_CODE"],
		"PROPERTY_CODE_MOD" => $arParams["PROPERTY_CODE_MOD"],
		"OFFERS_FIELD_CODE" => $arParams["OFFERS_FIELD_CODE"],
		"OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
		"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
		"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
		"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
		"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
		"OFFERS_LIMIT" => "",
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
		"PRODUCT_PROPERTIES" => array(),
		"USE_PRODUCT_QUANTITY" => "Y",
		"CACHE_TYPE" => $arParams["CACHE_TYPE_CATALOG"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_NOTES" => "",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"PAGER_TEMPLATE" => "arrows",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => GetMessage("PRODUCTS_OF_MANUFACTURER"),
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "86400",
		"PAGER_SHOW_ALL" => "N",
		"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
		"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
		"CURRENCY_ID" => $arParams["CURRENCY_ID"],
		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"DISPLAY_IMG_WIDTH"	 =>	$arParams["DISPLAY_IMG_WIDTH"],
		"DISPLAY_IMG_HEIGHT" =>	$arParams["DISPLAY_IMG_HEIGHT"],
		"SHARPEN" => "30",
	),
	false
);?>


<?/***DESCRIPTION***/
if(!empty($arResult["PREVIEW_TEXT"])):
	if(empty($_REQUEST["PAGEN_2"]) || (!empty($_REQUEST["PAGEN_2"]) && $_REQUEST["PAGEN_2"]=="1")):?>
		<div class="catalog_description">
			<?=$arResult["PREVIEW_TEXT"];?>
		</div>
	<?endif;
endif;


/***BIGDATA_ITEMS***/
$arRecomData = array();
if(Loader::includeModule("catalog")) {
	$arSKU = CCatalogSKU::GetInfoByProductIBlock($arParams["IBLOCK_ID_CATALOG"]);
	$arRecomData["OFFER_IBLOCK_ID"] = (!empty($arSKU) ? $arSKU["IBLOCK_ID"] : 0);
}

if(!empty($arRecomData)):
	if(ModuleManager::isModuleInstalled("sale") && (!isset($arParams['USE_BIG_DATA']) || $arParams['USE_BIG_DATA'] != 'N')):?>
		<?$APPLICATION->IncludeComponent("bitrix:catalog.bigdata.products", ".default", 
			array(
				"DISPLAY_IMG_WIDTH" => $arParams["DISPLAY_IMG_WIDTH"],
				"DISPLAY_IMG_HEIGHT" => $arParams["DISPLAY_IMG_HEIGHT"],
				"SHARPEN" => $arParams["SHARPEN"],
				"DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
				"SHOW_POPUP" => "Y",
				"LINE_ELEMENT_COUNT" => "4",
				"TEMPLATE_THEME" => "",
				"DETAIL_URL" => "/catalog/#SECTION_CODE#/#ELEMENT_CODE#/",
				"BASKET_URL" => "/personal/cart/",
				"ACTION_VARIABLE" => "action",
				"PRODUCT_ID_VARIABLE" => "id",
				"PRODUCT_QUANTITY_VARIABLE" => "quantity",
				"ADD_PROPERTIES_TO_BASKET" => "Y",
				"PRODUCT_PROPS_VARIABLE" => "prop",
				"PARTIAL_PRODUCT_PROPERTIES" => "",
				"SHOW_OLD_PRICE" => "",
				"SHOW_DISCOUNT_PERCENT" => "",
				"PRICE_CODE" => $arParams["PRICE_CODE"],
				"SHOW_PRICE_COUNT" => "1",
				"PRODUCT_SUBSCRIPTION" => "",
				"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
				"USE_PRODUCT_QUANTITY" => "Y",
				"SHOW_NAME" => "Y",
				"SHOW_IMAGE" => "Y",
				"MESS_BTN_BUY" => "",
				"MESS_BTN_DETAIL" => "",
				"MESS_BTN_SUBSCRIBE" => "",
				"MESS_NOT_AVAILABLE" => "",
				"PAGE_ELEMENT_COUNT" => "4",
				"SHOW_FROM_SECTION" => "N",
				"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE_CATALOG"],
				"IBLOCK_ID" => $arParams["IBLOCK_ID_CATALOG"],
				"DEPTH" => "2",
				"CACHE_TYPE" => $arParams["CACHE_TYPE_CATALOG"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
				"SHOW_PRODUCTS_".$arParams["IBLOCK_ID_CATALOG"] => "Y",
				"ADDITIONAL_PICT_PROP_".$arParams["IBLOCK_ID_CATALOG"] => "",
				"LABEL_PROP_".$arParams["IBLOCK_ID_CATALOG"] => "",
				"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
				"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
				"CURRENCY_ID" => $arParams["CURRENCY_ID"],
				"SECTION_ID" => "",
				"SECTION_CODE" => "",
				"SECTION_ELEMENT_ID" => "",
				"SECTION_ELEMENT_CODE" => "",
				"ID" => "",
				"PROPERTY_CODE_".$arParams["IBLOCK_ID_CATALOG"] => $arParams["PROPERTY_CODE"],
				"PROPERTY_CODE_MOD" => $arParams["PROPERTY_CODE_MOD"],
				"CART_PROPERTIES_".$arParams["IBLOCK_ID_CATALOG"] => "",
				"RCM_TYPE" => $arParams["BIG_DATA_RCM_TYPE"],
				"OFFER_TREE_PROPS_".$arRecomData["OFFER_IBLOCK_ID"] => $arParams["OFFERS_PROPERTY_CODE"],
				"ADDITIONAL_PICT_PROP_".$arRecomData["OFFER_IBLOCK_ID"] => ""
			),
			false,
			array("HIDE_ICONS" => "Y")
		);?>
	<?endif;
endif;