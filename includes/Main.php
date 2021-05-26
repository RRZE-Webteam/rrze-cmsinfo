<?php

namespace RRZE\CMSinfo;

defined('ABSPATH') || exit;

use RRZE\CMSinfo\Shortcodes\Plugins;
use RRZE\CMSinfo\Shortcodes\Themes;

/**
 * Hauptklasse (Main)
 */
class Main
{
    public function __construct()
    {
        new Themes;
        new Plugins;
    }
}
