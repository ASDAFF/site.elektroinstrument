<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="content-form forgot-form">
	<div class="fields">
		<?ShowMessage($arParams["~AUTH_RESULT"]);?>
		<form name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
			<?if(strlen($arResult["BACKURL"]) > 0) {?>
				<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
			<?}?>
			<input type="hidden" name="AUTH_FORM" value="Y">
			<input type="hidden" name="TYPE" value="SEND_PWD">
			<div class="field">
				<?=GetMessage("AUTH_FORGOT_PASSWORD_1")?>
			</div>
			<div class="field">
				<label class="field-title"><?=GetMessage("AUTH_LOGIN")?></label>
				<div class="form-input">
					<input type="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult["LAST_LOGIN"]?>" />
				</div>
			</div>
			<div class="field">
				<label class="field-title">E-Mail</label>
				<div class="form-input">
					<input type="text" name="USER_EMAIL" maxlength="255" />
				</div>
			</div>
			<div class="field field-button">
				<button type="submit" name="send_account_info" class="btn_buy popdef" value="<?=GetMessage("AUTH_SEND")?>"><?=GetMessage("AUTH_SEND")?></button>	
			</div>
			<div class="field">
				<a class="btn_buy boc_anch" href="<?=$arResult["AUTH_AUTH_URL"]?>"><i class="fa fa-user"></i><?=GetMessage("AUTH_AUTH")?></a>
			</div> 
		</form>
		<script type="text/javascript">
			document.bform.USER_LOGIN.focus();
		</script>
	</div>
</div>