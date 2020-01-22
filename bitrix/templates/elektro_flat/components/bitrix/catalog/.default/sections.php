<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);?>

<?$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "",
	Array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"TOP_DEPTH" => 2,
		"DISPLAY_IMG_WIDTH"	 =>	50,
		"DISPLAY_IMG_HEIGHT" =>	50,
		"SHARPEN" => $arParams["SHARPEN"],
	),
	$component
);?>

<div class="clr"></div>
<div class="catalog-section-descr">	
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/catalog_descr.php"), false);?>
</div>