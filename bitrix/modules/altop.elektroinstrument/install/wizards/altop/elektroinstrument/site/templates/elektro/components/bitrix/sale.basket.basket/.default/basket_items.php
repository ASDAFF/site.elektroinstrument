<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="cart-items" id="id-cart-list">
	<div class="sort-clear">
		<div class="sort">
			<div class="sorttext"><?=GetMessage("SALE_PRD_IN_BASKET")?></div>
			<a href="javascript:void(0)" class="sortbutton current"><?=GetMessage("SALE_PRD_IN_BASKET_ACT")?></a>
			<?if($countItemsDelay = count($arResult["ITEMS"]["DelDelCanBuy"])):?>
				<a href="javascript:void(0)" onclick="ShowBasketItems(2);" class="sortbutton"><?=GetMessage("SALE_PRD_IN_BASKET_SHELVE")?> (<?=$countItemsDelay?>)</a>
			<?endif?>
			<?if($countItemsSubscribe = count($arResult["ITEMS"]["ProdSubscribe"])):?>
				<a href="javascript:void(0)" onclick="ShowBasketItems(3);" class="sortbutton"><?=GetMessage("SALE_PRD_IN_BASKET_SUBSCRIBE")?> (<?=$countItemsSubscribe?>)</a>			
			<?endif?>		
		</div>
		<?if(count($arResult["ITEMS"]["AnDelCanBuy"]) > 0):?>
			<div class="clear">
				<a class="btn_buy apuo clear_cart" href="<?=$arUrlTempl['BasketClear']?>" title="<?=GetMessage('SALE_CLEAR_CART')?>"><span class="clear_cont"><i class="fa fa-times"></i><span><?=GetMessage("SALE_CLEAR_CART")?></span></span></a>
			</div>
		<?endif;?>
	</div>
	<?if(count($arResult["ITEMS"]["AnDelCanBuy"]) > 0):?>	
		<div class="equipment">
			<div class="thead">
				<div class="cart-item-image"><?=GetMessage("SALE_IMAGE")?></div>
				<?if(in_array("NAME", $arParams["COLUMNS_LIST"])):?>
					<div class="cart-item-name"><?=GetMessage("SALE_NAME")?></div>
				<?endif;?>
				<?if(in_array("PRICE", $arParams["COLUMNS_LIST"])):?>
					<div class="cart-item-price"><?=GetMessage("SALE_PRICE")?></div>
				<?endif;?>
				<?if(in_array("QUANTITY", $arParams["COLUMNS_LIST"])):?>
					<div class="cart-item-quantity"><?=GetMessage("SALE_QUANTITY")?></div>
				<?endif;?>
				<div class="cart-item-summa"><?=GetMessage("SALE_SUMMA")?></div>
				<?if(in_array("DELAY", $arParams["COLUMNS_LIST"])):?>
					<div class="cart-item-actions"><?=GetMessage("SALE_ACTIONS")?></div>
				<?endif;?>
			</div>
			<div class="tbody">
				<?$i=0;
				foreach($arResult["ITEMS"]["AnDelCanBuy"] as $arBasketItems) {?>					
					<div class="tr">
						<div class="tr_into">							
							<div class="cart-item-image">
								<img src="<?=$arBasketItems['DETAIL_PICTURE']['src']?>" width="<?=$arBasketItems['DETAIL_PICTURE']['width']?>" height="<?=$arBasketItems['DETAIL_PICTURE']['height']?>" />
							</div>							
							<?if(in_array("NAME", $arParams["COLUMNS_LIST"])):?>
								<div class="cart-item-name">
									<?if(strlen($arBasketItems["DETAIL_PAGE_URL"])>0):?>
										<a href="<?=$arBasketItems["DETAIL_PAGE_URL"]?>">
									<?endif;?>
										<?=$arBasketItems["NAME"] ?>
									<?if(strlen($arBasketItems["DETAIL_PAGE_URL"])>0):?>
										</a>
									<?endif;?>
									<?if(in_array("PROPS", $arParams["COLUMNS_LIST"])):
										foreach($arBasketItems["PROPS"] as $val) {
											echo "<br />".$val["NAME"].": ".$val["VALUE"];
										}
									endif;?>
								</div>
							<?endif;?>							
							<?if(in_array("PRICE", $arParams["COLUMNS_LIST"])):?>
								<div class="cart-item-price">
									<?if($arBasketItems["DISCOUNT_PRICE"] > 0):?>
										<div class="old-price">
											<?=$arBasketItems["FULL_PRICE_FORMATED"]?>
										</div>
										<div class="price">
											<?=$arBasketItems["PRICE_FORMATED"]?>
										</div>
									<?else:?>
										<div class="price">
											<?=$arBasketItems["PRICE_FORMATED"]?>
										</div>
									<?endif?>
									<?if(!empty($arBasketItems["MEASURE_TEXT"])):?>
										<div class="unit">
											<?=GetMessage('UNIT')." ".$arBasketItems["MEASURE_TEXT"]?>
										</div>
									<?endif;?>
								</div>
							<?endif;?>							
							<?if(in_array("QUANTITY", $arParams["COLUMNS_LIST"])):?>
								<div class="cart-item-quantity">
									<div style="float:right;" class="buy_more">
										<a href="javascript:void(0)" class="minus" onclick="if (BX('QUANTITY_<?=$arBasketItems["ID"]?>').value > <?=$arBasketItems["MEASURE_RATIO"]?>) BX('QUANTITY_<?=$arBasketItems["ID"]?>').value = parseFloat(BX('QUANTITY_<?=$arBasketItems["ID"]?>').value)-<?=$arBasketItems["MEASURE_RATIO"]?>;"><span>-</span></a>
										<input type="text" name="QUANTITY_<?=$arBasketItems["ID"]?>" id="QUANTITY_<?=$arBasketItems["ID"]?>" class="quantity" value="<?=$arBasketItems["QUANTITY"]?>"/>
										<a href="javascript:void(0)" class="plus" onclick="BX('QUANTITY_<?=$arBasketItems["ID"]?>').value = parseFloat(BX('QUANTITY_<?=$arBasketItems["ID"]?>').value)+<?=$arBasketItems["MEASURE_RATIO"]?>;"><span>+</span></a>
									</div>
								</div>
							<?endif;?>							
							<div class="cart-item-summa">
								<?=$arBasketItems["SUM"]?>
							</div>							
							<?if(in_array("DELAY", $arParams["COLUMNS_LIST"])):?>
								<div class="cart-item-actions">								
									<div class="delay">
										<a class="setaside" href="<?=str_replace("#ID#", $arBasketItems["ID"], $arUrlTempl["delay"])?>" title="<?=GetMessage("SALE_OTLOG")?>"><i class="fa fa-heart-o"></i></a>
									</div>
									<?if(in_array("DELETE", $arParams["COLUMNS_LIST"])):?>
										<div class="delete">
											<a class="deleteitem" href="<?=str_replace("#ID#", $arBasketItems["ID"], $arUrlTempl["delete"])?>" onclick="//return DeleteFromCart(this);" title="<?=GetMessage("SALE_DELETE_PRD")?>"><i class="fa fa-trash-o"></i></a>
										</div>
									<?endif;?>
								</div>
							<?endif;?>
						</div>
					</div>
					<?$i++;
				}?>
				<div class="myorders_itog">
					<div class="cart-itogo"><?=GetMessage("SALE_ITOGO")?>:</div>
					<div class="cart-allsum"><?=$arResult["allSum_FORMATED"]?></div>
				</div>
			</div>
		</div>		
		<table class="w100p" border="0" align="right">
			<tr>
				<?if($arParams["HIDE_COUPON"] != "Y"):?>	
					<td class="cart-coupon" align="left">
						<input name="COUPON" class="input_text_style" <?if(empty($arResult["COUPON"])):?>onclick="if(this.value=='<?=GetMessage("SALE_COUPON_VAL")?>')this.value='';" onblur="if(this.value=='')this.value='<?=GetMessage("SALE_COUPON_VAL")?>';"<?endif;?> value="<?if(!empty($arResult["COUPON"])):?><?=$arResult["COUPON"]?><?else:?><?=GetMessage("SALE_COUPON_VAL")?><?endif;?>" />
					</td>
				<?endif;?>
				<td class="tal" align="right">
					<button type="submit" name="BasketRefresh" class="btn_buy ppp bt2" value="<?=GetMessage('SALE_UPDATE')?>"><?=GetMessage("SALE_UPDATE")?></button>
				</td>
				<td class="tac" align="right">
					<button name="boc_cart_anch" id="boc_cart_anch" class="btn_buy boc_cart_anch" value="<?=GetMessage('SALE_BOC')?>"><?=GetMessage('SALE_BOC')?></button>
				</td>
				<td class="tar" align="right">
					<button type="submit" name="BasketOrder" id="basketOrderButton2" class="btn_buy popdef bt3" value="<?=GetMessage('SALE_ORDER')?>"><?=GetMessage("SALE_ORDER")?></button>
				</td>
			</tr>
		</table>
	<?else:?>	
		<div class="cart-notetext"><?=GetMessage("SALE_NO_ACTIVE_PRD");?></div>
		<a href="<?=SITE_DIR?>" class="bt3"><?=GetMessage("SALE_NO_ACTIVE_PRD_START")?></a>
	<?endif;?>
</div>