<?php

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

Loc::loadMessages(__FILE__);
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/vsech.multiverse/include.php';

$iblockInstaller = new \Vsech\Multiverse\Wizard\IblockInstaller();
if (!$iblockInstaller->includeModule() || $iblockInstaller->isLandingWizardInstalled(WIZARD_SITE_ID)) {
    return;
}

$iblockInstaller->ensureType(
    [
        'ID' => 'content_multiverse',
        'SECTIONS' => 'Y',
        'IN_RSS' => 'N',
        'SORT' => 500,
        'LANG' => [
            'ru' => [
                'NAME' => Loc::getMessage('IB_TYPE_NAME_content_multiverse'),
                'SECTION_NAME' => Loc::getMessage('IB_TYPE_SECTION_NAME'),
                'ELEMENT_NAME' => Loc::getMessage('IB_TYPE_ELEMENT_NAME'),
            ],
        ],
    ]
);
$iblockInstaller->setCombinedListMode();
