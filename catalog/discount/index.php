<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Скидки");

global $arrFilter;
$arrFilter = array(
    "!PROPERTY_DISCOUNT" => false
);?>

<?$APPLICATION->IncludeComponent("altop:catalog.top", "new_disc_leader",
	Array(
		"DISPLAY_IMG_WIDTH" => "178",
		"DISPLAY_IMG_HEIGHT" => "178",
		"SHARPEN" => "30",
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "3",
		"ELEMENT_SORT_FIELD" => "CATALOG_AVAILABLE",
		"ELEMENT_SORT_ORDER" => "desc",
		"ELEMENT_SORT_FIELD2" => "",
		"ELEMENT_SORT_ORDER2" => "",
		"SECTION_URL" => "",
		"DETAIL_URL" => "",
		"BASKET_URL" => "/personal/cart/",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"DISPLAY_COMPARE" => "Y",
		"ELEMENT_COUNT" => "6",
		"LINE_ELEMENT_COUNT" => "",
		"FILTER_NAME" => "arrFilter",
		"PROPERTY_CODE" => array(
			0 => "CHASTOTA_H_H",
			1 => "MAX_KR_MOM",
			2 => "NAPRAJ_AKKUM",
			3 => "VES_S_AKKUM"
		),
		"PROPERTY_CODE_MOD" => array(),
		"FLAG_PROPERTY_CODE" => "",
		"OFFERS_FIELD_CODE" => array(),
		"OFFERS_PROPERTY_CODE" => array(
			0 => "COLOR",
			1 => "PROP2"
		),
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER2" => "asc",
		"OFFERS_LIMIT" => "",
		"PRICE_CODE" => array(
			0 => "BASE"
		),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_PROPERTIES" => array(),
		"USE_PRODUCT_QUANTITY" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "86400",
		"CACHE_NOTES" => "",
		"CACHE_GROUPS" => "Y",
		"HIDE_NOT_AVAILABLE" => "N",
		"CONVERT_CURRENCY" => "N",
		"CURRENCY_ID" => "",
		"OFFERS_CART_PROPERTIES" => array(
			0 => "COLOR",
			1 => "PROP2"
		),
		"USE_BIG_DATA" => "Y",
		"BIG_DATA_RCM_TYPE" => "any"
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>