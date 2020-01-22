<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if(count($arResult["ITEMS"]) < 1)
	return;
?>

<div class="vendors-section-list">
	<div class="vendors-section-items">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<div class="vendors-section-item">
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
					<span class="item">
						<span class="image">
							<?if(!empty($arItem["PICTURE_PREVIEW"]["SRC"])):?>
								<img src="<?=$arItem["PICTURE_PREVIEW"]["SRC"]?>" width="<?=$arItem["PICTURE_PREVIEW"]['WIDTH']?>" height="<?=$arItem["PICTURE_PREVIEW"]['HEIGHT']?>" alt="<?=$arItem['NAME']?>" />
							<?else:?>
								<img src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" width="50" height="50" alt="<?=$arItem['NAME']?>" />
							<?endif;?>
						</span>
						<span class="item-title"><?=$arItem["NAME"]?></span>
					</span>
				</a>
			</div>
		<?endforeach;?>
		<div class="clr"></div>
	</div>
</div>

<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?>
<?endif;?>