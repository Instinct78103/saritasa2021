<?php

if (class_exists('WPBakeryShortCode')) {
    class WPBakeryShortCode_Saritasa_Video_Lightbox extends WPBakeryShortCode
    {
    }
}

function saritasa_video_lightbox_data()
{
    return [
        "name" => __("Saritasa Video Lightbox", "js_composer"),
        "base" => "saritasa_video_lightbox",
        "icon" => sar_image_url('logo-small.png'),
        "category" => __('Saritasa', 'js_composer'),
        "description" => __('Add a video lightbox link', 'js_composer'),
        "params" => [
            [
                "type" => "dropdown",
                "heading" => __("Link Style", "js_composer"),
                "param_name" => "link_style",
                "value" => [
                    "Play Button" => "play_button",
                    "Play Button With text" => "play_button_with_text",
                    "Play Button With Preview Image" => "play_button_2",
                ],
                'save_always' => true,
                "admin_label" => true,
                "description" => __("Please select your link style", "js_composer"),
            ],
            [
                "type" => "textfield",
                "heading" => __("Video URL", "js_composer"),
                "param_name" => "video_url",
                "admin_label" => false,
                "description" => __("The URL to your video on Youtube or Vimeo e.g. <br/> https://vimeo.com/118023315 <br/> https://www.youtube.com/watch?v=6oTurM7gESE",
                    "js_composer"),
            ],
            [
                "type" => "textfield",
                "heading" => __("Title", "js_composer"),
                "param_name" => "video_button_title",
                "admin_label" => false,
                "description" => __("Button title",
                    "js_composer"),
            ],
            [
                "type" => "textfield",
                "heading" => __("Subtitle", "js_composer"),
                "param_name" => "video_button_subtitle",
                "admin_label" => false,
                "description" => __("Button subtitle",
                    "js_composer"),
            ],
            [
                "type" => "dropdown",
                "dependency" => ['element' => "link_style", 'value' => "play_button_2"],
                "heading" => __("Hover Effect", "js_composer"),
                'save_always' => true,
                "param_name" => "hover_effect",
                "value" => [
                    __("Zoom BG Image", "js_composer") => "defaut",
                    __("Zoom Button", "js_composer") => "zoom_button",
                ],
                "description" => __("Select your desired hover effect", "js_composer"),
            ],
            [
                "type" => "dropdown",
                "dependency" => ['element' => "link_style", 'value' => "play_button_2"],
                "heading" => __("Box Shadow", "js_composer"),
                'save_always' => true,
                "param_name" => "box_shadow",
                "value" => [
                    __("None", "js_composer") => "none",
                    __("Small Depth", "js_composer") => "small_depth",
                    __("Medium Depth", "js_composer") => "medium_depth",
                    __("Large Depth", "js_composer") => "large_depth",
                    __("Very Large Depth", "js_composer") => "x_large_depth",
                ],
                "description" => __("Select your desired image box shadow", "js_composer"),
            ],
            [
                "type" => "dropdown",
                "heading" => __("Border Radius", "js_composer"),
                'save_always' => true,
                "dependency" => ['element' => "link_style", 'value' => "play_button_2"],
                "param_name" => "border_radius",
                "value" => [
                    __("0px", "js_composer") => "none",
                    __("3px", "js_composer") => "3px",
                    __("5px", "js_composer") => "5px",
                    __("10px", "js_composer") => "10px",
                    __("15px", "js_composer") => "15px",
                    __("20px", "js_composer") => "20px",
                ],
            ],
            [
                "type" => "dropdown",
                "heading" => __("Play Button Size", "js_composer"),
                'save_always' => true,
                "dependency" => ['element' => "link_style", 'value' => "play_button_2"],
                "param_name" => "play_button_size",
                "value" => [
                    __("Default", "js_composer") => "default",
                    __("Larger", "js_composer") => "larger",
                ],
            ],
            [
                "type" => "textfield",
                "heading" => __("Link Text", "js_composer"),
                "param_name" => "link_text",
                "admin_label" => false,
                "dependency" => [
                    'element' => "link_style",
                    'value' => ["nectar-button", "play_button_with_text"],
                ],
                "description" => __("The text that will be displayed for your link", "js_composer"),
            ],
            [
                "type" => "dropdown",
                "class" => "",
                'save_always' => true,
                "heading" => "Text Font Style",
                "dependency" => ['element' => "link_style", 'value' => ["play_button_with_text"]],
                "description" => __("Choose what element your link text will inherit styling from", "js_composer"),
                "param_name" => "font_style",
                "value" => [
                    "Paragraph" => "p",
                    "H6" => "h6",
                    "H5" => "h5",
                    "H4" => "h4",
                    "H3" => "h3",
                    "H2" => "h2",
                    "H1" => "h1",
                ],
            ],
        ],
        "html_template" => SAR_TEMPLATES . '/elements/video_lightbox.php',
    ];
}

if (function_exists('vc_lean_map')) {
    add_action('vc_before_init', function () {
        vc_lean_map('saritasa_video_lightbox', 'saritasa_video_lightbox_data');
    });
}