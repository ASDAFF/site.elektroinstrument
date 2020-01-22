<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("catalog"))
	return;

if(COption::GetOptionString("altop.elektroinstrument", "wizard_installed", "N", WIZARD_SITE_ID) == "Y" && !WIZARD_INSTALL_DEMO_DATA)
	return;

//catalog iblock import
$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/".LANGUAGE_ID."/models.xml";
$iblockXMLFilePrices = WIZARD_SERVICE_RELATIVE_PATH."/xml/".LANGUAGE_ID."/models_price.xml";

$iblockCode = "catalog_".WIZARD_SITE_ID;
$iblockType = "catalog";

$rsIBlock = CIBlock::GetList(array(), array("XML_ID" => $iblockCode, "TYPE" => $iblockType));
$IBLOCK_CATALOG_ID = false;

if($arIBlock = $rsIBlock->Fetch()) {
	$IBLOCK_CATALOG_ID = $arIBlock["ID"];
}

if(WIZARD_INSTALL_DEMO_DATA && $IBLOCK_CATALOG_ID) {
	$boolFlag = true;
	$arSKU = CCatalogSKU::GetInfoByProductIBlock($IBLOCK_CATALOG_ID);
	if(!empty($arSKU)) {
		$boolFlag = CCatalog::UnLinkSKUIBlock($IBLOCK_CATALOG_ID);
		if(!$boolFlag) {
			$strError = "";
			if($ex = $APPLICATION->GetException()) {
				$strError = $ex->GetString();
			} else {
				$strError = "Couldn't unlink iblocks";
			}
		}
		$boolFlag = CIBlock::Delete($arSKU['IBLOCK_ID']);
		if(!$boolFlag) {
			$strError = "";
			if($ex = $APPLICATION->GetException()) {
				$strError = $ex->GetString();
			} else {
				$strError = "Couldn't delete offers iblock";
			}
		}
	}
	if($boolFlag) {
		$boolFlag = CIBlock::Delete($IBLOCK_CATALOG_ID);
		if(!$boolFlag) {
			$strError = "";
			if($ex = $APPLICATION->GetException()) {
				$strError = $ex->GetString();
			} else {
				$strError = "Couldn't delete catalog iblock";
			}
		}
	}
	if($boolFlag) {
		$IBLOCK_CATALOG_ID = false;
	}
}

if($IBLOCK_CATALOG_ID == false) {
	
	$permissions = Array(
		"1" => "X",
		"2" => "R"
	);
	$dbGroup = CGroup::GetList($by = "", $order = "", Array("STRING_ID" => "sale_administrator"));
	if($arGroup = $dbGroup -> Fetch()) {
		$permissions[$arGroup["ID"]] = 'W';
	}
	$dbGroup = CGroup::GetList($by = "", $order = "", Array("STRING_ID" => "content_editor"));
	if($arGroup = $dbGroup -> Fetch()) {
		$permissions[$arGroup["ID"]] = 'W';
	}
	
	$IBLOCK_CATALOG_ID = WizardServices::ImportIBlockFromXML(
		$iblockXMLFile,
		$iblockCode,
		$iblockType,
		WIZARD_SITE_ID,
		$permissions
	);
	$IBLOCK_CATALOG_ID1 = WizardServices::ImportIBlockFromXML(
		$iblockXMLFilePrices,
		$iblockCode,
		$iblockType."_prices",
		WIZARD_SITE_ID,
		$permissions
	);

	if($IBLOCK_CATALOG_ID < 1)
		return;

	$_SESSION["WIZARD_CATALOG_IBLOCK_ID"] = $IBLOCK_CATALOG_ID;

} else {
	
	$arSites = array();
	$db_res = CIBlock::GetSite($IBLOCK_CATALOG_ID);
	while($res = $db_res->Fetch())
		$arSites[] = $res["LID"];
	if(!in_array(WIZARD_SITE_ID, $arSites)) {
		$arSites[] = WIZARD_SITE_ID;
		$iblock = new CIBlock;
		$iblock->Update($IBLOCK_CATALOG_ID, array("LID" => $arSites));
	}

}?>