<?php

/**
 * Get js url
 * @param string $file_name (js file name)
 * @return string
 */
function sar_js_url(string $file_name): string
{
    return SAR_ASSETS_JS_URL . '/' . $file_name;
}

/**
 * Get css url
 * @param string $file_name (css file name)
 * @return string
 */
function sar_css_url(string $file_name): string
{
    return SAR_ASSETS_CSS_URL . '/' . $file_name;
}

/**
 * Get css url depends on scope
 * @param string $file_name (css file name)
 * @return string
 */
function sar_css_scoped_url(string $file_name): string
{
    return SAR_ASSETS_CSS_URL . (sar_is_amp_endpoint() ? '/amp/' : '/default/') . $file_name;
}

/**
 * Get image url
 * @param string $file_name (image file name)
 * @return string
 */
function sar_image_url(string $file_name): string
{
    return SAR_ASSETS_IMAGES_URL . '/' . $file_name;
}

/**
 * Get icon url
 * @param string $file_name (image file name)
 * @return string
 */
function sar_icon_url(string $file_name): string
{
    return SAR_ASSETS_ICONS_URL . '/' . $file_name;
}

/**
 * Get no image url
 * @return string
 */
function sar_no_image_url(): string
{
    return sar_image_url('noimage.png');
}

/**
 * Get template
 * @param string $template
 * @param array $args
 * @return string
 */
function sar_get_template(string $template, array $args = []): string
{
    ob_start();

    if (!empty($args)) {
        extract($args);
    }

    include(SAR_TEMPLATES . "/$template.php");

    return ob_get_clean();
}

/**
 * Underscore camelized string
 * @param string $string
 * @param string $explodeDelimiter
 * @param string $implodeDelimiter
 * @return string
 */
function sar_underscore(string $string, string $explodeDelimiter = '\\', string $implodeDelimiter = '/'): string
{
    $data = [];
    $chunks = array_filter(explode($explodeDelimiter, $string));

    foreach ($chunks as $chunk) {
        $data[] = strtolower(preg_replace('/(.)([A-Z])/', "$1-$2", $chunk));
    }

    return implode($implodeDelimiter, $data);
}

/**
 * Return true if it is AMP endpoint
 * @return bool
 */
function sar_is_amp_endpoint(): bool
{
    return function_exists('is_amp_endpoint') && is_amp_endpoint();
}

/**
 * Return true if current page has shortcode
 * @param string $shortcode
 * @return bool
 */
function sar_has_shortcode(string $shortcode): bool
{
    $queried_object = get_queried_object();
    return $queried_object instanceof WP_Post && has_shortcode($queried_object->post_content, $shortcode);
}
