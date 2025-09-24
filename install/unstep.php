<?php

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!check_bitrix_sessid()) {
    return;
}

global $APPLICATION;

Loc::loadMessages(__FILE__);

$exception = $APPLICATION->GetException();
?>
<form method="post" action="<?= $APPLICATION->GetCurPage(); ?>">
    <?= bitrix_sessid_post(); ?>
    <input type="hidden" name="lang" value="<?= htmlspecialcharsbx(LANGUAGE_ID); ?>">
    <input type="hidden" name="id" value="vsech.multiverse">
    <input type="hidden" name="uninstall" value="Y">
    <input type="hidden" name="step" value="2">
    <div class="adm-info-message-wrap">
        <?php if ($exception): ?>
            <div class="adm-info-message adm-info-message-red"><?= htmlspecialcharsbx($exception->GetString()); ?></div>
        <?php else: ?>
            <div class="adm-info-message"><?= Loc::getMessage('VSECH_MULTIVERSE_UNSTEP_OK'); ?></div>
        <?php endif; ?>
    </div>
    <div class="adm-detail-content-btns-wrap">
        <div class="adm-detail-content-btns">
            <input type="submit" class="adm-btn-save" value="<?= Loc::getMessage('VSECH_MULTIVERSE_STEP_BACK'); ?>">
        </div>
    </div>
</form>
