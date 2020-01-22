<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $arSetting;?>

<script type="text/javascript">
	//<![CDATA[
	$(function() {
		$(".add2basket_form").submit(function() {
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
	});
	//]]>
</script>

<?$itemsCnt = count($arResult["ITEMS"]);
$delUrlID = "";

foreach($arResult["ITEMS"] as $arElement):
	$delUrlID .= "&ID[]=".$arElement["ID"];
endforeach;?>

<div class="compare-list-result">
	<div class="sort tabfilter">
		<div class="sorttext"><?=GetMessage("CATALOG_CHARACTERISTICS_LABEL")?>:</div>
		<?if($arResult["DIFFERENT"]):?>
			<a class="sortbutton" href="<?=htmlspecialchars($APPLICATION->GetCurPageParam("DIFFERENT=N",array("DIFFERENT")))?>" rel="nofollow">
				<span class="def"><?=GetMessage("CATALOG_ALL_CHARACTERISTICS")?></span>
				<span class="mob"><?=GetMessage("CATALOG_ALL_CHARACTERISTICS_MOBILE")?></span>
			</a>
			<a class="sortbutton current" href="javascript:void(0)">
				<span class="def"><?=GetMessage("CATALOG_ONLY_DIFFERENT")?></span>
				<span class="mob"><?=GetMessage("CATALOG_ONLY_DIFFERENT_MOBILE")?></span>
			</a>
		<?else:?>
			<a class="sortbutton current" href="javascript:void(0)">
				<span class="def"><?=GetMessage("CATALOG_ALL_CHARACTERISTICS")?></span>
				<span class="mob"><?=GetMessage("CATALOG_ALL_CHARACTERISTICS_MOBILE")?></span>
			</a>
			<a class="sortbutton" href="<?=htmlspecialchars($APPLICATION->GetCurPageParam("DIFFERENT=Y",array("DIFFERENT")))?>" rel="nofollow">
				<span class="def"><?=GetMessage("CATALOG_ONLY_DIFFERENT")?></span>
				<span class="mob"><?=GetMessage("CATALOG_ONLY_DIFFERENT_MOBILE")?></span>
			</a>
		<?endif;?>
	</div>
	<?$i = 0;?>
	<div class="compare-grid">
		<?if($itemsCnt > 4):?>
			<table class="compare-grid" style="width:<?=($itemsCnt*25 + 25)?>%; table-layout: fixed;">
		<?else:?>
			<table class="compare-grid">
				<col />
				<col span="<?=$itemsCnt?>" width="<?=round(100/$itemsCnt)?>%" />
		<?endif;?>
		<tbody>
			<?$i++;
			foreach($arResult["ITEMS"][0]["FIELDS"] as $key_field => $field):?>
				<tr>
					<td class="compare-property"></td>
					<?foreach($arResult["ITEMS"] as $key => $arElement):?>
						<td>
							<?switch($key_field):
								case "NAME":?>
									<a class="compare-title" href="<?=$arElement['DETAIL_PAGE_URL']?>"><?=$arElement[$key_field]?></a>
								<?break;
								case "PREVIEW_PICTURE":
								case "DETAIL_PICTURE":
									if(is_array($arElement["FIELDS"][$key_field])):?>
										<a href="<?=$arElement['DETAIL_PAGE_URL']?>">
											<img src="<?=$arElement['FIELDS'][$key_field]['PREVIEW_IMG']['SRC']?>" width="<?=$arElement['FIELDS'][$key_field]['PREVIEW_IMG']['WIDTH']?>" height="<?=$arElement['FIELDS'][$key_field]['PREVIEW_IMG']['HEIGHT']?>" alt="<?=$arElement['FIELDS'][$key_field]['ALT']?>" />
										</a>
									<?else:?>
										<a href="<?=$arElement['DETAIL_PAGE_URL']?>">
											<img src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" width="150" height="150" alt="<?=$arElement['FIELDS'][$key_field]['ALT']?>" />
										</a>
									<?endif;
								break;
								default:
									echo $arElement["FIELDS"][$key_field];
								break;
							endswitch;?>
						</td>
					<?endforeach;?>
				</tr>
				<?$i++;
			endforeach;?>
			
			<tr class="compare-delete">
				<td class="compare-property"></td>
				<?foreach($arResult["ITEMS"] as $key => $arElement):?>
					<td>
						<a class="btn_buy apuo compare-delete-item" href="<?=htmlspecialchars($APPLICATION->GetCurPageParam("action=DELETE_FROM_COMPARE_RESULT&IBLOCK_ID=".$arParams['IBLOCK_ID']."&ID[]=".$arElement['ID'],array("action", "IBLOCK_ID", "ID")))?>" title="<?=GetMessage('CATALOG_REMOVE_PRODUCT')?>"><i class="fa fa-trash-o"></i><?=GetMessage("CATALOG_REMOVE_PRODUCT")?></a>
					</td>
				<?endforeach;?>
			</tr>				

			<?foreach($arResult["SHOW_PROPERTIES"] as $key_prop => $arProperty):
				$arCompare = Array();
				foreach($arResult["ITEMS"] as $key => $arElement) {
					$arPropertyValue = $arElement["DISPLAY_PROPERTIES"][$key_prop]["VALUE"];
					if(is_array($arPropertyValue)) {
						sort($arPropertyValue);
						$arPropertyValue = implode(" / ", $arPropertyValue);
					}
					$arCompare[] = $arPropertyValue;
				}
				$diff = (count(array_unique($arCompare)) > 1 ? true : false);
				if($diff || !$arResult["DIFFERENT"]):?>
					<tr<?if($i%2 == 0) echo ' class="alt"';?>>
						<?if(!empty($arProperty["VALUE"])) {?>
							<td class="compare-property"><?=$arProperty["NAME"]?></td>
							<?foreach($arResult["ITEMS"] as $key => $arElement):
								if($diff):?>
									<td>
										<?if($key_prop == "MANUFACTURER"):
											if(!empty($arElement["PROPERTIES"][$key_prop]["PREVIEW_IMG"]["SRC"])):?>
												<img src="<?=$arElement['PROPERTIES'][$key_prop]['PREVIEW_IMG']['SRC']?>" width="<?=$arElement['PROPERTIES'][$key_prop]['PREVIEW_IMG']['WIDTH']?>" height="<?=$arElement['PROPERTIES'][$key_prop]['PREVIEW_IMG']['HEIGHT']?>" alt="<?=$arElement['PROPERTIES'][$key_prop]['NAME']?>" style="margin:0px 0px 3px 0px;" />
												<br />
											<?endif;
										endif;?>
										<?=(is_array($arElement["DISPLAY_PROPERTIES"][$key_prop]["DISPLAY_VALUE"]) ? implode("/ ", $arElement["DISPLAY_PROPERTIES"][$key_prop]["DISPLAY_VALUE"]) : $arElement["DISPLAY_PROPERTIES"][$key_prop]["DISPLAY_VALUE"]);?>
									</td>
								<?else:?>
									<td>
										<?if($key_prop == "MANUFACTURER"):
											if(!empty($arElement["PROPERTIES"][$key_prop]["PREVIEW_IMG"]["SRC"])):?>
												<img src="<?=$arElement['PROPERTIES'][$key_prop]['PREVIEW_IMG']['SRC']?>" width="<?=$arElement['PROPERTIES'][$key_prop]['PREVIEW_IMG']['WIDTH']?>" height="<?=$arElement['PROPERTIES'][$key_prop]['PREVIEW_IMG']['HEIGHT']?>" alt="<?=$arElement['PROPERTIES'][$key_prop]['NAME']?>" style="margin:0px 0px 3px 0px;" />
												<br />
											<?endif;
										endif;?>
										<?=(is_array($arElement["DISPLAY_PROPERTIES"][$key_prop]["DISPLAY_VALUE"]) ? implode("/ ", $arElement["DISPLAY_PROPERTIES"][$key_prop]["DISPLAY_VALUE"]) : $arElement["DISPLAY_PROPERTIES"][$key_prop]["DISPLAY_VALUE"]);?>
									</td>
								<?endif;
							endforeach;?>
						<?}?>
					</tr>
					<?$i++;
				endif;
			endforeach;?>
			
			<tr class="price">
				<td class="compare-property"></td>
				<?foreach($arResult["ITEMS"] as $key => $arElement):?>
					<td>
						<?if(isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"])):
							$price = CCurrencyLang::GetCurrencyFormat($arElement["OFFERS_MIN_PRICE"]["CURRENCY"], "ru");
							if(empty($price["THOUSANDS_SEP"])):
								$price["THOUSANDS_SEP"] = " ";
							endif;
							$currency = str_replace("#", " ", $price["FORMAT_STRING"]);
							
							if($arElement["OFFERS_MIN_PRICE"]["VALUE"] == 0):?>
								<span class="item-no-price">
									<?=GetMessage("CATALOG_ELEMENT_NO_PRICE")?>									
									<span class="unit">
										<span><?=(!empty($arElement["OFFERS_MIN_PRICE"]["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arElement["OFFERS_MIN_PRICE"]["CATALOG_MEASURE_NAME"] : "";?></span>
									</span>
								</span>
							<?elseif($arElement["OFFERS_MIN_PRICE"]["DISCOUNT_VALUE"] < $arElement["OFFERS_MIN_PRICE"]["VALUE"]):?>	
								<span class="catalog-item-price-old">
									<?=number_format($arElement["OFFERS_MIN_PRICE"]["VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
									<?=$currency;?>
								</span>
								<span class="catalog-item-price-percent">									
									<?=GetMessage("CATALOG_ELEMENT_SKIDKA")." ".$arElement["OFFERS_MIN_PRICE"]["PRINT_DISCOUNT_DIFF"];?>
								</span>
								<span class="catalog-item-price">
									<?=number_format($arElement["OFFERS_MIN_PRICE"]["DISCOUNT_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
									<span class="unit">
										<?=$currency?>
										<span><?=(!empty($arElement["OFFERS_MIN_PRICE"]["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arElement["OFFERS_MIN_PRICE"]["CATALOG_MEASURE_NAME"] : "";?></span>
									</span>
								</span>		
							<?else:?>
								<span class="catalog-item-price">
									<?=number_format($arElement["OFFERS_MIN_PRICE"]["VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
									<span class="unit">
										<?=$currency?>
										<span><?=(!empty($arElement["OFFERS_MIN_PRICE"]["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arElement["OFFERS_MIN_PRICE"]["CATALOG_MEASURE_NAME"] : "";?></span>
									</span>
								</span>								
							<?endif;?>
							<div class="available">
								<?if($arElement["OFFERS_MIN_PRICE"]["CAN_BUY"]):?>
									<div class="avl">
										<i class="fa fa-check-circle"></i>
										<span><?=GetMessage("CATALOG_ELEMENT_AVAILABLE")?><?=in_array("PRODUCT_QUANTITY", $arSetting["GENERAL_SETTINGS"]["VALUE"]) ? " ".$arElement["OFFERS_MIN_PRICE"]["CATALOG_QUANTITY"] : ""?></span>
									</div>
								<?elseif(!$arElement["OFFERS_MIN_PRICE"]["CAN_BUY"]):?>
									<div class="not_avl">
										<i class="fa fa-times-circle"></i>
										<span><?=GetMessage("CATALOG_ELEMENT_NOT_AVAILABLE")?></span>
									</div>
								<?endif;?>
							</div>		
						<?else:
							foreach($arElement["PRICES"] as $code => $arPrice):
								if($arPrice["MIN_PRICE"] == "Y"):
									if($arPrice["CAN_ACCESS"]):
													
										$price = CCurrencyLang::GetCurrencyFormat($arPrice["CURRENCY"], "ru");
										if(empty($price["THOUSANDS_SEP"])):
											$price["THOUSANDS_SEP"] = " ";
										endif;
										$currency = str_replace("#", " ", $price["FORMAT_STRING"]);
													
										if($arPrice["VALUE"] == 0):
											$arResult["ITEMS"][$key]["ASK_PRICE"] = 1;?>
											<span class="item-no-price">
												<?=GetMessage("CATALOG_ELEMENT_NO_PRICE")?>											
												<span class="unit">
													<span><?=(!empty($arElement["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arElement["CATALOG_MEASURE_NAME"] : "";?></span>
												</span>
											</span>
										<?elseif($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>									
											<span class="catalog-item-price-old">
												<?=number_format($arPrice["VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
												<?=$currency;?>
											</span>
											<span class="catalog-item-price-percent">
												<?=GetMessage("CATALOG_ELEMENT_SKIDKA")." ".$arPrice["PRINT_DISCOUNT_DIFF"];?>
											</span>
											<span class="catalog-item-price">
												<?=number_format($arPrice["DISCOUNT_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>		
												<span class="unit">
													<?=$currency?>
													<span><?=(!empty($arElement["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arElement["CATALOG_MEASURE_NAME"] : "";?></span>
												</span>
											</span>	
										<?else:?>
											<span class="catalog-item-price" itemprop="price">
												<?=number_format($arPrice["VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
												<span class="unit">
													<?=$currency?>
													<span><?=(!empty($arElement["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arElement["CATALOG_MEASURE_NAME"] : "";?></span>
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
					</td>
				<?endforeach;?>
			</tr>

			<tr class="buy">
				<td class="compare-property"></td>
				<?foreach($arResult["ITEMS"] as $key => $arElement):
					$strMainID = $this->GetEditAreaId($arElement["ID"]);
					$arItemIDs = array(
						"ID" => $strMainID
					);?>
					<td>
						<div class="buy_more">
							<?if(isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"])):?>
								<script type="text/javascript">
									$(function() {
										$("#add2basket_offer_form_<?=$arElement['ID']?>").submit(function() {
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
														
											quantityItem = form.find("#quantity_<?=$arElement['ID']?>").attr("value");
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
									<form action="<?=$APPLICATION->GetCurPage()?>" id="add2basket_offer_form_<?=$arElement['ID']?>">
										<div class="qnt_cont">
											<a href="javascript:void(0)" class="minus" onclick="if (BX('quantity_<?=$arElement["ID"]?>').value > <?=$arElement["OFFERS_MIN_PRICE"]["CATALOG_MEASURE_RATIO"]?>) BX('quantity_<?=$arElement["ID"]?>').value = parseFloat(BX('quantity_<?=$arElement["ID"]?>').value)-<?=$arElement["OFFERS_MIN_PRICE"]["CATALOG_MEASURE_RATIO"]?>;"><span>-</span></a>
											<input type="text" id="quantity_<?=$arElement['ID']?>" name="quantity" class="quantity" value="<?=$arElement['OFFERS_MIN_PRICE']['CATALOG_MEASURE_RATIO']?>"/>
											<a href="javascript:void(0)" class="plus" onclick="BX('quantity_<?=$arElement["ID"]?>').value = parseFloat(BX('quantity_<?=$arElement["ID"]?>').value)+<?=$arElement["OFFERS_MIN_PRICE"]["CATALOG_MEASURE_RATIO"]?>;"><span>+</span></a>
										</div>
										<button type="submit" name="add2basket" class="btn_buy" value="<?=GetMessage('CATALOG_ELEMENT_ADD_TO_CART')?>"><i class="fa fa-shopping-cart"></i><span><?=GetMessage("CATALOG_ELEMENT_ADD_TO_CART")?></span></button>
									</form>
								</div>
							<?else:								
								if(isset($arElement["SELECT_PROPS"]) && !empty($arElement["SELECT_PROPS"])):?>
									<script type="text/javascript">
										$(function() {
											$("#add2basket_select_form_<?=$arElement['ID']?>").submit(function() {
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
																	
												quantityItem = form.find("#quantity_<?=$arElement['ID']?>").attr("value");
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
									<?if($arElement["CAN_BUY"]):
										if($arElement["ASK_PRICE"]):?>
											<a class="btn_buy apuo" id="ask_price_anch_<?=$arElement['ID']?>" href="javascript:void(0)" rel="nofollow"><i class="fa fa-comment-o"></i><span><?=GetMessage("CATALOG_ELEMENT_ASK_PRICE")?></span></a>
											<?$APPLICATION->IncludeComponent("altop:ask.price", "",
												Array(
													"ELEMENT_ID" => $arElement["ID"],		
													"ELEMENT_NAME" => $arElement["NAME"],
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
										<?elseif(!$arElement["ASK_PRICE"]):
											if(isset($arElement["SELECT_PROPS"]) && !empty($arElement["SELECT_PROPS"])):?>
												<form action="<?=$APPLICATION->GetCurPage()?>" id="add2basket_select_form_<?=$arElement['ID']?>">
											<?else:?>									
												<form action="<?=SITE_DIR?>ajax/add2basket.php" class="add2basket_form">
											<?endif;?>
												<div class="qnt_cont">
													<a href="javascript:void(0)" class="minus" onclick="if (BX('quantity_<?=$arElement["ID"]?>').value > <?=$arElement["CATALOG_MEASURE_RATIO"]?>) BX('quantity_<?=$arElement["ID"]?>').value = parseFloat(BX('quantity_<?=$arElement["ID"]?>').value)-<?=$arElement["CATALOG_MEASURE_RATIO"]?>;"><span>-</span></a>
													<input type="text" id="quantity_<?=$arElement['ID']?>" name="quantity" class="quantity" value="<?=$arElement['CATALOG_MEASURE_RATIO']?>"/>
													<a href="javascript:void(0)" class="plus" onclick="BX('quantity_<?=$arElement["ID"]?>').value = parseFloat(BX('quantity_<?=$arElement["ID"]?>').value)+<?=$arElement["CATALOG_MEASURE_RATIO"]?>;"><span>+</span></a>
												</div>
												<?if(!isset($arElement["SELECT_PROPS"]) || empty($arElement["SELECT_PROPS"])):?>
													<input type="hidden" name="ID" value="<?=$arElement['ID']?>"/>				
													<input type="hidden" name="item_image" class="item_image" value="&lt;img class='item_image' src='<?=$arElement["FIELDS"]["DETAIL_PICTURE"]["PREVIEW_IMG"]["SRC"]?>' alt='<?=$arElement["NAME"]?>'/&gt;"/>										
													<input type="hidden" name="item_title" class="item_title" value="<?=$arElement['NAME']?>"/>
												<?endif;?>												
												<button type="submit" name="add2basket" class="btn_buy" value="<?=GetMessage('CATALOG_ELEMENT_ADD_TO_CART')?>"><i class="fa fa-shopping-cart"></i><span><?=GetMessage("CATALOG_ELEMENT_ADD_TO_CART")?></span></button>
												<?if(!isset($arElement["SELECT_PROPS"]) || empty($arElement["SELECT_PROPS"])):?>
													<small class="result hidden"><i class="fa fa-check"></i><span><?=GetMessage("CATALOG_ELEMENT_ADDED")?></span></small>
												<?endif;?>
											</form>												
										<?endif;										
									elseif(!$arElement["CAN_BUY"]):?>
										<a class="btn_buy apuo" id="order_anch_<?=$arElement['ID']?>" href="javascript:void(0)" rel="nofollow"><i class="fa fa-clock-o"></i><span><?=GetMessage("CATALOG_ELEMENT_UNDER_ORDER")?></span></a>
										<?$APPLICATION->IncludeComponent("altop:ask.price", "order",
											Array(
												"ELEMENT_ID" => $arElement["ID"],		
												"ELEMENT_NAME" => $arElement["NAME"],
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
									<?endif;?>
								</div>
							<?endif;?>							
						</div>
					</td>
				<?endforeach;?>
			</tr>

			<tr class="delay">
				<td class="compare-property"></td>
				<?foreach($arResult["ITEMS"] as $key => $arElement):?>
					<td align="center">
						<?if(isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"])):
							if($arElement["OFFERS_MIN_PRICE"]["CAN_BUY"]):
								if($arElement["OFFERS_MIN_PRICE"]["VALUE"] > 0):
									$props = array();
									foreach($arElement["OFFERS_MIN_PRICE"]["DISPLAY_PROPERTIES"] as $propOffer) {
										$props[] = array(
											"NAME" => $propOffer["NAME"],
											"CODE" => $propOffer["CODE"],
											"VALUE" => strip_tags($propOffer["DISPLAY_VALUE"])
										);
									}
									$props = strtr(base64_encode(addslashes(gzcompress(serialize($props),9))), '+/=', '-_,');?>		
									<div class="delay">
										<a href="javascript:void(0)" id="catalog-item-delay-<?=$arElement['OFFERS_MIN_PRICE']['ID']?>" class="catalog-item-delay" onclick="return addToDelay('<?=$arElement["OFFERS_MIN_PRICE"]["ID"]?>', '<?=$arElement["OFFERS_MIN_PRICE"]["CATALOG_MEASURE_RATIO"]?>', '<?=$props?>', '', 'catalog-item-delay-<?=$arElement["OFFERS_MIN_PRICE"]["ID"]?>')" rel="nofollow"><span class="delay_cont"><i class="fa fa-heart-o"></i><i class="fa fa-check"></i><span class="delay_text"><?=GetMessage('CATALOG_ELEMENT_ADD_TO_DELAY')?></span></span></a>
									</div>
								<?endif;
							endif;
						else:
							if($arElement["CAN_BUY"]):
								foreach($arElement["PRICES"] as $code => $arPrice):
									if($arPrice["MIN_PRICE"] == "Y"):
										if($arPrice["VALUE"] > 0):?>											
											<div class="delay">
												<a href="javascript:void(0)" id="catalog-item-delay-<?=$arElement['ID']?>" class="catalog-item-delay" onclick="return addToDelay('<?=$arElement["ID"]?>', '<?=$arElement["CATALOG_MEASURE_RATIO"]?>', '', '', 'catalog-item-delay-<?=$arElement["ID"]?>')" rel="nofollow"><span class="delay_cont"><i class="fa fa-heart-o"></i><i class="fa fa-check"></i><span class="delay_text"><?=GetMessage('CATALOG_ELEMENT_ADD_TO_DELAY')?></span></span></a>
											</div>
										<?endif;
									endif;
								endforeach;
							endif;
						endif;?>
					</td>
				<?endforeach;?>
			</tr>
		</tbody>
		</table>
	</div>

	<?foreach($arResult["ITEMS"] as $key => $arElement):
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
										<img src="<?=$arElement['FIELDS']['DETAIL_PICTURE']['PREVIEW_IMG']['SRC']?>" width="<?=$arElement['FIELDS']['DETAIL_PICTURE']['PREVIEW_IMG']['WIDTH']?>" height="<?=$arElement['FIELDS']['DETAIL_PICTURE']['PREVIEW_IMG']['HEIGHT']?>" alt="<?=$arElement['NAME']?>"/>
									<?endif;?>
								</div>
							<?endforeach;
						else:?>
							<div class="img">
								<?if(isset($arElement["FIELDS"]["DETAIL_PICTURE"]["PREVIEW_IMG"])):?>
									<img src="<?=$arElement["FIELDS"]["DETAIL_PICTURE"]["PREVIEW_IMG"]["SRC"]?>" width="<?=$arElement["FIELDS"]["DETAIL_PICTURE"]["PREVIEW_IMG"]["WIDTH"]?>" height="<?=$arElement["FIELDS"]["DETAIL_PICTURE"]["PREVIEW_IMG"]["HEIGHT"]?>" alt="<?=$arElement["NAME"]?>"/>
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
												<a class="btn_buy apuo" id="ask_price_anch_<?=$arOffer['ID']?>" href="javascript:void(0)" rel="nofollow"><i class="fa fa-comment-o"></i><span><?=GetMessage("CATALOG_ELEMENT_ASK_PRICE")?></span></a>
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
															<input type="hidden" name="item_image" class="item_image" value="&lt;img class='item_image' src='<?=$arElement["FIELDS"]["DETAIL_PICTURE"]["PREVIEW_IMG"]["SRC"]?>' alt='<?=$arElement["NAME"]?>'/&gt;"/>
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
													"REQUIRED_FIELDS" => array(
														0 => "NAME",
														1 => "TEL",
														2 => "TIME"
													)
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
												<input type="hidden" name="item_image" class="item_image" value="&lt;img class='item_image' src='<?=$arElement["FIELDS"]["DETAIL_PICTURE"]["PREVIEW_IMG"]["SRC"]?>' alt='<?=$arElement["NAME"]?>'/&gt;"/>										
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

	<?if(strlen($delUrlID) > 0) {
		$delUrl = htmlspecialchars($APPLICATION->GetCurPageParam("action=DELETE_FROM_COMPARE_RESULT&IBLOCK_ID=".$arParams['IBLOCK_ID'].$delUrlID,array("action", "IBLOCK_ID", "ID")));?>
		<a class="btn_buy apuo compare-delete-item-all" href="<?=$delUrl?>"><i class="fa fa-trash-o"></i><?=GetMessage("CATALOG_DELETE_ALL")?></a>
	<?}?>
</div>