<?php

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

Loc::loadMessages(__FILE__);
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/vsech.multiverse/include.php';

$iblockInstaller = new \Vsech\Multiverse\Wizard\IblockInstaller();
if (!$iblockInstaller->includeModule()) {
    return;
}

$iblockXmlFile = WIZARD_SERVICE_RELATIVE_PATH . '/xml/' . LANGUAGE_ID . '/export.xml';
$iblockCode = 'rasp_' . WIZARD_SITE_ID;
$iblockType = 'content_multiverse';
$iblockId = $iblockInstaller->findIblockId($iblockCode, $iblockType);

if ($iblockId !== null && WIZARD_REINSTALL_DATA) {
    $iblockInstaller->deleteIblock($iblockId);
    $iblockId = null;
}

if ($iblockId === null) {
    $iblockId = $iblockInstaller->importFromXml(
        $iblockXmlFile,
        $iblockCode,
        $iblockType,
        WIZARD_SITE_ID,
        $iblockInstaller->getImportPermissions('content_multiverse_editor')
    );

    if ($iblockId < 1) {
        return;
    }

    $iblockInstaller->updateImportedIblock($iblockId, $iblockCode);

    WizardServices::IncludeServiceLang('features.php');

    $tabs = [
        array_merge(
            [
                ['edit1', GetMessage('FEATURES_TAB_NAME')],
                ['ACTIVE', GetMessage('FEATURES_FIELD_ACTIVE')],
                ['NAME', GetMessage('FEATURES_FIELD_NAME')],
            ],
            $iblockInstaller->getPropertyTabs($iblockId),
            [
                ['PREVIEW_PICTURE', GetMessage('FEATURES_FIELD_PREVIEW_PICTURE')],
                ['PREVIEW_TEXT', GetMessage('FEATURES_FIELD_PREVIEW_TEXT')],
            ]
        ),
    ];

    $iblockInstaller->saveElementFormSettings($iblockId, $tabs);
} else {
    $iblockInstaller->ensureSiteBinding($iblockId, WIZARD_SITE_ID);
}

CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . '/_index.php', ['EXPORT_IBLOCK_ID' => $iblockId]);
