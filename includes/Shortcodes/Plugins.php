<?php

namespace RRZE\CMSinfo\Shortcodes;

defined('ABSPATH') || exit;

use function RRZE\CMSinfo\plugin;

/**
 * Plugins Shortcode
 */
class Plugins
{
    /**
     * __construct
     */
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
        add_shortcode('cmsinfo_plugins', [$this, 'shortcode']);
    }

    /**
     * enqueueScripts
     */
    public function enqueueScripts()
    {
        wp_register_style(
            'cms-info-plugins',
            plugins_url('build/plugins.css', plugin()->getBasename()),
            [],
            plugin()->getVersion()
        );
    }

    /**
     * shortcode
     * @param  array $atts
     * @return string
     */
    public function shortcode($atts)
    {
        $atts = shortcode_atts([], $atts);

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
                    _x('Version: %s', 'plugin version', 'rrze-cmsinfo'),
                    $activePlugin['Version']
                ));
            }

            if ($activePlugin['Author'] !== '') {
                array_push($metaValues, sprintf( /* translators: s=plugin author */
                    _x('by %s', 'plugin author', 'rrze-cmsinfo'),
                    $activePlugin['Author']
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

        wp_enqueue_style('cms-info-plugins');

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
        $activePluginsOption = get_option('active_plugins');
        $allowedPlugins = get_plugins();

        if (!is_array($activePluginsOption) || empty($activePluginsOption) || !is_array($allowedPlugins) || empty($allowedPlugins)) {
            return false;
        }

        $activePlugins = [];
        foreach ($activePluginsOption as $plugin) {
            if (isset($allowedPlugins[$plugin])) {
                $activePlugins[$plugin] = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);
            }
        }

        if (empty($activePlugins) || !is_array($activePlugins)) {
            return false;
        }

        return $activePlugins;
    }
}
