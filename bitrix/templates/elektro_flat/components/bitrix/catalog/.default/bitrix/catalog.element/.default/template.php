<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

global $arSetting;?>

<script type="text/javascript">
	//<![CDATA[
	$(function() {
		$("#set-constructor-items-from").appendTo("#set-constructor-items-to").css({"display":"block"});
		$("#accessories-from").appendTo("#accessories-to").css({"display":"block"});
		$("#catalog-reviews-from").appendTo("#catalog-reviews-to").css({"display":"block"});
		$(".add2basket_form").submit(function() {
			var form = $(this);

			imageItem = form.find(".item_image").attr("value");
			$("#addItemInCart .item_image_full").html(imageItem);

			titleItem = form.find(".item_title").attr("value");
			$("#addItemInCart .item_title").text(titleItem);			

			var ModalName = $("#addItemInCart");
			CentriredModalWindow(ModalName);
			OpenModalWindow(ModalName);

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
				} catch (e) {}
			});
			return false;
		});
		$(function() {
			$("div.catalog-detail-pictures a").fancybox({
				"transitionIn": "elastic",
				"transitionOut": "elastic",
				"speedIn": 600,
				"speedOut": 200,
				"overlayShow": false,
				"cyclic" : true,
				"padding": 20,
				"titlePosition": "over",
				"onComplete": function() {
					$("#fancybox-title").css({"top":"100%", "bottom":"auto"});
				} 
			});
		});
	});
	//]]>
</script>

<?$strMainID = $this->GetEditAreaId($arResult["ID"]);
$arItemIDs = array(
	"ID" => $strMainID,
	"PICT" => $strMainID."_picture",
	"PRICE" => $strMainID."_price",
	"BUY" => $strMainID."_buy",
	"DELAY" => $strMainID."_delay",
	"STORE" => $strMainID."_store",
	"PROP_DIV" => $strMainID."_skudiv",
	"PROP" => $strMainID."_prop_",
	"SELECT_PROP_DIV" => $strMainID."_propdiv",
	"SELECT_PROP" => $strMainID."_select_prop_",
);
$strObName = "ob".preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
$templateData["JS_OBJ"] = $strObName;

$sticker = "";
if(array_key_exists("PROPERTIES", $arResult) && is_array($arResult["PROPERTIES"])) {
	if(array_key_exists("NEWPRODUCT", $arResult["PROPERTIES"]) && !$arResult["PROPERTIES"]["NEWPRODUCT"]["VALUE"] == false) {
		$sticker .= "<span class='new'>".GetMessage("CATALOG_ELEMENT_NEWPRODUCT")."</span>";
	}
	if(array_key_exists("SALELEADER", $arResult["PROPERTIES"]) && !$arResult["PROPERTIES"]["SALELEADER"]["VALUE"] == false) {
		$sticker .= "<span class='hit'>".GetMessage("CATALOG_ELEMENT_SALELEADER")."</span>";
	}
	if(array_key_exists("DISCOUNT", $arResult["PROPERTIES"]) && !$arResult["PROPERTIES"]["DISCOUNT"]["VALUE"] == false) {
		if(isset($arResult["OFFERS"]) && !empty($arResult["OFFERS"])) {						
			
		} else {
			if($arResult["MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"] > 0) {
				$sticker .= "<span class='discount'>-".$arResult["MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"]."%</span>";
			} else {
				$sticker .= "<span class='discount'>%</span>";
			}
		}
	}
}?>

