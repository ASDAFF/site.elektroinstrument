<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="kabinet">
	<?$frame = $this->createFrame("kabinet")->begin("");?>
	
		<script type="text/javascript">
			$(function() {
				$('.login_anch').click(function(e){
					e.preventDefault();
					$('.login_body').css({'display':'block'});
					$('.login').css({'display':'block'});
				});
				$('.login_close, .login_body').click(function(e){
					e.preventDefault();
					$('.login_body').css({'display':'none'});
					$('.login').css({'display':'none'});
				});
			});
		</script>

		<?if(!$USER->IsAuthorized()):?>
			<a class="login_anch" href="javascript:void(0)" title="<?=GetMessage("LOGIN")?>"><i class="fa fa-user"></i><span><?=GetMessage("LOGIN")?></span></a>
			<div class="pop-up-bg login_body"></div>
			<div class="pop-up login">
				<a href="javascript:void(0)" class="pop-up-close login_close"><i class="fa fa-times"></i></a>
				<div class="login-form">
					<div class="fields">
						<form name="form_auth" method="post" target="_top" action="<?=SITE_DIR?>personal/">
							<input type="hidden" name="AUTH_FORM" value="Y"/>
							<input type="hidden" name="TYPE" value="AUTH"/>
							<?if(strlen($arResult["BACKURL"]) > 0):?>
								<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>"/>
							<?endif?>
							<?foreach($arResult["POST"] as $key => $value){?>
								<input type="hidden" name="<?=$key?>" value="<?=$value?>"/>
							<?}?>
							<div class="field">
								<input type="text" name="USER_LOGIN" maxlength="50" placeholder="<?=GetMessage("AUTH_LOGIN")?>" value="" class="input-field"/>
							</div>	
							<div class="field">
								<input type="password" name="USER_PASSWORD" maxlength="50" placeholder="<?=GetMessage("AUTH_PASSWORD")?>" value="" class="input-field"/>
							</div>
							<div class="field field-button">
								<button type="submit" name="Login" class="btn_buy popdef" value="<?=GetMessage('LOGIN')?>"><?=GetMessage('LOGIN')?></button>
							</div>
							<div class="field" style="margin:0px;">
								<a class="btn_buy apuo forgot" href="<?=SITE_DIR?>personal/profile/?forgot_password=yes" rel="nofollow"><?=GetMessage("AUTH_FORGOT_PASSWORD")?></a>
							</div>
						</form>
						<script type="text/javascript">
							<?if(strlen($arResult["LAST_LOGIN"])>0) {?>
								try {
									document.form_auth.USER_PASSWORD.focus();
								} catch(e) {}
							<?} else {?>
								try {
									document.form_auth.USER_LOGIN.focus();
								} catch(e) {}
							<?}?>
						</script>
					</div>
					<p class="login_as"><?=GetMessage("LOGIN_AS_USER")?></p>
					<?if($arResult["AUTH_SERVICES"]):?>
						<?$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "icons", 
							array(
								"AUTH_SERVICES"=>$arResult["AUTH_SERVICES"],
								"SUFFIX"=>"form", 
							), 
							$component, 
							array("HIDE_ICONS"=>"Y")
						);?>
						<?$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "",
							array(
								"AUTH_SERVICES"=>$arResult["AUTH_SERVICES"],
								"AUTH_URL"=>$arResult["AUTH_URL"],
								"POST"=>$arResult["POST"],
								"POPUP"=>"Y",
								"SUFFIX"=>"form",
							),
							$component,
							array("HIDE_ICONS"=>"Y")
						);?>
					<?endif?>					
				</div>
			</div>
			<a class="register" href="<?=SITE_DIR?>personal/profile/?register=yes" title="<?=GetMessage("REGISTRATION")?>" rel="nofollow"><i class="fa fa-user-plus"></i><span><?=GetMessage("REGISTRATION")?></span></a>
		<?else:?>
			<a class="personal" href="<?=SITE_DIR?>personal/" title="<?=GetMessage("PERSONAL")?>" rel="nofollow"><i class="fa fa-user"></i><span><?=GetMessage("PERSONAL")?></span></a>
		<?endif;
	$frame->end();?>
</div>