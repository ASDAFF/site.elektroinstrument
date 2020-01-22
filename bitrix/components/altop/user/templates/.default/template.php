<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="personal_user">
	<div class="photo">
		<?if(!empty($arResult["PERSONAL_PHOTO"]["SRC"])):?>
			<img src="<?=$arResult["PERSONAL_PHOTO"]["SRC"]?>" width="<?=$arResult["PERSONAL_PHOTO"]["WIDTH"]?>" height="<?=$arResult["PERSONAL_PHOTO"]["HEIGHT"]?>" />
		<?else:?>
			<img src="<?=SITE_TEMPLATE_PATH?>/images/userpic.jpg" width="57px" height="57px" />
		<?endif;?>
	</div>
	<div class="info">
		<?if(!empty($arResult["FIO"])):?>
			<p class="fio"><?=$arResult["FIO"]?></p>
		<?else:?>
			<p class="fio"><?=$arResult["LOGIN"]?></p>
		<?endif;?>
		<a class="exit" href="<?=SITE_DIR?>?logout=yes"><?=GetMessage('USER_EXIT')?></a>
	</div>
</div>