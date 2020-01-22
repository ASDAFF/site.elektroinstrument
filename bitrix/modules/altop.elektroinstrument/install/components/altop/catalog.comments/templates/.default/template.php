<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$sComponentFolder = $this->__component->__path;
$sTemplateFolder  = $this->GetFolder();?>

<script type="text/javascript">
	$(function() {
		$('.reviews-collapse-link').click(function(e){
			e.preventDefault();
			$(window).resize(function () {
				modalHeight = ($(window).height() - $(".review").height()) / 2;
				$(".review").css({
					'top': modalHeight + 'px'
				});
			});
			$(window).resize();
			$('.review_body').css({'display':'block'});
			$('.review').css({'display':'block'});
		});
		$('.review_close, .review_body').click(function(e){
			e.preventDefault();
			$('.review_body').css({'display':'none'});
			$('.review').css({'display':'none'});
		});
	});
</script>

<div class="reviews-collapse reviews-minimized">
	<a href="javascript:void(0)" class="btn_buy apuo reviews-collapse-link"><i class="fa fa-pencil"></i><span><?=GetMessage("ADD_COMMENT_TITLE")?></span></a>
</div>
<div class="clr"></div>

<?if($arResult['COMMENTS_COUNT']) {	
	$count = 0;
	foreach($arResult['COMMENTS'] as $COMMENT) {?>
    	<div id="comment_<?=$COMMENT['ID']?>" class="comment">
			<div class="userpic">
				<?if(!empty($COMMENT["USER"]["PICT"]["SRC"])):?>
					<img src="<?=$COMMENT["USER"]["PICT"]["SRC"]?>" width="<?=$COMMENT["USER"]["PICT"]["WIDTH"]?>" height="<?=$COMMENT["USER"]["PICT"]["HEIGHT"]?>" alt="userpic" />
				<?else:?>
					<img src="<?=SITE_TEMPLATE_PATH?>/images/userpic.jpg" width="57" height="57" alt="userpic" />
				<?endif;?>
			</div>
			<div class="text">
				<span class="comment_name"><?=$COMMENT['USER']['NAME']?></span>
				<span class="comment_date"><?=$COMMENT['DATE_CREATE']?></span>
				<span class="comment_text"><?=$COMMENT['TEXT']?></span>
			</div>
		</div>
		<?$count++;
	}?>
	<div class="clr"></div>
<?}?>

<div class="pop-up-bg review_body"></div>
<div class="pop-up review">
	<a href="javascript:void(0)" class="pop-up-close review_close"><i class="fa fa-times"></i></a>
	<div class="h1"><?=GetMessage('ADD_COMMENT_TITLE');?></div>
	<?$frame = $this->createFrame("review")->begin("");?>
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
			<?if($arResult['NON_AUTHORIZED_USER_CAN_COMMENT'] == "Y") {?>
				<form action="<?=$APPLICATION->GetCurPage()?>" id="new_comment_form" class="new_comment_form">
					<span id="echo_comment_form"></span>
					<?if(!$USER->IsAuthorized()) {?>
						<div class="row">
							<div class="span1">
								<?=GetMessage('NAME');?><span class="mf-req">*</span>
							</div>
							<div class="span2">
								<input type="text" id="comment_name" name="comment_name" value="" />
							</div>
							<div class="clr"></div>
						</div>
					<?}?>
					<div class="row">
						<div class="span1">
							<?=GetMessage('YOUR_COMMENT')?><span class="mf-req">*</span>
						</div>
						<div class="span2">
							<textarea id="comment_text" name="comment_text" rows="3" cols="90"></textarea>
						</div>
						<div class="clr"></div>
					</div>
					<?if($arResult["USE_CAPTCHA"] == "Y") {?>
						<div class="row">
							<div class="span1">
								<?=GetMessage('CAPTCHA');?><span class="mf-req">*</span>
							</div>
							<div class="span2">
								<input type="text" id="comment_captcha_word" name="comment_captcha_word" maxlength="50" value=""/>
								<img id="comment_cImg" src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="127" height="30" alt="CAPTCHA" />
								<input type="hidden" id="comment_captcha_sid" name="comment_captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
							</div>
							<div class="clr"></div>
						</div>
					<?}?>
					<input type="hidden" id="comment_method" name="comment_method" value="comment"/>
					<div class="submit">
						<button onclick="button_comment(<?=$arParams['OBJECT_ID']?>, '<?=$arParams['OBJECT_NAME']?>', <?=$arParams['COMMENTS_IBLOCK_ID']?>, '<?=$arResult['URL'];?>', '<?=$sComponentFolder?>', '<?=$sTemplateFolder?>', '<?=$arResult["USE_CAPTCHA"]?>', '<?=$arParams["PRE_MODERATION"]?>', '<?=$arResult["PROPS"]?>');" type="button" name="send_button" class="btn_buy popdef"><?=GetMessage('ADD_COMMENT_BUTTON');?></button>
						<small class="result hidden"><?=GetMessage('ADD_COMMENT_ADDED');?></small>
					</div>
				</form>
			<?} else {?>
				<span class="must_auth">
					<?=GetMessage('ONLY_AUTH_USER');?>
				</span>
			<?}?>
		</div>
	<?$frame->end();?>
</div>