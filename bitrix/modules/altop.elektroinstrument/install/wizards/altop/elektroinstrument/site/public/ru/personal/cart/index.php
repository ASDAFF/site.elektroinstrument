<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Моя корзина");?>

<?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket", ".default", 
	array(
		"COLUMNS_LIST" => array(
			0 => "NAME",
			1 => "DISCOUNT",
			2 => "PROPS",
			3 => "DELETE",
			4 => "DELAY",
			5 => "PRICE",
			6 => "QUANTITY",
		),
		"PATH_TO_ORDER" => SITE_DIR."personal/order/make/",
		"HIDE_COUPON" => "N",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
		"USE_PREPAYMENT" => "N",
		"QUANTITY_FLOAT" => "N",
		"SET_TITLE" => "Y",
		"ACTION_VARIABLE" => "action",
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "#ITEMS_IBLOCK_ID#",
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
			0 => "BASE",
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"OFFERS_CART_PROPERTIES" => array(
			0 => "COLOR",
			1 => "PROP2"
		),
		"DISPLAY_IMG_WIDTH" => "178",
		"DISPLAY_IMG_HEIGHT" => "178",
		"ELEMENT_SORT_FIELD" => "rand",
		"ELEMENT_SORT_ORDER" => "asc",
		"DISPLAY_COMPARE" => "Y",
		"PROPERTY_CODE" => array(
			0 => "NEWPRODUCT",
			1 => "SALELEADER",
			2 => "DISCOUNT",
			3 => "MANUFACTURER"
		),
		"PROPERTY_CODE_MOD" => array(),
		"HIDE_NOT_AVAILABLE" => "N",
		"USE_BIG_DATA" => "Y",
		"BIG_DATA_RCM_TYPE" => "any"
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>