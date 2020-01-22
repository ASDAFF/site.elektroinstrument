<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if(count($arResult["ITEMS"]) < 1)
	return;?>

<script type="text/javascript">
	$(function() {
		$('.anythingSlider').anythingSlider({
			'theme': "default",
			'mode': 'horiz',
			'expand': true,
			'resizeContents': true,
			'easing': 'easeInOutExpo',
			'buildNavigation': true,
			'buildStartStop': false,
			'hashTags': false,
			'autoPlay': true,
			'pauseOnHover': true,
			'delay': 3000,
		});
		$(window).resize(function () {
			currentWidth = $('.center').width();
			if(currentWidth < '768') {
				$('.anythingContainer').css({
					'height': currentWidth * 0.30 + 'px'
				});
			} else {
				$('.anythingContainer').removeAttr('style');
			}
		});
		$(window).resize();
	});
</script>

<div class="anythingContainer">
	<ul class="anythingSlider">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<li>
				<?if(!empty($arItem["DISPLAY_PROPERTIES"]["URL"])):?>
					<a href="<?=$arItem["DISPLAY_PROPERTIES"]["URL"]["VALUE"]?>" style="background:url(<?=$arItem["PICTURE_PREVIEW"]["SRC"]?>) center center no-repeat; background-size:cover;"></a>
				<?else:?>
					<a href="javascript:void(0)" style="background:url(<?=$arItem["PICTURE_PREVIEW"]["SRC"]?>) center center no-repeat; background-size:cover;"></a>
				<?endif;?>
			</li>
		<?endforeach;?>
	</ul>
</div>