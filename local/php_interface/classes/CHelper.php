<?php
/**
 * Created by PhpStorm.
 * User: ASDAFF
 * Date: 22.01.2020
 * Time: 19:44
 */

class CHelper
{
    function DoIBlockAfterSave($arg1, $arg2 = false) {
        $ELEMENT_ID = false;
        $IBLOCK_ID = false;
        $OFFERS_IBLOCK_ID = false;
        $OFFERS_PROPERTY_ID = false;
        if(CModule::IncludeModule('currency'))
            $strDefaultCurrency = CCurrency::GetBaseCurrency();

        if(is_array($arg2) && $arg2["PRODUCT_ID"] > 0) {
            $rsPriceElement = CIBlockElement::GetList(
                array(),
                array(
                    "ID" => $arg2["PRODUCT_ID"],
                ),
                false,
                false,
                array("ID", "IBLOCK_ID")
            );
            if($arPriceElement = $rsPriceElement->Fetch()) {
                $arCatalog = CCatalog::GetByID($arPriceElement["IBLOCK_ID"]);
                if(is_array($arCatalog)) {
                    if($arCatalog["OFFERS"] == "Y") {
                        $rsElement = CIBlockElement::GetProperty(
                            $arPriceElement["IBLOCK_ID"],
                            $arPriceElement["ID"],
                            "sort",
                            "asc",
                            array("ID" => $arCatalog["SKU_PROPERTY_ID"])
                        );
                        $arElement = $rsElement->Fetch();
                        if($arElement && $arElement["VALUE"] > 0) {
                            $ELEMENT_ID = $arElement["VALUE"];
                            $IBLOCK_ID = $arCatalog["PRODUCT_IBLOCK_ID"];
                            $OFFERS_IBLOCK_ID = $arCatalog["IBLOCK_ID"];
                            $OFFERS_PROPERTY_ID = $arCatalog["SKU_PROPERTY_ID"];
                        }
                    } elseif($arCatalog["OFFERS_IBLOCK_ID"] > 0) {
                        $ELEMENT_ID = $arPriceElement["ID"];
                        $IBLOCK_ID = $arPriceElement["IBLOCK_ID"];
                        $OFFERS_IBLOCK_ID = $arCatalog["OFFERS_IBLOCK_ID"];
                        $OFFERS_PROPERTY_ID = $arCatalog["OFFERS_PROPERTY_ID"];
                    } else {
                        $ELEMENT_ID = $arPriceElement["ID"];
                        $IBLOCK_ID = $arPriceElement["IBLOCK_ID"];
                        $OFFERS_IBLOCK_ID = false;
                        $OFFERS_PROPERTY_ID = false;
                    }
                }
            }
        } elseif(is_array($arg1) && $arg1["ID"] > 0 && $arg1["IBLOCK_ID"] > 0) {
            $arOffers = CIBlockPriceTools::GetOffersIBlock($arg1["IBLOCK_ID"]);
            if(is_array($arOffers)) {
                $ELEMENT_ID = $arg1["ID"];
                $IBLOCK_ID = $arg1["IBLOCK_ID"];
                $OFFERS_IBLOCK_ID = $arOffers["OFFERS_IBLOCK_ID"];
                $OFFERS_PROPERTY_ID = $arOffers["OFFERS_PROPERTY_ID"];
            }
        }

        if($ELEMENT_ID) {
            static $arPropCache = array();
            if(!array_key_exists($IBLOCK_ID, $arPropCache)) {
                $rsProperty = CIBlockProperty::GetByID("MINIMUM_PRICE", $IBLOCK_ID);
                $arProperty = $rsProperty->Fetch();
                if($arProperty)
                    $arPropCache[$IBLOCK_ID] = $arProperty["ID"];
                else
                    $arPropCache[$IBLOCK_ID] = false;
            }

            if($arPropCache[$IBLOCK_ID]) {
                if($OFFERS_IBLOCK_ID) {
                    $rsOffers = CIBlockElement::GetList(
                        array(),
                        array(
                            "IBLOCK_ID" => $OFFERS_IBLOCK_ID,
                            "PROPERTY_".$OFFERS_PROPERTY_ID => $ELEMENT_ID,
                        ),
                        false,
                        false,
                        array("ID")
                    );
                    while($arOffer = $rsOffers->Fetch())
                        $arProductID[] = $arOffer["ID"];

                    if(!is_array($arProductID))
                        $arProductID = array($ELEMENT_ID);
                } else
                    $arProductID = array($ELEMENT_ID);

                $minPrice = false;
                $minQuantity = false;

                $rsPrices = CPrice::GetList(
                    array(),
                    array(
                        "PRODUCT_ID" => $arProductID,
                    )
                );
                while($arPrice = $rsPrices->Fetch()) {
                    if(CModule::IncludeModule('currency') && $strDefaultCurrency != $arPrice['CURRENCY'])
                        $arPrice["PRICE"] = CCurrencyRates::ConvertCurrency($arPrice["PRICE"], $arPrice["CURRENCY"], $strDefaultCurrency);

                    $PRICE = $arPrice["PRICE"];
                    $ar_res = CCatalogProduct::GetByID($arPrice["PRODUCT_ID"]);
                    $QUANTITY = $ar_res["QUANTITY"];

                    if($minPrice === false || $minPrice > $PRICE) {
                        $minPrice = $PRICE;
                        $minQuantity = $QUANTITY;
                    }
                }

                if($minPrice !== false) {
                    CIBlockElement::SetPropertyValuesEx(
                        $ELEMENT_ID,
                        $IBLOCK_ID,
                        array(
                            "MINIMUM_PRICE" => $minPrice
                        )
                    );

                    CCatalogProduct::Update(
                        $ELEMENT_ID,
                        array(
                            "QUANTITY" => $minQuantity
                        )
                    );
                }
            }
        }
    }
}