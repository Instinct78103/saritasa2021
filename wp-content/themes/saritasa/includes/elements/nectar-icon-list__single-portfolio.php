<?php

add_shortcode('nectar_icon_list', function ($atts, $content = '') {
    extract(shortcode_atts(
        [
            "columns" => "",
            "direction" => 'vertical',
            "animate" => "",
            "color" => 'default',
            'icon_size' => '',
            'icon_style' => 'border',
        ], $atts));

    return '<div class="sar-icon-list">' . wpb_js_remove_wpautop(do_shortcode($content), true) . '</div>';

});

add_shortcode('nectar_icon_list_item', function ($atts, $content = '') {
    extract(shortcode_atts(
        [
            "icon_type" => '',
            'icon_family' => '',
            'icon_fontawesome' => '',
            "header" => "",
            "text" => "",
        ], $atts));

    $icon_output = '<i class="' . $icon_fontawesome . '"></i>';

    return '
        <div class="sar-icon-list-item">
            <div class="list-icon-holder">' . $icon_output . '</div>
            <div class="content">
                <h4>' . $header . '</h4>
                <p>' . $text . '</p>
            </div>
        </div>
    ';
});

add_action('vc_before_init', function () {
    $tab_id_1 = time() . '-1-' . rand(0, 100);
    $tab_id_2 = time() . '-2-' . rand(0, 100);

    vc_map([
        "name" => __("Icon List", "js_composer"),
        "base" => "nectar_icon_list",
        "show_settings_on_create" => false,
        "is_container" => true,
        "icon" => "icon-wpb-fancy-ul",
        "category" => __('Saritasa', 'js_composer'),
        "description" => __('Create an icon list', 'js_composer'),
        "params" => [

            [
                "type" => "checkbox",
                "class" => "",
                "heading" => "Animate Element?",
                "value" => ["Yes, please" => "true"],
                "param_name" => "animate",
                "description" => "",
            ],
            [
                "type" => "dropdown",
                "class" => "",
                "heading" => "Icon Color",
                "param_name" => "color",
                "value" => [
                    "Default (inherit from row Text Color)" => "default",
                    "Accent Color" => "Accent-Color",
                    "Extra Color 1" => "Extra-Color-1",
                    "Extra Color 2" => "Extra-Color-2",
                    "Extra Color 3" => "Extra-Color-3",
                    "Color Gradient 1" => "extra-color-gradient-1",
                    "Color Gradient 2" => "extra-color-gradient-2",
                ],
                'save_always' => true,
                'description' => __('Choose a color from your', 'salient') . ' <a target="_blank" href="' . admin_url() . '?page=Salient&tab=6"> ' . __('globally defined color scheme', 'salient') . '</a>',
            ],
            [
                "type" => "dropdown",
                "heading" => __("Direction", "js_composer"),
                "param_name" => "direction",
                "value" => [
                    "Vertical" => "vertical",
                    "Horizontal" => "horizontal",
                ],
                'save_always' => true,
                "description" => __("Please select the direction you would like your list items to display in", "js_composer"),
            ],
            [
                "type" => "dropdown",
                "heading" => __("Columns", "js_composer"),
                "param_name" => "columns",
                "value" => [
                    "Default (3)" => "default",
                    "1" => "1",
                    "2" => "2",
                    "3" => "3",
                    "4" => "4",
                    "5" => "5",
                ],
                "dependency" => ['element' => "direction", 'value' => 'horizontal'],
                'save_always' => true,
                "description" => __("Please select the column number you desire for your icon list items", "js_composer"),
            ],
            [
                "type" => "dropdown",
                "heading" => __("Icon Size", "js_composer"),
                "param_name" => "icon_size",
                "value" => [
                    "Small" => "small",
                    "Medium" => "medium",
                    "Large" => "large",
                ],
                'save_always' => true,
                "description" => __("Please select the size you would like your list item icons to display in", "js_composer"),
            ],
            [
                "type" => "dropdown",
                "heading" => __("Icon Style", "js_composer"),
                "param_name" => "icon_style",
                "value" => [
                    "Icon Colored W/ BG" => "border",
                    "Icon Colored No BG" => "no-border",
                ],
                'save_always' => true,
                "description" => __("Please select the style you would like your list item icons to display in", "js_composer"),
            ],

        ],
        "custom_markup" => '
	  <div class="wpb_tabs_holder wpb_holder vc_container_for_children">
	  <ul class="tabs_controls">
	  </ul>
	  {{content}}
	  </div>'
        ,
        'default_content' => '
	  [nectar_icon_list_item title="List Item" id="' . $tab_id_1 . '"]  [/nectar_icon_list_item]
	  [nectar_icon_list_item title="List Item" id="' . $tab_id_2 . '"] [/nectar_icon_list_item]
	  ',
        "js_view" => 'VcTabsView',
    ]);

    vc_map([
        "name" => __("List Item", "js_composer"),
        "base" => "nectar_icon_list_item",
        "allowed_container_element" => 'vc_row',
        "is_container" => true,
        "content_element" => false,
        "params" => [
            [
                "type" => "dropdown",
                "heading" => __("List Icon Type", "js_composer"),
                "param_name" => "icon_type",
                "value" => [
                    "Number" => "numerical",
                    "Icon" => "icon",
                ],
                'save_always' => true,
                "admin_label" => true,
                "description" => __("Please select how many columns you would like..", "js_composer"),
            ],

            [
                'type' => 'dropdown',
                'heading' => __('Icon library', 'js_composer'),
                'value' => [
                    __('Font Awesome', 'js_composer') => 'fontawesome',
                    __('Iconsmind', 'js_composer') => 'iconsmind',
                    __('Linea', 'js_composer') => 'linea',
                    __('Steadysets', 'js_composer') => 'steadysets',
                ],
                "dependency" => ['element' => "icon_type", 'value' => 'icon'],
                'param_name' => 'icon_family',
                'description' => __('Select icon library.', 'js_composer'),
            ],
            [
                "type" => "iconpicker",
                "heading" => __("Icon", "js_composer"),
                "param_name" => "icon_fontawesome",
                "settings" => ["emptyIcon" => true, "iconsPerPage" => 4000],
                "dependency" => ['element' => "icon_family", 'value' => 'fontawesome'],
                "description" => __("Select icon from library.", "js_composer"),
            ],
            [
                "type" => "iconpicker",
                "heading" => __("Icon", "js_composer"),
                "param_name" => "icon_iconsmind",
                "settings" => ['type' => 'iconsmind', 'emptyIcon' => false, "iconsPerPage" => 4000],
                "dependency" => ['element' => "icon_family", 'value' => 'iconsmind'],
                "description" => __("Select icon from library.", "js_composer"),
            ],
            [
                "type" => "iconpicker",
                "heading" => __("Icon", "js_composer"),
                "param_name" => "icon_linea",
                "settings" => ['type' => 'linea', "emptyIcon" => true, "iconsPerPage" => 4000],
                "dependency" => ['element' => "icon_family", 'value' => 'linea'],
                "description" => __("Select icon from library.", "js_composer"),
            ],
            [
                "type" => "iconpicker",
                "heading" => __("Icon", "js_composer"),
                "param_name" => "icon_steadysets",
                "settings" => ['type' => 'steadysets', 'emptyIcon' => false, "iconsPerPage" => 4000],
                "dependency" => ['element' => "icon_family", 'value' => 'steadysets'],
                "description" => __("Select icon from library.", "js_composer"),
            ],
            [
                "admin_label" => true,
                "type" => "textfield",
                "heading" => __("Header", "js_composer"),
                "param_name" => "header",
                "description" => __("Enter the header desired for your icon list element", "js_composer"),
            ],
            [
                "admin_label" => true,
                "type" => "textarea",
                "heading" => __("Text Content", "js_composer"),
                "param_name" => "text",
                "description" => __("Enter the text content desired for your icon list element", "js_composer"),
            ],
            [
                "type" => "tab_id",
                "heading" => __("Item ID", "js_composer"),
                "param_name" => "id",
            ],

        ],
        'js_view' => 'VcTabView',
    ]);
});


if (class_exists('WPBakeryShortCode_Tabbed_Section')) {
    class WPBakeryShortCode_Nectar_Icon_List extends WPBakeryShortCode_Tabbed_Section
    {
    }
}

if (class_exists('WPBakeryShortCode')) {
    class WPBakeryShortCode_Nectar_Icon_List_Item extends WPBakeryShortCode
    {
        public function customAdminBlockParams()
        {
            return ' id="tab-' . $this->atts['id'] . '"';
        }

    }
}

