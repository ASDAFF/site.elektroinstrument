<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

if(!Loader::includeModule('iblock'))
	return;

if(ModuleManager::isModuleInstalled("sale")) {
	$arTemplateParameters['USE_BIG_DATA'] = array(
		'PARENT' => 'BIG_DATA_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_USE_BIG_DATA'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
		'REFRESH' => 'Y'
	);
	if(!isset($arCurrentValues['USE_BIG_DATA']) || $arCurrentValues['USE_BIG_DATA'] == 'Y') {
		$rcmTypeList = array(
			'bestsell' => GetMessage('CP_BC_TPL_RCM_BESTSELLERS'),
			'personal' => GetMessage('CP_BC_TPL_RCM_PERSONAL'),
			'similar_sell' => GetMessage('CP_BC_TPL_RCM_SOLD_WITH'),
			'similar_view' => GetMessage('CP_BC_TPL_RCM_VIEWED_WITH'),
			'similar' => GetMessage('CP_BC_TPL_RCM_SIMILAR'),
			'any_similar' => GetMessage('CP_BC_TPL_RCM_SIMILAR_ANY'),
			'any_personal' => GetMessage('CP_BC_TPL_RCM_PERSONAL_WBEST'),
			'any' => GetMessage('CP_BC_TPL_RCM_RAND')
		);
		$arTemplateParameters['BIG_DATA_RCM_TYPE'] = array(
			'PARENT' => 'BIG_DATA_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_BIG_DATA_RCM_TYPE'),
			'TYPE' => 'LIST',
			'VALUES' => $rcmTypeList
		);
		unset($rcmTypeList);
	}
}?>