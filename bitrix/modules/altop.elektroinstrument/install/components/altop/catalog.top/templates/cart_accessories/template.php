<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(count($arResult["ITEMS"]) < 1)
	return;

global $arSetting;?>

<script type="text/javascript">
	//<![CDATA[
	$(function() {
		$(".add2basket_form").submit(function() {
			var form = $(this);
			$.post($(this).attr("action"), $(this).serialize(), function(data) {
				try {
					$.post("/ajax/basket_line.php", function(data) {
						$(".cart_line").replaceWith(data);
					});
					$.post("/ajax/delay_line.php", function(data) {
						$(".delay_line").replaceWith(data);
					});
					form.children(".btn_buy").addClass("hidden");
					form.children(".result").removeClass("hidden");
					location="/personal/cart/";
				} catch (e) {}
			});
			return false;
		});
	});
	//]]>
</script>

<div class="cart-accessories">
	<div class="h3"><?=GetMessage("ACCESSORIES_CART_ITEMS")?></div>
	<div class="catalog-item-cards">
		<?foreach($arResult["ITEMS"] as $key => $arItem):
		
			$strMainID = $this->GetEditAreaId($arItem["ID"]);
			$arItemIDs = array(
				"ID" => $strMainID
			);

			$bPicture = is_array($arItem["PREVIEW_IMG"]);

			$sticker = "";
			if(array_key_exists("PROPERTIES", $arItem) && is_array($arItem["PROPERTIES"])):
				if(array_key_exists("NEWPRODUCT", $arItem["PROPERTIES"]) && !$arItem["PROPERTIES"]["NEWPRODUCT"]["VALUE"] == false):
					$sticker .= "<span class='new'>".GetMessage("CATALOG_ELEMENT_NEWPRODUCT")."</span>";
				endif;
				if(array_key_exists("SALELEADER", $arItem["PROPERTIES"]) && !$arItem["PROPERTIES"]["SALELEADER"]["VALUE"] == false):
					$sticker .= "<span class='hit'>".GetMessage("CATALOG_ELEMENT_SALELEADER")."</span>";
				endif;
				if(array_key_exists("DISCOUNT", $arItem["PROPERTIES"]) && !$arItem["PROPERTIES"]["DISCOUNT"]["VALUE"] == false):
					if(isset($arItem["OFFERS"]) && !empty($arItem["OFFERS"])):						
						if($arItem["OFFERS_MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"] > 0):
							$sticker .= "<span class='discount'>-".$arItem["OFFERS_MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"]."%</span>";
						else:
							$sticker .= "<span class='discount'>%</span>";
						endif;
					else:
						if($arItem["MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"] > 0):
							$sticker .= "<span class='discount'>-".$arItem["MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"]."%</span>";
						else:
							$sticker .= "<span class='discount'>%</span>";
						endif;
					endif;
				endif;
			endif;?>

			<div class="catalog-item-card">
				<div class="catalog-item-info">					
					<div class="item-image">
						<?if($bPicture):?>
							<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
								<img class="item_img" src="<?=$arItem['PREVIEW_IMG']['SRC']?>" width="<?=$arItem['PREVIEW_IMG']['WIDTH']?>" height="<?=$arItem['PREVIEW_IMG']['HEIGHT']?>" alt="<?=$arItem['NAME']?>" />
								<span class="sticker">
									<?=$sticker?>
								</span>
								<?if(!empty($arItem["PROPERTIES"]["MANUFACTURER"]["PREVIEW_IMG"]["SRC"])):?>
									<img class="manufacturer" src="<?=$arItem['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['SRC']?>" width="<?=$arItem['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['WIDTH']?>" height="<?=$arItem['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['HEIGHT']?>" alt="<?=$arItem['PROPERTIES']['MANUFACTURER']['NAME']?>" />
								<?endif;?>
							</a>
						<?else:?>
							<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
								<img class="item_img" src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" width="150" height="150" alt="<?=$arItem['NAME']?>" />
								<span class="sticker">
									<?=$sticker?>
								</span>
								<?if(!empty($arItem["PROPERTIES"]["MANUFACTURER"]["PREVIEW_IMG"]["SRC"])):?>
									<img class="manufacturer" src="<?=$arItem['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['SRC']?>" width="<?=$arItem['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['WIDTH']?>" height="<?=$arItem['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['HEIGHT']?>" alt="<?=$arItem['PROPERTIES']['MANUFACTURER']['NAME']?>" />
								<?endif;?>
							</a>
						<?endif?>
					</div>
					<div class="item-all-title">
						<a class="item-title" href="<?=$arItem['DETAIL_PAGE_URL']?>" title="<?=$arItem['NAME']?>">
							<?=$arItem["NAME"]?>
						</a>
					</div>
					<?if(in_array("ARTNUMBER", $arSetting["PRODUCT_TABLE_VIEW"]["VALUE"]) || in_array("RATING", $arSetting["PRODUCT_TABLE_VIEW"]["VALUE"])):?>
						<div class="article_rating">
							<?if(in_array("ARTNUMBER", $arSetting["PRODUCT_TABLE_VIEW"]["VALUE"])):?>
								<div class="article">
									<?=GetMessage("CATALOG_ELEMENT_ARTNUMBER")?><?=!empty($arItem["PROPERTIES"]["ARTNUMBER"]["VALUE"]) ? $arItem["PROPERTIES"]["ARTNUMBER"]["VALUE"] : "-";?>
								</div>
							<?endif;
							if(in_array("RATING", $arSetting["PRODUCT_TABLE_VIEW"]["VALUE"])):?>
								<div class="rating">
									<?$APPLICATION->IncludeComponent("bitrix:iblock.vote", "ajax",
										Array(
											"DISPLAY_AS_RATING" => "vote_avg",
											"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
											"IBLOCK_ID" => $arParams["IBLOCK_ID"],
											"ELEMENT_ID" => $arItem["ID"],
											"ELEMENT_CODE" => "",
											"MAX_VOTE" => "5",
											"VOTE_NAMES" => array("1","2","3","4","5"),
											"SET_STATUS_404" => "N",
											"CACHE_TYPE" => $arParams["CACHE_TYPE"],
											"CACHE_TIME" => $arParams["CACHE_TIME"],
											"CACHE_NOTES" => "",
											"READ_ONLY" => "Y"
										),
										false,
										array("HIDE_ICONS" => "Y")
									);?>
								</div>
							<?endif;?>
							<div class="clr"></div>
						</div>
					<?endif;?>
					<?if(in_array("PREVIEW_TEXT", $arSetting["PRODUCT_TABLE_VIEW"]["VALUE"])):?>
						<div class="item-desc">
							<?=strip_tags($arItem["PREVIEW_TEXT"]);?>
						</div>
					<?endif;?>
					<div class="item-price-cont<?=(!in_array('OLD_PRICE', $arSetting['PRODUCT_TABLE_VIEW']['VALUE']) && !in_array('PERCENT_PRICE', $arSetting['PRODUCT_TABLE_VIEW']['VALUE'])) ? ' one' : ''?><?=(in_array('OLD_PRICE', $arSetting['PRODUCT_TABLE_VIEW']['VALUE']) && !in_array('PERCENT_PRICE', $arSetting['PRODUCT_TABLE_VIEW']['VALUE'])) || (!in_array('OLD_PRICE', $arSetting['PRODUCT_TABLE_VIEW']['VALUE']) && in_array('PERCENT_PRICE', $arSetting['PRODUCT_TABLE_VIEW']['VALUE'])) ? ' two' : ''?>">						
						<?if(isset($arItem["OFFERS"]) && !empty($arItem["OFFERS"])):
							$price = CCurrencyLang::GetCurrencyFormat($arItem["OFFERS_MIN_PRICE"]["CURRENCY"], "ru");
							if(empty($price["THOUSANDS_SEP"])):
								$price["THOUSANDS_SEP"] = " ";
							endif;
							$currency = str_replace("#", " ", $price["FORMAT_STRING"]);
						
							if($arItem["OFFERS_MIN_PRICE"]["VALUE"] == 0):?>
								<div class="item-no-price">
									<span class="unit">
										<?=GetMessage("CATALOG_ELEMENT_NO_PRICE")?>
										<span><?=(!empty($arItem["OFFERS_MIN_PRICE"]["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arItem["OFFERS_MIN_PRICE"]["CATALOG_MEASURE_NAME"] : "";?></span>
									</span>									
								</div>
							<?elseif($arItem["OFFERS_MIN_PRICE"]["DISCOUNT_VALUE"] < $arItem["OFFERS_MIN_PRICE"]["VALUE"]):?>
								<div class="item-price">
									<?if(in_array("OLD_PRICE", $arSetting["PRODUCT_TABLE_VIEW"]["VALUE"])):?>
										<span class="catalog-item-price-old">
											<?=number_format($arItem["OFFERS_MIN_PRICE"]["VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
											<?=$currency;?>
										</span>
									<?endif;
									if(in_array("PERCENT_PRICE", $arSetting["PRODUCT_TABLE_VIEW"]["VALUE"])):?>
										<span class="catalog-item-price-percent">
											<?=GetMessage("CATALOG_ELEMENT_SKIDKA")." ".$arItem["OFFERS_MIN_PRICE"]["PRINT_DISCOUNT_DIFF"];?>
										</span>
									<?endif;?>
									<span class="catalog-item-price">
										<?=number_format($arItem["OFFERS_MIN_PRICE"]["DISCOUNT_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
										<span class="unit">
											<?=$currency?>
											<span><?=(!empty($arItem["OFFERS_MIN_PRICE"]["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arItem["OFFERS_MIN_PRICE"]["CATALOG_MEASURE_NAME"] : "";?></span>
										</span>
									</span>											
								</div>
							<?else:?>
								<div class="item-price">
									<span class="catalog-item-price">
										<?=number_format($arItem["OFFERS_MIN_PRICE"]["VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
										<span class="unit">
											<?=$currency?>
											<span><?=(!empty($arItem["OFFERS_MIN_PRICE"]["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arItem["OFFERS_MIN_PRICE"]["CATALOG_MEASURE_NAME"] : "";?></span>
										</span>
									</span>											
								</div>
							<?endif;
						else:
							foreach($arItem["PRICES"] as $code=>$arPrice):
								if($arPrice["MIN_PRICE"] == "Y"):
									if($arPrice["CAN_ACCESS"]):
										
										$price = CCurrencyLang::GetCurrencyFormat($arPrice["CURRENCY"], "ru");
										if(empty($price["THOUSANDS_SEP"])):
											$price["THOUSANDS_SEP"] = " ";
										endif;
										$currency = str_replace("#", " ", $price["FORMAT_STRING"]);

										if($arPrice["VALUE"] == 0):
											$arItem["ASK_PRICE"]=1;?>
											<div class="item-no-price">
												<span class="unit">
													<?=GetMessage("CATALOG_ELEMENT_NO_PRICE")?>
													<span><?=(!empty($arItem["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arItem["CATALOG_MEASURE_NAME"] : "";?></span>
												</span>												
											</div>
										<?elseif($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
											<div class="item-price">
												<?if(in_array("OLD_PRICE", $arSetting["PRODUCT_TABLE_VIEW"]["VALUE"])):?>
													<span class="catalog-item-price-old">
														<?=number_format($arPrice["VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
														<?=$currency;?>
													</span>
												<?endif;
												if(in_array("PERCENT_PRICE", $arSetting["PRODUCT_TABLE_VIEW"]["VALUE"])):?>
													<span class="catalog-item-price-percent">
														<?=GetMessage("CATALOG_ELEMENT_SKIDKA")." ".$arPrice["PRINT_DISCOUNT_DIFF"];?>
													</span>
												<?endif;?>
												<span class="catalog-item-price">
													<?=number_format($arPrice["DISCOUNT_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
													<span class="unit">
														<?=$currency?>
														<span><?=(!empty($arItem["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arItem["CATALOG_MEASURE_NAME"] : "";?></span>
													</span>
												</span>														
											</div>
										<?else:?>
											<div class="item-price">
												<span class="catalog-item-price">
													<?=number_format($arPrice["VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
													<span class="unit">
														<?=$currency?>
														<span><?=(!empty($arItem["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arItem["CATALOG_MEASURE_NAME"] : "";?></span>
													</span>
												</span>														
											</div>
										<?endif;
									endif;
								endif;
							endforeach;
						endif;?>
					</div>
					<div class="buy_more">
						<?if(isset($arItem["OFFERS"]) && !empty($arItem["OFFERS"])):?>
							<div class="available">
								<?if($arItem["OFFERS_MIN_PRICE"]["CAN_BUY"]):?>
									<div class="avl">
										<i class="fa fa-check-circle"></i>
										<span><?=GetMessage("CATALOG_ELEMENT_AVAILABLE")?><?=in_array("PRODUCT_QUANTITY", $arSetting["GENERAL_SETTINGS"]["VALUE"]) ? " ".$arItem["OFFERS_MIN_PRICE"]["CATALOG_QUANTITY"] : ""?></span>
									</div>
								<?elseif(!$arItem["OFFERS_MIN_PRICE"]["CAN_BUY"]):?>
									<div class="not_avl">
										<i class="fa fa-times-circle"></i>
										<span><?=GetMessage("CATALOG_ELEMENT_NOT_AVAILABLE")?></span>
									</div>
								<?endif;?>
							</div>							
							<script type="text/javascript">
								$(function() {
									$("#add2basket_offer_form_<?=$arItem['ID']?>").submit(function() {
										var form = $(this);
										$(window).resize(function () {
											modalHeight = $(window).height()/2 - $("#<?=$arItemIDs['ID']?>").height()/2 + $(window).scrollTop();
											$("#<?=$arItemIDs['ID']?>").css({
												"top": modalHeight + "px"
											});
										});
										$(window).resize();
										$("#<?=$arItemIDs['ID']?>_body").css({"display":"block"});
										$("#<?=$arItemIDs['ID']?>").css({"display":"block"});
														
										quantityItem = form.find("#quantity_<?=$arItem['ID']?>").attr("value");
										$("#<?=$arItemIDs['ID']?> .quantity").attr("value", quantityItem);
										return false;
									});
									$("#<?=$arItemIDs['ID']?>_close, #<?=$arItemIDs['ID']?>_body").click(function(e){
										e.preventDefault();
										$("#<?=$arItemIDs['ID']?>_body").css({"display":"none"});
										$("#<?=$arItemIDs['ID']?>").css({"display":"none"});
									});
								});
							</script>
							<div class="add2basket_block">
								<form action="<?=$APPLICATION->GetCurPage()?>" id="add2basket_offer_form_<?=$arItem['ID']?>">
									<a href="javascript:void(0)" class="minus" onclick="if (BX('quantity_<?=$arItem["ID"]?>').value > <?=$arItem["OFFERS_MIN_PRICE"]["CATALOG_MEASURE_RATIO"]?>) BX('quantity_<?=$arItem["ID"]?>').value = parseFloat(BX('quantity_<?=$arItem["ID"]?>').value)-<?=$arItem["OFFERS_MIN_PRICE"]["CATALOG_MEASURE_RATIO"]?>;"><span>-</span></a>
									<input type="text" id="quantity_<?=$arItem['ID']?>" name="quantity" class="quantity" value="<?=$arItem['OFFERS_MIN_PRICE']['CATALOG_MEASURE_RATIO']?>"/>
									<a href="javascript:void(0)" class="plus" onclick="BX('quantity_<?=$arItem["ID"]?>').value = parseFloat(BX('quantity_<?=$arItem["ID"]?>').value)+<?=$arItem["OFFERS_MIN_PRICE"]["CATALOG_MEASURE_RATIO"]?>;"><span>+</span></a>
									<button type="submit" name="add2basket" class="btn_buy" value="<?=GetMessage('CATALOG_ELEMENT_ADD_TO_CART')?>"><i class="fa fa-shopping-cart"></i><span><?=GetMessage("CATALOG_ELEMENT_ADD_TO_CART")?></span></button>
								</form>
							</div>
						<?else:?>
							<div class="available">
								<?if($arItem["CAN_BUY"]):?>
									<div class="avl">
										<i class="fa fa-check-circle"></i>
										<span><?=GetMessage("CATALOG_ELEMENT_AVAILABLE")?><?=in_array("PRODUCT_QUANTITY", $arSetting["GENERAL_SETTINGS"]["VALUE"]) ? " ".$arItem["CATALOG_QUANTITY"] : ""?></span>
									</div>
								<?elseif(!$arItem["CAN_BUY"]):?>
									<div class="not_avl">
										<i class="fa fa-times-circle"></i>
										<span><?=GetMessage("CATALOG_ELEMENT_NOT_AVAILABLE")?></span>
									</div>
								<?endif;?>
							</div>
							<?if(isset($arItem["SELECT_PROPS"]) && !empty($arItem["SELECT_PROPS"])):?>
								<script type="text/javascript">
									$(function() {
										$("#add2basket_select_form_<?=$arItem['ID']?>").submit(function() {
											var form = $(this);
											$(window).resize(function () {
												modalHeight = $(window).height()/2 - $("#<?=$arItemIDs['ID']?>").height()/2 + $(window).scrollTop();
												$("#<?=$arItemIDs['ID']?>").css({
													"top": modalHeight + "px"
												});
											});
											$(window).resize();
											$("#<?=$arItemIDs['ID']?>_body").css({"display":"block"});
											$("#<?=$arItemIDs['ID']?>").css({"display":"block"});
															
											quantityItem = form.find("#quantity_<?=$arItem['ID']?>").attr("value");
											$("#<?=$arItemIDs['ID']?> .quantity").attr("value", quantityItem);
											return false;
										});
										$("#<?=$arItemIDs['ID']?>_close, #<?=$arItemIDs['ID']?>_body").click(function(e){
											e.preventDefault();
											$("#<?=$arItemIDs['ID']?>_body").css({"display":"none"});
											$("#<?=$arItemIDs['ID']?>").css({"display":"none"});
										});
									});
								</script>
							<?endif;?>									
							<div class="add2basket_block">
								<?if($arItem["CAN_BUY"]):
									if($arItem["ASK_PRICE"]):?>
										<a class="btn_buy apuo" id="ask_price_anch_<?=$arItem['ID']?>" href="javascript:void(0)" rel="nofollow"><i class="fa fa-comment-o"></i><span class="full"><?=GetMessage("CATALOG_ELEMENT_ASK_PRICE_FULL")?></span><span class="short"><?=GetMessage("CATALOG_ELEMENT_ASK_PRICE_SHORT")?></span></a>
										<?$APPLICATION->IncludeComponent("altop:ask.price", "",
											Array(
												"ELEMENT_ID" => $arItem["ID"],		
												"ELEMENT_NAME" => $arItem["NAME"],
												"EMAIL_TO" => "",				
												"REQUIRED_FIELDS" => array("NAME", "TEL", "TIME"),
											),
											false,
											array("HIDE_ICONS" => "Y")
										);?>
									<?elseif(!$arItem["ASK_PRICE"]):
										if(isset($arItem["SELECT_PROPS"]) && !empty($arItem["SELECT_PROPS"])):?>
											<form action="<?=$APPLICATION->GetCurPage()?>" id="add2basket_select_form_<?=$arItem['ID']?>">
										<?else:?>
											<form action="<?=SITE_DIR?>ajax/add2basket.php" class="add2basket_form">
										<?endif;?>
											<a href="javascript:void(0)" class="minus" onclick="if (BX('quantity_<?=$arItem["ID"]?>').value > <?=$arItem["CATALOG_MEASURE_RATIO"]?>) BX('quantity_<?=$arItem["ID"]?>').value = parseFloat(BX('quantity_<?=$arItem["ID"]?>').value)-<?=$arItem["CATALOG_MEASURE_RATIO"]?>;"><span>-</span></a>
											<input type="text" id="quantity_<?=$arItem['ID']?>" name="quantity" class="quantity" value="<?=$arItem['CATALOG_MEASURE_RATIO']?>"/>
											<a href="javascript:void(0)" class="plus" onclick="BX('quantity_<?=$arItem["ID"]?>').value = parseFloat(BX('quantity_<?=$arItem["ID"]?>').value)+<?=$arItem["CATALOG_MEASURE_RATIO"]?>;"><span>+</span></a>
											<?if(!isset($arItem["SELECT_PROPS"]) || empty($arItem["SELECT_PROPS"])):?>
												<input type="hidden" name="ID" value="<?=$arItem['ID']?>" />
												<input type="hidden" name="item_image" class="item_image" value="&lt;img class='item_image' src='<?=$arItem["PREVIEW_IMG"]["SRC"]?>' alt='<?=$arItem["NAME"]?>'/&gt;"/>
												<input type="hidden" name="item_title" class="item_title" value="<?=$arItem['NAME']?>"/>													
											<?endif;?>
											<button type="submit" name="add2basket" class="btn_buy" value="<?=GetMessage('CATALOG_ELEMENT_ADD_TO_CART')?>"><i class="fa fa-shopping-cart"></i><span><?=GetMessage("CATALOG_ELEMENT_ADD_TO_CART")?></span></button>
											<?if(!isset($arItem["SELECT_PROPS"]) || empty($arItem["SELECT_PROPS"])):?>
												<small class="result hidden"><i class="fa fa-check"></i><span><?=GetMessage("CATALOG_ELEMENT_ADDED")?></span></small>
											<?endif;?>
										</form>
									<?endif;								
								elseif(!$arItem["CAN_BUY"]):?>
									<a class="btn_buy apuo" id="order_anch_<?=$arItem['ID']?>" href="javascript:void(0)" rel="nofollow"><i class="fa fa-clock-o"></i><span><?=GetMessage("CATALOG_ELEMENT_UNDER_ORDER")?></span></a>
									<?$APPLICATION->IncludeComponent("altop:ask.price", "order",
										Array(
											"ELEMENT_ID" => $arItem["ID"],		
											"ELEMENT_NAME" => $arItem["NAME"],
											"EMAIL_TO" => "",				
											"REQUIRED_FIELDS" => array("NAME", "TEL", "TIME"),
										),
										false,
										array("HIDE_ICONS" => "Y")
									);?>
								<?endif;?>
							</div>
						<?endif;?>
						<div class="clr"></div>
						<?if($arParams["DISPLAY_COMPARE"]=="Y"):?>
							<div class="compare">
								<a href="javascript:void(0)" class="catalog-item-compare" id="catalog_add2compare_link_<?=$arItem['ID']?>" onclick="return addToCompare('<?=SITE_DIR?>catalog/?action=ADD_TO_COMPARE_LIST&id=<?=$arItem["ID"]?>', 'catalog_add2compare_link_<?=$arItem["ID"]?>');" title="<?=GetMessage('CATALOG_ELEMENT_ADD_TO_COMPARE')?>" rel="nofollow"><i class="fa fa-bar-chart"></i><i class="fa fa-check"></i></a>
							</div>
						<?endif;?>
						<?if(isset($arItem["OFFERS"]) && !empty($arItem["OFFERS"])):
							if($arItem["OFFERS_MIN_PRICE"]["CAN_BUY"]):
								if($arItem["OFFERS_MIN_PRICE"]["VALUE"] > 0):
									$props = array();
									foreach($arItem["OFFERS_MIN_PRICE"]["DISPLAY_PROPERTIES"] as $propOffer) {
										$props[] = array(
											"NAME" => $propOffer["NAME"],
											"CODE" => $propOffer["CODE"],
											"VALUE" => strip_tags($propOffer["DISPLAY_VALUE"])
										);
									}
									$props = strtr(base64_encode(addslashes(gzcompress(serialize($props),9))), '+/=', '-_,');?>
									<div class="delay">
										<a href="javascript:void(0)" id="catalog-item-delay-<?=$arItem['OFFERS_MIN_PRICE']['ID']?>" class="catalog-item-delay" onclick="return addToDelay('<?=$arItem["OFFERS_MIN_PRICE"]["ID"]?>', '<?=$arItem["OFFERS_MIN_PRICE"]["CATALOG_MEASURE_RATIO"]?>', '<?=$props?>', '', 'catalog-item-delay-<?=$arItem["OFFERS_MIN_PRICE"]["ID"]?>')" title="<?=GetMessage('CATALOG_ELEMENT_ADD_TO_DELAY')?>" rel="nofollow"><i class="fa fa-heart-o"></i><i class="fa fa-check"></i></a>
									</div>
								<?endif;
							endif;
						else:
							if($arItem["CAN_BUY"]):
								foreach($arItem["PRICES"] as $code=>$arPrice):
									if($arPrice["MIN_PRICE"] == "Y"):
										if($arPrice["VALUE"] > 0):?>
											<div class="delay">
												<a href="javascript:void(0)" id="catalog-item-delay-<?=$arItem['ID']?>" class="catalog-item-delay" onclick="return addToDelay('<?=$arItem["ID"]?>', '<?=$arItem["CATALOG_MEASURE_RATIO"]?>', '', '', 'catalog-item-delay-<?=$arItem["ID"]?>')" title="<?=GetMessage('CATALOG_ELEMENT_ADD_TO_DELAY')?>" rel="nofollow"><i class="fa fa-heart-o"></i><i class="fa fa-check"></i></a>
											</div>
										<?endif;
									endif;
								endforeach;
							endif;
						endif;?>
					</div>					
				</div>
			</div>
		<?endforeach;

		foreach($arResult["ITEMS"] as $key => $arElement):
			if((isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"])) || (isset($arElement["SELECT_PROPS"]) && !empty($arElement["SELECT_PROPS"]))):
				$strMainID = $this->GetEditAreaId($arElement["ID"]);
				$arItemIDs = array(
					"ID" => $strMainID,
					"PICT" => $strMainID."_picture",
					"PRICE" => $strMainID."_price",
					"BUY" => $strMainID."_buy",
					"PROP_DIV" => $strMainID."_sku_tree",
					"PROP" => $strMainID."_prop_",
					"SELECT_PROP_DIV" => $strMainID."_propdiv",
					"SELECT_PROP" => $strMainID."_select_prop_"
				);
				$strObName = "ob".preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);?>
				
				<div class="pop-up-bg more_options_body" id="<?=$arItemIDs['ID']?>_body"></div>
				<div class="pop-up more_options" id="<?=$arItemIDs['ID']?>">
					<a href="javascript:void(0)" class="pop-up-close more_options_close" id="<?=$arItemIDs['ID']?>_close"><i class="fa fa-times"></i></a>
					<div class="h1"><?=GetMessage("CATALOG_ELEMENT_MORE_OPTIONS")?></div>
					<div class="item_info">
						<div class="item_image" id="<?=$arItemIDs['PICT']?>">
							<?if(isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"])):
								foreach($arElement["OFFERS"] as $key_off => $arOffer):?>
									<div id="img_<?=$arElement['ID']?>_<?=$arOffer['ID']?>" class="img <?=$arElement['ID']?> hidden">
										<?if(isset($arOffer["PREVIEW_IMG"])):?>
											<img src="<?=$arOffer['PREVIEW_IMG']['SRC']?>" alt="<?=$arElement['NAME']?>" width="<?=$arOffer['PREVIEW_IMG']['WIDTH']?>" height="<?=$arOffer['PREVIEW_IMG']['HEIGHT']?>"/>
										<?else:?>
											<img src="<?=$arElement['PREVIEW_IMG']['SRC']?>" width="<?=$arElement['PREVIEW_IMG']['WIDTH']?>" height="<?=$arElement['PREVIEW_IMG']['HEIGHT']?>" alt="<?=$arElement['NAME']?>"/>
										<?endif;?>
									</div>
								<?endforeach;
							else:?>
								<div class="img">
									<?if(isset($arElement["PREVIEW_IMG"])):?>
										<img src="<?=$arElement["PREVIEW_IMG"]["SRC"]?>" width="<?=$arElement["PREVIEW_IMG"]["WIDTH"]?>" height="<?=$arElement["PREVIEW_IMG"]["HEIGHT"]?>" alt="<?=$arElement["NAME"]?>"/>
									<?else:?>
										<img src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" width="150" height="150" alt="<?=$arElement['NAME']?>" />
									<?endif;?>
								</div>
							<?endif;?>
							<div class="item_name">
								<?=$arElement["NAME"]?>
							</div>
						</div>
						<div class="item_block">							
							<?if(!empty($arElement["OFFERS_PROP"])):?>
								<table class="offer_block" id="<?=$arItemIDs['PROP_DIV'];?>">
									<?$arSkuProps = array();
									foreach($arResult["SKU_PROPS"] as &$arProp) {
										if(!isset($arElement["OFFERS_PROP"][$arProp["CODE"]]))
											continue;
										$arSkuProps[] = array(
											"ID" => $arProp["ID"],
											"SHOW_MODE" => $arProp["SHOW_MODE"]
										);?>
										<tr class="<?=$arProp['CODE']?>" id="<?=$arItemIDs['PROP'].$arProp['ID'];?>_cont">
											<td class="h3">
												<?=htmlspecialcharsex($arProp["NAME"]);?>:
											</td>
											<td class="props">
												<ul id="<?=$arItemIDs['PROP'].$arProp['ID'];?>_list" class="<?=$arProp['CODE']?><?=$arProp['SHOW_MODE'] == 'PICT' ? ' COLOR' : '';?>">
													<?foreach($arProp["VALUES"] as $arOneValue) {
														$arOneValue["NAME"] = htmlspecialcharsbx($arOneValue["NAME"]);?>
														<li data-treevalue="<?=$arProp['ID'].'_'.$arOneValue['ID'];?>" data-onevalue="<?=$arOneValue['ID'];?>" style="display:none;">
															<span title="<?=$arOneValue['NAME'];?>">
																<?if("TEXT" == $arProp["SHOW_MODE"]) {
																	echo $arOneValue["NAME"];
																} elseif("PICT" == $arProp["SHOW_MODE"]) {
																	if(!empty($arOneValue["PICT"]["src"])):?>
																		<img src="<?=$arOneValue['PICT']['src']?>" width="<?=$arOneValue['PICT']['width']?>" height="<?=$arOneValue['PICT']['height']?>" alt="<?=$arOneValue['NAME']?>" />
																	<?else:?>
																		<i style="background:#<?=$arOneValue['HEX']?>"></i>
																	<?endif;
																}?>
															</span>
														</li>
													<?}?>
												</ul>
												<div class="bx_slide_left" style="display:none;" id="<?=$arItemIDs['PROP'].$arProp['ID']?>_left" data-treevalue="<?=$arProp['ID']?>"></div>
												<div class="bx_slide_right" style="display:none;" id="<?=$arItemIDs['PROP'].$arProp['ID']?>_right" data-treevalue="<?=$arProp['ID']?>"></div>
												<div class="clr"></div>
											</td>
										</tr>
									<?}
									unset($arProp);?>
								</table>
							<?endif;?>

							<?if(!empty($arElement["SELECT_PROPS"])):?>
								<table class="offer_block" id="<?=$arItemIDs['SELECT_PROP_DIV'];?>">
									<?$arSelProps = array();
									foreach($arElement["SELECT_PROPS"] as $key_prop => $arProp):
										$arSelProps[] = array(
											"ID" => $arProp["ID"]
										);?>
										<tr class="<?=$arProp['CODE']?>" id="<?=$arItemIDs['SELECT_PROP'].$arProp['ID'];?>">
											<td class="h3"><?=htmlspecialcharsex($arProp["NAME"]);?></td>
											<td class="props">												
												<ul class="<?=$arProp['CODE']?>">
													<?$props = array();
													foreach($arProp["DISPLAY_VALUE"] as $arOneValue) {
														$props[$key_prop] = array(
															"NAME" => $arProp["NAME"],
															"CODE" => $arProp["CODE"],
															"VALUE" => strip_tags($arOneValue)
														);
														$props[$key_prop] = strtr(base64_encode(addslashes(gzcompress(serialize($props[$key_prop]),9))), '+/=', '-_,');?>
														<li data-select-onevalue="<?=$props[$key_prop]?>">
															<span title="<?=$arOneValue;?>"><?=$arOneValue?></span>
														</li>
													<?}?>
												</ul>
												<div class="clr"></div>
											</td>
										</tr>
									<?endforeach;
									unset($arProp);?>
								</table>
							<?endif;?>
								
							<div class="catalog_price" id="<?=$arItemIDs['PRICE'];?>">
								<?if(isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"])):
									foreach($arElement["OFFERS"] as $key_off => $arOffer):?>
										<div id="price_<?=$arElement['ID']?>_<?=$arOffer['ID']?>" class="price <?=$arElement['ID']?> hidden">
											<?foreach($arOffer["PRICES"] as $code => $arPrice):
												if($arPrice["MIN_PRICE"] == "Y"):
													if($arPrice["CAN_ACCESS"]):
														
														$price = CCurrencyLang::GetCurrencyFormat($arPrice["CURRENCY"], "ru");
														if(empty($price["THOUSANDS_SEP"])):
															$price["THOUSANDS_SEP"] = " ";
														endif;
														$currency = str_replace("#", " ", $price["FORMAT_STRING"]);

														if($arPrice["VALUE"]==0):
															$arElement["OFFERS"][$key_off]["ASK_PRICE"] = 1;?>
															<span class="no-price">
																<?=GetMessage("CATALOG_ELEMENT_NO_PRICE")?>
																<?=(!empty($arOffer["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arOffer["CATALOG_MEASURE_NAME"] : "";?>
															</span>
														<?elseif($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
															<span class="price-old">
																<?=number_format($arPrice["VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
																<?=$currency;?>
															</span>
															<span class="price-percent">
																<?=GetMessage("CATALOG_ELEMENT_SKIDKA")." ".$arPrice["PRINT_DISCOUNT_DIFF"];?>
															</span>
															<span class="price-normal">
																<?=number_format($arPrice["DISCOUNT_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
																<span class="unit">
																	<?=$currency?>
																	<?=(!empty($arOffer["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arOffer["CATALOG_MEASURE_NAME"] : "";?>
																</span>
															</span>
														<?else:?>
															<span class="price-normal">
																<?=number_format($arPrice["VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
																<span class="unit">
																	<?=$currency?>
																	<?=(!empty($arOffer["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arOffer["CATALOG_MEASURE_NAME"] : "";?>
																</span>
															</span>
														<?endif;													
													endif;
												endif;
											endforeach;?>
											<div class="available">
												<?if($arOffer["CAN_BUY"]):?>													
													<div class="avl">
														<i class="fa fa-check-circle"></i>
														<span><?=GetMessage("CATALOG_ELEMENT_AVAILABLE")?><?=in_array("PRODUCT_QUANTITY", $arSetting["GENERAL_SETTINGS"]["VALUE"]) ? " ".$arOffer["CATALOG_QUANTITY"] : ""?></span>
													</div>
												<?elseif(!$arOffer["CAN_BUY"]):?>												
													<div class="not_avl">
														<i class="fa fa-times-circle"></i>
														<span><?=GetMessage("CATALOG_ELEMENT_NOT_AVAILABLE")?></span>
													</div>
												<?endif;?>
											</div>
										</div>
									<?endforeach;
								else:
									foreach($arElement["PRICES"] as $code => $arPrice):
										if($arPrice["MIN_PRICE"] == "Y"):
											if($arPrice["CAN_ACCESS"]):
																		
												$price = CCurrencyLang::GetCurrencyFormat($arPrice["CURRENCY"], "ru");
												if(empty($price["THOUSANDS_SEP"])):
													$price["THOUSANDS_SEP"] = " ";
												endif;
												$currency = str_replace("#", " ", $price["FORMAT_STRING"]);

												if($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
													<span class="price-old">
														<?=number_format($arPrice["VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
														<?=$currency;?>
													</span>
													<span class="price-percent">
														<?=GetMessage("CATALOG_ELEMENT_SKIDKA")." ".$arPrice["PRINT_DISCOUNT_DIFF"];?>
													</span>
													<span class="price-normal">
														<?=number_format($arPrice["DISCOUNT_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
														<span class="unit">
															<?=$currency?>
															<?=(!empty($arElement["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arElement["CATALOG_MEASURE_NAME"] : "";?>
														</span>
													</span>
												<?else:?>
													<span class="price-normal">
														<?=number_format($arPrice["VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
														<span class="unit">
															<?=$currency?>
															<?=(!empty($arElement["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arElement["CATALOG_MEASURE_NAME"] : "";?>
														</span>
													</span>
												<?endif;
											endif;
										endif;
									endforeach;?>
									<div class="available">
										<?if($arElement["CAN_BUY"]):?>												
											<div class="avl">
												<i class="fa fa-check-circle"></i>
												<span><?=GetMessage("CATALOG_ELEMENT_AVAILABLE")?><?=in_array("PRODUCT_QUANTITY", $arSetting["GENERAL_SETTINGS"]["VALUE"]) ? " ".$arElement["CATALOG_QUANTITY"] : ""?></span>
											</div>
										<?elseif(!$arElement["CAN_BUY"]):?>												
											<div class="not_avl">
												<i class="fa fa-times-circle"></i>
												<span><?=GetMessage("CATALOG_ELEMENT_NOT_AVAILABLE")?></span>
											</div>
										<?endif;?>
									</div>
								<?endif;?>
							</div>

							<div class="catalog_buy_more" id="<?=$arItemIDs['BUY'];?>">
								<?if(isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"])):
									foreach($arElement["OFFERS"] as $key_off => $arOffer):?>
										<div id="buy_more_<?=$arElement['ID']?>_<?=$arOffer['ID']?>" class="buy_more <?=$arElement['ID']?> hidden">
											<?if($arOffer["CAN_BUY"]):
												if($arOffer["ASK_PRICE"]):?>
													<a class="btn_buy apuo" id="ask_price_anch_<?=$arOffer['ID']?>" href="javascript:void(0)" rel="nofollow"><i class="fa fa-comment-o"></i><span><?=GetMessage("CATALOG_ELEMENT_ASK_PRICE_FULL")?></span></a>
													<?$properties = false;
													foreach($arOffer["DISPLAY_PROPERTIES"] as $propOffer) {
														$properties[] = $propOffer["NAME"].": ".strip_tags($propOffer["DISPLAY_VALUE"]);
													}
													$properties = implode("; ", $properties);
													if(!empty($properties)):
														$offer_name = $arElement["NAME"]." (".$properties.")";
													else:
														$offer_name = $arElement["NAME"];
													endif;?>
													<?$APPLICATION->IncludeComponent("altop:ask.price", "",
														Array(
															"ELEMENT_ID" => $arOffer["ID"],		
															"ELEMENT_NAME" => $offer_name,
															"EMAIL_TO" => "",				
															"REQUIRED_FIELDS" => array("NAME", "TEL", "TIME")
														),
														false,
														array("HIDE_ICONS" => "Y")
													);?>
												<?elseif(!$arOffer["ASK_PRICE"]):?>												
													<div class="add2basket_block">
														<form action="<?=SITE_DIR?>ajax/add2basket.php" class="add2basket_form">
															<div class="qnt_cont">
																<a href="javascript:void(0)" class="minus" onclick="if (BX('quantity_<?=$arOffer["ID"]?>').value > <?=$arOffer["CATALOG_MEASURE_RATIO"]?>) BX('quantity_<?=$arOffer["ID"]?>').value = parseFloat(BX('quantity_<?=$arOffer["ID"]?>').value)-<?=$arOffer["CATALOG_MEASURE_RATIO"]?>;"><span>-</span></a>
																<input type="text" id="quantity_<?=$arOffer['ID']?>" name="quantity" class="quantity" value="<?=$arOffer['CATALOG_MEASURE_RATIO']?>"/>
																<a href="javascript:void(0)" class="plus" onclick="BX('quantity_<?=$arOffer["ID"]?>').value = parseFloat(BX('quantity_<?=$arOffer["ID"]?>').value)+<?=$arOffer["CATALOG_MEASURE_RATIO"]?>;"><span>+</span></a>
															</div>
															<input type="hidden" name="ID" class="offer_id" value="<?=$arOffer['ID']?>" />
															<?$props = array();
															foreach($arOffer["DISPLAY_PROPERTIES"] as $propOffer) {
																$props[] = array(
																	"NAME" => $propOffer["NAME"],
																	"CODE" => $propOffer["CODE"],
																	"VALUE" => strip_tags($propOffer["DISPLAY_VALUE"])
																);
															}
															$props = strtr(base64_encode(addslashes(gzcompress(serialize($props),9))), '+/=', '-_,');?>
															<input type="hidden" name="PROPS" value="<?=$props?>" />
															<?if(!empty($arElement["SELECT_PROPS"])):?>
																<input type="hidden" name="SELECT_PROPS" id="select_props_<?=$arOffer['ID']?>" value="" />					
															<?endif;?>
															<?if(!empty($arOffer["PREVIEW_IMG"]["SRC"])):?>
																<input type="hidden" name="item_image" class="item_image" value="&lt;img class='item_image' src='<?=$arOffer["PREVIEW_IMG"]["SRC"]?>' alt='<?=$arElement["NAME"]?>'/&gt;"/>
															<?else:?>
																<input type="hidden" name="item_image" class="item_image" value="&lt;img class='item_image' src='<?=$arElement["PREVIEW_IMG"]["SRC"]?>' alt='<?=$arElement["NAME"]?>'/&gt;"/>
															<?endif;?>
															<input type="hidden" name="item_title" class="item_title" value="<?=$arElement['NAME']?>"/>										
															<button type="submit" name="add2basket" class="btn_buy" value="<?=GetMessage('CATALOG_ELEMENT_ADD_TO_CART')?>"><i class="fa fa-shopping-cart"></i><span><?=GetMessage("CATALOG_ELEMENT_ADD_TO_CART")?></span></button>
															<small class="result hidden"><i class="fa fa-check"></i><span><?=GetMessage("CATALOG_ELEMENT_ADDED")?></span></small>
														</form>
													</div>
												<?endif;													
											elseif(!$arOffer["CAN_BUY"]):?>
												<a class="btn_buy apuo" id="order_anch_<?=$arOffer['ID']?>" href="javascript:void(0)" rel="nofollow"><i class="fa fa-clock-o"></i><span><?=GetMessage("CATALOG_ELEMENT_UNDER_ORDER")?></span></a>
												<?$properties = false;
												foreach($arOffer["DISPLAY_PROPERTIES"] as $propOffer) {
													$properties[] = $propOffer["NAME"].": ".strip_tags($propOffer["DISPLAY_VALUE"]);
												}
												$properties = implode("; ", $properties);
												if(!empty($properties)):
													$offer_name = $arElement["NAME"]." (".$properties.")";
												else:
													$offer_name = $arElement["NAME"];
												endif;?>
												<?$APPLICATION->IncludeComponent("altop:ask.price", "order",
													Array(
														"ELEMENT_ID" => $arOffer["ID"],		
														"ELEMENT_NAME" => $offer_name,
														"EMAIL_TO" => "",				
														"REQUIRED_FIELDS" => array("NAME", "TEL", "TIME")
													),
													false,
													array("HIDE_ICONS" => "Y")
												);?>
											<?endif;?>
										</div>
									<?endforeach;
								else:?>
									<div class="buy_more">
										<?if($arElement["CAN_BUY"]):?>
											<div class="add2basket_block">
												<form action="<?=SITE_DIR?>ajax/add2basket.php" class="add2basket_form">
													<div class="qnt_cont">
														<a href="javascript:void(0)" class="minus" onclick="if (BX('quantity_select_<?=$arElement["ID"]?>').value > <?=$arElement["CATALOG_MEASURE_RATIO"]?>) BX('quantity_select_<?=$arElement["ID"]?>').value = parseFloat(BX('quantity_select_<?=$arElement["ID"]?>').value)-<?=$arElement["CATALOG_MEASURE_RATIO"]?>;"><span>-</span></a>
														<input type="text" id="quantity_select_<?=$arElement['ID']?>" name="quantity" class="quantity" value="<?=$arElement['CATALOG_MEASURE_RATIO']?>"/>
														<a href="javascript:void(0)" class="plus" onclick="BX('quantity_select_<?=$arElement["ID"]?>').value = parseFloat(BX('quantity_select_<?=$arElement["ID"]?>').value)+<?=$arElement["CATALOG_MEASURE_RATIO"]?>;"><span>+</span></a>
													</div>
													<input type="hidden" name="ID" class="id" value="<?=$arElement['ID']?>" />
													<input type="hidden" name="SELECT_PROPS" id="select_props_<?=$arElement['ID']?>" value="" />													
													<input type="hidden" name="item_image" class="item_image" value="&lt;img class='item_image' src='<?=$arElement["PREVIEW_IMG"]["SRC"]?>' alt='<?=$arElement["NAME"]?>'/&gt;"/>
													<input type="hidden" name="item_title" class="item_title" value="<?=$arElement['NAME']?>"/>												
													<button type="submit" name="add2basket" class="btn_buy" value="<?=GetMessage('CATALOG_ELEMENT_ADD_TO_CART')?>"><i class="fa fa-shopping-cart"></i><span><?=GetMessage("CATALOG_ELEMENT_ADD_TO_CART")?></span></button>
													<small class="result hidden"><i class="fa fa-check"></i><span><?=GetMessage("CATALOG_ELEMENT_ADDED")?></span></small>
												</form>
											</div>
										<?endif;?>
									</div>
								<?endif;?>							
							</div>
						</div>
					</div>
				</div>
				<?if(isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"])):
					$arJSParams = array(
						"PRODUCT_TYPE" => $arElement["CATALOG_TYPE"],
						"VISUAL" => array(
							"ID" => $arItemIDs["ID"],
							"PICT_ID" => $arItemIDs["PICT"],
							"PRICE_ID" => $arItemIDs["PRICE"],
							"BUY_ID" => $arItemIDs["BUY"],
							"TREE_ID" => $arItemIDs["PROP_DIV"],
							"TREE_ITEM_ID" => $arItemIDs["PROP"]
						),
						"PRODUCT" => array(
							"ID" => $arElement["ID"],
							"NAME" => $arElement["NAME"]
						),
						"OFFERS" => $arElement["JS_OFFERS"],
						"OFFER_SELECTED" => $arElement["OFFERS_SELECTED"],
						"TREE_PROPS" => $arSkuProps
					);
				else:
					$arJSParams = array(
						"PRODUCT_TYPE" => $arElement["CATALOG_TYPE"],
						"VISUAL" => array(
							"ID" => $arItemIDs["ID"]
						),
						"PRODUCT" => array(
							"ID" => $arElement["ID"],
							"NAME" => $arElement["NAME"]
						)
					);
				endif;
				if(isset($arElement["SELECT_PROPS"]) && !empty($arElement["SELECT_PROPS"])):
					$arJSParams["VISUAL"]["SELECT_PROP_ID"] = $arItemIDs["SELECT_PROP_DIV"];
					$arJSParams["VISUAL"]["SELECT_PROP_ITEM_ID"] = $arItemIDs["SELECT_PROP"];
					$arJSParams["SELECT_PROPS"] = $arSelProps;
				endif;?>
				<script type="text/javascript">
					var <?=$strObName;?> = new JCCatalogSection(<?=CUtil::PhpToJSObject($arJSParams, false, true);?>);
				</script>
			<?endif;
		endforeach;?>
	</div>
	<div class="clr"></div>
</div>