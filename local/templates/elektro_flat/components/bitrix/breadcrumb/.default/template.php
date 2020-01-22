<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

__IncludeLang(dirname(__FILE__).'/lang/'.LANGUAGE_ID.'/'.basename(__FILE__));

$curPage = $GLOBALS['APPLICATION']->GetCurPage($get_index_page=false);

if($curPage != SITE_DIR) {
	if(empty($arResult) || $curPage != $arResult[count($arResult)-1]['LINK'])
		$arResult[] = array('TITLE' =>  htmlspecialcharsback($GLOBALS['APPLICATION']->GetTitle(false, true)), 'LINK' => $curPage);
}

if(empty($arResult))
	return "";
	
$strReturn = '<div class="breadcrumb"><ul><li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.SITE_DIR.'" title="'.GetMessage('BREADCRUMB_HOME').'" itemprop="url"><i class="fa fa-home"></i><span itemprop="title" class="breadcrumb_home">'.GetMessage("BREADCRUMB_HOME").'</span></a></li>';

for($index = 0, $itemSize = count($arResult)-1; $index < $itemSize; $index++) {
	$strReturn .= '<li class="separator"><span>|</span></li>';

	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);
	
	if($arResult[$index]["LINK"] <> "")
		$strReturn .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.$arResult[$index]["LINK"].'" itemprop="url"><span itemprop="title">'.$title.'</span></a></li>';
	else
		$strReturn .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'.$title.'</span></li>';
}

$strReturn .= '</ul></div>';

return $strReturn;
?>