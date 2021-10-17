<?php

/**
 * Copyright © 2021 Saritasa, LLC. All Rights Reserved.
 */

function pre($var)
{
    echo '<pre style="white-space: pre-wrap;">';
    print_r($var);
    echo '</pre>';
}

//Disable Gutenberg
add_filter('use_block_editor_for_post', '__return_false');
add_filter('use_widgets_block_editor', '__return_false');

use Sar\Portfolio;
use Sar\Elements\ProjectsArchive;

/**
 * Add page title in <head></head>
 */
add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
});

/**
 * Add page slug to body class
 */
add_filter('body_class', function ($classes) {
    global $post;
    if (isset($post) && $post->post_type === 'page') {
        $classes[] = $post->post_name;
    }
    return $classes;
});

/**
 * Enqueue scripts and styles
 */
add_action('wp_enqueue_scripts', function () {
    /**
     * Register libraries
     */
//    wp_register_script('isotope', sar_js_url('isotope.min.js'), ['jquery'], '3.0.6', true);
    wp_register_script('why-chose-us-toggles-min', sar_js_url('why-chose-us-toggles.min.js'));

    /**
     * Owl Carousel
     */
    wp_register_script('owl-carousel', sar_js_url('owl.carousel.min.js'), ['jquery'], SAR_VERSION, true);
    wp_register_script('owl-init', sar_js_url('owl-init.min.js'), ['jquery', 'owl-carousel'], SAR_VERSION, true);

    /**
     * Flip Box -- About Page
     */
    wp_register_script('flip-box', sar_js_url('flip-box.min.js'), ['jquery'], SAR_VERSION, true);

    /**
     * Form validate
     */
    wp_register_script('phone-mask', sar_js_url('mask.min.js'), ['jquery'], SAR_VERSION, true);
    wp_register_script('form-validate', sar_js_url('form-validate.min.js'), ['jquery', 'phone-mask'], SAR_VERSION, true);

    /**
     * Enqueue layout scripts and styles
     */
    wp_enqueue_script('sar-layout', sar_js_url('layout.min.js'), ['jquery'], SAR_VERSION, true);
    wp_enqueue_style('sar-layout', sar_css_scoped_url('layout.min.css'), [], SAR_VERSION);
    wp_enqueue_script('phone-mask', sar_js_url('mask.js'), ['jquery'], SAR_VERSION, true);
    wp_enqueue_script('form-validate', sar_js_url('form-validate.js'), ['jquery', 'phone-mask'], SAR_VERSION, true);

    /**
     * Home page
     */
    if (is_front_page()) {
        wp_enqueue_style('sar-home', sar_css_scoped_url('home.min.css'), ['sar-layout'], SAR_VERSION);
        wp_enqueue_script('counter-start', sar_js_url('counter-start.min.js'), ['jquery'], SAR_VERSION, true);

        /**
         * Owl Carousel stuff
         */
        wp_enqueue_style('owl-carousel-min', sar_css_scoped_url('owl.carousel.min.css'), ['sar-layout'], SAR_VERSION);
        wp_enqueue_style('owl-theme-default-min', sar_css_scoped_url('owl.theme.default.min.css'), ['sar-layout'], SAR_VERSION);
        wp_enqueue_script('owl-carousel', sar_js_url('owl.carousel.min.js'), ['jquery'], SAR_VERSION, true);
        wp_enqueue_script('owl-init', sar_js_url('owl-init.min.js'), ['jquery', 'owl-carousel'], SAR_VERSION, true);
    } else if (is_page('projects')) {
        wp_enqueue_style('sar-projects', sar_css_scoped_url('projects.min.css'), [], SAR_VERSION);
//        wp_enqueue_script('sar-projects', sar_js_url('projects.min.js'), ['jquery', 'isotope'], SAR_VERSION, true);

        wp_enqueue_script('sar-projects-chunk', sar_js_url('../app-pub/projects/js/chunk-vendors.js'), [], false, true);
        wp_enqueue_script('sar-projects-app', sar_js_url('../app-pub/projects/js/app.js'), ['sar-projects-chunk'], false, true);

        /**
         * Owl Carousel stuff
         */
        wp_enqueue_style('owl-carousel-min', sar_css_scoped_url('owl.carousel.min.css'), ['sar-layout'], SAR_VERSION);
        wp_enqueue_style('owl-theme-default-min', sar_css_scoped_url('owl.theme.default.min.css'), ['sar-layout'], SAR_VERSION);
        wp_enqueue_script('owl-carousel', sar_js_url('owl.carousel.min.js'), ['jquery'], SAR_VERSION, true);
        wp_enqueue_script('owl-init', sar_js_url('owl-init.min.js'), ['jquery', 'owl-carousel'], SAR_VERSION, true);

        /**
         * Counter
         */
        wp_enqueue_script('counter-start', sar_js_url('counter-start.min.js'), ['jquery'], SAR_VERSION, true);
    } /**
     * Resources Single Post
     */
    else if (is_singular('post')) {
        wp_enqueue_style('sar_single_post', sar_css_scoped_url('single-post.min.css'));
    } else if (is_singular('portfolio')) {
        wp_enqueue_style('sar_single_portfolio', sar_css_scoped_url('single-portfolio.min.css'));
        wp_enqueue_script('counter-start', sar_js_url('counter-start.min.js'), ['jquery'], SAR_VERSION, true);
        wp_enqueue_script('flickity-min', sar_js_url('flickity.min.js'), ['jquery'], '', true);
        wp_enqueue_script('flickity-init', sar_js_url('flickity-init.js'), ['jquery', 'flickity-min'], '', true);
    } /**
     * About page
     */
    else if (is_page('about-us')) {
        wp_enqueue_style('sar-about', sar_css_scoped_url('about.min.css'), ['sar-layout'], SAR_VERSION);
        wp_enqueue_script('counter-start', sar_js_url('counter-start.min.js'), ['jquery'], SAR_VERSION, true);
//        wp_enqueue_script('flip-box', sar_js_url('flip-box.min.js'), ['jquery'], SAR_VERSION, true);

        /**
         * Owl Carousel stuff
         */
        wp_enqueue_style('owl-carousel-min', sar_css_scoped_url('owl.carousel.min.css'), ['sar-layout'], SAR_VERSION);
        wp_enqueue_style('owl-theme-default-min', sar_css_scoped_url('owl.theme.default.min.css'), ['sar-layout'], SAR_VERSION);
        wp_enqueue_script('owl-carousel', sar_js_url('owl.carousel.min.js'), ['jquery'], SAR_VERSION, true);
        wp_enqueue_script('owl-init', sar_js_url('owl-init.min.js'), ['jquery', 'owl-carousel'], SAR_VERSION, true);
    } else if (is_page('resources')) {
        wp_enqueue_style('sar-resources', sar_css_scoped_url('resources.min.css'), ['sar-layout'], SAR_VERSION);
        wp_enqueue_script('sar-resources-chunk', sar_js_url('../app-pub/resources/js/chunk-vendors.js'), [], false, true);
        wp_enqueue_script('sar-resources-app', sar_js_url('../app-pub/resources/js/app.js'), ['sar-resources-chunk'], false, true);
    } else if (is_page('thank-you')) {
        wp_enqueue_style('sar-thank-you', sar_css_scoped_url('thank-you.min.css'), ['sar-layout'], SAR_VERSION);
    } else if (is_page('join-our-team')) {
        wp_enqueue_style('sar-join-our-team', sar_css_scoped_url('join-our-team.min.css'), ['sar-layout'], SAR_VERSION);

        /**
         * Owl Carousel stuff
         */
        wp_enqueue_style('owl-carousel-min', sar_css_scoped_url('owl.carousel.min.css'), ['sar-layout'], SAR_VERSION);
        wp_enqueue_style('owl-theme-default-min', sar_css_scoped_url('owl.theme.default.min.css'), ['sar-layout'], SAR_VERSION);
        wp_enqueue_script('owl-carousel', sar_js_url('owl.carousel.min.js'), ['jquery'], SAR_VERSION, true);
        wp_enqueue_script('owl-init', sar_js_url('owl-init.js'), ['jquery', 'owl-carousel'], SAR_VERSION, true);

        /**
         * Toggles
         */
        wp_enqueue_script('why-chose-us-toggles-min', sar_js_url('why-chose-us-toggles.min.js'));
    } /**
     * Services pages
     */
    else if (is_page([
        'mobile-development',
        'web-development',
        '3d-development',
        'database-development',
        'iot-solutions',
        'devops-services',
        'custom-development',
        'project-takeovers',
    ])) {
        wp_enqueue_style('sar-services', sar_css_scoped_url('services.min.css'), ['sar-layout'], SAR_VERSION);
        /**
         * Owl Carousel stuff
         */
        wp_enqueue_style('owl-carousel-min', sar_css_scoped_url('owl.carousel.min.css'), ['sar-layout'], SAR_VERSION);
        wp_enqueue_style('owl-theme-default-min', sar_css_scoped_url('owl.theme.default.min.css'), ['sar-layout'], SAR_VERSION);
        wp_enqueue_script('owl-carousel', sar_js_url('owl.carousel.min.js'), ['jquery'], SAR_VERSION, true);
        wp_enqueue_script('owl-init', sar_js_url('owl-init.min.js'), ['jquery', 'owl-carousel'], SAR_VERSION, true);

        wp_enqueue_script('counter-start', sar_js_url('counter-start.min.js'), ['jquery'], SAR_VERSION, true);

//        if (is_page(['project-takeovers'])) {
//            wp_enqueue_script('why-chose-us-toggles-min', sar_js_url('why-chose-us-toggles.min.js'));
//        }
    } /**
     * Technologies pages
     */
    else if (is_page([
        'python-development',
        'net-development',
        'php-development',
        'react-js-development',
        'angular-js-development',
        'kotlin-development',
        'swift-development',
        'c-development',
        'lamp-development',
        'unity-development',
        'laravel-development',
        'django-development',
    ])) {
        wp_enqueue_style('sar-technologies', sar_css_scoped_url('technologies.min.css'), ['sar-layout'], SAR_VERSION);

        /**
         * Owl Carousel stuff
         */
        wp_enqueue_style('owl-carousel-min', sar_css_scoped_url('owl.carousel.min.css'), ['sar-layout'], SAR_VERSION);
        wp_enqueue_style('owl-theme-default-min', sar_css_scoped_url('owl.theme.default.min.css'), ['sar-layout'], SAR_VERSION);
        wp_enqueue_script('owl-carousel', sar_js_url('owl.carousel.min.js'), ['jquery'], SAR_VERSION, true);
        wp_enqueue_script('owl-init', sar_js_url('owl-init.min.js'), ['jquery', 'owl-carousel'], SAR_VERSION, true);

        /**
         * Counter
         */
        wp_enqueue_script('counter-start', sar_js_url('counter-start.min.js'), ['jquery'], SAR_VERSION, true);

        /**
         * Toggles
         */
        wp_enqueue_script('why-chose-us-toggles-min', sar_js_url('why-chose-us-toggles.min.js'));
    } /**
     * Subpages
     */
    else if (is_page([
        //Mobile Dev
        'ios-development',
        'android-development',
        'apple-pay-integration',
        'apple-watch-app-development',
        'cross-platform',

        //Web Dev
        'ecommerce',
        'custom-crm-development',
        'content-management-systems',

        //3D
        'virtual-reality',
        'augmented-reality',

        //Custom
        'aws-development-services',

    ])) {
        wp_enqueue_style('services-sub', sar_css_scoped_url('services-sub.min.css'), ['sar-layout']);

        wp_enqueue_style('owl-carousel-min', sar_css_scoped_url('owl.carousel.min.css'), ['sar-layout'], SAR_VERSION);
        wp_enqueue_style('owl-theme-default-min', sar_css_scoped_url('owl.theme.default.min.css'), ['sar-layout'], SAR_VERSION);
        wp_enqueue_script('owl-carousel', sar_js_url('owl.carousel.min.js'), ['jquery'], SAR_VERSION, true);
        wp_enqueue_script('owl-init', sar_js_url('owl-init.min.js'), ['jquery', 'owl-carousel'], SAR_VERSION, true);
    } /**
     * 404
     */
    else if (is_404()) {
        wp_enqueue_style('sar-404', sar_css_scoped_url('404.min.css'), ['sar-layout'], SAR_VERSION);
    } else if (is_page([
        'design1',
        'design2',
    ])) {
        wp_enqueue_style('test-design', sar_css_scoped_url('design1.min.css'), ['sar-layout']);

        /**
         * Owl Carousel stuff
         */
        wp_enqueue_style('owl-carousel-min', sar_css_scoped_url('owl.carousel.min.css'), ['sar-layout'], SAR_VERSION);
        wp_enqueue_style('owl-theme-default-min', sar_css_scoped_url('owl.theme.default.min.css'), ['sar-layout'], SAR_VERSION);
        wp_enqueue_script('owl-carousel', sar_js_url('owl.carousel.min.js'), ['jquery'], SAR_VERSION, true);
        wp_enqueue_script('owl-init', sar_js_url('owl-init.min.js'), ['jquery', 'owl-carousel'], SAR_VERSION, true);

        /**
         * Toggles
         */
        wp_enqueue_script('why-chose-us-toggles-min', sar_js_url('why-chose-us-toggles.min.js'));

        wp_enqueue_script('counter-start', sar_js_url('counter-start.min.js'), ['jquery'], SAR_VERSION, true);
    } /**
     * Default
     */
    else {
        wp_enqueue_style('sar-default', sar_css_scoped_url('default.min.css'), ['sar-layout'], SAR_VERSION);
    }

}, 15);

