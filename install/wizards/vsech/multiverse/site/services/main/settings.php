<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

if (!defined("WIZARD_SITE_ID"))
    return;

if (!defined("WIZARD_SITE_DIR"))
    return;

	
// START IMPORT MENU TYPES
	$arMenuTypes = GetMenuTypes();
	$arMenuTypes["top"] = GetMessage("MAIN_OPT_MENU_TOP");
	SetMenuTypes($arMenuTypes, WIZARD_SITE_ID);
// END IMPORT MENU TYPES


?>