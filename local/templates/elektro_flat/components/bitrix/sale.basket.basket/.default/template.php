<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

if(StrLen($arResult["ERROR_MESSAGE"])<=0):
	$arUrlTempl = Array(
		"delete" => $APPLICATION->GetCurPage()."?action=delete&id=#ID#",
		"delay" => $APPLICATION->GetCurPage()."?action=delay&id=#ID#",
		"add" => $APPLICATION->GetCurPage()."?action=add&id=#ID#",
		"BasketClear" => $APPLICATION->GetCurPage()."?BasketClear=Y",
		"DelayClear" => $APPLICATION->GetCurPage()."?DelayClear=Y",
		"SubscribeClear" => $APPLICATION->GetCurPage()."?SubscribeClear=Y",
	);?>
	
	<script>
		function ShowBasketItems(val) {
			if(val == 1) {
				if(document.getElementById("id-cart-list"))
					document.getElementById("id-cart-list").style.display = 'block';
				if(document.getElementById("id-shelve-list"))
					document.getElementById("id-shelve-list").style.display = 'none';
				if(document.getElementById("id-subscribe-list"))
					document.getElementById("id-subscribe-list").style.display = 'none';
			} else if(val == 2) {
				if(document.getElementById("id-cart-list"))
					document.getElementById("id-cart-list").style.display = 'none';
				if(document.getElementById("id-shelve-list"))
					document.getElementById("id-shelve-list").style.display = 'block';
				if(document.getElementById("id-subscribe-list"))
					document.getElementById("id-subscribe-list").style.display = 'none';
			} else {
				if(document.getElementById("id-cart-list"))
					document.getElementById("id-cart-list").style.display = 'none';
				if(document.getElementById("id-shelve-list"))
					document.getElementById("id-shelve-list").style.display = 'none';
				if(document.getElementById("id-subscribe-list"))
					document.getElementById("id-subscribe-list").style.display = 'block';
			}
		}
	</script>
	
	<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="basket_form">
		<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items.php");
		include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_delay.php");
		include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_subscribe.php");?>
	</form>

	<?$APPLICATION->IncludeComponent("altop:buy.one.click", "cart", 
		array(
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"ELEMENT_ID" => "cart",
			"ELEMENT_PROPS" => "",
			"REQUIRED_ORDER_FIELDS" => array(
				0 => "NAME",
				1 => "TEL"
			),
			"DEFAULT_PERSON_TYPE" => "1",
			"DEFAULT_ORDER_PROP_NAME" => "1",
			"DEFAULT_ORDER_PROP_TEL" => "3",
			"DEFAULT_ORDER_PROP_EMAIL" => "2",
			"DEFAULT_DELIVERY" => "0",
			"DEFAULT_PAYMENT" => "0",			
			"BUY_MODE" => "ALL",			
			"DUPLICATE_LETTER_TO_EMAILS" => array(
				0 => "admin"
			)
		),
		false
	);

else:
	include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items.php");
endif;


