<?php

add_shortcode('image_with_animation', 'custom_image_with_animation');

/**
 * Image with animation
 *
 * @param $atts
 * @param null $content
 *
 * @return string
 */
function custom_image_with_animation($atts, $content = null)
{
    extract(shortcode_atts([
//        "animation" => 'Fade In',
//        "delay" => '0',
        "image_url" => '',
//        'alt' => '',
//        'margin_top' => '',
//        'margin_right' => '',
//        'margin_bottom' => '',
//        'margin_left' => '',
//        'alignment' => 'left',
//        'border_radius' => '',
//        'img_link_target' => '_self',
//        'img_link' => '',
//        'img_link_large' => '',
//        'box_shadow' => 'none',
//        'box_shadow_direction' => 'middle',
//        'max_width' => '100%',
//        'el_class' => '',
    ], $atts));

    if (preg_match('/^\d+$/', $image_url)) {
        $image_src = wp_get_attachment_image_url($image_url, 'full');
        $image_url = $image_src;
    }

    return '<div class="img-with-animation-wrap"><img src="' . $image_url . '" alt></div>';
}