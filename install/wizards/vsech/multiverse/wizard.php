<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/install/wizard_sol/wizard.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/vsech.multiverse/include.php';

class SelectSiteStep extends CSelectSiteWizardStep
{
    public function InitStep()
    {
        parent::InitStep();

        $wizard = $this->GetWizard();
        $wizard->solutionName = 'multiverse';

        $this->SetNextStep('site_settings');
    }
}

class SiteSettingsStep extends CSiteSettingsWizardStep
{
    public function InitStep()
    {
        $wizard = $this->GetWizard();
        $wizard->solutionName = 'multiverse';
        parent::InitStep();

        if (!$wizard->GetVar('templateID')) {
            $wizard->SetDefaultVar('templateID', 'multiverse');
        }

        if ($wizard->GetVar('multiverse_themeID') === null) {
            $wizard->SetDefaultVar('multiverse_themeID', '');
        }

        $this->SetTitle(Loc::getMessage('wiz_settings'));
        $this->SetNextStep('data_install');
        $this->SetNextCaption(Loc::getMessage('wiz_install'));

        global $USER;

        $wizard->SetDefaultVars(
            [
                'siteName' => Loc::getMessage('wiz_name'),
                'siteDescription' => Loc::getMessage('wiz_slogan'),
                'siteSeoTitle' => Loc::getMessage('wiz_name'),
                'siteSeoDescription' => Loc::getMessage('wiz_slogan'),
                'siteSeoKeywords' => Loc::getMessage('wiz_keywords'),
                'admins_e_mail' => $USER->GetParam('EMAIL'),
            ]
        );
    }

    public function ShowStep()
    {
        $wizard = $this->GetWizard();
        $templateId = (string)($wizard->GetVar('templateID', true) ?: 'multiverse');
        $themeId = (string)($wizard->GetVar('multiverse_themeID', true) ?: '');

        $this->content .= '<div class="wizard-input-form">';
        $this->content .= $this->ShowHiddenField('templateID', $templateId);
        $this->content .= $this->ShowHiddenField('multiverse_themeID', $themeId);
        $this->content .= '
        <div class="wizard-upload-img-block">
            <div class="wizard-catalog-title">' . Loc::getMessage('wiz_company_name') . '</div>
            ' . $this->ShowInputField('text', 'siteName', ['id' => 'siteName', 'class' => 'wizard-field']) . '
        </div>';
        $this->content .= '
        <div class="wizard-upload-img-block">
            <div class="wizard-catalog-title">' . Loc::getMessage('wiz_company_description') . '</div>
            ' . $this->ShowInputField('text', 'siteDescription', ['id' => 'siteDescription', 'class' => 'wizard-field']) . '
        </div>';
        $this->content .= '
        <div class="wizard-upload-img-block">
            <div class="wizard-catalog-title">' . Loc::getMessage('wiz_company_email') . '</div>
            ' . $this->ShowInputField('text', 'admins_e_mail', ['id' => 'admins_e_mail', 'class' => 'wizard-field']) . '
        </div>';
        $this->content .= '
        <div class="wizard-metadata-title">' . Loc::getMessage('wiz_seo') . '</div>
        <div class="wizard-upload-img-block">
            <label for="siteMetaTitle" class="wizard-input-title">' . Loc::getMessage('wiz_seo_title') . '</label>
            ' . $this->ShowInputField('text', 'siteSeoTitle', ['id' => 'siteSeoTitle', 'class' => 'wizard-field']) . '
        </div><div class="wizard-upload-img-block">
            <label for="siteMetaDescription" class="wizard-input-title">' . Loc::getMessage('wiz_seo_description') . '</label>
            ' . $this->ShowInputField('text', 'siteSeoDescription', ['id' => 'siteSeoDescription', 'class' => 'wizard-field']) . '
        </div><div class="wizard-upload-img-block">
            <label for="siteMetaKeywords" class="wizard-input-title">' . Loc::getMessage('wiz_seo_keywords') . '</label>
            ' . $this->ShowInputField('text', 'siteSeoKeywords', ['id' => 'siteSeoKeywords', 'class' => 'wizard-field']) . '
        </div>';
    }
}

class DataInstallStep extends CDataInstallWizardStep
{
}

class FinishStep extends CFinishWizardStep
{
    public function InitStep()
    {
        $this->SetStepID('finish');
        $this->SetNextStep('finish');
        $this->SetTitle(Loc::getMessage('FINISH_STEP_TITLE'));
        $this->SetNextCaption(Loc::getMessage('wiz_go'));
    }

    public function ShowStep()
    {
        global $USER;

        $wizard = $this->GetWizard();
        $siteId = WizardServices::GetCurrentSiteID($wizard->GetVar('siteID'));
        $siteService = new \Vsech\Multiverse\Wizard\SiteService();

        if ($siteId !== '' && is_object($USER) && method_exists($USER, 'GetEmail')) {
            $siteService->updateSite(
                $siteId,
                [
                    'EMAIL' => $USER->GetEmail(),
                    'NAME' => Loc::getMessage('wiz_site_name'),
                    'SERVER_NAME' => $this->getSiteUrl(),
                ]
            );
        }

        $siteDir = $siteService->getSiteDirectory($siteId, SITE_DIR);
        $wizard->SetFormActionScript(str_replace('//', '/', $siteDir . '/?finish'));

        $this->CreateNewIndex();
        $siteService->rememberWizardSolution($wizard->solutionName, $siteId);

        if ($wizard->GetVar('installDemoData') === 'Y') {
            $this->content .= Loc::getMessage('FINISH_STEP_REINDEX');
        }
    }

    private function getSiteUrl(): string
    {
        $host = (string)($_SERVER['HTTP_HOST'] ?? getenv('HTTP_HOST') ?? '');
        if ($host === '') {
            return '';
        }

        $host = preg_replace('/^https?:\/\//i', '', $host);
        if ($host === null || $host === '') {
            return '';
        }

        return rtrim($host, '/');
    }
}