/***CART_ACCESSORIES***/
if(!empty($arResult["ITEMS"]["ACCESSORIES"])):
	$acces_id = array_unique($arResult["ITEMS"]["ACCESSORIES"]);
	$parent_product_ids = $arResult["ITEMS"]["PARENT_PRODUCT_IDS"];
	
	global $arAccesCartFilter;
	$arAccesCartFilter = Array("ID" => $acces_id, "!ID" => $parent_product_ids);?>

	<?$APPLICATION->IncludeComponent("altop:catalog.top", "cart_accessories",
		Array(
			"DISPLAY_IMG_WIDTH" => $arParams["DISPLAY_IMG_WIDTH"],
			"DISPLAY_IMG_HEIGHT" => $arParams["DISPLAY_IMG_HEIGHT"],
			"SHARPEN" => $arParams["SHARPEN"],
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
			"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
			"ACTION_VARIABLE" => "action",
			"PRODUCT_ID_VARIABLE" => "id",
			"PRODUCT_QUANTITY_VARIABLE" => "quantity",
			"PRODUCT_PROPS_VARIABLE" => "prop",
			"SECTION_ID_VARIABLE" => "SECTION_ID",
			"DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
			"ELEMENT_COUNT" => "8",
			"LINE_ELEMENT_COUNT" => "",
			"FILTER_NAME" => "arAccesCartFilter",
			"PROPERTY_CODE" => $arParams["PROPERTY_CODE"],
			"PROPERTY_CODE_MOD" => $arParams["PROPERTY_CODE_MOD"],
			"OFFERS_FIELD_CODE" => $arParams["OFFERS_FIELD_CODE"],
			"OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
			"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
			"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
			"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
			"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
			"OFFERS_LIMIT" => $arParams["OFFERS_LIMIT"],
			"PRICE_CODE" => $arParams["PRICE_CODE"],
			"USE_PRICE_COUNT" => "N",
			"SHOW_PRICE_COUNT" => "1",
			"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
			"PRODUCT_PROPERTIES" => array(),
			"USE_PRODUCT_QUANTITY" => "Y",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "86400",
			"CACHE_NOTES" => "",
			"CACHE_GROUPS" => "Y",
			"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
			"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
			"CURRENCY_ID" => $arParams["CURRENCY_ID"],
			"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"]
		),
		false,
		array("HIDE_ICONS" => "Y")
	);?>
<?endif;


/***BIGDATA_ITEMS***/
if(!empty($arResult["ITEMS"]["AnDelCanBuy"])):
	$arRecomData = array();
	if(Loader::includeModule("catalog")) {
		$arSKU = CCatalogSKU::GetInfoByProductIBlock($arParams['IBLOCK_ID']);
		$arRecomData["OFFER_IBLOCK_ID"] = (!empty($arSKU) ? $arSKU['IBLOCK_ID'] : 0);
	}

	if(!empty($arRecomData)):
		if(ModuleManager::isModuleInstalled("sale") && (!isset($arParams['USE_BIG_DATA']) || $arParams['USE_BIG_DATA'] != 'N')):?>
			<?$APPLICATION->IncludeComponent("bitrix:catalog.bigdata.products", ".default",
				array(
					"DISPLAY_IMG_WIDTH" => $arParams["DISPLAY_IMG_WIDTH"],
					"DISPLAY_IMG_HEIGHT" => $arParams["DISPLAY_IMG_HEIGHT"],
					"SHARPEN" => $arParams["SHARPEN"],
					"DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
					"SHOW_POPUP" => "N",
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
					"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
					"DEPTH" => "2",
					"CACHE_TYPE" => "A",
					"CACHE_TIME" => "86400",
					"CACHE_GROUPS" => "Y",
					"SHOW_PRODUCTS_".$arParams["IBLOCK_ID"] => "Y",
					"ADDITIONAL_PICT_PROP_".$arParams["IBLOCK_ID"] => "",
					"LABEL_PROP_".$arParams["IBLOCK_ID"] => "",
					"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
					"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
					"CURRENCY_ID" => $arParams["CURRENCY_ID"],
					"SECTION_ID" => "",
					"SECTION_CODE" => "",
					"SECTION_ELEMENT_ID" => "",
					"SECTION_ELEMENT_CODE" => "",
					"ID" => "",
					"PROPERTY_CODE_".$arParams["IBLOCK_ID"] => $arParams["PROPERTY_CODE"],
					"PROPERTY_CODE_MOD" => $arParams["PROPERTY_CODE_MOD"],
					"CART_PROPERTIES_".$arParams["IBLOCK_ID"] => "",
					"RCM_TYPE" => $arParams["BIG_DATA_RCM_TYPE"],
					"OFFER_TREE_PROPS_".$arRecomData["OFFER_IBLOCK_ID"] => $arParams["OFFERS_PROPERTY_CODE"],
					"ADDITIONAL_PICT_PROP_".$arRecomData["OFFER_IBLOCK_ID"] => ""
				),
				false,
				array("HIDE_ICONS" => "Y")
			);?>
		<?endif;
	endif;
endif;?>