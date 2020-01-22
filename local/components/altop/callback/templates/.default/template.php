<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
$sComponentFolder = $this->__component->__path;
$sTemplateFolder  = $this->GetFolder();

$frame = $this->createFrame("callback")->begin("");?>	
	<form action="<?=$APPLICATION->GetCurPage()?>" id="new_callback_form" class="new_callback_form">		
		<span id="echo_callback_form"></span>
		<div class="row">
			<div class="span1">
				<?=GetMessage("MFT_NAME")?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("NAME", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
			</div>
			<div class="span2">
				<input type="text" class="input-text" id="callback_name" name="callback_name" value="<?=$arResult['NAME']?>" />
			</div>
			<div class="clear"></div>
		</div>
		<div class="row">
			<div class="span1">
				<?=GetMessage("MFT_TEL")?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("TEL", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
			</div>
			<div class="span2">
				<input type="text" class="input-text" id="callback_tel" name="callback_tel" value="" />
			</div>
			<div class="clear"></div>
		</div>
		<div class="row">
			<div class="span1">
				<?=GetMessage("MFT_TIME")?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("TIME", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
			</div>
			<div class="span2">
				<input type="text" class="input-text" id="callback_time" name="callback_time" value="" />
			</div>
			<div class="clear"></div>
		</div>
		<div class="row">
			<div class="span1">
				<?=GetMessage("MFT_MESSAGE")?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("MESSAGE", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
			</div>
			<div class="span2">
				<textarea id="callback_message" name="callback_message" rows="3" cols="30"></textarea>
			</div>
			<div class="clear"></div>
		</div>
		<?if(!$USER->IsAuthorized()):?>			
			<div class="row">
				<div class="span1">
					<?=GetMessage('MFT_CAPTCHA');?><span class="mf-req">*</span>
				</div>
				<div class="span2">					
					<input type="text" id="callback_captcha_word" name="callback_captcha_word" maxlength="50" value=""/>			
					<img id="callback_cImg" src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="127" height="30" alt="CAPTCHA" />					
					<input type="hidden" id="callback_captcha_sid" name="callback_captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />					
				</div>
				<div class="clear"></div>
			</div>			
		<?endif;?>		
		<input type="hidden" id="callback_method" name="callback_method" value="callback"/>
		<div class="submit">
			<button onclick="button_callback('<?=$sComponentFolder?>', '<?=$sTemplateFolder?>', '<?=$arResult["EMAIL_TO"]?>', '<?=$arResult["REQUIRED"]?>');" type="button" name="send_button" class="btn_buy popdef"><?=GetMessage('MFT_ORDER');?></button>
		</div>
	</form>
<?$frame->end();?>