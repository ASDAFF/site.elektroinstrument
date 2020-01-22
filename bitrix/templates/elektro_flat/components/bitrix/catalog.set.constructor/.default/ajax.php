<?define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if(!CModule::IncludeModule("sale") || !CModule::IncludeModule("catalog") || !CModule::IncludeModule("iblock"))
	return;

if($_SERVER["REQUEST_METHOD"]=="POST" && strlen($_POST["action"]) > 0 && check_bitrix_sessid()) {
	$APPLICATION->RestartBuffer();

	switch($_POST["action"]) {		
		case "ajax_recount_prices":
			if(strlen($_POST["currency"]) > 0) {
				$arPices = array("sumValue" => "", "sumCurrency" => "", "formatOldSum" => "", "formatDiscDiffSum" => "");
				
				if($_POST["sumPrice"]) {					
					$price = CCurrencyLang::GetCurrencyFormat($_POST["currency"], "ru");
					if(empty($price["THOUSANDS_SEP"])):
						$price["THOUSANDS_SEP"] = " ";
					endif;
					$currency = str_replace("#", " ", $price["FORMAT_STRING"]);
					
					$arPices["sumValue"] = number_format($_POST["sumPrice"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);
					
					$arPices["sumCurrency"] = $currency;
				}
				if($_POST["sumOldPrice"] && $_POST["sumOldPrice"] != $_POST["sumPrice"]) {
					$arPices["formatOldSum"] = CCurrencyLang::CurrencyFormat($_POST["sumOldPrice"], $_POST["currency"], true);
				}
				if($_POST["sumDiffDiscountPrice"]) {
					$arPices["formatDiscDiffSum"] = CCurrencyLang::CurrencyFormat($_POST["sumDiffDiscountPrice"], $_POST["currency"], true);
				}

				if(SITE_CHARSET != "utf-8") {
					$arPices = $APPLICATION->ConvertCharsetArray($arPices, SITE_CHARSET, "utf-8");
				}

				echo json_encode($arPices);
			}
			break;
		
		case "catalogSetAdd2Basket":
			if(is_array($_POST["set_ids"])) {
				foreach($_POST["set_ids"] as $itemID) {
					$product_properties = true;
					if(!empty($_POST["setOffersCartProps"])) {
						$product_properties = CIBlockPriceTools::GetOfferProperties(
							$itemID,
							$_POST["iblockId"],
							$_POST["setOffersCartProps"]
						);
					}
					
					$ratio = 1;
					if($_POST["itemsRatio"][$itemID]) {
						$ratio = $_POST["itemsRatio"][$itemID];
					}

					if(intval($itemID)) {
						$resBasket = CSaleBasket::GetList(
							array(), 
							array(
								"PRODUCT_ID" => intval($itemID),
								"FUSER_ID" => CSaleBasket::GetBasketUserID(),
								"LID" => $_POST["lid"],
								"ORDER_ID" => "NULL",
								"DELAY" => "Y"
							), 
							false, 
							false, 
							array("ID")
						);
						if($ar = $resBasket->Fetch()) {
							CSaleBasket::Update($ar["ID"], array("QUANTITY" => $ratio, "DELAY" => "N"));
						} else {
							Add2BasketByProductID(intval($itemID), $ratio, array("LID" => $_POST["lid"]), $product_properties);
						}
					}
				}
			}
			break;
	}
	die();
}?>