<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/vsech.multiverse/include.php';

$fileDeployer = new \Vsech\Multiverse\Wizard\FileDeployer();
$siteService = new \Vsech\Multiverse\Wizard\SiteService();
$templateId = defined('WIZARD_TEMPLATE_ID') && is_string(WIZARD_TEMPLATE_ID) && WIZARD_TEMPLATE_ID !== ''
    ? WIZARD_TEMPLATE_ID
    : (string)($wizard->GetVar('templateID', true) ?: 'multiverse');

$fileDeployer->copyTemplate(WIZARD_RELATIVE_PATH, $templateId);
$siteService->applyTemplate(WIZARD_SITE_ID, $templateId);
