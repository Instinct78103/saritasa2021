<?php

if (class_exists('WPBakeryShortCode_Tabbed_Section')) {
    class WPBakeryShortCode_Testimonial_Slider extends WPBakeryShortCode_Tabbed_Section
    {
    }
}

add_shortcode('testimonial_slider', function ($atts, $content = null) {
    $atts = shortcode_atts([
        'autorotate' => false,
        'desktop' => 3,
        'tablet' => 3,
        'mobile' => 1,
        'off_at_screen_resolution' => '',
        'speed' => 5000,
        'stage_padding' => 0,
    ], $atts);

    return '
        <div class="col span_12 testimonial_slider" >
            <div class="slides owl-carousel" data-settings=\'' . json_encode($atts, JSON_NUMERIC_CHECK) . '\'>' . wpb_js_remove_wpautop(do_shortcode($content), true) . '</div>
        </div>';
});

add_shortcode('testimonial', function ($atts) {

    $atts = shortcode_atts([
        "name" => '',
        "subtitle" => '',
        "quote" => '',
        'image' => '',
        'star_rating' => 'none',
    ], $atts);

    $has_bg = null;
    $bg_markup = null;

    if (!empty($image)) {
        $image_src = wp_get_attachment_image_src($image, 'medium');
        $image = $image_src[0];
    }

    $rating_markup = null;

    if ($atts['star_rating'] != 'none') {
        $rating_markup = '
            <div class="star-rating-wrap">
                <span class="star-rating">
                    <span style="width: ' . $atts['star_rating'] . '" class="filled"></span>
                </span>
            </div>';
    }

    return '
        <blockquote>' . '
            <p class="person">' . $atts['name'] . '</p>
            <p class="subtitle">' . $atts['subtitle'] . '</p>
            <p class="quote">&#8221;' . preg_replace('~\.*$~', '', $atts['quote']) . '&#8221;.</p>' .
        $rating_markup . '
        </blockquote>';
});

function map_testimonial_slider()
{
    $tab_id_1 = time() . '-1-' . rand(0, 100);
    $tab_id_2 = time() . '-2-' . rand(0, 100);

    return [
        "name" => __("Testimonial Slider", "js_composer"),
        "base" => "testimonial_slider",
        "show_settings_on_create" => false,
        "is_container" => true,
        "icon" => "icon-wpb-testimonial-slider",
        "category" => __('Saritasa', 'js_composer'),
        "description" => __('An appealing testmonial slider.', 'js_composer'),
        "params" => [
            [
                'type' => 'checkbox',
                'heading' => 'Autorotate?',
                'param_name' => 'autorotate',
                'value' => false,
                'always_save' => true,
            ],
            [
                'type' => 'textfield',
                'heading' => 'Columns',
                'description' => 'Desktop',
                'param_name' => 'desktop',
                'value' => 3,
                'std' => 3,
                'always_save' => true,
            ],
            [
                'type' => 'textfield',
                'description' => 'Small Screen',
                'param_name' => 'tablet',
                'value' => 3,
                'std' => 3,
                'always_save' => true,
            ],
            [
                'type' => 'textfield',
                'description' => 'Mobile',
                'param_name' => 'mobile',
                'value' => 1,
                'std' => 1,
                'always_save' => true,
            ],
            [
                'type' => 'textfield',
                'heading' => 'The carousel will be disabled at a certain resolution',
                'description' => 'Set the min resolution at which the carousel should turn off (without `px`)',
                'param_name' => 'off_at_screen_resolution',
                'value' => '',
                'always_save' => true,
            ],
            [
                'type' => 'textfield',
                'heading' => 'Autoplay Speed',
                'description' => 'Speed',
                'param_name' => 'speed',
                'std' => 5000,
                'always_save' => true,
            ],
            [
                'type' => 'textfield',
                'heading' => 'Show parts of next and previous items (without `px`)',
                'description' => 'Set the size of the edge of adjacent elements. Be careful, this param can make your slider ugly or larger then expected',
                'param_name' => 'stage_padding',
                'value' => 0,
                'always_save' => true,
            ],
        ],
        "custom_markup" => '
          <div class="wpb_tabs_holder wpb_holder vc_container_for_children">
          <ul class="tabs_controls">
          </ul>
          {{content}}
          </div>',
        'default_content' => '
          [testimonial title="' . __('Testimonial', 'js_composer') . '" id="' . $tab_id_1 . '"] Click the edit button to add your testimonial. [/testimonial]
          [testimonial title="' . __('Testimonial', 'js_composer') . '" id="' . $tab_id_2 . '"] Click the edit button to add your testimonial. [/testimonial]',
        "js_view" => 'VcTabsView',
    ];
}

function map_testimonial()
{
    return [
        "name" => __("Testimonial", "js_composer"),
        "base" => "testimonial",
        "allowed_container_element" => 'vc_row',
        "is_container" => true,
        "content_element" => false,
        "params" => [
            [
                "type" => "attach_image",
                "class" => "",
                "heading" => "Image",
                "value" => "",
                "param_name" => "image",
                "description" => "Add an optional image for the person/company who supplied the testimonial",
            ],
            [
                "type" => "textfield",
                "heading" => __("Name", "js_composer"),
                "param_name" => "name",
                "admin_label" => true,
                "description" => __("Name or source of the testimonial", "js_composer"),
            ],
            [
                "type" => "textfield",
                "heading" => __("Subtitle", "js_composer"),
                "param_name" => "subtitle",
                "admin_label" => false,
                "description" => __("The optional subtitle that will follow the testimonial name", "js_composer"),
            ],
            [
                "type" => "textarea",
                "heading" => __("Quote", "js_composer"),
                "param_name" => "quote",
                "description" => __("The testimonial quote", "js_composer"),
            ],
            [
                "type" => "dropdown",
                "heading" => __("Star Rating", "js_composer"),
                "param_name" => "star_rating",
                "admin_label" => false,
                "value" => [
                    "Hidden" => "none",
                    "5 Stars" => "100%",
                    "4.5 Stars" => "91%",
                    "4 Stars" => "80%",
                    "3.5 Stars" => "701%",
                    "3 Stars" => "60%",
                    "2.5 Stars" => "51%",
                    "2 Stars" => "40%",
                    "1.5 Stars" => "31%",
                    "1 Stars" => "20%",
                ],
                'save_always' => true,
                "description" => __("Please select the star raing you would like to show for your testimonial", "js_composer"),
            ],
            [
                "type" => "tab_id",
                "heading" => __("Testimonial ID", "js_composer"),
                "param_name" => "id",
            ],
        ],
        'js_view' => 'VcTabView',
    ];
}

if (class_exists('WPBakeryShortCode')) {
    class WPBakeryShortCode_Testimonial extends WPBakeryShortCode
    {
        public function customAdminBlockParams()
        {
            return ' id="tab-' . $this->atts['id'] . '"';
        }
    }
}

if (function_exists('vc_lean_map')) {
    add_action('vc_before_init', function () {
        vc_remove_element("vc_tabs");
        vc_remove_element("vc_tab");
        vc_lean_map('testimonial_slider', 'map_testimonial_slider');
        vc_lean_map('testimonial', 'map_testimonial');
    });
}