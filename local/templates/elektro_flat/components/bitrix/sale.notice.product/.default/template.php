<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$this->setFrameMode(true);?>

<?if($arResult["STATUS"] == "Y"):?>
	<span class="url_notify" id="url_notify_<?=$arParams['NOTIFY_ID']?>">
		<span class="alertMsg good">
			<i class="fa fa-check"></i>
			<span class="text"><?=GetMessage("MFT_NOTIFY_MESSAGE")?></span>
		</span>
	</span>
<?elseif($arResult["STATUS"] == "N"):?>
	<span class="url_notify" id="url_notify_<?=$arParams['NOTIFY_ID']?>">
		<a class="btn_buy notify_anch" href="javascript:void(0)" onclick="return notifyProduct('<?=$arResult["NOTIFY_URL"]?>', <?=$arParams['NOTIFY_ID']?>);"><i class="fa fa-envelope"></i><?=GetMessage("MFT_NOTIFY");?></a>		
	</span>
<?elseif($arResult["STATUS"] == "R"):?>
	<span class="url_notify" id="url_notify_<?=$arParams['NOTIFY_ID']?>">
		<a class="btn_buy notify_anch" id="notify_product_<?=$arParams['NOTIFY_ID']?>" href="javascript:void(0)"><i class="fa fa-envelope"></i><?=GetMessage("MFT_NOTIFY");?></a>
	</span>
<?endif;?>

<script type="text/javascript">
	$(function() {
		$("#notify_product_<?=$arParams['NOTIFY_ID']?>").click(function(e){
			e.preventDefault();
			$(window).resize(function () {
				modalHeight = ($(window).height() - $("#notify_<?=$arParams['NOTIFY_ID']?>").height()) / 2;
				$("#notify_<?=$arParams['NOTIFY_ID']?>").css({
					'top': modalHeight + 'px'
				});
			});
			$(window).resize();
			$("#notify_body_<?=$arParams['NOTIFY_ID']?>").css({'display':'block'});
			$("#notify_<?=$arParams['NOTIFY_ID']?>").css({'display':'block'});			
			$("#notify_<?=$arParams['NOTIFY_ID']?> #popup_user_email").focus();			
		});
		$("#notify_close_<?=$arParams['NOTIFY_ID']?>, #notify_body_<?=$arParams['NOTIFY_ID']?>").click(function(e){
			e.preventDefault();
			$("#notify_body_<?=$arParams['NOTIFY_ID']?>").css({'display':'none'});
			$("#notify_<?=$arParams['NOTIFY_ID']?>").css({'display':'none'});
		});		
	});
</script>

<div class="pop-up-bg notify_body" id="notify_body_<?=$arParams['NOTIFY_ID']?>"></div>
<div class="pop-up notify" id="notify_<?=$arParams['NOTIFY_ID']?>">
	<a href="javascript:void(0)" class="pop-up-close notify_close" id="notify_close_<?=$arParams['NOTIFY_ID']?>"><i class="fa fa-times"></i></a>
	<div class="h1"><?=GetMessage("MFT_NOTIFY");?></div>
	<div class="container">
		<div class="info">
			<div class="image">
				<?if(is_array($arResult["PREVIEW_IMG"])):?>
					<img src="<?=$arResult['PREVIEW_IMG']['SRC']?>" width="<?=$arResult['PREVIEW_IMG']['WIDTH']?>" height="<?=$arResult['PREVIEW_IMG']['HEIGHT']?>" alt="<?=$arResult['NAME']?>" />
				<?else:?>
					<img src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" width="150" height="150" alt="<?=$arResult['NAME']?>" />
				<?endif?>
			</div>
			<div class="name"><?=$arResult["NAME"]?></div>
		</div>		
		<div class="new_notify_form">	
			<div id="popup_n_error"></div>
			<div class="row">
				<div class="span1"><?=GetMessage("MFT_NOTIFY_EMAIL")?><span class="mf-req">*</span></div>
				<div class="span2">					
					<input type="text" name="popup_user_email" id="popup_user_email" value="" />
				</div>
				<div class="clr"></div>
			</div>						
			<?if($arResult["CAPTCHA_CODE"]):?>
				<div class="row">
					<div class="span1"><?=GetMessage("MFT_NOTIFY_CAPTCHA");?><span class="mf-req">*</span></div>
					<div class="span2">						
						<input type="text" name="popup_captcha_word" id="popup_captcha_word" maxlength="50" value="" />
						<span id="popup_captcha_img">
							<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="127" height="30" alt="CAPTCHA" />
						</span>
						<input type="hidden" name="popup_captcha_sid" id="popup_captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
					</div>
					<div class="clr"></div>
				</div>
			<?endif;?>
			<input type="hidden" name="popup_notify_url" id="popup_notify_url" value="<?=$arResult['NOTIFY_URL']?>" />			
			<div class="submit">
				<button name="send_button" class="btn_buy popdef"><?=GetMessage("MFT_NOTIFY_BUTTON");?></button>				
			</div>
		</div>		
	</div>