/**
 * Preload images
 */
add_action('wp_head', function () {
    $theme_dir = get_template_directory_uri();
    if (is_front_page()) {
        echo '<link rel="preload" href="' . $theme_dir . '/includes/assets/images/Home_BG.webp" as="image">';
    }
});

/**
 * Dequeue unnecessary styles and scripts
 */
add_action('wp_enqueue_scripts', function () {

    // Gutenberg block
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');

    // Js composer
    wp_dequeue_style('js_composer_front');
    wp_deregister_style('js_composer_front');
    wp_dequeue_script('wpb_composer_front_js');
    wp_deregister_script('wpb_composer_front_js');

    // Contact form
    wp_dequeue_style('contact-form-7');

    //Related Post plugin
    wp_dequeue_style('related-post');
    wp_deregister_style('related-post');
});

/**
 * * Dequeue Mailchimp styles (it worked out only on this hook)
 */
add_action('wp_footer', function () {
    wp_dequeue_style('yikes-inc-easy-mailchimp-public-styles');
    wp_deregister_style('yikes-inc-easy-mailchimp-public-styles');
});

/**
 * Disable js-composer script
 */
add_action('wp_print_scripts', function () {
    wp_dequeue_script('vc-backend-min-js');
    wp_deregister_script('vc-backend-min-js');
});

