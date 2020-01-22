<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if (!defined("WIZARD_SITE_ID"))
	return;

if (!defined("WIZARD_SITE_DIR"))
	return;

if(COption::GetOptionString("elektroinstrument", "wizard_installed", "N") == 'N'){
	$file = fopen(WIZARD_SITE_ROOT_PATH."/bitrix/php_interface/init.php", "ab");
	fwrite($file, file_get_contents(WIZARD_ABSOLUTE_PATH."/site/services/main/bitrix/init.php"));
	fclose($file);
	COption::SetOptionString("elektroinstrument", "wizard_installed", "Y");
}

if(COption::GetOptionString("elektroinstrument", "wizard_installed", "N", WIZARD_SITE_ID) == "Y" && !WIZARD_INSTALL_DEMO_DATA) {
	$wizard =& $this->GetWizard();
		
	___writeToAreasFile(WIZARD_SITE_PATH."include/copyright.php", $wizard->GetVar("siteCopy"));
	___writeToAreasFile(WIZARD_SITE_PATH."include/schedule.php", $wizard->GetVar("siteSchedule"));
	___writeToAreasFile(WIZARD_SITE_PATH."include/telephone.php", $wizard->GetVar("siteTelephone"));

	if($wizard->GetVar('rewriteIndex', true)){
		if($wizard->GetVar('siteLogoSet', true)){
			CopyDirFiles(
				WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/_index_.php",
				WIZARD_SITE_PATH."/_index.php",
				$rewrite = true,
				$recursive = true,
				$delete_after_copy = false
			);
		} else {
			CopyDirFiles(
				WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/_index.php",
				WIZARD_SITE_PATH."/_index.php",
				$rewrite = true,
				$recursive = true,
				$delete_after_copy = false
			);
		}
		CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/_index.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
	}
	return;
}

$path = str_replace("//", "/", WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/"); 

$handle = @opendir($path);
if($handle) {
	while($file = readdir($handle)) {
		if(in_array($file, array(".", "..")))
			continue; 
		
		CopyDirFiles(
			$path.$file,
			WIZARD_SITE_PATH."/".$file,
			$rewrite = true, 
			$recursive = true,
			$delete_after_copy = false,
			$exclude = "bitrix"
		);
	}
}

WizardServices::PatchHtaccess(WIZARD_SITE_PATH);

WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH, Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."content/contacts/", Array("SALE_EMAIL" => $wizard->GetVar("shopEmail")));

copy(WIZARD_THEME_ABSOLUTE_PATH."/favicon.ico", WIZARD_SITE_PATH."favicon.ico");

$arUrlRewrite = array(); 
if(file_exists(WIZARD_SITE_ROOT_PATH."/urlrewrite.php")) {
	include(WIZARD_SITE_ROOT_PATH."/urlrewrite.php");
}

$arNewUrlRewrite[] =
	array(
		"CONDITION"	=>	"#^".WIZARD_SITE_DIR."personal/order/#",
		"RULE"	=>	"",
		"ID"	=>	"bitrix:sale.personal.order",
		"PATH"	=>	 WIZARD_SITE_DIR."personal/order/index.php",
		);
$arNewUrlRewrite[] =
	array(
		"CONDITION"	=>	"#^".WIZARD_SITE_DIR."catalog/#",
		"RULE"	=>	"",
		"ID"	=>	"bitrix:catalog",
		"PATH"	=>	 WIZARD_SITE_DIR."catalog/index.php",
		);	
$arNewUrlRewrite[] =
	array(
		"CONDITION"	=>	"#^".WIZARD_SITE_DIR."news/#",
		"RULE"	=>	"",
		"ID"	=>	"bitrix:news",
		"PATH"	=>	 WIZARD_SITE_DIR."news/index.php",
		);
$arNewUrlRewrite[] =
	array(
		"CONDITION"	=>	"#^".WIZARD_SITE_DIR."reviews/#",
		"RULE"	=>	"",
		"ID"	=>	"bitrix:news",
		"PATH"	=>	 WIZARD_SITE_DIR."reviews/index.php",
		);
$arNewUrlRewrite[] =
	array(
		"CONDITION"	=>	"#^".WIZARD_SITE_DIR."vendors/#",
		"RULE"	=>	"",
		"ID"	=>	"bitrix:news",
		"PATH"	=>	 WIZARD_SITE_DIR."vendors/index.php",
		);

foreach ($arNewUrlRewrite as $arUrl) {
	if(!in_array($arUrl, $arUrlRewrite)) {
		CUrlRewriter::Add($arUrl);
	}
}

function ___writeToAreasFile($fn, $text) {
	if(file_exists($fn) && !is_writable($abs_path) && defined("BX_FILE_PERMISSIONS"))
		@chmod($abs_path, BX_FILE_PERMISSIONS);

	$fd = @fopen($fn, "wb");
	if(!$fd)
		return false;

	if(false === fwrite($fd, $text)) {
		fclose($fd);
		return false;
	}

	fclose($fd);

	if(defined("BX_FILE_PERMISSIONS"))
		@chmod($fn, BX_FILE_PERMISSIONS);
}

CheckDirPath(WIZARD_SITE_PATH."include/");

$wizard =& $this->GetWizard();

___writeToAreasFile(WIZARD_SITE_PATH."include/copyright.php", $wizard->GetVar("siteCopy"));
___writeToAreasFile(WIZARD_SITE_PATH."include/schedule.php", $wizard->GetVar("siteSchedule"));
___writeToAreasFile(WIZARD_SITE_PATH."include/telephone.php", $wizard->GetVar("siteTelephone"));

CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/index.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/.section.php", array("SITE_DESCRIPTION" => $wizard->GetVar("siteMetaDescription")));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/.section.php", array("SITE_KEYWORDS" => $wizard->GetVar("siteMetaKeywords")));
?>