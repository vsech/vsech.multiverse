<?php

namespace Vsech\Multiverse\Wizard;

use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Config\Option;
use Bitrix\Main\GroupTable;
use Bitrix\Main\Loader;

final class IblockInstaller
{
    public function includeModule(): bool
    {
        return Loader::includeModule('iblock');
    }

    public function isLandingWizardInstalled(string $siteId): bool
    {
        return Option::get('landing', 'wizard_installed', 'N', $siteId) === 'Y';
    }

    public function setCombinedListMode(): void
    {
        Option::set('iblock', 'combined_list_mode', 'Y');
    }

    public function ensureType(array $typeDefinition): void
    {
        $typeId = (string)$typeDefinition['ID'];
        $typeResult = \CIBlockType::GetList([], ['ID' => $typeId]);

        if ($typeResult->Fetch()) {
            return;
        }

        $iblockType = new \CIBlockType();
        $iblockType->Add($typeDefinition);
    }

    public function findIblockId(string $iblockCode, string $iblockType): ?int
    {
        $iblock = IblockTable::getList(
            [
                'filter' => [
                    '=CODE' => $iblockCode,
                    '=IBLOCK_TYPE_ID' => $iblockType,
                ],
                'select' => ['ID'],
                'limit' => 1,
            ]
        )->fetch();

        if (!$iblock) {
            return null;
        }

        return (int)$iblock['ID'];
    }

    public function deleteIblock(int $iblockId): void
    {
        \CIBlock::Delete($iblockId);
    }

    public function getImportPermissions(string $groupCode): array
    {
        $permissions = [
            '1' => 'X',
            '2' => 'R',
        ];

        $group = GroupTable::getList(
            [
                'filter' => ['=STRING_ID' => $groupCode],
                'select' => ['ID'],
                'limit' => 1,
            ]
        )->fetch();

        if ($group) {
            $permissions[(string)$group['ID']] = 'W';
        }

        return $permissions;
    }

    public function importFromXml(
        string $xmlFile,
        string $iblockCode,
        string $iblockType,
        string $siteId,
        array $permissions
    ): int {
        return (int)\WizardServices::ImportIBlockFromXML(
            $xmlFile,
            $iblockCode,
            $iblockType,
            $siteId,
            $permissions
        );
    }

    public function updateImportedIblock(int $iblockId, string $iblockCode): void
    {
        $iblock = new \CIBlock();
        $iblock->Update(
            $iblockId,
            [
                'ACTIVE' => 'Y',
                'FIELDS' => [
                    'IBLOCK_SECTION' => ['IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => ''],
                    'ACTIVE' => ['IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => 'Y'],
                    'ACTIVE_FROM' => ['IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '=today'],
                    'ACTIVE_TO' => ['IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => ''],
                    'SORT' => ['IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => ''],
                    'NAME' => ['IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => ''],
                    'PREVIEW_PICTURE' => [
                        'IS_REQUIRED' => 'N',
                        'DEFAULT_VALUE' => [
                            'FROM_DETAIL' => 'N',
                            'SCALE' => 'N',
                            'WIDTH' => '',
                            'HEIGHT' => '',
                            'IGNORE_ERRORS' => 'N',
                        ],
                    ],
                    'PREVIEW_TEXT_TYPE' => ['IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => 'text'],
                    'PREVIEW_TEXT' => ['IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => ''],
                    'DETAIL_PICTURE' => [
                        'IS_REQUIRED' => 'N',
                        'DEFAULT_VALUE' => [
                            'SCALE' => 'N',
                            'WIDTH' => '',
                            'HEIGHT' => '',
                            'IGNORE_ERRORS' => 'N',
                        ],
                    ],
                    'DETAIL_TEXT_TYPE' => ['IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => 'text'],
                    'DETAIL_TEXT' => ['IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => ''],
                    'XML_ID' => ['IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => ''],
                    'CODE' => ['IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => ''],
                    'TAGS' => ['IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => ''],
                ],
                'CODE' => $iblockCode,
                'XML_ID' => $iblockCode,
                'NAME' => (string)\CIBlock::GetArrayByID($iblockId, 'NAME'),
            ]
        );
    }

    public function saveElementFormSettings(int $iblockId, array $tabs): void
    {
        \CUserOptions::SetOption(
            'form',
            'form_element_' . $iblockId,
            ['tabs' => $this->buildTabsOptionValue($tabs)],
            true
        );
    }

    public function getPropertyTabs(int $iblockId): array
    {
        $propertyTabs = [];
        $propertyResult = \CIBlock::GetProperties($iblockId, [], ['!CODE' => false]);

        while ($property = $propertyResult->Fetch()) {
            $propertyTabs[] = ['PROPERTY_' . $property['ID'], $property['NAME']];
        }

        return $propertyTabs;
    }

    public function ensureSiteBinding(int $iblockId, string $siteId): void
    {
        $siteIds = [];
        $siteResult = \CIBlock::GetSite($iblockId);

        while ($site = $siteResult->Fetch()) {
            $siteIds[] = $site['LID'];
        }

        if (in_array($siteId, $siteIds, true)) {
            return;
        }

        $siteIds[] = $siteId;
        $iblock = new \CIBlock();
        $iblock->Update($iblockId, ['LID' => $siteIds]);
    }

    private function buildTabsOptionValue(array $tabs): string
    {
        $result = [];

        foreach ($tabs as $tab) {
            $items = [];

            foreach ($tab as $item) {
                $items[] = implode('--#--', $item);
            }

            $result[] = implode('--,--', $items);
        }

        return implode('--;--', $result);
    }
}
