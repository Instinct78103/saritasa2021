<?php

add_shortcode('sar_carousel', function ($atts, $content = null) {
    $atts = shortcode_atts([
        'autorotate' => false,
        'desktop' => 3,
        'tablet' => 3,
        'mobile' => 1,
        'off_at_screen_resolution' => 0,
        'speed' => 5000,
    ], $atts);

    $uid = uniqid();
    return '<div id="' . $uid . '" class="owl-carousel" data-settings=\'' . json_encode($atts, JSON_NUMERIC_CHECK) . '\'>' . wpb_js_remove_wpautop(do_shortcode($content), true) . '</div>';
});

add_shortcode('sar_carousel_item', function ($atts, $content = null) {
    $atts = shortcode_atts([
        'img' => '',
        'custom_class' => '',
    ], $atts);

    $image = wp_get_attachment_image_url((int)$atts['img'], 'full');
    $output = '<div class="carousel-item ' . $atts['custom_class'] . '">';
    if ($atts['img']) {
        $output .= '<div class="img-block" style="background-image: url(' . $image . ')"></div>';
    }
    $output .= ($content !== '')
        ? '<div class="content">' . wpb_js_remove_wpautop(do_shortcode($content), true) . '</div>'
        : '';
    $output .= '</div>'; // div.carousel-item

    return $output;
});

add_action('vc_before_init', function () {

    $tab_id_1 = time() . '-1-' . rand(0, 100);
    $tab_id_2 = time() . '-2-' . rand(0, 100);
    $tab_id_3 = time() . '-3-' . rand(0, 100);

    vc_map([
        'name' => __('Custom Carousel', 'js_composer'),
        "base" => "sar_carousel",
        "show_settings_on_create" => false,
        "is_container" => true,
        'icon' => 'icon-wpb-testimonial-slider',
        "category" => 'Saritasa',
        'as_parent' => ['only' => 'sar_carousel_item'],
        "content_element" => true,
        'description' => __('Custom Owl Carousel', 'js_composer'),
        'params' => [
            [
                'type' => 'checkbox',
                'heading' => 'Autorotate?',
                'param_name' => 'autorotate',
                'value' => false,
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
                'value' => 0,
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
        ],
        "custom_markup" => '
          <div class="wpb_tabs_holder wpb_holder vc_container_for_children">
          <ul class="tabs_controls">
          </ul>
          {{content}}
          </div>',
        'default_content' => '
	  [sar_carousel_item title="' . __('Item', 'js_composer') . '" id="' . $tab_id_1 . '"] Your Content. [/sar_carousel_item]
	  [sar_carousel_item title="' . __('Item', 'js_composer') . '" id="' . $tab_id_2 . '"] Your Content. [/sar_carousel_item]
	  [sar_carousel_item title="' . __('Item', 'js_composer') . '" id="' . $tab_id_3 . '"] Your Content. [/sar_carousel_item]
	  ',
        "js_view" => 'VcTabsView',
    ]);

    vc_map([
        "name" => "Carousel Item",
        "base" => "sar_carousel_item",
        "allowed_container_element" => 'vc_row',
        "is_container" => true,
        "content_element" => false,
        "params" => [
            [
                'type' => 'attach_image',
                'heading' => 'Add Image',
                'param_name' => 'img',
                'admin_label' => true,
            ],
            [
                'type' => 'textarea_html',
                'holder' => 'div',
                'param_name' => 'content',
                'value' => '',
            ],
            [
                "type" => "tab_id",
                "heading" => __("Item ID", "js_composer"),
                "param_name" => "id",
            ],
            [
                "type" => "textfield",
                "heading" => __("Classes", "js_composer"),
                "save_always" => true,
                "param_name" => "custom_class",
                "value" => "",
                "description" => __("Type custom classes", "js_composer"),
            ],
        ],
        'js_view' => 'VcTabView',
    ]);
});

if (class_exists('WPBakeryShortCode_Tabbed_Section')) {
    class WPBakeryShortCode_Sar_Carousel extends WPBakeryShortCode_Tabbed_Section
    {
    }
}

if (class_exists('WPBakeryShortCode')) {
    class WPBakeryShortCode_Sar_Carousel_Item extends WPBakeryShortCode
    {
        public function customAdminBlockParams()
        {
            return ' id="tab-' . $this->atts['id'] . '"';
        }
    }
}