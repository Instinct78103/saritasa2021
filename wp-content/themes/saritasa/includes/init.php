<?php

/**
 * Define constants
 */
define('SAR_VERSION', '0.0.1');
define('SAR_INCLUDES', dirname(__FILE__));
define('SAR_INCLUDES_ELEMENT', SAR_INCLUDES . '/elements');
define('SAR_TEMPLATES', SAR_INCLUDES . '/templates');
define('SAR_ASSETS_URL', get_stylesheet_directory_uri() . '/includes/assets');
define('SAR_ASSETS_JS_URL', SAR_ASSETS_URL . '/js');
define('SAR_ASSETS_CSS_URL', SAR_ASSETS_URL . '/css');
define('SAR_ASSETS_ICONS_URL', SAR_ASSETS_URL . '/icons');
define('SAR_ASSETS_IMAGES_URL', SAR_ASSETS_URL . '/images');

/**
 * Require functions
 */
require_once(SAR_INCLUDES . '/functions.php');

/**
 * Require portfolio features
 */
require_once(SAR_INCLUDES . '/portfolio.php');

/**
 * Require visual composer containers
 */
require_once(SAR_INCLUDES_ELEMENT . '/containers/vc-row.php');
require_once(SAR_INCLUDES_ELEMENT . '/containers/vc-column.php');
require_once(SAR_INCLUDES_ELEMENT . '/containers/vc-column-inner.php');

/**
 * Saritasa Custom Elements
 */
require_once(SAR_INCLUDES_ELEMENT . '/projects-archive.php');
require_once(SAR_INCLUDES_ELEMENT . '/featured-projects.php');
require_once(SAR_INCLUDES_ELEMENT . '/block-template.php');
require_once(SAR_INCLUDES_ELEMENT . '/image-with-animation__single-portfolio.php');
require_once(SAR_INCLUDES_ELEMENT . '/sar-btn.php');
require_once(SAR_INCLUDES_ELEMENT . '/nectar-icon-list__single-portfolio.php');

/**
 * Require visual composer elements
 */
require_once(SAR_INCLUDES_ELEMENT . '/text-with-icon.php');
require_once(SAR_INCLUDES_ELEMENT . '/carousel_tabbed.php');
require_once(SAR_INCLUDES_ELEMENT . '/related-articles.php');
require_once(SAR_INCLUDES_ELEMENT . '/video-lightbox.php');
require_once(SAR_INCLUDES_ELEMENT . '/fancy-list.php');
require_once(SAR_INCLUDES_ELEMENT . '/card.php');
require_once(SAR_INCLUDES_ELEMENT . '/flip-box.php');
require_once(SAR_INCLUDES_ELEMENT . '/testimonial-slider.php');

/**
 * Require main logic
 */
require_once(SAR_INCLUDES . '/main.php');
require_once(SAR_INCLUDES . '/endpoints.php');