<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($_REQUEST["filter_history"] == "Y")
	$page = "all";
else
	$page = "active";?>

<div class="order-list">
	<div class="sort tabfilter order">
		<div class="sorttext"><?=GetMessage("STPOL_F_NAME")?></div>
		<a class="sortbutton active<?if($page == "active") echo " current"?>" href="<?if($page != "active") echo $arResult["CURRENT_PAGE"]."?filter_history=N"; else echo "javascript:void(0)";?>"><?=GetMessage("STPOL_CUR_ORDERS")?></a>

		<a class="sortbutton all<?if($page == "all") echo " current"?>" href="<?if($page != "all") echo $arResult["CURRENT_PAGE"]."?filter_history=Y"; else echo "javascript:void(0)";?>"><?=GetMessage("STPOL_ORDERS_HISTORY")?></a>
	</div>

	<?if(!empty($arResult["ORDERS"])):?>
		<div class="cart-items">
			<div class="equipment-order list">
				<div class="thead">					
					<div class="cart-item-number-date"><?=GetMessage("STPOL_ORDER_NUMBER_DATE")?></div>
					<div class="cart-item-status"><?=GetMessage("STPOL_ORDER_STATUS")?></div>
					<div class="cart-item-payment"><?=GetMessage("STPOL_ORDER_PAYMENT")?></div>
					<div class="cart-item-payed"><?=GetMessage("STPOL_ORDER_PAYED")?></div>
					<div class="cart-item-summa"><?=GetMessage("STPOL_ORDER_SUMMA")?></div>
				</div>
				<div class="tbody">
					<?foreach($arResult["ORDERS"] as $key => $val):
						$accountHashNumber = md5($val["ORDER"]["ACCOUNT_NUMBER"]);?>
						<div class="tr">
							<div class="tr_into">
								<div class="tr_into_in">
									<div class="cart-item-plus-minus">
										<script type="text/javascript">
											$(document).ready(function() {
												$("#plus-minus-<?=$accountHashNumber?>").click(function() {
													var clickitem = $(this);
													if(clickitem.hasClass("plus")) {
														clickitem.removeClass().addClass("minus active");							
													} else {
														clickitem.removeClass().addClass("plus");									
													}
													$(".cart-items.basket.<?=$accountHashNumber?>, .order-recipient.<?=$accountHashNumber?>, .order-item-actions.<?=$accountHashNumber?>").slideToggle();
												});
											});
										</script>
										<a href="javascript:void(0)" id="plus-minus-<?=$accountHashNumber?>" class="plus"><i class="fa fa-plus-circle"></i><i class="fa fa-minus-circle"></i></a>
									</div>									
									<div class="cart-item-number-date">
										<span class="cart-item-number"><?=$val["ORDER"]["ACCOUNT_NUMBER"]?></span>
										<?=date("d.m.Y H:i", MakeTimeStamp($val["ORDER"]["DATE_INSERT"], "DD.MM.YYYY HH:MI:SS"));?>
									</div>
									<div class="cart-item-status">
										<?if($val["ORDER"]["CANCELED"] == "Y"):?>
											<span class="item-status-d">
												<?=GetMessage("STPOL_ORDER_DELETE");?>
											</span>
										<?else:?>
											<span class="item-status-<?=toLower($val["ORDER"]["STATUS_ID"])?>">
												<?=$arResult["INFO"]["STATUS"][$val["ORDER"]["STATUS_ID"]]["NAME"];?>
											</span>
										<?endif;?>
									</div>
									<div class="cart-item-payment">
										<?if(IntVal($val["ORDER"]["PAY_SYSTEM_ID"]) > 0):
											echo $arResult["INFO"]["PAY_SYSTEM"][$val["ORDER"]["PAY_SYSTEM_ID"]]["NAME"];			
											if(isset($val["ORDER"]["PSA_ACTION_FILE"]) && !empty($val["ORDER"]["PSA_ACTION_FILE"])):?>
												<br />
												<a href="<?=$val["ORDER"]["PSA_ACTION_FILE"]?>" target="_blank"><?=GetMessage("STPOL_REPEAT_PAY")?></a>
											<?endif;
										else:
											echo GetMessage("STPOL_NONE");
										endif;?>
									</div>
									<div class="cart-item-payed">
										<?if($val["ORDER"]["PAYED"] == "Y"):
											echo "<span class='item-payed-yes'>".GetMessage("STPOL_YES")."</span>";
										else:
											echo GetMessage("STPOL_NO");
										endif;?>
									</div>
									<div class="cart-item-summa">
										<?=$val["ORDER"]["FORMATED_PRICE"];?>
									</div>
								</div>
								
								<div class="cart-items basket <?=$accountHashNumber?>" style="display:none;">
									<div class="equipment-order basket">
										<div class="thead">
											<div class="cart-item-name"><?=GetMessage("STPOL_ORDER_NAME")?></div>
											<div class="cart-item-price"><?=GetMessage("STPOL_ORDER_PRICE")?></div>
											<div class="cart-item-quantity"><?=GetMessage("STPOL_ORDER_QUANTITY")?></div>
											<div class="cart-item-summa"><?=GetMessage("STPOL_ORDER_SUMMA")?></div>
										</div>
										<div class="tbody">
											<?$i = 1;
											foreach($val["BASKET_ITEMS"] as $arBasketItems):?>
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
																	<?foreach($arBasketItems["PROPS"] as $props) {
																		echo "<span style='display:block;'>".$props["NAME"].": ".$props["VALUE"]."</span>";
																	}?>
																	<div class="clr"></div>
																</div>
															<?}?>
														</div>
														<div class="cart-item-price">
															<div class="price">
																<?=CurrencyFormat($arBasketItems["PRICE"], $arBasketItems["CURRENCY"]);?>
															</div>
														</div>
														<div class="cart-item-quantity">
															<?=$arBasketItems["QUANTITY"];
															if(!empty($arBasketItems["MEASURE_TEXT"])):
																echo " ".$arBasketItems["MEASURE_TEXT"];
															endif;?>
														</div>
														<div class="cart-item-summa">
															<?=CurrencyFormat(($arBasketItems["PRICE"] * $arBasketItems["QUANTITY"]), $arBasketItems["CURRENCY"]);?>
														</div>
													</div>
												</div>
												<?$i++;
											endforeach;
											if(IntVal($val["ORDER"]["DELIVERY_ID"]) > 0):?>
												<div class="tr">
													<div class="tr_into">
														<div class="cart-itogo">
															<?=$arResult["INFO"]["DELIVERY"][$val["ORDER"]["DELIVERY_ID"]]["NAME"]?>
														</div>
														<div class="cart-allsum">
															<?if($val["ORDER"]["PRICE_DELIVERY"] > 0):
																echo CurrencyFormat($val["ORDER"]["PRICE_DELIVERY"], $val["ORDER"]["CURRENCY"]);
															endif;?>
														</div>
													</div>
												</div>
											<?endif;?>
										</div>
										<div class="myorders_itog">
											<div class="cart-itogo"><?=GetMessage("STPOL_ORDER_SUM_IT")?></div>
											<div class="cart-allsum"><?=$val["ORDER"]["FORMATED_PRICE"];?></div>
										</div>
									</div>
								</div>

								<table class="order-recipient <?=$accountHashNumber?>" style="display:none;">
									<?if(!empty($val["ORDER"]["ORDER_PROPS"])) {
										foreach($val["ORDER"]["ORDER_PROPS"] as $orderProps) {?>
											<tr>
												<td class="field-name"><?=$orderProps["NAME"]?>:</td>
												<td class="field-value">
													<?if($orderProps["TYPE"] == "CHECKBOX") {
														if($orderProps["VALUE"] == "Y")
															echo GetMessage("SALE_YES");
														else
															echo GetMessage("SALE_NO");
													} else {
														echo $orderProps["VALUE"];
													}?>
												</td>
											</tr>
										<?}
									}?>
									<?if(strlen($val["ORDER"]["USER_DESCRIPTION"])>0):?>
										<tr>
											<td class="field-name"><?=GetMessage("STPOL_ORDER_USER_COMMENT")?></td>
											<td class="field-value"><?=$val["ORDER"]["USER_DESCRIPTION"]?></td>
										</tr>
									<?endif;?>
								</table>

								<div class="order-item-actions <?=$accountHashNumber?>" style="display:none;">
									<a class="btn_buy apuo order_repeat" href="<?=$val['ORDER']['URL_TO_COPY']?>" title="<?=GetMessage('STPOL_REPEAT_ORDER')?>"><i class="fa fa-repeat"></i><span><?=GetMessage("STPOL_REPEAT_ORDER")?></span></a>
									<?if($val["ORDER"]["CAN_CANCEL"]=="Y"):?>
										<a class="btn_buy apuo order_delete" href="<?=$val['ORDER']['URL_TO_CANCEL']?>" title="<?=GetMessage('STPOL_CANCEL_ORDER')?>"><i class="fa fa-times"></i><span><?=GetMessage("STPOL_CANCEL_ORDER")?></span></a>
									<?endif;?>
									<a class="btn_buy apuo order_detail" href="<?=$val['ORDER']['URL_TO_DETAIL']?>" title="<?=GetMessage('STPOL_DETAIL_ORDER')?>"><i class="fa fa-chevron-right"></i><span><?=GetMessage("STPOL_DETAIL_ORDER")?></span></a>
									<div class="clr"></div>
								</div>
							</div>
						</div>
					<?endforeach;?>
				</div>
			</div>
		</div>		
	<?else:		
		echo ShowNote(GetMessage("STPOL_NO_ORDERS_NEW"));	
	endif;?>
</div>

<?if(strlen($arResult["NAV_STRING"]) > 0):?>
	<div class="navigation"><?=$arResult["NAV_STRING"]?></p>
<?endif?>