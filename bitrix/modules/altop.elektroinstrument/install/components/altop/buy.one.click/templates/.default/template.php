<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
$sComponentFolder = $this->__component->__path;?>

<script type="text/javascript">
	$(function() {
		$("#boc_anch_<?=$arParams['ELEMENT_ID']?>").click(function(e){
			e.preventDefault();
			$(window).resize(function () {
				modalHeight = ($(window).height() - $("#boc_<?=$arParams['ELEMENT_ID']?>").height()) / 2;
				$("#boc_<?=$arParams['ELEMENT_ID']?>").css({
					'top': modalHeight + 'px'
				});
			});
			$(window).resize();
			$("#boc_body_<?=$arParams['ELEMENT_ID']?>").css({'display':'block'});
			$("#boc_<?=$arParams['ELEMENT_ID']?>").css({'display':'block'});

			var form = $("#add2basket_form_<?=$arParams['ELEMENT_ID']?>");
			quantityItem = form.find("#quantity_<?=$arParams['ELEMENT_ID']?>").attr('value');
			$("#boc_<?=$arParams['ELEMENT_ID']?> #boc_quantity_<?=$arParams['ELEMENT_ID']?>").attr('value', quantityItem);
		});
		$("#boc_close_<?=$arParams['ELEMENT_ID']?>, #boc_body_<?=$arParams['ELEMENT_ID']?>").click(function(e){
			e.preventDefault();
			$("#boc_body_<?=$arParams['ELEMENT_ID']?>").css({'display':'none'});
			$("#boc_<?=$arParams['ELEMENT_ID']?>").css({'display':'none'});
		});
	});
</script>

