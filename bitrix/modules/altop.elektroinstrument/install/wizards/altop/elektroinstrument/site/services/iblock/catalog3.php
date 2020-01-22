<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("catalog"))
	return;

if(COption::GetOptionString("altop.elektroinstrument", "wizard_installed", "N", WIZARD_SITE_ID) == "Y" && !WIZARD_INSTALL_DEMO_DATA)
	return;

//update iblocks, user fields, demo discount, related properties
if($_SESSION["WIZARD_CATALOG_IBLOCK_ID"]) {
	$IBLOCK_CATALOG_ID = $_SESSION["WIZARD_CATALOG_IBLOCK_ID"];
	unset($_SESSION["WIZARD_CATALOG_IBLOCK_ID"]);
}

if($_SESSION["WIZARD_OFFERS_IBLOCK_ID"]) {
	$IBLOCK_OFFERS_ID = $_SESSION["WIZARD_OFFERS_IBLOCK_ID"];
	unset($_SESSION["WIZARD_OFFERS_IBLOCK_ID"]);
}

if($IBLOCK_OFFERS_ID) {
	$iblockCodeOffers = "offers_".WIZARD_SITE_ID;
	//IBlock fields
	$iblock = new CIBlock;
	$arFields = Array(
		"ACTIVE" => "Y",
		"FIELDS" => array(),
		"CODE" => $iblockCodeOffers,
		"XML_ID" => $iblockCodeOffers
	);
	$iblock->Update($IBLOCK_OFFERS_ID, $arFields);
}

