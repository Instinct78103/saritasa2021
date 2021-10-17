<?php

extract(shortcode_atts([
    "link_style" => "play_button",
    'hover_effect' => 'default',
    "font_style" => "p",
    "video_url" => '#',
    "video_button_title" => '',
    "video_button_subtitle" => '',
    "link_text" => "",
    "play_button_color" => "default",
    "nectar_button_color" => "default",
    'nectar_play_button_color' => 'Accent-Color',
//    'image_url' => '',
    'border_radius' => 'none',
    'play_button_size' => 'default',
    'box_shadow' => '',
], $atts));

if ($link_style == 'play_button_2') {

//    $image = '';
//    if (!empty($image_url)) {
//        if (!preg_match('/^\d+$/', $image_url)) {
//            $image = '<img src="' . $image_url . '" alt="video preview" />';
//        } else {
//            $image = wp_get_attachment_image_url($image_url, 'full');
//        }
//    }
//    echo '<div class="video-box" style="background-image: url(' . $image . ')">';

    echo '<div class="video-box">';
}

echo
    '<div class="play-button">
        <a href="' . $video_url . '"></a>
        <h3>' . $video_button_title . '</h3>
        <p>' . $video_button_subtitle . '</p>
    </div>';

if ($link_style == 'play_button_2') {
    echo '</div>';
}