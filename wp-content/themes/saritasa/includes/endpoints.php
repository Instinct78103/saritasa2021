<?php

add_action('rest_api_init', function () {
    /**
     * Resourses Page
     */
    register_rest_route('sar/v2', 'resources/cats_tags', [
        'methods' => \WP_REST_Server::READABLE,
        'callback' => 'get_resources_posts_taxonomies',
    ]);

    register_rest_route('sar/v2', 'resources/posts', [
        'methods' => \WP_REST_Server::READABLE,
        'callback' => 'get_resources_posts',
        'args' => [
            'loaded',
            'category',
            'tag',
        ],
    ]);

    register_rest_route('sar/v2', 'resources/count_posts', [
        'methods' => \WP_REST_Server::READABLE,
        'callback' => 'count_posts_by_cat_AND_tag',
        'args' => [
            'category',
            'tag',
        ],
    ]);

    /**
     * Projects Page
     */
    register_rest_route('sar/v2', 'projects/posts', [
        'methods' => \WP_REST_Server::READABLE,
        'callback' => 'get_portfolio_posts_and_count',
        'args' => [
            'exclude',
            \Sar\Portfolio::PORTFOLIO_TAXONOMY_TYPE,
            \Sar\Portfolio::PORTFOLIO_TAXONOMY_INDUSTRY,
        ],
    ]);

    register_rest_route('sar/v2', 'projects/types_industries', [
        'methods' => \WP_REST_Server::READABLE,
        'callback' => 'get_portfolio_taxonomies',
        'permission_callback' => '__return_true',
    ]);

});

function count_posts_by_cat_AND_tag(\WP_REST_Request $request)
{
    $result = [];
    $tax_query = [];

    $args = $request->get_params();

    if ($args['tag'] && $args['category']) {
        $tax_query = [
            [
                'relation' => 'AND', // only posts that have both taxonomies will return.
                [
                    'taxonomy' => 'post_tag',
                    'field' => 'slug',
                    'terms' => $args['tag'],
                ],
                [
                    'taxonomy' => 'category',
                    'field' => 'slug',
                    'terms' => $args['category'],
                ],
            ],
        ];
    } elseif ($args['category']) {
        $tax_query = [
            [
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => $args['category'],
            ],
        ];
    } elseif ($args['tag']) {
        $tax_query = [
            [
                'taxonomy' => 'post_tag',
                'field' => 'slug',
                'terms' => $args['tag'],
            ],
        ];
    }


    $posts = get_posts([
        'posts_per_page' => -1,
        'tax_query' => $tax_query,
    ]);

    return $result['count_posts'] = count($posts);

}

function get_resources_posts(\WP_REST_Request $request)
{
    $args = $request->get_params();

    $res = [];

    $posts = get_posts([
        'numberposts' => -1,
        'post_type' => 'post',
        'post_status' => ['publish'],
        'posts_per_page' => 6,
        'exclude' => $args['loaded'],
        'category_name' => $args['category'],
        'tag' => $args['tag'],
    ]);


    foreach ($posts as $key => $post) {
        $res[$key]['ID'] = $post->ID;

        $category_object_arr = get_the_category($post->ID);

        foreach ($category_object_arr as $obj) {
            $res[$key]['cats'][] = $obj->slug;
        }

        $posttags = get_the_tags($post->ID);
        if ($posttags) {
            foreach ($posttags as $item) {
                $res[$key]['tags'][] = $item->name;
            }
        } else {
            $res[$key]['tags'] = '';
        }

        $res[$key]['image'] = get_the_post_thumbnail_url($post->ID);
        $res[$key]['permalink'] = get_post_permalink($post->ID);

        $res[$key]['title'] = $post->post_title;
        $res[$key]['excerpt'] = wp_trim_words($post->post_content, 20, '');

        $res[$key]['avatar'] = get_avatar_url($post->post_author);
        $res[$key]['author'] = get_the_author_meta('display_name', $post->post_author);
        $res[$key]['post_date'] = get_the_date('', $post->ID);
    }
    return new \WP_REST_Response($res);
}

