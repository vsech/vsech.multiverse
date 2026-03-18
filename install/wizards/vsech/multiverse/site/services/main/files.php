<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!defined('WIZARD_SITE_ID') || !defined('WIZARD_SITE_DIR')) {
    return;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/vsech.multiverse/include.php';

$fileDeployer = new \Vsech\Multiverse\Wizard\FileDeployer();
$sourcePath = str_replace('//', '/', WIZARD_ABSOLUTE_PATH . '/site/public/' . LANGUAGE_ID . '/');
$templateId = defined('WIZARD_TEMPLATE_ID') && is_string(WIZARD_TEMPLATE_ID) && WIZARD_TEMPLATE_ID !== ''
    ? WIZARD_TEMPLATE_ID
    : (string)($wizard->GetVar('templateID', true) ?: 'multiverse');

$fileDeployer->copyPublicFiles($sourcePath, WIZARD_SITE_PATH, WIZARD_SITE_ID);
$fileDeployer->copyFavicon(WIZARD_THEME_ABSOLUTE_PATH, WIZARD_SITE_PATH);
$fileDeployer->replaceIndexMacros(
    WIZARD_SITE_PATH,
    [
        'SITE_SEO_TITLE' => htmlspecialcharsbx($wizard->GetVar('siteSeoTitle')),
        'SITE_SEO_DESCRIPTION' => htmlspecialcharsbx($wizard->GetVar('siteSeoDescription')),
        'SITE_SEO_KEYWORDS' => htmlspecialcharsbx($wizard->GetVar('siteSeoKeywords')),
        'SITE_TITLE' => htmlspecialcharsbx($wizard->GetVar('siteName')),
    ]
);
$fileDeployer->copyTemplate(WIZARD_RELATIVE_PATH, $templateId);
