<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
$sComponentFolder = $this->__component->__path;?>

<script type="text/javascript">
	$(function() {
		$("#boc_cart_anch").click(function(e){
			e.preventDefault();
			$(window).resize(function () {
				modalHeight = ($(window).height() - $("#boc_cart").height()) / 2;
				$("#boc_cart").css({
					'top': modalHeight + 'px'
				});
			});
			$(window).resize();
			$("#boc_cart_body").css({'display':'block'});
			$("#boc_cart").css({'display':'block'});
		});
		$("#boc_cart_close, #boc_cart_body").click(function(e){
			e.preventDefault();
			$("#boc_cart_body").css({'display':'none'});
			$("#boc_cart").css({'display':'none'});
		});
	});
</script>

<div class="pop-up-bg boc_cart_body" id="boc_cart_body"></div>
<div class="pop-up boc_cart" id="boc_cart">	
	<a href="javascript:void(0)" class="pop-up-close boc_cart_close" id="boc_cart_close"><i class="fa fa-times"></i></a>
	<div class="h1"><?=GetMessage("MFT_BOC_TITLE");?></div>	
	<form action="<?=$APPLICATION->GetCurPage()?>" class="new_boc_cart_form">
		<span id="echo_boc_cart_form"></span>
		<div class="row">
			<div class="span1">
				<?=GetMessage("MFT_NAME")?><?if(empty($arParams["REQUIRED_ORDER_FIELDS"]) || in_array("NAME", $arParams["REQUIRED_ORDER_FIELDS"])):?><span class="mf-req">*</span><?endif?>
			</div>
			<div class="span2">
				<input type="text" class="input-text" id="boc_cart_name" name="boc_cart_name" value="<?=$arResult['NAME']?>" />
			</div>
			<div class="clr"></div>
		</div>
		<div class="row">
			<div class="span1">
				<?=GetMessage("MFT_TEL")?><?if(empty($arParams["REQUIRED_ORDER_FIELDS"]) || in_array("TEL", $arParams["REQUIRED_ORDER_FIELDS"])):?><span class="mf-req">*</span><?endif?>
			</div>
			<div class="span2">
				<input type="text" class="input-text" id="boc_cart_tel" name="boc_cart_tel" value="" />
			</div>
			<div class="clr"></div>
		</div>
		<div class="row">
			<div class="span1">
				<?=GetMessage("MFT_EMAIL")?><?if(empty($arParams["REQUIRED_ORDER_FIELDS"]) || in_array("EMAIL", $arParams["REQUIRED_ORDER_FIELDS"])):?><span class="mf-req">*</span><?endif?>
			</div>
			<div class="span2">
				<input type="text" class="input-text" id="boc_cart_email" name="boc_cart_email" value="<?=$arResult['EMAIL']?>" />
			</div>
			<div class="clr"></div>
		</div>
		<div class="row">
			<div class="span1">
				<?=GetMessage("MFT_MESSAGE")?><?if(empty($arParams["REQUIRED_ORDER_FIELDS"]) || in_array("MESSAGE", $arParams["REQUIRED_ORDER_FIELDS"])):?><span class="mf-req">*</span><?endif?>
			</div>
			<div class="span2">
				<textarea id="boc_cart_message" name="boc_cart_message" rows="3" cols="30"></textarea>
			</div>
			<div class="clr"></div>
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
		<input type="hidden" id="boc_cart_method" name="boc_cart_method" value="boc"/>
		<input type="hidden" id="boc_cart_personTypeId" name="boc_cart_personTypeId" value="<?=$arParams['DEFAULT_PERSON_TYPE']?>" />
		<input type="hidden" id="boc_cart_propNameId" name="boc_cart_propNameId" value="<?=$arParams['DEFAULT_ORDER_PROP_NAME']?>" />
		<input type="hidden" id="boc_cart_propTelId" name="boc_cart_propTelId" value="<?=$arParams['DEFAULT_ORDER_PROP_TEL']?>" />
		<input type="hidden" id="boc_cart_propEmailId" name="boc_cart_propEmailId" value="<?=$arParams['DEFAULT_ORDER_PROP_EMAIL']?>" />
		<input type="hidden" id="boc_cart_deliveryId" name="boc_cart_deliveryId" value="<?=$arParams['DEFAULT_DELIVERY']?>" />
		<input type="hidden" id="boc_cart_paysystemId" name="boc_cart_paysystemId" value="<?=$arParams['DEFAULT_PAYMENT']?>" />
		<input type="hidden" id="boc_cart_buyMode" name="boc_cart_buyMode" value="<?=$arParams['BUY_MODE']?>" />		
		<input type="hidden" id="boc_cart_dubLetter" name="boc_cart_dubLetter" value="<?=$arParams['DUB']?>" />		
		<div class="submit">
			<button onclick="button_boc('<?=$sComponentFolder?>', '<?=$arResult["REQUIRED"]?>', '<?=$arParams['ELEMENT_ID']?>');" type="button" name="send_button" class="btn_buy popdef"><?=GetMessage("MFT_BOC_BUTTON");?></button>
			<small class="result detail hidden"><?=GetMessage("MFT_BOC_BUTTON");?></small>
		</div>
	</form>
</div>