<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arComponentParameters = array(
	"PARAMETERS" => array(
		"ELEMENT_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("MFP_ELEMENT_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => '={$arItem["ID"]}',
		),
		"ELEMENT_NAME" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("MFP_ELEMENT_NAME"),
			"TYPE" => "STRING",
			"DEFAULT" => '={$arItem["NAME"]}',
		),
		"EMAIL_TO" => Array(
			"NAME" => GetMessage("MFP_EMAIL_TO"), 
			"TYPE" => "STRING",
			"DEFAULT" => htmlspecialchars(COption::GetOptionString("main", "email_from")), 
			"PARENT" => "BASE",
		),
		"REQUIRED_FIELDS" => Array(
			"NAME" => GetMessage("MFP_REQUIRED_FIELDS"), 
			"TYPE"=>"LIST", 
			"MULTIPLE"=>"Y", 
			"VALUES" => Array("NONE" => GetMessage("MFP_ALL_REQ"), "NAME" => GetMessage("MFP_NAME"), "TEL" => GetMessage("MFP_TEL"), "TIME" => GetMessage("MFP_TIME"), "MESSAGE" => GetMessage("MFP_MESSAGE")),
			"DEFAULT"=>"", 
			"COLS"=>25, 
			"PARENT" => "BASE",
		),
	)
);?>