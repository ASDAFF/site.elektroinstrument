<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Sale\Location;

if(!CModule::IncludeModule("iblock"))
	return;

foreach($arResult["ORDERS"] as $key => $val):
	
	if($val["ORDER"]["PAYED"] != "Y" && $val["ORDER"]["CANCELED"] != "Y") {
		if(intval($val["ORDER"]["PAY_SYSTEM_ID"])) {
			$dbPaySysAction = CSalePaySystemAction::GetList(
				array(),
				array("PAY_SYSTEM_ID" => $val["ORDER"]["PAY_SYSTEM_ID"], "PERSON_TYPE_ID" => $val["ORDER"]["PERSON_TYPE_ID"]),
				false,
				false,
				array("NAME", "ACTION_FILE", "NEW_WINDOW", "PARAMS", "ENCODING")
			);

			if($arPaySysAction = $dbPaySysAction->Fetch()) {
				if(strlen($arPaySysAction["ACTION_FILE"])) {
					if($arPaySysAction["NEW_WINDOW"] == "Y") {
						$arResult["ORDERS"][$key]["ORDER"]["PSA_ACTION_FILE"] = htmlspecialcharsbx($arParams["PATH_TO_PAYMENT"]).'?ORDER_ID='.urlencode(urlencode($val["ORDER"]["ACCOUNT_NUMBER"]));
					}
				}
			}
		}
	}

	foreach($val["BASKET_ITEMS"] as $key2 => $arBasketItems):
		$ar = CIBlockElement::GetList(
			array(), 
			array("=ID" => $arBasketItems["PRODUCT_ID"]), 
			false, 
			false, 
			array(
				"ID", 
				"IBLOCK_ID", 
				"NAME", 
				"DETAIL_PICTURE",
				"PROPERTY_CML2_LINK"
			)
		)->Fetch();
		if($ar["DETAIL_PICTURE"] > 0) {
			$ar["DETAIL_PICTURE"] = CFile::ResizeImageGet($ar["DETAIL_PICTURE"], array("width" => 30, "height" => 30), BX_RESIZE_IMAGE_PROPORTIONAL, true);
			$arResult["ORDERS"][$key]["BASKET_ITEMS"][$key2]["DETAIL_PICTURE"] = $ar["DETAIL_PICTURE"];
		} else {
			if(!empty($ar["PROPERTY_CML2_LINK_VALUE"])) {
				$ar2 = CIBlockElement::GetList(
					array(), 
					array("=ID" => $ar["PROPERTY_CML2_LINK_VALUE"]), 
					false, 
					false, 
					array(
						"ID", 
						"IBLOCK_ID", 
						"NAME", 
						"DETAIL_PICTURE"
					)
				)->Fetch();
				if($ar2["DETAIL_PICTURE"] > 0) {
					$ar2["DETAIL_PICTURE"] = CFile::ResizeImageGet($ar2["DETAIL_PICTURE"], array("width" => 30, "height" => 30), BX_RESIZE_IMAGE_PROPORTIONAL, true);
					$arResult["ORDERS"][$key]["BASKET_ITEMS"][$key2]["DETAIL_PICTURE"] = $ar2["DETAIL_PICTURE"];
				}
			}
		}

		/***BASKET_ITEMS_PROPS***/
		$dbProp = CSaleBasket::GetPropsList(
			array("SORT" => "ASC", "ID" => "ASC"),
			array("BASKET_ID" => $arBasketItems["ID"], "!CODE" => array("CATALOG.XML_ID", "PRODUCT.XML_ID"))
		);
		while($arProp = $dbProp->GetNext()) {
			$arResult["ORDERS"][$key]["BASKET_ITEMS"][$key2]["PROPS"][] = $arProp;
		}
	endforeach;

	/***ORDER_PROPS***/
	$dbOrderProps = CSaleOrderPropsValue::GetOrderProps($val["ORDER"]["ID"]);
	$iGroup = -1;
	while($arOrderProps = $dbOrderProps->Fetch()) {
		if(empty($arParams["PROP_".$val["ORDER"]["PERSON_TYPE_ID"]]) || !in_array($arOrderProps["ORDER_PROPS_ID"], $arParams["PROP_".$val["ORDER"]["PERSON_TYPE_ID"]])) {
			if($arOrderProps["ACTIVE"] == "Y" && $arOrderProps["UTIL"] == "N") {
				$arOrderPropsTmp = $arOrderProps;

				if($iGroup != intval($arOrderProps["PROPS_GROUP_ID"])) {
					$arOrderPropsTmp["SHOW_GROUP_NAME"] = "Y";
					$iGroup = intval($arOrderProps["PROPS_GROUP_ID"]);
				}
				
				if($arOrderProps["TYPE"] == "SELECT" || $arOrderProps["TYPE"] == "RADIO") {
					
					$arVal = CSaleOrderPropsVariant::GetByValue($arOrderProps["ORDER_PROPS_ID"], $arOrderProps["VALUE"]);
					$arOrderPropsTmp["VALUE"] = htmlspecialcharsEx($arVal["NAME"]);
				
				} elseif($arOrderProps["TYPE"] == "MULTISELECT") {
					
					$arOrderPropsTmp["VALUE"] = "";
					$curVal = explode(",", $arOrderProps["VALUE"]);
					for($i = 0, $intCount = count($curVal); $i < $intCount; $i++) {
						$arVal = CSaleOrderPropsVariant::GetByValue($arOrderProps["ORDER_PROPS_ID"], $curVal[$i]);
						if($i > 0)
							$arOrderPropsTmp["VALUE"] .= ", ";

						$arOrderPropsTmp["VALUE"] .= htmlspecialcharsEx($arVal["NAME"]);
					}
				
				} elseif($arOrderProps["TYPE"] == "LOCATION") {
					
					$locationName = "";
					if(CSaleLocation::isLocationProEnabled()) {
						$locationName = Location\Admin\LocationHelper::getLocationStringByCode($arOrderProps["VALUE"]);
					} else {
						if(CSaleLocation::isLocationProMigrated())
							$arOrderProps["VALUE"] = CSaleLocation::getLocationIDbyCODE($arOrderProps["VALUE"]);
						
						$arVal = CSaleLocation::GetByID($arOrderProps["VALUE"], LANGUAGE_ID);

						$locationName .= (!strlen($arVal["COUNTRY_NAME"]) ? "" : $arVal["COUNTRY_NAME"]);

						if(strlen($arVal["COUNTRY_NAME"]) && strlen($arVal["REGION_NAME"]))
							$locationName .= " - ".$arVal["REGION_NAME"];
						elseif(strlen($arVal["REGION_NAME"]))
							$locationName .= $arVal["REGION_NAME"];

						if(strlen($arVal["COUNTRY_NAME"]) || strlen($arVal["REGION_NAME"]))
							$locationName .= " - ".$arVal["CITY_NAME"];
						elseif(strlen($arVal["CITY_NAME"]))
							$locationName .= $arVal["CITY_NAME"];
					}
					$arOrderPropsTmp["VALUE"] = $locationName;
				
				} elseif($arOrderProps["TYPE"] == "FILE") {
					
					if(strpos($arOrderProps["VALUE"], ",") !== false) {
						$fileValue = "";
						$values = explode(",", $arOrderProps["VALUE"]);

						foreach($values as $fileId)
							$fileValue .= CFile::ShowFile(trim($fileId), 0, 90, 90, true)."<br/>";

						$arOrderPropsTmp["VALUE"] = $fileValue;
					} else {
						$arOrderPropsTmp["VALUE"] = CFile::ShowFile($arOrderProps["VALUE"], 0, 90, 90, true);
					}
				}
				
				$arResult["ORDERS"][$key]["ORDER"]["ORDER_PROPS"][] = $arOrderPropsTmp;
			}
		}
	}	

endforeach;?>