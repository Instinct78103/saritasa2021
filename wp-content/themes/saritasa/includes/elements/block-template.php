<?php
/**
 * Copyright © 2021 Saritasa, LLC. All Rights Reserved.
 */

namespace Sar\Elements;

use Exception;
use Sar\Portfolio;
use WP_Query;

/**
 * Class SaritasaPageBlockTemplate
 * @package Sar\Elements
 */
class SaritasaPageBlockTemplate
{

    /**
     * Define constants
     */
    const PAGE_BLOCK_POST_TYPE = 'vc_templates';

    /**
     * Init
     * @throws Exception
     */
    public static function init()
    {
        add_post_type_support(static::PAGE_BLOCK_POST_TYPE, 'thumbnail');
        add_action('init', function () {
            /**
             * Register element
             */
            register_post_type(static::PAGE_BLOCK_POST_TYPE, [
                'labels' => [
                    'name' => __('Page Block Templates'),
                    'singular_name' => __('Template'),
                ],
                'public' => true,
            ]);
        });

        static::featured_image_as_column_in_admin_panel();
    }

    public static function featured_image_as_column_in_admin_panel()
    {
        add_filter('manage_posts_columns', function ($defaults) {
            $defaults['wdm_post_thumbs'] = __('Featured Image'); //name of the column
            return $defaults;
        });
        add_action('manage_posts_custom_column', function ($column_name, $id) {
            if ($column_name === 'wdm_post_thumbs') {
                echo the_post_thumbnail([250, 150]); //size of the thumbnail
            }
        }, 5, 2);
    }

    /**
     * @return string[]
     */

    public static function get_page_block_templates()
    {
        $templates = get_posts([
            'post_type' => static::PAGE_BLOCK_POST_TYPE,
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        ]);

        $value = [];

        foreach ($templates as $template) {
            $title = $template->post_title;
            $id = $template->ID;

            if (array_key_exists($title, $value)) {
                $title = "{$title} ({$id})";
            }

            $value[__($title)] = $id;
        }

        return $value;
    }
}

SaritasaPageBlockTemplate::init();