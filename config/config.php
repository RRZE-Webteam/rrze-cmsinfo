<?php

namespace RRZE\CMSinfo\Config;

defined('ABSPATH') || exit;

/**
 * Gibt der Name der Option zurück.
 * @return array [description]
 */
function getOptionName()
{
    return 'cmsinfo';
}

/**
 * Gibt die Einstellungen des Menus zurück.
 * @return array [description]
 */
function getMenuSettings()
{
    return [
        'page_title'    => __('CMS Basis', 'cmsinfo'),
        'menu_title'    => __('CMS Basis', 'cmsinfo'),
        'capability'    => 'manage_options',
        'menu_slug'     => 'cmsinfo',
        'title'         => __('CMS Basis Settings', 'cmsinfo'),
    ];
}

/**
 * Gibt die Einstellungen der Inhaltshilfe zurück.
 * @return array [description]
 */
function getHelpTab()
{
    return [
        [
            'id'        => 'cmsinfo-help',
            'content'   => [
                '<p>' . __('Here comes the Context Help content.', 'cmsinfo') . '</p>'
            ],
            'title'     => __('Overview', 'cmsinfo'),
            'sidebar'   => sprintf('<p><strong>%1$s:</strong></p><p><a href="https://blogs.fau.de/webworking">RRZE Webworking</a></p><p><a href="https://github.com/RRZE Webteam">%2$s</a></p>', __('For more information', 'cmsinfo'), __('RRZE Webteam on Github', 'cmsinfo'))
        ]
    ];
}

/**
 * Gibt die Einstellungen der Optionsbereiche zurück.
 * @return array [description]
 */
function getSections()
{
    return [
        [
            'id'    => 'basic',
            'title' => __('Basic Settings', 'cmsinfo')
        ],
        [
            'id'    => 'advanced',
            'title' => __('Advanced Settings', 'cmsinfo')
        ]
    ];
}

/**
 * Gibt die Einstellungen der Optionsfelder zurück.
 * @return array [description]
 */
function getFields()
{
    return [
        'basic' => [
            [
                'name'              => 'text_input',
                'label'             => __('Text Input', 'cmsinfo'),
                'desc'              => __('Text input description.', 'cmsinfo'),
                'placeholder'       => __('Text Input placeholder', 'cmsinfo'),
                'type'              => 'text',
                'default'           => 'Title',
                'sanitize_callback' => 'sanitize_text_field'
            ],
            [
                'name'              => 'number_input',
                'label'             => __('Number Input', 'cmsinfo'),
                'desc'              => __('Number input description.', 'cmsinfo'),
                'placeholder'       => '5',
                'min'               => 0,
                'max'               => 100,
                'step'              => '1',
                'type'              => 'number',
                'default'           => 'Title',
                'sanitize_callback' => 'floatval'
            ],
            [
                'name'        => 'textarea',
                'label'       => __('Textarea Input', 'cmsinfo'),
                'desc'        => __('Textarea description', 'cmsinfo'),
                'placeholder' => __('Textarea placeholder', 'cmsinfo'),
                'type'        => 'textarea'
            ],
            [
                'name'  => 'checkbox',
                'label' => __('Checkbox', 'cmsinfo'),
                'desc'  => __('Checkbox description', 'cmsinfo'),
                'type'  => 'checkbox'
            ],
            [
                'name'    => 'multicheck',
                'label'   => __('Multiple checkbox', 'cmsinfo'),
                'desc'    => __('Multiple checkbox description.', 'cmsinfo'),
                'type'    => 'multicheck',
                'default' => [
                    'one' => 'one',
                    'two' => 'two'
                ],
                'options'   => [
                    'one'   => __('One', 'cmsinfo'),
                    'two'   => __('Two', 'cmsinfo'),
                    'three' => __('Three', 'cmsinfo'),
                    'four'  => __('Four', 'cmsinfo')
                ]
            ],
            [
                'name'    => 'radio',
                'label'   => __('Radio Button', 'cmsinfo'),
                'desc'    => __('Radio button description.', 'cmsinfo'),
                'type'    => 'radio',
                'options' => [
                    'yes' => __('Yes', 'cmsinfo'),
                    'no'  => __('No', 'cmsinfo')
                ]
            ],
            [
                'name'    => 'selectbox',
                'label'   => __('Dropdown', 'cmsinfo'),
                'desc'    => __('Dropdown description.', 'cmsinfo'),
                'type'    => 'select',
                'default' => 'no',
                'options' => [
                    'yes' => __('Yes', 'cmsinfo'),
                    'no'  => __('No', 'cmsinfo')
                ]
            ]
        ],
        'advanced' => [
            [
                'name'    => 'color',
                'label'   => __('Color', 'cmsinfo'),
                'desc'    => __('Color description.', 'cmsinfo'),
                'type'    => 'color',
                'default' => ''
            ],
            [
                'name'    => 'password',
                'label'   => __('Password', 'cmsinfo'),
                'desc'    => __('Password description.', 'cmsinfo'),
                'type'    => 'password',
                'default' => ''
            ],
            [
                'name'    => 'wysiwyg',
                'label'   => __('Advanced Editor', 'cmsinfo'),
                'desc'    => __('Advanced Editor description.', 'cmsinfo'),
                'type'    => 'wysiwyg',
                'default' => ''
            ],
            [
                'name'    => 'file',
                'label'   => __('File', 'cmsinfo'),
                'desc'    => __('File description.', 'cmsinfo'),
                'type'    => 'file',
                'default' => '',
                'options' => [
                    'button_label' => __('Choose an Image', 'cmsinfo')
                ]
            ]
        ]
    ];
}
