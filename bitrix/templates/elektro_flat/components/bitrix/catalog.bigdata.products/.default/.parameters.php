<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arTemplateParameters = array(
	"DISPLAY_IMG_WIDTH" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_IMG_WIDTH"),
		"TYPE" => "TEXT",
		"DEFAULT" => "150",
	),
	"DISPLAY_IMG_HEIGHT" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_IMG_HEIGHT"),
		"TYPE" => "TEXT",
		"DEFAULT" => "150",
	),
	"SHARPEN" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_SHARPEN"),
		"TYPE" => "TEXT",
		"DEFAULT" => "30",
	),
	"DISPLAY_COMPARE" => Array(
		"PARENT" => "ADDITIONAL_SETTINGS",
		"NAME" => GetMessage("T_IBLOCK_DESC_DISPLAY_COMPARE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"SHOW_POPUP" => Array(
		"PARENT" => "ADDITIONAL_SETTINGS",
		"NAME" => GetMessage("T_IBLOCK_DESC_SHOW_POPUP"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	)
);?>