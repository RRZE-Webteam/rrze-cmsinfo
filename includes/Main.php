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
    /**
     * Der vollständige Pfad- und Dateiname der Plugin-Datei.
     * @var string
     */
    protected $pluginFile;

    /**
     * Variablen Werte zuweisen.
     * @param string $pluginFile Pfad- und Dateiname der Plugin-Datei
     */
    public function __construct($pluginFile)
    {
        $this->pluginFile = $pluginFile;
    }

    /**
     * Es wird ausgeführt, sobald die Klasse instanziiert wird.
     */
    public function onLoaded()
    {
        $shortcodeThemes = new Themes($this->pluginFile);
        $shortcodeThemes->onLoaded();

        $shortcodePlugins = new Plugins($this->pluginFile);
        $shortcodePlugins->onLoaded();
    }

}
