<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile(__FILE__);
if(defined("ERROR_404") && ERROR_404 == "Y" && $APPLICATION->GetCurPage(true) !='/404.php') LocalRedirect('/404.php');?>
							</div>
						</div>
						<?if($APPLICATION->GetCurPage(true)== SITE_DIR."index.php"):?>
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
								Array(
									"AREA_FILE_SHOW" => "file",
									"PATH" => SITE_DIR."include/vendors_bottom.php",
									"AREA_FILE_RECURSIVE" => "N",
									"EDIT_MODE" => "html",
								),
								false,
								Array('HIDE_ICONS' => 'Y')
							);?>
						<?endif;?>
						<?if(!CSite::InDir('/reviews/')):?>
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
								Array(
									"AREA_FILE_SHOW" => "file",
									"PATH" => SITE_DIR."include/stati_bottom.php",
									"AREA_FILE_RECURSIVE" => "N",
									"EDIT_MODE" => "html",
								),
								false,
								Array('HIDE_ICONS' => 'Y')
							);?>
						<?endif;?>
					</div>
					<?$APPLICATION->IncludeComponent("bitrix:subscribe.form", "bottom", 
						array(
							"USE_PERSONALIZATION" => "Y",	
							"PAGE" => "/personal/subscribe/",
							"SHOW_HIDDEN" => "N",
							"CACHE_TYPE" => "A",
							"CACHE_TIME" => "86400",
							"CACHE_NOTES" => ""
						),
						false
					);?>
					<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/viewed_products.php"), false);?>
				</div>
				<footer>
					<div class="footer_menu_soc_pay">
						<div class="footer_menu">
							<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", 
								array(
									"ROOT_MENU_TYPE" => "footer1",
									"MENU_CACHE_TYPE" => "A",
									"MENU_CACHE_TIME" => "86400",
									"MENU_CACHE_USE_GROUPS" => "Y",
									"MENU_CACHE_GET_VARS" => array(),
									"MAX_LEVEL" => "1",
									"CHILD_MENU_TYPE" => "",
									"USE_EXT" => "N",
									"ALLOW_MULTI_SELECT" => "N"
								)
							);?>
							<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", 
								array(
									"ROOT_MENU_TYPE" => "footer2",
									"MENU_CACHE_TYPE" => "A",
									"MENU_CACHE_TIME" => "86400",
									"MENU_CACHE_USE_GROUPS" => "Y",
									"MENU_CACHE_GET_VARS" => array(),
									"MAX_LEVEL" => "1",
									"CHILD_MENU_TYPE" => "",
									"USE_EXT" => "N",
									"ALLOW_MULTI_SELECT" => "N"
								)
							);?>
							<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", 
								array(
									"ROOT_MENU_TYPE" => "footer3",
									"MENU_CACHE_TYPE" => "A",
									"MENU_CACHE_TIME" => "86400",
									"MENU_CACHE_USE_GROUPS" => "Y",
									"MENU_CACHE_GET_VARS" => array(),
									"MAX_LEVEL" => "1",
									"CHILD_MENU_TYPE" => "",
									"USE_EXT" => "N",
									"ALLOW_MULTI_SELECT" => "N"
								)
							);?>
							<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", 
								array(
									"ROOT_MENU_TYPE" => "footer4",
									"MENU_CACHE_TYPE" => "A",
									"MENU_CACHE_TIME" => "86400",
									"MENU_CACHE_USE_GROUPS" => "Y",
									"MENU_CACHE_GET_VARS" => array(),
									"MAX_LEVEL" => "1",
									"CHILD_MENU_TYPE" => "",
									"USE_EXT" => "N",
									"ALLOW_MULTI_SELECT" => "N"
								)
							);?>
						</div>
						<div class="footer_soc_pay">							
							<div class="footer_soc">
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/join_us.php"), false);?>
							</div>
							<div class="footer_pay">
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/payments_icons.php"), false);?>
							</div>
						</div>
					</div>
					<div class="footer_left">
						<div class="copyright">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/copyright.php"), false);?>
						</div>
					</div>
					<div class="footer_center">
						<div class="footer-links">
							<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", 
								array(
									"ROOT_MENU_TYPE" => "bottom",
									"MENU_CACHE_TYPE" => "A",
									"MENU_CACHE_TIME" => "86400",
									"MENU_CACHE_USE_GROUPS" => "Y",
									"MENU_CACHE_GET_VARS" => array(),
									"MAX_LEVEL" => "1",
									"CHILD_MENU_TYPE" => "",
									"USE_EXT" => "N",
									"ALLOW_MULTI_SELECT" => "N"
								)
							);?>
						</div>
					</div>
					<div class="footer_right">
						<div class="counters">
							<div class="counter_1">
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/counter_1.php"), false);?>
							</div>
							<div class="counter_2">
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/counter_2.php"), false);?>
							</div>
						</div>
						<div class="footer-design">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/developer.php"), false);?>
						</div>
					</div>
					<div class="foot_panel_all">
						<div class="foot_panel">
							<div class="foot_panel_1">
								<?$APPLICATION->IncludeComponent("bitrix:system.auth.form", "login",
									Array(
										"REGISTER_URL" => SITE_DIR."personal/profile/",
										"FORGOT_PASSWORD_URL" => SITE_DIR."personal/profile/",
										"PROFILE_URL" => SITE_DIR."personal/profile/",
										"SHOW_ERRORS" => "N" 
									 )
								);?>
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
									Array(
										"AREA_FILE_SHOW" => "file", 
										"PATH" => SITE_DIR."include/footer_compare.php"
									),
									false
								);?>
								<?$APPLICATION->IncludeComponent("altop:sale.basket.delay", ".default", 
									Array(
										"PATH_TO_DELAY" => SITE_DIR."personal/cart/?delay=Y",
									),
									false
								);?>
							</div>
							<div class="foot_panel_2">
								<?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", ".default", 
									Array(
										"PATH_TO_BASKET" => SITE_DIR."personal/cart/",
										"PATH_TO_ORDER" => SITE_DIR."personal/order/make/",
										"SHOW_NUM_PRODUCTS" => "Y",
										"SHOW_TOTAL_PRICE" => "Y",
										"SHOW_EMPTY_VALUES" => "Y",
									),
									false
								);?>
							</div>
						</div>
					</div>
				</footer>				
				<div class="pop-up-bg" id="bgmod"></div>
				<div class="pop-up modal" id="addItemInCart">
					<a href="javascript:void(0)" class="pop-up-close close button"><i class="fa fa-times"></i></a>
					<div class="h1"><?=GetMessage("FOOTER_ADD_TO_BASKET")?></div>					
					<div class="cont">
						<div class="item_image_cont">
							<div class="item_image_full"></div>
						</div>
						<div class="item_title"></div>						
						<div class="item_links">
							<button name="close" class="btn_buy ppp close" value="<?=GetMessage("FOOTER_CLOSE")?>"><?=GetMessage("FOOTER_CLOSE")?></button>					
							<form action="<?=SITE_DIR.'personal/cart/'?>" method="post">
								<button name="order" class="btn_buy popdef order" value="<?=GetMessage("FOOTER_ORDER")?>"><?=GetMessage("FOOTER_ORDER")?></button>
							</form>
							<div class="clr"></div>
						</div>
					</div>					
				</div>
				<div class="clr"></div>
			</div>
		</div>
	</div>
</body>
</html>