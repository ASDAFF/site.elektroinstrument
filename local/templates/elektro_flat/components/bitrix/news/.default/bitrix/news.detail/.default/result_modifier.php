<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arSort = array(
	$arParams["SORT_BY1"]=>$arParams["SORT_ORDER1"],
	$arParams["SORT_BY2"]=>$arParams["SORT_ORDER2"],
);

$arSelect = array(
	"ID",
	"NAME",
	"DETAIL_PAGE_URL",
	"DATE_ACTIVE_FROM",
);

$arFilter = array (
	"IBLOCK_ID" => $arResult["IBLOCK_ID"],
	"ACTIVE" => "Y",
	"CHECK_PERMISSIONS" => "Y",
);

if($arParams["CHECK_DATES"] == "Y") {
	$arFilter["ACTIVE_DATE"] = "Y";
}

$arNavParams = array(
	"nPageSize" => 1,
	"nElementID" => $arResult["ID"],
);

$arItems = Array();
$rsElement = CIBlockElement::GetList($arSort, $arFilter, false, $arNavParams, $arSelect);
$rsElement->SetUrlTemplates($arParams["DETAIL_URL"]);
while($obElement = $rsElement->GetNextElement())
	$arItems[] = $obElement->GetFields();
	
if(count($arItems)==3):
	
	$arResult["TORIGHT"] = Array(
		"NAME" => $arItems[0]["NAME"], 
		"URL" => $arItems[0]["DETAIL_PAGE_URL"], 
		"DISPLAY_ACTIVE_FROM" => CIBlockFormatProperties::DateFormat($arParams["ACTIVE_DATE_FORMAT"], MakeTimeStamp($arItems[0]["DATE_ACTIVE_FROM"], CSite::GetDateFormat())),
	);
	$arResult["TOLEFT"] = Array(
		"NAME" => $arItems[2]["NAME"], 
		"URL" => $arItems[2]["DETAIL_PAGE_URL"], 
		"DISPLAY_ACTIVE_FROM" => CIBlockFormatProperties::DateFormat($arParams["ACTIVE_DATE_FORMAT"], MakeTimeStamp($arItems[2]["DATE_ACTIVE_FROM"], CSite::GetDateFormat())),
	);

elseif(count($arItems)==2):
	
	if($arItems[0]["ID"]!=$arResult["ID"])
		$arResult["TORIGHT"] = Array(
			"NAME" => $arItems[0]["NAME"], 
			"URL" => $arItems[0]["DETAIL_PAGE_URL"], 
			"DISPLAY_ACTIVE_FROM" => CIBlockFormatProperties::DateFormat($arParams["ACTIVE_DATE_FORMAT"], MakeTimeStamp($arItems[0]["DATE_ACTIVE_FROM"], CSite::GetDateFormat())),
		);
	else
		$arResult["TOLEFT"] = Array(
			"NAME" => $arItems[1]["NAME"], 
			"URL" => $arItems[1]["DETAIL_PAGE_URL"], 
			"DISPLAY_ACTIVE_FROM" => CIBlockFormatProperties::DateFormat($arParams["ACTIVE_DATE_FORMAT"], MakeTimeStamp($arItems[1]["DATE_ACTIVE_FROM"], CSite::GetDateFormat())),
		);

endif;?>