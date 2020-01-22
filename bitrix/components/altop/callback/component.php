<?if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && htmlspecialchars($_POST['METHOD']) == 'callback') {
	
	//Подключаем API без визуальной части
	require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

	//Подключаем языковый файл
	require_once($_SERVER['DOCUMENT_ROOT'].htmlspecialchars($_POST["TEMPLATE"])."/lang/ru/template.php");
		
    $error = '';

	$REQUIRED = array();
	$REQUIRED = explode("/", $_POST["REQUIRED"]);	

	//Проверка заполненности Имени
	if(empty($REQUIRED) || in_array("NAME", $REQUIRED)):
		if(!isset($_POST['NAME']) || !strlen($_POST['NAME'])) {
			$error .= GetMessage('NAME_NOT_FILLED').' <br>';
			$return = true;
		}
	endif;

	//Проверка заполненности Телефона
	if(empty($REQUIRED) || in_array("TEL", $REQUIRED)):
		if(!isset($_POST['TEL']) || !strlen($_POST['TEL'])) {
			$error .= GetMessage('TEL_NOT_FILLED').' <br>';
			$return = true;
		}
	endif;

	//Проверка заполненности Времени звонка
	if(empty($REQUIRED) || in_array("TIME", $REQUIRED)):
		if(!isset($_POST['TIME']) || !strlen($_POST['TIME'])) {
			$error .= GetMessage('TIME_NOT_FILLED').' <br>';
			$return = true;
		}
	endif;

	//Проверка заполненности Сообщения
	if(empty($REQUIRED) || in_array("MESSAGE", $REQUIRED)):
		if(!isset($_POST['MESSAGE']) || !strlen($_POST['MESSAGE'])) {
			$error .= GetMessage('MESSAGE_NOT_FILLED').' <br>';
			$return = true;
		}
	endif;

	if(!$USER->IsAuthorized()) {		
		//Затираем значение введенной капчи
		echo '<script>$("#callback_captcha_word").attr("value", "");</script>';		
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
			echo '<script>$("#callback_cImg").attr("src","/bitrix/tools/captcha.php?captcha_sid='.$cCode.'");$("#callback_captcha_sid").val("'.$cCode.'");</script>';
		}
		echo '<span class="alertMsg bad"><i class="fa fa-times"></i><span class="text">'.$error.'</span></span>';
        return;
    }
	
    $_POST['NAME']			= iconv("UTF-8", SITE_CHARSET, strip_tags(trim($_POST['NAME'])));
    $_POST['TEL']			= iconv("UTF-8", SITE_CHARSET, strip_tags(trim($_POST['TEL'])));
	$_POST['TIME']			= iconv("UTF-8", SITE_CHARSET, strip_tags(trim($_POST['TIME'])));
	$_POST['MESSAGE']		= iconv("UTF-8", SITE_CHARSET, strip_tags(trim($_POST['MESSAGE'])));
	
	
	//Отправка письма
	$headers = "From: ".$_POST["EMAIL_TO"]."\r\n";
	$headers .= "Content-type: text/plain; charset=KOI8-R\r\n";
	$headers .= "Mime-Version: 1.0\r\n";

	$title = SITE_SERVER_NAME.": ".GetMessage('MF_MESSAGE_CALLBACK_ZAKAZ');

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
	
	$arResult = array();

	if(!$USER->IsAuthorized()) {
		$arResult["CAPTCHA_CODE"] = htmlspecialchars($APPLICATION->CaptchaGetCode());
	}
	
	$arResult["EMAIL_TO"] = trim($arParams["EMAIL_TO"]);
	if(strlen($arParams["EMAIL_TO"]) <= 0)
		$arResult["EMAIL_TO"] = COption::GetOptionString("main", "email_from");

	$arResult["REQUIRED"] = implode("/", $arParams["REQUIRED_FIELDS"]);

	if($USER->IsAuthorized())
		$arResult['NAME'] = htmlspecialcharsEx($USER->GetFullName());	
	
	$this->IncludeComponentTemplate();
}?>