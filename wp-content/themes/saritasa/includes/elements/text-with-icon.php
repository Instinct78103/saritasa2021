<?php
/**
 * Copyright © 2021 Saritasa, LLC. All Rights Reserved.
 */

namespace Sar\Elements;

use Exception;

/**
 * Class TextWithIcon
 * @package Sar\Elements
 */
class TextWithIcon
{
    /**
     * Shortcode name
     * @var string
     */
    const SHORTCODE = 'text-with-icon';

    /**
     * Init
     * @throws Exception
     */
    public static function init()
    {
        /**
         * Register element
         */
        add_action('vc_before_init', function () {
            static::register_element();
        });

        /**
         * Define shortcode (text-with-icon)
         */
        add_shortcode(static::SHORTCODE, function ($atts, $content): string {
            return static::render(array_filter((array)$atts), $content);
        });
    }

    /**
     * Register element
     * @throws Exception
     */
    public static function register_element()
    {
        vc_map([
            'name' => __('Text With Icon', 'js_composer'),
            'base' => 'text-with-icon',
            'icon' => sar_image_url('logo-small.png'),
            'category' => __('Saritasa', 'saritasa'),
            'weight' => 1,
            'description' => __('Add a text block with stylish icon', 'js_composer'),
            'params' => [
                [
                    'type' => 'attach_image',
                    'heading' => __('Icon Image', 'js_composer'),
                    'param_name' => 'icon_image',
                    'value' => '',
                ],
                [
                    'type' => 'dropdown',
                    'heading' => __('Icon Position', 'js_composer'),
                    'param_name' => 'icon_pos',
                    'admin_label' => false,
                    'value' => [
                        'top',
                        'left',
                        'bottom',
                        'right',
                    ],
                    'std' => 'left',
                    'save_always' => true,
                    'description' => __('Please select the icon position where you wish it to be', 'js_composer'),
                ],
                [
                    'type' => 'textarea_html',
                    'holder' => 'div',
                    'heading' => __('Text Content', 'js_composer'),
                    'param_name' => 'content',
                    'value' => '',
                ],
                [
                    "type" => "textfield",
                    "heading" => __("Classes", "js_composer"),
                    "save_always" => true,
                    "param_name" => "custom_class",
                    "value" => "",
                    "description" => __("Type custom classes", "js_composer"),
                ],
            ],
        ]);
    }

    /**
     * Render element
     * @param array $atts
     * @param string $content
     * @return string
     */
    public static function render(array $atts, string $content = ''): string
    {
        $atts = shortcode_atts([
            'icon_pos' => 'left',
            'icon_image' => '',
            'custom_class' => '',
        ], $atts);

        $icon_markup = '';
        $img_src = wp_get_attachment_image_url((int)$atts['icon_image'], 'full');
        if ($img_src) {
            $icon_markup .= '<i class="sar-icon sar-icon-default" style="background-image: url(' . $img_src . ')"></i>';
        }

        return '<div class="iwithtext icon-pos-' . $atts['icon_pos'] . ' ' . $atts['custom_class'] . '">' .
            $icon_markup .
            '<div class="iwt-text">' . wpb_js_remove_wpautop(do_shortcode($content), true) . '</div>
                </div>';
    }
}

TextWithIcon::init();
