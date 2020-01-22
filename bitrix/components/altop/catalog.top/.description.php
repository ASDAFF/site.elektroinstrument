<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("IBLOCK_MAIN_PAGE_TEMPLATE_NAME"),
	"DESCRIPTION" => GetMessage("IBLOCK_MAIN_PAGE_TEMPLATE_DESCRIPTION"),
	"ICON" => "/images/cat_all.gif",
	"CACHE_PATH" => "Y",
	"SORT" => 90,
	"PATH" => array(
		"ID" => "altop_tools",
		"NAME" => GetMessage("ALTOP_TOOLS")
	),
);

?>