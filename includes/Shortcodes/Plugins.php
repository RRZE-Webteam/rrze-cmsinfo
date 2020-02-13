<?php

namespace RRZE\CMSinfo\Shortcodes;

defined('ABSPATH') || exit;

/**
 * Plugins Shortcode
 */
class Plugins
{
    /**
     * Der vollständige Pfad- und Dateiname der Plugin-Datei.
     * @var string
     */
    protected $pluginFile;

    /**
     * [__construct description]
     * @param string $pluginFile [description]
     */
    public function __construct($pluginFile)
    {
        $this->pluginFile = $pluginFile;
    }

    /**
     * [onLoaded description]
     */
    public function onLoaded()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
        add_shortcode('cmsinfo_plugins', [$this, 'shortcode'], 10, 2);
    }

    /**
     * [enqueueScripts description]
     */
    public function enqueueScripts()
    {
        wp_register_style('plugins-shortcode', plugins_url('css/plugins-shortcode.css', plugin_basename($this->pluginFile)));
    }

    /**
     * [shortcode description]
     * @param  array $atts    [description]
     * @param  string $content [description]
     * @return string          [description]
     */
    public function shortcode($atts, $content = '')
    {
        $shortcode_atts = shortcode_atts([

        ], $atts);

        $output = '';

        $activePlugins = $this->getActivePlugins();

        if ($activePlugins === false) {
            return '';
        }

        $markupAry = [];
        foreach ($activePlugins as $basename => $activePlugin) {
            $metaValues = [];

            if ($activePlugin['Version'] !== '') {
                array_push($metaValues, sprintf( /* translators: s=plugin name */
                    _x('Version: %s', 'plugin version', 'cms-info'),
                    $activePlugin['Version']
                ));
            }

            if ($activePlugin['Author'] !== '') {
                array_push($metaValues, sprintf( /* translators: s=plugin author */
                    _x('by %s', 'plugin author', 'cms-info'),
                    $activePlugin['AuthorURI'] !== '' ? sprintf(
                        '<a href="%s">%s</a>',
                        $activePlugin['AuthorURI'],
                        $activePlugin['Author']
                    ) : $activePlugin['Author']
                ));
            }

            $metaString = '';
            if (!empty($metaValues)) {
                $metaString = sprintf(
                    '<p class="plugin-meta">%s</p>',
                    implode(' | ', $metaValues)
                );
            }

            $markupAry[] = sprintf(
                '<div class="plugin">
                    <div class="plugin-title-and-meta">
                        <h3 class="plugin-title">%s</h3>
                        %s
                    </div>
                    %s
                </div>',
                $activePlugin['Name'],
                $metaString,
                $activePlugin['Description'] !== '' ? sprintf(
                    '<p class="plugin-description">%s</p>',
                    $activePlugin['Description']
                ) : ''
            );
        }

        if (empty($markupAry)) {
            return '';
        }

        wp_enqueue_style('plugins-shortcode');

        $output = '';
        foreach ($markupAry as $markup) {
            $output .= sprintf(
                '<div class="card">
                    <div class="content">%s</div>
                </div>',
                $markup
            );
        }

        return sprintf(
            '<div class="plugins-list">
                %s
            </div>',
            $output
        );
    }

    /**
     * Gibt ein Array mit den aktiven Plugins zurück.
     * @return array|boolean [description]
     */
    protected function getActivePlugins()
    {
        $activePlugins = get_option('active_plugins');
        $plugins = get_plugins();

        if (!is_array($activePlugins) || empty($activePlugins) || !is_array($plugins) || empty($plugins)) {
            return false;
        }

        $tmp = [];
        foreach ($activePlugins as $activePlugin) {
            $tmp[$activePlugin] = get_plugin_data(WP_PLUGIN_DIR . '/' . $activePlugin);
        }

        $activePlugins = array_intersect_key($plugins, $tmp);

        if (empty($activePlugins) || !is_array($activePlugins)) {
            return false;
        }

        return $activePlugins;
    }

}
