<?php
/**
 * Copyright © 2021 Saritasa, LLC. All Rights Reserved.
 */

namespace Sar\Elements;

use Exception;
use Sar\Portfolio;

/**
 * Class ProjectsArchive
 * @package Sar\Elements
 */
class ProjectsArchive
{
    /**
     * Shortcode name
     */
    const SHORTCODE = 'sar_portfolio';

    /**
     * Posts per page
     */
    const PROJECTS_PER_PAGE = 8;

    /**
     * Init
     * @throws Exception
     */
    public static function init()
    {
        add_action('init', function () {
            /**
             * Add rewrite rules to portfolio permalink
             */
            foreach (['portfolio', 'projects'] as $pageName) {
                add_rewrite_rule('^(' . $pageName . ')/type-([^/]*)/industry-([^/]*)/?', 'index.php?pagename=$matches[1]&type=$matches[2]&industry=$matches[3]', 'top');
                add_rewrite_rule('^(' . $pageName . ')/industry-([^/]*)/type-([^/]*)/?', 'index.php?pagename=$matches[1]&industry=$matches[2]&type=$matches[3]', 'top');
                add_rewrite_rule('^(' . $pageName . ')/type-([^/]*)/?', 'index.php?pagename=$matches[1]&type=$matches[2]', 'top');
                add_rewrite_rule('^(' . $pageName . ')/industry-([^/]*)/?', 'index.php?pagename=$matches[1]&industry=$matches[2]', 'top');
            }

            /**
             * Add projects types to query vars
             */
            add_filter('query_vars', function ($vars) {
                $vars[] = 'type';
                $vars[] = 'industry';
                return $vars;
            });
        });

        /**
         * Register element
         */
        add_action('vc_before_init', function () {
            static::register_element();
        });

        /**
         * Define projects archive shortcode (sar_portfolio)
         */
        add_shortcode(static::SHORTCODE, function ($atts): string {
            return static::render(array_filter((array)$atts));
        });

        /**
         * Add retrieving projects endpoint
         */
        add_action('wp_ajax_get_projects', [static::class, 'get_projects_endpoint']);
        add_action('wp_ajax_nopriv_get_projects', [static::class, 'get_projects_endpoint']);

        /**
         * Add projects loaded status endpoint
         */
        add_action('wp_ajax_get_projects_loaded_status', [static::class, 'get_projects_loaded_status_endpoint']);
        add_action('wp_ajax_nopriv_get_projects_loaded_status', [static::class, 'get_projects_loaded_status_endpoint']);
    }

    /**
     * Register element
     * @throws Exception
     */
    protected static function register_element()
    {
        vc_map([
            'name' => __('Projects Archive', 'saritasa'),
            'base' => static::SHORTCODE,
            'icon' => sar_image_url('logo-small.png'),
            'category' => __('Saritasa', 'saritasa'),
            'allowed_container_element' => 'vc_row',
            'params' => [
                [
                    'type' => 'textfield',
                    'heading' => __('Per page', 'js_composer'),
                    'value' => static::PROJECTS_PER_PAGE,
                    'save_always' => true,
                    'param_name' => 'per_page',
                    'description' => __('The "per_page" shortcode determines how many products to show on the page', 'js_composer'),
                ],
            ],
        ]);
    }

    /**
     * Get projects
     */
    public static function get_projects_endpoint()
    {
        $projects_list = '';
        $projects_loaded = json_decode($_REQUEST['loaded_projects']);

        $args = [
            'post_type' => 'portfolio',
            'exclude' => $projects_loaded,
            'post_status' => 'publish',
            'numberposts' => 4,
        ];

        $posts = get_posts($args);

        foreach ($posts as $post) {
            $projects_loaded[] = $post->ID;
            $projects_list .= sar_get_template('portfolio-archive-item', ['project' => $post]);
        }

        wp_send_json_success([
            'projects' => $projects_list,
            'total' => $_REQUEST['terms']
                ? count(static::get_portfolio_posts_by_taxonomies($_REQUEST['terms']))
                : count(get_posts([
                    'post_type' => 'portfolio',
                    'post_status' => 'publish',
                    'numberposts' => -1,
                ])),
            'loaded_projects' => json_encode($projects_loaded),
        ]);
    }

    /**
     * Get projects loaded status
     */
    public static function get_projects_loaded_status_endpoint()
    {
        $projects_per_page = static::get_projects_per_page_form_request();

        $projects_query = Portfolio::get_projects(
            [
                'exclude' => static::get_loaded_projects_form_request(),
                'posts_per_page' => $projects_per_page,
            ],
            $_REQUEST['terms'] ?? []
        );

        wp_send_json_success([
            'loaded' => $projects_query->post_count < $projects_per_page,
        ]);
    }

    /**
     * Get projects per page from request
     * @return int
     */
    protected static function get_projects_per_page_form_request(): int
    {
        $projects_count = (int)$_REQUEST['count'] ?? 0;
        $projects_per_page = (int)$_REQUEST['projects_per_page'] ?? static::PROJECTS_PER_PAGE;

        if ($projects_count < $projects_per_page) {
            $projects_per_page -= $projects_count;
        } else if ($projects_count > $projects_per_page) {
            $projects_per_page -= ($projects_count - $projects_per_page) % $projects_per_page;
        }

        return abs($projects_per_page);
    }

    /**
     * Get loaded projects from request
     * @return int[]
     */
    protected static function get_loaded_projects_form_request(): array
    {
        if (empty($_REQUEST['loaded_projects'])
            || !is_array($_REQUEST['loaded_projects'])) {
            return [];
        }

        return array_map('intval', $_REQUEST['loaded_projects']);
    }

    /**
     * Render element
     * @param array $atts
     * @return string
     */
    public static function render(array $atts): string
    {
        $terms = [];
        $atts = shortcode_atts([
            'per_page' => static::PROJECTS_PER_PAGE,
        ], $atts);

        $types = get_terms([
            'post_type' => Portfolio::PORTFOLIO_POST_TYPE,
            'taxonomy' => Portfolio::PORTFOLIO_TAXONOMY_TYPE,
            'hide_empty' => false,
        ]);
        $type_slug = null;
        $type_slug_input = get_query_var('type');
        if ($type_slug_input) {
            foreach ($types as $type_term) {
                if ($type_term->slug === $type_slug_input) {
                    $type_slug = $terms['type'] = $type_term->slug;
                }
            }
        }

        $industries = get_terms([
            'post_type' => Portfolio::PORTFOLIO_POST_TYPE,
            'taxonomy' => Portfolio::PORTFOLIO_TAXONOMY_INDUSTRY,
            'hide_empty' => false,
        ]);
        $industry_slug = null;
        $industry_slug_input = get_query_var('industry');
        if ($industry_slug_input) {
            foreach ($industries as $industry_term) {
                if ($industry_term->slug === $industry_slug_input) {
                    $industry_slug = $terms['industry'] = $industry_term->slug;
                }
            }
        }

        $projects_query = Portfolio::get_projects([
            'posts_per_page' => (int)$atts['per_page'],
        ], $terms);

        return sar_get_template('portfolio-archive', [
            'type' => $type_slug,
            'industry' => $industry_slug,
            'types' => $types,
            'industries' => $industries,
            'projects' => $projects_query->posts,
            'projects_total' => $projects_query->found_posts,
            'projects_per_page' => (int)$atts['per_page'],
        ]);
    }
}

ProjectsArchive::init();
