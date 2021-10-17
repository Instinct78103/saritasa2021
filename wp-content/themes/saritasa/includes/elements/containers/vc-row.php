<?php
/**
 * Copyright © 2021 Saritasa, LLC. All Rights Reserved.
 */

namespace Sar\Elements\Containers;

use Exception;

/**
 * Class VcRow
 * @package Sar\Elements\Containers
 */
class VcRow
{
    /**
     * Init
     */
    public static function init()
    {
        /**
         * Register element
         */
        add_action('vc_before_init', function () {

            vc_add_shortcode_param('fws_image', function ($param, $value) {
                $param_line = '';
                $param_line .= '<input type="hidden" class="wpb_vc_param_value gallery_widget_attached_images_ids ' . esc_attr($param['param_name']) . ' ' . esc_attr($param['type']) . '" name="' . esc_attr($param['param_name']) . '" value="' . esc_attr($value) . '"/>';
                //$param_line .= '<a class="button gallery_widget_add_images" href="#" use-single="true" title="'.__('Add image', "js_composer").'">'.__('Add image', "js_composer").'</a>';
                $param_line .= '<div class="gallery_widget_attached_images">';
                $param_line .= '<ul class="gallery_widget_attached_images_list">';

                if (strpos($value, "http://") !== false || strpos($value, "https://") !== false) {
                    //$param_value = fjarrett_get_attachment_id_by_url($param_value);
                    $param_line .= '<li class="added">
					<img src="' . esc_attr($value) . '" />
					<a href="#" class="vc_icon-remove"><i class="vc-composer-icon vc-c-icon-close"></i></a>
				</li>';
                } else {
                    $param_line .= ($value != '') ? fieldAttachedImages(explode(",", esc_attr($value))) : '';
                }

                $param_line .= '</ul>';
                $param_line .= '</div>';
                $param_line .= '<div class="gallery_widget_site_images">';
                // $param_line .= siteAttachedImages(explode(",", $param_value));
                $param_line .= '</div>';
                $param_line .= '<a class="gallery_widget_add_images" href="#" use-single="true" title="' . __('Add image', "js_composer") . '">' . __('Add image', "js_composer") . '</a>';//class: button
                //$param_line .= '<div class="wpb_clear"></div>';

                return $param_line;
            });

            static::register_element();
        });
    }

