<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(strlen($arResult["ERROR_MESSAGE"])<=0):?>	
	<div class="cart-items" style="margin:0px 0px 10px 0px;">
		<div class="equipment-order detail">
			<div class="thead">				
				<div class="cart-item-number-date"><?=GetMessage("SPOD_ORDER_NUMBER_DATE")?></div>
				<div class="cart-item-status"><?=GetMessage("SPOD_ORDER_STATUS")?></div>
				<div class="cart-item-payment"><?=GetMessage("SPOD_ORDER_PAYMENT")?></div>
				<div class="cart-item-payed"><?=GetMessage("SPOD_ORDER_PAYED")?></div>
				<div class="cart-item-summa"><?=GetMessage("SPOD_ORDER_SUMMA")?></div>
			</div>
			<div class="tbody">
				<div class="tr">
					<div class="tr_into">						
						<div class="cart-item-number-date">
							<span class="cart-item-number"><?=$arResult["ACCOUNT_NUMBER"]?></span>
							<?=date("d.m.Y H:i", MakeTimeStamp($arResult["DATE_INSERT"], "DD.MM.YYYY HH:MI:SS"));?>
						</div>
						<div class="cart-item-status">
							<?if($arResult["CANCELED"] == "N"):?>
								<span class="item-status-<?=toLower($arResult["STATUS"]["ID"])?>">
									<?=$arResult["STATUS"]["NAME"];?>
								</span>
							<?elseif($arResult["CANCELED"] == "Y"):?>
								<span class="item-status-d">
									<?=GetMessage("SPOD_ORDER_DELETE");?>
								</span>
							<?endif;?>
						</div>
						<div class="cart-item-payment">
							<?if(IntVal($arResult["PAY_SYSTEM_ID"]) > 0):
								echo $arResult["PAY_SYSTEM"]["NAME"];								
								if($arResult["CAN_REPAY"]=="Y"):
									if($arResult["PAY_SYSTEM"]["PSA_NEW_WINDOW"] == "Y"):?>
										<br />
										<a href="<?=$arResult["PAY_SYSTEM"]["PSA_ACTION_FILE"]?>" target="_blank"><?=GetMessage("SALE_REPEAT_PAY")?></a>
									<?endif;
								endif;
							else:
								echo GetMessage("SPOD_NONE");
							endif;?>
						</div>
						<div class="cart-item-payed">
							<?if($arResult["PAYED"] == "Y"):
								echo "<span class='item-payed-yes'>".GetMessage("SALE_YES")."</span>";
							else:
								echo GetMessage("SALE_NO");
							endif;?>
						</div>
						<div class="cart-item-summa">
							<?=$arResult["PRICE_FORMATED"];?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="cart-items basket">
		<div class="equipment-order basket">
			<div class="thead">
				<div class="cart-item-name"><?=GetMessage("SPOD_ORDER_NAME")?></div>
				<div class="cart-item-price"><?=GetMessage("SPOD_ORDER_PRICE")?></div>
				<div class="cart-item-quantity"><?=GetMessage("SPOD_ORDER_QUANTITY")?></div>
				<div class="cart-item-summa"><?=GetMessage("SPOD_ORDER_SUMMA")?></div>
			</div>
			<div class="tbody">
				<?$i = 1;
				foreach($arResult["BASKET"] as $arBasketItems):?>
					<div class="tr">
						<div class="tr_into">
							<div class="cart-item-number"><?=$i?></div>
							<div class="cart-item-image">
								<img src="<?=$arBasketItems['DETAIL_PICTURE']['src']?>" width="<?=$arBasketItems['DETAIL_PICTURE']['width']?>" height="<?=$arBasketItems['DETAIL_PICTURE']['height']?>" />
							</div>
							<div class="cart-item-name">
								<?if(strlen($arBasketItems["DETAIL_PAGE_URL"])>0):?>
									<a href="<?=$arBasketItems["DETAIL_PAGE_URL"]?>">
								<?endif;
									echo $arBasketItems["NAME"];
								if(strlen($arBasketItems["DETAIL_PAGE_URL"])>0):?>
									</a>
								<?endif;
								if(!empty($arBasketItems["PROPS"])) {?>
									<div class="item-props">
										<?foreach($arBasketItems["PROPS"] as $val) {
											echo "<span style='display:block;'>".$val["NAME"].": ".$val["VALUE"]."</span>";
										}?>
										<div class="clr"></div>
									</div>
								<?}?>
							</div>
							<div class="cart-item-price">
								<div class="price">
									<?=$arBasketItems["PRICE_FORMATED"]?>
								</div>
							</div>
							<div class="cart-item-quantity">
								<?=$arBasketItems["QUANTITY"];
								if(!empty($arBasketItems["MEASURE_TEXT"])):
									echo " ".$arBasketItems["MEASURE_TEXT"];
								endif;?>
							</div>
							<div class="cart-item-summa">
								<?$price = CCurrencyLang::GetCurrencyFormat($arBasketItems["CURRENCY"], "ru");
								if(empty($price["THOUSANDS_SEP"])):
									$price["THOUSANDS_SEP"] = " ";
								endif;
								$currency = str_replace("#", " ", $price["FORMAT_STRING"]);

								echo number_format(($arBasketItems["PRICE"] * $arBasketItems["QUANTITY"]), $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);
								echo $currency;?>
							</div>
						</div>
					</div>
					<?$i++;
				endforeach;
				if(IntVal($arResult["DELIVERY_ID"]) > 0):?>
					<div class="tr">
						<div class="tr_into">
							<div class="cart-itogo"><?=$arResult["DELIVERY"]["NAME"]?></div>
							<div class="cart-allsum"><?=$arResult["PRICE_DELIVERY_FORMATED"]?></div>
						</div>
					</div>
				<?endif;?>
			</div>
			<div class="myorders_itog">
				<div class="cart-itogo"><?=GetMessage("SPOD_ORDER_SUM_IT")?></div>
				<div class="cart-allsum"><?=$arResult["PRICE_FORMATED"]?></div>
			</div>
		</div>
	</div>
	
	<table class="order-recipient">
		<?if(!empty($arResult["ORDER_PROPS"])) {
			foreach($arResult["ORDER_PROPS"] as $val) {?>
				<tr>
					<td class="field-name"><?echo $val["NAME"] ?>:</td>
					<td class="field-value">
						<?if($val["TYPE"] == "CHECKBOX") {
							if($val["VALUE"] == "Y")
								echo GetMessage("SALE_YES");
							else
								echo GetMessage("SALE_NO");
						} else {
							echo $val["VALUE"];
						}?>
					</td>
				</tr>
			<?}
		}?>
		<?if(strlen($arResult["USER_DESCRIPTION"])>0):?>
			<tr>
				<td class="field-name"><?=GetMessage("P_ORDER_USER_COMMENT")?></td>
				<td class="field-value"><?=$arResult["USER_DESCRIPTION"]?></td>
			</tr>
		<?endif;?>
	</table>

	<div class="order-item-actions">
		<a class="btn_buy apuo order_repeat" href="<?=$arResult['URL_TO_LIST']?>?COPY_ORDER=Y&ID=<?=$arResult['ACCOUNT_NUMBER']?>" title="<?=GetMessage('SALE_REPEAT_ORDER')?>"><i class="fa fa-repeat"></i><span><?=GetMessage("SALE_REPEAT_ORDER")?></span></a>
		<?if($arResult["CAN_CANCEL"]=="Y"):?>
			<a class="btn_buy apuo order_delete" href="<?=$arResult["URL_TO_CANCEL"]?>" title="<?=GetMessage('SALE_CANCEL_ORDER')?>"><i class="fa fa-times"></i><span><?=GetMessage("SALE_CANCEL_ORDER")?></span></a>
		<?endif;?>
		<div class="clr"></div>
	</div>
<?else:	
	echo ShowError($arResult["ERROR_MESSAGE"]);
endif;?>