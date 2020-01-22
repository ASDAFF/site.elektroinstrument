<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule("iblock"))
	return;

$arTypesEx = CIBlockParameters::GetIBlockTypes(Array("-"=>" "));

$arIBlocks=Array();
$db_iblock = CIBlock::GetList(Array("SORT" => "ASC"), Array("SITE_ID" => $_REQUEST["site"], "TYPE" => ($arCurrentValues["IBLOCK_TYPE"] != "-" ? $arCurrentValues["IBLOCK_TYPE"] : "")));
while($arRes = $db_iblock->Fetch())
	$arIBlocks[$arRes["ID"]] = $arRes["NAME"];
	
$rsGroups = CGroup::GetList(($by = "c_sort"), ($order = "desc"), array("ACTIVE" => "Y")); 

while($arGroup = $rsGroups->Fetch()) {
	$arGroups[$arGroup["ID"]] = $arGroup["NAME"];	
}
	
	
$arComponentParameters = array(
	"GROUPS" => array(
		"PROPERTY" => array(
			"NAME" => GetMessage("PROPERTY_FIELDS_GROUP_NAME")
		),
		"ACCESS" => array(
			"NAME" => GetMessage("ACCESS_FIELDS_GROUP_NAME")
		),
	),
	"PARAMETERS" => array(
		"OBJECT_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("OBJECT_ID"),
			"TYPE" => "INT",
			"MULTIPLE" => "N",
			"ADDITIONAL_VALUES" => "N",			
		),
		"OBJECT_NAME" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("OBJECT_NAME"),
			"TYPE" => "STRING",			
			"ADDITIONAL_VALUES" => "N",			
		),
		"IBLOCK_TYPE" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arTypesEx,
			"DEFAULT" => "news",
			"REFRESH" => "Y",
		),
		"COMMENTS_IBLOCK_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("COMMENTS_IBLOCK_ID"),
			"TYPE" => "LIST",			
			"VALUES" => $arIBlocks,
			"DEFAULT" => "={$_REQUEST['ID']}",
			"ADDITIONAL_VALUES" => "Y",
			"REFRESH" => "Y",
		),		
		"PROPERTY_OBJECT_ID" => array(
			"PARENT" => "PROPERTY",
			"NAME" => GetMessage("PROPERTY_OBJECT_ID"),
			"TYPE" => "STRING",			
			"ADDITIONAL_VALUES" => "N",
		),
		"PROPERTY_USER_ID" => array(
			"PARENT" => "PROPERTY",
			"NAME" => GetMessage("PROPERTY_USER_ID"),
			"TYPE" => "STRING",			
			"ADDITIONAL_VALUES" => "N",
		),
		"PROPERTY_IP_COMMENTOR" => array(
			"PARENT" => "PROPERTY",
			"NAME" => GetMessage("PROPERTY_IP_COMMENTOR"),
			"TYPE" => "STRING",			
			"ADDITIONAL_VALUES" => "N",
		),
		"PROPERTY_URL" => array(
			"PARENT" => "PROPERTY",
			"NAME" => GetMessage("PROPERTY_URL"),
			"TYPE" => "STRING",			
			"ADDITIONAL_VALUES" => "N",
		),		
		"NON_AUTHORIZED_USER_CAN_COMMENT" => array(
			"PARENT" => "ACCESS",
			"NAME" => GetMessage("NON_AUTHORIZED_USER_CAN_COMMENT"),
			"TYPE" => "CHECKBOX",			
			"DEFAULT" => "N",
			"ADDITIONAL_VALUES" => "N",
		),
		"PRE_MODERATION" => array(
			"PARENT" => "ACCESS",
			"NAME" => GetMessage("PRE_MODERATION"),
			"TYPE" => "CHECKBOX",			
			"DEFAULT" => "N",
			"ADDITIONAL_VALUES" => "N",
		),
		"USE_CAPTCHA" => array(
			"PARENT" => "ACCESS",
			"NAME" => GetMessage("USE_CAPTCHA"),
			"TYPE" => "CHECKBOX",			
			"DEFAULT" => "N",
			"ADDITIONAL_VALUES" => "N",
		),
	)
);?>