<?php

namespace Vsech\Multiverse\Wizard;

final class FileDeployer
{
    public function copyPublicFiles(string $sourcePath, string $sitePath, string $siteId): void
    {
        $handle = @opendir($sourcePath);
        if ($handle === false) {
            return;
        }

        while (($file = readdir($handle)) !== false) {
            if ($this->shouldSkip($file)) {
                continue;
            }

            $targetPath = $this->resolveTargetPath($file, $sitePath, $siteId);
            CopyDirFiles($sourcePath . $file, $targetPath, true, true, false);
        }

        closedir($handle);
    }

    public function copyTemplate(string $wizardRelativePath, string $templateId): void
    {
        $bitrixTemplateDir = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/templates/' . $templateId;
        $templateSourcePath = $_SERVER['DOCUMENT_ROOT']
            . WizardServices::GetTemplatesPath($wizardRelativePath . '/site')
            . '/'
            . $templateId;

        CopyDirFiles($templateSourcePath, $bitrixTemplateDir, true, true, false);
    }

    public function copyFavicon(string $themeAbsolutePath, string $sitePath): void
    {
        copy($themeAbsolutePath . '/favicon.ico', $sitePath . 'favicon.ico');
    }

    public function replaceIndexMacros(string $sitePath, array $macros): void
    {
        CWizardUtil::ReplaceMacros($sitePath . '/_index.php', $macros);
    }

    private function shouldSkip(string $file): bool
    {
        return in_array(
            $file,
            [
                '.',
                '..',
                'bitrix_messages',
                'bitrix_admin',
                'bitrix_php_interface',
                'bitrix_js',
                'bitrix_images',
                'bitrix_themes',
            ],
            true
        );
    }

    private function resolveTargetPath(string $file, string $sitePath, string $siteId): string
    {
        if ($file === 'bitrix_php_interface_init') {
            return $_SERVER['DOCUMENT_ROOT'] . '/bitrix/php_interface/' . $siteId;
        }

        if ($file === 'upload') {
            return $_SERVER['DOCUMENT_ROOT'] . '/upload/';
        }

        return $sitePath . '/' . $file;
    }
}
