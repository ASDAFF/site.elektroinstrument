<?if($_SERVER ['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && htmlspecialchars($_POST['METHOD']) == 'comment') {
	
	//Подключаем API без визуальной части
	require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

	CModule::IncludeModule('iblock');
	global $APPLICATION, $USER;
	
    //Подключаем языковый файл
	require_once($_SERVER['DOCUMENT_ROOT'].htmlspecialchars($_POST["TEMPLATE"])."/lang/ru/template.php");
	
    $error = '';
	
    if(!$USER->IsAuthorized()) {		
		if($_POST["CAPTCHA"] == 'Y') {
			//Затираем значение введенной капчи
    		echo '<script>$("#comment_captcha_word").attr("value", "");</script>';	
		}		
    	
		//Проверка заполненности Имени
		if(!isset($_POST['NAME']) || !strlen($_POST['NAME'])) {
			$error .= GetMessage('NAME_NOT_FILLED').' <br>';
		    $return = true;
		}
	}
    
	//Проверка заполненности текста комментария
    if(!strlen($_POST['TEXT'])) {
        $error .= GetMessage('COMMENT_NOT_FILLED').' <br>';
        $return = true;
    }
	
	//Проверка капчи
    if($_POST["CAPTCHA"] == 'Y') {
	    if(!$USER->IsAuthorized() and !$APPLICATION->CaptchaCheckCode($_POST["captcha_word"], $_POST["captcha_sid"])) {
	        $error .= GetMessage('WRONG_CAPTCHA').' <br>';
	        $return = true;
	    }
	}
	
	//Если есть ошибки, то выдаем текст ошибки
    if($return == true) {
    	//Если в настройках есть параметр использования капчи, обновляем капчу
	    if(!$USER->IsAuthorized() && $_POST["CAPTCHA"] == 'Y') {
    		$cCode = $APPLICATION->CaptchaGetCode();
			echo '<script>$("#comment_cImg").attr("src","/bitrix/tools/captcha.php?captcha_sid='.$cCode.'");$("#comment_captcha_sid").val("'.$cCode.'");</script>';
		}
        echo '<span class="alertMsg bad"><i class="fa fa-times"></i><span class="text">'.$error.'</span></span>';
        return;
    }
	
    $_POST['NAME']            = iconv("UTF-8", SITE_CHARSET, strip_tags(trim($_POST['NAME'])));
	$_POST['TEXT']            = iconv("UTF-8", SITE_CHARSET, trim($_POST['TEXT']));
	
	//Для всех ссылок в тексте комментария добавим rel="nofollow"
	$_POST['TEXT'] = preg_replace("#(https?|ftp)://\S+[^\s.,>)\];'\"!?]#",'<a href="\\0" rel="nofollow">\\0</a>', $_POST['TEXT']);
	
    $el = new CIBlockElement;
    $PROPS = array();
	$arProps = explode("/", $_POST["PROPS"]);	
	
	//Если пользователь авторизован, то данные для имени, почты и т.д. берем из его учетной записи, иначе, из полей комментария
    if(!$USER->IsAuthorized()) {
        $PROPS[$arProps[1]] = htmlspecialchars($_POST['NAME']);
    } else {
		$rsUser = CUser::GetByID($USER->GetID());
		$arUser = $rsUser->Fetch();
        $PROPS[$arProps[1]] = $arUser["LOGIN"];
    }
    
	//Заполняем остальные свйоства
    $PROPS[$arProps[0]] = $_POST['OID'];
    $PROPS[$arProps[2]] = $_SERVER["REMOTE_ADDR"];
	$PROPS[$arProps[3]] = $_POST["URL"];
    
    	
    $arLoadCommentArray = Array(
        "MODIFIED_BY"       => $USER->GetID(),
        "ACTIVE_FROM"       => ConvertTimeStamp(false, "FULL"),
        "IBLOCK_SECTION_ID" => false,
        "IBLOCK_ID"         => $_POST['CID'],
        "PROPERTY_VALUES"   => $PROPS,
        "NAME"              => GetMessage('REVIEW_ON_PRODUCT')." ".iconv("UTF-8", SITE_CHARSET, $_POST['ONAME']),
        "ACTIVE"            => ($_POST["PRE_MODER"] == "Y" && !$USER->IsAdmin())? "N" : "Y",
        "DETAIL_TEXT"       => $_POST['TEXT']
    );

	//Добавляем новый коммент
    if($NEW_ID = $el->Add($arLoadCommentArray)) {
		//Коммент успешно дабавлен, очищаем поля формы добавления коммента
        $_POST['NAME'] = '';
		$_POST['TEXT'] = '';

		if($_POST["PRE_MODER"] == "Y" && !$USER->IsAdmin()) {
			echo '<span class="alertMsg good"><i class="fa fa-check"></i><span class="text">'.GetMessage("REVIEW_ADD_AFTER_MODER").'</span></span>';
		} else {
			echo '<span class="alertMsg good"><i class="fa fa-check"></i><span class="text">'.GetMessage("REVIEW_ADD_SUCCESS").'</span></span>';
		}

		echo '<script>$("#new_comment_form .btn_buy").addClass("hidden");$("#new_comment_form .result").removeClass("hidden");</script>';
				        
        //Перезагружаем страницу
        echo '<script>window.setTimeout(function(){location.reload()},2000)</script>';
	}

} elseif(!$_SERVER['HTTP_X_REQUESTED_WITH']) {	
	
	if(!CModule::IncludeModule('iblock'))
		return;

	$arResult = array();

	$arResult['OBJECT_NAME']   					 = $arParams['OBJECT_NAME'];	
	$arResult["PROPS"]                           = implode("/", array($arParams["PROPERTY_OBJECT_ID"], $arParams["PROPERTY_USER_ID"], $arParams["PROPERTY_IP_COMMENTOR"], $arParams["PROPERTY_URL"]));	
	$arResult['NON_AUTHORIZED_USER_CAN_COMMENT'] = $arParams['NON_AUTHORIZED_USER_CAN_COMMENT'];	
	$arResult['PRE_MODERATION']                  = $arParams["PRE_MODERATION"];
	$arResult['USE_CAPTCHA']                     = $arParams['USE_CAPTCHA'];

	//Проверка на заполненность обязательных параметров компонента
	if(empty($arParams['OBJECT_ID'])) {
		echo GetMessage('NO_OBJECT_ID');
		return;
	}

	if(empty($arParams['OBJECT_NAME'])) {
		echo GetMessage('NO_OBJECT_NAME');
		return;
	}

	if(empty($arParams['COMMENTS_IBLOCK_ID'])) {
		echo GetMessage('NO_COMMENTS_IBLOCK_ID');
		return;
	}

	if(empty($arParams["PROPERTY_OBJECT_ID"])) {
		echo GetMessage('NO_PROPERTY_OBJECT_ID');
		return;
	}

	if(empty($arParams["PROPERTY_USER_ID"])) {
		echo GetMessage('NO_PROPERTY_USER_ID');
		return;
	}

	$arElement = CIBlockElement::GetList(
		array(), 
		array("=ID" => $arParams['OBJECT_ID']), 
		false, 
		false, 
		array("ID", "IBLOCK_ID", "NAME", "DETAIL_PICTURE")
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
	}

	if(!$USER->IsAuthorized() && $arResult["USE_CAPTCHA"] == "Y" && $arResult['NON_AUTHORIZED_USER_CAN_COMMENT'] == "Y") {
		$arResult["CAPTCHA_CODE"] = htmlspecialchars($APPLICATION->CaptchaGetCode());
	} elseif($USER->IsAdmin()) {
		$arResult['USE_CAPTCHA'] = 'N';
	} elseif($USER->IsAuthorized() && !$USER->IsAdmin()) {
		$arResult['USE_CAPTCHA'] = 'N';
	} else {
		$arResult['USE_CAPTCHA'] = 'N';
	}	

	$comments = array();
	
	$arSelect = array("ID", "DETAIL_TEXT", "PROPERTY_".$arParams["PROPERTY_USER_ID"], "ACTIVE", "DATE_CREATE", "CREATED_BY");
	
	$arFilter = array("IBLOCK_ID" => $arParams['COMMENTS_IBLOCK_ID'], "ACTIVE" => "Y", "PROPERTY_".$arParams["PROPERTY_OBJECT_ID"] => $arParams['OBJECT_ID']);
		
	$res = CIBlockElement::GetList(array("DATE" => "DESC"), $arFilter, false, Array("nPageSize" => 5), $arSelect);
		
	while($ob = $res->GetNextElement()) {

		$arFields = $ob->GetFields();
					
		$user["NAME"] = $arFields["PROPERTY_".$arParams["PROPERTY_USER_ID"]."_VALUE"];
		
		$user["PICT"] = array();
		$rsUser = CUser::GetByID($arFields["CREATED_BY"]);
		if($arUser = $rsUser->Fetch()):
			if(!empty($arUser["PERSONAL_PHOTO"])):
				$arFileTmp = CFile::ResizeImageGet(
					$arUser["PERSONAL_PHOTO"],
					array("width" => 57, "height" => 57),
					BX_RESIZE_IMAGE_PROPORTIONAL,
					true
				);

				$user["PICT"] = array(
					"SRC" => $arFileTmp["src"],
					'WIDTH' => $arFileTmp["width"],
					'HEIGHT' => $arFileTmp["height"],
				);
			endif;
		endif;

		$comments[] = array(
			"ID"          => $arFields['ID'],
			"ACTIVE"      => $arFields['ACTIVE'],
			"DATE_CREATE" => $arFields['DATE_CREATE'],
			"USER"        => $user,
			"TEXT"        => $arFields['DETAIL_TEXT'],
		);
	}

	$navStr = $res->GetPageNavStringEx($navComponentObject, "", "reviews");
	
	$arResult['COMMENTS'] = $comments;
	$arResult['URL'] = $APPLICATION->GetCurPage();
	$arResult['COMMENTS_COUNT'] = sizeof($comments);
			
	$this->IncludeComponentTemplate();

	echo $navStr; 
}?>