function get_categories_by_tag($tag_id)
{
    global $wpdb;
    return $wpdb->get_col("SELECT {$wpdb->terms}.slug
                           FROM {$wpdb->terms}
                           LEFT JOIN {$wpdb->term_taxonomy} ON {$wpdb->term_taxonomy}.term_id = {$wpdb->terms}.term_id
                           WHERE {$wpdb->term_taxonomy}.taxonomy = 'category' 
                           AND {$wpdb->terms}.term_id IN
                           (
                                SELECT tr1.term_taxonomy_id 
                                FROM {$wpdb->term_relationships} as tr1
                                WHERE tr1.object_id IN
                                (
                                    SELECT tr2.object_id 
                                    FROM {$wpdb->term_relationships} as tr2 
                                    WHERE tr2.term_taxonomy_id = {$tag_id}
                                )
                            )"
    );
}

function get_resources_posts_taxonomies()
{
    $res = [];

    $terms = get_terms([
        'post_type' => 'post',
        'taxonomy' => 'category',
        'hide_empty' => true,
    ]);
    foreach ($terms as $term) {
        $res['cats'][$term->slug]['name'] = wp_specialchars_decode($term->name);
        $res['cats'][$term->slug]['count'] = $term->count;
    }

    $tags = get_tags();
    if (is_wp_error($tags)) {
        $tags = [];
    }
    foreach ($tags as $tag) {
        $res['tags'][$tag->name]['catsByTag'] = get_categories_by_tag($tag->term_id);
    }

    return $res;
}

function get_portfolio_posts_and_count(\WP_REST_Request $request)
{
    $args = $request->get_params();

    $res = [];

    if ($args['project-industry'] && $args['project-type']) {
        $tax_query = [
            [
                'relation' => 'AND', // only posts that have both taxonomies will return.
                [
                    'taxonomy' => 'project-industry',
                    'field' => 'slug',
                    'terms' => $args['project-industry'],
                ],
                [
                    'taxonomy' => 'project-type',
                    'field' => 'slug',
                    'terms' => $args['project-type'],
                ],
            ],
        ];
    } elseif ($args['project-type']) {
        $tax_query = [
            [
                'taxonomy' => 'project-type',
                'field' => 'slug',
                'terms' => $args['project-type'],
            ],
        ];
    } elseif ($args['project-industry']) {
        $tax_query = [
            [
                'taxonomy' => 'project-industry',
                'field' => 'slug',
                'terms' => $args['project-industry'],
            ],
        ];
    }


    $posts = get_posts([
        'post_type' => 'portfolio',
        'post_status' => ['publish'],
        'posts_per_page' => $args['posts_per_page'] ?: 8,
        'tax_query' => $tax_query,
        'exclude' => $args['exclude'],
    ]);

    $count = count(get_posts([
        'post_type' => 'portfolio',
        'post_status' => ['publish'],
        'posts_per_page' => -1,
        'tax_query' => $tax_query,
    ]));

    foreach ($posts as $key => $post) {
        $res['posts'][$key]['id'] = $post->ID;
        $res['posts'][$key]['title'] = $post->post_title;
        $res['posts'][$key]['image'] = get_the_post_thumbnail_url($post->ID);
        $res['posts'][$key]['href'] = get_permalink($post->ID);


        $types = get_the_terms($post->ID, 'project-type');
        if ($types) {
            foreach ($types as $type) {
                $res['posts'][$key]['type'][] = $type->slug;
            }
        } else {
            $res['posts'][$key]['type'] = [];
        }

        $industries = get_the_terms($post->ID, 'project-industry');
        if ($industries) {
            foreach ($industries as $industry) {
                $res['posts'][$key]['industry'][] = $industry->slug;
            }
        } else {
            $res['posts'][$key]['industry'] = [];
        }
    }

    $res['count'] = $count;

    return new \WP_REST_Response($res);
}