<div id="<?=$arItemIDs['ID']?>" class="catalog-detail-element" itemscope itemtype="http://schema.org/Product">
	<meta content="<?=$arResult['NAME']?>" itemprop="name" />
	<div class="catalog-detail">
		<div class="column first">
			<div class="catalog-detail-pictures">
				<div class="catalog-detail-picture" id="<?=$arItemIDs['PICT']?>">
					<?if(isset($arResult["OFFERS"]) && !empty($arResult["OFFERS"])):
						foreach($arResult["OFFERS"] as $key => $arOffer):?>
							<div id="detail_picture_<?=$arOffer['ID']?>" class="detail_picture <?=$arResult['ID']?> hidden">
								<?if(is_array($arOffer["DETAIL_IMG"])):?>
									<meta content="<?=$arOffer['DETAIL_PICTURE']['SRC']?>" itemprop="image" />							
									<a rel="" class="catalog-detail-images" id="catalog-detail-images-<?=$arOffer['ID']?>" href="<?=$arOffer['DETAIL_PICTURE']['SRC']?>"> 
										<img src="<?=$arOffer['DETAIL_IMG']['SRC']?>" width="<?=$arOffer['DETAIL_IMG']['WIDTH']?>" height="<?=$arOffer['DETAIL_IMG']['HEIGHT']?>" alt="<?=$arResult['NAME']?>" />
										<div class="sticker">
											<?=$sticker?>
										</div>
										<?if(!empty($arResult["PROPERTIES"]["MANUFACTURER"]["PREVIEW_IMG"]["SRC"])):?>
											<img class="manufacturer" src="<?=$arResult['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['SRC']?>" width="<?=$arResult['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['WIDTH']?>" height="<?=$arResult['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['HEIGHT']?>" alt="<?=$arResult['PROPERTIES']['MANUFACTURER']['NAME']?>" />
										<?endif;?>
									</a>
								<?else:?>
									<meta content="<?=$arResult['DETAIL_PICTURE']['SRC']?>" itemprop="image" />							
									<a rel="" class="catalog-detail-images" id="catalog-detail-images-<?=$arOffer['ID']?>" href="<?=$arResult['DETAIL_PICTURE']['SRC']?>"> 
										<img src="<?=$arResult['DETAIL_IMG']['SRC']?>" width="<?=$arResult['DETAIL_IMG']['WIDTH']?>" height="<?=$arResult['DETAIL_IMG']['HEIGHT']?>" alt="<?=$arResult['NAME']?>" />
										<div class="sticker">
											<?=$sticker?>
										</div>
										<?if(!empty($arResult["PROPERTIES"]["MANUFACTURER"]["PREVIEW_IMG"]["SRC"])):?>
											<img class="manufacturer" src="<?=$arResult['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['SRC']?>" width="<?=$arResult['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['WIDTH']?>" height="<?=$arResult['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['HEIGHT']?>" alt="<?=$arResult['PROPERTIES']['MANUFACTURER']['NAME']?>" />
										<?endif;?>
									</a>
								<?endif;?>
							</div>
						<?endforeach;
					else:
						if(is_array($arResult["DETAIL_IMG"])):?>
							<div class="detail_picture">
								<meta content="<?=$arResult['DETAIL_PICTURE']['SRC']?>" itemprop="image" />								
								<a rel="lightbox" class="catalog-detail-images" href="<?=$arResult['DETAIL_PICTURE']['SRC']?>"> 
									<img src="<?=$arResult['DETAIL_IMG']['SRC']?>" width="<?=$arResult['DETAIL_IMG']['WIDTH']?>" height="<?=$arResult['DETAIL_IMG']['HEIGHT']?>" alt="<?=$arResult['NAME']?>" />
									<div class="sticker">
										<?=$sticker?>
									</div>
									<?if(!empty($arResult["PROPERTIES"]["MANUFACTURER"]["PREVIEW_IMG"]["SRC"])):?>
										<img class="manufacturer" src="<?=$arResult['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['SRC']?>" width="<?=$arResult['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['WIDTH']?>" height="<?=$arResult['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['HEIGHT']?>" alt="<?=$arResult['PROPERTIES']['MANUFACTURER']['NAME']?>" />
									<?endif;?>
								</a>
							</div>
						<?else:?>
							<div class="detail_picture">
								<meta content="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" itemprop="image" />							
								<div class="catalog-detail-images">
									<img src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" width="150" height="150" alt="<?=$arResult['NAME']?>" />
									<div class="sticker">
										<?=$sticker?>
									</div>
									<?if(!empty($arResult["PROPERTIES"]["MANUFACTURER"]["PREVIEW_IMG"]["SRC"])):?>
										<img class="manufacturer" src="<?=$arResult['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['SRC']?>" width="<?=$arResult['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['WIDTH']?>" height="<?=$arResult['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['HEIGHT']?>" alt="<?=$arResult['PROPERTIES']['MANUFACTURER']['NAME']?>" />
									<?endif;?>
								</div>
							</div>
						<?endif;
					endif;?>
				</div>
				<?if(!empty($arResult["PROPERTIES"]["VIDEO"]) || count($arResult["MORE_PHOTO"])>0):?>
					<div class="clr"></div>
					<div class="more_photo">
						<ul>
							<?if(!empty($arResult["PROPERTIES"]["VIDEO"]["VALUE"])):?>
								<li class="catalog-detail-video">
									<a rel="lightbox" class="catalog-detail-images" href="#video">
										<i class="fa fa-play-circle-o"></i>
										<span><?=GetMessage("CATALOG_ELEMENT_VIDEO")?></span>
									</a>
									<div id="video" style="overflow:hidden;">
										<?=$arResult["PROPERTIES"]["VIDEO"]["~VALUE"]["TEXT"];?>
									</div>
								</li>
							<?endif;
							if(count($arResult["MORE_PHOTO"]) > 0):
								foreach($arResult["MORE_PHOTO"] as $PHOTO):?>
									<li>										
										<a rel="lightbox" class="catalog-detail-images" href="<?=$PHOTO['SRC']?>">
											<img src="<?=$PHOTO['PREVIEW']['SRC']?>" width="<?=$PHOTO['PREVIEW']['WIDTH']?>" height="<?=$PHOTO['PREVIEW']['HEIGHT']?>" alt="<?=$arResult['NAME']?>" />
										</a>
									</li>
								<?endforeach;
							endif;?>
						</ul>
					</div>
				<?endif?>
			</div>
		</div>
		<div class="column second">
			<div class="price_buy_detail" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
				<div class="catalog-detail-price" id="<?=$arItemIDs['PRICE'];?>">
					<?if(isset($arResult["OFFERS"]) && !empty($arResult["OFFERS"])):
						foreach($arResult["OFFERS"] as $key => $arOffer):?>
							<div id="detail_price_<?=$arOffer['ID']?>" class="detail_price <?=$arResult['ID']?> hidden">
								<?foreach($arOffer["PRICES"] as $code => $arPrice):
									if($arPrice["MIN_PRICE"] == "Y"):
										if($arPrice["CAN_ACCESS"]):
											
											$price = CCurrencyLang::GetCurrencyFormat($arPrice["CURRENCY"], "ru");
											if(empty($price["THOUSANDS_SEP"])):
												$price["THOUSANDS_SEP"] = " ";
											endif;
											$currency = str_replace("#", " ", $price["FORMAT_STRING"]);

											if($arPrice["VALUE"] == 0):
												$arResult["OFFERS"][$key]["ASK_PRICE"] = 1;?>										
												<span class="catalog-detail-item-no-price">
													<?=GetMessage("CATALOG_ELEMENT_NO_PRICE")?>
													<?=(!empty($arOffer["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arOffer["CATALOG_MEASURE_NAME"] : "";?>
												</span>																				
											<?elseif($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
												<span class="catalog-detail-item-price-old">
													<?=number_format($arPrice["VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
													<?=$currency;?>
												</span>
												<span class="catalog-detail-item-price-percent">
													<?=GetMessage('CATALOG_ELEMENT_SKIDKA')." ".$arPrice["PRINT_DISCOUNT_DIFF"];?>
												</span>
												<span class="catalog-detail-item-price">
													<?=number_format($arPrice["DISCOUNT_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
													<span class="unit">
														<?=$currency?>
														<?=(!empty($arOffer["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arOffer["CATALOG_MEASURE_NAME"] : "";?>
													</span>
												</span>
												<meta itemprop="price" content="<?=$arPrice['DISCOUNT_VALUE']?>" />
												<meta itemprop="priceCurrency" content="<?=$arPrice['CURRENCY']?>" />
											<?else:?>											
												<span class="catalog-detail-item-price">
													<?=number_format($arPrice["VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
													<span class="unit">
														<?=$currency?>
														<?=(!empty($arOffer["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arOffer["CATALOG_MEASURE_NAME"] : "";?>
													</span>
												</span>
												<meta itemprop="price" content="<?=$arPrice['VALUE']?>" />
												<meta itemprop="priceCurrency" content="<?=$arPrice['CURRENCY']?>" />
											<?endif;
										endif;
									endif;
								endforeach;?>
								<div class="available">
									<?if($arOffer["CAN_BUY"]):?>
										<meta content="InStock" itemprop="availability" />
										<div class="avl">
											<i class="fa fa-check-circle"></i>
											<span><?=GetMessage("CATALOG_ELEMENT_AVAILABLE")?><?=in_array("PRODUCT_QUANTITY", $arSetting["GENERAL_SETTINGS"]["VALUE"]) ? " ".$arOffer["CATALOG_QUANTITY"] : ""?></span>
										</div>
									<?elseif(!$arOffer["CAN_BUY"]):?>
										<meta content="OutOfStock" itemprop="availability" />
										<div class="not_avl">
											<i class="fa fa-times-circle"></i>
											<span><?=GetMessage("CATALOG_ELEMENT_NOT_AVAILABLE")?></span>
										</div>
									<?endif;?>
								</div>
							</div>
						<?endforeach;
					else:
						foreach($arResult["PRICES"] as $code => $arPrice):
							if($arPrice["MIN_PRICE"] == "Y"):
								if($arPrice["CAN_ACCESS"]):
											
									$price = CCurrencyLang::GetCurrencyFormat($arPrice["CURRENCY"], "ru");
									if(empty($price["THOUSANDS_SEP"])):
										$price["THOUSANDS_SEP"] = " ";
									endif;
									$currency = str_replace("#", " ", $price["FORMAT_STRING"]);

									if($arPrice["VALUE"] == 0):
										$arResult["ASK_PRICE"] = 1;?>										
										<span class="catalog-detail-item-no-price">
											<?=GetMessage("CATALOG_ELEMENT_NO_PRICE")?>
											<?=(!empty($arResult["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arResult["CATALOG_MEASURE_NAME"] : "";?>
										</span>																	
									<?elseif($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
										<span class="catalog-detail-item-price-old">
											<?=number_format($arPrice["VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
											<?=$currency;?>
										</span>
										<span class="catalog-detail-item-price-percent">
											<?=GetMessage('CATALOG_ELEMENT_SKIDKA')." ".$arPrice["PRINT_DISCOUNT_DIFF"];?>
										</span>
										<span class="catalog-detail-item-price">
											<?=number_format($arPrice["DISCOUNT_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
											<span class="unit">
												<?=$currency?>
												<?=(!empty($arResult["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arResult["CATALOG_MEASURE_NAME"] : "";?>
											</span>
										</span>
										<meta itemprop="price" content="<?=$arPrice['DISCOUNT_VALUE']?>" />
										<meta itemprop="priceCurrency" content="<?=$arPrice['CURRENCY']?>" />
									<?else:?>
										<span class="catalog-detail-item-price">
											<?=number_format($arPrice["VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
											<span class="unit">
												<?=$currency?>
												<?=(!empty($arResult["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arResult["CATALOG_MEASURE_NAME"] : "";?>
											</span>
										</span>
										<meta itemprop="price" content="<?=$arPrice['VALUE']?>" />
										<meta itemprop="priceCurrency" content="<?=$arPrice['CURRENCY']?>" />
									<?endif;
								endif;
							endif;
						endforeach;?>
						<div class="available">
							<?if($arResult["CAN_BUY"]):?>
								<meta content="InStock" itemprop="availability" />
								<div class="avl">
									<i class="fa fa-check-circle"></i>
									<span><?=GetMessage("CATALOG_ELEMENT_AVAILABLE")?><?=in_array("PRODUCT_QUANTITY", $arSetting["GENERAL_SETTINGS"]["VALUE"]) ? " ".$arResult["CATALOG_QUANTITY"] : ""?></span>
								</div>
							<?elseif(!$arResult["CAN_BUY"]):?>
								<meta content="OutOfStock" itemprop="availability" />
								<div class="not_avl">
									<i class="fa fa-times-circle"></i>
									<span><?=GetMessage("CATALOG_ELEMENT_NOT_AVAILABLE")?></span>
								</div>
							<?endif;?>
						</div>
					<?endif;?>
				</div>
				<div class="catalog-detail-buy" id="<?=$arItemIDs['BUY'];?>">
					<?if(isset($arResult["OFFERS"]) && !empty($arResult["OFFERS"])):
						foreach($arResult["OFFERS"] as $key => $arOffer):?>
							<div id="buy_more_detail_<?=$arOffer['ID']?>" class="buy_more_detail <?=$arResult['ID']?> hidden">
								<?if($arOffer["CAN_BUY"]):
									if($arOffer["ASK_PRICE"]):?>
										<a class="btn_buy apuo_detail" id="ask_price_anch_<?=$arOffer['ID']?>" href="javascript:void(0)" rel="nofollow"><i class="fa fa-comment-o"></i><?=GetMessage("CATALOG_ELEMENT_ASK_PRICE")?></a>
										<?$properties = false;
										foreach($arOffer["DISPLAY_PROPERTIES"] as $propOffer) {
											$properties[] = $propOffer["NAME"].": ".strip_tags($propOffer["DISPLAY_VALUE"]);
										}
										$properties = implode("; ", $properties);
										if(!empty($properties)):
											$offer_name = $arResult["NAME"]." (".$properties.")";
										else:
											$offer_name = $arResult["NAME"];
										endif;?>
										<?$APPLICATION->IncludeComponent("altop:ask.price", "",
											Array(
												"ELEMENT_ID" => $arOffer["ID"],		
												"ELEMENT_NAME" => $offer_name,
												"EMAIL_TO" => "",				
												"REQUIRED_FIELDS" => array(
													0 => "NAME",
													1 => "TEL",
													2 => "TIME"
												)
											),
											false,
											array("HIDE_ICONS" => "Y")
										);?>
									<?elseif(!$arOffer["ASK_PRICE"]):?>
										<div class="add2basket_block">
											<form action="<?=SITE_DIR?>ajax/add2basket.php" class="add2basket_form" id="add2basket_form_<?=$arOffer['ID']?>">
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
												<?if(!empty($arResult["SELECT_PROPS"])):?>
													<input type="hidden" name="SELECT_PROPS" id="select_props_<?=$arOffer['ID']?>" value="" />													
												<?endif;?>
												<?if(!empty($arOffer["PREVIEW_IMG"]["SRC"])):?>							
													<input type="hidden" name="item_image" class="item_image" value="&lt;img class='item_image' src='<?=$arOffer["PREVIEW_IMG"]["SRC"]?>' alt='<?=$arResult["NAME"]?>'/&gt;"/>
												<?else:?>
													<input type="hidden" name="item_image" class="item_image" value="&lt;img class='item_image' src='<?=$arResult["PREVIEW_IMG"]["SRC"]?>' alt='<?=$arResult["NAME"]?>'/&gt;"/>
												<?endif;?>
												<input type="hidden" name="item_title" class="item_title" value="<?=$arResult['NAME']?>"/>												
												<input type="hidden" name="item_props" class="item_props" value="
													<?foreach($arOffer["DISPLAY_PROPERTIES"] as $propOffer): 
														echo '&lt;span&gt;'.$propOffer["NAME"].': '.strip_tags($propOffer["DISPLAY_VALUE"]).'&lt;/span&gt;';
													endforeach;?>
												"/>
												<button type="submit" name="add2basket" class="btn_buy detail" value="<?=GetMessage('CATALOG_ELEMENT_ADD_TO_CART')?>"><i class="fa fa-shopping-cart"></i><?=GetMessage('CATALOG_ELEMENT_ADD_TO_CART')?></button>
												<small class="result detail hidden"><i class="fa fa-check"></i><?=GetMessage('CATALOG_ELEMENT_ADDED')?></small>
											</form>
											<button name="boc_anch" id="boc_anch_<?=$arOffer['ID']?>" class="btn_buy boc_anch" value="<?=GetMessage('CATALOG_ELEMENT_BOC')?>"><i class="fa fa-bolt"></i><?=GetMessage('CATALOG_ELEMENT_BOC')?></button>
											<?$APPLICATION->IncludeComponent("altop:buy.one.click", ".default", 
												array(
													"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
													"IBLOCK_ID" => $arParams["IBLOCK_ID"],
													"ELEMENT_ID" => $arOffer["ID"],
													"ELEMENT_PROPS" => $props,
													"REQUIRED_ORDER_FIELDS" => array(
														0 => "NAME",
														1 => "TEL"
													),
													"DEFAULT_PERSON_TYPE" => "1",
													"DEFAULT_ORDER_PROP_NAME" => "1",
													"DEFAULT_ORDER_PROP_TEL" => "3",
													"DEFAULT_ORDER_PROP_EMAIL" => "2",
													"DEFAULT_DELIVERY" => "0",
													"DEFAULT_PAYMENT" => "0",													
													"BUY_MODE" => "ONE",													
													"DUPLICATE_LETTER_TO_EMAILS" => array(
														0 => "admin"
													)
												),
												false,
												array("HIDE_ICONS" => "Y")
											);?>
										</div>
									<?endif;
								elseif(!$arOffer["CAN_BUY"]):?>
									<a class="btn_buy apuo_detail" id="order_anch_<?=$arOffer['ID']?>" href="javascript:void(0)" rel="nofollow"><i class="fa fa-clock-o"></i><?=GetMessage("CATALOG_ELEMENT_UNDER_ORDER")?></a>
									<?$properties = false;
									foreach($arOffer["DISPLAY_PROPERTIES"] as $propOffer) {
										$properties[] = $propOffer["NAME"].": ".strip_tags($propOffer["DISPLAY_VALUE"]);
									}
									$properties = implode("; ", $properties);
									if(!empty($properties)):
										$offer_name = $arResult["NAME"]." (".$properties.")";
									else:
										$offer_name = $arResult["NAME"];
									endif;?>
									<?$APPLICATION->IncludeComponent("altop:ask.price", "order",
										Array(
											"ELEMENT_ID" => $arOffer["ID"],		
											"ELEMENT_NAME" => $offer_name,
											"EMAIL_TO" => "",				
											"REQUIRED_FIELDS" => array(
												0 => "NAME",
												1 => "TEL",
												2 => "TIME"
											)
										),
										false,
										array("HIDE_ICONS" => "Y")
									);?>
									<?$APPLICATION->IncludeComponent("bitrix:sale.notice.product", "", 
										array(
											"NOTIFY_ID" => $arOffer["ID"],
											"NOTIFY_URL" => htmlspecialcharsback($arOffer["SUBSCRIBE_URL"]),
											"NOTIFY_USE_CAPTHA" => "Y"
										),									
										false,
										array("HIDE_ICONS" => "Y")
									);?>
								<?endif;?>								
							</div>
						<?endforeach;
					else:?>
						<div class="buy_more_detail">
							<?if($arResult["CAN_BUY"]):
								if($arResult["ASK_PRICE"]):?>
									<a class="btn_buy apuo_detail" id="ask_price_anch_<?=$arResult['ID']?>" href="javascript:void(0)" rel="nofollow"><i class="fa fa-comment-o"></i><?=GetMessage("CATALOG_ELEMENT_ASK_PRICE")?></a>
									<?$APPLICATION->IncludeComponent("altop:ask.price", "",
										Array(
											"ELEMENT_ID" => $arResult["ID"],		
											"ELEMENT_NAME" => $arResult["NAME"],
											"EMAIL_TO" => "",				
											"REQUIRED_FIELDS" => array(
												0 => "NAME",
												1 => "TEL",
												2 => "TIME"
											)
										),
										false
									);?>
								<?elseif(!$arResult["ASK_PRICE"]):?>
									<form action="<?=SITE_DIR?>ajax/add2basket.php" class="add2basket_form" id="add2basket_form_<?=$arResult['ID']?>">
										<div class="qnt_cont">
											<a href="javascript:void(0)" class="minus" onclick="if(BX('quantity_<?=$arResult["ID"]?>').value > <?=$arResult["CATALOG_MEASURE_RATIO"]?>) BX('quantity_<?=$arResult["ID"]?>').value = parseFloat(BX('quantity_<?=$arResult["ID"]?>').value)-<?=$arResult["CATALOG_MEASURE_RATIO"]?>;"><span>-</span></a>
											<input type="text" id="quantity_<?=$arResult['ID']?>" name="quantity" class="quantity" value="<?=$arResult['CATALOG_MEASURE_RATIO']?>"/>
											<a href="javascript:void(0)" class="plus" onclick="BX('quantity_<?=$arResult["ID"]?>').value = parseFloat(BX('quantity_<?=$arResult["ID"]?>').value)+<?=$arResult["CATALOG_MEASURE_RATIO"]?>;"><span>+</span></a>
										</div>
										<input type="hidden" name="ID" class="id" value="<?=$arResult['ID']?>" />
										<?if(!empty($arResult["SELECT_PROPS"])):?>
											<input type="hidden" name="SELECT_PROPS" id="select_props_<?=$arResult['ID']?>" value="" />											
										<?endif;?>												
										<input type="hidden" name="item_image" class="item_image" value="&lt;img class='item_image' src='<?=$arResult["PREVIEW_IMG"]["SRC"]?>' alt='<?=$arResult["NAME"]?>'/&gt;"/>
										<input type="hidden" name="item_title" class="item_title" value="<?=$arResult['NAME']?>"/>	
										<button type="submit" name="add2basket" class="btn_buy detail" value="<?=GetMessage('CATALOG_ELEMENT_ADD_TO_CART')?>"><i class="fa fa-shopping-cart"></i><?=GetMessage('CATALOG_ELEMENT_ADD_TO_CART')?></button>
										<small class="result detail hidden"><i class="fa fa-check"></i><?=GetMessage('CATALOG_ELEMENT_ADDED')?></small>								
									</form>									
									<button name="boc_anch" id="boc_anch_<?=$arResult['ID']?>" class="btn_buy boc_anch" value="<?=GetMessage('CATALOG_ELEMENT_BOC')?>"><i class="fa fa-bolt"></i><?=GetMessage('CATALOG_ELEMENT_BOC')?></button>
									<?$APPLICATION->IncludeComponent("altop:buy.one.click", ".default", 
										array(
											"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
											"IBLOCK_ID" => $arParams["IBLOCK_ID"],
											"ELEMENT_ID" => $arResult["ID"],
											"ELEMENT_PROPS" => "",
											"REQUIRED_ORDER_FIELDS" => array(
												0 => "NAME",
												1 => "TEL"
											),
											"DEFAULT_PERSON_TYPE" => "1",
											"DEFAULT_ORDER_PROP_NAME" => "1",
											"DEFAULT_ORDER_PROP_TEL" => "3",
											"DEFAULT_ORDER_PROP_EMAIL" => "2",
											"DEFAULT_DELIVERY" => "0",
											"DEFAULT_PAYMENT" => "0",											
											"BUY_MODE" => "ONE",											
											"DUPLICATE_LETTER_TO_EMAILS" => array(
												0 => "admin"
											)
										),
										false
									);?>
								<?endif;
							elseif(!$arResult["CAN_BUY"]):?>
								<a class="btn_buy apuo_detail" id="order_anch_<?=$arResult['ID']?>" href="javascript:void(0)" rel="nofollow"><i class="fa fa-clock-o"></i><?=GetMessage("CATALOG_ELEMENT_UNDER_ORDER")?></a>
								<?$APPLICATION->IncludeComponent("altop:ask.price", "order",
									Array(
										"ELEMENT_ID" => $arResult["ID"],		
										"ELEMENT_NAME" => $arResult["NAME"],
										"EMAIL_TO" => "",				
										"REQUIRED_FIELDS" => array(
											0 => "NAME",
											1 => "TEL",
											2 => "TIME"
										)
									),
									false
								);?>
								<?$APPLICATION->IncludeComponent("bitrix:sale.notice.product", "", 
									array(
										"NOTIFY_ID" => $arResult["ID"],
										"NOTIFY_URL" => htmlspecialcharsback($arResult["SUBSCRIBE_URL"]),
										"NOTIFY_USE_CAPTHA" => "Y"
									),									
									false
								);?>
							<?endif;?>										
						</div>
					<?endif;?>
				</div>				
				<div class="compare_delay">
					<?if($arParams["USE_COMPARE"]=="Y"):?>
						<div class="compare">
							<a href="javascript:void(0)" class="catalog-item-compare" id="catalog_add2compare_link_<?=$arResult['ID']?>" onclick="return addToCompare('<?=$arResult["COMPARE_URL"]?>', 'catalog_add2compare_link_<?=$arResult["ID"]?>');" rel="nofollow"><span class="compare_cont"><i class="fa fa-bar-chart"></i><i class="fa fa-check"></i><span class="compare_text"><?=GetMessage('CATALOG_ELEMENT_ADD_TO_COMPARE')?></span></span></a>
						</div>
					<?endif;?>
					<div class="catalog-detail-delay" id="<?=$arItemIDs['DELAY']?>">
						<?if(isset($arResult["OFFERS"]) && !empty($arResult["OFFERS"])):
							foreach($arResult["OFFERS"] as $key => $arOffer):
								if($arOffer["CAN_BUY"]):
									foreach($arOffer["PRICES"] as $code => $arPrice):
										if($arPrice["MIN_PRICE"] == "Y"):
											if($arPrice["VALUE"] > 0):
												$props = array();
												foreach($arOffer["DISPLAY_PROPERTIES"] as $propOffer) {
													$props[] = array(
														"NAME" => $propOffer["NAME"],
														"CODE" => $propOffer["CODE"],
														"VALUE" => strip_tags($propOffer["DISPLAY_VALUE"])
													);
												}
												$props = strtr(base64_encode(addslashes(gzcompress(serialize($props),9))), '+/=', '-_,');?>
												<div id="delay_<?=$arOffer['ID']?>" class="delay <?=$arResult['ID']?> hidden">
													<a href="javascript:void(0)" id="catalog-item-delay-<?=$arOffer['ID']?>" class="catalog-item-delay" onclick="return addToDelay('<?=$arOffer["ID"]?>', '<?=$arOffer["CATALOG_MEASURE_RATIO"]?>', '<?=$props?>', '', 'catalog-item-delay-<?=$arOffer["ID"]?>')" rel="nofollow"><span class="delay_cont"><i class="fa fa-heart-o"></i><i class="fa fa-check"></i><span class="delay_text"><?=GetMessage('CATALOG_ELEMENT_ADD_TO_DELAY')?></span></span></a>
												</div>
											<?endif;
										endif;
									endforeach;
								endif;
							endforeach;
						else:
							if($arResult["CAN_BUY"]):
								foreach($arResult["PRICES"] as $code => $arPrice):
									if($arPrice["MIN_PRICE"] == "Y"):
										if($arPrice["VALUE"] > 0):?>
											<div class="delay">
												<a href="javascript:void(0)" id="catalog-item-delay-<?=$arResult['ID']?>" class="catalog-item-delay" onclick="return addToDelay('<?=$arResult["ID"]?>', '<?=$arResult["CATALOG_MEASURE_RATIO"]?>', '', '', 'catalog-item-delay-<?=$arResult["ID"]?>')" rel="nofollow"><span class="delay_cont"><i class="fa fa-heart-o"></i><i class="fa fa-check"></i><span class="delay_text"><?=GetMessage('CATALOG_ELEMENT_ADD_TO_DELAY')?></span></span></a>
											</div>
										<?endif;
									endif;
								endforeach;
							endif;
						endif?>
					</div>
				</div>
			</div>			
			
			<div class="article_rating">
				<div class="article">
					<?=GetMessage("CATALOG_ELEMENT_ARTNUMBER")?><?=!empty($arResult["PROPERTIES"]["ARTNUMBER"]["VALUE"]) ? $arResult["PROPERTIES"]["ARTNUMBER"]["VALUE"] : "-";?>
				</div>
				<div class="rating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
					<?$APPLICATION->IncludeComponent("bitrix:iblock.vote", "ajax",
						Array(
							"DISPLAY_AS_RATING" => "vote_avg",
							"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
							"IBLOCK_ID" => $arParams["IBLOCK_ID"],
							"ELEMENT_ID" => $arResult["ID"],
							"ELEMENT_CODE" => "",
							"MAX_VOTE" => "5",
							"VOTE_NAMES" => array("1","2","3","4","5"),
							"SET_STATUS_404" => "N",
							"CACHE_TYPE" => $arParams["CACHE_TYPE"],
							"CACHE_TIME" => $arParams["CACHE_TIME"],
							"CACHE_NOTES" => "",
							"READ_ONLY" => "N"
						),
						false,
						array("HIDE_ICONS" => "Y")
					);?>
					<?if($arResult["PROPERTIES"]["vote_count"]["VALUE"]):?>
						<meta content="<?=round($arResult['PROPERTIES']['vote_sum']['VALUE']/$arResult['PROPERTIES']['vote_count']['VALUE'], 2);?>" itemprop="ratingValue" />
						<meta content="<?=$arResult['PROPERTIES']['vote_count']['VALUE']?>" itemprop="ratingCount" />
					<?else:?>
						<meta content="0" itemprop="ratingValue" />
						<meta content="0" itemprop="ratingCount" />
					<?endif;?>					
				</div>				
			</div>			
			
			<?if(!empty($arResult["PREVIEW_TEXT"])):?>				
				<div class="catalog-detail-preview-text" itemprop="description">
					<?=$arResult["PREVIEW_TEXT"]?>
				</div>
			<?endif;?>
			
			<?if(isset($arResult["OFFERS"]) && !empty($arResult["OFFERS"]) && !empty($arResult["OFFERS_PROP"])) {
				$arSkuProps = array();?>
				<div class="catalog-detail-offers" id="<?=$arItemIDs['PROP_DIV'];?>">
					<?foreach($arResult["SKU_PROPS"] as &$arProp) {
						if(!isset($arResult["OFFERS_PROP"][$arProp["CODE"]]))
							continue;
						$arSkuProps[] = array(
							"ID" => $arProp["ID"],
							"SHOW_MODE" => $arProp["SHOW_MODE"]
						);?>						
						<div class="offer_block" id="<?=$arItemIDs['PROP'].$arProp['ID'];?>_cont">
							<div class="h3"><?=htmlspecialcharsex($arProp["NAME"]);?></div>
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
						</div>
					<?}
					unset($arProp);?>
				</div>
			<?}?>
			
			<?if(isset($arResult["SELECT_PROPS"]) && !empty($arResult["SELECT_PROPS"])):
				$arSelProps = array();?>
				<div class="catalog-detail-offers" id="<?=$arItemIDs['SELECT_PROP_DIV'];?>">
					<?foreach($arResult["SELECT_PROPS"] as $key => $arProp):
						$arSelProps[] = array(
							"ID" => $arProp["ID"]
						);?>
						<div class="offer_block" id="<?=$arItemIDs['SELECT_PROP'].$arProp['ID'];?>">
							<div class="h3"><?=htmlspecialcharsex($arProp["NAME"]);?></div>
							<ul class="<?=$arProp['CODE']?>">
								<?$props = array();
								foreach($arProp["DISPLAY_VALUE"] as $arOneValue) {
									$props[$key] = array(
										"NAME" => $arProp["NAME"],
										"CODE" => $arProp["CODE"],
										"VALUE" => strip_tags($arOneValue)
									);
									$props[$key] = strtr(base64_encode(addslashes(gzcompress(serialize($props[$key]),9))), '+/=', '-_,');?>
									<li data-select-onevalue="<?=$props[$key]?>">
										<span title="<?=$arOneValue;?>"><?=$arOneValue?></span>
									</li>
								<?}?>
							</ul>
						</div>
					<?endforeach;
					unset($arProp);?>
				</div>
			<?endif;?>
									
			<?if(!empty($arResult["DISPLAY_PROPERTIES"])):?>
				<div class="catalog-detail-properties">
					<div class="h4"><?=GetMessage("CATALOG_ELEMENT_PROPERTIES")?></div>
					<?foreach($arResult["DISPLAY_PROPERTIES"] as $k => $v):?>
						<div class="catalog-detail-property">
							<span class="name"><?=$v["NAME"]?></span> 
							<span class="val"><?=is_array($v["DISPLAY_VALUE"]) ? implode(", ", $v["DISPLAY_VALUE"]) : $v["DISPLAY_VALUE"];?></span>
						</div>
					<?endforeach;?>
				</div>
			<?endif;?>				
		</div>
	</div>
	<?if(count($arResult["KIT_ITEMS"]) > 0):?>
		<div class="set-constructor-items">
			<div class="h3"><?=GetMessage("CATALOG_ELEMENT_KIT_ITEMS")?></div>
			<div class="catalog-item-cards">
				<?foreach($arResult["KIT_ITEMS"] as $key => $arItem):?>
					<div class="catalog-item-card set_item">
						<div class="catalog-item-info">
							<div class="item-image">
								<?if(is_array($arItem["PREVIEW_IMG"])):?>
									<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
										<img class="item_img" src="<?=$arItem['PREVIEW_IMG']['SRC']?>" width="<?=$arItem['PREVIEW_IMG']['WIDTH']?>" height="<?=$arItem['PREVIEW_IMG']['HEIGHT']?>" alt="<?=$arItem['NAME']?>" />
									</a>
								<?else:?>
									<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
										<img class="item_img" src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" width="150" height="150" alt="<?=$arItem['NAME']?>" />
									</a>
								<?endif?>
							</div>
							<div class="item-all-title">
								<a class="item-title" href="<?=$arItem['DETAIL_PAGE_URL']?>" title="<?=$arItem['NAME']?>">
									<?=$arItem["NAME"]?>
								</a>
							</div>
							<div class="item-price-cont">
								<?$price = CCurrencyLang::GetCurrencyFormat($arItem["PRICE_CURRENCY"], "ru");
								if(empty($price["THOUSANDS_SEP"])):
									$price["THOUSANDS_SEP"] = " ";
								endif;
								$currency = str_replace("#", " ", $price["FORMAT_STRING"]);?>

								<div class="item-price">
									<?if($arItem["PRICE_DISCOUNT_VALUE"] < $arItem["PRICE_VALUE"]):?>
										<span class="catalog-item-price-old">
											<?=number_format($arItem["PRICE_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
											<?=$currency;?>
										</span>
										<span class="catalog-item-price">
											<?=number_format($arItem["PRICE_DISCOUNT_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
											<span class="unit"><?=$currency?></span>
										</span>		
									<?else:?>
										<span class="catalog-item-price">
											<?=number_format($arItem["PRICE_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
											<span class="unit"><?=$currency?></span>
										</span>		
									<?endif;?>
								</div>
							</div>
						</div>
					</div>
				<?endforeach;?>
			</div>
			<div class="clr"></div>
		</div>
	<?endif;?>
	<div id="set-constructor-items-to"></div>
	<div class="section">
		<ul class="tabs">
			<li class="current">
				<a href="#tab1"><span><?=GetMessage("CATALOG_ELEMENT_FULL_DESCRIPTION")?></span></a>
			</li>
			<li style="<?if(empty($arResult["PROPERTIES"]["FREE_TAB"]["VALUE"])){ echo 'display:none;'; }?>">
				<a href="#tab2"><span><?=$arResult["PROPERTIES"]["FREE_TAB"]["NAME"]?></span></a>
			</li>
			<li style="<?if(empty($arResult["PROPERTIES"]["ACCESSORIES"]["VALUE"])){ echo 'display:none;'; }?>">
				<a href="#tab3"><span><?=$arResult["PROPERTIES"]["ACCESSORIES"]["NAME"]?></span></a>
			</li>
			<li>
				<a href="#tab4"><span><?=GetMessage("CATALOG_ELEMENT_REVIEWS")?> <span class="reviews_count">(<?=$arResult["REVIEWS"]["COUNT"]?>)</span></span></a>
			</li>
			<li>
				<a href="#tab5"><span><?=GetMessage("CATALOG_ELEMENT_SHOPS")?></span></a>
			</li>
		</ul>
		<div class="box visible">
			<div class="description">
				<?=$arResult["DETAIL_TEXT"];?>
			</div>
		</div>
		<div class="box" style="<?if(empty($arResult["PROPERTIES"]["FREE_TAB"]["VALUE"])){ echo 'display:none;'; }?>">
			<div class="tab-content">
				<?=$arResult["PROPERTIES"]["FREE_TAB"]["~VALUE"]["TEXT"];?>
			</div>
		</div>
		<div class="box" id="accessories-to" style="<?if(empty($arResult["PROPERTIES"]["ACCESSORIES"]["VALUE"])){ echo 'display:none;'; }?>"></div>
		<div class="box" id="catalog-reviews-to"></div>
		<div class="box">
			<div id="<?=$arItemIDs['STORE'];?>">
				<?if(isset($arResult["OFFERS"]) && !empty($arResult["OFFERS"])):
					foreach($arResult["OFFERS"] as $key => $arOffer):?>
						<div id="catalog-detail-stores-<?=$arOffer['ID']?>" class="catalog-detail-stores <?=$arResult['ID']?> hidden">
							<?$APPLICATION->IncludeComponent("bitrix:catalog.store.amount",	".default",
								array(
									"ELEMENT_ID" => $arOffer["ID"],
									"STORE_PATH" => $arParams["STORE_PATH"],
									"CACHE_TYPE" => $arParams["CACHE_TYPE"],
									"CACHE_TIME" => $arParams["CACHE_TIME"],
									"MAIN_TITLE" => $arParams["MAIN_TITLE"],
									"USE_STORE_PHONE" => $arParams["USE_STORE_PHONE"],
									"SCHEDULE" => $arParams["USE_STORE_SCHEDULE"],
									"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
									"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],									
									"STORES" => $arParams['STORES'],
									"SHOW_EMPTY_STORE" => $arParams['SHOW_EMPTY_STORE'],
									"SHOW_GENERAL_STORE_INFORMATION" => $arParams['SHOW_GENERAL_STORE_INFORMATION'],
									"USER_FIELDS" => $arParams['USER_FIELDS'],
									"FIELDS" => $arParams['FIELDS']
								),
								false,
								array("HIDE_ICONS" => "Y")
							);?>
						</div>
					<?endforeach;
				else:?>
					<div class="catalog-detail-stores">
						<?$APPLICATION->IncludeComponent("bitrix:catalog.store.amount",	".default",
							array(
								"ELEMENT_ID" => $arResult["ID"],
								"STORE_PATH" => $arParams["STORE_PATH"],
								"CACHE_TYPE" => $arParams["CACHE_TYPE"],
								"CACHE_TIME" => $arParams["CACHE_TIME"],
								"MAIN_TITLE" => $arParams["MAIN_TITLE"],
								"USE_STORE_PHONE" => $arParams["USE_STORE_PHONE"],
								"SCHEDULE" => $arParams["USE_STORE_SCHEDULE"],
								"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
								"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
								"STORES" => $arParams['STORES'],
								"SHOW_EMPTY_STORE" => $arParams['SHOW_EMPTY_STORE'],
								"SHOW_GENERAL_STORE_INFORMATION" => $arParams['SHOW_GENERAL_STORE_INFORMATION'],
								"USER_FIELDS" => $arParams['USER_FIELDS'],
								"FIELDS" => $arParams['FIELDS']
							),
							false,
							array("HIDE_ICONS" => "Y")
						);?>
					</div>
				<?endif;?>
			</div>
		</div>
	</div>
	<div class="clr"></div>
</div>

<?if(isset($arResult["OFFERS"]) && !empty($arResult["OFFERS"])) {
	$arJSParams = array(
		"CONFIG" => array(
			"USE_CATALOG" => $arResult["CATALOG"],
		),
		"PRODUCT_TYPE" => $arResult["CATALOG_TYPE"],
		"VISUAL" => array(
			"ID" => $arItemIDs["ID"],
			"PICT_ID" => $arItemIDs["PICT"],
			"PRICE_ID" => $arItemIDs["PRICE"],
			"BUY_ID" => $arItemIDs["BUY"],
			"DELAY_ID" => $arItemIDs["DELAY"],
			"STORE_ID" => $arItemIDs["STORE"],
			"TREE_ID" => $arItemIDs["PROP_DIV"],
			"TREE_ITEM_ID" => $arItemIDs["PROP"],
		),
		"PRODUCT" => array(
			"ID" => $arResult["ID"],
			"NAME" => $arResult["~NAME"]
		),
		"OFFERS" => $arResult["JS_OFFERS"],
		"OFFER_SELECTED" => $arResult["OFFERS_SELECTED"],
		"TREE_PROPS" => $arSkuProps
	);
} else {
	$arJSParams = array(
		"CONFIG" => array(
			"USE_CATALOG" => $arResult["CATALOG"]
		),
		"PRODUCT_TYPE" => $arResult["CATALOG_TYPE"],	
		"VISUAL" => array(
			"ID" => $arItemIDs["ID"],
		),
		"PRODUCT" => array(
			"ID" => $arResult["ID"],
			"NAME" => $arResult["~NAME"]
		)
	);	
}

if(isset($arResult["SELECT_PROPS"]) && !empty($arResult["SELECT_PROPS"])) {
	$arJSParams["VISUAL"]["SELECT_PROP_ID"] = $arItemIDs["SELECT_PROP_DIV"];
	$arJSParams["VISUAL"]["SELECT_PROP_ITEM_ID"] = $arItemIDs["SELECT_PROP"];
	$arJSParams["SELECT_PROPS"] = $arSelProps;
}?>

<script type="text/javascript">
	var <?=$strObName;?> = new JCCatalogElement(<?=CUtil::PhpToJSObject($arJSParams, false, true);?>);
	BX.message({
		SITE_ID: "<?=SITE_ID;?>"
	});
</script>