<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
$sComponentFolder = $this->__component->__path;
$sTemplateFolder  = $this->GetFolder();?>

<script type="text/javascript">
	$(function() {
		$("#order_anch_<?=$arParams['ELEMENT_ID']?>").click(function(e){
			e.preventDefault();
			$(window).resize(function () {
				modalHeight = ($(window).height() - $("#order_<?=$arParams['ELEMENT_ID']?>").height()) / 2;
				$("#order_<?=$arParams['ELEMENT_ID']?>").css({
					'top': modalHeight + 'px'
				});
			});
			$(window).resize();
			$("#order_body_<?=$arParams['ELEMENT_ID']?>").css({'display':'block'});
			$("#order_<?=$arParams['ELEMENT_ID']?>").css({'display':'block'});
		});
		$("#order_close_<?=$arParams['ELEMENT_ID']?>, #order_body_<?=$arParams['ELEMENT_ID']?>").click(function(e){
			e.preventDefault();
			$("#order_body_<?=$arParams['ELEMENT_ID']?>").css({'display':'none'});
			$("#order_<?=$arParams['ELEMENT_ID']?>").css({'display':'none'});
		});
	});
</script>

<div class="pop-up-bg order_body" id="order_body_<?=$arParams['ELEMENT_ID']?>"></div>
<div class="pop-up order" id="order_<?=$arParams['ELEMENT_ID']?>">
	<a href="javascript:void(0)" class="pop-up-close order_close" id="order_close_<?=$arParams['ELEMENT_ID']?>"><i class="fa fa-times"></i></a>
	<div class="h1"><?=GetMessage("MFT_ORDER_TITLE");?></div>
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
		<form action="<?=$APPLICATION->GetCurPage()?>" class="new_order_form">
			<span id="echo_order_form_<?=$arParams['ELEMENT_ID']?>"></span>
			<div class="row">
				<div class="span1">
					<?=GetMessage("MFT_ORDER_NAME")?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("NAME", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
				</div>
				<div class="span2">
					<input type="text" class="input-text" id="order_name_<?=$arParams['ELEMENT_ID']?>" name="order_name" value="<?=$arResult['NAME']?>" />
				</div>
				<div class="clear"></div>
			</div>			
			<div class="row">
				<div class="span1">
					<?=GetMessage("MFT_ORDER_TEL")?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("TEL", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
				</div>
				<div class="span2">
					<input type="text" class="input-text" id="order_tel_<?=$arParams['ELEMENT_ID']?>" name="order_tel" value="" />
				</div>
				<div class="clear"></div>
			</div>
			<div class="row">
				<div class="span1">
					<?=GetMessage("MFT_ORDER_TIME")?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("TIME", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
				</div>
				<div class="span2">
					<input type="text" class="input-text" id="order_time_<?=$arParams['ELEMENT_ID']?>" name="order_time" value="" />
				</div>
				<div class="clear"></div>
			</div>
			<div class="row">
				<div class="span1">
					<?=GetMessage("MFT_ORDER_MESSAGE")?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("MESSAGE", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
				</div>
				<div class="span2">
					<textarea id="order_message_<?=$arParams['ELEMENT_ID']?>" name="order_message" rows="3" cols="30"><?=$arResult['MESSAGE']?></textarea>
				</div>
				<div class="clear"></div>
			</div>
			<?if(!$USER->IsAuthorized()):?>
				<div class="row">
					<div class="span1">
						<?=GetMessage('MFT_ORDER_CAPTCHA');?><span class="mf-req">*</span>
					</div>
					<div class="span2">
						<input type="text" id="order_captcha_word_<?=$arParams['ELEMENT_ID']?>" name="order_captcha_word" maxlength="50" value=""/>
						<img id="order_cImg_<?=$arParams['ELEMENT_ID']?>" src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="127" height="30" alt="CAPTCHA" />
						<input type="hidden" id="order_captcha_sid_<?=$arParams['ELEMENT_ID']?>" name="order_captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
					</div>
					<div class="clear"></div>
				</div>
			<?endif;?>
			<input type="hidden" id="order_method_<?=$arParams['ELEMENT_ID']?>" name="order_method" value="order"/>
			<div class="submit">
				<button onclick="button_order('<?=$sComponentFolder?>', '<?=$sTemplateFolder?>', '<?=$arResult["EMAIL_TO"]?>', '<?=$arResult["REQUIRED"]?>', '<?=$arParams['ELEMENT_ID']?>');" type="button" name="send_button" class="btn_buy popdef"><?=GetMessage('MFT_ORDER_BUTTON');?></button>
			</div>
		</form>
	</div>
</div>