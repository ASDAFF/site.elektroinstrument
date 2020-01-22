<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);?>

<div class="subscribe_bottom">
	<div class="text">
		<div class="h3"><?=GetMessage("SUBSCRIBE");?></div>
		<p><?=GetMessage("SUBSCRIBE_TEXT");?></p>
	</div>
	<form action="<?=$arResult["FORM_ACTION"]?>">
		<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
			<input type="hidden" name="sf_RUB_ID[]" value="<?=$itemValue["ID"]?>" />
		<?endforeach;?>
		<input type="text" name="sf_EMAIL" maxlength="50" value="<?=GetMessage("SUBSCRIBE_BOTTOM_DEFAULT_VALUE")?>" onfocus="this.value=''" onblur="if (this.value==''){this.value='<?=GetMessage("SUBSCRIBE_BOTTOM_DEFAULT_VALUE")?>'}" class="text" />
		<button type="submit" name="submit" class="btn_buy ppp" value="<?=GetMessage('SUBSCRIBE_BOTTOM_SUBMIT')?>"><?=GetMessage('SUBSCRIBE_BOTTOM_SUBMIT')?></button>
	</form>
</div>