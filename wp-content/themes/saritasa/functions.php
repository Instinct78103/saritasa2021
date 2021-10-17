<?php

/**
 * Init general project logic
 */
require_once(get_template_directory() . '/includes/init.php');

if (defined('ATTACH_FROM_DEV') && ATTACH_FROM_DEV) {
    if ($_SERVER['REMOTE_ADDR'] === '127.0.0.1') {
        // Replace src paths
        add_filter('wp_get_attachment_url', function ($url) {
            if (file_exists($url)) {
                return $url;
            }
            return str_replace(site_url(), 'https://saritasa2021.saritasa-hosting.com', $url);
        }, 1000);

        // Replace srcset paths
        add_filter('wp_calculate_image_srcset', function ($sources) {
            foreach ($sources as &$source) {
                if (!file_exists($source['url'])) {
                    $source['url'] = str_replace(site_url(), 'https://saritasa2021.saritasa-hosting.com', $source['url']);
                }
            }
            return $sources;
        }, 1000);
    }
}