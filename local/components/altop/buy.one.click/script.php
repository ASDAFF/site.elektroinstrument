<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require(dirname(__FILE__)."/lang/".LANGUAGE_ID."/script.php");

if(!CModule::IncludeModule('sale') || !CModule::IncludeModule('iblock') || !CModule::IncludeModule('catalog') || !CModule::IncludeModule('currency'))
	die();

if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && htmlspecialchars($_POST['METHOD']) == 'boc') {

    $error = '';

	$REQUIRED = array();
	$REQUIRED = explode("/", $_POST["REQUIRED"]);	

	if(empty($REQUIRED) || in_array("NAME", $REQUIRED)):
		if(!isset($_POST['NAME']) || !strlen($_POST['NAME'])) {
			$error .= GetMessage('NAME_NOT_FILLED').'<br />';
			$return = true;
		}
	endif;

	if(empty($REQUIRED) || in_array("TEL", $REQUIRED)):
		if(!isset($_POST['TEL']) || !strlen($_POST['TEL'])) {
			$error .= GetMessage('TEL_NOT_FILLED').'<br />';
			$return = true;
		}
	endif;

	if(empty($REQUIRED) || in_array("EMAIL", $REQUIRED)):
		if(!isset($_POST['EMAIL']) || !strlen($_POST['EMAIL'])) {
			$error .= GetMessage('EMAIL_NOT_FILLED').' <br>';
			$return = true;
		}
	endif;

	if(empty($REQUIRED) || in_array("MESSAGE", $REQUIRED)):
		if(!isset($_POST['MESSAGE']) || !strlen($_POST['MESSAGE'])) {
			$error .= GetMessage('MESSAGE_NOT_FILLED').' <br>';
			$return = true;
		}
	endif;

	if(!$USER->IsAuthorized()):
		echo '<script>$("#boc_captcha_word_'.htmlspecialchars($_POST["ELEMENT_ID"]).'").attr("value", "");</script>';
		if(!$APPLICATION->CaptchaCheckCode($_POST["captcha_word"], $_POST["captcha_sid"])) {
	        $error .= GetMessage('WRONG_CAPTCHA').'<br />';
	        $return = true;
	    }
	endif;

	if($return == true) {
		if(!$USER->IsAuthorized()) {
    		$cCode = $APPLICATION->CaptchaGetCode();
			echo '<script>$("#boc_cImg_'.htmlspecialchars($_POST["ELEMENT_ID"]).'").attr("src","/bitrix/tools/captcha.php?captcha_sid='.$cCode.'");$("#boc_captcha_sid_'.htmlspecialchars($_POST["ELEMENT_ID"]).'").val("'.$cCode.'");</script>';
		}
		echo '<span class="alertMsg bad"><i class="fa fa-times"></i><span class="text">'.$error.'</span></span>';
        return;
    }

	$_POST['NAME']		= iconv("UTF-8", SITE_CHARSET, strip_tags(trim($_POST['NAME'])));
    $_POST['TEL']		= iconv("UTF-8", SITE_CHARSET, strip_tags(trim($_POST['TEL'])));
	$_POST['EMAIL']		= iconv("UTF-8", SITE_CHARSET, strip_tags(trim($_POST['EMAIL'])));
	$_POST['MESSAGE']	= iconv("UTF-8", SITE_CHARSET, strip_tags(trim($_POST['MESSAGE'])));
	
	
	/***USER REGISTER***/
	global $USER, $APPLICATION;
	$register_new_user = $send_letter = false;
	
	/***User is not authorized***/
	if(!$USER->IsAuthorized()) {
		
		if(empty($_POST['EMAIL'])) {
			$login = 'user_'.time();
			$_POST['EMAIL'] = $login.'@'.$login.'.com';
			$register_new_user = true;
		} else {
			$login = 'user_'.time();
			$register_new_user = true;
			$send_letter = true;
		}
		
		if($register_new_user) {
			$use_captcha = COption::GetOptionString('main', 'captcha_registration', 'N');
			if($use_captcha == 'Y')
				COption::SetOptionString('main', 'captcha_registration', 'N');
			
			$userPassword = randString(10);

			$use_email_confirm = COption::GetOptionString('main', 'new_user_registration_email_confirmation', 'N');
			if($use_email_confirm == 'Y')
				COption::SetOptionString('main', 'new_user_registration_email_confirmation', 'N');
			
			$newUser = $USER->Register($login, $_POST['NAME'], '', $userPassword, $userPassword, $_POST['EMAIL']);
			
			if($use_captcha == 'Y')
				COption::SetOptionString('main', 'captcha_registration', 'Y');

			if($use_email_confirm == 'Y')
				COption::SetOptionString('main', 'new_user_registration_email_confirmation', 'Y');
			
			if($newUser['TYPE'] != 'ERROR') {
				$registeredUserID = $USER->GetID();
				if(!empty($_POST['TEL']))
					$userUpd = $USER->Update($registeredUserID, array('PERSONAL_PHONE' => $_POST['TEL']));
			} 
		}
	
	/***User is authorized***/
	} else {
		
		$send_letter = true;
		$registeredUserID = $USER->GetID();
	
	}

	$basketUserID = CSaleBasket::GetBasketUserID();
		
	
	/***CREATE ORDER***/
	if($_POST['buyMode'] == 'ONE') {
		CSaleBasket::DeleteAll($basketUserID);
		
		$arProps = array();
		if(isset($_POST["element_props"]) && !empty($_POST["element_props"])):
			$arPropsBefore = unserialize(gzuncompress(stripslashes(base64_decode(strtr($_POST["element_props"], '-_,', '+/=')))));
			foreach($arPropsBefore as $arProp):
				$arProps[] = $arProp;
			endforeach;
		endif;
		if(isset($_POST["element_select_props"]) && !empty($_POST["element_select_props"])):
			$select_props = explode("||", $_POST["element_select_props"]);
			foreach($select_props as $arSelProp):
				$arProps[] = unserialize(gzuncompress(stripslashes(base64_decode(strtr($arSelProp, '-_,', '+/=')))));
			endforeach;
		endif;

		Add2BasketByProductID($_POST['ELEMENT_ID'], $_POST['quantity'], array(), $arProps);
	}

	$dbBasketItems = CSaleBasket::GetList(
		array("ID" => "ASC"),
		array(
			"FUSER_ID" => $basketUserID,
			"LID" => SITE_ID,
			"ORDER_ID" => "NULL",
			"DELAY" => "N"
		),
		false,
		false,
		array()
	);

	while($arItem = $dbBasketItems->GetNext()) {
		if($arItem["VAT_RATE"] > 0) {
			$arResult["bUsingVat"] = "Y";
		}
		$arResult["BASKET_ITEMS"][] = $arItem;
	}

	$personTypeId = intval($_POST['personTypeId']) > 0 ? $_POST['personTypeId']: 1;
	$deliveryId = intval($_POST['deliveryId']) > 0 ? $_POST['deliveryId']: 0;
	$paysystemId = intval($_POST['paysystemId']) > 0 ? $_POST['paysystemId']: 0;

	$orderProps = array(
		$_POST['propNameId'] => $_POST['NAME'],		
		$_POST['propTelId'] => $_POST['TEL'],
		$_POST['propEmailId'] => $_POST['EMAIL']
	);

	$arOrderDat = CSaleOrder::DoCalculateOrder(
		SITE_ID,
		$registeredUserID,
		$arResult["BASKET_ITEMS"],
		$personTypeId,
		array(),
		$deliveryId,
		$paysystemId,
		array(),
		$arErrors,
		$arWarnings
	);

	$arResult["BASE_LANG_CURRENCY"] = CSaleLang::GetLangCurrency(SITE_ID);

	$arResult["ORDER_PRICE"] = $arOrderDat['ORDER_PRICE'];
	$arResult["ORDER_PRICE_FORMATED"] = SaleFormatCurrency($arResult["ORDER_PRICE"], $arResult["BASE_LANG_CURRENCY"]);

	$arResult["USE_VAT"] = $arOrderDat['USE_VAT'];
	$arResult["VAT_SUM"] = $arOrderDat["VAT_SUM"];
	$arResult["VAT_SUM_FORMATED"] = SaleFormatCurrency($arResult["VAT_SUM"], $arResult["BASE_LANG_CURRENCY"]);

	$arResult['TAX_PRICE'] = $arOrderDat["TAX_PRICE"];
	$arResult['TAX_LIST'] = $arOrderDat["TAX_LIST"];

	$arResult['DISCOUNT_PRICE'] = $arOrderDat["DISCOUNT_PRICE"];

	$arResult['DELIVERY_PRICE'] = $arOrderDat['PRICE_DELIVERY'];
	$arResult['DELIVERY_PRICE_FORMATED'] = SaleFormatCurrency($arOrderDat["DELIVERY_PRICE"], $arResult["BASE_LANG_CURRENCY"]);

	$arResult['BASKET_ITEMS'] = $arOrderDat['BASKET_ITEMS'];

	$orderTotalSum = $arResult["ORDER_PRICE"] + $arResult["DELIVERY_PRICE"] + $arResult["TAX_PRICE"] - $arResult["DISCOUNT_PRICE"];

	$arFields = array(
		"LID" => SITE_ID,
		"PERSON_TYPE_ID" => $personTypeId,
		"PAYED" => "N",
		"CANCELED" => "N",
		"STATUS_ID" => "N",
		"PRICE" => $orderTotalSum,
		"CURRENCY" => $arResult["BASE_LANG_CURRENCY"],
		"USER_ID" => $registeredUserID,
		"PAY_SYSTEM_ID" => $paysystemId,
		"PRICE_DELIVERY" => $arResult["DELIVERY_PRICE"],
		"DELIVERY_ID" => $deliveryId,
		"DISCOUNT_VALUE" => $arResult["DISCOUNT_PRICE"],
		"TAX_VALUE" => $arResult["bUsingVat"] == "Y" ? $arResult["VAT_SUM"] : $arResult["TAX_PRICE"],
		"COMMENTS" => GetMessage('ORDER_COMMENT').(!empty($_POST["MESSAGE"]) ? ": ".$_POST["MESSAGE"] : ""),
	);
	
	/***Add guest id***/
	if(CModule::IncludeModule("statistic"))
		$arFields["STAT_GID"] = CStatistic::GetEventParam();

	$affiliateID = CSaleAffiliate::GetAffiliate();
	if($affiliateID > 0) {
		$dbAffiliat = CSaleAffiliate::GetList(array(), array("SITE_ID" => SITE_ID, "ID" => $affiliateID));
		$arAffiliates = $dbAffiliat->Fetch();
		if(count($arAffiliates) > 1)
			$arFields["AFFILIATE_ID"] = $affiliateID;
	} else
		$arFields["AFFILIATE_ID"] = false;

	$arResult["ORDER_ID"] = CSaleOrder::Add($arFields);
	$arResult["ORDER_ID"] = IntVal($arResult["ORDER_ID"]);
	
	if($arResult["ORDER_ID"] > 0) {
		
		/***Order props***/
		foreach($orderProps as $key => $val) {
			$db_props = CSaleOrderProps::GetList(
				array(),
				array("ID" => $key),
				false,
				false,
				array()
			);
			while($props = $db_props->Fetch()){
				CSaleOrderPropsValue::Add(
					array(
						"ORDER_ID" => $arResult["ORDER_ID"],
						"NAME" => $props["NAME"],
						"ORDER_PROPS_ID" => $props["ID"],
						"CODE" => $props["CODE"],
						"VALUE" => $val
					)
				);
			}
		}

		/***Add basket product***/
		$arOrder = CSaleOrder::GetByID($arResult["ORDER_ID"]);
		CSaleBasket::OrderBasket($arResult["ORDER_ID"], $basketUserID, SITE_ID, false);

		/***Display message***/
		echo '<span class="alertMsg good"><i class="fa fa-check"></i><span class="text">'.GetMessage("ORDER_CREATE_SUCCESS").'</span></span>';
		if($_POST['buyMode'] == 'ONE') {
			echo '<script>$("#boc_'.htmlspecialchars($_POST["ELEMENT_ID"]).' .btn_buy").addClass("hidden");$("#boc_'.htmlspecialchars($_POST["ELEMENT_ID"]).' .result").removeClass("hidden");</script>';
		} elseif($_POST['buyMode'] == 'ALL') {
			echo '<script>$("#boc_cart .btn_buy").addClass("hidden");$("#boc_cart .result").removeClass("hidden");</script>';
		}

		/***Logout new user***/
		if($register_new_user) {
			$USER->Logout();
		}


		/***SEND MAIL MESSAGE***/
		$strOrderList = "";
		$arBasketList = array();
		
		$dbBasketItems = CSaleBasket::GetList(
			array("ID" => "ASC"),
			array("ORDER_ID" => $arResult["ORDER_ID"]),
			false,
			false,
			array("ID", "PRODUCT_ID", "NAME", "QUANTITY", "PRICE", "CURRENCY", "TYPE", "SET_PARENT_ID")
		);
		
		while($arItem = $dbBasketItems->Fetch()) {
			$arBasketList[] = $arItem;
		}

		$arBasketList = getMeasures($arBasketList);

		foreach($arBasketList as $arItem) {
			$measureText = (isset($arItem["MEASURE_TEXT"]) && strlen($arItem["MEASURE_TEXT"])) ? $arItem["MEASURE_TEXT"] : GetMessage("SOA_SHT");

			$strOrderList .= $arItem["NAME"]." - ".$arItem["QUANTITY"]." ".$measureText.": ".SaleFormatCurrency($arItem["PRICE"], $arItem["CURRENCY"]);
			$strOrderList .= "\n";
		}

		$email = '';
		$bcc = array();
		$duplicate = 'N';
		
		if(isset($_POST['dubLetter']) && 0 < strlen($_POST['dubLetter'])) {
			$rsSites = CSite::GetList($by = "sort", $order = "desc", Array("ACTIVE" => "Y"));
			while($arSite = $rsSites->Fetch()) {
				if(strpos($_POST['dubLetter'], 'default_'.$arSite['LID']) !== false) {					
					$default_email = $arSite['EMAIL'];
					if(!empty($default_email))
						$bcc[] = $default_email;
				}
			}

			if(strpos($_POST['dubLetter'], 'admin') !== false) {
				$admin_email = COption::GetOptionString('main', 'email_from', '');
				if(!empty($admin_email))
					$bcc[] = $admin_email;
			}

			if(strpos($_POST['dubLetter'], 'sales') !== false) {
				$sales_email = COption::GetOptionString('sale', 'order_email', '');
				if(!empty($sales_email))
					$bcc[] = $sales_email;
			}

			if(strpos($_POST['dubLetter'], 'dub') !== false) {
				$dub_email = COption::GetOptionString('main', 'all_bcc', '');
				if(!empty($dub_email))
					$duplicate = 'Y';
			}
		}
		$bcc = array_unique($bcc);		

		if($send_letter) {
			$email = $_POST['EMAIL'];
		} else {
			if(empty($bcc)) {
				if($duplicate == 'Y')
					$email = COption::GetOptionString('main', 'all_bcc', '');
			} else
				$email = array_shift($bcc);
		}

		if(strlen($email) > 0)	{
			$arFields = Array(
				"ORDER_ID" => $arOrder["ACCOUNT_NUMBER"],
				"ORDER_DATE" => Date($DB->DateFormatToPHP(CLang::GetDateFormat("SHORT", SITE_ID))),
				"ORDER_USER" => $_POST['NAME'],
				"PRICE" => SaleFormatCurrency($orderTotalSum, $arResult["BASE_LANG_CURRENCY"]),
				"BCC" => !empty($bcc)? implode(',', $bcc) : '',
				"EMAIL" => $email,
				"ORDER_LIST" => $strOrderList,
				"SALE_EMAIL" => COption::GetOptionString("sale", "order_email", "sales@".$SERVER_NAME),
				"DELIVERY_PRICE" => $arResult["DELIVERY_PRICE"],
			);

			$eventName = "SALE_NEW_ORDER";
			$bSend = true;
			
			foreach(GetModuleEvents("sale", "OnOrderNewSendEmail", true) as $arEvent)
				if(ExecuteModuleEventEx($arEvent, Array($arResult["ORDER_ID"], &$eventName, &$arFields))===false)
					$bSend = false;

			if($bSend) {
				$event = new CEvent;
				$event->Send($eventName, SITE_ID, $arFields, "N");
			}
		}

		CSaleMobileOrderPush::send("ORDER_CREATED", array("ORDER_ID" => $arFields["ORDER_ID"]));


		/***STATISTIC***/
		if(CModule::IncludeModule("statistic")) {
			$event1 = "eStore";
			$event2 = "order_confirm";
			$event3 = $arResult["ORDER_ID"];

			$e = $event1."/".$event2."/".$event3;

			if(!is_array($_SESSION["ORDER_EVENTS"]) || (is_array($_SESSION["ORDER_EVENTS"]) && !in_array($e, $_SESSION["ORDER_EVENTS"]))) {
				CStatistic::Set_Event($event1, $event2, $event3);
				$_SESSION["ORDER_EVENTS"][] = $e;
			}
		}

		foreach(GetModuleEvents("sale", "OnSaleComponentOrderOneStepComplete", true) as $arEvent)
			ExecuteModuleEventEx($arEvent, Array($arResult["ORDER_ID"], $arOrder, $arParams));
	
	} else {
		
		/***Display message***/
		echo '<span class="alertMsg bad"><i class="fa fa-times"></i><span class="text">'.GetMessage("ORDER_CREATE_ERROR").'</span></span>';
		
		/***Logout new user***/
		if($register_new_user) {
			$USER->Logout();
		}
	}
		
}?>