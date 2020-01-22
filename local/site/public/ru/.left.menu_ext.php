<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;

$aMenuLinksExt = $APPLICATION->IncludeComponent("bitrix:menu.sections", "", 
	array(
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "#ITEMS_IBLOCK_ID#",
		"DEPTH_LEVEL" => "4",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "86400"
	),
	false,
	Array('HIDE_ICONS' => 'Y')
);
$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);?>