<?define("NOT_CHECK_PERMISSIONS", true);
define("NO_KEEP_STATISTIC", true);
define('NO_AGENT_CHECK', true);
define("NO_AGENT_STATISTIC", true);

if(isset($_REQUEST['site_id']) && !empty($_REQUEST['site_id'])) {
	if(!is_string($_REQUEST['site_id']))
		die();
	if(preg_match('/^[a-z0-9_]{2}$/i', $_REQUEST['site_id']) === 1)
		define('SITE_ID', $_REQUEST['site_id']);
} else {
	die();
}

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->ShowAjaxHead();
$APPLICATION->AddHeadScript("/bitrix/js/main/dd.js");

if(!CModule::IncludeModule("catalog"))
	return;

if(SITE_CHARSET != "utf-8")
	$_REQUEST["arParams"] = $APPLICATION->ConvertCharsetArray($_REQUEST["arParams"], "utf-8", SITE_CHARSET);

if(!is_array($_REQUEST["arParams"]["ELEMENT"]))
	return;

$curElementId = intval($_REQUEST["arParams"]["ELEMENT"]["ID"]);
$arCurElementInfo = $_REQUEST["arParams"]["ELEMENT"];
$arSetItemsInfo = $_REQUEST["arParams"]["SET_ITEMS"];
$arMessage = $_REQUEST["arParams"]["MESS"];
$curTemplatePath = $_REQUEST["arParams"]["CURRENT_TEMPLATE_PATH"];

$arSetElementsDefault = $_REQUEST["arParams"]["SET_ITEMS"]["DEFAULT"];
$arSetElementsOther = $_REQUEST["arParams"]["SET_ITEMS"]["OTHER"];

$setPriceVal = $_REQUEST["arParams"]["SET_ITEMS"]["PRICE_VALUE"];
$setOldPriceVal = $_REQUEST["arParams"]["SET_ITEMS"]["OLD_PRICE_VALUE"];
$setPriceCurr = $_REQUEST["arParams"]["SET_ITEMS"]["PRICE_CURRENCY"];

$setOldPrice = $_REQUEST["arParams"]["SET_ITEMS"]["OLD_PRICE"];
$setPriceDiscountDifference = $_REQUEST["arParams"]["SET_ITEMS"]["PRICE_DISCOUNT_DIFFERENCE"];
?>

