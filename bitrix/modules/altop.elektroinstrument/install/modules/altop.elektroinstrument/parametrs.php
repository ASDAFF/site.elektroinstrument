<?
$moduleClass = "CElektroinstrument";
$moduleID = "altop.elektroinstrument";
IncludeModuleLangFile(__FILE__);

//initialize module parametrs list and default values
$moduleClass::$arParametrsList = array(
	"MAIN" => array(
		"TITLE" => GetMessage("MAIN_OPTIONS"),
		"OPTIONS" => array(
			"SHOW_SETTINGS_PANEL" => array(
				"TITLE" => GetMessage("SHOW_SETTINGS_PANEL"),
				"TYPE" => "checkbox",
				"DEFAULT" => "Y",
				"IN_SETTINGS_PANEL" => "N"
			),
			"CATALOG_LOCATION" => array(
				"TITLE" => GetMessage("CATALOG_LOCATION"),
				"TYPE" => "selectbox", 
				"LIST" => array(					
					"LEFT" => GetMessage("CATALOG_LOCATION_LEFT"),
					"HEADER" => GetMessage("CATALOG_LOCATION_HEADER"),
				),
				"DEFAULT" => "LEFT",
				"IN_SETTINGS_PANEL" => "Y"
			),
			"CATALOG_VIEW" => array(
				"TITLE" => GetMessage("CATALOG_VIEW"),
				"TYPE" => "selectbox", 
				"LIST" => array(					
					"TWO_LEVELS" => GetMessage("CATALOG_VIEW_TWO_LEVELS"),
					"FOUR_LEVELS" => GetMessage("CATALOG_VIEW_FOUR_LEVELS"),
				),
				"DEFAULT" => "TWO_LEVELS",
				"IN_SETTINGS_PANEL" => "Y"
			),
			"SMART_FILTER_LOCATION" => array(
				"TITLE" => GetMessage("SMART_FILTER_LOCATION"),
				"TYPE" => "selectbox", 
				"LIST" => array(					
					"VERTICAL" => GetMessage("SMART_FILTER_LOCATION_VERTICAL"),
					"HORIZONTAL" => GetMessage("SMART_FILTER_LOCATION_HORIZONTAL"),
				),
				"DEFAULT" => "HORIZONTAL",
				"IN_SETTINGS_PANEL" => "Y"
			),
			"CART_LOCATION" => array(
				"TITLE" => GetMessage("CART_LOCATION"),
				"TYPE" => "selectbox", 
				"LIST" => array(					
					"BOTTOM" => GetMessage("CART_LOCATION_BOTTOM"),
					"TOP" => GetMessage("CART_LOCATION_TOP"),
					"RIGHT" => GetMessage("CART_LOCATION_RIGHT"),
					"LEFT" => GetMessage("CART_LOCATION_LEFT"),
				),
				"DEFAULT" => "BOTTOM",
				"IN_SETTINGS_PANEL" => "Y"
			),
			"PRODUCT_TABLE_VIEW" => array(
				"TITLE" => GetMessage("PRODUCT_TABLE_VIEW"),
				"TYPE" => "multiselectbox",
				"LIST" => array(
					"ARTNUMBER" => GetMessage("PRODUCT_TABLE_VIEW_ARTNUMBER"),
					"RATING" => GetMessage("PRODUCT_TABLE_VIEW_RATING"),
					"PREVIEW_TEXT" => GetMessage("PRODUCT_TABLE_VIEW_PREVIEW_TEXT"),
					"OLD_PRICE" => GetMessage("PRODUCT_TABLE_VIEW_OLD_PRICE"),
					"PERCENT_PRICE" => GetMessage("PRODUCT_TABLE_VIEW_PERCENT_PRICE"),					
				),
				"DEFAULT" => array("ARTNUMBER", "RATING", "PREVIEW_TEXT", "OLD_PRICE", "PERCENT_PRICE"),
				"IN_SETTINGS_PANEL" => "Y"
			),			
			"GENERAL_SETTINGS" => array(
				"TITLE" => GetMessage("GENERAL_SETTINGS"),
				"TYPE" => "multiselectbox",
				"LIST" => array(					
					"PRODUCT_QUANTITY" => GetMessage("GENERAL_SETTINGS_PRODUCT_QUANTITY"),
				),
				"DEFAULT" => array("PRODUCT_QUANTITY"),
				"IN_SETTINGS_PANEL" => "Y"
			)			
		)
	)
);?>