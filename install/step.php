<?php

use Bitrix\Main\Localization\Loc;

if (!check_bitrix_sessid()) {
    return;
}

Loc::loadMessages(__FILE__);

echo CAdminMessage::ShowNote(Loc::getMessage('VSECH_MULTIVERSE_STEP_OK'));
?>
<form action="<?= $APPLICATION->GetCurPage(); ?>">
    <input type="hidden" name="lang" value="<?= LANG; ?>">
    <input type="submit" value="<?= Loc::getMessage('VSECH_MULTIVERSE_STEP_BACK'); ?>">
</form>
