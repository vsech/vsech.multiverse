<?php

namespace Vsech\Multiverse;

class SiteCorporate
{
    public static function showPanel(): void
    {
        global $APPLICATION;

        if (is_object($APPLICATION) && method_exists($APPLICATION, 'ShowPanel')) {
            $APPLICATION->ShowPanel();
        }
    }
}
