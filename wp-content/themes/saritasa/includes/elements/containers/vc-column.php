<?php
/**
 * Copyright © 2021 Saritasa, LLC. All Rights Reserved.
 */

namespace Sar\Elements\Containers;

use Exception;

/**
 * Class VcColumn
 * @package Sar\Elements\Containers
 */
class VcColumn
{

    public static function init()
    {
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

            self::register_element();
        });
    }

    protected static function register_element(): bool
    {
        try {
            vc_map_update('vc_column', [
                'html_template' => SAR_TEMPLATES . '/elements/containers/vc-column.php'
            ]);

            return vc_map(array(
                'name' => __('Column', 'js_composer'),
                'base' => 'vc_column',
                'is_container' => true,
                'content_element' => false,
                'params' => array(
                    array(
                        "type" => "checkbox",
                        "class" => "",
                        "heading" => "Enable Animation",
                        "value" => array("Enable Column Animation?" => "true"),
                        "param_name" => "enable_animation",
                        "description" => ""
                    ),

                    array(
                        "type" => "dropdown",
                        "class" => "",
                        "heading" => "Animation",
                        "param_name" => "animation",
                        'save_always' => true,
                        "value" => array(
                            "None" => "none",
                            "Fade In" => "fade-in",
                            "Fade In From Left" => "fade-in-from-left",
                            "Fade In Right" => "fade-in-from-right",
                            "Fade In From Bottom" => "fade-in-from-bottom",
                            "Grow In" => "grow-in",
                            "Flip In Horizontal" => "flip-in",
                            "Flip In Vertical" => "flip-in-vertical",
                            "Reveal From Right" => "reveal-from-right",
                            "Reveal From Bottom" => "reveal-from-bottom",
                            "Reveal From Left" => "reveal-from-left",
                            "Reveal From Top" => "reveal-from-top"
                        ),
                        "dependency" => Array('element' => "enable_animation", 'not_empty' => true)
                    ),

                    array(
                        "type" => "textfield",
                        "class" => "",
                        "heading" => "Animation Delay",
                        "param_name" => "delay",
                        "admin_label" => false,
                        "description" => __("Enter delay (in milliseconds) if needed e.g. 150. This parameter comes in handy when creating the animate in \"one by one\" effect.", "js_composer"),
                        "dependency" => Array('element' => "enable_animation", 'not_empty' => true)
                    ),

                    array(
                        "type" => "checkbox",
                        "class" => "",
                        "heading" => "Boxed Column",
                        "value" => array("Boxed Style" => "true"),
                        "param_name" => "boxed",
                        "description" => ""
                    ),

                    array(
                        "type" => "checkbox",
                        "class" => "",
                        "heading" => "Centered Content",
                        "value" => array("Centered Content Alignment" => "true"),
                        "param_name" => "centered_text",
                        "description" => ""
                    ),

                    array(
                        "type" => "dropdown",
                        "class" => "",
                        'save_always' => true,
                        "heading" => "Column Padding",
                        "param_name" => "column_padding",
                        "value" => array(
                            "None" => "no-extra-padding",
                            "1%" => "padding-1-percent",
                            "2%" => "padding-2-percent",
                            "3%" => "padding-3-percent",
                            "4%" => "padding-4-percent",
                            "5%" => "padding-5-percent",
                            "6%" => "padding-6-percent",
                            "7%" => "padding-7-percent",
                            "8%" => "padding-8-percent",
                            "9%" => "padding-9-percent",
                            "10%" => "padding-10-percent",
                            "11%" => "padding-11-percent",
                            "12%" => "padding-12-percent",
                            "13%" => "padding-13-percent",
                            "14%" => "padding-14-percent",
                            "15%" => "padding-15-percent",
                            "16%" => "padding-16-percent",
                            "17%" => "padding-17-percent"
                        ),
                        "description" => "When using the full width content row type or providing a background color/image for the column, you have the option to define the amount of padding your column will receive."
                    ),

                    array(
                        "type" => "dropdown",
                        "class" => "",
                        "heading" => "Column Padding Position",
                        "param_name" => "column_padding_position",
                        'save_always' => true,
                        "value" => array(
                            "All Sides" => 'all',
                            'Top' => "top",
                            'Right' => 'right',
                            'Left' => 'left',
                            'Bottom' => 'bottom',
                            'Left & Right' => 'left-right',
                            'Top & Right' => 'top-right',
                            'Top & Left' => 'top-left',
                            'Top & Bottom' => 'top-bottom',
                            'Bottom & Right' => 'bottom-right',
                            'Bottom & Left' => 'bottom-left'
                        ),
                        "description" => "Use this to fine tune where the column padding will take effect"
                    ),

                    array(
                        "type" => "colorpicker",
                        "class" => "",
                        "heading" => "Background Color",
                        "param_name" => "background_color",
                        "value" => "",
                        "description" => "",
                    ),

                    array(
                        "type" => "dropdown",
                        "class" => "",
                        'save_always' => true,
                        "heading" => "Background Color Opacity",
                        "param_name" => "background_color_opacity",
                        "value" => array(
                            "1" => "1",
                            "0.9" => "0.9",
                            "0.8" => "0.8",
                            "0.7" => "0.7",
                            "0.6" => "0.6",
                            "0.5" => "0.5",
                            "0.4" => "0.4",
                            "0.3" => "0.3",
                            "0.2" => "0.2",
                            "0.1" => "0.1",
                        )

                    ),

                    array(
                        "type" => "colorpicker",
                        "class" => "",
                        "heading" => "Background Color Hover",
                        "param_name" => "background_color_hover",
                        "value" => "",
                        "description" => "",
                    ),

                    array(
                        "type" => "dropdown",
                        "class" => "",
                        'save_always' => true,
                        "heading" => "Background Hover Color Opacity",
                        "param_name" => "background_hover_color_opacity",
                        "value" => array(
                            "1" => "1",
                            "0.9" => "0.9",
                            "0.8" => "0.8",
                            "0.7" => "0.7",
                            "0.6" => "0.6",
                            "0.5" => "0.5",
                            "0.4" => "0.4",
                            "0.3" => "0.3",
                            "0.2" => "0.2",
                            "0.1" => "0.1",
                        )

                    ),

                    array(
                        "type" => "fws_image",
                        "class" => "",
                        "heading" => "Background Image",
                        "param_name" => "background_image",
                        "value" => "",
                        "description" => "",
                    ),

                    array(
                        "type" => "checkbox",
                        "class" => "",
                        "heading" => "Scale Background Image To Column",
                        "value" => array("Enable" => "true"),
                        "param_name" => "enable_bg_scale",
                        "description" => "",
                        "dependency" => Array('element' => "background_image", 'not_empty' => true)
                    ),

                    array(
                        "type" => "colorpicker",
                        "class" => "",
                        "heading" => "Font Color",
                        "param_name" => "font_color",
                        "value" => "",
                        "description" => ""
                    ),

                    array(
                        "type" => "textfield",
                        "class" => "",
                        "heading" => "Column Link",
                        "param_name" => "column_link",
                        "admin_label" => false,
                        "description" => "If you wish for this column to link somewhere, enter the URL in here",
                    ),
                    array(
                        "type" => "dropdown",
                        "class" => "",
                        "heading" => "Column Link Target",
                        "param_name" => "column_link_target",
                        'save_always' => true,
                        'value' => array(__("Same window", "js_composer") => "_self", __("New window", "js_composer") => "_blank")
                    ),

                    array(
                        "type" => "dropdown",
                        "heading" => __("Box Shadow", "js_composer"),
                        'save_always' => true,
                        "param_name" => "column_shadow",
                        "value" => array(__("None", "js_composer") => "none", __("Small Depth", "js_composer") => "small_depth", __("Medium Depth", "js_composer") => "medium_depth", __("Large Depth", "js_composer") => "large_depth", __("Very Large Depth", "js_composer") => "x_large_depth"),
                        "description" => __("Select your desired column box shadow", "js_composer")
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => __("Border Radius", "js_composer"),
                        'save_always' => true,
                        "param_name" => "column_border_radius",
                        "value" => array(
                            __("0px", "js_composer") => "none",
                            __("3px", "js_composer") => "3px",
                            __("5px", "js_composer") => "5px",
                            __("10px", "js_composer") => "10px",
                            __("15px", "js_composer") => "15px",
                            __("20px", "js_composer") => "20px"),
                        "description" => __("This will round the edges of your column", "js_composer")
                    ),
                    array(
                        "type" => "textfield",
                        "class" => "",
                        "heading" => "Margin Top",
                        "value" => "",
                        "param_name" => "top_margin",
                        "description" => "Don't include \"px\" in your strings . e.g \"40\" - However you can also use a percent value in which case a \"%\" would be needed at the end e.g. \"10%\". Negative Values are also accepted."
                    ),

                    array(
                        "type" => "textfield",
                        "class" => "",
                        "heading" => "Margin Bottom",
                        "value" => "",
                        "param_name" => "bottom_margin",
                        "description" => ""
                    ),

                    array(
                        "type" => "textfield",
                        "class" => "",
                        "heading" => "Extra Class Name",
                        "param_name" => "el_class",
                        "value" => ""
                    ),
                    array(
                        'type' => 'dropdown',
                        'save_always' => true,
                        'heading' => __('Width', 'js_composer'),
                        'param_name' => 'width',
                        'value' => array(
                            __('1 column - 1/12', 'js_composer') => '1/12',
                            __('2 columns - 1/6', 'js_composer') => '1/6',
                            __('3 columns - 1/4', 'js_composer') => '1/4',
                            __('4 columns - 1/3', 'js_composer') => '1/3',
                            __('5 columns - 5/12', 'js_composer') => '5/12',
                            __('6 columns - 1/2', 'js_composer') => '1/2',
                            __('7 columns - 7/12', 'js_composer') => '7/12',
                            __('8 columns - 2/3', 'js_composer') => '2/3',
                            __('9 columns - 3/4', 'js_composer') => '3/4',
                            __('10 columns - 5/6', 'js_composer') => '5/6',
                            __('11 columns - 11/12', 'js_composer') => '11/12',
                            __('12 columns - 1/1', 'js_composer') => '1/1',
                            __('20% - 1/5', 'js_composer') => '1/5',
                            __('40% - 2/5', 'js_composer') => '2/5',
                            __('60% - 3/5', 'js_composer') => '3/5',
                            __('80% - 4/5', 'js_composer') => '4/5'
                        ),
                        'group' => __('Responsive Options', 'js_composer'),
                        'description' => __('Select column width.', 'js_composer'),
                        'std' => '1/1'
                    ),
                    array(
                        'type' => 'column_offset',
                        'heading' => __('Responsiveness', 'js_composer'),
                        'param_name' => 'offset',
                        'group' => __('Responsive Options', 'js_composer'),
                        'description' => __('Adjust column for different screen sizes. Control width, offset and visibility settings.', 'js_composer')
                    ),

                    array(
                        "type" => "dropdown",
                        "class" => "",
                        'group' => __('Responsive Options', 'js_composer'),
                        'save_always' => true,
                        "heading" => "Tablet Column Width Inherits From",
                        "param_name" => "tablet_width_inherit",
                        "value" => array(
                            "Mobile Column Width (Default)" => "default",
                            "Small Desktop Colummn Width" => "small_desktop",
                        ),
                        "description" => "This allows you to determine what your column width will inherit from when viewed on tablets in a portrait orientation."
                    ),

                    array(
                        "type" => "dropdown",
                        "class" => "",
                        'group' => __('Responsive Options', 'js_composer'),
                        'save_always' => true,
                        "heading" => "Tablet Text Alignment",
                        "param_name" => "tablet_text_alignment",
                        "value" => array(
                            "Default" => "default",
                            "Left" => "left",
                            "Center" => "center",
                            "Right" => "right",
                        ),
                        "description" => "Text alignment that will be used on tablet devices"
                    ),

                    array(
                        "type" => "dropdown",
                        "class" => "",
                        'group' => __('Responsive Options', 'js_composer'),
                        'save_always' => true,
                        "heading" => "Smartphone Text Alignment",
                        "param_name" => "phone_text_alignment",
                        "value" => array(
                            "Default" => "default",
                            "Left" => "left",
                            "Center" => "center",
                            "Right" => "right",
                        ),
                        "description" => "Text alignment that will be used on smartphones"
                    ),

                    array(
                        "type" => "dropdown",
                        "class" => "",
                        'save_always' => true,
                        'group' => __('Border Options', 'js_composer'),
                        "heading" => "Border Width",
                        "param_name" => "column_border_width",
                        "value" => array(
                            "0px" => "none",
                            "1px" => "1px",
                            "2px" => "2px",
                            "3px" => "3px",
                            "4px" => "4px",
                            "5px" => "5px",
                            "6px" => "6px",
                            "7px" => "7px",
                            "8px" => "8px"
                        ),
                        "description" => ""
                    ),
                    array(
                        "type" => "colorpicker",
                        "class" => "",
                        "heading" => "Border Color",
                        "param_name" => "column_border_color",
                        'group' => __('Border Options', 'js_composer'),
                        "value" => "",
                        "description" => ""
                    ),
                    array(
                        "type" => "dropdown",
                        "class" => "",
                        'save_always' => true,
                        'group' => __('Border Options', 'js_composer'),
                        "heading" => "Border Style",
                        "param_name" => "column_border_style",
                        "value" => array(
                            "Solid" => "solid",
                            "Dotted" => "dotted",
                            "Dashed" => "dashed",
                            "Double" => "double",
                            "Double Offset" => "double_offset"
                        ),
                        "description" => "",
                        "dependency" => Array('element' => "column_border_radius", 'value' => 'none')
                    ),
                    array(
                        "type" => "checkbox",
                        "class" => "",
                        'group' => __('Border Options', 'js_composer'),
                        "heading" => "Enable Border Animation",
                        "value" => array("Enable Column Animation?" => "true"),
                        "param_name" => "enable_border_animation",
                        "description" => "",
                        "dependency" => Array('element' => "column_border_radius", 'value' => 'none')
                    ),

                    array(
                        "type" => "textfield",
                        "class" => "",
                        "heading" => "Animation Delay",
                        'group' => __('Border Options', 'js_composer'),
                        "param_name" => "border_animation_delay",
                        "admin_label" => false,
                        "description" => __("Enter delay (in milliseconds) if needed e.g. 150. This parameter comes in handy when creating the animate in \"one by one\" effect.", "js_composer"),
                        "dependency" => Array('element' => "enable_border_animation", 'not_empty' => true)
                    )


                ),
                'js_view' => 'VcColumnView'
            ));

        } catch (Exception $e) {
            return false;
        }
    }

}

VcColumn::init();