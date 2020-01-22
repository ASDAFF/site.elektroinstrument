<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$APPLICATION->AddHeadScript('/bitrix/components/altop/ask.price/templates/.default/script.js');
$APPLICATION->SetAdditionalCSS('/bitrix/components/altop/ask.price/templates/.default/style.css');

$APPLICATION->AddHeadScript('/bitrix/components/altop/ask.price/templates/order/script.js');
$APPLICATION->SetAdditionalCSS('/bitrix/components/altop/ask.price/templates/order/style.css');

$APPLICATION->AddHeadScript('/bitrix/components/altop/buy.one.click/templates/.default/script.js');
$APPLICATION->SetAdditionalCSS('/bitrix/components/altop/buy.one.click/templates/.default/style.css');

$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/components/bitrix/sale.notice.product/.default/style.css');

$APPLICATION->AddHeadScript('/bitrix/components/altop/catalog.comments/templates/.default/script.js');
$APPLICATION->SetAdditionalCSS('/bitrix/components/altop/catalog.comments/templates/.default/style.css');

$APPLICATION->AddHeadString("<meta property='og:title' content='".$arResult['NAME']."' />", true);
$APPLICATION->AddHeadString("<meta property='og:description' content='".strip_tags($arResult['PREVIEW_TEXT'])."' />", true);
$APPLICATION->AddHeadString("<meta property='og:url' content='".$APPLICATION->GetCurPage()."' />", true);
if(isset($arResult["OFFERS"]) && !empty($arResult["OFFERS"])):
	foreach($arResult["OFFERS"] as $key => $arOffer):
		if(is_array($arOffer["DETAIL_PICTURE"])):
			$APPLICATION->AddHeadString("<meta property='og:image' content='".$arOffer['DETAIL_PICTURE']['SRC']."' />", true);
		else:
			$APPLICATION->AddHeadString("<meta property='og:image' content='".$arResult['DETAIL_PICTURE']['SRC']."' />", true);
		endif;
	endforeach;
else:
	if(is_array($arResult["DETAIL_PICTURE"])):
		$APPLICATION->AddHeadString("<meta property='og:image' content='".$arResult['DETAIL_PICTURE']['SRC']."' />", true);
	else:
		$APPLICATION->AddHeadString("<meta property='og:image' content='".SITE_TEMPLATE_PATH."/images/no-photo.jpg' />", true);
	endif;
endif;
if(count($arResult["MORE_PHOTO"]) > 0):
	foreach($arResult["MORE_PHOTO"] as $PHOTO):
		$APPLICATION->AddHeadString("<meta property='og:image' content='".$PHOTO['SRC']."' />", true);
	endforeach;
endif;

if(isset($templateData['JS_OBJ'])):?>
	<script type="text/javascript">
		BX.ready(BX.defer(function(){
			if(!!window.<?=$templateData['JS_OBJ'];?>) {
				window.<?=$templateData['JS_OBJ'];?>.allowViewedCount(true);
			}
		}));
	</script>
<?endif;

if(!empty($arParams["IBLOCK_ID_REVIEWS"])):
	$arResult["REVIEWS"]["IBLOCK_ID"] = $arParams["IBLOCK_ID_REVIEWS"];
else:
	$res = CIBlock::GetList(
		Array(),
		Array("TYPE" => "catalog", "SITE_ID" => SITE_ID, "ACTIVE" => "Y", "CODE" => "comments_".SITE_ID),
		true
	);
	$reviews_iblock = $res->Fetch();
	$arResult["REVIEWS"]["IBLOCK_ID"] = $reviews_iblock["ID"];
endif?>

<div id="set-constructor-items-from" style="display:none;">
	<?$APPLICATION->IncludeComponent("bitrix:catalog.set.constructor", "",
		array(
			"IBLOCK_TYPE_ID" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"ELEMENT_ID" => $arResult["ID"],		
			"BASKET_URL" => $arParams["BASKET_URL"],
			"PRICE_CODE" => $arParams["PRICE_CODE"],
			"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
			"CURRENCY_ID" => $arParams["CURRENCY_ID"],
			"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"]
		),
		false,
		array("HIDE_ICONS" => "Y")
	);?>
</div>
<div id="accessories-from" class="accessories" style="display:none;">
	<?$arProperty = $arResult["PROPERTIES"]["ACCESSORIES"]["PROPERTY_VALUE_ID"];
	if($arProperty !=""): 
		global $arRecPrFilter;
		$arRecPrFilter["ID"] = $arResult["PROPERTIES"]["ACCESSORIES"]["VALUE"];
		$APPLICATION->IncludeComponent("altop:catalog.top", "access",
			Array(
				"DISPLAY_IMG_WIDTH" => $arParams["DISPLAY_IMG_WIDTH"],
				"DISPLAY_IMG_HEIGHT" => $arParams["DISPLAY_IMG_HEIGHT"],
				"SHARPEN" => $arParams["SHARPEN"],
				"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				"ELEMENT_SORT_FIELD" => "rand",
				"ELEMENT_SORT_ORDER" => "asc",
				"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
				"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
				"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
				"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
				"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
				"ELEMENT_COUNT" => "8",
				"LINE_ELEMENT_COUNT" => "",
				"FILTER_NAME" => "arRecPrFilter",
				"PROPERTY_CODE" => array("NEWPRODUCT", "SALELEADER", "DISCOUNT", "MANUFACTURER"),
				"PROPERTY_CODE_MOD" => $arParams["PROPERTY_CODE_MOD"],
				"OFFERS_FIELD_CODE" => $arParams["OFFERS_FIELD_CODE"],
				"OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
				"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
				"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
				"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
				"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
				"OFFERS_LIMIT" => $arParams["OFFERS_LIMIT"],
				"PRICE_CODE" => $arParams["PRICE_CODE"],
				"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
				"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
				"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
				"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
				"CACHE_TYPE" => $arParams["CACHE_TYPE"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
				"HIDE_NOT_AVAILABLE" => "N",
				"CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
				"CURRENCY_ID" => $arParams['CURRENCY_ID'],
				"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"]
			),
			false,
			array("HIDE_ICONS" => "Y")
		);
		unset($arResult["PROPERTIES"]["ACCESSORIES"]);
	endif;?>
</div>
<div id="catalog-reviews-from" style="display:none;">
	<?$APPLICATION->IncludeComponent("altop:catalog.comments", "",
		Array(
			"OBJECT_ID" => $arResult["ID"],
			"OBJECT_NAME" => $arResult["NAME"],
			"IBLOCK_TYPE" => "catalog",
			"COMMENTS_IBLOCK_ID" => $arResult["REVIEWS"]["IBLOCK_ID"],			
			"PROPERTY_OBJECT_ID" => "OBJECT_ID",
			"PROPERTY_USER_ID" => "USER_ID",
			"PROPERTY_IP_COMMENTOR" => "USER_IP",
			"PROPERTY_URL" => "COMMENT_URL",			
			"NON_AUTHORIZED_USER_CAN_COMMENT" => "Y",
			"PRE_MODERATION" => "Y",
			"USE_CAPTCHA" => "Y"
		),
		false
	);?>
</div>