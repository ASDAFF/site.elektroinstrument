<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?foreach($arResult["ITEMS"] as $key => $arElement):
	
	if(is_array($arElement["DETAIL_PICTURE"])) {
		$arFileTmp = CFile::ResizeImageGet(
			$arElement["DETAIL_PICTURE"],
			array("width" => $arParams["DISPLAY_IMG_WIDTH"], "height" => $arParams["DISPLAY_IMG_HEIGHT"]),
			BX_RESIZE_IMAGE_PROPORTIONAL,
			true
		);

		$arResult["ITEMS"][$key]["PREVIEW_IMG"] = array(
			"SRC" => $arFileTmp["src"],
			'WIDTH' => $arFileTmp["width"],
			'HEIGHT' => $arFileTmp["height"],
		);
	}

	if($arElement["PROPERTIES"]["MANUFACTURER"]["VALUE"]):
		$obElement = CIBlockElement::GetByID($arElement["PROPERTIES"]["MANUFACTURER"]["VALUE"]);
		if($arEl = $obElement->GetNext()):
			$arResult["ITEMS"][$key]["PROPERTIES"]["MANUFACTURER"]["NAME"] = $arEl["NAME"];

			$rsFile = CFile::ResizeImageGet(
				$arEl["PREVIEW_PICTURE"],
				array("width" => 69, "height" => 24),
				BX_RESIZE_IMAGE_PROPORTIONAL,
				true
			);
			$arResult["ITEMS"][$key]["PROPERTIES"]["MANUFACTURER"]["PREVIEW_IMG"] = array(
				"SRC" => $rsFile["src"],
				'WIDTH' => $rsFile["width"],
				'HEIGHT' => $rsFile["height"],
			);
		endif;
	endif;

	/***OFFERS***/
	if(isset($arElement['OFFERS']) && !empty($arElement['OFFERS'])):

		/***MIN_PRICE***/
		$minPrice = false;
		$minDiscount = false;
		$minDiscountDiffPercent = false;
		$minCurr = false;
		foreach($arElement['OFFERS'] as $key_off => $arOffer):
			if($minPrice === false || $minPrice > $arOffer['MIN_PRICE']['VALUE']) {
				$minPrice = $arOffer['MIN_PRICE']['VALUE'];
				$minDiscount = $arOffer['MIN_PRICE']['DISCOUNT_VALUE'];
				$minDiscountDiffPercent = $arOffer['MIN_PRICE']['DISCOUNT_DIFF_PERCENT'];
				$minCurr = $arOffer['MIN_PRICE']['CURRENCY'];
			}
		endforeach;
		$prices = array(
			"VALUE" => $minPrice,
			"DISCOUNT_VALUE" => $minDiscount,
			"DISCOUNT_DIFF_PERCENT" => $minDiscountDiffPercent,
			"CURRENCY" => $minCurr
		);
		$arResult["ITEMS"][$key]["OFFERS_MIN_PRICE"] = $prices;
		/***END_MIN_PRICE***/

	endif;
	/***END_OFFERS***/

endforeach;
/***END_ELEMENTS***/	
?>