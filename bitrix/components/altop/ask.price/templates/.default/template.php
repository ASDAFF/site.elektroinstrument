<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
$sComponentFolder = $this->__component->__path;
$sTemplateFolder  = $this->GetFolder();?>

<script type="text/javascript">
	$(function() {
		$("#ask_price_anch_<?=$arParams['ELEMENT_ID']?>").click(function(e){
			e.preventDefault();
			$(window).resize(function () {
				modalHeight = ($(window).height() - $("#ask_price_<?=$arParams['ELEMENT_ID']?>").height()) / 2;
				$("#ask_price_<?=$arParams['ELEMENT_ID']?>").css({
					'top': modalHeight + 'px'
				});
			});
			$(window).resize();
			$("#ask_price_body_<?=$arParams['ELEMENT_ID']?>").css({'display':'block'});
			$("#ask_price_<?=$arParams['ELEMENT_ID']?>").css({'display':'block'});
		});
		$("#ask_price_close_<?=$arParams['ELEMENT_ID']?>, #ask_price_body_<?=$arParams['ELEMENT_ID']?>").click(function(e){
			e.preventDefault();
			$("#ask_price_body_<?=$arParams['ELEMENT_ID']?>").css({'display':'none'});
			$("#ask_price_<?=$arParams['ELEMENT_ID']?>").css({'display':'none'});
		});
	});
</script>

<div class="pop-up-bg ask_price_body" id="ask_price_body_<?=$arParams['ELEMENT_ID']?>"></div>
<div class="pop-up ask_price" id="ask_price_<?=$arParams['ELEMENT_ID']?>">
	<a href="javascript:void(0)" class="pop-up-close ask_price_close" id="ask_price_close_<?=$arParams['ELEMENT_ID']?>"><i class="fa fa-times"></i></a>
	<div class="h1"><?=GetMessage("MFT_ASK_PRICE_TITLE");?></div>
	<div class="container">
		<div class="info">
			<div class="image">
				<?if(is_array($arResult["PREVIEW_IMG"])):?>
					<img src="<?=$arResult['PREVIEW_IMG']['SRC']?>" width="<?=$arResult['PREVIEW_IMG']['WIDTH']?>" height="<?=$arResult['PREVIEW_IMG']['HEIGHT']?>" alt="<?=$arResult['NAME']?>" />
				<?else:?>
					<img src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" width="150" height="150" alt="<?=$arResult['NAME']?>" />
				<?endif?>
			</div>
			<div class="name"><?=$arResult["ELEMENT_NAME"]?></div>
		</div>
		<form action="<?=$APPLICATION->GetCurPage()?>" class="new_ask_price_form">
			<span id="echo_ask_price_form_<?=$arParams['ELEMENT_ID']?>"></span>
			<div class="row">
				<div class="span1">
					<?=GetMessage("MFT_ASK_PRICE_NAME")?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("NAME", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
				</div>
				<div class="span2">
					<input type="text" class="input-text" id="ask_price_name_<?=$arParams['ELEMENT_ID']?>" name="ask_price_name" value="<?=$arResult['NAME']?>" />
				</div>
				<div class="clear"></div>
			</div>			
			<div class="row">
				<div class="span1">
					<?=GetMessage("MFT_ASK_PRICE_TEL")?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("TEL", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
				</div>
				<div class="span2">
					<input type="text" class="input-text" id="ask_price_tel_<?=$arParams['ELEMENT_ID']?>" name="ask_price_tel" value="" />
				</div>
				<div class="clear"></div>
			</div>
			<div class="row">
				<div class="span1">
					<?=GetMessage("MFT_ASK_PRICE_TIME")?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("TIME", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
				</div>
				<div class="span2">
					<input type="text" class="input-text" id="ask_price_time_<?=$arParams['ELEMENT_ID']?>" name="ask_price_time" value="" />
				</div>
				<div class="clear"></div>
			</div>
			<div class="row">
				<div class="span1">
					<?=GetMessage("MFT_ASK_PRICE_MESSAGE")?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("MESSAGE", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
				</div>
				<div class="span2">
					<textarea id="ask_price_message_<?=$arParams['ELEMENT_ID']?>" name="ask_price_message" rows="3" cols="30"><?=$arResult['MESSAGE']?></textarea>
				</div>
				<div class="clear"></div>
			</div>
			<?if(!$USER->IsAuthorized()):?>
				<div class="row">
					<div class="span1">
						<?=GetMessage('MFT_ASK_PRICE_CAPTCHA');?><span class="mf-req">*</span>
					</div>
					<div class="span2">
						<input type="text" id="ask_price_captcha_word_<?=$arParams['ELEMENT_ID']?>" name="ask_price_captcha_word" maxlength="50" value=""/>
						<img id="ask_price_cImg_<?=$arParams['ELEMENT_ID']?>" src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="127" height="30" alt="CAPTCHA" />
						<input type="hidden" id="ask_price_captcha_sid_<?=$arParams['ELEMENT_ID']?>" name="ask_price_captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
					</div>
					<div class="clear"></div>
				</div>
			<?endif;?>
			<input type="hidden" id="ask_price_method_<?=$arParams['ELEMENT_ID']?>" name="ask_price_method" value="ask_price"/>
			<div class="submit">
				<button onclick="button_ask_price('<?=$sComponentFolder?>', '<?=$sTemplateFolder?>', '<?=$arResult["EMAIL_TO"]?>', '<?=$arResult["REQUIRED"]?>', '<?=$arParams['ELEMENT_ID']?>');" type="button" name="send_button" class="btn_buy popdef"><?=GetMessage('MFT_ASK_PRICE_BUTTON');?></button>
			</div>
		</form>
	</div>
</div>