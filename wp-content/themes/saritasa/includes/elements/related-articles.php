<?php
/**
 * Copyright © 2021 Saritasa, LLC. All Rights Reserved.
 */
namespace Sar\Elements;

use Exception;

/**
 * Class RelatedArticles
 * @package Sar\Elements
 */
class RelatedArticles
{
    /**
     * Shortcode name
     */
    const SHORTCODE = 'sar_related_articles';

    /**
     * Posts per page
     */
    const POSTS_PER_PAGE = 4;

    /**
     * Posts per row
     */
    const POSTS_PER_ROW = 4;

    /**
     * Init
     */
    public static function init()
    {
        add_action('vc_before_init', function () {
            /**
             * Register element
             */
            static::register_element();

            /**
             * Add custom multiselect field
             */
            vc_add_shortcode_param('multiselect_related_articles', function (array $settings, string $value): string {
                return static::custom_multiselect($settings, $value);
            });
        });

        /**
         * Define related articles shortcode (sar_related_articles)
         */
        add_shortcode(self::SHORTCODE, function ($atts): string {
            return static::render(array_filter((array) $atts));
        });
    }

    /**
     * Get articles list
     * @return array
     */
    private static function get_articles_list(): array
    {
        $posts = get_posts([
            'post_type' => 'post',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        ]);

        $options = [];
        foreach ($posts as $post) {
            $options[$post->ID] = $post->post_title . ' -- ' . $post->ID;
        }

        return $options;
    }

    /**
     * Register element
     * @return bool
     */
    public static function register_element(): bool
    {
        try {
            return vc_map([
                'name' => __('Related Articles', 'saritasa'),
                'base' => static::SHORTCODE,
                'icon' => sar_image_url('logo-small.png'),
                'category' => __('Saritasa', 'saritasa'),
                'allowed_container_element' => 'vc_row',
                "description" => __('Used on the Services page', 'js_composer'),
                'params' => [
                    [
                        'type' => 'multiselect_related_articles',
                        'save_always' => true,
                        'heading' => __('Selected Posts', 'saritasa'),
                        'admin_label' => true,
                        'param_name' => 'multiselect_related_articles',
                        'value' => static::get_articles_list(),
                    ],
                    [
                        'type' => 'textfield',
                        'heading' => __('Per page', 'js_composer'),
                        'value' => static::POSTS_PER_PAGE,
                        'save_always' => true,
                        'param_name' => 'per_page',
                        'description' => __('The "per_page" shortcode determines how many posts to show on the page', 'js_composer'),
                    ],
                    [
                        'type' => 'textfield',
                        'heading' => __('Per row', 'js_composer'),
                        'value' => static::POSTS_PER_ROW,
                        'save_always' => true,
                        'param_name' => 'per_row',
                        'description' => __('The "per_row" shortcode determines how many posts to show on the one row', 'js_composer'),
                    ],
                    [
                        'type' => 'dropdown',
                        'heading' => __('Sort', 'js_composer'),
                        'save_always' => true,
                        'param_name' => 'orderby',
                        'value' => [
                            'By id' => 'id',
                            'By Date' => 'date',
                            'By Title' => 'title',
                        ],
                        'std' => 'date',
                    ],
                    [
                        'type' => 'dropdown',
                        'heading' => __('Order', 'js_composer'),
                        'save_always' => true,
                        'param_name' => 'order',
                        'value' => [
                            __('ASC', 'js_composer') => 'asc',
                            __('DESC', 'js_composer') => 'desc',
                        ],
                        'std' => 'desc',
                    ],
                ],
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Custom multiselect field html
     * @param array $settings
     * @param string $value
     * @return string
     */
    protected static function custom_multiselect(array $settings, string $value): string
    {
        $html = '';
        $selected = [];
        $selected_items = explode(',', $value);
        $articles_list = static::get_articles_list();

        foreach ($selected_items as $selected_item) {
            $selected[$selected_item] = $articles_list[$selected_item];
            $html .= '<option id="' . $selected_item . '" value="' . $selected_item . '" selected>' . $articles_list[$selected_item] . '</option>';
        }

        $not_selected_items = array_diff($articles_list, $selected);
        foreach ($not_selected_items as $key => $not_selected_item) {
            $html .= '<option id="' . $key . '" value="' . $key . '">' . $not_selected_item . '</option>';
        }

        return '
            <div class="custom_multiselect">
                <select data-value="' . $value . '" name="' . esc_attr($settings['param_name']) . '" multiple class="wpb_vc_param_value">' . $html . '</select>
            </div>';
    }

    /**
     * Render element
     * @param array $atts
     * @return string
     */
    public static function render(array $atts): string
    {
        $atts = shortcode_atts([
            'multiselect_related_articles' => '',
            'per_page' => static::POSTS_PER_PAGE,
            'per_row' => static::POSTS_PER_ROW,
            'orderby' => 'date',
            'order' => 'desc',
        ], $atts);

        $posts_query = get_posts([
            'posts_per_pag' => $atts['per_page'],
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
            'post__in' => $atts['multiselect_related_articles'] ? explode(',', $atts['multiselect_related_articles']) : [],
        ]);

        return sar_get_template('elements/related-articles', [
            'posts' => $posts_query,
            'posts_per_row' => $atts['per_row'],
        ]);
    }
}

RelatedArticles::init();
