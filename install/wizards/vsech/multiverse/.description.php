<?php

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

Loc::loadMessages(__FILE__);

if (!defined('WIZARD_DEFAULT_SITE_ID') && !empty($_REQUEST['wizardSiteID'])) {
    define('WIZARD_DEFAULT_SITE_ID', $_REQUEST['wizardSiteID']);
}

$arWizardDescription = [
    'NAME' => Loc::getMessage('VSECH_WIZARD_NAME'),
    'DESCRIPTION' => Loc::getMessage('VSECH_WIZARD_DESC'),
    'VERSION' => '1.1.0',
    'START_TYPE' => 'WINDOW',
    'WIZARD_TYPE' => 'INSTALL',
    'IMAGE' => '/images/' . LANGUAGE_ID . '/solution.gif',
    'PARENT' => 'wizard_sol',
    'TEMPLATES' => [
        ['SCRIPT' => 'wizard_sol'],
    ],
    'STEPS' => [],
];

$arWizardDescription['STEPS'] = defined('WIZARD_DEFAULT_SITE_ID')
    ? [
        'PersonType',
        'PaySystem',
        'CatalogStep',
        'SiteSettingsStep',
        'DataInstallStep',
        'FinishStep',
    ]
    : [
        'SelectSiteStep',
        'SiteSettingsStep',
        'PersonType',
        'PaySystem',
        'CatalogStep',
        'DataInstallStep',
        'FinishStep',
    ];
