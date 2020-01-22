<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<script type="text/javascript">
	//<![CDATA[
	$(function() {
		$("a.search_close").click(function() {
			$("div.title-search-result").css({"display":"none"});
		});
		$(this).keydown(function(eventObject){
			if (eventObject.which == 27)
				$("div.title-search-result").css({"display":"none"});
		});
		$(".add2basket_search_form").submit(function() {
			var form = $(this);

			$(".more_options_body").css({"display":"none"});
			$(".more_options").css({"display":"none"});

			imageItem = form.find(".item_image").attr("value");
			$("#addItemInCart .item_image_full").html(imageItem);

			titleItem = form.find(".item_title").attr("value");
			$("#addItemInCart .item_title").text(titleItem);		

			var ModalName = $("#addItemInCart");
			CentriredModalWindow(ModalName);
			OpenModalWindow(ModalName);

			$.post($(this).attr("action"), $(this).serialize(), function(data) {
				try {
					$.post("<?=SITE_DIR?>ajax/basket_line.php", function(data) {
						$(".cart_line").replaceWith(data);
					});
					$.post("<?=SITE_DIR?>ajax/delay_line.php", function(data) {
						$(".delay_line").replaceWith(data);
					});
					form.children(".btn_buy").addClass("hidden");
					form.children(".result").removeClass("hidden");
				} catch (e) {}
			});
			return false;
		});
	});
	//]]>
</script>