<div class="pop-up-bg boc_body" id="boc_body_<?=$arParams['ELEMENT_ID']?>"></div>
<div class="pop-up boc" id="boc_<?=$arParams['ELEMENT_ID']?>">
	<a href="javascript:void(0)" class="pop-up-close boc_close" id="boc_close_<?=$arParams['ELEMENT_ID']?>"><i class="fa fa-times"></i></a>
	<div class="h1"><?=GetMessage("MFT_BOC_TITLE");?></div>
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
		<form action="<?=$APPLICATION->GetCurPage()?>" class="new_boc_form">
			<span id="echo_boc_form_<?=$arParams['ELEMENT_ID']?>"></span>
			<div class="row">
				<div class="span1">
					<?=GetMessage("MFT_NAME")?><?if(empty($arParams["REQUIRED_ORDER_FIELDS"]) || in_array("NAME", $arParams["REQUIRED_ORDER_FIELDS"])):?><span class="mf-req">*</span><?endif?>
				</div>
				<div class="span2">
					<input type="text" class="input-text" id="boc_name_<?=$arParams['ELEMENT_ID']?>" name="boc_name" value="<?=$arResult['NAME']?>" />
				</div>
				<div class="clr"></div>
			</div>
			<div class="row">
				<div class="span1">
					<?=GetMessage("MFT_TEL")?><?if(empty($arParams["REQUIRED_ORDER_FIELDS"]) || in_array("TEL", $arParams["REQUIRED_ORDER_FIELDS"])):?><span class="mf-req">*</span><?endif?>
				</div>
				<div class="span2">
					<input type="text" class="input-text" id="boc_tel_<?=$arParams['ELEMENT_ID']?>" name="boc_tel" value="" />
				</div>
				<div class="clr"></div>
			</div>
			<div class="row">
				<div class="span1">
					<?=GetMessage("MFT_EMAIL")?><?if(empty($arParams["REQUIRED_ORDER_FIELDS"]) || in_array("EMAIL", $arParams["REQUIRED_ORDER_FIELDS"])):?><span class="mf-req">*</span><?endif?>
				</div>
				<div class="span2">
					<input type="text" class="input-text" id="boc_email_<?=$arParams['ELEMENT_ID']?>" name="boc_email" value="<?=$arResult['EMAIL']?>" />
				</div>
				<div class="clr"></div>
			</div>
			<div class="row">
				<div class="span1">
					<?=GetMessage("MFT_MESSAGE")?><?if(empty($arParams["REQUIRED_ORDER_FIELDS"]) || in_array("MESSAGE", $arParams["REQUIRED_ORDER_FIELDS"])):?><span class="mf-req">*</span><?endif?>
				</div>
				<div class="span2">
					<textarea id="boc_message_<?=$arParams['ELEMENT_ID']?>" name="boc_message" rows="3" cols="30"></textarea>
				</div>
				<div class="clear"></div>
			</div>
			<?if(!$USER->IsAuthorized()):?>
				<div class="row">
					<div class="span1">
						<?=GetMessage('MFT_CAPTCHA');?><span class="mf-req">*</span>
					</div>
					<div class="span2">
						<input type="text" id="boc_captcha_word_<?=$arParams['ELEMENT_ID']?>" name="boc_captcha_word" maxlength="50" value=""/>
						<img id="boc_cImg_<?=$arParams['ELEMENT_ID']?>" src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="127" height="30" alt="CAPTCHA" />
						<input type="hidden" id="boc_captcha_sid_<?=$arParams['ELEMENT_ID']?>" name="boc_captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
					</div>
					<div class="clr"></div>
				</div>
			<?endif;?>
			<input type="hidden" id="boc_method_<?=$arParams['ELEMENT_ID']?>" name="boc_method" value="boc"/>
			<input type="hidden" id="boc_element_props_<?=$arParams['ELEMENT_ID']?>" name="boc_element_props" value="<?=$arParams['ELEMENT_PROPS']?>"/>
			<input type="hidden" id="boc_element_select_props_<?=$arParams['ELEMENT_ID']?>" name="boc_element_select_props" value="" />
			<input type="hidden" id="boc_quantity_<?=$arParams['ELEMENT_ID']?>" name="boc_quantity" value="" />
			<input type="hidden" id="boc_personTypeId_<?=$arParams['ELEMENT_ID']?>" name="boc_personTypeId" value="<?=$arParams['DEFAULT_PERSON_TYPE']?>" />			
			<input type="hidden" id="boc_propNameId_<?=$arParams['ELEMENT_ID']?>" name="boc_propNameId" value="<?=$arParams['DEFAULT_ORDER_PROP_NAME']?>" />
			<input type="hidden" id="boc_propTelId_<?=$arParams['ELEMENT_ID']?>" name="boc_propTelId" value="<?=$arParams['DEFAULT_ORDER_PROP_TEL']?>" />
			<input type="hidden" id="boc_propEmailId_<?=$arParams['ELEMENT_ID']?>" name="boc_propEmailId" value="<?=$arParams['DEFAULT_ORDER_PROP_EMAIL']?>" />
			<input type="hidden" id="boc_deliveryId_<?=$arParams['ELEMENT_ID']?>" name="boc_deliveryId" value="<?=$arParams['DEFAULT_DELIVERY']?>" />
			<input type="hidden" id="boc_paysystemId_<?=$arParams['ELEMENT_ID']?>" name="boc_paysystemId" value="<?=$arParams['DEFAULT_PAYMENT']?>" />			
			<input type="hidden" id="boc_buyMode_<?=$arParams['ELEMENT_ID']?>" name="boc_buyMode" value="<?=$arParams['BUY_MODE']?>" />			
			<input type="hidden" id="boc_dubLetter_<?=$arParams['ELEMENT_ID']?>" name="boc_dubLetter" value="<?=$arParams['DUB']?>" />
			<div class="submit">
				<button onclick="button_boc('<?=$sComponentFolder?>', '<?=$arResult["REQUIRED"]?>', '<?=$arParams['ELEMENT_ID']?>');" type="button" name="send_button" class="btn_buy popdef"><?=GetMessage("MFT_BOC_BUTTON");?></button>
				<small class="result hidden"><?=GetMessage("MFT_BOC_BUTTON");?></small>
			</div>
		</form>
	</div>
</div>