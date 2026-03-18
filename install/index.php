<?php

use Bitrix\Main\Localization\Loc;
use Vsech\Multiverse\Installer\ModuleInstaller;

Loc::loadMessages(__FILE__);
require_once __DIR__ . '/../include.php';

class vsech_multiverse extends CModule
{
    public $MODULE_ID = 'vsech.multiverse';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_CSS;
    public $MODULE_GROUP_RIGHTS = 'Y';

    /** @var ModuleInstaller */
    private $moduleInstaller;

    public function __construct()
    {
        $arModuleVersion = [];

        include __DIR__ . '/version.php';

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage('VSECH_INSTALL_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('VSECH_INSTALL_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('SPER_PARTNER');
        $this->PARTNER_URI = Loc::getMessage('PARTNER_URI');
        $this->moduleInstaller = new ModuleInstaller();
    }

    public function InstallDB($installWizard = true): bool
    {
        $this->moduleInstaller->install();

        return true;
    }

    public function UnInstallDB($arParams = []): bool
    {
        $this->moduleInstaller->uninstall();

        return true;
    }

    public function InstallEvents(): bool
    {
        return true;
    }

    public function UnInstallEvents(): bool
    {
        return true;
    }

    public function InstallFiles(): bool
    {
        return true;
    }

    public function InstallPublic(): void
    {
    }

    public function UnInstallFiles(): bool
    {
        return true;
    }

    public function DoInstall(): void
    {
        global $APPLICATION;

        $this->InstallFiles();
        $this->InstallDB(false);
        $this->InstallEvents();
        $this->InstallPublic();

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('VSECH_INSTALL_TITLE'),
            $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/vsech.multiverse/install/step.php'
        );
    }

    public function DoUninstall(): void
    {
        global $APPLICATION;

        $this->UnInstallDB();
        $this->UnInstallFiles();
        $this->UnInstallEvents();

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('VSECH_UNINSTALL_TITLE'),
            $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/vsech.multiverse/install/unstep.php'
        );
    }
}
