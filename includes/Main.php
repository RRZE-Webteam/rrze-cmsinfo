<?php

namespace RRZE\CMSinfo;

defined('ABSPATH') || exit;

use RRZE\CMSinfo\Shortcode;

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
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);

        // Shortcode-Klasse wird instanziiert.
        $shortcode = new Shortcode($this->pluginFile);
        $shortcode->onLoaded();
    }

    /**
     * Enqueue der globale Skripte.
     */
    public function enqueueScripts()
    {
        wp_register_style('cmsinfo', plugins_url('css/plugin.css', plugin_basename($this->pluginFile)));
    }
}
