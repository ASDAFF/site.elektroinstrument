<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if(!CModule::IncludeModule('sale'))
	return;

$arGeneralInfo = Array();

$dbSite = CSite::GetByID(WIZARD_SITE_ID);
if($arSite = $dbSite -> Fetch())
	$lang = $arSite["LANGUAGE_ID"];

if(strlen($lang) <= 0)
	$lang = "ru";

$bRus = false;
if($lang == "ru")
	$bRus = true;

$defCurrency = "EUR";
if($lang == "ru")
	$defCurrency = "RUB";
elseif($lang == "en")
	$defCurrency = "USD";
	
$arLanguages = Array();
$rsLanguage = CLanguage::GetList($by, $order, array());
while($arLanguage = $rsLanguage->Fetch())
	$arLanguages[] = $arLanguage["LID"];

WizardServices::IncludeServiceLang("step1.php", $lang);

if($bRus || COption::GetOptionString("altop.elektroinstrument", "wizard_installed", "N", WIZARD_SITE_ID) != "Y" || WIZARD_INSTALL_DEMO_DATA) {
	$personType = $wizard->GetVar("personType");
	$paysystem = $wizard->GetVar("paysystem");
	
	$shopOfName = $wizard->GetVar("shopOfName");
	COption::SetOptionString("elektroinstrument", "shopOfName", $shopOfName, false, WIZARD_SITE_ID);
	$shopLocation = $wizard->GetVar("shopLocation");
	COption::SetOptionString("elektroinstrument", "shopLocation", $shopLocation, false, WIZARD_SITE_ID);
	$shopINN = $wizard->GetVar("shopINN");
	COption::SetOptionString("elektroinstrument", "shopINN", $shopINN, false, WIZARD_SITE_ID);
	$shopKPP = $wizard->GetVar("shopKPP");
	COption::SetOptionString("elektroinstrument", "shopKPP", $shopKPP, false, WIZARD_SITE_ID);
	$shopNS = $wizard->GetVar("shopNS");
	COption::SetOptionString("elektroinstrument", "shopNS", $shopNS, false, WIZARD_SITE_ID);
	$shopBANK = $wizard->GetVar("shopBANK");
	COption::SetOptionString("elektroinstrument", "shopBANK", $shopBANK, false, WIZARD_SITE_ID);
	$shopBANKREKV = $wizard->GetVar("shopBANKREKV");
	COption::SetOptionString("elektroinstrument", "shopBANKREKV", $shopBANKREKV, false, WIZARD_SITE_ID);
	$shopKS = $wizard->GetVar("shopKS");
	COption::SetOptionString("elektroinstrument", "shopKS", $shopKS, false, WIZARD_SITE_ID);
	$siteStamp = $wizard->GetVar("siteStamp");
	COption::SetOptionString("elektroinstrument", "siteStamp", $siteStamp, false, WIZARD_SITE_ID);
	$shopAdr = $wizard->GetVar("shopAdr");
	COption::SetOptionString("elektroinstrument", "shopAdr", $shopAdr, false, WIZARD_SITE_ID);
	$siteTelephone = $wizard->GetVar("siteTelephone");
	COption::SetOptionString("elektroinstrument", "siteTelephone", $siteTelephone, false, WIZARD_SITE_ID);
	$shopEmail = $wizard->GetVar("shopEmail");
	COption::SetOptionString("elektroinstrument", "shopEmail", $shopEmail, false, WIZARD_SITE_ID);
	$siteName = $wizard->GetVar("siteName");
	COption::SetOptionString("elektroinstrument", "siteName", $siteName, false, WIZARD_SITE_ID);
	$delivery = $wizard->GetVar("delivery");
	
	$obSite = new CSite;
	$obSite->Update(WIZARD_SITE_ID, Array(
		"EMAIL" => $shopEmail,
		"SITE_NAME" => $siteName,
		"SERVER_NAME" => $_SERVER["SERVER_NAME"],
	));
	
	if(strlen($siteStamp)>0) {
		if(IntVal($siteStamp) > 0) {
			$ff = CFile::GetByID($siteStamp);
			if($zr = $ff->Fetch()) {
				$strOldFile = str_replace("//", "/", WIZARD_SITE_ROOT_PATH."/".(COption::GetOptionString("main", "upload_dir", "upload"))."/".$zr["SUBDIR"]."/".$zr["FILE_NAME"]);
				@copy($strOldFile, WIZARD_SITE_PATH."include/stamp.gif");
				CFile::Delete($zr["ID"]);
				$siteStamp = WIZARD_SITE_DIR."include/stamp.gif";
				COption::SetOptionString("elektroinstrument", "siteStamp", $siteStamp, false, WIZARD_SITE_ID);
			}
		}
	} else {
		$siteStamp = "/bitrix/templates/".WIZARD_TEMPLATE_ID."_".WIZARD_THEME_ID."/images/pechat.gif";
	}
	
	$dbPerson = CSalePersonType::GetList(array(), Array("LID" => WIZARD_SITE_ID));
	while($arPerson = $dbPerson->Fetch()) {
		$arPersonTypeNames[$arPerson["ID"]] = $arPerson["NAME"];
	}
	//Person Types
	if(!$bRus) {
		$personType["fiz"] = "Y";
		$personType["ur"] = "N";
	}

	$fizExist = in_array(GetMessage("SALE_WIZARD_PERSON_1"), $arPersonTypeNames) ? true : false;
	$urExist = in_array(GetMessage("SALE_WIZARD_PERSON_2"), $arPersonTypeNames) ? true : false;
	$fizUaExist = in_array(GetMessage("SALE_WIZARD_PERSON_3"), $arPersonTypeNames) ? true : false;

	$personTypeFiz = ($personType["fiz"] == "Y") ? "Y" : "N";
	COption::SetOptionString("elektroinstrument", "personTypeFiz", $personTypeFiz, false, WIZARD_SITE_ID);
	$personTypeUr = ($personType["ur"] == "Y") ? "Y" : "N";
	COption::SetOptionString("elektroinstrument", "personTypeUr", $personTypeUr, false, WIZARD_SITE_ID);

	if(in_array(GetMessage("SALE_WIZARD_PERSON_1"), $arPersonTypeNames)) {
		$arGeneralInfo["personType"]["fiz"] = array_search(GetMessage("SALE_WIZARD_PERSON_1"), $arPersonTypeNames);
		CSalePersonType::Update(array_search(GetMessage("SALE_WIZARD_PERSON_1"), $arPersonTypeNames), 
			Array(
				"ACTIVE" => $personType["fiz"] == "Y" ? "Y" : "N",
			)
		);
	} elseif($personType["fiz"] == "Y") {
		$arGeneralInfo["personType"]["fiz"] = CSalePersonType::Add(
			Array(
				"LID" => WIZARD_SITE_ID,
				"NAME" => GetMessage("SALE_WIZARD_PERSON_1"),
				"SORT" => "100"
			)
		);
	}

	if(in_array(GetMessage("SALE_WIZARD_PERSON_2"), $arPersonTypeNames)) {
		$arGeneralInfo["personType"]["ur"] = array_search(GetMessage("SALE_WIZARD_PERSON_2"), $arPersonTypeNames);
		CSalePersonType::Update(array_search(GetMessage("SALE_WIZARD_PERSON_2"), $arPersonTypeNames), 
			Array(
				"ACTIVE" => $personType["ur"] == "Y" ? "Y" : "N",
			)
		);
	} elseif($personType["ur"] == "Y") {
		$arGeneralInfo["personType"]["ur"] = CSalePersonType::Add(
			Array(
				"LID" => WIZARD_SITE_ID,
				"NAME" => GetMessage("SALE_WIZARD_PERSON_2"),
				"SORT" => "150"
			)
		);
	}

	if(COption::GetOptionString("altop.elektroinstrument", "wizard_installed", "N", WIZARD_SITE_ID) != "Y" || WIZARD_INSTALL_DEMO_DATA) {
		//Set options 
		COption::SetOptionString('sale','default_currency',$defCurrency);
		COption::SetOptionString('sale','delete_after','30');
		COption::SetOptionString('sale','order_list_date','30');
		COption::SetOptionString('sale','MAX_LOCK_TIME','30');
		COption::SetOptionString('sale','GRAPH_WEIGHT','600');
		COption::SetOptionString('sale','GRAPH_HEIGHT','600');
		COption::SetOptionString('sale','path2user_ps_files','/bitrix/php_interface/include/sale_payment/');
		COption::SetOptionString('sale','lock_catalog','Y');
		COption::SetOptionString('sale','order_list_fields','ID,USER,PAY_SYSTEM,PRICE,STATUS,PAYED,PS_STATUS,CANCELED,BASKET');
		COption::SetOptionString('sale','GROUP_DEFAULT_RIGHT','D');
		COption::SetOptionString('sale','affiliate_param_name','partner');
		COption::SetOptionString('sale','show_order_sum','N');
		COption::SetOptionString('sale','show_order_product_xml_id','N');
		COption::SetOptionString('sale','show_paysystem_action_id','N');
		COption::SetOptionString('sale','affiliate_plan_type','N');
		if($bRus) {
			COption::SetOptionString('sale','1C_SALE_SITE_LIST',WIZARD_SITE_ID);
			COption::SetOptionString('sale','1C_EXPORT_PAYED_ORDERS','N');
			COption::SetOptionString('sale','1C_EXPORT_ALLOW_DELIVERY_ORDERS','N');
			COption::SetOptionString('sale','1C_EXPORT_FINAL_ORDERS','');
			COption::SetOptionString('sale','1C_FINAL_STATUS_ON_DELIVERY','F');
			COption::SetOptionString('sale','1C_REPLACE_CURRENCY',GetMessage("SALE_WIZARD_PS_BILL_RUB"));
			COption::SetOptionString('sale','1C_SALE_USE_ZIP','Y');
		}
		COption::SetOptionString('sale','weight_unit', GetMessage("SALE_WIZARD_WEIGHT_UNIT"), false, WIZARD_SITE_ID);
		COption::SetOptionString('sale','WEIGHT_different_set','N', false, WIZARD_SITE_ID);
		COption::SetOptionString('sale','ADDRESS_different_set','N');
		COption::SetOptionString('sale','measurement_path','/bitrix/modules/sale/measurements.php');
		COption::SetOptionString('sale','delivery_handles_custom_path','/bitrix/php_interface/include/sale_delivery/');
		if($bRus)
			COption::SetOptionString('sale','location_zip','101000', false, WIZARD_SITE_ID);
		COption::SetOptionString('sale','weight_koef','1000', false, WIZARD_SITE_ID);
		
		COption::SetOptionString('sale','recalc_product_list','Y');
		COption::SetOptionString('sale','recalc_product_list_period','4');
		COption::SetOptionString('sale', 'order_email', $shopEmail);
		
		if(!$bRus)
			$shopLocation = GetMessage("WIZ_CITY");
		
		if(\Bitrix\Main\Config\Option::get('sale', 'sale_locationpro_migrated', '') == 'Y') {
			$location = '';

			if(strlen($shopLocation)) {
				// get city with name equal to $shopLocation
				$item = \Bitrix\Sale\Location\LocationTable::getList(array(
					'filter' => array(
						'=NAME.LANGUAGE_ID' => $lang,
						'=NAME.NAME' => $shopLocation,
						'=TYPE.CODE' => 'CITY'
					),
					'select' => array(
						'CODE'
					)
				))->fetch();

				if($item)
					$location = $item['CODE']; // city found, simply take it`s code an proceed with it
				else {
					// city were not found, create it

					require($_SERVER['DOCUMENT_ROOT'].WIZARD_SERVICE_RELATIVE_PATH."/locations/pro/country_codes.php");

					// due to some reasons, $shopLocalization is being changed at the beginning of the step,
					// but here we want to have real country selected, so introduce a new variable
					$shopCountry = $wizard->GetVar("shopLocalization");

					$countryCode = $LOCALIZATION_COUNTRY_CODE_MAP[$shopCountry];
					$countryId = false;

					if(strlen($countryCode)) {
						// get country which matches the current localization
						$countryId = 0;
						$item = \Bitrix\Sale\Location\LocationTable::getList(array(
							'filter' => array(
								'=CODE' => $countryCode,
								'=TYPE.CODE' => 'COUNTRY'
							),
							'select' => array(
								'ID'
							)
						))->fetch();

						// country found
						if($item)
							$countryId = $item['ID'];
					}

					// at this point types must exist
					$types = array();
					$res = \Bitrix\Sale\Location\TypeTable::getList();
					while($item = $res->fetch())
						$types[$item['CODE']] = $item['ID'];

					if(isset($types['COUNTRY']) && isset($types['CITY'])) {
						if(!$countryId) {
							// such country were not found, create it

							$data = array(
								'CODE' => 'demo_country_'.WIZARD_SITE_ID,
								'TYPE_ID' => $types['COUNTRY'],
								'NAME' => array()
							);
							foreach($arLanguages as $langID) {
								$data["NAME"][$langID] = array(
									'NAME' => GetMessage("WIZ_COUNTRY_".ToUpper($shopCountry))
								);
							}

							$res = \Bitrix\Sale\Location\LocationTable::add($data);
							if($res->isSuccess())
								$countryId = $res->getId();
						}

						if($countryId) {
							// ok, so country were created, now create demo-city

							$data = array(
								'CODE' => 'demo_city_'.WIZARD_SITE_ID,
								'TYPE_ID' => $types['CITY'],
								'NAME' => array(),
								'PARENT_ID' => $countryId
							);
							foreach($arLanguages as $langID) {
								$data["NAME"][$langID] = array(
									'NAME' => $shopLocation
								);
							}

							$res = \Bitrix\Sale\Location\LocationTable::add($data);
							if($res->isSuccess())
								$location = 'demo_city_'.WIZARD_SITE_ID;
						}

					}
				}
			}
		} else {
			$location = 0;
			$dbLocation = CSaleLocation::GetList(Array("ID" => "ASC"), Array("LID" => $lang, "CITY_NAME" => $shopLocation));
			if($arLocation = $dbLocation->Fetch()) {
				$location = $arLocation["ID"];
			}
			if(IntVal($location) <= 0) {
				$CurCountryID = 0;
				$db_contList = CSaleLocation::GetList(
					Array(),
					Array(
						"COUNTRY_NAME" => GetMessage("WIZ_COUNTRY_".ToUpper($shopLocalization)),
						"LID" => $lang
					)
				);
				if($arContList = $db_contList->Fetch()) {
					$LLL = IntVal($arContList["ID"]);
					$CurCountryID = IntVal($arContList["COUNTRY_ID"]);
				}

				// если страна не найдена
				if(IntVal($CurCountryID) <= 0) {
					$arArrayTmp = Array();
					$arArrayTmp["NAME"] = GetMessage("WIZ_COUNTRY_".ToUpper($shopLocalization));
					foreach($arLanguages as $langID) {
						WizardServices::IncludeServiceLang("step1.php", $langID);
						$arArrayTmp[$langID] = array(
							"LID" => $langID,
							"NAME" => GetMessage("WIZ_COUNTRY_".ToUpper($shopLocalization))
						);
					}
					// то она добавляется
					$CurCountryID = CSaleLocation::AddCountry($arArrayTmp);
				}

				// и добавляется город
				$arArrayTmp = Array();
				$arArrayTmp["NAME"] = $shopLocation;
				foreach($arLanguages as $langID) {
					$arArrayTmp[$langID] = array(
						"LID" => $langID,
						"NAME" => $shopLocation
					);
				}
				$city_id = CSaleLocation::AddCity($arArrayTmp);

				$location = CSaleLocation::AddLocation(
					array(
						"COUNTRY_ID" => $CurCountryID,
						"CITY_ID" => $city_id
					)
				);
				if($bRus)
					CSaleLocation::AddLocationZIP($location, "101000");

				WizardServices::IncludeServiceLang("step1.php", $lang);
			}
		}
		COption::SetOptionString('sale', 'location', $location);
	}
	
	//Order Prop Group
	if($fizExist) {
		$dbSaleOrderPropsGroup = CSaleOrderPropsGroup::GetList(Array(), Array("PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"], "NAME" => GetMessage("SALE_WIZARD_PROP_GROUP_FIZ1")), false, false, array("ID"));
		if($arSaleOrderPropsGroup = $dbSaleOrderPropsGroup->GetNext())
			$arGeneralInfo["propGroup"]["user_fiz"] = $arSaleOrderPropsGroup["ID"];

		$dbSaleOrderPropsGroup = CSaleOrderPropsGroup::GetList(Array(),Array("PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"], "NAME" => GetMessage("SALE_WIZARD_PROP_GROUP_FIZ2")), false, false, array("ID"));
		if($arSaleOrderPropsGroup = $dbSaleOrderPropsGroup->GetNext())
			$arGeneralInfo["propGroup"]["adres_fiz"] = $arSaleOrderPropsGroup["ID"];

	} elseif($personType["fiz"] == "Y" ) {
		$arGeneralInfo["propGroup"]["user_fiz"] = CSaleOrderPropsGroup::Add(Array("PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"], "NAME" => GetMessage("SALE_WIZARD_PROP_GROUP_FIZ1"), "SORT" => 100));
		$arGeneralInfo["propGroup"]["adres_fiz"] = CSaleOrderPropsGroup::Add(Array("PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"], "NAME" => GetMessage("SALE_WIZARD_PROP_GROUP_FIZ2"), "SORT" => 200));
	}

	if($urExist) {
		$dbSaleOrderPropsGroup = CSaleOrderPropsGroup::GetList(Array(), Array("PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"], "NAME" => GetMessage("SALE_WIZARD_PROP_GROUP_UR1")), false, false, array("ID"));
		if($arSaleOrderPropsGroup = $dbSaleOrderPropsGroup->GetNext())
			$arGeneralInfo["propGroup"]["user_ur"] = $arSaleOrderPropsGroup["ID"];

		$dbSaleOrderPropsGroup = CSaleOrderPropsGroup::GetList(Array(),Array("PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"], "NAME" => GetMessage("SALE_WIZARD_PROP_GROUP_UR2")), false, false, array("ID"));
		if($arSaleOrderPropsGroup = $dbSaleOrderPropsGroup->GetNext())
			$arGeneralInfo["propGroup"]["adres_ur"] = $arSaleOrderPropsGroup["ID"];
	} elseif($personType["ur"] == "Y") {
		$arGeneralInfo["propGroup"]["user_ur"] = CSaleOrderPropsGroup::Add(Array("PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"], "NAME" => GetMessage("SALE_WIZARD_PROP_GROUP_UR1"), "SORT" => 300));
		$arGeneralInfo["propGroup"]["adres_ur"] = CSaleOrderPropsGroup::Add(Array("PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"], "NAME" => GetMessage("SALE_WIZARD_PROP_GROUP_UR2"), "SORT" => 400));
	}

	$arProps = Array();
	if($personType["fiz"] == "Y") {
		$arProps[] = Array(
			"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"],
			"NAME" => GetMessage("SALE_WIZARD_PROP_6"),
			"TYPE" => "TEXT",
			"REQUIED" => "Y",
			"DEFAULT_VALUE" => "",
			"SORT" => 100,
			"USER_PROPS" => "Y",
			"IS_LOCATION" => "N",
			"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["user_fiz"],
			"SIZE1" => 40,
			"SIZE2" => 0,
			"DESCRIPTION" => "",
			"IS_EMAIL" => "N",
			"IS_PROFILE_NAME" => "Y",
			"IS_PAYER" => "Y",
			"IS_LOCATION4TAX" => "N",
			"CODE" => "FIO",
			"IS_FILTERED" => "Y",
		);
		$arProps[] = Array(
			"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"],
			"NAME" => "E-Mail",
			"TYPE" => "TEXT",
			"REQUIED" => "Y",
			"DEFAULT_VALUE" => "",
			"SORT" => 110,
			"USER_PROPS" => "Y",
			"IS_LOCATION" => "N",
			"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["user_fiz"],
			"SIZE1" => 40,
			"SIZE2" => 0,
			"DESCRIPTION" => "",
			"IS_EMAIL" => "Y",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER" => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE" => "EMAIL",
			"IS_FILTERED" => "Y",
		);
		$arProps[] = Array(
			"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"],
			"NAME" => GetMessage("SALE_WIZARD_PROP_9"),
			"TYPE" => "TEXT",
			"REQUIED" => "Y",
			"DEFAULT_VALUE" => "",
			"SORT" => 120,
			"USER_PROPS" => "Y",
			"IS_LOCATION" => "N",
			"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["user_fiz"],
			"SIZE1" => 0,
			"SIZE2" => 0,
			"DESCRIPTION" => "",
			"IS_EMAIL" => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER" => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE" => "PHONE",
			"IS_FILTERED" => "N",
		);
		$arProps[] = Array(
			"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"],
			"NAME" => GetMessage("SALE_WIZARD_PROP_21"),
			"TYPE" => "TEXT",
			"REQUIED" => "N",
			"DEFAULT_VALUE" => $shopLocation,
			"SORT" => 140,
			"USER_PROPS" => "Y",
			"IS_LOCATION" => "N",
			"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_fiz"],
			"SIZE1" => 3,
			"SIZE2" => 0,
			"DESCRIPTION" => "",
			"IS_EMAIL" => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER" => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE" => "CITY",
			"IS_FILTERED" => "Y",
		);
		$arProps[] = Array(
			"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"],
			"NAME" => GetMessage("SALE_WIZARD_PROP_2"),
			"TYPE" => "LOCATION",
			"REQUIED" => "Y",
			"DEFAULT_VALUE" => $location,
			"SORT" => 130,
			"USER_PROPS" => "Y",
			"IS_LOCATION" => "Y",
			"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_fiz"],
			"SIZE1" => 3,
			"SIZE2" => 0,
			"DESCRIPTION" => "",
			"IS_EMAIL" => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER" => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE" => "LOCATION",
			"IS_FILTERED" => "N",
			"INPUT_FIELD_LOCATION" => "",
		);
		$arProps[] = Array(
			"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"],
			"NAME" => GetMessage("SALE_WIZARD_PROP_5"),
			"TYPE" => "TEXTAREA",
			"REQUIED" => "Y",
			"DEFAULT_VALUE" => "",
			"SORT" => 150,
			"USER_PROPS" => "Y",
			"IS_LOCATION" => "N",
			"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_fiz"],
			"SIZE1" => 30,
			"SIZE2" => 3,
			"DESCRIPTION" => "",
			"IS_EMAIL" => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER" => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE" => "ADDRESS",
			"IS_FILTERED" => "N",
		);
	}

	if($personType["ur"] == "Y") {
		$arProps[] = Array(
			"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
			"NAME" => GetMessage("SALE_WIZARD_PROP_8"),
			"TYPE" => "TEXT",
			"REQUIED" => "Y",
			"DEFAULT_VALUE" => "",
			"SORT" => 200,
			"USER_PROPS" => "Y",
			"IS_LOCATION" => "N",
			"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["user_ur"],
			"SIZE1" => 40,
			"SIZE2" => 0,
			"DESCRIPTION" => "",
			"IS_EMAIL" => "N",
			"IS_PROFILE_NAME" => "Y",
			"IS_PAYER" => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE" => "COMPANY",
			"IS_FILTERED" => "Y",
		);
		$arProps[] = Array(
			"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
			"NAME" => GetMessage("SALE_WIZARD_PROP_7"),
			"TYPE" => "TEXTAREA",
			"REQUIED" => "N",
			"DEFAULT_VALUE" => "",
			"SORT" => 210,
			"USER_PROPS" => "Y",
			"IS_LOCATION" => "N",
			"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["user_ur"],
			"SIZE1" => 40,
			"SIZE2" => 0,
			"DESCRIPTION" => "",
			"IS_EMAIL" => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER" => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE" => "COMPANY_ADR",
			"IS_FILTERED" => "N",
		);
		$arProps[] = Array(
			"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
			"NAME" => GetMessage("SALE_WIZARD_PROP_13"),
			"TYPE" => "TEXT",
			"REQUIED" => "N",
			"DEFAULT_VALUE" => "",
			"SORT" => 220,
			"USER_PROPS" => "Y",
			"IS_LOCATION" => "N",
			"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["user_ur"],
			"SIZE1" => 0,
			"SIZE2" => 0,
			"DESCRIPTION" => "",
			"IS_EMAIL" => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER" => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE" => "INN",
			"IS_FILTERED" => "N",
		);
		$arProps[] = Array(
			"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
			"NAME" => GetMessage("SALE_WIZARD_PROP_14"),
			"TYPE" => "TEXT",
			"REQUIED" => "N",
			"DEFAULT_VALUE" => "",
			"SORT" => 230,
			"USER_PROPS" => "Y",
			"IS_LOCATION" => "N",
			"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["user_ur"],
			"SIZE1" => 0,
			"SIZE2" => 0,
			"DESCRIPTION" => "",
			"IS_EMAIL" => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER" => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE" => "KPP",
			"IS_FILTERED" => "N",
		);
		$arProps[] = Array(
			"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
			"NAME" => GetMessage("SALE_WIZARD_PROP_10"),
			"TYPE" => "TEXT",
			"REQUIED" => "Y",
			"DEFAULT_VALUE" => "",
			"SORT" => 240,
			"USER_PROPS" => "Y",
			"IS_LOCATION" => "N",
			"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
			"SIZE1" => 0,
			"SIZE2" => 0,
			"DESCRIPTION" => "",
			"IS_EMAIL" => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER" => "Y",
			"IS_LOCATION4TAX" => "N",
			"CODE" => "CONTACT_PERSON",
			"IS_FILTERED" => "N",
		);
		$arProps[] = Array(
			"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
			"NAME" => "E-Mail",
			"TYPE" => "TEXT",
			"REQUIED" => "Y",
			"DEFAULT_VALUE" => "",
			"SORT" => 250,
			"USER_PROPS" => "Y",
			"IS_LOCATION" => "N",
			"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
			"SIZE1" => 40,
			"SIZE2" => 0,
			"DESCRIPTION" => "",
			"IS_EMAIL" => "Y",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER" => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE" => "EMAIL",
			"IS_FILTERED" => "N",
		);
		$arProps[] = Array(
			"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
			"NAME" => GetMessage("SALE_WIZARD_PROP_9"),
			"TYPE" => "TEXT",
			"REQUIED" => "N",
			"DEFAULT_VALUE" => "",
			"SORT" =>260,
			"USER_PROPS" => "Y",
			"IS_LOCATION" => "N",
			"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
			"SIZE1" => 0,
			"SIZE2" => 0,
			"DESCRIPTION" => "",
			"IS_EMAIL" => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER" => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE" => "PHONE",
			"IS_FILTERED" => "N",
		);
		$arProps[] = Array(
			"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
			"NAME" => GetMessage("SALE_WIZARD_PROP_11"),
			"TYPE" => "TEXT",
			"REQUIED" => "N",
			"DEFAULT_VALUE" => "",
			"SORT" => 270,
			"USER_PROPS" => "Y",
			"IS_LOCATION" => "N",
			"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
			"SIZE1" => 0,
			"SIZE2" => 0,
			"DESCRIPTION" => "",
			"IS_EMAIL" => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER" => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE" => "FAX",
			"IS_FILTERED" => "N",
		);
		$arProps[] = Array(
			"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
			"NAME" => GetMessage("SALE_WIZARD_PROP_4"),
			"TYPE" => "TEXT",
			"REQUIED" => "N",
			"DEFAULT_VALUE" => "",
			"SORT" => 280,
			"USER_PROPS" => "Y",
			"IS_LOCATION" => "N",
			"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
			"SIZE1" => 8,
			"SIZE2" => 0,
			"DESCRIPTION" => "",
			"IS_EMAIL" => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER" => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE" => "ZIP",
			"IS_FILTERED" => "N",
			"IS_ZIP" => "Y",
		);
		$arProps[] = Array(
			"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
			"NAME" => GetMessage("SALE_WIZARD_PROP_21"),
			"TYPE" => "TEXT",
			"REQUIED" => "N",
			"DEFAULT_VALUE" => $shopLocation,
			"SORT" => 300,
			"USER_PROPS" => "Y",
			"IS_LOCATION" => "N",
			"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
			"SIZE1" => 3,
			"SIZE2" => 0,
			"DESCRIPTION" => "",
			"IS_EMAIL" => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER" => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE" => "CITY",
			"IS_FILTERED" => "Y",
		);
		$arProps[] = Array(
			"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
			"NAME" => GetMessage("SALE_WIZARD_PROP_2"),
			"TYPE" => "LOCATION",
			"REQUIED" => "Y",
			"DEFAULT_VALUE" => "",
			"SORT" => 290,
			"USER_PROPS" => "Y",
			"IS_LOCATION" => "Y",
			"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
			"SIZE1" => 3,
			"SIZE2" => 0,
			"DESCRIPTION" => "",
			"IS_EMAIL" => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER" => "N",
			"IS_LOCATION4TAX" => "Y",
			"CODE" => "LOCATION",
			"IS_FILTERED" => "N",
		);
		$arProps[] = Array(
			"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
			"NAME" => GetMessage("SALE_WIZARD_PROP_12"),
			"TYPE" => "TEXTAREA",
			"REQUIED" => "Y",
			"DEFAULT_VALUE" => "",
			"SORT" => 310,
			"USER_PROPS" => "Y",
			"IS_LOCATION" => "N",
			"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_ur"],
			"SIZE1" => 30,
			"SIZE2" => 3,
			"DESCRIPTION" => "",
			"IS_EMAIL" => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER" => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE" => "ADDRESS",
			"IS_FILTERED" => "N",
		);
	}
	
	$propCityId = 0;
	foreach($arProps as $prop) {
		$variants = Array();
		if(!empty($prop["VARIANTS"])) {
			$variants = $prop["VARIANTS"];
			unset($prop["VARIANTS"]);
		}

		if($prop["CODE"] == "LOCATION" && $propCityId > 0) {
			$prop["INPUT_FIELD_LOCATION"] = $propCityId;
			$propCityId = 0;
		}

		$dbSaleOrderProps = CSaleOrderProps::GetList(array(), array("PERSON_TYPE_ID" => $prop["PERSON_TYPE_ID"], "CODE" =>  $prop["CODE"]));
		if($arSaleOrderProps = $dbSaleOrderProps->GetNext())
			$id = $arSaleOrderProps["ID"];
		else
			$id = CSaleOrderProps::Add($prop);

		if($prop["CODE"] == "CITY") {
			$propCityId = $id;
		}
		if(strlen($prop["CODE"]) > 0) {
			//$arGeneralInfo["propCode"][$prop["CODE"]] = $prop["CODE"];
			$arGeneralInfo["propCodeID"][$prop["CODE"]] = $id;
			$arGeneralInfo["properies"][$prop["PERSON_TYPE_ID"]][$prop["CODE"]] = $prop;
			$arGeneralInfo["properies"][$prop["PERSON_TYPE_ID"]][$prop["CODE"]]["ID"] = $id;
		}

		if(!empty($variants)) {
			foreach($variants as $val) {
				$val["ORDER_PROPS_ID"] = $id;
				CSaleOrderPropsVariant::Add($val);
			}
		}
	}

	//PaySystem
	$arPaySystems = Array();
	if($paysystem["cash"] == "Y") {
		$arPaySystemTmp = Array(
			"NAME" => GetMessage("SALE_WIZARD_PS_CASH"),
			"SORT" => 50,
			"ACTIVE" => "Y",
			"DESCRIPTION" => GetMessage("SALE_WIZARD_PS_CASH_DESCR"),
			"CODE_TEMP" => "cash"
		);
		if($personType["fiz"] == "Y") {
			$arPaySystemTmp["ACTION"][] = Array(
				"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"],
				"NAME" => GetMessage("SALE_WIZARD_PS_CASH"),
				"ACTION_FILE" => "/bitrix/modules/sale/payment/cash",
				"RESULT_FILE" => "",
				"NEW_WINDOW" => "N",
				"PARAMS" => "",
				"HAVE_PAYMENT" => "Y",
				"HAVE_ACTION" => "N",
				"HAVE_RESULT" => "N",
				"HAVE_PREPAY" => "N",
				"HAVE_RESULT_RECEIVE" => "N",
				"LOGOTIP"   => CFile::MakeFileArray($_SERVER['DOCUMENT_ROOT'].WIZARD_SERVICE_RELATIVE_PATH."/sale/paysystem/logotip/cash.jpg"),
			);
		}
		if($personType["ur"] == "Y") {
			$arPaySystemTmp["ACTION"][] = Array(
				"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
				"NAME" => GetMessage("SALE_WIZARD_PS_CASH"),
				"ACTION_FILE" => "/bitrix/modules/sale/payment/cash",
				"RESULT_FILE" => "",
				"NEW_WINDOW" => "N",
				"PARAMS" => "",
				"HAVE_PAYMENT" => "Y",
				"HAVE_ACTION" => "N",
				"HAVE_RESULT" => "N",
				"HAVE_PREPAY" => "N",
				"HAVE_RESULT_RECEIVE" => "N",
				"LOGOTIP"   => CFile::MakeFileArray($_SERVER['DOCUMENT_ROOT'].WIZARD_SERVICE_RELATIVE_PATH."/sale/paysystem/logotip/cash.jpg"),
			);
		}
		$arPaySystems[] = $arPaySystemTmp;
	}
	if($personType["fiz"] == "Y") {
		if($bRus) {
			$arPaySystems[] = Array(
				"NAME" => GetMessage("SALE_WIZARD_PS_CC"),
				"SORT" => 60,
				"ACTIVE" => "N",
				"DESCRIPTION" => GetMessage("SALE_WIZARD_PS_CC"),
				"CODE_TEMP" => "card",
				"ACTION" => Array(Array(
					"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"],
					"NAME" => GetMessage("SALE_WIZARD_PS_CC"),
					"ACTION_FILE" => "/bitrix/modules/sale/payment/assist",
					"RESULT_FILE" => "/bitrix/modules/sale/payment/assist_res.php",
					"NEW_WINDOW" => "N",
					"PARAMS" => serialize(Array(
						"FIRST_NAME" => Array("TYPE" => "USER", "VALUE" => "NAME"),
						"LAST_NAME" => Array("TYPE" => "USER", "VALUE" => "LAST_NAME"),
						"EMAIL" => Array("TYPE" => "PROPERTY", "VALUE" => "EMAIL"),
						"ADDRESS" => Array("TYPE" => "PROPERTY", "VALUE" => "ADDRESS"),
					)),
					"HAVE_PAYMENT" => "Y",
					"HAVE_ACTION" => "N",
					"HAVE_RESULT" => "Y",
					"HAVE_PREPAY" => "N",
					"HAVE_RESULT_RECEIVE" => "N",
				))
			);	
			$arPaySystems[] = Array(
				"NAME" => GetMessage("SALE_WIZARD_PS_WM"),
				"SORT" => 70,
				"ACTIVE" => "N",
				"DESCRIPTION" => GetMessage("SALE_WIZARD_PS_WM_DESCR"),
				"CODE_TEMP" => "webmoney",
				"ACTION" => Array(Array(
					"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"],
					"NAME" => GetMessage("SALE_WIZARD_PS_WM"),
					"ACTION_FILE" => "/bitrix/modules/sale/payment/webmoney_web",
					"RESULT_FILE" => "",
					"NEW_WINDOW" => "Y",
					"PARAMS" => "",
					"HAVE_PAYMENT" => "Y",
					"HAVE_ACTION" => "N",
					"HAVE_RESULT" => "Y",
					"HAVE_PREPAY" => "N",
					"HAVE_RESULT_RECEIVE" => "N",
				))
			);
			$arPaySystems[] = Array(
				"NAME" => GetMessage("SALE_WIZARD_PS_PC"),
				"SORT" => 80,
				"ACTIVE" => "N",
				"DESCRIPTION" => "",
				"CODE_TEMP" => "paycash",
				"ACTION" => Array(Array(
					"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"],
					"NAME" => GetMessage("SALE_WIZARD_PS_PC"),
					"ACTION_FILE" => "/bitrix/modules/sale/payment/yandex",
					"RESULT_FILE" => "",
					"NEW_WINDOW" => "N",
					"PARAMS" => serialize(Array(
						"ORDER_ID" => Array("TYPE" => "ORDER", "VALUE" => "ID"),
						"USER_ID" => Array("TYPE" => "PROPERTY", "VALUE" => "FIO"),
						"ORDER_DATE" => Array("TYPE" => "ORDER", "VALUE" => "DATE_INSERT"),
						"SHOULD_PAY" => Array("TYPE" => "ORDER", "VALUE" => "PRICE"),
					)),
					"HAVE_PAYMENT" => "Y",
					"HAVE_ACTION" => "N",
					"HAVE_RESULT" => "N",
					"HAVE_PREPAY" => "N",
					"HAVE_RESULT_RECEIVE" => "Y",
				))
			);		
			if($paysystem["sber"] == "Y") {
				$arPaySystems[] = Array(
					"NAME" => GetMessage("SALE_WIZARD_PS_SB"),
					"SORT" => 90,
					"DESCRIPTION" => GetMessage("SALE_WIZARD_PS_SB_DESCR"),
					"CODE_TEMP" => "sberbank",
					"ACTION" => Array(Array(
						"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"],
						"NAME" => GetMessage("SALE_WIZARD_PS_SB"),
						"ACTION_FILE" => "/bitrix/modules/sale/payment/sberbank_new",
						"RESULT_FILE" => "",
						"NEW_WINDOW" => "Y",
						"PARAMS" => serialize(Array(
							"COMPANY_NAME" => Array("TYPE" => "", "VALUE" => $shopOfName),
							"INN" => Array("TYPE" => "", "VALUE" => $shopINN),
							"KPP" => Array("TYPE" => "", "VALUE" => $shopKPP),
							"SETTLEMENT_ACCOUNT" => Array("TYPE" => "", "VALUE" => $shopNS),
							"BANK_NAME" => Array("TYPE" => "", "VALUE" => $shopBANK),
							"BANK_BIC" => Array("TYPE" => "", "VALUE" => $shopBANKREKV),
							"BANK_COR_ACCOUNT" => Array("TYPE" => "", "VALUE" => $shopKS),
							"ORDER_ID" => Array("TYPE" => "ORDER", "VALUE" => "ID"),
							"DATE_INSERT" => Array("TYPE" => "ORDER", "VALUE" => "DATE_INSERT_DATE"),
							"PAYER_CONTACT_PERSON" => Array("TYPE" => "PROPERTY", "VALUE" => "FIO"),
							"PAYER_ZIP_CODE" => Array("TYPE" => "PROPERTY", "VALUE" => "ZIP"),
							"PAYER_COUNTRY" => Array("TYPE" => "PROPERTY", "VALUE" => "LOCATION_COUNTRY"),
							"PAYER_CITY" => Array("TYPE" => "PROPERTY", "VALUE" => "LOCATION_CITY"),
							"PAYER_ADDRESS_FACT" => Array("TYPE" => "PROPERTY", "VALUE" => "ADDRESS"),
							"SHOULD_PAY" => Array("TYPE" => "ORDER", "VALUE" => "PRICE"),
						)),
						"HAVE_PAYMENT" => "Y",
						"HAVE_ACTION" => "N",
						"HAVE_RESULT" => "N",
						"HAVE_PREPAY" => "N",
						"HAVE_RESULT_RECEIVE" => "N",
						"LOGOTIP"   => CFile::MakeFileArray($_SERVER['DOCUMENT_ROOT'].WIZARD_SERVICE_RELATIVE_PATH."/sale/paysystem/logotip/sberbank.jpg"),
					))
				);		
			}
		} else {
			$arPaySystems[] = Array(
				"NAME" => "PayPal",
				"SORT" => 90,
				"DESCRIPTION" => "",
				"CODE_TEMP" => "paypal",
				"ACTION" => Array(Array(
					"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"],
					"NAME" => "PayPal",
					"ACTION_FILE" => "/bitrix/modules/sale/payment/paypal",
					"RESULT_FILE" => "",
					"NEW_WINDOW" => "N",
					"PARAMS" => serialize(Array(
						"ORDER_ID" => Array("TYPE" => "ORDER", "VALUE" => "ID"),
						"DATE_INSERT" => Array("TYPE" => "ORDER", "VALUE" => "DATE_INSERT_DATE"),
						"SHOULD_PAY" => Array("TYPE" => "ORDER", "VALUE" => "SHOULD_PAY"),
						"CURRENCY" => Array("TYPE" => "ORDER", "VALUE" => "CURRENCY"),
					)),
					"HAVE_PAYMENT" => "Y",
					"HAVE_ACTION" => "N",
					"HAVE_RESULT" => "N",
					"HAVE_PREPAY" => "N",
					"HAVE_RESULT_RECEIVE" => "Y",
				))
			);		
		}
	}
	
	if($personType["ur"] == "Y" && $paysystem["bill"] == "Y") {
		$arPaySystems[] = Array(
			"NAME" => GetMessage("SALE_WIZARD_PS_BILL"),
			"SORT" => 100,
			"DESCRIPTION" => "",
			"CODE_TEMP" => "bill",
			"ACTION" => Array(Array(
				"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
				"NAME" => GetMessage("SALE_WIZARD_PS_BILL"),
				"ACTION_FILE" => "/bitrix/modules/sale/payment/bill",
				"RESULT_FILE" => "",
				"NEW_WINDOW" => "Y",
				"PARAMS" => serialize(Array(
					"DATE_INSERT" => Array("TYPE" => "ORDER", "VALUE" => "DATE_INSERT_DATE"),
					"SELLER_NAME" => Array("TYPE" => "", "VALUE" => $shopOfName),
					"SELLER_ADDRESS" => Array("TYPE" => "", "VALUE" => $shopAdr),
					"SELLER_PHONE" => Array("TYPE" => "", "VALUE" => $siteTelephone),
					"SELLER_INN" => Array("TYPE" => "", "VALUE" => $shopINN),
					"SELLER_KPP" => Array("TYPE" => "", "VALUE" => $shopKPP),
					"SELLER_RS" => Array("TYPE" => "", "VALUE" => $shopNS),
					"SELLER_KS" => Array("TYPE" => "", "VALUE" => $shopKS),
					"SELLER_BIK" => Array("TYPE" => "", "VALUE" => $shopBANKREKV),
					"BUYER_NAME" => Array("TYPE" => "PROPERTY", "VALUE" => "COMPANY_NAME"),
					"BUYER_INN" => Array("TYPE" => "PROPERTY", "VALUE" => "INN"),
					"BUYER_ADDRESS" => Array("TYPE" => "PROPERTY", "VALUE" => "COMPANY_ADR"),
					"BUYER_PHONE" => Array("TYPE" => "PROPERTY", "VALUE" => "PHONE"),
					"BUYER_FAX" => Array("TYPE" => "PROPERTY", "VALUE" => "FAX"),
					"BUYER_PAYER_NAME" => Array("TYPE" => "PROPERTY", "VALUE" => "CONTACT_PERSON"),
					"PATH_TO_STAMP" => Array("TYPE" => "", "VALUE" => $siteStamp),
				)),
				"HAVE_PAYMENT" => "Y",
				"HAVE_ACTION" => "N",
				"HAVE_RESULT" => "N",
				"HAVE_PREPAY" => "N",
				"HAVE_RESULT_RECEIVE" => "N",
			))
		);
	}

	foreach($arPaySystems as $val) {
		$dbSalePaySystem = CSalePaySystem::GetList(array(), array("LID" => WIZARD_SITE_ID, "NAME" => $val["NAME"]), false, false, array("ID", "NAME"));
		if($arSalePaySystem = $dbSalePaySystem->GetNext()) {
			if($arSalePaySystem["NAME"] == GetMessage("SALE_WIZARD_PS_SB") || $arSalePaySystem["NAME"] == GetMessage("SALE_WIZARD_PS_BILL") || $arSalePaySystem["NAME"] == GetMessage("SALE_WIZARD_PS_OS")) {
				foreach($val["ACTION"] as $action) {
					$arGeneralInfo["paySystem"][$val["CODE_TEMP"]][$action["PERSON_TYPE_ID"]] = $arSalePaySystem["ID"];
					$action["PAY_SYSTEM_ID"] = $arSalePaySystem["ID"];
					$dbSalePaySystemAction = CSalePaySystemAction::GetList(array(), array("PAY_SYSTEM_ID" =>  $arSalePaySystem["ID"], "PERSON_TYPE_ID" => $action["PERSON_TYPE_ID"]), false, false, array("ID"));
					if($arSalePaySystemAction = $dbSalePaySystemAction->GetNext()) {
						CSalePaySystemAction::Update($arSalePaySystemAction["ID"], $action);
					} else {
						if(strlen($action["ACTION_FILE"]) > 0 && file_exists($_SERVER["DOCUMENT_ROOT"].$action["ACTION_FILE"]."/logo.gif")) {
							$action["LOGOTIP"] = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].$action["ACTION_FILE"]."/logo.gif");
						}
						CSalePaySystemAction::Add($action);
					}
				}
			}
		} else {
			$id = CSalePaySystem::Add(
				Array(
					"LID" => WIZARD_SITE_ID,
					"CURRENCY" => $defCurrency,
					"NAME" => $val["NAME"],
					"ACTIVE" => ($val["ACTIVE"] == "N") ? "N" : "Y",
					"SORT" => $val["SORT"],
					"DESCRIPTION" => $val["DESCRIPTION"]
				)
			);

			foreach($val["ACTION"] as &$action) {
				$arGeneralInfo["paySystem"][$val["CODE_TEMP"]][$action["PERSON_TYPE_ID"]] = $id;
				$action["PAY_SYSTEM_ID"] = $id;
				if(strlen($action["ACTION_FILE"]) > 0 && file_exists($_SERVER["DOCUMENT_ROOT"].$action["ACTION_FILE"]."/logo.gif") && !is_array($action["LOGOTIP"])) {
					$action["LOGOTIP"] = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].$action["ACTION_FILE"]."/logo.gif");
				}
				CSalePaySystemAction::Add($action);
			}
		}
	}

	if(COption::GetOptionString("altop.elektroinstrument", "wizard_installed", "N", WIZARD_SITE_ID) != "Y" || WIZARD_INSTALL_DEMO_DATA) {
		$bStatusP = false;
		$dbStatus = CSaleStatus::GetList(Array("SORT" => "ASC"));
		while($arStatus = $dbStatus->Fetch()) {
			$arFields = Array();
			foreach($arLanguages as $langID) {
				WizardServices::IncludeServiceLang("step1.php", $langID);
				$arFields["LANG"][] = Array("LID" => $langID, "NAME" => GetMessage("WIZ_SALE_STATUS_".$arStatus["ID"]), "DESCRIPTION" => GetMessage("WIZ_SALE_STATUS_DESCRIPTION_".$arStatus["ID"]));
			}
			$arFields["ID"] = $arStatus["ID"];
			CSaleStatus::Update($arStatus["ID"], $arFields);
			if($arStatus["ID"] == "P")
				$bStatusP = true;
		}
		if(!$bStatusP) {
			$arFields = Array("ID" => "P", "SORT" => 150);
			foreach($arLanguages as $langID) {
				WizardServices::IncludeServiceLang("step1.php", $langID);
				$arFields["LANG"][] = Array("LID" => $langID, "NAME" => GetMessage("WIZ_SALE_STATUS_P"), "DESCRIPTION" => GetMessage("WIZ_SALE_STATUS_DESCRIPTION_P"));
			}

			$ID = CSaleStatus::Add($arFields);
			if($ID !== '') {
				CSaleStatus::CreateMailTemplate($ID);
			}
		}

		if(CModule::IncludeModule("currency")) {
			$dbCur = CCurrency::GetList($by="currency", $o = "asc");
			while($arCur = $dbCur->Fetch()) {
				if($lang == "ru")
					CCurrencyLang::Update($arCur["CURRENCY"], $lang, array("DECIMALS" => 0, "HIDE_ZERO" => "Y"));
				elseif($arCur["CURRENCY"] == "EUR")
					CCurrencyLang::Update($arCur["CURRENCY"], $lang, array("DECIMALS" => 2, "FORMAT_STRING" => "&euro;#"));
			}
		}
		WizardServices::IncludeServiceLang("step1.php", $lang);

		if(CModule::IncludeModule("catalog")) {
			$dbVat = CCatalogVat::GetListEx(
				array(),
				array('RATE' => 0),
				false,
				false,
				array('ID', 'RATE')
			);
			if(!($dbVat->Fetch())) {
				$arF = array("ACTIVE" => "Y", "SORT" => "100", "NAME" => GetMessage("WIZ_VAT_1"), "RATE" => 0);
				CCatalogVat::Add($arF);
			}
			$dbVat = CCatalogVat::GetListEx(
				array(),
				array('RATE' => GetMessage("WIZ_VAT_2_VALUE")),
				false,
				false,
				array('ID', 'RATE')
			);
			if(!($dbVat->Fetch())) {
				$arF = array("ACTIVE" => "Y", "SORT" => "200", "NAME" => GetMessage("WIZ_VAT_2"), "RATE" => GetMessage("WIZ_VAT_2_VALUE"));
				CCatalogVat::Add($arF);
			}
			$dbResultList = CCatalogGroup::GetList(array(), array("CODE" => "BASE"));
			if($arRes = $dbResultList->Fetch()) {
				$arFields = Array();
				foreach($arLanguages as $langID) {
					WizardServices::IncludeServiceLang("step1.php", $langID);
					$arFields["USER_LANG"][$langID] = GetMessage("WIZ_PRICE_NAME");
				}
				$arFields["BASE"] = "Y";
				if($wizard->GetVar("installPriceBASE") == "Y"){
					$db_res = CCatalogGroup::GetGroupsList(array("CATALOG_GROUP_ID"=>'1', "BUY"=>"Y"));
					if($ar_res = $db_res->Fetch()) {
						$wizGroupId[] = $ar_res['GROUP_ID'];
					}
					$wizGroupId[] = 2;
					$arFields["USER_GROUP"] = $wizGroupId;
					$arFields["USER_GROUP_BUY"] = $wizGroupId;
				}
				CCatalogGroup::Update($arRes["ID"], $arFields);
			}
		}
		
		//making orders
		function __MakeOrder($prdCnt=1, $arData = Array()) {
			global $APPLICATION, $USER, $DB;
			CModule::IncludeModule("iblock");
			CModule::IncludeModule("sale");
			CModule::IncludeModule("catalog");
			$arPrd = Array();
			$dbItem = CIBlockElement::GetList(Array(/*"PROPERTY_MORE_PHOTO" => "DESC", "ID" => "ASC"*/), Array("IBLOCK_TYPE" => "catalog", "IBLOCK_SITE_ID" => WIZARD_SITE_ID, "PROPERTY_NEWPRODUCT" => false), false, Array("nTopCount" => 100), Array("ID", "IBLOCK_ID", "XML_ID", "NAME", "DETAIL_PAGE_URL", "IBLOCK_XML_ID"));
			while($arItem = $dbItem->GetNext())
				$arPrd[] = $arItem;

			if(!empty($arPrd)) {
				for($i=0; $i<$prdCnt;$i++) {
					$prdID = $arPrd[mt_rand(20, 99)];
					$arProduct = CCatalogProduct::GetByID($prdID["ID"]);
					$CALLBACK_FUNC = "";
					$arCallbackPrice = CSaleBasket::ReReadPrice($CALLBACK_FUNC, "catalog", $prdID["ID"], 1);

					$arFields = array(
						"PRODUCT_ID" => $prdID["ID"],
						"PRODUCT_PRICE_ID" => $arCallbackPrice["PRODUCT_PRICE_ID"],
						"PRICE" => $arCallbackPrice["PRICE"],
						"CURRENCY" => $arCallbackPrice["CURRENCY"],
						"WEIGHT" => $arProduct["WEIGHT"],
						"QUANTITY" => 1,
						"LID" => WIZARD_SITE_ID,
						"DELAY" => "N",
						"CAN_BUY" => "Y",
						"NAME" => $prdID["NAME"],
						"CALLBACK_FUNC" => $CALLBACK_FUNC,
						"MODULE" => "catalog",
						"PRODUCT_PROVIDER_CLASS" => "CCatalogProductProvider",
						"ORDER_CALLBACK_FUNC" => "",
						"CANCEL_CALLBACK_FUNC" => "",
						"PAY_CALLBACK_FUNC" => "",
						"DETAIL_PAGE_URL" => $prdID["DETAIL_PAGE_URL"],
						"CATALOG_XML_ID" => $prdID["IBLOCK_XML_ID"],
						"PRODUCT_XML_ID" => $prdID["XML_ID"],
						"VAT_RATE" => $arCallbackPrice['VAT_RATE'],
					);
					$addres = CSaleBasket::Add($arFields);
				}

				$arOrder = Array(
					"LID" => $arData["SITE_ID"],
					"PERSON_TYPE_ID" => $arData["PERSON_TYPE_ID"],
					"PAYED" => "N",
					"CANCELED" => "N",
					"STATUS_ID" => "N",
					"PRICE" => 1,
					"CURRENCY" => $arData["CURRENCY"],
					"USER_ID" => $arData["USER_ID"],
					"PAY_SYSTEM_ID" => $arData["PAY_SYSTEM_ID"],
					//"PRICE_DELIVERY" => $arData["PRICE_DELIVERY"],
					//"DELIVERY_ID" => $arData["DELIVERY_ID"],
				);

				$dbFUserListTmp = CSaleUser::GetList(array("USER_ID" => $arData["USER_ID"]));
				if(empty($dbFUserListTmp)) {
					$arFields = array(
						"=DATE_INSERT" => $DB->GetNowFunction(),
						"=DATE_UPDATE" => $DB->GetNowFunction(),
						"USER_ID" => $arData["USER_ID"]
					);
					$ID = CSaleUser::_Add($arFields);
				}

				$orderID = CSaleOrder::Add($arOrder);
				CSaleBasket::OrderBasket($orderID, CSaleBasket::GetBasketUserID(), WIZARD_SITE_ID);
				$dbBasketItems = CSaleBasket::GetList(
					array("NAME" => "ASC"),
					array(
						"FUSER_ID" => CSaleBasket::GetBasketUserID(),
						"LID" => WIZARD_SITE_ID,
						"ORDER_ID" => $orderID
					),
					false,
					false,
					array("ID", "CALLBACK_FUNC", "MODULE", "PRODUCT_ID", "QUANTITY", "DELAY", "CAN_BUY", "PRICE", "WEIGHT", "NAME")
				);
				$ORDER_PRICE = 0;
				while($arBasketItems = $dbBasketItems->GetNext()) {
					$ORDER_PRICE += roundEx($arBasketItems["PRICE"], SALE_VALUE_PRECISION) * DoubleVal($arBasketItems["QUANTITY"]);
				}

				$totalOrderPrice = $ORDER_PRICE + $arData["PRICE_DELIVERY"];
				CSaleOrder::Update($orderID, Array("PRICE" => $totalOrderPrice));
				foreach($arData["PROPS"] as $val) {
					$arFields = Array(
						"ORDER_ID" => $orderID,
						"ORDER_PROPS_ID" => $val["ID"],
						"NAME" => $val["NAME"],
						"CODE" => $val["CODE"],
						"VALUE" => $val["VALUE"],
					);
					CSaleOrderPropsValue::Add($arFields);
				}
				return $orderID;
			}
		}

		$personType = $arGeneralInfo["personType"]["ur"];
		if(IntVal($arGeneralInfo["personType"]["fiz"]) > 0)
			$personType = $arGeneralInfo["personType"]["fiz"];
		if(IntVal($personType) <= 0) {
			$dbPerson = CSalePersonType::GetList(array(), Array("LID" => WIZARD_SITE_ID));
			if($arPerson = $dbPerson->Fetch()) {
				$personType = $arPerson["ID"];
			}
		}
		if(IntVal($arGeneralInfo["paySystem"]["cash"][$personType]) > 0 )
			$paySystem = $arGeneralInfo["paySystem"]["cash"][$personType];
		elseif(IntVal($arGeneralInfo["paySystem"]["bill"][$personType]) > 0 )
			$paySystem = $arGeneralInfo["paySystem"]["bill"][$personType];
		elseif(IntVal($arGeneralInfo["paySystem"]["bill"][$personType]) > 0 )
			$paySystem = $arGeneralInfo["paySystem"]["sber"][$personType];
		elseif(IntVal($arGeneralInfo["paySystem"]["paypal"][$personType]) > 0 )
			$paySystem = $arGeneralInfo["paySystem"]["paypal"][$personType];
		else {
			$dbPS = CSalePaySystem::GetList(Array(), Array("LID" => WIZARD_SITE_ID));
			if($arPS = $dbPS->Fetch())
				$paySystem = $arPS["ID"];
		}

		if(\Bitrix\Main\Config\Option::get('sale', 'sale_locationpro_migrated', '') == 'Y') {
			if(!strlen($location)) {
				// get first found
				$item = \Bitrix\Sale\Location\LocationTable::getList(array('limit' => 1, 'select' => array('CODE')))->fetch();
				if($item)
					$location = $item['CODE'];
			}
		} else {
			if(IntVal($location) <= 0) {
				$dbLocation = CSaleLocation::GetList(Array("ID" => "ASC"), Array("LID" => $lang));
				if($arLocation = $dbLocation->Fetch()) {
					$location = $arLocation["ID"];
				}
			}
		}

		if(empty($arGeneralInfo["properies"][$personType])) {
			$dbProp = CSaleOrderProps::GetList(array(), Array("PERSON_TYPE_ID" => $personType));
			while($arProp = $dbProp->Fetch())
				$arGeneralInfo["properies"][$personType][$arProp["CODE"]] = $arProp;
		}

		if(WIZARD_INSTALL_DEMO_DATA) {
			$db_sales = CSaleOrder::GetList(array("DATE_INSERT" => "ASC"), array("LID" => WIZARD_SITE_ID), false, false, array("ID"));
			while($ar_sales = $db_sales->Fetch()) {
				CSaleOrder::Delete($ar_sales["ID"]);
			}
		}

		$arData = Array(
			"SITE_ID" => WIZARD_SITE_ID,
			"PERSON_TYPE_ID" => $personType,
			"CURRENCY" => $defCurrency,
			"USER_ID" => 1,
			"PAY_SYSTEM_ID" => $paySystem,
			//"PRICE_DELIVERY" => "0",
			//"DELIVERY_ID" => "",
			"PROPS" => Array(),
		);
		foreach($arGeneralInfo["properies"][$personType] as $key => $val) {
			$arProp = Array(
				"ID" => $val["ID"],
				"NAME" => $val["NAME"],
				"CODE" => $val["CODE"],
				"VALUE" => "",
			);

			if($key == "FIO" || $key == "CONTACT_PERSON")
				$arProp["VALUE"] = GetMessage("WIZ_ORD_FIO");
			elseif($key == "ADDRESS" || $key == "COMPANY_ADR")
				$arProp["VALUE"] = GetMessage("WIZ_ORD_ADR");
			elseif($key == "EMAIL")
				$arProp["VALUE"] = "example@example.com";
			elseif($key == "PHONE")
				$arProp["VALUE"] = "8 495 2312121";
			elseif($key == "ZIP")
				$arProp["VALUE"] = "101000";
			elseif($key == "LOCATION")
				$arProp["VALUE"] = $location;
			elseif($key == "CITY")
				$arProp["VALUE"] = $shopLocation;
			$arData["PROPS"][] = $arProp;
		}
		$orderID = __MakeOrder(3, $arData);
		CSaleOrder::DeliverOrder($orderID, "Y");
		CSaleOrder::PayOrder($orderID, "Y");
		CSaleOrder::StatusOrder($orderID, "F");
		$orderID = __MakeOrder(4, $arData);
		CSaleOrder::DeliverOrder($orderID, "Y");
		CSaleOrder::PayOrder($orderID, "Y");
		CSaleOrder::StatusOrder($orderID, "F");
		$orderID = __MakeOrder(2, $arData);
		CSaleOrder::PayOrder($orderID, "Y");
		CSaleOrder::StatusOrder($orderID, "P");
		$orderID = __MakeOrder(1, $arData);
		$orderID = __MakeOrder(3, $arData);
		CSaleOrder::CancelOrder($orderID, "Y");
		CAgent::RemoveAgent("CSaleProduct::RefreshProductList();", "sale");
		CAgent::AddAgent("CSaleProduct::RefreshProductList();", "sale", "N", 60*60*24*4, "", "Y");
	}
}
return true;
?>