/**
 * Enqueue styles
 */
add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style('sar-backend', sar_css_url('backend/backend.min.css'), [], SAR_VERSION);
    wp_enqueue_style('sar-js-composer', sar_css_url('backend/js_composer_backend_editor.min.css'), [], SAR_VERSION);

    /**
     * Enabling custom js-composer script after disabling plugin script (see above)
     */
    wp_register_script('sar-backend-min', sar_js_url('saritasa-backend.min.js'), ['jquery', 'vc_accordion_script', 'vc-backend-actions-js'], SAR_VERSION, true);
    wp_enqueue_script('sar-backend-min', sar_js_url('saritasa-backend.min.js'), ['jquery', 'vc_accordion_script', 'vc-backend-actions-js'], SAR_VERSION, true);
});

/**
 * Register sidebars
 */
register_sidebar([
    'name' => 'Footer Area 1',
    'id' => 'footer-area-1',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h4>',
    'after_title' => '</h4>',
]);

register_sidebar([
    'name' => 'Footer Area 2',
    'id' => 'footer-area-2',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h4>',
    'after_title' => '</h4>',
]);

register_sidebar([
    'name' => 'Footer Area 3',
    'id' => 'footer-area-3',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h4>',
    'after_title' => '</h4>',
]);

register_sidebar([
    'name' => 'Contact Form Sidebar',
    'id' => "cf-sidebar",
    'description' => 'Used on Services, Technologies pages and pop-up form',
    'class' => '',
    'before_widget' => '',
    'after_widget' => '',
    'before_title' => '',
    'after_title' => '',
]);

