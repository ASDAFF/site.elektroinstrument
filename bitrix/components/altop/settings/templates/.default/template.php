<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(false);?>

<div class="style-switcher <?=$_COOKIE['styleSwitcher'] == 'open' ? 'active' : ''?>">
	<div class="header">
		<?=GetMessage("THEME_MODIFY")?><span class="switch"><i class="fa fa-cog"></i></span>		
	</div>
	<form action="<?=$APPLICATION->GetCurPage()?>" method="POST" name="style-switcher">
		<?=bitrix_sessid_post("sessid-style-switcher");
		$i = 1;
		foreach($arResult as $optionCode => $arOption):
			if($arOption["IN_SETTINGS_PANEL"] === "Y"):?>
				<div class="block">					
					<div class="block-title">
						<span><?=$arOption["TITLE"]?></span>
						<a class="plus" id="plus-minus-<?=$optionCode?>" href="javascript:void(0)"><i class="fa fa-plus-circle"></i><i class="fa fa-minus-circle"></i></a>
					</div>
					<div class="options" id="options-<?=$optionCode?>" style="display:none;">							
						<?$k = 1;
						if($arOption["TYPE"] == "selectbox"):							
							foreach($arOption["LIST"] as $variantCode => $arVariant):?>								
								<div class="custom-forms">
									<input type="radio" id="option-<?=$i?>-<?=$k?>" name="<?=$optionCode?>" <?=$arVariant["CURRENT"] == "Y" ? "checked=\"checked\"" : ""?> value="<?=$variantCode?>" />
									<label for="option-<?=$i?>-<?=$k?>"><?=$arVariant["TITLE"]?></label>
								</div>
								<?$k++;
							endforeach;?>
							<div class="clr"></div>
						<?elseif($arOption["TYPE"] == "multiselectbox"):							
							foreach($arOption["LIST"] as $variantCode => $arVariant):?>								
								<div class="custom-forms option">
									<input type="checkbox" id="option-<?=$i?>-<?=$k?>" name="<?=$optionCode?>[]" <?=$arVariant["CURRENT"] == "Y" ? "checked=\"checked\"" : ""?> value="<?=$variantCode?>" />
									<label for="option-<?=$i?>-<?=$k?>"><span class="check-cont"><span class="check"><i class="fa fa-check"></i></span></span><span class="check-title"><?=$arVariant["TITLE"]?></span></label>
								</div>
								<?$k++;
							endforeach;
						endif;?>						
					</div>
				</div>
				<?$i++;
			endif;			
		endforeach;?>
		<div class="reset">
			<button type="button" name="reset_button" class="btn_buy apuo"><i class="fa fa-repeat"></i><span><?=GetMessage("THEME_RESET")?></span></button>
		</div>
	</form>
	
	<script type="text/javascript">
		$(function() {
			if($.cookie("styleSwitcher") == "open") {
				$(".style-switcher").addClass("active");
			}
			
			$(".style-switcher .switch").hover(function(e) {
				$(".fa-cog").addClass("fa-spin");
			}, function() {
				$(".fa-cog").removeClass("fa-spin");
			});
			
			$(".style-switcher .switch").click(function(e) {
				e.preventDefault();
				var styleswitcher = $(this).closest(".style-switcher");
				if(styleswitcher.hasClass("active")) {
					styleswitcher.animate({right: "-" + styleswitcher.outerWidth() + "px"}, 300).removeClass("active");
					$.removeCookie("styleSwitcher", {path: "/"});
				} else {
					styleswitcher.animate({right: "0"}, 300).addClass("active");
					var pos = styleswitcher.offset().top;
					if($(window).scrollTop() > pos){
						$("html, body").animate({scrollTop: pos}, 500);
					}
					$.cookie("styleSwitcher", "open", {path: "/"});
				}
			});
			
			<?foreach($arResult as $optionCode => $arOption):
				if($arOption["IN_SETTINGS_PANEL"] === "Y"):?>
					if($.cookie("plus-minus-<?=$optionCode?>") == "open") {
						$("#plus-minus-<?=$optionCode?>").removeClass().addClass("minus");
						$(".style-switcher .block #options-<?=$optionCode?>").show();
					}	
						
					$("#plus-minus-<?=$optionCode?>").click(function() {
						var clickitem = $(this);
						if(clickitem.hasClass("plus")) {
							clickitem.removeClass().addClass("minus");
							$.cookie("plus-minus-<?=$optionCode?>", "open", {path: "/"});
						} else {
							clickitem.removeClass().addClass("plus");
							$.removeCookie("plus-minus-<?=$optionCode?>", {path: "/"});
						}
						$(".style-switcher .block #options-<?=$optionCode?>").slideToggle();
					});
				<?endif;
			endforeach;?>
			
			$(".style-switcher .reset button[name=reset_button]").click(function(e) {
				$("form[name=style-switcher]").append("<input type='hidden' name='THEME' value='default' />");
				$("form[name=style-switcher]").submit();
			});			
			
			$(".style-switcher .options input[type=radio], .style-switcher .options input[type=checkbox]").click(function(e) {		
				$("form[name=style-switcher]").submit();
			});
		});
	</script>
</div>