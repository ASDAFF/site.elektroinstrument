<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$moduleClass = "CElektroinstrument";
$moduleID = "altop.elektroinstrument";

if(!CModule::IncludeModule($moduleID))
	return;

if($_SERVER["REQUEST_METHOD"] == "POST" && check_bitrix_sessid("sessid-style-switcher")) {		
	foreach($moduleClass::$arParametrsList as $blockCode => $arBlock){
		foreach($arBlock["OPTIONS"] as $optionCode => $arOption) {
			if($arOption["IN_SETTINGS_PANEL"] === "Y") {
				if($_REQUEST["THEME"] === "default") {
					$newVal = $arOption["DEFAULT"];					
				} else {
					$newVal = $_REQUEST[$optionCode];
					if($arOption["TYPE"] == "multiselectbox") {
						if(!is_array($newVal))
							$newVal = array();
					}
				}
				$arTab["OPTIONS"][$optionCode] = $newVal;
			}
		}
	}		
	COption::SetOptionString($moduleID, "OPTIONS", serialize((array)$arTab["OPTIONS"]), "", SITE_ID);

	if($moduleClass::IsCompositeEnabled()){
		$obCache = new CPHPCache();
		$obCache->CleanDir("", "html_pages");
		$moduleClass::EnableComposite();
	}

	BXClearCache(true, "/".SITE_ID."/altop/catalog.top/");
	BXClearCache(true, "/".SITE_ID."/bitrix/catalog.bigdata.products/");
	BXClearCache(true, "/".SITE_ID."/bitrix/catalog.section/");
	BXClearCache(true, "/".SITE_ID."/bitrix/catalog.element/");
}

global $USER;
$arResult = array();

$arFrontParametrs = $moduleClass::GetFrontParametrsValues(SITE_ID);
foreach($moduleClass::$arParametrsList as $blockCode => $arBlock){
	foreach($arBlock["OPTIONS"] as $optionCode => $arOption){
		$arResult[$optionCode] = $arOption;
		$arResult[$optionCode]["VALUE"] = $arFrontParametrs[$optionCode];
		//CURRENT for compatibility with old versions
		if($arResult[$optionCode]["LIST"]){
			foreach($arResult[$optionCode]["LIST"] as $variantCode => $variantTitle){
				if(!is_array($variantTitle)){
					$arResult[$optionCode]["LIST"][$variantCode] = array("TITLE" => $variantTitle);
				}
				if($arResult[$optionCode]["TYPE"] == "selectbox"){
					if($arResult[$optionCode]["VALUE"] == $variantCode){
						$arResult[$optionCode]["LIST"][$variantCode]["CURRENT"] = "Y";
					}
				} elseif($arResult[$optionCode]["TYPE"] == "multiselectbox"){
					if(in_array($variantCode, $arResult[$optionCode]["VALUE"])){
						$arResult[$optionCode]["LIST"][$variantCode]["CURRENT"] = "Y";
					}
				}
			}
		}
	}
}

if($arResult["SHOW_SETTINGS_PANEL"]["VALUE"] == "Y") {
	if($USER->IsAdmin()) {
		$this->IncludeComponentTemplate();
	}
}

return $arResult;?>