add_action('wp_footer', function () {
    if (!is_page([
        //Services
        'mobile-development',
        'web-development',
        '3d-development',
        'database-development',
        'iot-solutions',
        'devops-services',
        'custom-development',
        'project-takeovers',

        //Services-sub
        'ios-development',
        'android-development',
        'apple-pay-integration',
        'apple-watch-app-development',
        'cross-platform',
        'ecommerce',
        'custom-crm-development',
        'content-management-systems',
        'virtual-reality',
        'augmented-reality',
        'aws-development-services',

        //Technologies
        'python-development',
        'net-development',
        'php-development',
        'react-js-development',
        'angular-js-development',
        'kotlin-development',
        'swift-development',
        'c-development',
        'lamp-development',
        'unity-development',
        'laravel-development',
        'django-development',

        //test industries pages
        'design1',
        'design2',
    ])) { ?>
        <div class="sar-popup-overlay">
            <div class="sar-popup-form">
                <div class="form-header">
                    <a href="#" class="btn-close">⨯</a>
                    <h1>Want To Talk About Your Project?</h1>
                    <p>You Have Questions. We Have Answers.</p>
                </div>
                <div class="contact-form">
                    <?php echo do_shortcode('[contact-form-7 id="41702" html_id="form-contact" title="Scope My Project Form Capabilities"]'); ?>
                </div>
                <div class="form-footer">
                    <?php dynamic_sidebar('cf-sidebar'); ?>
                </div>
            </div>
        </div>
        <?php
    }
});

/**
 * Register menus
 */
register_nav_menus([
    'top_nav' => 'Top Navigation Menu',
    'secondary_nav' => 'Secondary Navigation Menu',
]);

/**
 * Allow svg upload
 */
