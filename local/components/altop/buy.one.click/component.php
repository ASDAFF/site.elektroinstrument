<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule('iblock') || !CModule::IncludeModule('catalog'))
	return;

if(intval($arParams['IBLOCK_ID']) < 1)
  return;

if(empty($arParams['REQUIRED_ORDER_FIELDS']))
	$arParams['REQUIRED_ORDER_FIELDS'] = array('NAME', 'TEL');

if(empty($arParams['DEFAULT_ORDER_PROP_NAME']))
	$arParams['DEFAULT_ORDER_PROP_NAME'] = 1;
if(empty($arParams['DEFAULT_ORDER_PROP_TEL']))
	$arParams['DEFAULT_ORDER_PROP_TEL'] = 3;
if(empty($arParams['DEFAULT_ORDER_PROP_EMAIL']))
	$arParams['DEFAULT_ORDER_PROP_EMAIL'] = 2;

if(!empty($arParams['DUPLICATE_LETTER_TO_EMAILS']))
	foreach($arParams['DUPLICATE_LETTER_TO_EMAILS'] as $item)
		$arParams['DUB'] .= $item;

$arParams['ELEMENT_ID'] = intval($arParams['ELEMENT_ID']);

$arResult = array();

$arElement = CIBlockElement::GetList(
	array(), 
	array("=ID" => $arParams["ELEMENT_ID"]), 
	false, 
	false, 
	array("ID", "IBLOCK_ID", "NAME", "DETAIL_PICTURE", "PROPERTY_CML2_LINK")
)->Fetch();	

$arResult["ELEMENT_NAME"] = $arElement["NAME"];

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
}

if(!$USER->IsAuthorized()) {
	$arResult["CAPTCHA_CODE"] = htmlspecialchars($APPLICATION->CaptchaGetCode());
}
	
$arResult["REQUIRED"] = implode("/", $arParams["REQUIRED_ORDER_FIELDS"]);

if($USER->IsAuthorized()) {
	$arResult['NAME'] = htmlspecialcharsEx($USER->GetFullName());	
	$arResult['EMAIL'] = htmlspecialcharsEx($USER->GetEmail());
}

$this->IncludeComponentTemplate();?>