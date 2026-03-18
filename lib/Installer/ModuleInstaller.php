<?php

namespace Vsech\Multiverse\Installer;

use Bitrix\Main\EventManager;
use Bitrix\Main\ModuleManager;

final class ModuleInstaller
{
    public const MODULE_ID = 'vsech.multiverse';
    private const EVENT_FROM_MODULE = 'main';
    private const EVENT_NAME = 'OnBeforeProlog';
    private const EVENT_CLASS = 'CSiteCorporate';
    private const EVENT_METHOD = 'ShowPanel';

    public function install(): void
    {
        ModuleManager::registerModule(self::MODULE_ID);
        EventManager::getInstance()->registerEventHandler(
            self::EVENT_FROM_MODULE,
            self::EVENT_NAME,
            self::MODULE_ID,
            self::EVENT_CLASS,
            self::EVENT_METHOD
        );
    }

    public function uninstall(): void
    {
        EventManager::getInstance()->unRegisterEventHandler(
            self::EVENT_FROM_MODULE,
            self::EVENT_NAME,
            self::MODULE_ID,
            self::EVENT_CLASS,
            self::EVENT_METHOD
        );
        ModuleManager::unRegisterModule(self::MODULE_ID);
    }
}
