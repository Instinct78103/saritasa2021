<?php

add_shortcode('sar-btn', function ($atts, $content = '') {
    extract(shortcode_atts([
        'url' => '',
        'icon_fontawesome' => '',
        'text' => '',
    ], $atts));

    $content .= '
    <a class="sar-btn" href="' . $url . '" target="_blank">
        <span>' . $text . '</span>
        <i class="' . $icon_fontawesome . '"></i>
    </a>
    ';

    return $content;
});