</div>

<script type="text/javascript">
	$(function() {
		$("#notify_<?=$arParams['NOTIFY_ID']?> .btn_buy").click(function() {
			var error = 'N';
			var useCaptha = 'N';
			BX('popup_n_error').innerHTML = '';

			var sessid = '';
			if(BX('sessid'))
				sessid = BX('sessid').value;
			var data = "sessid="+sessid+'&ajax=Y';

			if(BX('popup_user_email').value.length == 0) {				
				BX('popup_n_error').innerHTML = "<span class='alertMsg bad'><i class='fa fa-times'></i><span class='text'><?=GetMessageJS('MFT_NOTIFY_EMPTY_EMAIL');?></span></span>";
				error = "Y";
			}			

			var reg = /@/i;
			if(BX('popup_user_email').value.length > 0 && !reg.test(BX('popup_user_email').value)) {				
				BX('popup_n_error').innerHTML = "<span class='alertMsg bad'><i class='fa fa-times'></i><span class='text'><?=GetMessageJS('MFT_NOTIFY_ERR_EMAIL');?></span></span>";
				error = "Y";
			} else {
				data = data + '&user_mail='+BX('popup_user_email').value;

				if(BX('popup_captcha_sid') && BX('popup_captcha_word')) {
					data = data + '&captcha_sid='+BX('popup_captcha_sid').value;
					data = data + '&captcha_word='+BX('popup_captcha_word').value;
					useCaptha = 'Y';
				}
			}

			if(error == 'N') {
				BX.showWait();

				BX.ajax.post('/bitrix/components/bitrix/sale.notice.product/ajax.php', data, function(res) {
					BX.closeWait();

					var rs = eval( '('+res+')' );

					if(rs['ERRORS'].length > 0) {
						if(rs['ERRORS'] == 'NOTIFY_ERR_NULL')
							BX('popup_n_error').innerHTML = "<span class='alertMsg bad'><i class='fa fa-times'></i><span class='text'><?=GetMessageJS('MFT_NOTIFY_EMPTY_EMAIL')?></span></span>";
						else if(rs['ERRORS'] == 'NOTIFY_ERR_CAPTHA')
							BX('popup_n_error').innerHTML = "<span class='alertMsg bad'><i class='fa fa-times'></i><span class='text'><?=GetMessageJS('MFT_NOTIFY_ERR_CAPTCHA')?></span></span>";
						else if(rs['ERRORS'] == 'NOTIFY_ERR_MAIL_EXIST') {
							BX('popup_n_error').innerHTML = "<span class='alertMsg bad'><i class='fa fa-times'></i><span class='text'><?=GetMessageJS('MFT_NOTIFY_ERR_EMAIL_EXIST')?></span></span>";							
							BX('popup_user_email').value = '';							
						} else if(rs['ERRORS'] == 'NOTIFY_ERR_REG')
							BX('popup_n_error').innerHTML = "<span class='alertMsg bad'><i class='fa fa-times'></i><span class='text'><?=GetMessageJS('MFT_NOTIFY_ERR_REG')?></span></span>";
						else
							BX('popup_n_error').innerHTML = "<span class='alertMsg bad'><i class='fa fa-times'></i><span class='text'>" + rs['ERRORS'] + "</span></span>";

						if(useCaptha == 'Y') {
							BX.ajax.get('/bitrix/components/bitrix/sale.notice.product/ajax.php?reloadcaptha=Y', '', function(res) {
								BX('popup_captcha_sid').value = res;
								BX('popup_captcha_img').innerHTML = '<img src="/bitrix/tools/captcha.php?captcha_sid='+res+'" width="127" height="30" alt="CAPTCHA" />';
							});
						}
					} else if(rs['STATUS'] == 'Y') {
						notifyProduct(BX('popup_notify_url').value, <?=$arParams['NOTIFY_ID']?>);						
						$("#notify_body_<?=$arParams['NOTIFY_ID']?>").css({'display':'none'});
						$("#notify_<?=$arParams['NOTIFY_ID']?>").css({'display':'none'});
					}
				});
			}		
		});
	});
	
	function notifyProduct(url, id) {
		BX.showWait();

		BX.ajax.post(url, '', function(res) {
			BX.closeWait();			
			
			if(BX('url_notify_'+id))				
				BX('url_notify_'+id).innerHTML = "<span class='alertMsg good'><i class='fa fa-check'></i><span class='text'><?=GetMessage('MFT_NOTIFY_MESSAGE')?></span></span>";		
		});
	}
</script>