<div class="set-constructor-descr"><?=$arMessage["CATALOG_SET_POPUP_DESC"]?></div>
<div class="set-constructor-items" id="bx_catalog_set_construct_popup_<?=$curElementId?>">	
	<div class="catalog-item-cards first_section">
		<div class="catalog-item-card set_element">
			<div class="catalog-item-info">
				<div class="item-image">
					<?if(is_array($arCurElementInfo["PREVIEW_IMG"])):?>						
						<span>
							<img class="item_img" src="<?=$arCurElementInfo['PREVIEW_IMG']['SRC']?>" width="<?=$arCurElementInfo['PREVIEW_IMG']['WIDTH']?>" height="<?=$arCurElementInfo['PREVIEW_IMG']['HEIGHT']?>" alt="<?=$arCurElementInfo['NAME']?>" />
						</span>
					<?else:?>						
						<span>
							<img class="item_img" src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" width="150" height="150" alt="<?=$arCurElementInfo['NAME']?>" />
						</span>
					<?endif?>
				</div>	
				<div class="item-all-title">
					<span class="item-title" title="<?=$arCurElementInfo['NAME']?>">
						<?=$arCurElementInfo["NAME"]?>
					</span>
				</div>
				<div class="item-price-cont">
					<?$price = CCurrencyLang::GetCurrencyFormat($arCurElementInfo["PRICE_CURRENCY"], "ru");
					if(empty($price["THOUSANDS_SEP"])):
						$price["THOUSANDS_SEP"] = " ";
					endif;
					$currency = str_replace("#", " ", $price["FORMAT_STRING"]);?>

					<div class="item-price">
						<?if($arCurElementInfo["PRICE_DISCOUNT_VALUE"] < $arCurElementInfo["PRICE_VALUE"]):?>
							<span class="catalog-item-price-old">
								<?=number_format($arCurElementInfo["PRICE_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
								<?=$currency;?>
							</span>
							<span class="catalog-item-price">
								<?=number_format($arCurElementInfo["PRICE_DISCOUNT_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
								<span class="unit"><?=$currency?></span>
							</span>		
						<?else:?>
							<span class="catalog-item-price">
								<?=number_format($arCurElementInfo["PRICE_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
								<span class="unit"><?=$currency?></span>
							</span>		
						<?endif;?>
					</div>
				</div>
			</div>		
		</div>		
		
		<?foreach($arSetElementsDefault as $arItem):?>
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
					<div class="item-all-title" data-item-id="<?=$arItem['ID']?>">
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

						<div class="item-price" data-discount-price="<?=$arItem['PRICE_DISCOUNT_VALUE']?>" data-price="<?=$arItem['PRICE_VALUE']?>" data-discount-diff-price="<?=$arItem['PRICE_DISCOUNT_DIFFERENCE_VALUE']?>">
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
				<a class="bx_item_set_del pop-up-close" href="javascript:void(0)" onclick="catalogSetPopupObj.catalogSetDelete(this);"><i class="fa fa-times"></i></a>
			</div>
		<?endforeach;?>

		<div class="catalog-item-card set_result">
			<div class="catalog-item-info">
				<div class="item-image">
					<i class="fa fa-check"></i>
				</div>
				<div class="item-price-cont">
					<?$price = CCurrencyLang::GetCurrencyFormat($setPriceCurr, "ru");
					if(empty($price["THOUSANDS_SEP"])):
						$price["THOUSANDS_SEP"] = " ";
					endif;
					$currency = str_replace("#", " ", $price["FORMAT_STRING"]);?>

					<div class="item-price">
						<?if($setPriceVal < $setOldPriceVal):?>
							<span class="catalog-item-price-old" id="set-result-price-old-<?=$curElementId?>">
								<?=$setOldPrice;?>
							</span>
							<span class="catalog-item-price-percent" id="set-result-price-percent-<?=$curElementId?>">
								<span class="text"><?=$arMessage["CATALOG_SET_DISCOUNT_DIFF"]?></span>
								<span class="set-result-price-discount" id="set-result-price-discount-<?=$curElementId?>"><?=$setPriceDiscountDifference?></span>
							</span>
							<span class="catalog-item-price">
								<span id="set-result-price-<?=$curElementId?>">
									<?=number_format($setPriceVal, $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
								</span>
								<span class="unit" id="set-result-price-currency-<?=$curElementId?>"><?=$currency?></span>
							</span>		
						<?else:?>
							<span class="catalog-item-price">
								<span id="set-result-price-<?=$curElementId?>">
									<?=number_format($setPriceVal, $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
								</span>
								<span class="unit" id="set-result-price-currency-<?=$curElementId?>"><?=$currency?></span>
							</span>		
						<?endif;?>
					</div>
				</div>
				<div class="buy_more">
					<div class="add2basket_block">						
						<button name="add2basket" class="btn_buy" onclick="catalogSetPopupObj.Add2Basket();" value="<?=$arMessage['CATALOG_SET_ADD_TO_CART']?>"><i class="fa fa-shopping-cart"></i><span><?=$arMessage["CATALOG_SET_ADD_TO_CART"]?></span></button>
						<small class="result hidden"><i class="fa fa-check"></i><span><?=$arMessage["CATALOG_SET_ADDED"]?></span></small>									
					</div>				
				</div>
			</div>
		</div>
	</div>

	<div class="catalog-item-cards last-section">
		<div class="set_construct_slider_cont">
			<div class="set_construct_slider" id="bx_catalog_set_construct_slider_<?=$curElementId?>" data-style-left="0" style="left:0px; width:<?=(count($arSetElementsOther) <=5) ? '100%' : (144 * count($arSetElementsOther) - 2).'px'?>">
				<?if(is_array($arSetElementsOther)):
					foreach($arSetElementsOther as $arItem):?>
						<div class="catalog-item-card set_item_other">				
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
								<div class="item-all-title" data-item-id="<?=$arItem['ID']?>">
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

									<div class="item-price" data-discount-price="<?=$arItem['PRICE_DISCOUNT_VALUE']?>" data-price="<?=$arItem['PRICE_VALUE']?>" data-discount-diff-price="<?=$arItem['PRICE_DISCOUNT_DIFFERENCE_VALUE']?>">
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
							<a class="pop-up-add" href="javascript:void(0)" onclick="catalogSetPopupObj.catalogSetAdd(this);">+</a>
						</div>
					<?endforeach;
				endif;?>
			</div>
		</div>
		<div class="set_construct_slider_arrow_left" id="bx_catalog_set_construct_slider_left_<?=$curElementId?>"<?if (count($arSetElementsOther) <= 5):?> style="display:none"<?endif?> onclick="catalogSetPopupObj.scrollItems('left')"><span class="arrow_cont"><i class="fa fa-chevron-left"></i></span></div>
		<div class="set_construct_slider_arrow_right" id="bx_catalog_set_construct_slider_right_<?=$curElementId?>"<?if (count($arSetElementsOther) <= 5):?> style="display:none"<?endif?> onclick="catalogSetPopupObj.scrollItems('right')"><span class="arrow_cont"><i class="fa fa-chevron-right"></i></span></div>		
	</div>	
</div>

<script type="text/javascript">
	var catalogSetPopupObj = new catalogSetConstructPopup(		
		<?=count($arSetElementsOther)?>,
		"<?=CUtil::JSEscape($arCurElementInfo['PRICE_CURRENCY'])?>",
		"<?=CUtil::JSEscape($arCurElementInfo['PRICE_VALUE'])?>",
		"<?=CUtil::JSEscape($arCurElementInfo['PRICE_DISCOUNT_VALUE'])?>",
		"<?=CUtil::JSEscape($arCurElementInfo['PRICE_DISCOUNT_DIFFERENCE_VALUE'])?>",
		"<?=$_REQUEST['arParams']['AJAX_PATH']?>",
		<?=CUtil::PhpToJSObject($_REQUEST['arParams']['DEFAULT_SET_IDS'])?>,
		"<?=$_REQUEST['arParams']['SITE_ID']?>",
		"<?=$curElementId?>",
		<?=CUtil::PhpToJSObject($_REQUEST['arParams']['ITEMS_RATIO'])?>,
		"<?=$arCurElementInfo['PREVIEW_IMG']['SRC']?>"
	);
	catalogSetPopupObj.visibilitySlider();
</script>