<?php

use Bitrix\Main\Application;
use Bitrix\Main\EventManager;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Vsech\Multiverse\SiteCorporate;

Loc::loadMessages(__FILE__);

class vsech_multiverse extends CModule
{
    public $MODULE_ID = 'vsech.multiverse';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME;
    public $PARTNER_URI;
    public $MODULE_GROUP_RIGHTS = 'Y';

    public function __construct()
    {
        $arModuleVersion = [];

        include __DIR__ . '/version.php';

        if (!empty($arModuleVersion['VERSION'])) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        }

        if (!empty($arModuleVersion['VERSION_DATE'])) {
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_NAME = Loc::getMessage('VSECH_INSTALL_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('VSECH_INSTALL_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('VSECH_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('VSECH_PARTNER_URI');
    }

    public function installDB(array $installConfig = [])
    {
        ModuleManager::registerModule($this->MODULE_ID);

        EventManager::getInstance()->registerEventHandler(
            'main',
            'OnBeforeProlog',
            $this->MODULE_ID,
            SiteCorporate::class,
            'showPanel'
        );

        return true;
    }

    public function uninstallDB(array $arParams = [])
    {
        EventManager::getInstance()->unRegisterEventHandler(
            'main',
            'OnBeforeProlog',
            $this->MODULE_ID,
            SiteCorporate::class,
            'showPanel'
        );

        ModuleManager::unRegisterModule($this->MODULE_ID);

        return true;
    }

    public function installEvents()
    {
        return true;
    }

    public function uninstallEvents()
    {
        return true;
    }

    public function installFiles()
    {
        $sourceDirectory = __DIR__ . '/wizards';
        $targetDirectory = Application::getDocumentRoot() . '/bitrix/wizards';

        if (Directory::isDirectoryExists($sourceDirectory)) {
            CopyDirFiles($sourceDirectory, $targetDirectory, true, true, false);
        }

        return true;
    }

    public function uninstallFiles()
    {
        $wizardDirectory = Application::getDocumentRoot() . '/bitrix/wizards/vsech/multiverse';

        if (Directory::isDirectoryExists($wizardDirectory)) {
            Directory::deleteDirectory($wizardDirectory);

            $vendorDirectory = dirname($wizardDirectory);
            if (Directory::isDirectoryExists($vendorDirectory)) {
                $vendor = new Directory($vendorDirectory);
                if (empty($vendor->getChildren())) {
                    Directory::deleteDirectory($vendorDirectory);
                }
            }
        }

        return true;
    }

    public function installPublic()
    {
    }

    public function doInstall()
    {
        global $APPLICATION;

        $this->installFiles();
        $this->installDB();
        $this->installEvents();
        $this->installPublic();

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('VSECH_INSTALL_TITLE'),
            Application::getDocumentRoot() . '/bitrix/modules/' . $this->MODULE_ID . '/install/step.php'
        );
    }

    public function doUninstall()
    {
        global $APPLICATION;

        $this->uninstallDB();
        $this->uninstallFiles();
        $this->uninstallEvents();

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('VSECH_UNINSTALL_TITLE'),
            Application::getDocumentRoot() . '/bitrix/modules/' . $this->MODULE_ID . '/install/unstep.php'
        );
    }
}