add_filter('upload_mimes', function ($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
});

/**
 * @return array
 * Used in theRelatedPosts below
 */
function getRelatedPostsIdsList()
{
    global $post;
    $posts_ids = [];

    if (function_exists('pprp_post_ids_by_tax_terms')) {
        /**
         * Related Post plugin function (by PickPlugins)
         */
        $posts_ids = pprp_post_ids_by_tax_terms($post->ID);
    }

    $related_posts = get_post_meta($post->ID, 'related_post_ids', true);

    if (!empty($related_posts)) {
        $posts_ids = array_merge($related_posts, $posts_ids);
    }

    return array_map('abs', $posts_ids);
}

/**
 * Render related posts. Used in single.php
 */
function theRelatedPosts()
{
    $related_posts = getRelatedPostsIdsList();
    $settings = get_option('related_post_settings');

    if (isset($related_posts) && count($related_posts)) {
        $args = [
            'post_type' => 'post',
            'post_status' => 'publish',
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
            'post__in' => $related_posts,
            'posts_per_page' => $settings['max_post_count'],
        ];
        ?>

        <div class="recommended">
            <div class="col span_12">
                <h3>Recommended For You</h3>
                <?php

                global $post;
                $related_posts = get_posts($args);

                foreach ($related_posts as $related_post) {
                    $post = $related_post;
                    setup_postdata($related_post);
                    get_template_part('includes/templates/resources-post-item');
                }

                wp_reset_postdata();
                ?>
            </div>
        </div>
        <?php
    }
}

function get_additional_css_from_db($post_ID)
{
    $post_custom_css = get_post_meta($post_ID, '_wpb_shortcodes_custom_css', true);
    if (!empty($post_custom_css)) {
        $post_custom_css = wp_strip_all_tags($post_custom_css);
        echo '<style data-type="vc_custom-css">' . $post_custom_css . '</style>';
    }
}

/**
 * @param $slug
 * @return void
 * used in single-portfolio.php
 */
function get_page_block_template_by_slug($slug)
{
    $posts_arr = get_posts([
        'name' => $slug,
        'post_type' => 'vc_templates',
        'numberposts' => 1,
        'post_status' => 'publish',
    ]);

    if (!empty($posts_arr)) {
        $post_template = array_shift($posts_arr);
        get_additional_css_from_db($post_template->ID);
        echo do_shortcode($post_template->post_content);
    }
}

/**
 * Disable the emoji's
 */
function disable_emojis()
{
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

    // Remove from TinyMCE
    add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
}

add_action('init', 'disable_emojis');

/**
 * Filter out the tinymce emoji plugin.
 */
function disable_emojis_tinymce($plugins)
{
    if (is_array($plugins)) {
        return array_diff($plugins, ['wpemoji']);
    } else {
        return [];
    }
}

/**
 * Saritasa Subscription
 */
add_shortcode('saritasa_subscription', function () {

    ob_start();
    if (function_exists('process_mailchimp_shortcode') && ($subscription_form_id = (int)get_option('mailchimp_subscription_form'))): ?>
        <div class="subscription">
            <h2 class="heading-w-lines">Get Empowered!</h2>
            <div class="col span_12">
                <div class="subscription-form">
                    <div class="description">
                        <div class="description-icon"></div>
                        <div class="description-text">
                            <p class="headline">
                                Receive industry insights, tips, and advice from Saritasa.
                            </p>
                            <span>We publish new articles 1-2 times a month, sign up today.</span>
                        </div>
                    </div>
                    <div class="fields">
                        <?php echo process_mailchimp_shortcode(['form' => $subscription_form_id]); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif;

    return ob_get_clean();
});

/**
 * Add redirect to portfolio permalink
 */
//add_action('template_redirect', function () {
//    $uri = $_SERVER['REQUEST_URI'];
//    $old_slug = '/projects';
//    $new_slug = '/portfolio';
//
//    if (is_404() && stripos($uri, $new_slug) !== false) {
//        $uri = preg_replace('/(^\/|\/$)/', '', $uri);
//        $uriArr = explode('/', $uri);
//        $project_types = array_map(function ($type) {
//            return $type->slug;
//        }, get_terms(['taxonomy' => 'project-type']));
//
//        if (in_array(end($uriArr), $project_types)) {
//            $new_slug = "{$new_slug}?type=" . end($uriArr);
//        }
//
//        wp_redirect(site_url($new_slug));
//        exit();
//    } else if (stripos($uri, $old_slug) !== false) {
//        wp_redirect(site_url(str_replace($old_slug, $new_slug, $uri)));
//        exit();
//    }
//}, 1);