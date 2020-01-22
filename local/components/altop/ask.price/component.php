<?if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && (htmlspecialchars($_POST['METHOD']) == 'ask_price' || htmlspecialchars($_POST['METHOD']) == 'order')) {

	//Подключаем API без визуальной части
	require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

	//Подключаем языковый файл
	require_once($_SERVER['DOCUMENT_ROOT'].htmlspecialchars($_POST["TEMPLATE"])."/lang/ru/template.php");
	
    $error = '';

	$REQUIRED = array();
	$REQUIRED = explode("/", $_POST["REQUIRED"]);	

	//Проверка заполненности Имени
	if(empty($REQUIRED) || in_array("NAME", $REQUIRED)) {
		if(!isset($_POST['NAME']) || !strlen($_POST['NAME'])) {
			$error .= GetMessage('NAME_NOT_FILLED').' <br>';
			$return = true;
		}
	}	

	//Проверка заполненности Телефона
	if(empty($REQUIRED) || in_array("TEL", $REQUIRED)) {
		if(!isset($_POST['TEL']) || !strlen($_POST['TEL'])) {
			$error .= GetMessage('TEL_NOT_FILLED').' <br>';
			$return = true;
		}
	}

	//Проверка заполненности Времени звонка
	if(empty($REQUIRED) || in_array("TIME", $REQUIRED)) {
		if(!isset($_POST['TIME']) || !strlen($_POST['TIME'])) {
			$error .= GetMessage('TIME_NOT_FILLED').' <br>';
			$return = true;
		}
	}

	//Проверка заполненности Вопроса
	if(empty($REQUIRED) || in_array("MESSAGE", $REQUIRED)) {
		if(!isset($_POST['MESSAGE']) || !strlen($_POST['MESSAGE'])) {
			$error .= GetMessage('MESSAGE_NOT_FILLED').' <br>';
			$return = true;
		}
	}

	if(!$USER->IsAuthorized()) {
		//Затираем значение введенной капчи
		if(htmlspecialchars($_POST['METHOD']) == 'ask_price') {
			echo '<script>$("#ask_price_captcha_word_'.htmlspecialchars($_POST["ELEMENT_ID"]).'").attr("value", "");</script>';
		} elseif(htmlspecialchars($_POST['METHOD']) == 'order') {
			echo '<script>$("#order_captcha_word_'.htmlspecialchars($_POST["ELEMENT_ID"]).'").attr("value", "");</script>';
		}
		if(!$APPLICATION->CaptchaCheckCode($_POST["captcha_word"], $_POST["captcha_sid"])) {
	        $error .= GetMessage('WRONG_CAPTCHA').' <br>';
	        $return = true;
		}
	}

	//Если есть ошибки, то выдаем текст ошибки
	if($return == true) {
		//обновляем капчу
	    if(!$USER->IsAuthorized()) {
    		$cCode = $APPLICATION->CaptchaGetCode();
			if(htmlspecialchars($_POST['METHOD']) == 'ask_price') {
				echo '<script>$("#ask_price_cImg_'.htmlspecialchars($_POST["ELEMENT_ID"]).'").attr("src","/bitrix/tools/captcha.php?captcha_sid='.$cCode.'");$("#ask_price_captcha_sid_'.htmlspecialchars($_POST["ELEMENT_ID"]).'").val("'.$cCode.'");</script>';
			} elseif(htmlspecialchars($_POST['METHOD']) == 'order') {
				echo '<script>$("#order_cImg_'.htmlspecialchars($_POST["ELEMENT_ID"]).'").attr("src","/bitrix/tools/captcha.php?captcha_sid='.$cCode.'");$("#order_captcha_sid_'.htmlspecialchars($_POST["ELEMENT_ID"]).'").val("'.$cCode.'");</script>';
			}
		}
		echo '<span class="alertMsg bad"><i class="fa fa-times"></i><span class="text">'.$error.'</span></span>';
        return;
    }
	
    $_POST['NAME']		= iconv("UTF-8", SITE_CHARSET, strip_tags(trim($_POST['NAME'])));    
	$_POST['TEL']		= iconv("UTF-8", SITE_CHARSET, strip_tags(trim($_POST['TEL'])));
	$_POST['TIME']		= iconv("UTF-8", SITE_CHARSET, strip_tags(trim($_POST['TIME'])));
	$_POST['MESSAGE']	= iconv("UTF-8", SITE_CHARSET, strip_tags(trim($_POST['MESSAGE'])));

	//Отправка письма
	$headers = "From: ".$_POST["EMAIL_TO"]."\r\n";
	$headers .= "Content-type: text/plain; charset=KOI8-R\r\n";
	$headers .= "Mime-Version: 1.0\r\n";

	$title = SITE_SERVER_NAME.": ".GetMessage('MF_MESSAGE_TITLE');

	$message = GetMessage("MF_MESSAGE_INFO")." ".SITE_SERVER_NAME."\r\n";
	$message.= "------------------------------------------\r\n";
	$message.= GetMessage("MF_MESSAGE_ZAKAZ")."\n";
	$message.= GetMessage("MF_MESSAGE_NAME")." ".$_POST['NAME']."\r\n";	
	$message.= GetMessage("MF_MESSAGE_TEL")." ".$_POST['TEL']."\r\n";
	$message.= GetMessage("MF_MESSAGE_TIME")." ".$_POST['TIME']."\r\n";
	$message.= GetMessage("MF_MESSAGE_MESSAGE")." ".$_POST['MESSAGE']."\r\n";
	$message.= GetMessage("MF_MESSAGE_GENERAT")."\r\n";

	if(mail($_POST["EMAIL_TO"], iconv(SITE_CHARSET, 'KOI8-R', $title), iconv(SITE_CHARSET, 'KOI8-R', $message), $headers)) {
		echo '<span class="alertMsg good"><i class="fa fa-check"></i><span class="text">'.GetMessage("MF_OK_MESSAGE").'</span></span>';
	}
	
} elseif(!$_SERVER['HTTP_X_REQUESTED_WITH']) {
	
	if(!CModule::IncludeModule('iblock'))
		return;	

	$arResult = array();

	$element_id = preg_replace("~\D+~", "", $arParams["ELEMENT_ID"]);

	$arElement = CIBlockElement::GetList(
		array(), 
		array("=ID" => $element_id), 
		false, 
		false, 
		array("ID", "IBLOCK_ID", "NAME", "DETAIL_PICTURE", "PROPERTY_CML2_LINK")
	)->Fetch();	

	$arResult["ELEMENT_NAME"] = $arElement["NAME"];

	if($arElement["DETAIL_PICTURE"] > 0) {
		$arFileTmp = CFile::ResizeImageGet(
			$arElement["DETAIL_PICTURE"],
			array("width" => 178, "height" => 178),
			BX_RESIZE_IMAGE_PROPORTIONAL,
			true
		);		
		$arResult["PREVIEW_IMG"] = array(
			"SRC" => $arFileTmp["src"],
			"WIDTH" => $arFileTmp["width"],
			"HEIGHT" => $arFileTmp["height"],
		);
	} else {
		if(!empty($arElement["PROPERTY_CML2_LINK_VALUE"])) {
			$arElement2 = CIBlockElement::GetList(
				array(), 
				array("=ID" => $arElement["PROPERTY_CML2_LINK_VALUE"]), 
				false, 
				false, 
				array("NAME", "DETAIL_PICTURE")
			)->Fetch();
			if($arElement2["DETAIL_PICTURE"] > 0) {				
				$arFileTmp = CFile::ResizeImageGet(
					$arElement2["DETAIL_PICTURE"],
					array("width" => 178, "height" => 178),
					BX_RESIZE_IMAGE_PROPORTIONAL,
					true
				);		
				$arResult["PREVIEW_IMG"] = array(
					"SRC" => $arFileTmp["src"],
					"WIDTH" => $arFileTmp["width"],
					"HEIGHT" => $arFileTmp["height"],
				);
			}
		}
	}

	if(!$USER->IsAuthorized()) {
		$arResult["CAPTCHA_CODE"] = htmlspecialchars($APPLICATION->CaptchaGetCode());
	}
	
	$arResult["EMAIL_TO"] = trim($arParams["EMAIL_TO"]);
	if(strlen($arParams["EMAIL_TO"]) <= 0)
		$arResult["EMAIL_TO"] = COption::GetOptionString("main", "email_from");

	$arResult["REQUIRED"] = implode("/", $arParams["REQUIRED_FIELDS"]);

	if($USER->IsAuthorized())
		$arResult['NAME'] = htmlspecialcharsEx($USER->GetFullName());

	$arResult['MESSAGE'] = $arParams["ELEMENT_NAME"];
	
	$this->IncludeComponentTemplate();
}?>