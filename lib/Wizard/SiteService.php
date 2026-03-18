<?php

namespace Vsech\Multiverse\Wizard;

use Bitrix\Main\Config\Option;
use Bitrix\Main\SiteTable;

final class SiteService
{
    public function applyTemplate(string $siteId, ?string $templateId): void
    {
        if ($templateId === null || $templateId === '') {
            return;
        }

        $site = SiteTable::getList(
            [
                'filter' => ['=LID' => $siteId],
                'select' => ['LID', 'NAME'],
                'limit' => 1,
            ]
        )->fetch();

        if (!$site) {
            return;
        }

        $siteApi = new \CSite();
        $siteApi->Update(
            $site['LID'],
            [
                'TEMPLATE' => [
                    [
                        'CONDITION' => '',
                        'SORT' => 1,
                        'TEMPLATE' => $templateId,
                    ],
                ],
                'NAME' => $site['NAME'],
            ]
        );

        Option::set('main', 'wizard_template_id', $templateId, $siteId);
    }

    public function updateSite(string $siteId, array $fields): void
    {
        if ($fields === []) {
            return;
        }

        SiteTable::update($siteId, $fields);
    }

    public function getSiteDirectory(string $siteId, string $defaultDirectory): string
    {
        $site = SiteTable::getList(
            [
                'filter' => ['=LID' => $siteId],
                'select' => ['DIR'],
                'limit' => 1,
            ]
        )->fetch();

        if (!$site || empty($site['DIR'])) {
            return $defaultDirectory;
        }

        return $site['DIR'];
    }

    public function rememberWizardSolution(string $solutionName, string $siteId): void
    {
        Option::set('main', 'wizard_solution', $solutionName, $siteId);
    }
}
