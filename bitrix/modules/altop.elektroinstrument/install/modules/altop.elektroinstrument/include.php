<?IncludeModuleLangFile(__FILE__);

//initialize module parametrs list and default values
require_once __DIR__ .'/parametrs.php';

class CElektroinstrument {
	const moduleID = "altop.elektroinstrument";

	static $arParametrsList = array();

	function GetBackParametrsValues($SITE_ID, $bStatic = true){
		if($bStatic){
			static $arValues;
		}
		if($bStatic && $arValues === NULL || !$bStatic){
			$arDefaultValues = $arValues = array();
			if(self::$arParametrsList && is_array(self::$arParametrsList)){
				foreach(self::$arParametrsList as $blockCode => $arBlock){
					if($arBlock["OPTIONS"] && is_array($arBlock["OPTIONS"])){
						foreach($arBlock["OPTIONS"] as $optionCode => $arOption){
							$arDefaultValues[$optionCode] = $arOption["DEFAULT"];
						}
					}
				}
			}
			$arValues = unserialize(COption::GetOptionString(self::moduleID, "OPTIONS", serialize(array()), $SITE_ID));		
			if($arValues && is_array($arValues)){
				foreach($arValues as $optionCode => $arOption){
					if(!isset($arDefaultValues[$optionCode])){
						unset($arValues[$optionCode]);
					}
				}
			}
			if($arDefaultValues && is_array($arDefaultValues)){
				foreach($arDefaultValues as $optionCode => $arOption){
					if(!isset($arValues[$optionCode])){
						$arValues[$optionCode] = $arOption;
					}
				}
			}
		}
		return $arValues;
	}

	function GetFrontParametrsValues($SITE_ID){
		if(!strlen($SITE_ID)) $SITE_ID = SITE_ID;
		$arBackParametrs = self::GetBackParametrsValues($SITE_ID);		
		$arValues = (array)$arBackParametrs;
		return $arValues;
	}

	function ShowPanel() {
		if($GLOBALS["USER"]->IsAdmin() && COption::GetOptionString("main", "wizard_solution", "", SITE_ID) == "elektroinstrument") {
			$GLOBALS["APPLICATION"]->SetAdditionalCSS("/bitrix/wizards/bitrix/elektroinstrument/css/panel.css"); 

			$arMenu = Array(
				Array(		
					"ACTION" => "jsUtils.Redirect([], '".CUtil::JSEscape("/bitrix/admin/wizard_install.php?lang=".LANGUAGE_ID."&wizardSiteID=".SITE_ID."&wizardName=altop.elektroinstrument&".bitrix_sessid_get())."')",
					"ICON" => "bx-popup-item-wizard-icon",
					"TITLE" => GetMessage("STOM_BUTTON_TITLE_W1"),
					"TEXT" => GetMessage("STOM_BUTTON_NAME_W1"),
				),
			);
			
			$GLOBALS["APPLICATION"]->AddPanelButton(array(
				"HREF" => "/bitrix/admin/wizard_install.php?lang=".LANGUAGE_ID."&wizardName=altop.elektroinstrument&wizardSiteID=".SITE_ID."&".bitrix_sessid_get(),
				"ID" => "elektroinstrument_wizard",
				"ICON" => "bx-panel-site-wizard-icon",
				"MAIN_SORT" => 2500,
				"TYPE" => "BIG",
				"SORT" => 10,	
				"ALT" => GetMessage("SCOM_BUTTON_DESCRIPTION"),
				"TEXT" => GetMessage("SCOM_BUTTON_NAME"),
				"MENU" => $arMenu,
			));
		}
	}	

	function IsCompositeEnabled(){
		if(class_exists("CHTMLPagesCache")){
			if(method_exists("CHTMLPagesCache", "GetOptions")){
				if($arHTMLCacheOptions = CHTMLPagesCache::GetOptions()){
					if($arHTMLCacheOptions["COMPOSITE"] == "Y"){
						return true;
					}
				}
			}
		}
		return false;
	}
	
	function EnableComposite(){
		if(class_exists("CHTMLPagesCache")){
			if(method_exists("CHTMLPagesCache", "GetOptions")){
				if($arHTMLCacheOptions = CHTMLPagesCache::GetOptions()){
					$arHTMLCacheOptions["COMPOSITE"] = "Y";
					$arHTMLCacheOptions["DOMAINS"] = array_merge((array)$arHTMLCacheOptions["DOMAINS"], (array)$arDomains);
					CHTMLPagesCache::SetEnabled(true);
					CHTMLPagesCache::SetOptions($arHTMLCacheOptions);
					bx_accelerator_reset();
				}
			}
		}
	}
}?>