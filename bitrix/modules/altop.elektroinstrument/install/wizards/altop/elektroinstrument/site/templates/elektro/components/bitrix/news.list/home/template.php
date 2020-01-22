<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if(count($arResult["ITEMS"]) < 1)
	return;
?>

<div class="news_home">
	<div class="h3"><?=GetMessage("NEWS_TITLE")?></div>
	<a class="all" href="<?=SITE_DIR?>news/"><?=GetMessage("ALL_NEWS")?></a>
	<div class="clr"></div>
	<ul class="lsnn"> 
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<li>
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
					<span class="date"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></span>
					<span class="title-link">
						<span><?=$arItem["NAME"]?></span>
					</span>
				</a>
			</li>
		<?endforeach;?>
	</ul>
</div>