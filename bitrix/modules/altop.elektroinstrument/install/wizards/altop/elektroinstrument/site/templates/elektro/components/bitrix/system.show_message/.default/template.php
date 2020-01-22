<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<span class="alertMsg <?if($arParams["STYLE"] == "errortext"):?>bad<?else:?>good<?endif?>">
	<?if($arParams["STYLE"] == "errortext"):?><i class="fa fa-times"></i><?else:?><i class="fa fa-check"></i><?endif?>	
	<span class="text">
		<?=$arParams["MESSAGE"]?>
	</span>
</span>