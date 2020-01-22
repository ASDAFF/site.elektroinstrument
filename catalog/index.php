<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Каталог товаров");?><?$APPLICATION->IncludeComponent(
	"bitrix:catalog",
	"",
	Array(
		"ACTION_VARIABLE" => "action",
		"ADD_ELEMENT_CHAIN" => "N",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"BASKET_URL" => "/personal/cart/",
		"BIG_DATA_RCM_TYPE" => "any",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_NOTES" => "",
		"CACHE_TIME" => "86400",
		"CACHE_TYPE" => "A",
		"COMPARE_ELEMENT_SORT_FIELD" => "sort",
		"COMPARE_ELEMENT_SORT_ORDER" => "asc",
		"COMPARE_FIELD_CODE" => array("NAME","PREVIEW_TEXT","DETAIL_PICTURE",""),
		"COMPARE_NAME" => "CATALOG_COMPARE_LIST",
		"COMPARE_OFFERS_FIELD_CODE" => array("",""),
		"COMPARE_OFFERS_PROPERTY_CODE" => array("COLOR","PROP2",""),
		"COMPARE_PROPERTY_CODE" => array("NEWPRODUCT","SALELEADER","DISCOUNT","ARTNUMBER","MANUFACTURER","COUNTRY","CHASTOTA_H_H","MAX_KR_MOM","NAPRAJ_AKKUM","VES_S_AKKUM",""),
		"COMPATIBLE_MODE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"CURRENCY_ID" => "",
		"DETAIL_BACKGROUND_IMAGE" => "-",
		"DETAIL_BROWSER_TITLE" => "TITLE",
		"DETAIL_CHECK_SECTION_ID_VARIABLE" => "N",
		"DETAIL_META_DESCRIPTION" => "DESCRIPTION",
		"DETAIL_META_KEYWORDS" => "KEYWORDS",
		"DETAIL_OFFERS_FIELD_CODE" => array("",""),
		"DETAIL_OFFERS_PROPERTY_CODE" => array(0=>"COLOR",1=>"PROP2"),
		"DETAIL_PROPERTY_CODE" => array(0=>"ARTNUMBER",1=>"MANUFACTURER",2=>"COUNTRY",3=>"CHASTOTA_H_H",4=>"MAX_KR_MOM",5=>"NAPRAJ_AKKUM",6=>"VES_S_AKKUM"),
		"DETAIL_SET_CANONICAL_URL" => "N",
		"DETAIL_SET_VIEWED_IN_COMPONENT" => "N",
		"DETAIL_STRICT_SECTION_CHECK" => "N",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_DETAIL_IMG_HEIGHT" => "390",
		"DISPLAY_DETAIL_IMG_WIDTH" => "390",
		"DISPLAY_ELEMENT_SELECT_BOX" => "N",
		"DISPLAY_IMG_HEIGHT" => "178",
		"DISPLAY_IMG_WIDTH" => "178",
		"DISPLAY_MORE_PHOTO_HEIGHT" => "86",
		"DISPLAY_MORE_PHOTO_WIDTH" => "86",
		"DISPLAY_TOP_PAGER" => "N",
		"ELEMENT_SORT_FIELD" => "CATALOG_AVAILABLE",
		"ELEMENT_SORT_FIELD2" => "",
		"ELEMENT_SORT_ORDER" => "desc",
		"ELEMENT_SORT_ORDER2" => "",
		"FIELDS" => array("",""),
		"FILTER_FIELD_CODE" => array("",""),
		"FILTER_NAME" => "arrFilter",
		"FILTER_OFFERS_FIELD_CODE" => array("",""),
		"FILTER_OFFERS_PROPERTY_CODE" => array("",""),
		"FILTER_PRICE_CODE" => array("BASE"),
		"FILTER_PROPERTY_CODE" => array("",""),
		"GIFTS_DETAIL_BLOCK_TITLE" => "Выберите один из подарков",
		"GIFTS_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => "4",
		"GIFTS_DETAIL_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE" => "Выберите один из товаров, чтобы получить подарок",
		"GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT" => "4",
		"GIFTS_MESS_BTN_BUY" => "Выбрать",
		"GIFTS_SECTION_LIST_BLOCK_TITLE" => "Подарки к товарам этого раздела",
		"GIFTS_SECTION_LIST_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT" => "4",
		"GIFTS_SECTION_LIST_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
		"GIFTS_SHOW_IMAGE" => "Y",
		"GIFTS_SHOW_NAME" => "Y",
		"GIFTS_SHOW_OLD_PRICE" => "Y",
		"HIDE_NOT_AVAILABLE" => "N",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"IBLOCK_ID" => "3",
		"IBLOCK_ID_REVIEWS" => "5",
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_TYPE_REVIEWS" => "catalog",
		"INCLUDE_SUBSECTIONS" => "Y",
		"LINE_ELEMENT_COUNT" => "1",
		"LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
		"LINK_IBLOCK_ID" => "",
		"LINK_IBLOCK_TYPE" => "",
		"LINK_PROPERTY_SID" => "",
		"LIST_BROWSER_TITLE" => "UF_BROWSER_TITLE",
		"LIST_META_DESCRIPTION" => "UF_META_DESCRIPTION",
		"LIST_META_KEYWORDS" => "UF_KEYWORDS",
		"LIST_OFFERS_FIELD_CODE" => array("",""),
		"LIST_OFFERS_LIMIT" => "",
		"LIST_OFFERS_PROPERTY_CODE" => array(0=>"COLOR",1=>"PROP2"),
		"LIST_PROPERTY_CODE" => array(0=>"CHASTOTA_H_H",1=>"MAX_KR_MOM",2=>"NAPRAJ_AKKUM",3=>"VES_S_AKKUM"),
		"MAIN_TITLE" => "Наличие на складах",
		"MESSAGE_404" => "",
		"OFFERS_CART_PROPERTIES" => array(0=>"COLOR",1=>"PROP2"),
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_ORDER2" => "asc",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "arrows",
		"PAGER_TITLE" => "Товары",
		"PAGE_ELEMENT_COUNT" => "12",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PATH_TO_SHIPPING" => "/content/delivery/",
		"PRICE_CODE" => array("BASE"),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PROPERTY_CODE_MOD" => array("",""),
		"SECTION_BACKGROUND_IMAGE" => "-",
		"SECTION_COUNT_ELEMENTS" => "N",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SECTION_TOP_DEPTH" => "2",
		"SEF_FOLDER" => "/catalog/",
		"SEF_MODE" => "Y",
		"SEF_URL_TEMPLATES" => Array("compare"=>"compare/","element"=>"#SECTION_CODE#/#ELEMENT_CODE#/","section"=>"#SECTION_CODE#/","sections"=>"","smart_filter"=>"#SECTION_CODE#/filter/#SMART_FILTER_PATH#/apply/"),
		"SET_LAST_MODIFIED" => "N",
		"SET_STATUS_404" => "Y",
		"SET_TITLE" => "Y",
		"SHARPEN" => "30",
		"SHOW_404" => "N",
		"SHOW_DEACTIVATED" => "N",
		"SHOW_EMPTY_STORE" => "Y",
		"SHOW_GENERAL_STORE_INFORMATION" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"SHOW_TOP_ELEMENTS" => "N",
		"STORES" => array("",""),
		"STORE_PATH" => "/store/#store_id#",
		"USER_CONSENT" => "N",
		"USER_CONSENT_ID" => "0",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N",
		"USER_FIELDS" => array("",""),
		"USE_ALSO_BUY" => "N",
		"USE_BIG_DATA" => "Y",
		"USE_COMPARE" => "Y",
		"USE_ELEMENT_COUNTER" => "Y",
		"USE_FILTER" => "Y",
		"USE_GIFTS_DETAIL" => "Y",
		"USE_GIFTS_MAIN_PR_SECTION_LIST" => "Y",
		"USE_GIFTS_SECTION" => "Y",
		"USE_MAIN_ELEMENT_SECTION" => "Y",
		"USE_MIN_AMOUNT" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "Y",
		"USE_REVIEW" => "N",
		"USE_STORE" => "Y",
		"USE_STORE_PHONE" => "Y",
		"USE_STORE_SCHEDULE" => "Y"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>