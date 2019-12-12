<?php

namespace RRZE\CMSinfo;

defined('ABSPATH') || exit;

/**
 * Shortcode
 */
class Shortcode
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
     * Er wird ausgeführt, sobald die Klasse instanziiert wird.
     * @return void
     */
    public function onLoaded()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
        add_action('activated_plugin', [$this, 'storePluginsImageData']);
        add_action('deactivated_plugin', [$this, 'storePluginsImageData']);
        add_shortcode('cmsinfo_themes', [$this, 'themesShortcode'], 10, 2);
        add_shortcode('cmsinfo_plugins', [$this, 'pluginsShortcode'], 10, 2);
    }

    /**
     * Enqueue der Skripte.
     */
    public function enqueueScripts()
    {
        wp_register_style('themes-shortcode', plugins_url('css/themes-shortcode.css', plugin_basename($this->pluginFile)));
        wp_register_script('themes-shortcode', plugins_url('js/themes-shortcode.js', plugin_basename($this->pluginFile)));

        wp_register_style('plugins-shortcode', plugins_url('css/plugins-shortcode.css', plugin_basename($this->pluginFile)));
    }

    /**
     * Generieren Sie die Shortcode-Ausgabe
     * @param  array   $atts Shortcode-Attribute
     * @param  string  $content Beiliegender Inhalt
     * @return string Gib den Inhalt zurück
     */
    public function themesShortcode($atts, $content = '')
    {
        $shortcode_atts = shortcode_atts([
            'thumbnail' => 'true',
            'theme' => '',
        ], $atts);

        $display_thumb = $shortcode_atts['thumbnail'] == 'true' ? true : false;

        // Alle Themes holen, die netzwerkweit aktiv sind.
        $themes = wp_get_themes(['allowed' => 'network']);

        if (empty($themes)) {
            return '';
        }

        $markup = '';

        // Prüfen, ob wir die Ansicht für ein Theme anzeigen sollen.
        if ($shortcode_atts['theme'] !== '') {
            $wanted_theme = $shortcode_atts['theme'];
            // Das Theme suchen, das wir anzeigen sollen.
            foreach ($themes as $theme) {
                // User sollen den Theme-Namen und Slug angeben können, wir prüfen also auf beides.
                if ($wanted_theme !== $theme->get_stylesheet_directory() && $wanted_theme !== $theme->get('Name')) {
                    continue;
                }

                $extra_links_markup = $this->getExtraLinksList($theme);

                $markup = sprintf(
                    '<div class="single-theme-details %6$s">
                        <h2 class="theme-title">%1$s</h2>
                        %2$s
                        <p class="theme-version">%3$s</p>
                        %5$s
                        %4$s
                        %7$s
                    </div>',
                    $theme->get('Name'),
                    $display_thumb && $theme->get_screenshot() ? sprintf(
                        '<figure class="theme-screenshot">
                            <img src="%s">
                        </figure>',
                        $theme->get_screenshot()
                    ) : '',
                    sprintf( /* translators: s=theme name */
                        _x('Version: %s', 'theme version', 'rrze-cmsinfo'),
                        $theme->get('Version')
                    ),
                    $theme->get('Description') !== '' && $theme->get('Description') !== false ? sprintf(
                        '<p class="theme-description">%s</p>',
                        $theme->get('Description')
                    ) : '',
                    $theme->get('Author') !== '' && $theme->get('Author') !== false ? sprintf(
                        '<p class="theme-author">%s</p>',
                        sprintf( /* translators: s=theme author */
                            _x('by %s', 'theme author', 'rrze-cmsinfo'),
                            $theme->get('AuthorURI') !== '' && $theme->get('AuthorURI') !== false ? sprintf(
                                '<a href="%s">%s</a>',
                                $theme->get('AuthorURI'),
                                $theme->get('Author')
                            ) : $theme->get('Author')
                        )
                    ) : '',
                    $display_thumb && $theme->get_screenshot() ? ' has-thumbnail' : ' no-thumbnail',
                    $extra_links_markup
                );
            }

            if ($markup === '') {
                return '';
            }
        }

        wp_enqueue_style('themes-shortcode');
        wp_enqueue_script('themes-shortcode');

        // Wenn $markup nicht leer ist, war ein einzelnes Theme gefragt.
        // Wir können die Funktion also schon hier beenden und das Markup zurückgeben.
        if ($markup !== '') {
            return $markup;
        }

        $themes_list_markup = '';
        $counter = 1;
        foreach ($themes as $theme) {
            $extra_links_markup = $this->getExtraLinksList($theme);
            $themes_list_markup .= sprintf(
                '<div class="theme">
                    <h3 class="theme-title">%1$s</h3>
                    %2$s
                    <button class="show-theme-details" data-micromodal-trigger="theme-modal-%6$d">%9$s</button>
                    <div class="theme-info-overlay" id="theme-modal-%6$d" aria-hidden="true">
                        <div tabindex="-1" data-micromodal-close>
                            <div role="dialog" aria-modal="true" aria-label="%7$s">
                                <div class="theme-info-overlay-content %10$s">
                                    <button class="close-theme-details" data-micromodal-close><svg viewBox="0 0 16 16"><use xlink:href="#close-theme-details"></use></svg><span class="screen-reader-text">%8$s</button>
                                    <h3 class="theme-title">%1$s</h3>
                                    %2$s
                                    <p class="theme-version">%3$s</p>
                                    %5$s
                                    %4$s
                                    %11$s
                                </div>
                            </div>
                        </div>
                    </div>
                </div>',
                $theme->get('Name'),
                $display_thumb && $theme->get_screenshot() ? sprintf(
                    '<figure class="theme-screenshot">
                        <img src="%s">
                    </figure>',
                    $theme->get_screenshot()
                ) : '',
                $theme->get('Version'),
                $theme->get('Description') !== '' && $theme->get('Description') !== false ? sprintf(
                    '<p class="theme-description">%s</p>',
                    $theme->get('Description')
				) : '',
				$theme->get('Author') !== '' && $theme->get('Author') !== false ? sprintf(
                    '<p class="theme-author">%s</p>',
                    sprintf( /* translators: s=theme author */
                        _x('by %s', 'theme author', 'rrze-cmsinfo'),
                        $theme->get('AuthorURI') !== '' && $theme->get('AuthorURI') !== false ? sprintf(
                            '<a href="%s">%s</a>',
                            $theme->get('AuthorURI'),
                            $theme->get('Author')
                        ) : $theme->get('Author')
                    )
                ) : '',
                $counter,
                sprintf(
                    __('Details zu %s', 'rrze-cmsinfo'),
                    $theme->get('Name')
                ),
                __('Overlay schließen', 'rrze-cmsinfo'),
                __('Details anzeigen', 'rrze-cmsinfo'),
                $display_thumb && $theme->get_screenshot() ? ' has-thumbnail' : ' no-thumbnail',
                $extra_links_markup
            );

            $counter++;
        }

        if ($themes_list_markup !== '') {
            $markup = sprintf(
                '<div class="themes-list">
                    %s
                    %s
                </div>',
                '<svg class="themes-shortcode-svg-sprite" hidden viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg"><symbol viewBox="0 0 16 16" id="close-theme-details"><polygon points="12.7,4.7 11.3,3.3 8,6.6 4.7,3.3 3.3,4.7 6.6,8 3.3,11.3 4.7,12.7 8,9.4 11.3,12.7 12.7,11.3 9.4,8 "/></symbol></svg>',
                $themes_list_markup
            );
        }

        return $markup;
    }

    /**
     * Get additional links from Theme headers and return as list.
     *
     * @param \WP_Theme $theme WP_Theme object.
     *
     * @return string
     */
    protected function getExtraLinksList($theme) {
        if (!$theme instanceof \WP_Theme) {
            return '';
        }

        $headers = get_file_data(trailingslashit($theme->get_stylesheet_directory()) . 'style.css', [
            'Name'        => 'Theme Name',
            'ThemeURI'    => 'Theme URI',
            'Description' => 'Description',
            'Author'      => 'Author',
            'AuthorURI'   => 'Author URI',
            'Version'     => 'Version',
            'GitHubThemeURI' => 'GitHub Theme URI',
        ]);

        $list_markup = '';

        $theme_uri = $headers['ThemeURI'];
        $github_theme_uri = $headers['GitHubThemeURI'];;

        if ($github_theme_uri !== '' && $github_theme_uri !== false) {
            $list_markup .= sprintf(
                '<li><a href="%s">%s</a></li>',
                esc_url($github_theme_uri),
                __('Theme on GitHub', 'rrze-cmsinfo')
            );
        }

        if ($theme_uri !== '' && $theme_uri !== false) {
            $list_markup .= sprintf(
                '<li><a href="%s">%s</a></li>',
                esc_url($theme_uri),
                __('Theme URI', 'rrze-cmsinfo')
            );
        }

        if ($list_markup === '') {
            return '';
        }

        return sprintf(
            '<ul class="extra-theme-links">%s</ul>',
            $list_markup
        );
    }

    /**
     * Generieren Sie die Shortcode-Ausgabe
     * @param  array   $atts Shortcode-Attribute
     * @param  string  $content Beiliegender Inhalt
     * @return string Gib den Inhalt zurück
     */
    public function pluginsShortcode($atts, $content = '')
    {
        $shortcode_atts = shortcode_atts([
            'images' => 'false'
        ], $atts);

        $images = $shortcode_atts['images'] === 'true' ? true : false;

        $output = '';

        $active_plugins = $this->getActivePlugins();

        if ($active_plugins === false) {
            return '';
        }

        if ($images) {
            // Bilddaten holen.
            $image_data = $this->getPluginsImageData();
        }

        foreach ($active_plugins as $basename => $active_plugin) {
            $meta_values = [];
            if ($active_plugin['Version'] !== '') {
                array_push($meta_values, sprintf( /* translators: s=plugin name */
                    _x('Version: %s', 'plugin version', 'rrze-cmsinfo'),
                    $active_plugin['Version']
                ));
            }

            if ($active_plugin['Author'] !== '') {
                array_push($meta_values, sprintf( /* translators: s=plugin author */
                    _x('by %s', 'plugin author', 'rrze-cmsinfo'),
                    $active_plugin['AuthorURI'] !== '' ? sprintf(
                        '<a href="%s">%s</a>',
                        $active_plugin['AuthorURI'],
                        $active_plugin['Author']
                    ) : $active_plugin['Author']
                ));
            }

            $meta_string = '';
            if (!empty($meta_values)) {
                $meta_string = sprintf(
                    '<p class="plugin-meta">%s</p>',
                    implode(' | ', $meta_values)
                );
            }
            $output .= sprintf(
                '<div class="plugin %s">
                <div class="plugin-title-and-meta">
                    <h3 class="plugin-title">%s</h3>
                    %s
                </div>
                %s
                %s
                </div>',
                $images === true && empty($image_data[$basename]['icon']) ? 'no-icon' : '',
                $active_plugin['Name'],
                $meta_string,
                $images === true && isset($image_data[$basename]) ? sprintf(
                    '%s
                    %s',
                    $image_data[$basename]['banner'] !== '' && isset($image_data[$basename]['banner']['low']) ? sprintf(
                        '<figure class="plugin-banner"><img src="%s"></figure>',
                        $image_data[$basename]['banner']['low']
                    ) : '',
                    $image_data[$basename]['icon'] !== '' && $image_data[$basename]['icon']['1x'] !== false ? sprintf(
                        '<figure class="plugin-icon"><img src="%s"></figure>',
                        $image_data[$basename]['icon']['1x']
                    ) : ''
                ) : '',
                $active_plugin['Description'] !== '' ? sprintf(
                    '<p class="plugin-description">%s</p>',
                    $active_plugin['Description']
                ) : ''
            );
        }

        if ($output === '') {
            return '';
        }

        wp_enqueue_style('plugins-shortcode');

        return sprintf(
            '<div class="plugins-list %s">%s</div>',
            $images === true ? 'with-images' : '',
            $output
        );
    }

    /**
     * Gibt ein Array mit den aktiven Plugins zurück.
     *
     * @return array
     */
    protected function getActivePlugins()
    {
        $active_plugins = get_option('active_plugins');
        $plugins = get_plugins();

        if (!is_array($active_plugins) || empty($active_plugins) || !is_array($plugins) || empty($plugins)) {
            return false;
        }

        $tmp = [];
        foreach ($active_plugins as $active_plugin) {
            $tmp[$active_plugin] = $active_plugin;
        }

        $active_plugins = array_intersect_key($plugins, $tmp);

        if (empty($active_plugins) || !is_array($active_plugins)) {
            return false;
        }

        return $active_plugins;
    }

    /**
     * Holt die Bilddaten (URLs zu Icons und Bannern) zu den aktiven Plugins.
     *
     * @return array
     */
    protected function getPluginsImageData()
    {
        // Prüfen, ob der Transient mit den Bilddaten existiert. Sonst müssen wir die Bilder holen und den Transient neu anlegen.
        $image_data = get_transient('rrze-cmsinfo-plugins-image-data');
        if ($image_data === false) {
            // Die Bilddaten in Transient speichern.
            $this->storePluginsImageData();
            $image_data = get_transient('rrze-cmsinfo-plugins-image-data');
        }

        return $image_data;
    }

    /**
     * Speichert die Bilddaten der aktiven Plugins in einem Transient.
     */
    public function storePluginsImageData()
    {
        $active_plugins = $this->getActivePlugins();
        $image_data = [];
        foreach ($active_plugins as $key => $plugin) {
            $slug = dirname($key);
            $request = wp_safe_remote_get("https://api.wordpress.org/plugins/info/1.2/?action=plugin_information&request[slug]=$slug&request[fields][icons]=1&request[fields][banners]=1&request[fields][sections]=0");

            if (is_wp_error($request)) {
                continue;
            }

            $body = wp_remote_retrieve_body($request);
            $body = json_decode($body);

            if (isset($body->error)) {
                continue;
            }

            $image_data[$key] = [
                'banner' => isset($body->banners) ? (array) $body->banners : '',
                'icon' => isset($body->icons) ? (array) $body->icons : '',
            ];
        }

        set_transient('rrze-cmsinfo-plugins-image-data', $image_data);
    }
}