function get_portfolio_categories()
{
    global $wpdb;
    $cats = $wpdb->get_results("
        SELECT distinct t.name, t.slug 
        FROM wp_term_taxonomy t_t 
            INNER JOIN wp_terms t ON t_t.term_id = t.term_id
            INNER JOIN wp_term_relationships t_r ON t_r.term_taxonomy_id = t.term_id
            INNER JOIN wp_posts p ON t_r.object_id = p.ID
        WHERE t_t.taxonomy = 'project-type' 
            AND p.post_status = 'publish'
            AND p.post_type = 'portfolio'
        ORDER BY t.name"
    );

    return array_map(function ($item) {
        return [
            'type_slug' => $item->slug,
            'type_name' => $item->name,
        ];
    }, $cats);
}

function get_portfolio_tags_by_category($category): array
{
    global $wpdb;
    $tags = $wpdb->get_results
    ("
        SELECT distinct terms2.name as name, terms2.slug as slug
        FROM
            wp_posts as p1
            LEFT JOIN wp_term_relationships as r1 ON p1.ID = r1.object_ID
            LEFT JOIN wp_term_taxonomy as t1 ON r1.term_taxonomy_id = t1.term_taxonomy_id
            LEFT JOIN wp_terms as terms1 ON t1.term_id = terms1.term_id,

            wp_posts as p2
            LEFT JOIN wp_term_relationships as r2 ON p2.ID = r2.object_ID
            LEFT JOIN wp_term_taxonomy as t2 ON r2.term_taxonomy_id = t2.term_taxonomy_id
            LEFT JOIN wp_terms as terms2 ON t2.term_id = terms2.term_id
        WHERE
            t1.taxonomy = 'project-type' AND p1.post_status = 'publish' AND terms1.slug = '{$category}' AND
            t2.taxonomy = 'project-industry' AND p2.post_status = 'publish'
            AND p1.ID = p2.ID
        ORDER by name
    ");

    return $tags;
}

function get_portfolio_taxonomies()
{
    $res = get_portfolio_categories();

    foreach (get_portfolio_categories() as $key => $category) {
        $res[$key]['industries'] = array_map(function ($item) {
            return (array)$item;
        }, get_portfolio_tags_by_category($category['type_slug']));
    }

    return $res;
}

//function count_portfolio_posts_by_taxonomies(\WP_REST_Request $request): int
//{
//    $args = $request->get_params();
//
//    if ($args['project-type'] && $args['project-industry']) {
//        $q = "
//            SELECT * FROM
//                (SELECT t.slug as project_type, t.term_id, r.object_id FROM wp_terms t
//                JOIN wp_term_relationships r ON t.term_id = r.term_taxonomy_id
//                where t.slug = '{$args['project-type']}') type
//            JOIN
//                (SELECT t.slug, t.term_id, r.object_id FROM wp_terms t
//                JOIN wp_term_relationships r ON t.term_id = r.term_taxonomy_id
//                where t.slug = '{$args['project-industry']}') industry
//            ON type.object_id = industry.object_id";
//    } elseif ($args['project-type'] || $args['project-industry']) {
//        $tax_slug = array_values(array_filter($args))[0];
//        $q = "
//            SELECT t.slug as project_type, t.term_id, r.object_id FROM wp_terms t
//            JOIN wp_term_relationships r ON t.term_id = r.term_taxonomy_id
//            where t.slug = '{$tax_slug}'";
//    } else {
//        $q = "
//            SELECT `ID` FROM `wp_posts`
//            WHERE `post_type` = 'portfolio'
//            AND `post_status` = 'publish'
//            ";
//    }
//
//    global $wpdb;
//    return count($wpdb->get_results($q));
//
//}