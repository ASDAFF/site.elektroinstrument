<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule("sale") || !CModule::IncludeModule("catalog"))
	return;

$rsBasket = CSaleBasket::GetList(
	array(), 
	array(
		"FUSER_ID" => CSaleBasket::GetBasketUserID(),
		"LID" => SITE_ID,
		"ORDER_ID" => "NULL",
		"DELAY" => "N",
		"CAN_BUY" => "Y",
	), 
	false, 
	false, 
	array("QUANTITY", "PRICE", "CURRENCY", "DISCOUNT_PRICE", "WEIGHT", "VAT_RATE", "ID", "SET_PARENT_ID", "PRODUCT_ID", "CATALOG_XML_ID", "PRODUCT_XML_ID", "PRODUCT_PROVIDER_CLASS", "TYPE")
);

$arBasketItems = array();

while($arItem = $rsBasket->Fetch()) {
	if(CSaleBasketHelper::isSetItem($arItem))
		continue;
	$arBasketItems[] = $arItem;
}

$totalQnt = 0;
$totalPrice = 0;

if($arBasketItems) {	
	foreach($arBasketItems as $arItem) {
		$totalQnt += $arItem["QUANTITY"];
		$totalPrice += $arItem["PRICE"] * $arItem["QUANTITY"];			
	}

	$arOrder = array(
		'SITE_ID' => SITE_ID,
		'ORDER_PRICE' => $totalPrice,
		'BASKET_ITEMS' => $arBasketItems
	);
	
	$arOrder['USER_ID'] = CSaleBasket::GetBasketUserID();
	$arErrors = array();
	CSaleDiscount::DoProcessOrder($arOrder, array(), $arErrors);

	$totalPrice = $arOrder['ORDER_PRICE'];
}

$arResult["QUANTITY"] = $totalQnt;

$currency = CCurrencyLang::GetCurrencyFormat(CSaleLang::GetLangCurrency(SITE_ID), "ru");
if(empty($currency["THOUSANDS_SEP"])):
	$currency["THOUSANDS_SEP"] = " ";
endif;

$arResult["SUM"] = number_format($totalPrice, $currency["DECIMALS"], $currency["DEC_POINT"], $currency["THOUSANDS_SEP"]);
$arResult["CURRENCY"] = str_replace("#", " ", $currency["FORMAT_STRING"]);
?>