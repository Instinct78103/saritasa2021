<?php
/**
 * Copyright © 2021 Saritasa, LLC. All Rights Reserved.
 */

namespace Sar;

use WP_Post;
use WP_Query;
use WP_Term;

/**
 * Class Portfolio
 * @package Sar
 */
class Portfolio
{
    /**
     * Define constants
     */
    const PORTFOLIO_POST_TYPE = 'portfolio';
    const PORTFOLIO_TAXONOMY_TYPE = 'project-type';
    const PORTFOLIO_TAXONOMY_INDUSTRY = 'project-industry';

    /**
     * Init
     */
    public static function init()
    {
        add_action('init', function () {

            register_post_type(static::PORTFOLIO_POST_TYPE, [
                'labels' => [
                    'name' => __('Portfolio', 'saritasa'),
                    'singular_name' => __('Portfolio Item', 'saritasa'),
                    'add_new_item' => __('Add New Portfolio Item', 'saritasa'),
                    'edit_item' => __('Edit Portfolio Item', 'saritasa'),
                    'new_item' => __('New Portfolio Item', 'saritasa'),
                    'view_item' => __('View Portfolio Item', 'saritasa'),
                    'search_items' => __('Search Portfolio Items', 'saritasa'),
                    'not_found' => __('No Portfolio Items found', 'saritasa'),
                    'not_found_in_trash' => __('No Portfolio Items found in Trash', 'saritasa'),
                ],
                'public' => true,
                'has_archive' => false,
                'hierarchical' => false,
                'supports' => ['title', 'editor', 'thumbnail', 'comments', 'revisions'],
                'menu_icon' => 'dashicons-art',
            ]);

            register_taxonomy(static::PORTFOLIO_TAXONOMY_TYPE, static::PORTFOLIO_POST_TYPE, [
                'labels' => [
                    'name' => __('Project Types', 'saritasa'),
                    'singular_name' => __('Project Type', 'saritasa'),
                    'search_items' => __('Search Project Types', 'saritasa'),
                    'all_items' => __('All Project Types', 'saritasa'),
                    'parent_item' => __('Parent Project Type', 'saritasa'),
                    'edit_item' => __('Edit Project Type', 'saritasa'),
                    'update_item' => __('Update Project Type', 'saritasa'),
                    'add_new_item' => __('Add New Project Type', 'saritasa'),
                    'menu_name' => __('Project Types', 'saritasa'),
                ],
                'hierarchical' => true,
                'show_ui' => true,
                'query_var' => true,
            ]);

            register_taxonomy(static::PORTFOLIO_TAXONOMY_INDUSTRY, static::PORTFOLIO_POST_TYPE, [
                'labels' => [
                    'name' => __('Project Industries', 'saritasa'),
                    'singular_name' => __('Project Industry', 'saritasa'),
                    'search_items' => __('Search Project Industries', 'saritasa'),
                    'all_items' => __('All Project Industries', 'saritasa'),
                    'parent_item' => __('Parent Project Industry', 'saritasa'),
                    'edit_item' => __('Edit Project Industry', 'saritasa'),
                    'update_item' => __('Update Project Industry', 'saritasa'),
                    'add_new_item' => __('Add New Project Industry', 'saritasa'),
                    'menu_name' => __('Project Industries', 'saritasa'),
                ],
                'hierarchical' => true,
                'show_ui' => true,
                'query_var' => true,
            ]);
        });
    }

    /**
     * Get project thumbnail url
     * @param WP_Post $project
     * @return string
     */
    public static function get_project_thumb_url(WP_Post $project): string
    {
        $squareImg = get_post_meta($project->ID, 'square_image', true) ?? false;
        $thumb_url = $squareImg ? $squareImg : (get_the_post_thumbnail_url($project->ID, 'medium') ?? false);

        if (strpos($thumb_url, '/app/') !== -1) {
            $thumb_url = str_replace('/app/', '/wp-content/', $thumb_url);
        }

        if (!$thumb_url) {
            $post_content = $project->post_content;

            preg_match('/\<img(.*?)\>/', $post_content, $matches);

            if (!empty($matches)) {
                preg_match('/src="(.*?)\"/', $matches[0], $url);
                $thumb_url = $url[1];
            }
        }

        return $thumb_url;
    }

    /**
     * Get project client name
     * @param WP_Post $project
     * @return string|null
     */
    public static function get_project_client_name(WP_Post $project): ?string
    {
        return get_field('project_client_name', $project->ID) ?: null;
    }

    /**
     * Get project terms
     * @param WP_Post $project
     * @return WP_Term[]
     */
    public static function get_project_terms(WP_Post $project): array
    {
        return wp_get_post_terms($project->ID, [static::PORTFOLIO_TAXONOMY_TYPE, static::PORTFOLIO_TAXONOMY_INDUSTRY]);
    }

    /**
     * Get project terms list
     * @param WP_Term[] $project_terms
     * @return string
     */
    public static function get_project_terms_list(array $project_terms): string
    {
        return implode(' ', array_map(function ($item) {
            return 'filter-' . $item->slug;
        }, $project_terms));
    }

    /**
     * Get project term image
     * @param WP_Term $term
     * @return string
     */
    public static function get_project_term_image(WP_Term $term): string
    {
        $term_icon_url = get_field('category_icon', $term)['url'];
        return empty($term_icon_url) ? '' : '<img class="work-category-icon" src="' . $term_icon_url . '" alt="">';
    }

    /**
     * Get projects
     * @param array $args
     * @param array $terms
     * @return WP_Query
     */
    public static function get_projects(array $args = [], array $terms = []): WP_Query
    {
        $args = wp_parse_args($args, [
            'post_type' => static::PORTFOLIO_POST_TYPE,
            'post_status' => 'publish',
        ]);

        if (isset($terms['type'])) {
            $args['tax_query'][] = [
                'taxonomy' => static::PORTFOLIO_TAXONOMY_TYPE,
                'field' => 'slug',
                'terms' => $terms['type'],
            ];
        }

        if (isset($terms['industry'])) {
            $args['tax_query'][] = [
                'taxonomy' => static::PORTFOLIO_TAXONOMY_INDUSTRY,
                'field' => 'slug',
                'terms' => $terms['industry'],
            ];
        }

        return new WP_Query($args);
    }
}

Portfolio::init();
