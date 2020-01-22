<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

foreach($arResult["SECTIONS"] as $key => $arSection) {
	/***SECTION_ITEMS***/
	$rsElements = CIBlockElement::GetList(array("SORT" => "ASC", "NAME" => "ASC"), array("IBLOCK_ID" => $arSection["IBLOCK_ID"], "SECTION_ID" => $arSection["ID"], "INCLUDE_SUBSECTIONS" => "N", "ACTIVE" => "Y"), false, false, array("ID", "IBLOCK_ID", "NAME", "PREVIEW_TEXT", "PROPERTY_LOGO_1", "PROPERTY_LOGO_2", "PROPERTY_URL"));
	while($obElement = $rsElements->GetNextElement()) {
		$arItem = $obElement->GetFields();
		
		if(isset($arItem["PROPERTY_LOGO_1_VALUE"]) && $arItem["PROPERTY_LOGO_1_VALUE"] > 0) {
			$arFileTmp = CFile::ResizeImageGet(
				$arItem["PROPERTY_LOGO_1_VALUE"],
				array("width" => 66, "height" => 30),
				BX_RESIZE_IMAGE_PROPORTIONAL,
				true
			);

			$arItem["LOGO_1"] = array(
				"SRC" => $arFileTmp["src"],
				"WIDTH" => $arFileTmp["width"],
				"HEIGHT" => $arFileTmp["height"],
			);				
		}

		if(isset($arItem["PROPERTY_LOGO_2_VALUE"]) && $arItem["PROPERTY_LOGO_2_VALUE"] > 0) {
			$arFileTmp = CFile::ResizeImageGet(
				$arItem["PROPERTY_LOGO_2_VALUE"],
				array("width" => 66, "height" => 30),
				BX_RESIZE_IMAGE_PROPORTIONAL,
				true
			);

			$arItem["LOGO_2"] = array(
				"SRC" => $arFileTmp["src"],
				"WIDTH" => $arFileTmp["width"],
				"HEIGHT" => $arFileTmp["height"],
			);
		}
		
		$arResult["SECTIONS"][$key]["ITEMS"][] = $arItem;			
	}	
}?>