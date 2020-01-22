<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if(count($arResult["SECTIONS"]) < 1)
	return;
?>

<div class="catalog-section-list">
	<?foreach($arResult["SECTIONS"] as $arSection):

		$bHasChildren = is_array($arSection['CHILDREN']) && count($arSection['CHILDREN']) > 0;?>

		<div class="catalog-section">
			<?if($arSection['NAME'] && $arResult['SECTION']['ID'] != $arSection['ID']):?>
				<div class="catalog-section-title" <?if($bHasChildren):?>style="margin:0px 0px 4px 0px;"<?else:?>style="margin:0px 0px 2px 0px;"<?endif;?>>
					<a href="<?=$arSection["SECTION_PAGE_URL"]?>"><?=$arSection["NAME"]?></a>
				</div>
			<?endif;?>
			<?if($bHasChildren):?>
				<div class="catalog-section-childs">
					<?foreach($arSection['CHILDREN'] as $key => $arChild):?>
						<div class="catalog-section-child">
							<a href="<?=$arChild["SECTION_PAGE_URL"]?>">
								<span class="child">
									<span class="image">
										<?if(is_array($arChild['PICTURE_PREVIEW'])):?>
											<img src="<?=$arChild['PICTURE_PREVIEW']['SRC']?>" width="<?=$arChild['PICTURE_PREVIEW']['WIDTH']?>" height="<?=$arChild['PICTURE_PREVIEW']['HEIGHT']?>" alt="<?=$arChild['NAME']?>" />
										<?else:?>
											<img src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" width="50" height="50" alt="<?=$arChild['NAME']?>" />
										<?endif;?>
									</span>
									<span class="text"><?=$arChild['NAME']?></span>
								</span>
							</a>
						</div>
					<?endforeach;?>
					<div class="clr"></div>
				</div>
			<?endif;?>
		</div>
	
	<?endforeach;?>
</div>