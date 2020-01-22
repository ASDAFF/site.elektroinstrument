<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile(__FILE__);?>
<!DOCTYPE html>
<html lang="<?=LANGUAGE_ID?>">
<head>
	<link rel="shortcut icon" type="image/x-icon" href="<?=SITE_TEMPLATE_PATH?>/favicon.ico" />	
	<link rel="apple-touch-icon" sizes="57x57" href="<?=SITE_TEMPLATE_PATH?>/images/apple-touch-icon-114.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="<?=SITE_TEMPLATE_PATH?>/images/apple-touch-icon-114.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="<?=SITE_TEMPLATE_PATH?>/images/apple-touch-icon-144.png" />
	<link rel="apple-touch-icon" sizes="144x144" href="<?=SITE_TEMPLATE_PATH?>/images/apple-touch-icon-144.png" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?$APPLICATION->ShowTitle()?></title>
	<?$APPLICATION->SetAdditionalCSS("//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css");
	$APPLICATION->SetAdditionalCSS("http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700");
	$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/colors.css");	
	$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/js/anythingslider/slider.css");
	$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/js/custom-forms/custom-forms.css");
	$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/js/fancybox/jquery.fancybox-1.3.1.css");
	CJSCore::Init(array("jquery"));
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/jquery.cookie.js");
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/jquery.scrollUp.js");
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/moremenu.js");	
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/anythingslider/jquery.easing.1.2.js");
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/anythingslider/jquery.anythingslider.js");	
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/custom-forms/jquery.custom-forms.js");
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/fancybox/jquery.fancybox-1.3.1.pack.js");	
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/main.js");
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/script.js");
	$APPLICATION->ShowHead();?>	
