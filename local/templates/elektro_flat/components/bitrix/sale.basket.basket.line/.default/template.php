<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="cart_line">
	<?$frame = $this->createFrame("cart_line")->begin();?>		
		<a href="<?=$arParams['PATH_TO_BASKET']?>" class="cart" title="<?=GetMessage('TSBS')?>" rel="nofollow">
			<i class="fa fa-shopping-cart"></i>
			<span class="text"><?=GetMessage("TSBS")?></span>
			<span class="qnt_cont">
				<span class="qnt"><?=$arResult["QUANTITY"]?></span>
			</span>	
		</a>				
		<span class="sum_cont">
			<span class="sum">
				<?=$arResult["SUM"]?>
				<span class="curr"><?=$arResult["CURRENCY"]?></span>
			</span>
		</span>		
		<div class="oformit_cont">
			<?if(strpos($APPLICATION->GetCurDir(), $arParams["PATH_TO_BASKET"]) === false && strpos($APPLICATION->GetCurDir(), $arParams["PATH_TO_ORDER"]) === false && IntVal($arResult["NUM_PRODUCTS"]) > 0):?>
				<form action="<?=$arParams['PATH_TO_BASKET']?>" method="post">
					<button name="oformit" class="btn_buy popdef oformit" value="<?=GetMessage('BASKET_LINE_CHECKOUT')?>"><?=GetMessage("BASKET_LINE_CHECKOUT")?></button>
				</form>
			<?else:?>
				<div class="btn_buy oformit dsbl"><?=GetMessage("BASKET_LINE_CHECKOUT")?></div>
			<?endif;?>
		</div>
	<?$frame->end();?>
</div>