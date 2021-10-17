<?php
/**
 * Copyright © 2021 Saritasa, LLC. All Rights Reserved.
 */

namespace Sar\Elements;

use Exception;
use Sar\Portfolio;

/**
 * Class ProjectsFeatured
 * @package Sar\Elements
 */
class FeaturedProjects
{
    /**
     * Shortcode name
     */
    const SHORTCODE = 'sar_featured_projects';

    /**
     * Posts per page
     */
    const PROJECTS_PER_PAGE = 4;

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
            vc_add_shortcode_param('custom_multiselect', function (array $settings, string $value): string {
                return static::custom_multiselect($settings, $value);
            });
        });

        /**
         * Define featured projects shortcode (sar_featured_projects)
         */
        add_shortcode(static::SHORTCODE, function ($atts): string {
            return static::render(array_filter((array)$atts));
        });
    }

    /**
     * Register element
     * @return bool
     */
    public static function register_element(): bool
    {
        try {
            return vc_map([
                'name' => __('Featured Projects', 'saritasa'),
                'base' => static::SHORTCODE,
                'icon' => sar_image_url('logo-small.png'),
                'category' => __('Saritasa', 'saritasa'),
                "description" => __('Add a portfolio post', 'js_composer'),
                'allowed_container_element' => 'vc_row',
                'params' => [
                    [
                        'type' => 'custom_multiselect',
                        'save_always' => true,
                        'heading' => __('Selected featured projects', 'saritasa'),
                        'admin_label' => true,
                        'param_name' => 'multiselect',
                        'value' => static::get_portfolio_list(),
                    ],
                    [
                        'type' => 'textfield',
                        'heading' => __('Per page', 'js_composer'),
                        'value' => static::PROJECTS_PER_PAGE,
                        'save_always' => true,
                        'param_name' => 'per_page',
                        'description' => __('The "per_page" shortcode determines how many products to show on the page', 'js_composer'),
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
                    [
                        'type' => 'checkbox',
                        'heading' => __('Add "View More" button below?', 'js_composer'),
                        'group' => 'Add Button',
                        'save_always' => true,
                        'param_name' => 'add_button',
                        'value' => false,
                    ],
                    [
                        'type' => 'dropdown',
                        'heading' => __('Select link', 'js_composer'),
                        'group' => 'Add Button',
                        'save_always' => true,
                        'param_name' => 'link',
                        'dependency' => ['element' => 'add_button', 'value' => 'true'],
                        'value' => ['--Projects Page--' => ''] + static::get_all_projects_types() + static::get_all_projects_tags(),
                    ],
                ],
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get all projects types among all portfolio posts
     * @return array
     */
    public static function get_all_projects_types(): array
    {
        $all_types = [];
        $all_terms = get_terms(['taxonomy' => 'project-type']);

        if (is_array($all_terms) && !empty($all_terms)) {
            foreach ($all_terms as $item) {
                $all_types['type: ' . $item->name] = 'type-' . $item->slug;
            }
        }
        return $all_types;

    }

    public static function get_all_projects_tags(): array
    {
        $all_tags = [];
        $all_terms = get_terms(['taxonomy' => 'project-industry']);

        if (is_array($all_terms) && !empty($all_terms)) {
            foreach ($all_terms as $item) {
                $all_tags['industry: ' . $item->name] = 'industry-' . $item->slug;
            }
        }
        return $all_tags;

    }

    /**
     * Get portfolio list
     * @return array
     */
    protected static function get_portfolio_list(): array
    {
        $projects_query = Portfolio::get_projects([
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        ]);

        $projects = [];
        foreach ($projects_query->posts as $project) {
            $projects[$project->ID] = $project->post_title . ' -- ' . $project->ID;
        }

        return $projects;
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
        $portfolio_list = static::get_portfolio_list();

        foreach ($selected_items as $selected_item) {
            $selected[$selected_item] = $portfolio_list[$selected_item];
            $html .= '<option id="' . $selected_item . '" value="' . $selected_item . '" selected>' . $portfolio_list[$selected_item] . '</option>';
        }

        $not_selected_items = array_diff($portfolio_list, $selected);
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
        $params = shortcode_atts([
            'get_from' => 'list',
            'multiselect' => '',
            'per_page' => static::PROJECTS_PER_PAGE,
            'orderby' => 'date',
            'order' => 'DESC',
            'add_button' => false,
            'link' => '',
        ], $atts);

        $projects_query = Portfolio::get_projects([
            'posts_per_page' => $params['per_page'],
            'orderby' => $params['orderby'],
            'order' => $params['order'],
            'post__in' => $params['multiselect'] ? explode(',', $params['multiselect']) : [],
        ]);

        $pagination_html = '';

        if ($params['add_button']) {
            $pagination_html .= '<div class="pagination"><a href="/projects/' . $params['link'] . '" class="sar-btn">View More</a></div>';
        }

        return sar_get_template('elements/featured-projects', [
                'projects' => $projects_query->posts,
            ]) . $pagination_html;
    }
}

FeaturedProjects::init();