<?if(!empty($arResult["CATEGORIES"])):
	global $arSetting;?>
	<a href="javascript:void(0)" class="pop-up-close search_close"><i class="fa fa-times"></i></a>		
	<div id="catalog_search">
		<?foreach($arResult["CATEGORIES"] as $category_id => $arCategory):?>
			<?foreach($arCategory["ITEMS"] as $i => $arItem):
				
				$strMainID = $this->GetEditAreaId($arItem["ITEM_ID"]);
				$arItemIDs = array(
					"ID" => $strMainID."_search"
				);
						
				if($category_id === "all"):
					if($arParams["SHOW_ALL_RESULTS"]=="Y"):?>
						<a class="search_all" href="<?=$arItem['URL']?>"><?=$arItem["NAME"]?></a>
					<?endif;
				elseif(isset($arItem["ICON"])):?>
					<div class="tvr_search">
						<?if(!empty($arItem["PICTURE"]["src"])):?>
							<div class="image">
								<a href="<?=$arItem['URL']?>">
									<img src="<?=$arItem['PICTURE']['src']?>" width="<?=$arItem['PICTURE']['width']?>" height="<?=$arItem['PICTURE']['height']?>" alt="<?=$arItem['NAME']?>" />
								</a>
							</div>
						<?elseif(!empty($arItem["PREVIEW_PICTURE"]["src"])):?>
							<div class="image">
								<a href="<?=$arItem['URL']?>">
									<img src="<?=$arItem['PREVIEW_PICTURE']['src']?>" width="<?=$arItem['PREVIEW_PICTURE']['width']?>" height="<?=$arItem['PREVIEW_PICTURE']['height']?>" alt="<?=$arItem['NAME']?>" />
								</a>
							</div>
						<?else:?>
							<div class="image">
								<a href="<?=$arItem['URL']?>">
									<img src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" width="66" height="66" alt="<?=$arItem['NAME']?>" />
								</a>
							</div>
						<?endif?>
						
						<div class="<?if(!empty($arItem['PRICES']) || !empty($arItem['OFFERS_MIN_PRICE'])): echo 'item_'; else: echo 'cat_'; endif;?>title">
							<a href="<?=$arItem['URL']?>"><?=$arItem["NAME"]?></a>
						</div>
						
						<?if($arParams["SHOW_PRICE"]=="Y"):
							if(isset($arItem["OFFERS"]) && !empty($arItem["OFFERS"])):
								$price = CCurrencyLang::GetCurrencyFormat($arItem["OFFERS_MIN_PRICE"]["CURRENCY"], "ru");
								if(empty($price["THOUSANDS_SEP"])):
									$price["THOUSANDS_SEP"] = " ";
								endif;
								$currency = str_replace("#", " ", $price["FORMAT_STRING"]);?>

								<div class="search_price">
									<?if($arItem["OFFERS_MIN_PRICE"]["VALUE"] == 0):?>
										<span class="no-price">
											<?=GetMessage("CATALOG_ELEMENT_NO_PRICE")?>
										</span>													
									<?elseif($arItem["OFFERS_MIN_PRICE"]["DISCOUNT_VALUE"] < $arItem["OFFERS_MIN_PRICE"]["VALUE"]):?>
										<span class="price">
											<?=number_format($arItem["OFFERS_MIN_PRICE"]["DISCOUNT_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
											<span><?=$currency?></span>
										</span>													
									<?else:?>
										<span class="price">
											<?=number_format($arItem["OFFERS_MIN_PRICE"]["VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
											<span><?=$currency?></span>
										</span>
									<?endif;?>
								</div>
							<?else:
								foreach($arItem["PRICES"] as $code=>$arPrice):
									if($arPrice["MIN_PRICE"] == "Y"):
										if($arPrice["CAN_ACCESS"]):
											
											$price = CCurrencyLang::GetCurrencyFormat($arPrice["CURRENCY"], "ru");
											if(empty($price["THOUSANDS_SEP"])):
												$price["THOUSANDS_SEP"] = " ";
											endif;
											$currency = str_replace("#", " ", $price["FORMAT_STRING"]);?>

											<div class="search_price">
												<?if($arPrice["VALUE"] == 0):
													$arItem["ASK_PRICE"]=1;?>
													<span class="no-price">
														<?=GetMessage("CATALOG_ELEMENT_NO_PRICE")?>
													</span>																
												<?elseif($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
													<span class="price">
														<?=number_format($arPrice["DISCOUNT_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
														<span><?=$currency?></span>
													</span>																
												<?else:?>
													<span class="price">
														<?=number_format($arPrice["VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
														<span><?=$currency?></span>
													</span>
												<?endif;?>
											</div>
										
										<?endif;
									endif;
								endforeach;
							endif;
						endif;?>
						
						<?if($arParams["SHOW_ADD_TO_CART"]=="Y"):
							if(isset($arItem["OFFERS"]) && !empty($arItem["OFFERS"])):?>
								<div class="buy_more">
									<div class="add2basket_block">													
										<script type="text/javascript">
											$(function() {
												$("#add2basket_offer_search_form_<?=$arItem['ITEM_ID']?>").submit(function() {
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
															
													quantityItem = form.find("#quantity_search_<?=$arItem['ITEM_ID']?>").attr("value");
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
										<form action="<?=$APPLICATION->GetCurPage()?>" id="add2basket_offer_search_form_<?=$arItem['ITEM_ID']?>">
											<a href="javascript:void(0)" class="minus" onclick="if (BX('quantity_search_<?=$arItem["ITEM_ID"]?>').value > <?=$arItem["OFFERS_MIN_PRICE"]["CATALOG_MEASURE_RATIO"]?>) BX('quantity_search_<?=$arItem["ITEM_ID"]?>').value = parseFloat(BX('quantity_search_<?=$arItem["ITEM_ID"]?>').value)-<?=$arItem["OFFERS_MIN_PRICE"]["CATALOG_MEASURE_RATIO"]?>;"><span>-</span></a>
											<input type="text" id="quantity_search_<?=$arItem['ITEM_ID']?>" name="quantity" class="quantity" value="<?=$arItem['OFFERS_MIN_PRICE']['CATALOG_MEASURE_RATIO']?>"/>
											<a href="javascript:void(0)" class="plus" onclick="BX('quantity_search_<?=$arItem["ITEM_ID"]?>').value = parseFloat(BX('quantity_search_<?=$arItem["ITEM_ID"]?>').value)+<?=$arItem["OFFERS_MIN_PRICE"]["CATALOG_MEASURE_RATIO"]?>;"><span>+</span></a>			
											<button type="submit" name="add2basket" class="btn_buy" value="<?=GetMessage('CATALOG_ELEMENT_ADD_TO_CART')?>"><i class="fa fa-shopping-cart"></i></button>
										</form>
									</div>
								</div>
							<?else:
								if(isset($arItem["SELECT_PROPS"]) && !empty($arItem["SELECT_PROPS"])):?>
									<script type="text/javascript">
										$(function() {
											$("#add2basket_search_select_form_<?=$arItem['ITEM_ID']?>").submit(function() {
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
																
												quantityItem = form.find("#quantity_search_<?=$arItem['ITEM_ID']?>").attr("value");
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
								<?endif;														
								if($arItem["CAN_BUY"]):?>
									<div class="buy_more">
										<div class="add2basket_block">
											<?if($arItem["ASK_PRICE"]):?>
												<a class="btn_buy apuo" id="ask_price_anch_search_<?=$arItem['ITEM_ID']?>" href="javascript:void(0)" rel="nofollow"><i class="fa fa-comment-o"></i><span><?=GetMessage("CATALOG_ELEMENT_ASK_PRICE_SHORT")?></span></a>	
												<?$APPLICATION->IncludeComponent("altop:ask.price", "",
													Array(
														"ELEMENT_ID" => "search_".$arItem["ITEM_ID"],		
														"ELEMENT_NAME" => strip_tags($arItem["NAME"]),
														"EMAIL_TO" => "",				
														"REQUIRED_FIELDS" => array("NAME", "EMAIL", "TEL"),
													),
													false
												);?>
											<?else:?>
												<?if(isset($arItem["SELECT_PROPS"]) && !empty($arItem["SELECT_PROPS"])):?>
													<form action="<?=$APPLICATION->GetCurPage()?>" id="add2basket_search_select_form_<?=$arItem['ITEM_ID']?>">
												<?else:?>
													<form action="<?=SITE_DIR?>ajax/add2basket.php" class="add2basket_search_form">
												<?endif;?>
													<a href="javascript:void(0)" class="minus" onclick="if (BX('quantity_search_<?=$arItem["ITEM_ID"]?>').value > <?=$arItem["CATALOG_MEASURE_RATIO"]?>) BX('quantity_search_<?=$arItem["ITEM_ID"]?>').value = parseFloat(BX('quantity_search_<?=$arItem["ITEM_ID"]?>').value)-<?=$arItem["CATALOG_MEASURE_RATIO"]?>;"><span>-</span></a>
													<input type="text" id="quantity_search_<?=$arItem['ITEM_ID']?>" name="quantity" class="quantity" value="<?=$arItem['CATALOG_MEASURE_RATIO']?>"/>
													<a href="javascript:void(0)" class="plus" onclick="BX('quantity_search_<?=$arItem["ITEM_ID"]?>').value = parseFloat(BX('quantity_search_<?=$arItem["ITEM_ID"]?>').value)+<?=$arItem["CATALOG_MEASURE_RATIO"]?>;"><span>+</span></a>
													<?if(!isset($arItem["SELECT_PROPS"]) || empty($arItem["SELECT_PROPS"])):?>
														<input type="hidden" name="ID" value="<?=$arItem['ITEM_ID']?>"/>	
														<input type="hidden" name="item_image" class="item_image" value="&lt;img class='item_image' src='<?=$arItem["PICTURE_150"]["src"]?>' alt='<?=strip_tags($arItem["NAME"])?>'/&gt;"/>
														<input type="hidden" name="item_title" class="item_title" value="<?=strip_tags($arItem['NAME']);?>"/>						
													<?endif;?>															
													<button type="submit" name="add2basket" class="btn_buy" value="<?=GetMessage('CATALOG_ELEMENT_ADD_TO_CART')?>"><i class="fa fa-shopping-cart"></i></button>
													<?if(!isset($arItem["SELECT_PROPS"]) || empty($arItem["SELECT_PROPS"])):?>
														<small class="result hidden"><i class="fa fa-check"></i></small>
													<?endif;?>
												</form>
											<?endif;?>
										</div>
									</div>
								<?elseif(!$arItem["CAN_BUY"]):
									if(!empty($arItem["PRICES"])):?>
										<div class="buy_more">
											<div class="add2basket_block">
												<a class="btn_buy apuo" id="order_anch_search_<?=$arItem['ITEM_ID']?>" href="javascript:void(0)" rel="nofollow"><i class="fa fa-clock-o"></i><span><?=GetMessage("CATALOG_ELEMENT_UNDER_ORDER")?></span></a>
												<?$APPLICATION->IncludeComponent("altop:ask.price", "order",
													Array(
														"ELEMENT_ID" => "search_".$arItem["ITEM_ID"],		
														"ELEMENT_NAME" => strip_tags($arItem["NAME"]),
														"EMAIL_TO" => "",				
														"REQUIRED_FIELDS" => array("NAME", "TEL", "TIME"),
													),
													false,
													array("HIDE_ICONS" => "Y")
												);?>
											</div>
										</div>
									<?endif;												
								endif;
							endif;
						endif;?>										
					</div>							
				<?endif;
			endforeach;
		endforeach;?>
	</div>			
	
	<?foreach($arResult["CATEGORIES"] as $category_id => $arCategory):
		foreach($arCategory["ITEMS"] as $key => $arElement):
			if((isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"])) || (isset($arElement["SELECT_PROPS"]) && !empty($arElement["SELECT_PROPS"]))):
				$strMainID = $this->GetEditAreaId($arElement["ITEM_ID"]);
				$arItemIDs = array(
					"ID" => $strMainID."_search",
					"PICT" => $strMainID."_search_picture",
					"PRICE" => $strMainID."_search_price",
					"BUY" => $strMainID."_search_buy",
					"PROP_DIV" => $strMainID."_search_sku_tree",
					"PROP" => $strMainID."_search_prop_",
					"SELECT_PROP_DIV" => $strMainID."_search_propdiv",
					"SELECT_PROP" => $strMainID."_search_select_prop_"
				);
				$strObName = "ob".preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID)."_search";?>

				<div class="pop-up-bg more_options_body" id="<?=$arItemIDs['ID']?>_body"></div>
				<div class="pop-up more_options" id="<?=$arItemIDs['ID']?>">
					<a href="javascript:void(0)" class="pop-up-close more_options_close" id="<?=$arItemIDs['ID']?>_close"><i class="fa fa-times"></i></a>
					<div class="h1"><?=GetMessage("CATALOG_ELEMENT_MORE_OPTIONS")?></div>
					<div class="item_info">
						<div class="item_image" id="<?=$arItemIDs['PICT']?>">
							<?if(isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"])):
								foreach($arElement["OFFERS"] as $key_off => $arOffer):?>
									<div id="img_search_<?=$arElement['ITEM_ID']?>_<?=$arOffer['ID']?>" class="img <?=$arElement['ITEM_ID']?> hidden">
										<?if(isset($arOffer["PREVIEW_IMG"])):?>
											<img src="<?=$arOffer['PREVIEW_IMG']['SRC']?>" width="<?=$arOffer['PREVIEW_IMG']['WIDTH']?>" height="<?=$arOffer['PREVIEW_IMG']['HEIGHT']?>" alt="<?=strip_tags($arElement['NAME']);?>"/>
										<?else:?>
											<img src="<?=$arElement['PICTURE_150']['src']?>" width="<?=$arElement['PICTURE_150']['width']?>" height="<?=$arElement['PICTURE_150']['height']?>" alt="<?=strip_tags($arElement['NAME']);?>"/>
										<?endif;?>
									</div>
								<?endforeach;
							else:?>
								<div class="img">
									<?if(isset($arElement["PICTURE_150"])):?>
										<img src="<?=$arElement["PICTURE_150"]["src"]?>" width="<?=$arElement["PICTURE_150"]["width"]?>" height="<?=$arElement["PICTURE_150"]["height"]?>" alt="<?=$arElement["NAME"]?>"/>
									<?else:?>
										<img src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" width="150" height="150" alt="<?=$arElement['NAME']?>" />
									<?endif;?>
								</div>
							<?endif;?>
							<div class="item_name">
								<?=strip_tags($arElement["NAME"]);?>
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
										<div id="price_search_<?=$arElement['ITEM_ID']?>_<?=$arOffer['ID']?>" class="price <?=$arElement['ITEM_ID']?> hidden">
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
										<div id="buy_more_search_<?=$arElement['ITEM_ID']?>_<?=$arOffer['ID']?>" class="buy_more <?=$arElement['ITEM_ID']?> hidden">
											<?if($arOffer["CAN_BUY"]):
												if($arOffer["ASK_PRICE"]):?>
													<a class="btn_buy apuo" id="ask_price_anch_search_<?=$arOffer['ID']?>" href="javascript:void(0)" rel="nofollow"><i class="fa fa-comment-o"></i><span><?=GetMessage("CATALOG_ELEMENT_ASK_PRICE_FULL")?></span></a>
													<?$properties = false;
													foreach($arOffer["DISPLAY_PROPERTIES"] as $propOffer) {
														$properties[] = $propOffer["NAME"].": ".strip_tags($propOffer["DISPLAY_VALUE"]);
													}
													$properties = implode("; ", $properties);
													if(!empty($properties)):
														$offer_name = strip_tags($arElement["NAME"])." (".$properties.")";
													else:
														$offer_name = strip_tags($arElement["NAME"]);
													endif;?>
													<?$APPLICATION->IncludeComponent("altop:ask.price", "",
														Array(
															"ELEMENT_ID" => "search_".$arOffer["ID"],		
															"ELEMENT_NAME" => $offer_name,
															"EMAIL_TO" => "",				
															"REQUIRED_FIELDS" => array("NAME", "TEL", "TIME")
														),
														false,
														array("HIDE_ICONS" => "Y")
													);?>
												<?elseif(!$arOffer["ASK_PRICE"]):?>												
													<div class="add2basket_block">
														<form action="<?=SITE_DIR?>ajax/add2basket.php" class="add2basket_search_form">
															<div class="qnt_cont">
																<a href="javascript:void(0)" class="minus" onclick="if (BX('quantity_search_<?=$arOffer["ID"]?>').value > <?=$arOffer["CATALOG_MEASURE_RATIO"]?>) BX('quantity_search_<?=$arOffer["ID"]?>').value = parseFloat(BX('quantity_search_<?=$arOffer["ID"]?>').value)-<?=$arOffer["CATALOG_MEASURE_RATIO"]?>;"><span>-</span></a>
																<input type="text" id="quantity_search_<?=$arOffer['ID']?>" name="quantity" class="quantity" value="<?=$arOffer['CATALOG_MEASURE_RATIO']?>"/>
																<a href="javascript:void(0)" class="plus" onclick="BX('quantity_search_<?=$arOffer["ID"]?>').value = parseFloat(BX('quantity_search_<?=$arOffer["ID"]?>').value)+<?=$arOffer["CATALOG_MEASURE_RATIO"]?>;"><span>+</span></a>
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
																<input type="hidden" name="SELECT_PROPS" id="select_props_search_<?=$arOffer['ID']?>" value="" />			
															<?endif;?>
															<?if(!empty($arOffer["PREVIEW_IMG"]["SRC"])):?>
																<input type="hidden" name="item_image" class="item_image" value="&lt;img class='item_image' src='<?=$arOffer["PREVIEW_IMG"]["SRC"]?>' alt='<?=strip_tags($arElement["NAME"])?>'/&gt;"/>
															<?else:?>
																<input type="hidden" name="item_image" class="item_image" value="&lt;img class='item_image' src='<?=$arElement["PICTURE_150"]["src"]?>' alt='<?=strip_tags($arElement["NAME"])?>'/&gt;"/>
															<?endif;?>
															<input type="hidden" name="item_title" class="item_title" value="<?=strip_tags($arElement['NAME']);?>"/>						
															<button type="submit" name="add2basket" class="btn_buy" value="<?=GetMessage('CATALOG_ELEMENT_ADD_TO_CART')?>"><i class="fa fa-shopping-cart"></i><span><?=GetMessage("CATALOG_ELEMENT_ADD_TO_CART")?></span></button>
															<small class="result hidden"><i class="fa fa-check"></i><span><?=GetMessage("CATALOG_ELEMENT_ADDED")?></span></small>
														</form>
													</div>
												<?endif;													
											elseif(!$arOffer["CAN_BUY"]):?>
												<a class="btn_buy apuo" id="order_anch_search_<?=$arOffer['ID']?>" href="javascript:void(0)" rel="nofollow"><i class="fa fa-clock-o"></i><span><?=GetMessage("CATALOG_ELEMENT_UNDER_ORDER")?></span></a>
												<?$properties = false;
												foreach($arOffer["DISPLAY_PROPERTIES"] as $propOffer) {
													$properties[] = $propOffer["NAME"].": ".strip_tags($propOffer["DISPLAY_VALUE"]);
												}
												$properties = implode("; ", $properties);
												if(!empty($properties)):
													$offer_name = strip_tags($arElement["NAME"])." (".$properties.")";
												else:
													$offer_name = strip_tags($arElement["NAME"]);
												endif;?>
												<?$APPLICATION->IncludeComponent("altop:ask.price", "order",
													Array(
														"ELEMENT_ID" => "search_".$arOffer["ID"],		
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
												<form action="<?=SITE_DIR?>ajax/add2basket.php" class="add2basket_search_form">
													<div class="qnt_cont">
														<a href="javascript:void(0)" class="minus" onclick="if (BX('quantity_search_select_<?=$arElement["ITEM_ID"]?>').value > <?=$arElement["CATALOG_MEASURE_RATIO"]?>) BX('quantity_search_select_<?=$arElement["ITEM_ID"]?>').value = parseFloat(BX('quantity_search_select_<?=$arElement["ITEM_ID"]?>').value)-<?=$arElement["CATALOG_MEASURE_RATIO"]?>;"><span>-</span></a>
														<input type="text" id="quantity_search_select_<?=$arElement['ITEM_ID']?>" name="quantity" class="quantity" value="<?=$arElement['CATALOG_MEASURE_RATIO']?>"/>
														<a href="javascript:void(0)" class="plus" onclick="BX('quantity_search_select_<?=$arElement["ITEM_ID"]?>').value = parseFloat(BX('quantity_search_select_<?=$arElement["ITEM_ID"]?>').value)+<?=$arElement["CATALOG_MEASURE_RATIO"]?>;"><span>+</span></a>
													</div>
													<input type="hidden" name="ID" class="id" value="<?=$arElement['ITEM_ID']?>" />
													<input type="hidden" name="SELECT_PROPS" id="select_props_search_<?=$arElement['ITEM_ID']?>" value="" />					
													<input type="hidden" name="item_image" class="item_image" value="&lt;img class='item_image' src='<?=$arElement["PICTURE_150"]["src"]?>' alt='<?=$arElement["NAME"]?>'/&gt;"/>									
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
							"ID" => $arElement["ITEM_ID"],
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
							"ID" => $arElement["ITEM_ID"],
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
					var <?=$strObName;?> = new JCCatalogSectionSearch(<?=CUtil::PhpToJSObject($arJSParams, false, true);?>);
				</script>
			<?endif;
		endforeach;
	endforeach;
else:?>
	<a href="javascript:void(0)" class="pop-up-close search_close"><i class="fa fa-times"></i></a>
	<table class="search_result">
		<tr>
			<td>
				<div id="catalog_search_empty">
					<?=GetMessage("CATALOG_EMPTY_RESULT")?>
				</div>
			</td>
		</tr>
	</table>
<?endif;?>