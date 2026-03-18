<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!defined('WIZARD_TEMPLATE_ID')) {
    return;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/vsech.multiverse/include.php';

$fileDeployer = new \Vsech\Multiverse\Wizard\FileDeployer();
$siteService = new \Vsech\Multiverse\Wizard\SiteService();

$fileDeployer->copyTemplate(WIZARD_RELATIVE_PATH, WIZARD_TEMPLATE_ID);
$siteService->applyTemplate(WIZARD_SITE_ID, WIZARD_TEMPLATE_ID);
