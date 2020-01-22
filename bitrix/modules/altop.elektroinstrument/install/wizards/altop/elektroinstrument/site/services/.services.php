<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arServices = Array(
	"main" => Array(
		"NAME" => GetMessage("SERVICE_MAIN_SETTINGS"),
		"STAGES" => Array(
			"site_create.php", // Create site
			"files.php", // Copy bitrix files
			"search.php", // Indexing files
			"template.php", // Install template
			"theme.php", // Install theme
			"menu.php", // Install menu
			"settings.php",
		),
	),
	"iblock" => Array(
		"NAME" => GetMessage("SERVICE_IBLOCK_DEMO_DATA"),
		"STAGES" => Array(
			"types.php",
			"vendors.php",
			"colors.php",
			"catalog.php", //catalog iblock import
			"catalog2.php", //offers iblock import
			"catalog3.php",
			"comments.php",
			"news.php",
			"stati.php",
			"banner_left.php",
			"slider.php",
			"join_us.php",
			"payments_icons.php",
			"payments.php",
		),
	),
	"sale" => Array(
		"NAME" => GetMessage("SERVICE_SALE_DEMO_DATA"),
		"STAGES" => Array(
			"locations.php",
			"step1.php",
			"step2.php",
		),
	),
	"catalog" => Array(
		"NAME" => GetMessage("SERVICE_CATALOG_SETTINGS")
	),
);?>