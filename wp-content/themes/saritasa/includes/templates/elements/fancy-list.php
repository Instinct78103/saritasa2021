<?php

extract(shortcode_atts(array('color' => 'Accent-Color', 'alignment' => 'left' ,'icon_type' => 'standard_dash', 'icon' => 'icon-glass', 'enable_animation' => 'false', 'delay' => ''), $atts));

$icon_markup = null;
$output = '';
$delay = intval($delay);

if($icon_type == 'font_icon'){
    $icon_markup = 'data-list-icon="'.$icon.'" data-animation="'.$enable_animation.'" data-animation-delay="'.$delay.'" data-color="'. strtolower($color).'"';
} else if($icon_type == 'none') {
    $icon_markup = 'data-list-icon="none" data-animation="'.$enable_animation.'" data-animation-delay="'.$delay.'" data-color="'. strtolower($color).'"';
} else {
    $icon_markup = 'data-list-icon="icon-salient-thin-line" data-animation="'.$enable_animation.'" data-animation-delay="'.$delay.'" data-color="'. strtolower($color).'"';
}

$output .= '<div class="nectar-fancy-ul" '.$icon_markup.' data-alignment="'.$alignment.'">'.do_shortcode($content).' </div>';

return $output;