    /**
     * Register element
     */
    protected static function register_element(): bool
    {
        try {
            /**
             * Override element template
             */
            vc_map_update('vc_row', [
                'html_template' => SAR_TEMPLATES . '/elements/containers/vc-row.php',
            ]);

            return vc_map([
                'name' => __('Row', 'js_composer'),
                'base' => 'vc_row',
                'is_container' => true,
                'weight' => 11,
                'icon' => 'icon-wpb-row',
                'show_settings_on_create' => false,
                'category' => __('Structure', 'js_composer'),
                'description' => __('Place content elements inside the row', 'js_composer'),
                'params' => [
                    [
                        'type' => 'dropdown',
                        'heading' => 'Select Page Block Template',
                        'description' => 'Attention! This option excludes ALL other options below (if not empty)',
                        'param_name' => 'block_id',
                        'value' => [''] + \Sar\Elements\SaritasaPageBlockTemplate::get_page_block_templates(),
                        'admin_label' => true,
                    ],
                    [
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => 'Type',
                        'param_name' => 'type',
                        'save_always' => true,
                        'value' => [
                            'In Container' => 'in_container',
                            'Full Width Background' => 'full_width_background',
                            'Full Width Content' => 'full_width_content',
                        ],
                    ],
                    [
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => 'Fullscreen Row Position',
                        'param_name' => 'full_screen_row_position',
                        'save_always' => true,
                        'description' => __('Select how your content will be aligned in the fullscreen row - if full height is selected, columns will be 100% of the screen height as well.', 'js_composer'),
                        'value' => [
                            'Middle' => 'middle',
                            'Top' => 'top',
                            'Bottom' => 'bottom',
                            'Full Height' => 'full_height',
                        ],
                    ],
                    [
                        'type' => 'checkbox',
                        'heading' => __('Equal height', 'js_composer'),
                        'param_name' => 'equal_height',
                        'description' => __('If checked columns will be set to equal height.', 'js_composer'),
                        'value' => [__('Yes', 'js_composer') => 'yes'],
                    ],
                    [
                        'type' => 'dropdown',
                        'heading' => __('Column Content position', 'js_composer'),
                        'param_name' => 'content_placement',
                        'value' => [
                            __('Default', 'js_composer') => '',
                            __('Top', 'js_composer') => 'top',
                            __('Middle', 'js_composer') => 'middle',
                            __('Bottom', 'js_composer') => 'bottom',
                        ],
                        'description' => __('Select content position within columns.', 'js_composer'),
                        'dependency' => ['element' => 'equal_height', 'not_empty' => true],
                    ],
                    [
                        'type' => 'checkbox',
                        'class' => '',
                        'heading' => 'Vertically Center Columns',
                        'value' => ['Make all columns in this row vertically centered?' => 'true'],
                        'param_name' => 'vertically_center_columns',
                        'description' => '',
                        'dependency' => ['element' => 'type', 'value' => ['full_width_content']],
                    ],
                    [
                        'type' => 'fws_image',
                        'class' => '',
                        'heading' => 'Background Image',
                        'param_name' => 'bg_image',
                        'value' => '',
                        'description' => '',
                        'dependency' => ['element' => 'mouse_based_parallax_bg', 'is_empty' => true],
                    ],
                    [
                        'type' => 'checkbox',
                        'class' => '',
                        'heading' => 'Background Image Mobile Hidden',
                        'param_name' => 'background_image_mobile_hidden',
                        'value' => ['Hide Background Image on Mobile Views?' => 'true'],
                        'description' => 'Use this to remove your row BG image from displaying on mobile devices',
                        'dependency' => ['element' => 'bg_image', 'not_empty' => true],
                    ],
                    [
                        'type' => 'dropdown',
                        'class' => '',
                        'save_always' => true,
                        'heading' => 'Background Position',
                        'param_name' => 'bg_position',
                        'value' => [
                            '',
                            'Left Top' => 'left top',
                            'Left Center' => 'left center',
                            'Left Bottom' => 'left bottom',
                            'Center Top' => 'center top',
                            'Center Center' => 'center center',
                            'Center Bottom' => 'center bottom',
                            'Right Top' => 'right top',
                            'Right Center' => 'right center',
                            'Right Bottom' => 'right bottom',
                        ],
                        'dependency' => ['element' => 'bg_image', 'not_empty' => true],
                    ],
                    [
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => 'Background Repeat',
                        'param_name' => 'bg_repeat',
                        'save_always' => true,
                        'value' => [
                            'No Repeat' => 'no-repeat',
                            'Repeat' => 'repeat',
                        ],
                        'dependency' => ['element' => 'bg_image', 'not_empty' => true],
                    ],
                    [
                        'type' => 'checkbox',
                        'heading' => __('Full height row?', 'js_composer'),
                        'param_name' => 'full_height',
                        'description' => __('If checked row will be set to full height.', 'js_composer'),
                        'value' => [__('Yes', 'js_composer') => 'yes'],
                    ],
                    [
                        'type' => 'dropdown',
                        'heading' => __('Columns position', 'js_composer'),
                        'param_name' => 'columns_placement',
                        'value' => [
                            __('Middle', 'js_composer') => 'middle',
                            __('Top', 'js_composer') => 'top',
                            __('Bottom', 'js_composer') => 'bottom',
                            __('Stretch', 'js_composer') => 'stretch',
                        ],
                        'description' => __('Select columns position within row.', 'js_composer'),
                        'dependency' => [
                            'element' => 'full_height',
                            'not_empty' => true,
                        ],
                    ],
                    [
                        'type' => 'checkbox',
                        'class' => '',
                        'heading' => 'Parallax Background',
                        'value' => ['Enable Parallax Background?' => 'true'],
                        'param_name' => 'parallax_bg',
                        'description' => '',
                        'dependency' => ['element' => 'bg_image', 'not_empty' => true],
                    ],
                    [
                        'type' => 'dropdown',
                        'class' => '',
                        'description' => 'The faster you choose, the closer your BG will match the users scroll speed',
                        'heading' => 'Parallax Background Speed',
                        'param_name' => 'parallax_bg_speed',
                        'save_always' => true,
                        'value' => [
                            'Slow' => 'slow',
                            'Medium' => 'medium',
                            'Fast' => 'fast',
                            'Fixed' => 'fixed',
                        ],
                        'dependency' => ['element' => 'parallax_bg', 'not_empty' => true],
                    ],
                    [
                        'type' => 'colorpicker',
                        'class' => '',
                        'heading' => 'Background Color',
                        'param_name' => 'bg_color',
                        'value' => '',
                        'description' => '',
                    ],
                    [
                        'type' => 'checkbox',
                        'class' => '',
                        'heading' => 'Mouse Based Parallax Scene',
                        'value' => ['Enable Mouse Based Parallax BG?' => 'true'],
                        'param_name' => 'mouse_based_parallax_bg',
                        'description' => '',
                    ],
                    [
                        'type' => 'dropdown',
                        'heading' => __('Scene Positioning', 'js_composer'),
                        'param_name' => 'scene_position',
                        'save_always' => true,
                        'value' => [
                            'Center' => 'center',
                            'Top' => 'top',
                            'Bottom' => 'bottom',
                        ],
                        'description' => __('Select your desired scene alignment within your row', 'js_composer'),
                    ],
                    [
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => 'Scene Parallax Overall Strength',
                        'value' => '',
                        'param_name' => 'mouse_sensitivity',
                        'description' => 'Enter a number between 1 and 25 that will effect the overall strength of the parallax movement within the entire scene - the default is 10.',
                    ],
                    [
                        'type' => 'fws_image',
                        'class' => '',
                        'heading' => 'Scene Layer One',
                        'param_name' => 'layer_one_image',
                        'value' => '',
                        'description' => 'Please upload all of your layers at the same dimensions to ensure accurate placement.',
                    ],
                    [
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => 'Layer One Strength',
                        'value' => '',
                        'param_name' => 'layer_one_strength',
                        'description' => 'Enter a number <strong>between 0 and 1</strong> that will determine the strength this layer responds to mouse movement. <br/><br/>By default each layer will increment by .2',
                    ],
                    [
                        'type' => 'fws_image',
                        'class' => '',
                        'heading' => 'Scene Layer Two',
                        'param_name' => 'layer_two_image',
                        'value' => '',
                        'description' => '',
                    ],
                    [
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => 'Layer Two Strength',
                        'value' => '',
                        'param_name' => 'layer_two_strength',
                        'description' => 'See the description on \'Layer One Strength\' for guidelines on this property.',
                    ],
                    [
                        'type' => 'fws_image',
                        'class' => '',
                        'heading' => 'Scene Layer Three',
                        'param_name' => 'layer_three_image',
                        'value' => '',
                        'description' => '',
                    ],
                    [
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => 'Layer Three Strength',
                        'value' => '',
                        'param_name' => 'layer_three_strength',
                        'description' => 'See the description on \'Layer One Strength\' for guidelines on this property.',
                    ],
                    [
                        'type' => 'fws_image',
                        'class' => '',
                        'heading' => 'Scene Layer Four',
                        'param_name' => 'layer_four_image',
                        'value' => '',
                        'description' => '',
                    ],
                    [
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => 'Layer Four Strength',
                        'value' => '',
                        'param_name' => 'layer_four_strength',
                        'description' => 'See the description on \'Layer One Strength\' for guidelines on this property.',
                    ],
                    [
                        'type' => 'fws_image',
                        'class' => '',
                        'heading' => 'Scene Layer Five',
                        'param_name' => 'layer_five_image',
                        'value' => '',
                        'description' => '',
                    ],
                    [
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => 'Layer Five Strength',
                        'value' => '',
                        'param_name' => 'layer_five_strength',
                        'description' => 'See the description on \'Layer One Strength\' for guidelines on this property.',
                    ],
                    [
                        'type' => 'checkbox',
                        'class' => '',
                        'heading' => 'Video Background',
                        'value' => ['Enable Video Background?' => 'use_video'],
                        'param_name' => 'video_bg',
                        'description' => '',
                    ],
                    [
                        'type' => 'checkbox',
                        'class' => '',
                        'heading' => 'Video Color Overlay',
                        'value' => ['Enable a color overlay ontop of your video?' => 'true'],
                        'param_name' => 'enable_video_color_overlay',
                        'description' => '',
                        'dependency' => ['element' => 'video_bg', 'value' => ['use_video']],
                    ],
                    [
                        'type' => 'colorpicker',
                        'class' => '',
                        'heading' => 'Overlay Color',
                        'param_name' => 'video_overlay_color',
                        'value' => '',
                        'description' => '',
                        'dependency' => ['element' => 'enable_video_color_overlay', 'value' => ['true']],
                    ],
                    [
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => 'Youtube Video URL',
                        'value' => '',
                        'param_name' => 'video_external',
                        'description' => 'Can be used as an alternative to self hosted videos. Enter full video URL e.g. https://www.youtube.com/watch?v=6oTurM7gESE',
                        'dependency' => ['element' => 'video_bg', 'value' => ['use_video']],
                    ],
                    [
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => 'WebM File URL',
                        'value' => '',
                        'param_name' => 'video_webm',
                        'description' => 'You must include this format & the mp4 format to render your video with cross browser compatibility. OGV is optional. Video must be in a 16:9 aspect ratio.',
                        'dependency' => ['element' => 'video_bg', 'value' => ['use_video']],
                    ],
                    [
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => 'MP4 File URL',
                        'value' => '',
                        'param_name' => 'video_mp4',
                        'description' => 'Enter the URL for your mp4 video file here',
                        'dependency' => ['element' => 'video_bg', 'value' => ['use_video']],
                    ],
                    [
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => 'OGV File URL',
                        'value' => '',
                        'param_name' => 'video_ogv',
                        'description' => 'Enter the URL for your ogv video file here',
                        'dependency' => ['element' => 'video_bg', 'value' => ['use_video']],
                    ],
                    [
                        'type' => 'attach_image',
                        'class' => '',
                        'heading' => 'Video Preview Image',
                        'value' => '',
                        'param_name' => 'video_image',
                        'description' => '',
                        'dependency' => ['element' => 'video_bg', 'value' => ['use_video']],
                    ],
                    [
                        'type' => 'dropdown',
                        'class' => '',
                        'heading' => 'Text Color',
                        'param_name' => 'text_color',
                        'value' => [
                            'Dark' => 'dark',
                            'Light' => 'light',
                            'Custom' => 'custom',
                        ],
                        'save_always' => true,
                    ],
                    [
                        'type' => 'colorpicker',
                        'class' => '',
                        'heading' => 'Custom Text Color',
                        'param_name' => 'custom_text_color',
                        'value' => '',
                        'description' => '',
                        'dependency' => ['element' => 'text_color', 'value' => ['custom']],
                    ],
                    [
                        'type' => 'dropdown',
                        'class' => '',
                        'save_always' => true,
                        'heading' => 'Text Alignment',
                        'param_name' => 'text_align',
                        'value' => [
                            'Left' => 'left',
                            'Center' => 'center',
                            'Right' => 'right',
                        ],
                    ],
                    [
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => 'Padding Top',
                        'value' => '',
                        'param_name' => 'top_padding',
                        'description' => 'Don\'t include \'px\' in your string. e.g \'40\' - However you can also use a percent value in which case a \'%\' would be needed at the end e.g. \'10%\'',
                    ],
                    [
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => 'Padding Bottom',
                        'value' => '',
                        'param_name' => 'bottom_padding',
                        'description' => 'Don\'t include \'px\' in your string. e.g \'40\' - However you can also use a percent value in which case a \'%\' would be needed at the end e.g. \'10%\'',
                    ],
                    [
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => 'Translate Y',
                        'value' => '',
                        'param_name' => 'translate_y',
                        'description' => '',
                    ],
                    [
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => 'Translate X',
                        'value' => '',
                        'param_name' => 'translate_x',
                        'description' => '',
                    ],
                    [
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => 'Z-Index',
                        'param_name' => 'zindex',
                        'description' => 'If you want to set a custom stacking order on this row, enter it here. Can be useful when overlapping elements from other rows with negative margins/translates.',
                        'value' => '',
                    ],
                    [
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => 'Extra Class Name',
                        'param_name' => 'class',
                        'value' => '',
                    ],
                    [
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => 'Row ID',
                        'param_name' => 'id',
                        'value' => '',
                        'description' => 'Use this to option to add an ID onto your row. This can then be used to target the row with CSS or as an anchor point to scroll to when the relevant link is clicked.',
                    ],
                    [
                        'type' => 'textfield',
                        'class' => '',
                        'heading' => 'Row Name',
                        'param_name' => 'row_name',
                        'value' => '',
                        'description' => 'This will be shown in your dot navigation when using the Fullscreen Row option',
                    ],
                    [
                        'type' => 'checkbox',
                        'heading' => __('Disable Ken Burns BG effect', 'js_composer'),
                        'param_name' => 'disable_ken_burns', // Inner param name.
                        'description' => __('If checked the ken burns background zoom effect will not occur on this row.', 'js_composer'),
                        'value' => [__('Yes', 'js_composer') => 'yes'],
                    ],
                    [
                        'type' => 'checkbox',
                        'heading' => __('Disable row', 'js_composer'),
                        'param_name' => 'disable_element', // Inner param name.
                        'description' => __('If checked the row won\'t be visible on the public side of your website. You can switch it back any time.', 'js_composer'),
                        'value' => [__('Yes', 'js_composer') => 'yes'],
                    ],
                    [
                        'type' => 'checkbox',
                        'class' => '',
                        'group' => 'Color Overlay',
                        'heading' => 'Enable Gradient?',
                        'value' => ['Yes, please' => 'true'],
                        'param_name' => 'enable_gradient',
                        'description' => '',
                    ],
                    [
                        'type' => 'colorpicker',
                        'class' => '',
                        'heading' => 'Color Overlay',
                        'param_name' => 'color_overlay',
                        'value' => '',
                        'group' => 'Color Overlay',
                        'description' => '',
                    ],
                    [
                        'type' => 'colorpicker',
                        'class' => '',
                        'heading' => 'Color Overlay 2',
                        'param_name' => 'color_overlay_2',
                        'value' => '',
                        'group' => 'Color Overlay',
                        'description' => '',
                        'dependency' => ['element' => 'enable_gradient', 'not_empty' => true],
                    ],
                    [
                        'type' => 'dropdown',
                        'class' => '',
                        'save_always' => true,
                        'heading' => 'Gradient Direction',
                        'param_name' => 'gradient_direction',
                        'group' => 'Color Overlay',
                        'value' => [
                            'Left to Right' => 'left_to_right',
                            'Left Top to Right Bottom' => 'left_t_to_right_b',
                            'Left Bottom to Right Top' => 'left_b_to_right_t',
                            'Bottom to Top' => 'top_to_bottom',
                        ],
                        'dependency' => ['element' => 'enable_gradient', 'not_empty' => true],
                    ],
                    [
                        'type' => 'dropdown',
                        'class' => '',
                        'save_always' => true,
                        'group' => 'Color Overlay',
                        'heading' => 'Overlay Strength',
                        'param_name' => 'overlay_strength',
                        'value' => [
                            'Light' => '0.3',
                            'Medium' => '0.5',
                            'Heavy' => '0.8',
                            'Very Heavy' => '0.95',
                            'Solid' => '1',
                        ],
                    ],
                    [
                        'type' => 'checkbox',
                        'class' => '',
                        'group' => 'Shape Divider',
                        'heading' => 'Enable Shape Divider',
                        'value' => ['Yes, please' => 'true'],
                        'param_name' => 'enable_shape_divider',
                        'description' => '',
                    ],
                    /*[
                        'type' => 'nectar_radio_image',
                        'class' => '',
                        'save_always' => true,
                        'heading' => 'Shape Type',
                        'param_name' => 'shape_type',
                        'group' => 'Shape Divider',
                        'options' => [
                            'curve' => [__('Curve', 'salient') => $nectar_get_template_directory_uri . '/nectar/nectar-vc-addons/img/shape_dividers/curve_down.jpg'],
                            'fan' => [__('Fan', 'salient') => $nectar_get_template_directory_uri . '/nectar/nectar-vc-addons/img/shape_dividers/fan.jpg'],
                            'curve_opacity' => [__('Curve Opacity', 'salient') => $nectar_get_template_directory_uri . '/nectar/nectar-vc-addons/img/shape_dividers/curve_opacity.jpg'],
                            'mountains' => [__('Mountains', 'salient') => $nectar_get_template_directory_uri . '/nectar/nectar-vc-addons/img/shape_dividers/mountains.jpg'],
                            'curve_asym' => [__('Curve Asym.', 'salient') => $nectar_get_template_directory_uri . '/nectar/nectar-vc-addons/img/shape_dividers/curve_asym.jpg'],
                            'curve_asym_2' => [__('Curve Asym. Alt', 'salient') => $nectar_get_template_directory_uri . '/nectar/nectar-vc-addons/img/shape_dividers/curve_asym_2.jpg'],
                            'tilt' => [__('Tilt', 'salient') => $nectar_get_template_directory_uri . '/nectar/nectar-vc-addons/img/shape_dividers/tilt.jpg'],
                            'tilt_alt' => [__('Tilt Alt', 'salient') => $nectar_get_template_directory_uri . '/nectar/nectar-vc-addons/img/shape_dividers/tilt_alt.jpg'],
                            'triangle' => [__('Triangle', 'salient') => $nectar_get_template_directory_uri . '/nectar/nectar-vc-addons/img/shape_dividers/triangle.jpg'],
                            'waves' => [__('Waves', 'salient') => $nectar_get_template_directory_uri . '/nectar/nectar-vc-addons/img/shape_dividers/waves_no_opacity.jpg'],
                            'waves_opacity' => [__('Waves Opacity', 'salient') => $nectar_get_template_directory_uri . '/nectar/nectar-vc-addons/img/shape_dividers/waves.jpg'],
                            'waves_opacity_alt' => [__('Waves Opacity 2', 'salient') => $nectar_get_template_directory_uri . '/nectar/nectar-vc-addons/img/shape_dividers/waves_opacity.jpg'],
                            'clouds' => [__('Clouds', 'salient') => $nectar_get_template_directory_uri . '/nectar/nectar-vc-addons/img/shape_dividers/clouds.jpg'],
                            'speech' => [__('Speech', 'salient') => $nectar_get_template_directory_uri . '/nectar/nectar-vc-addons/img/shape_dividers/speech.jpg'],
                            'straight_section' => [__('Straight Section', 'salient') => $nectar_get_template_directory_uri . '/nectar/nectar-vc-addons/img/shape_dividers/straight_section.jpg'],
                        ],
                    ],*/
                    [
                        'type' => 'colorpicker',
                        'class' => '',
                        'heading' => 'Shape Divider Color',
                        'param_name' => 'shape_divider_color',
                        'value' => '',
                        'group' => 'Shape Divider',
                        'description' => '',
                    ],
                    [
                        'type' => 'dropdown',
                        'class' => '',
                        'save_always' => true,
                        'heading' => 'Shape Divider Position',
                        'param_name' => 'shape_divider_position',
                        'group' => 'Shape Divider',
                        'value' => [
                            'Bottom' => 'bottom',
                            'Top' => 'top',
                            'Bottom & Top' => 'both',
                        ],
                    ],
                    [
                        'type' => 'textfield',
                        'class' => '',
                        'group' => 'Shape Divider',
                        'heading' => 'Shape Divider Height',
                        'param_name' => 'shape_divider_height',
                        'value' => '',
                        'description' => 'Enter an optional custom height for your shape divider in pixels without the \'px\', e.g. 50',
                    ],
                    [
                        'type' => 'checkbox',
                        'class' => '',
                        'group' => 'Shape Divider',
                        'heading' => 'Bring to front?',
                        'value' => ['Yes, please' => 'true'],
                        'param_name' => 'shape_divider_bring_to_front',
                        'description' => 'This will bring the shape divider to the top layer, placing it on top of any content it intersects/',
                    ],
                ],
                'js_view' => 'VcRowView',
            ]);
        } catch (Exception $e) {
            return false;
        }
    }
}

VcRow::init();
