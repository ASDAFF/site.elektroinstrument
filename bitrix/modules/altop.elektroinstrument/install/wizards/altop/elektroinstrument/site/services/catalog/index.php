<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$curSiteSubscribe = array("use" => "Y", "del_after" => "100");
$subscribe = COption::GetOptionString("sale", "subscribe_prod", "");
$arSubscribe = unserialize($subscribe);
$arSubscribe[WIZARD_SITE_ID] = $curSiteSubscribe;
COption::SetOptionString("sale", "subscribe_prod", serialize($arSubscribe));
	
if(CModule::IncludeModule("catalog")) {
	$arStores = array();
	
	$dbStore= CCatalogStore::GetList(array(), array("XML_ID" => "shop_1"), false, false, array("ID"));
	if(!$arStore = $dbStore->Fetch())
	    $arNewStores[] =  array(
			"TITLE" => GetMessage("STORE_NAME_1"),
			"ACTIVE" => "Y",
			"ADDRESS" => GetMessage("STORE_ADR_1"),
			"DESCRIPTION" => "",
			"USER_ID" => $USER->GetID(),
			"GPS_N" => GetMessage("STORE_GPS_N_1"),
			"GPS_S" => GetMessage("STORE_GPS_S_1"),
			"PHONE" => "",
			"SCHEDULE" => GetMessage("STORE_SCHEDULE_1"),
			"XML_ID" => "shop_1",
		);
	
	$dbStore= CCatalogStore::GetList(array(), array("XML_ID" => "shop_2"), false, false, array("ID"));
	if(!$arStore = $dbStore->Fetch())
		$arNewStores[] = array(
			"TITLE" => GetMessage("STORE_NAME_2"),
			"ACTIVE" => "Y",
			"ADDRESS" => GetMessage("STORE_ADR_2"),
			"DESCRIPTION" => "",
			"USER_ID" => $USER->GetID(),
			"GPS_N" => GetMessage("STORE_GPS_N_2"),
			"GPS_S" => GetMessage("STORE_GPS_S_2"),
			"PHONE" => "",
			"SCHEDULE" => GetMessage("STORE_SCHEDULE_2"),
			"XML_ID" => "shop_2",
		); 
		
	if(count($arNewStores) > 0)
		foreach($arNewStores as $arFields)           
			CCatalogStore::Add($arFields);
}

if(COption::GetOptionString("elektroinstrument", "wizard_installed", "N", WIZARD_SITE_ID) == "Y" && !WIZARD_INSTALL_DEMO_DATA)
	return;

COption::SetOptionString("catalog", "default_quantity_trace", "Y");
COption::SetOptionString("catalog", "default_can_buy_zero", "N");
COption::SetOptionString("catalog", "allow_negative_amount", "N");
?>