if($IBLOCK_CATALOG_ID) {
	$iblockCode = "catalog_".WIZARD_SITE_ID;
	//IBlock fields
	$iblock = new CIBlock;
	$arFields = Array(
		"ACTIVE" => "Y",
		"FIELDS" => array ( 'CODE' => array ( 'IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => array ( 'UNIQUE' => 'Y', 'TRANSLITERATION' => 'Y', 'TRANS_LEN' => 100, 'TRANS_CASE' => 'L', 'TRANS_SPACE' => '_', 'TRANS_OTHER' => '_', 'TRANS_EAT' => 'Y', 'USE_GOOGLE' => 'N', ), ), 'TAGS' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 'SECTION_CODE' => array ( 'IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => array ( 'UNIQUE' => 'Y', 'TRANSLITERATION' => 'Y', 'TRANS_LEN' => 100, 'TRANS_CASE' => 'L', 'TRANS_SPACE' => '_', 'TRANS_OTHER' => '_', 'TRANS_EAT' => 'Y', 'USE_GOOGLE' => 'N', ), ), ),
		"CODE" => $iblockCode,
		"XML_ID" => $iblockCode
	);
	$iblock->Update($IBLOCK_CATALOG_ID, $arFields);

	if($IBLOCK_OFFERS_ID) {
		$ID_SKU = CCatalog::LinkSKUIBlock($IBLOCK_CATALOG_ID, $IBLOCK_OFFERS_ID);
		$rsCatalogs = CCatalog::GetList(
			array(),
			array('IBLOCK_ID' => $IBLOCK_OFFERS_ID),
			false,
			false,
			array('IBLOCK_ID')
		);
		if($arCatalog = $rsCatalogs->Fetch()) {
			CCatalog::Update($IBLOCK_OFFERS_ID, array('PRODUCT_IBLOCK_ID' => $IBLOCK_CATALOG_ID, 'SKU_PROPERTY_ID' => $ID_SKU));
		} else {
			CCatalog::Add(array('IBLOCK_ID' => $IBLOCK_OFFERS_ID, 'PRODUCT_IBLOCK_ID' => $IBLOCK_CATALOG_ID, 'SKU_PROPERTY_ID' => $ID_SKU));
		}
	}

	if(!CCatalog::GetByID($IBLOCK_CATALOG_ID))
		CCatalog::Add(array("IBLOCK_ID" => $IBLOCK_CATALOG_ID));

	//create facet index
	$index = \Bitrix\Iblock\PropertyIndex\Manager::createIndexer($IBLOCK_CATALOG_ID);
	$index->startIndex();
	$index->continueIndex(0);
	$index->endIndex();

	$index = \Bitrix\Iblock\PropertyIndex\Manager::createIndexer($IBLOCK_OFFERS_ID);
	$index->startIndex();
	$index->continueIndex(0);
	$index->endIndex();

	\Bitrix\Iblock\PropertyIndex\Manager::checkAdminNotification();
	
	//user fields for sections	
	$arLanguages = Array();
	$rsLanguage = CLanguage::GetList($by, $order, array());
	while($arLanguage = $rsLanguage->Fetch())
		$arLanguages[] = $arLanguage["LID"];
		
	$arUserFields = array("UF_BROWSER_TITLE", "UF_KEYWORDS", "UF_META_DESCRIPTION", "UF_SECTION_TITLE");
	foreach($arUserFields as $userField) {
		$arLabelNames = Array();
		foreach($arLanguages as $languageID) {
			WizardServices::IncludeServiceLang("property_names.php", $languageID);
			$arLabelNames[$languageID] = GetMessage($userField);
		}
		
		$arProperty["EDIT_FORM_LABEL"] = $arLabelNames;
		$arProperty["LIST_COLUMN_LABEL"] = $arLabelNames;
		$arProperty["LIST_FILTER_LABEL"] = $arLabelNames;
		
		$dbRes = CUserTypeEntity::GetList(Array(), Array("ENTITY_ID" => 'IBLOCK_'.$IBLOCK_CATALOG_ID.'_SECTION', "FIELD_NAME" => $userField));
		if($arRes = $dbRes->Fetch()) {
			$userType = new CUserTypeEntity();
			$userType->Update($arRes["ID"], $arProperty);
		}
	}
	
	//demo discount
	$dbDiscount = CCatalogDiscount::GetList(array(), Array("SITE_ID" => WIZARD_SITE_ID));
	if(!($dbDiscount->Fetch())) {
		if(CModule::IncludeModule("iblock")) {
			$properties = CIBlockProperty::GetPropertyEnum("DISCOUNT", Array(), Array("IBLOCK_ID" => $IBLOCK_CATALOG_ID, "!VALUE" => false));
			if($prop_fields = $properties->GetNext())
				$arProp["ID"] = $prop_fields["PROPERTY_ID"];
				$arProp["VALUE"] = $prop_fields["ID"];
		}
		
		$dbSite = CSite::GetByID(WIZARD_SITE_ID);
		if($arSite = $dbSite -> Fetch())
			$lang = $arSite["LANGUAGE_ID"];
		$defCurrency = "EUR";
		if($lang == "ru")
			$defCurrency = "RUB";
		elseif($lang == "en")
			$defCurrency = "USD";
		
		$arF = Array (
			"SITE_ID" => WIZARD_SITE_ID,
			"ACTIVE" => "Y",
			"RENEWAL" => "N",
			"NAME" => GetMessage("WIZ_DISCOUNT"),
			"SORT" => 100,
			"MAX_DISCOUNT" => 0,
			"VALUE_TYPE" => "P",
			"VALUE" => 10,
			"CURRENCY" => $defCurrency,
			"CONDITIONS" => Array (
				"CLASS_ID" => "CondGroup",
				"DATA" => Array("All" => "OR", "True" => "True"),
				"CHILDREN" => Array(Array("CLASS_ID" => "CondIBProp:".$IBLOCK_CATALOG_ID.":".$arProp["ID"], "DATA" => Array("logic" => "Equal", "value" => $arProp["VALUE"])))
			)
		);
		CCatalogDiscount::Add($arF);
	}

	//Related properties
	$arProp4Link = array(
		"MANUFACTURER" => "vendors",
		"ACCESSORIES" => "catalog"
	);

	$arProp4LinkSF = array(
		"MANUFACTURER" => "Y",
		"ACCESSORIES" => "N"
	);

	$dbProp = CIBlockProperty::GetList(array(), array('IBLOCK_ID' => $IBLOCK_CATALOG_ID));
	while($arProp = $dbProp->Fetch()){
		if(!array_key_exists($arProp['CODE'], $arProp4Link))
			continue;

		$rsIblock = CIBlock::GetList(array(), array("CODE" => $arProp4Link[$arProp['CODE']]."_".WIZARD_SITE_ID, "XML_ID" => $arProp4Link[$arProp['CODE']]."_".WIZARD_SITE_ID, "TYPE" => 'catalog'));
		if($arIblock = $rsIblock->Fetch()){
			$arFieldsUpdate = Array(
				"LINK_IBLOCK_ID" => $arIblock['ID'],
				"IBLOCK_ID" => $IBLOCK_CATALOG_ID,
				"SMART_FILTER" => $arProp4LinkSF[$arProp['CODE']]
			);

			$ibp = new CIBlockProperty;
			if(!$ibp->Update($arProp['ID'], $arFieldsUpdate))
				return;
		}
	}

	if($IBLOCK_OFFERS_ID) {
		$arProp4LinkOffers = array(
			"COLOR" => "colors"
		);

		$arProp4LinkSFOffers = array(
			"COLOR" => "Y"
		);

		$dbPropOffers = CIBlockProperty::GetList(array(), array('IBLOCK_ID' => $IBLOCK_OFFERS_ID));
		while($arPropOffers = $dbPropOffers->Fetch()){
			if(!array_key_exists($arPropOffers['CODE'], $arProp4LinkOffers))
				continue;

			$rsIblockOffers = CIBlock::GetList(array(), array("CODE" => $arProp4LinkOffers[$arPropOffers['CODE']]."_".WIZARD_SITE_ID, "XML_ID" => $arProp4LinkOffers[$arPropOffers['CODE']]."_".WIZARD_SITE_ID, "TYPE" => 'catalog'));
			if($arIblockOffers = $rsIblockOffers->Fetch()){
				$arFieldsUpdateOffers = Array(
					"LINK_IBLOCK_ID" => $arIblockOffers['ID'],
					"IBLOCK_ID" => $IBLOCK_OFFERS_ID,
					"SMART_FILTER" => $arProp4LinkSFOffers[$arPropOffers['CODE']]
				);

				$ibpOffers = new CIBlockProperty;
				if(!$ibpOffers->Update($arPropOffers['ID'], $arFieldsUpdateOffers))
					return;
			}
		}
	}

	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/catalog/index.php", array("ITEMS_IBLOCK_ID" => $IBLOCK_CATALOG_ID));
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/catalog/newproduct/index.php", array("ITEMS_IBLOCK_ID" => $IBLOCK_CATALOG_ID));
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/catalog/saleleader/index.php", array("ITEMS_IBLOCK_ID" => $IBLOCK_CATALOG_ID));
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/catalog/discount/index.php", array("ITEMS_IBLOCK_ID" => $IBLOCK_CATALOG_ID));

	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/include/newproduct.php", array("ITEMS_IBLOCK_ID" => $IBLOCK_CATALOG_ID));
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/include/saleleader.php", array("ITEMS_IBLOCK_ID" => $IBLOCK_CATALOG_ID));
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/include/discount.php", array("ITEMS_IBLOCK_ID" => $IBLOCK_CATALOG_ID));
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/include/discount_left.php", array("ITEMS_IBLOCK_ID" => $IBLOCK_CATALOG_ID));
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/include/header_search.php", array("ITEMS_IBLOCK_ID" => $IBLOCK_CATALOG_ID));
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/include/footer_compare.php", array("ITEMS_IBLOCK_ID" => $IBLOCK_CATALOG_ID));
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/include/sections.php", array("ITEMS_IBLOCK_ID" => $IBLOCK_CATALOG_ID));
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/include/viewed_products.php", array("ITEMS_IBLOCK_ID" => $IBLOCK_CATALOG_ID, "OFFERS_IBLOCK_ID" => $IBLOCK_OFFERS_ID));

	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/.left.menu_ext.php", array("ITEMS_IBLOCK_ID" => $IBLOCK_CATALOG_ID));
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/ajax/compare_line.php", array("ITEMS_IBLOCK_ID" => $IBLOCK_CATALOG_ID));
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/personal/cart/index.php", array("ITEMS_IBLOCK_ID" => $IBLOCK_CATALOG_ID));
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/vendors/index.php", array("ITEMS_IBLOCK_ID" => $IBLOCK_CATALOG_ID));
}?>