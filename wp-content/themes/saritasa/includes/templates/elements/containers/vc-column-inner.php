<?php
$output = $el_class = $width = '';
extract(shortcode_atts(array(
    'el_class' => '',
    'width' => '1/1',
    'offset' => '',
    'css' => '',
    "boxed" => 'false', 
    "centered_text" => 'false', 
    'enable_animation' => '',
    'animation' => '', 
    'column_padding' => 'no-extra-padding',
    'column_padding_position'=> 'all',
    'top_margin' => '',
    'bottom_margin' => '',
    'delay' => '0',
    'background_color' => '',
    'background_color_hover' => '',
    'background_hover_color_opacity' => '1',
    'background_color_opacity' => '1',
    'background_image' => '',
    'enable_bg_scale' => '',
    'column_link' => '',
    'column_link_target' => '_self',
    'font_color' => '',
    'column_border_width' => 'none',
    'column_border_color' => '',
    'column_border_style' => '',
    'enable_border_animation' => '',
    'border_animation_delay' => '',
    'column_shadow' => 'none',
    'column_border_radius' => 'none',
    'tablet_width_inherit' => 'default'
), $atts));

//var init
$el_class = $this->getExtraClass($el_class);
$width = wpb_translateColumnWidthToSpan($width);
$width = vc_column_offset_class_merge($offset, $width);
$box_border = null;
$parsed_animation = null;	
$style = 'style="';

$el_class .= ' wpb_column column_container vc_column_container col';
if($boxed == 'true' && empty($background_image) && empty($background_color))  { $el_class .= ' boxed'; $box_border = '<span class="bottom-line"></span>'; }
if($centered_text == 'true') $el_class .= ' centered-text';


//style related
$background_color_string = null;
$has_bg_color = 'false';
if(!empty($background_color)) {
	$background_color_string .= $background_color;	
    $has_bg_color = 'true';
}
if(!empty($background_image)) {
	
      if(!preg_match('/^\d+$/',$background_image)){
                    
        $style .= 'background-image: url('.$background_image . '); ';
    
    } else {

    	$bg_image_src = wp_get_attachment_image_src($background_image, 'full');
    	$style .= ' background-image: url(\''.$bg_image_src[0].'\'); ';
    }
}
$using_custom_font_color = null;
if(!empty($font_color)) { 
    $style .= ' color: '.$font_color.';';
    $using_custom_font_color = 'data-cfc="true"';
}

/*margins*/
if(!empty($top_margin)) {
    //class for neg margin to adjust z-index
    if(strpos($top_margin,'-') !== false) {
        $el_class .= ' neg-marg';
    }
    //actual margin proc
    if(strpos($top_margin,'%') !== false) {
        $style .= 'margin-top: '. $top_margin .'; ';
    } else {
        $style .= 'margin-top: '. $top_margin .'px; ';
    }
}
if(!empty($bottom_margin)) {
    if(strpos($bottom_margin,'%') !== false){
        $style .= 'margin-bottom: '. $bottom_margin .'!important; ';
    } else {    
        $style .= 'margin-bottom: '. $bottom_margin .'px!important; ';
    }
}

(empty($background_color) && empty($background_image) && empty($font_color) && empty($top_margin) && empty($bottom_margin) ) ? $style = null : $style .= '"';

$using_bg = (!empty($background_image) || !empty($background_color)) ? 'data-using-bg="true"': null;

$using_reveal_animation = false;


if(!empty($animation) && $animation != 'none' && $enable_animation == 'true') {
	 $el_class .= ' has-animation';
	
	 $parsed_animation = str_replace(" ","-",$animation);
	 $delay = intval($delay);

      if($animation == 'reveal-from-right' || $animation == 'reveal-from-bottom' || $animation == 'reveal-from-left' || $animation == 'reveal-from-top')
        $using_reveal_animation = true;
}

if($using_reveal_animation == false) $el_class .= ' '. $column_padding;
if($using_reveal_animation == true) {
    $style2 = $style;
    $style = null;
}

$border_html = null;
if(!empty($column_border_width) && $column_border_width != 'none') {
    
  //regular border when using border radius
  if(strpos($column_border_radius, 'px') !== false) {
    
    if($style == null) {
         $style = 'style="border: '. $column_border_width.' solid '.$column_border_color.';"';
     }
    else {
        //remove the ending quotation first since it's already closed
        $style = substr($style,0,-1);
        $style .= 'border: '. $column_border_width.' solid '.$column_border_color.'"';
    }
    
    
  } else {
    
        $column_border_markup = 'border: '. $column_border_width.' solid rgba(255,255,255,0); ';
        if($style == null) {
             $style = 'style="'.$column_border_markup.'"';
         }
        else {
            //remove the ending quotation first since it's already closed
            $style = substr($style,0,-1);
            $style .= $column_border_markup . '"';
        }
        $border_html = '<span class="border-wrap" style="border-color: '.$column_border_color.';"><span class="border-top"></span><span class="border-right"></span><span class="border-bottom"></span><span class="border-left"></span></span>';
   }
   
} else {
    $column_border_markup = null;
}


$column_overlay_style = '';

if(!empty($background_color_string)) {
  
  $column_overlay_style = ' style="';
  
  if(!empty($background_color_opacity)) {
    $column_overlay_style .= 'opacity: '.$background_color_opacity.'; ';
  }
  $column_overlay_style .= 'background-color: '.$background_color_string.';';

  $column_overlay_style .= '"';
  
}


$column_link_html = (!empty($column_link)) ? '<a class="column-link" target="'.$column_link_target.'" href="'.$column_link.'"></a>' : null;
$column_bg_color_html = (!empty($column_link)) ? '<a class="column-link" target="'.$column_link_target.'" href="'.$column_link.'"></a>' : null;
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $width . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
$output .= "\n\t".'<div '.$style.' class="'.$css_class.'" '.$using_custom_font_color.' '.$using_bg.'>' . $column_link_html . $border_html;
$output .= '<div class="column-bg-overlay"'.$column_overlay_style.'></div>';
if($using_reveal_animation == true) { $output .= "\n\t\t".'<div class="column-inner-wrap"><div '.$style2.' data-bg-cover="'.$enable_bg_scale.'" class="column-inner '.$column_padding.'">'; }
else { $output .= "\n\t\t".'<div class="vc_column-inner">'; }
$output .= "\n\t\t".'<div class="wpb_wrapper">';
$output .= "\n\t\t\t".wpb_js_remove_wpautop($content);
$output .= "\n\t\t".'</div> '.$this->endBlockComment('.wpb_wrapper'); 
if($using_reveal_animation == true) { $output .= "\n\t\t".'</div></div>'; }
else { $output .= "\n\t".'</div>'; }
$output .= "\n\t".'</div> '.$this->endBlockComment($el_class) . "\n";

echo $output;