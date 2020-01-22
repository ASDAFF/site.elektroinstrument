<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule("iblock"))
	return;

if(empty($arResult))
	return;

foreach($arResult as $key => $arItem) {
	if($arItem["DEPTH_LEVEL"] == 1) {
		continue;
	} elseif($arItem["DEPTH_LEVEL"] == 2) {		
		$dbSections = CIBlockSection::GetList(array(), array("NAME" => $arItem["TEXT"]), false, array("PICTURE"));
		while($arSections = $dbSections->GetNext()) {
			if(!empty($arSections["PICTURE"])) {
				$arFileTmp = CFile::ResizeImageGet(
					$arSections['PICTURE'],
					array("width" => 50, "height" => 50),
					BX_RESIZE_IMAGE_PROPORTIONAL,
					true
				);
				$arResult[$key]["PICTURE"] = array(
					'SRC' => $arFileTmp["src"],
					'WIDTH' => $arFileTmp["width"],
					'HEIGHT' => $arFileTmp["height"],
				);
			}
		}		
	} elseif($arItem["DEPTH_LEVEL"] > 2) {
		unset($arResult[$key]);
	}
}?>