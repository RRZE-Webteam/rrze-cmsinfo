<?php

namespace RRZE\CMSinfo\Shortcodes;

defined('ABSPATH') || exit;

use WP_Theme;

/**
 * Themes Shortcode
 */
class Themes
{
    /**
     * [protected description]
     * @var string
     */
    protected $pluginFile;

    /**
     * [__construct description]
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
        add_shortcode('cmsinfo_themes', [$this, 'shortcode'], 10, 2);
    }

    /**
     * [enqueueScripts description]
     */
    public function enqueueScripts()
    {
        wp_register_style('themes-shortcode', plugins_url('css/themes-shortcode.css', plugin_basename($this->pluginFile)));
    }

    /**
     * [shortcode description]
     * @param  array $atts    [description]
     * @param  string $content [description]
     * @return string          [description]
     */
    public function shortcode($atts, $content = '')
    {
        $shortcodeAtts = shortcode_atts([
            'thumbnail' => 'true',
            'theme' => '',
        ], $atts);

        // Alle Themes holen, die netzwerkweit aktiv sind.
        $themes = wp_get_themes(['allowed' => 'network']);

        if (empty($themes)) {
            return '';
        }

        wp_enqueue_style('themes-shortcode');

        $searchTheme = $shortcodeAtts['theme'] ? trim($shortcodeAtts['theme']) : '';

        if ($searchTheme !== '') {
            $themeSingle = null;
            foreach ($themes as $theme) {
                if ($searchTheme === $theme->get_stylesheet_directory() || $searchTheme === $theme->get('Name')) {
                    $themeSingle = $theme;
                    break;
                }
            }

            if (empty($themeSingle)) {
                return '';
            }

            $template =
            '<div class="theme-single">
                <div class="card">
                    %1$s
                    <div class="content">
                        <h3>%2$s</h3>
                        <p class="theme-version">%3$s</p>
                        %4$s
                        %5$s
                        %6$s
                    </div>
                </div>
            </div>';

            return $this->TemplateParser($themeSingle, $template, $shortcodeAtts);
        }

        $markupAry = [];
        foreach ($themes as $theme) {
            $template =
            '%1$s
            <div class="content">
                <h3>%2$s</h3>
                <p class="theme-version">%3$s</p>
                %4$s
                %5$s
                %6$s
            </div>';
            $markupAry[] = $this->TemplateParser($theme, $template, $shortcodeAtts);
        }

        if (empty($markupAry)) {
            return '';
        }

        $output = '';
        foreach ($markupAry as $markup) {
            $output .= sprintf(
                '<div class="card">
                    %s
                </div>',
                $markup
            );
        }

        return sprintf(
            '<div class="themes-list">
                %s
            </div>',
            $output
        );
    }

    protected function TemplateParser($theme, $template, $atts) {
        $thumbnail = $atts['thumbnail'] == 'true' ? true : false;

        return sprintf(
            $template,
            $thumbnail && $theme->get_screenshot() ? sprintf(
                '<div class="ptheme-screenshot">
                    <img src="%s">
                </div>',
                $theme->get_screenshot()
            ) : '',
            $theme->get('Name'),
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
            $this->getExtraLinksList($theme)
        );
    }

    /**
     * Get additional links from Theme headers and return as list.
     * @param $theme $theme WP_Theme object.
     * @return string Markup list
     */
    protected function getExtraLinksList($theme) {
        if (!$theme instanceof WP_Theme) {
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

        $markupList = '';

        $themeUri = $headers['ThemeURI'];
        $githubThemeUri = $headers['GitHubThemeURI'];;

        if ($githubThemeUri !== '' && $githubThemeUri !== false) {
            $markupList .= sprintf(
                '<li><a href="%s">%s</a></li>',
                esc_url($githubThemeUri),
                __('Theme on GitHub', 'rrze-cmsinfo')
            );
        }

        if ($themeUri !== '' && $themeUri !== false) {
            $markupList .= sprintf(
                '<li><a href="%s">%s</a></li>',
                esc_url($themeUri),
                __('Theme URI', 'rrze-cmsinfo')
            );
        }

        if ($markupList === '') {
            return '';
        }

        return sprintf(
            '<ul class="theme-extra-links">%s</ul>',
            $markupList
        );
    }
}
