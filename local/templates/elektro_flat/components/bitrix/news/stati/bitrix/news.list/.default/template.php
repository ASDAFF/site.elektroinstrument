<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if(count($arResult["ITEMS"]) < 1)
	return;
?>

<div class="stati-list">
	<?foreach($arResult["ITEMS"] as $arItem):?>
		<div class="stati-item">
			<?if(!empty($arItem["PICTURE_PREVIEW"]["SRC"])):?>
				<div class="image_cont">
					<div class="image">
						<img src="<?=$arItem['PICTURE_PREVIEW']['SRC']?>" width="<?=$arItem['PICTURE_PREVIEW']['WIDTH']?>" height="<?=$arItem['PICTURE_PREVIEW']['HEIGHT']?>" alt="<?=$arItem['NAME']?>" />
					</div>
				</div>
			<?endif;?>
			<div class="descr">
				<a class="stati-title" href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem["NAME"]?></a>
				<div class="stati-detail"><?=$arItem["PREVIEW_TEXT"]?></div>
				<a class="more" href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=GetMessage("NEWS_MORE")?></a>
			</div>
		</div>
	<?endforeach;?>
</div>
<div class="clr"></div>

<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?>
<?endif;?>