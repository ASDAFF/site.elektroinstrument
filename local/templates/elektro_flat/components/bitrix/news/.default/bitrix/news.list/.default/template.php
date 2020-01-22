<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if(count($arResult["ITEMS"]) < 1)
	return;?>

<div class="news-list">
	<?foreach($arResult["ITEMS"] as $arItem):?>
		<div class="news-item">
			<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
				<span class="news-date-cont">
					<span class="news-date"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></span>
				</span>
				<span class="news-title"><?=$arItem["NAME"]?></span>
			</a>
		</div>
	<?endforeach;?>
</div>
<div class="clr"></div>

<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?>
<?endif;?>