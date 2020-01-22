<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if(!CModule::IncludeModule("iblock"))
	return;
	
$arTypes =Array( 
	Array(
		'ID'=>'catalog',
		'SECTIONS'=>'Y',
		'IN_RSS'=>'N',
		'SORT'=>10,
		'LANG'=>Array()
	),
	Array(
		'ID'=>'content',
		'SECTIONS'=>'Y',
		'IN_RSS'=>'N',
		'SORT'=>20,
		'LANG'=>Array()
	),
);
$arLanguages = Array();
$rsLanguage = CLanguage::GetList($by, $order, array());
while($arLanguage = $rsLanguage->Fetch())
	$arLanguages[] = $arLanguage["LID"];
			
GLOBAL $DB;
$iblockType = new CIBlockType;	

foreach ($arTypes as $arType)
{
	$dbType = CIBlockType::GetList(Array(),Array("=ID" => $arType["ID"]));
	
	if($dbType->Fetch())
		continue;

	foreach($arLanguages as $languageID)
	{
		WizardServices::IncludeServiceLang("types.php", $languageID);

		$code = strtoupper($arType["ID"]);
		
		$arType["LANG"][$languageID]["NAME"] = GetMessage($code."_TYPE_NAME");
		$arType["LANG"][$languageID]["ELEMENT_NAME"] = GetMessage($code."_ELEMENT_NAME");

		if ($arType["SECTIONS"] == "Y")
			$arType["LANG"][$languageID]["SECTION_NAME"] = GetMessage($code."_SECTION_NAME");
	}
	
		$DB->StartTransaction();
		
		$res = $iblockType->Add($arType);
	
		if(!$res){
			$DB->Rollback();
			$this->SetError('Error: '.$obBlocktype->LAST_ERROR);
			
		}
		else $DB->Commit();							
		
};
							
?>