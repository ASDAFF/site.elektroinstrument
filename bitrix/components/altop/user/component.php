<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

global $USER;

if($USER->IsAuthorized()):

	$rsUser = CUser::GetByID($USER->GetID());
	
	if($arUser = $rsUser->Fetch()):

		$arFileTmp = CFile::ResizeImageGet(
			$arUser["PERSONAL_PHOTO"],
			array("width" => 57, "height" => 57),
			BX_RESIZE_IMAGE_PROPORTIONAL,
			true
		);

		$arResult["PERSONAL_PHOTO"] = array(
			"SRC" => $arFileTmp["src"],
			'WIDTH' => $arFileTmp["width"],
			'HEIGHT' => $arFileTmp["height"],
		);

		$arResult["FIO"] = $USER->GetFullName();

		$arResult["LOGIN"] = $arUser["LOGIN"];

		$this->IncludeComponentTemplate();

	endif;

endif;?>