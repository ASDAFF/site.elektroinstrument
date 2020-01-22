<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
	
if(!CModule::IncludeModule('iblock'))
	return;	

$arElement = CIBlockElement::GetList(
	array(), 
	array("=ID" => $arParams["NOTIFY_ID"]), 
	false, 
	false, 
	array("ID", "IBLOCK_ID", "NAME", "DETAIL_PICTURE", "PROPERTY_CML2_LINK")
)->Fetch();	

$arResult["NAME"] = $arElement["NAME"];

if($arElement["DETAIL_PICTURE"] > 0) {
	$arFileTmp = CFile::ResizeImageGet(
		$arElement["DETAIL_PICTURE"],
		array("width" => 178, "height" => 178),
		BX_RESIZE_IMAGE_PROPORTIONAL,
		true
	);		
	$arResult["PREVIEW_IMG"] = array(
		"SRC" => $arFileTmp["src"],
		"WIDTH" => $arFileTmp["width"],
		"HEIGHT" => $arFileTmp["height"],
	);
} else {
	if(!empty($arElement["PROPERTY_CML2_LINK_VALUE"])) {
		$arElement2 = CIBlockElement::GetList(
			array(), 
			array("=ID" => $arElement["PROPERTY_CML2_LINK_VALUE"]), 
			false, 
			false, 
			array("NAME", "DETAIL_PICTURE")
		)->Fetch();
		if($arElement2["DETAIL_PICTURE"] > 0) {				
			$arFileTmp = CFile::ResizeImageGet(
				$arElement2["DETAIL_PICTURE"],
				array("width" => 178, "height" => 178),
				BX_RESIZE_IMAGE_PROPORTIONAL,
				true
			);		
			$arResult["PREVIEW_IMG"] = array(
				"SRC" => $arFileTmp["src"],
				"WIDTH" => $arFileTmp["width"],
				"HEIGHT" => $arFileTmp["height"],
			);
		}
	}
}?>