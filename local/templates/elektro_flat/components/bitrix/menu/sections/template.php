<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if(count($arResult) < 1)
	return;

global $arSetting;?>

<ul class="left-menu">
	<?$previousLevel = 0;	
	foreach($arResult as $key => $arItem):		
		if($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):
			echo str_repeat("</div></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));
		endif;
		if($arItem["DEPTH_LEVEL"] == 1):
			if($arItem["IS_PARENT"]):?>
				<li id="id<?=$key?>" class="parent<?if($arItem["SELECTED"]):?> selected<?endif?>">
					<a href="<?=$arItem['LINK']?>"><?=$arItem["TEXT"]?><?if($arSetting["CATALOG_LOCATION"]["VALUE"] == "LEFT"):?><span class="arrow"></span><?endif;?></a>
					<?if($arSetting["CATALOG_LOCATION"]["VALUE"] == "HEADER"):?><span class="arrow"></span><?endif;?>
					<div class="catalog-section-childs">
			<?else:?>
				<li id="id<?=$key?>"<?if($arItem["SELECTED"]):?> class="selected"<?endif?>>
					<a href="<?=$arItem['LINK']?>"><?=$arItem["TEXT"]?></a>
				</li>
			<?endif;
		elseif($arItem["DEPTH_LEVEL"] == 2):?>
			<div class="catalog-section-child">
				<a href="<?=$arItem['LINK']?>">
					<span class="child">
						<span class="image">
							<?if(is_array($arItem["PICTURE"])):?>
								<img src="<?=$arItem['PICTURE']['SRC']?>" width="<?=$arItem['PICTURE']['WIDTH']?>" height="<?=$arItem['PICTURE']['HEIGHT']?>" alt="<?=$arItem['TEXT']?>" />
							<?else:?>
								<img src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" width="50" height="50" alt="<?=$arItem['TEXT']?>" />
							<?endif;?>
						</span>
						<span class="text"><?=$arItem["TEXT"]?></span>
					</span>
				</a>
			</div>
		<?else:
			continue;
		endif;
		$previousLevel = $arItem["DEPTH_LEVEL"];		
	endforeach;	
	if($previousLevel > 1):
		echo str_repeat("</div></li>", ($previousLevel-1));
	endif?>
</ul>

<script type="text/javascript">
	//<![CDATA[
	$(function() {
		<?if($arSetting["CATALOG_LOCATION"]["VALUE"] == "HEADER"):?>			
			$('.top-catalog ul.left-menu').moreMenu();
		<?endif;?>
		var popupItems = $("ul.left-menu").find('> li.parent'),
			popupNumItems = $("ul.left-menu").find('> li.parent').length;		
		for(i = 0; i < popupNumItems; i++) {
			eval('var timeOut'+popupItems[i].id);
		}		
		$("ul.left-menu > li.parent").hover(function() {
			<?if($arSetting["CATALOG_LOCATION"]["VALUE"] == "LEFT"):?>
				var li = $(this),
					uid = li.attr("id"),					
					pos = li.offset(),				
					top = pos.top - 5,
					left = pos.left + li.width() + 9;
            			
				eval("timeIn"+uid+" = setTimeout(function(){ li.find('> .catalog-section-childs').show(15).css({'top': top + 'px', 'left': left + 'px'}); }, 200);");
			<?elseif($arSetting["CATALOG_LOCATION"]["VALUE"] == "HEADER"):?>
				var li = $(this),
					uid = li.attr("id"),				
					pos = li.position(),
					top = pos.top + li.height() + 13;
					if(li.parent().width() - pos.left < li.find('> .catalog-section-childs').width()) {
						var left = "auto",
							right = 10 + "px";
					} else {
						var left = pos.left + "px",
							right = "auto";
					}
				var	arrowTop = pos.top + li.height() + 3,
					arrowLeft = pos.left + (li.width() / 2);					
				
				eval("timeIn"+uid+" = setTimeout(function(){ li.find('> .catalog-section-childs').show(15).css({'top': top + 'px', 'left': left, 'right': right});li.find('> .arrow').show(15).css({'top': arrowTop + 'px', 'left': arrowLeft + 'px'}); }, 200);");				
			<?endif;?>			
			eval("clearTimeout(timeOut"+uid+")");
		}, function(){
			var li = $(this),
				uid = li.attr("id");
            
			eval("clearTimeout(timeIn"+uid+")");			
			eval("timeOut"+uid+" = setTimeout(function(){ li.find('> .catalog-section-childs').hide(15);<?if($arSetting['CATALOG_LOCATION']['VALUE'] == 'HEADER'):?>li.find('> .arrow').hide(15);<?endif;?> }, 200);");
        });
	});
	//]]>
</script>