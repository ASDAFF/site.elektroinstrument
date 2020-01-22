<?php
/**
 * Created by PhpStorm.
 * User: ASDAFF
 * Date: 16.05.2018
 * Time: 21:49
 *
 * Event handling.
 *
 * We strongly recommend to group event handlers in classes.
 *
 * For example, you can handle events "OnBeforeUserAdd" and "OnBeforeUserUpdate"
 * with methods UserHandlers::OnBeforeUserAdd() and UserHandlers::OnBeforeUserUpdate(), like this:
 *
 * AddEventHandler("main", "OnBeforeUserAdd", Array("UserHandlers", "OnBeforeUserAdd"));
 */

use \Bitrix\Main\Loader;

AddEventHandler("iblock", "OnAfterIBlockElementUpdate", array("CHelper", "DoIBlockAfterSave"));
AddEventHandler("iblock", "OnAfterIBlockElementAdd", array("CHelper", "DoIBlockAfterSave"));
AddEventHandler("catalog", "OnPriceAdd", array("CHelper", "DoIBlockAfterSave"));
AddEventHandler("catalog", "OnPriceUpdate", array("CHelper", "DoIBlockAfterSave"));

/**
 * Пользовательское свойство инфоблока типа "Логическое" (true/false). Внешний вид - чекбокс.
 */
// добавляем тип для инфоблока
AddEventHandler("iblock", "OnIBlockPropertyBuildList", array("CUserTypeBool", "GetIBlockPropertyDescription"));

/**
 * Пользовательское свойство "Да/Нет в виде Input Checkbox (Флажок)
 */
AddEventHandler("iblock", "OnIBlockPropertyBuildList", array("CUserTypeYesNo", "GetUserTypeDescription"), 50);