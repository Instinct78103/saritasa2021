<?php

if (class_exists('WPBakeryShortCode')) {
    class WPBakeryShortCode_Saritasa_Card extends WPBakeryShortCode
    {
    }
}

function saritasa_card_data()
{
    return [
        "name" => __("Saritasa Card", "js_composer"),
        "base" => "saritasa_cards",
        "icon" => sar_image_url('logo-small.png'),
        "category" => __('Saritasa', 'js_composer'),
        "description" => __('Add a card with description', 'js_composer'),
        "params" => [
            [
                "type" => "dropdown",
                "heading" => __("Type", "js_composer"),
                'save_always' => true,
                "param_name" => "type",
                "value" => [
                    __("Big (with description)", "js_composer") => 'big',
                    __("Mini (only title)", "js_composer") => 'mini',
                ],
            ],
            [
                "type" => "fws_image",
                "heading" => __("Title background icon", "js_composer"),
                "param_name" => "image_url",
                "value" => "",
                "description" => __("\Select image from media library.", "js_composer")
            ],
            [
                "type" => "textfield",
                "heading" => __("Title", "js_composer"),
                "save_always" => true,
                "param_name" => "title",
                "value" => "",
                "description" => __("Type card title", "js_composer")
            ],
            [
                "type" => "colorpicker",
                "class" => "",
                "heading" => __("Title color", "js_composer"),
                "save_always" => true,
                "param_name" => "title_color",
                "value" => "#F00000",
                "description" => __("Choose title color", "js_composer")
            ],
            [
                "type" => "textarea_html",
                "holder" => "div",
                "heading" => __("Description", "js_composer"),
                "param_name" => "content",
                "value" => "",
                "description" => __("Type card description", "js_composer")
            ],
            [
                "type" => "colorpicker",
                "class" => "",
                "dependency" => ['element' => "type", 'value' => "big"],
                "heading" => __("Description color", "js_composer"),
                "save_always" => true,
                "param_name" => "desc_color",
                "value" => "#F00000",
                "description" => __("Choose description color", "js_composer")
            ],
            [
                "type" => "colorpicker",
                "class" => "",
                "heading" => __("Background color", "js_composer"),
                "save_always" => true,
                "param_name" => "bg_color",
                "value" => "#F00000",
                "description" => __("Choose background color", "js_composer")
            ],
            [
                "type" => "textfield",
                "heading" => __("Classes", "js_composer"),
                "save_always" => true,
                "param_name" => "custom_class",
                "value" => "",
                "description" => __("Type custom classes", "js_composer")
            ],
        ],
        "html_template" => SAR_TEMPLATES . '/elements/card.php'
    ];
}

if (function_exists('vc_lean_map')) {
    vc_lean_map('saritasa_card', 'saritasa_card_data');
}