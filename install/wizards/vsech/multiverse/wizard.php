<?php

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SiteTable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/install/wizard_sol/wizard.php';

Loc::loadMessages(__FILE__);

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
        parent::InitStep();

        $wizard = $this->GetWizard();
        $wizard->solutionName = 'multiverse';

        $this->SetTitle(Loc::getMessage('wiz_settings'));
        $this->SetNextStep('data_install');
        $this->SetNextCaption(Loc::getMessage('wiz_install'));

        global $USER;
        $adminsMail = is_object($USER) && method_exists($USER, 'GetParam') ? $USER->GetParam('EMAIL') : '';

        $wizard->SetDefaultVars([
            'siteName' => Loc::getMessage('wiz_name'),
            'siteDescription' => Loc::getMessage('wiz_slogan'),
            'siteSeoTitle' => Loc::getMessage('wiz_name'),
            'siteSeoDescription' => Loc::getMessage('wiz_slogan'),
            'siteSeoKeywords' => Loc::getMessage('wiz_keywords'),
            'admins_e_mail' => $adminsMail,
        ]);
    }

    public function ShowStep()
    {
        $this->content .= '<div class="wizard-input-form">';
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
        $siteID = WizardServices::GetCurrentSiteID($wizard->GetVar('siteID'));

        if ($siteID && is_object($USER) && method_exists($USER, 'GetEmail')) {
            SiteTable::update($siteID, [
                'EMAIL' => $USER->GetEmail(),
                'NAME' => Loc::getMessage('wiz_site_name'),
                'SERVER_NAME' => $this->getSiteUrl(),
            ]);
        }

        $siteDir = SITE_DIR;
        if ($siteID) {
            $site = SiteTable::getByPrimary($siteID)->fetch();
            if (!empty($site['DIR'])) {
                $siteDir = $site['DIR'];
            }
        }

        $wizard->SetFormActionScript(str_replace('//', '/', $siteDir . '/?finish'));

        $this->CreateNewIndex();

        Option::set('main', 'wizard_solution', $wizard->solutionName, $siteID ?: '');

        if ($wizard->GetVar('installDemoData') === 'Y') {
            $this->content .= Loc::getMessage('FINISH_STEP_REINDEX');
        }
    }

    protected function getSiteUrl(): string
    {
        $context = Application::getInstance()->getContext();
        $server = $context->getServer();

        return $server->getHttpHost();
    }
}