</head>
<body>
	<?global $arSetting;?>
	<?$arSetting = $APPLICATION->IncludeComponent("altop:settings", "", array(), false);?>	
	<div class="bx-panel<?=($arSetting['CART_LOCATION']['VALUE'] == 'TOP') ? ' clvt' : ''?>">
		<?$APPLICATION->ShowPanel();?>
	</div>	
	<div class="body<?=($arSetting['CATALOG_LOCATION']['VALUE'] == 'HEADER') ? ' clvh' : ''?><?=($arSetting['CART_LOCATION']['VALUE'] == 'TOP') ? ' clvt' : ''?><?=($arSetting['CART_LOCATION']['VALUE'] == 'RIGHT') ? ' clvr' : ''?><?=($arSetting['CART_LOCATION']['VALUE'] == 'LEFT') ? ' clvl' : ''?>">
		<div class="page-wrapper">
			<div class="pop-up-bg callback_body"></div>
			<div class="pop-up callback">
				<a href="javascript:void(0)" class="pop-up-close callback_close"><i class="fa fa-times"></i></a>
				<div class="h1"><?=GetMessage("ALTOP_CALL_BACK");?></div>
				<?$APPLICATION->IncludeComponent("altop:callback", "",
					Array(
						"EMAIL_TO" => "",
						"REQUIRED_FIELDS" => array("NAME","TEL","TIME")
					),
					false
				);?>
			</div>
			<?if($arSetting["CATALOG_LOCATION"]["VALUE"] == "HEADER"):?>
				<div class="top-menu-header">					
					<div class="top-menu">							
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/top_menu.php"), false);?>
					</div>					
				</div>
			<?endif;?>
			<div class="center">
				<header>					
					<div class="header_1">
						<div class="logo">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/company_logo.php"), false);?>
						</div>
					</div>
					<div class="header_2">
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/header_search.php"), false);?>
					</div>
					<div class="header_3">
						<div class="schedule">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/schedule.php"), false);?>
						</div>
					</div>
					<div class="header_4">
						<div class="telephone">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/telephone.php"), false);?>
							<a class="btn_buy apuo callback_anch" href="#"><span class="cont"><i class="fa fa-phone"></i><span class="text"><?=GetMessage("ALTOP_CALL_BACK")?></span></span></a>
						</div>
					</div>
					<?if($arSetting["CATALOG_LOCATION"]["VALUE"] == "LEFT"):?>
						<div class="top-menu">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/top_menu.php"), false);?>
						</div>
					<?elseif($arSetting["CATALOG_LOCATION"]["VALUE"] == "HEADER"):?>
						<div class="top-catalog">							
							<?$APPLICATION->IncludeComponent("bitrix:menu", $arSetting["CATALOG_VIEW"]["VALUE"] == "FOUR_LEVELS" ? "tree" : "sections",
								array(
									"ROOT_MENU_TYPE" => "left",
									"MENU_CACHE_TYPE" => "A",
									"MENU_CACHE_TIME" => "86400",
									"MENU_CACHE_USE_GROUPS" => "Y",
									"MENU_CACHE_GET_VARS" => array(),
									"MAX_LEVEL" => "4",
									"CHILD_MENU_TYPE" => "left",
									"USE_EXT" => "Y",
									"DELAY" => "N",
									"ALLOW_MULTI_SELECT" => "N"
								),
								false
							);?>
						</div>
					<?endif;?>					
				</header>
				<div class="top_panel">
					<div class="panel_1">
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/sections.php"), false);?>
					</div>
					<div class="panel_2">
						<?$APPLICATION->IncludeComponent("bitrix:menu", "panel", 
							array(
								"ROOT_MENU_TYPE" => "top",
								"MENU_CACHE_TYPE" => "A",
								"MENU_CACHE_TIME" => "86400",
								"MENU_CACHE_USE_GROUPS" => "Y",
								"MENU_CACHE_GET_VARS" => array(),
								"MAX_LEVEL" => "3",
								"CHILD_MENU_TYPE" => "topchild",
								"USE_EXT" => "N",
								"ALLOW_MULTI_SELECT" => "N"
							),
							false
						);?>
					</div>
					<div class="panel_3">
						<ul class="contacts-vertical">
							<li>
								<a class="showcontacts" href="javascript:void(0)"><i class="fa fa-phone"></i></a>
							</li>
						</ul>
					</div>
					<div class="panel_4">
						<ul class="search-vertical">
							<li>
								<a class="showsearch" href="javascript:void(0)"><i class="fa fa-search"></i></a>
							</li>
						</ul>
					</div>
				</div>
				<div class="content-wrapper">
					<div class="content">
						<div class="left-column">
							<?if($APPLICATION->GetDirProperty("PERSONAL_SECTION")):?>
								<div class="h3"><?=GetMessage("PERSONAL_HEADER");?></div>
								<?$APPLICATION->IncludeComponent("altop:user", ".default", array(), false);?>
								<?$APPLICATION->IncludeComponent("bitrix:menu", "tree",
									array(
										"ROOT_MENU_TYPE" => "personal",
										"MENU_CACHE_TYPE" => "A",
										"MENU_CACHE_TIME" => "86400",
										"MENU_CACHE_USE_GROUPS" => "Y",
										"MENU_CACHE_GET_VARS" => array(),
										"MAX_LEVEL" => "1",
										"CHILD_MENU_TYPE" => "personal",
										"USE_EXT" => "Y",
										"DELAY" => "N",
										"ALLOW_MULTI_SELECT" => "N"
									),
									false
								);?>
							<?else:
								if($arSetting["CATALOG_LOCATION"]["VALUE"] == "LEFT"):?>
									<div class="h3"><?=GetMessage("BASE_HEADER");?></div>
									<?$APPLICATION->IncludeComponent("bitrix:menu", $arSetting["CATALOG_VIEW"]["VALUE"] == "FOUR_LEVELS" ? "tree" : "sections",
										array(
											"ROOT_MENU_TYPE" => "left",
											"MENU_CACHE_TYPE" => "A",
											"MENU_CACHE_TIME" => "86400",
											"MENU_CACHE_USE_GROUPS" => "Y",
											"MENU_CACHE_GET_VARS" => array(),
											"MAX_LEVEL" => "4",
											"CHILD_MENU_TYPE" => "left",
											"USE_EXT" => "Y",
											"DELAY" => "N",
											"ALLOW_MULTI_SELECT" => "N"
										),
										false
									);?>									
								<?endif;
							endif;
							if($arSetting["SMART_FILTER_LOCATION"]["VALUE"] == "VERTICAL"):
								$APPLICATION->ShowViewContent("filter_vertical");
							endif;
							if($arSetting["CATALOG_LOCATION"]["VALUE"] == "HEADER"):?>
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
									Array(
										"AREA_FILE_SHOW" => "file",
										"PATH" => SITE_DIR."include/banner_left.php",
										"AREA_FILE_RECURSIVE" => "N",
										"EDIT_MODE" => "html",
									),
									false,
									Array('HIDE_ICONS' => 'Y')
								);?>													
								<?if($APPLICATION->GetCurPage(true)!= SITE_DIR."index.php") {?>
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
										Array(
											"AREA_FILE_SHOW" => "file",
											"PATH" => SITE_DIR."include/discount_left.php",
											"AREA_FILE_RECURSIVE" => "N",
											"EDIT_MODE" => "html",
										),
										false,
										Array('HIDE_ICONS' => 'Y')
									);?>
								<?}?>
							<?endif;?>
							<ul class="new_leader_disc">
								<li>
									<a class="new" href="<?=SITE_DIR?>catalog/newproduct/">
										<span class="icon"><?=GetMessage("CR_TITLE_ICON_NEWPRODUCT")?></span>
										<span class="text"><?=GetMessage("CR_TITLE_NEWPRODUCT")?></span>
									</a>
								</li>
								<li>
									<a class="saleleader" href="<?=SITE_DIR?>catalog/saleleader/">
										<span class="icon"><?=GetMessage("CR_TITLE_ICON_SALELEADER")?></span>
										<span class="text"><?=GetMessage("CR_TITLE_SALELEADER")?></span>
									</a>
								</li>
								<li>
									<a class="discount" href="<?=SITE_DIR?>catalog/discount/">
										<span class="icon"><?=GetMessage("CR_TITLE_ICON_DISCOUNT")?></span>
										<span class="text"><?=GetMessage("CR_TITLE_DISCOUNT")?></span>
									</a>
								</li>
							</ul>							
							<?if($arSetting["CATALOG_LOCATION"]["VALUE"] == "LEFT"):?>
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
									Array(
										"AREA_FILE_SHOW" => "file",
										"PATH" => SITE_DIR."include/banner_left.php",
										"AREA_FILE_RECURSIVE" => "N",
										"EDIT_MODE" => "html",
									),
									false,
									Array('HIDE_ICONS' => 'Y')
								);?>													
								<?if($APPLICATION->GetCurPage(true)!= SITE_DIR."index.php") {?>
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
										Array(
											"AREA_FILE_SHOW" => "file",
											"PATH" => SITE_DIR."include/discount_left.php",
											"AREA_FILE_RECURSIVE" => "N",
											"EDIT_MODE" => "html",
										),
										false,
										Array('HIDE_ICONS' => 'Y')
									);?>
								<?}?>
							<?endif;?>
							<div class="vendors">
								<div class="h3"><?=GetMessage("MANUFACTURERS");?></div>
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
									Array(
										"AREA_FILE_SHOW" => "file",
										"PATH" => SITE_DIR."include/vendors_left.php",
										"AREA_FILE_RECURSIVE" => "N",
										"EDIT_MODE" => "html",
									),
									false,
									Array('HIDE_ICONS' => 'Y')
								);?>
							</div>
							<div class="subscribe">
								<div class="h3"><?=GetMessage("SUBSCRIBE");?></div>
								<p><?=GetMessage("SUBSCRIBE_TEXT");?></p>
								<?$APPLICATION->IncludeComponent("bitrix:subscribe.form", "left", 
									Array(
										"USE_PERSONALIZATION" => "Y",	
										"PAGE" => SITE_DIR."personal/subscribe/",
										"SHOW_HIDDEN" => "N",
										"CACHE_TYPE" => "A",
										"CACHE_TIME" => "86400",
										"CACHE_NOTES" => ""
									),
									false
								);?>
							</div>
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
								Array(
									"AREA_FILE_SHOW" => "file",
									"PATH" => SITE_DIR."include/stati_left.php",
									"AREA_FILE_RECURSIVE" => "N",
									"EDIT_MODE" => "html",
								),
								false,
								Array('HIDE_ICONS' => 'Y')
							);?>
						</div>
						<div class="workarea">
							<?if($APPLICATION->GetCurPage(true)== SITE_DIR."index.php") {?>
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
									Array(
										"AREA_FILE_SHOW" => "file",
										"PATH" => SITE_DIR."include/slider.php",
										"AREA_FILE_RECURSIVE" => "N",
										"EDIT_MODE" => "html",
									),
									false,
									Array('HIDE_ICONS' => 'Y')
								);?>
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
									Array(
										"AREA_FILE_SHOW" => "file",
										"PATH" => SITE_DIR."include/news_home.php",
										"AREA_FILE_RECURSIVE" => "N",
										"EDIT_MODE" => "html",
									),
									false,
									Array('HIDE_ICONS' => 'Y')
								);?>
								<div class="ndl_tabs">
									<div class="section">
										<ul class="tabs">
											<li class="new">
												<a href="javascript:void(0)"><span><?=GetMessage("CR_TITLE_NEWPRODUCT")?></span></a>
											</li>
											<li class="hit">
												<a href="javascript:void(0)"><span><?=GetMessage("CR_TITLE_SALELEADER")?></span></a>
											</li>
											<li class="discount">
												<a href="javascript:void(0)"><span><?=GetMessage("CR_TITLE_DISCOUNT")?></span></a>
											</li>
										</ul>
										<div class="new box">
											<div class="catalog-top">
												<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
													Array(
														"AREA_FILE_SHOW" => "file",
														"PATH" => SITE_DIR."include/newproduct.php",
														"AREA_FILE_RECURSIVE" => "N",
														"EDIT_MODE" => "html",
													),
													false,
													Array('HIDE_ICONS' => 'Y')
												);?>
												<a class="all" href="<?=SITE_DIR?>catalog/newproduct/"><?=GetMessage("CR_TITLE_ALL_NEWPRODUCT");?></a>
											</div>
										</div>
										<div class="hit box">
											<div class="catalog-top">
												<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
													Array(
														"AREA_FILE_SHOW" => "file",
														"PATH" => SITE_DIR."include/saleleader.php",
														"AREA_FILE_RECURSIVE" => "N",
														"EDIT_MODE" => "html",
													),
													false,
													Array('HIDE_ICONS' => 'Y')
												);?>
												<a class="all" href="<?=SITE_DIR?>catalog/saleleader/"><?=GetMessage("CR_TITLE_ALL_SALELEADER");?></a>
											</div>
										</div>
										<div class="discount box">
											<div class="catalog-top">
												<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
													Array(
														"AREA_FILE_SHOW" => "file",
														"PATH" => SITE_DIR."include/discount.php",
														"AREA_FILE_RECURSIVE" => "N",
														"EDIT_MODE" => "html",
													),
													false,
													Array('HIDE_ICONS' => 'Y')
												);?>
												<a class="all" href="<?=SITE_DIR?>catalog/discount/"><?=GetMessage("CR_TITLE_ALL_DISCOUNT");?></a>
											</div>
										</div>
									</div>
								</div>
								<div class="clr"></div>
							<?}?>
							<div class="body_text" style="<?if ($APPLICATION->GetCurPage(true)== SITE_DIR."index.php"): echo 'padding:0px 15px;'; else: echo 'padding:0px;'; endif;?>">
								<?if($APPLICATION->GetCurPage(true)!= SITE_DIR."index.php"):?>
									<div class="breadcrumb-search">
										<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", ".default", 
											array(
												"START_FROM" => "1",
												"PATH" => "",
												"SITE_ID" => "-"
											),
											false,
											Array('HIDE_ICONS' => 'Y')
										);?>
										<div class="podelitsya">											
											<script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script>
											<div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="small" data-yashareQuickServices="vkontakte,facebook,twitter,odnoklassniki,gplus" data-yashareTheme="counter"></div>
										</div>
										<div class="clr"></div>
									</div>
									<h1><?=$APPLICATION->ShowTitle(false);?></h1>
								<?endif;?>		