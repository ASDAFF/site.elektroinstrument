<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("test");
?>

<?
$arFrontParametrs = CElektroinstrument::GetFrontParametrsValues(SITE_ID);

echo "<pre>";print_r($arFrontParametrs);echo "</pre>";


?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>