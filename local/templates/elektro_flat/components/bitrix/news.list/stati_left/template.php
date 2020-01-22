<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if(count($arResult["ITEMS"]) < 1)
	return;
?>

<div class="stati_left">
	<div class="h3"><?=GetMessage("STATI_TITLE")?></div>
	<ul class="lsnn"> 
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<li>
				<?if(!empty($arItem["PICTURE_PREVIEW"]["SRC"])):?>
					<div class="image_cont">
						<div class="image">
							<img src="<?=$arItem["PICTURE_PREVIEW"]["SRC"]?>" width="<?=$arItem["PICTURE_PREVIEW"]["WIDTH"]?>" height="<?=$arItem["PICTURE_PREVIEW"]["HEIGHT"]?>" alt="<?=$arItem['NAME']?>" />
						</div>
					</div>
				<?endif;?>
				<a class="title-link" href="<?=$arItem["DETAIL_PAGE_URL"]?>">
					<?=$arItem["NAME"]?>
				</a>
			</li>
		<?endforeach;?>
	</ul>
	<a class="all" href="<?=SITE_DIR?>reviews/"><?=GetMessage("ALL_STATI")?></a>
</div>