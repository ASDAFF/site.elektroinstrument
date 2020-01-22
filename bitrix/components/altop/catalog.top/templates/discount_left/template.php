<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$frame = $this->createFrame("discount_left")->begin();
	if(count($arResult["ITEMS"]) > 0):?>
		<script type="text/javascript">
			//<![CDATA[
			$(function() {
				$('.discountSlider').anythingSlider({
					'theme': "discount-left",
					'mode': 'horiz',
					'expand': false,
					'resizeContents': false,
					'easing': 'easeInOutExpo',
					'buildArrows': false,
					'buildNavigation': true,
					'buildStartStop': false,
					'hashTags': false,
					'autoPlay': true,
					'pauseOnHover': true,
					'delay': 3000,
				});				
			});
			//]]>
		</script>
		<div class="discount_left">
			<ul class="discountSlider">
				<?foreach($arResult["ITEMS"] as $key => $arItem):
					
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

					<li>
						<div class="item-image">
							<?if($bPicture):?>
								<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
									<img class="item_img" src="<?=$arItem['PREVIEW_IMG']['SRC']?>" width="<?=$arItem['PREVIEW_IMG']['WIDTH']?>" height="<?=$arItem['PREVIEW_IMG']['HEIGHT']?>" alt="<?=$arItem['NAME']?>" />
									<span class="sticker">
										<?=$sticker?>
									</span>
									<?if(!empty($arItem["PROPERTIES"]["MANUFACTURER"]["PREVIEW_IMG"]["SRC"])):?>
										<img class="manufacturer" src="<?=$arItem['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['SRC']?>" width="<?=$arItem['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['WIDTH']?>" height="<?=$arItem['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['HEIGHT']?>" alt="<?=$arItem['PROPERTIES']['MANUFACTURER']['NAME']?>"/>
									<?endif;?>
								</a>
							<?else:?>
								<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
									<img class="item_img" src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" width="150" height="150" alt="<?=$arItem['NAME']?>" />
									<span class="sticker">
										<?=$sticker?>
									</span>
									<?if(!empty($arItem["PROPERTIES"]["MANUFACTURER"]["PREVIEW_IMG"]["SRC"])):?>
										<img class="manufacturer" src="<?=$arItem['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['SRC']?>" width="<?=$arItem['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['WIDTH']?>" height="<?=$arItem['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['HEIGHT']?>" alt="<?=$arItem['PROPERTIES']['MANUFACTURER']['NAME']?>"/>
									<?endif;?>
								</a>
							<?endif?>
						</div>
						<a class="item-title" href="<?=$arItem['DETAIL_PAGE_URL']?>" title="<?=$arItem['NAME']?>">
							<?=$arItem["NAME"]?>
						</a>
						<div class="item-price">
							<?if(isset($arItem["OFFERS"]) && !empty($arItem["OFFERS"])):
								$price = CCurrencyLang::GetCurrencyFormat($arItem["OFFERS_MIN_PRICE"]["CURRENCY"], "ru");
								if(empty($price["THOUSANDS_SEP"])):
									$price["THOUSANDS_SEP"] = " ";
								endif;
								$currency = str_replace("#", " ", $price["FORMAT_STRING"]);
									
								if($arItem["OFFERS_MIN_PRICE"]["VALUE"] == 0):?>									
									<span class="catalog-item-no-price">
										<?=GetMessage("CATALOG_ELEMENT_NO_PRICE")?>											
									</span>									
								<?elseif($arItem["OFFERS_MIN_PRICE"]["DISCOUNT_VALUE"] < $arItem["OFFERS_MIN_PRICE"]["VALUE"]):?>
									<span class="catalog-item-price">
										<?=number_format($arItem["OFFERS_MIN_PRICE"]["DISCOUNT_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
										<span><?=$currency?></span>
									</span>
									<span class="catalog-item-price-old">
										<?=number_format($arItem["OFFERS_MIN_PRICE"]["VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
										<?=$currency;?>
									</span>									
								<?else:?>
									<span class="catalog-item-price">
										<?=number_format($arItem["OFFERS_MIN_PRICE"]["VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
										<span><?=$currency?></span>
									</span>									
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

											if($arPrice["VALUE"] == 0):?>
												<span class="catalog-item-no-price">
													<?=GetMessage("CATALOG_ELEMENT_NO_PRICE")?>
												</span>
											<?elseif($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
												<span class="catalog-item-price">
													<?=number_format($arPrice["DISCOUNT_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
													<span><?=$currency?></span>
												</span>	
												<span class="catalog-item-price-old">
													<?=number_format($arPrice["VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
													<?=$currency;?>
												</span>
											<?else:?>
												<span class="catalog-item-price">
													<?=number_format($arPrice["VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
													<span><?=$currency?></span>
												</span>
											<?endif;											
										endif;
									endif;
								endforeach;
							endif;?>
						</div>
					</li>
				<?endforeach;?>
			</ul>
		</div>
	<?else:?>
		<div class="discount_left_empty"></div>
	<?endif;
$frame->end();?>