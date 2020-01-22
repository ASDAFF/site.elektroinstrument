<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);?>

<div class="news-detail">
	<?if(is_array($arResult["DETAIL_PICTURE"])):?>
		<img class="detail_picture" src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" width="<?=$arResult["DETAIL_PICTURE"]["WIDTH"]?>" height="<?=$arResult["DETAIL_PICTURE"]["HEIGHT"]?>" alt="<?=$arResult["NAME"]?>" />
	<?endif?>
	<div class="detail-text"><?=$arResult["DETAIL_TEXT"]?></div>
</div>

<?if(is_array($arResult["TOLEFT"]) || is_array($arResult["TORIGHT"])):?>
	<ul class="stati_prev_next"> 
		<?if(is_array($arResult["TORIGHT"])):?>
			<li class="prev">
				<a href="<?=$arResult["TORIGHT"]["URL"]?>">
					<span class="arrow_prev"></span>
					<?if(!empty($arResult["TORIGHT"]["PREVIEW_PICTURE"]["src"])):?>
						<span class="image_cont">
							<span class="image">
								<img src="<?=$arResult["TORIGHT"]["PREVIEW_PICTURE"]["src"]?>" width="<?=$arResult["TORIGHT"]["PREVIEW_PICTURE"]["width"]?>" height="<?=$arResult["TORIGHT"]["PREVIEW_PICTURE"]["height"]?>" alt="<?=$arResult["TORIGHT"]["NAME"]?>" />
							</span>
						</span>
					<?endif;?>
					<span class="title-link"><?=$arResult["TORIGHT"]["NAME"]?></span>
				</a>
			</li>
		<?endif?>
		<?if(is_array($arResult["TOLEFT"])):?>
			<li class="next">
				<a href="<?=$arResult["TOLEFT"]["URL"]?>">
					<span class="title-link"><?=$arResult["TOLEFT"]["NAME"]?></span>
					<?if(!empty($arResult["TOLEFT"]["PREVIEW_PICTURE"]["src"])):?>
						<span class="image_cont">
							<span class="image">
								<img src="<?=$arResult["TOLEFT"]["PREVIEW_PICTURE"]["src"]?>" width="<?=$arResult["TOLEFT"]["PREVIEW_PICTURE"]["width"]?>" height="<?=$arResult["TOLEFT"]["PREVIEW_PICTURE"]["height"]?>" alt="<?=$arResult["TOLEFT"]["NAME"]?>" />
							</span>
						</span>
					<?endif;?>
					<span class="arrow_next"></span>
				</a>
			</li>
		<?endif?>
	</ul>
<?endif?>