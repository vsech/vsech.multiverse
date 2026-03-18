<?php

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
    'vsech.multiverse',
    [
        'Vsech\\Multiverse\\Installer\\ModuleInstaller' => 'lib/Installer/ModuleInstaller.php',
        'Vsech\\Multiverse\\Wizard\\FileDeployer' => 'lib/Wizard/FileDeployer.php',
        'Vsech\\Multiverse\\Wizard\\IblockInstaller' => 'lib/Wizard/IblockInstaller.php',
        'Vsech\\Multiverse\\Wizard\\SiteService' => 'lib/Wizard/SiteService.php',
    ]
);
