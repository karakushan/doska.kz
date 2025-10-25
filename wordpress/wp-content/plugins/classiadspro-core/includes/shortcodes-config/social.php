<?php

vc_map(array(
    "name" => esc_html__("Social Networks", "pacz"),
    "base" => "pacz_social_networks",
    'icon' => 'icon-pacz-social-networks vc_pacz_element-icon',
    'description' => esc_html__( 'Adds social network icons.', 'pacz' ),
    "category" => esc_html__('Social', 'pacz'),
    "params" => array(
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Style", "pacz"),
            "param_name" => "style",
            "value" => array(
                "Square" => "square",
                "Circle" => "circle",
                "Simple" => "simple"
            )
        ),
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Skin", "pacz"),
            "param_name" => "skin",
            "value" => array(
                "Dark" => "dark",
                "Light" => "light",
                "Custom" => "custom",
            )
        ),


        array(
            "type" => "colorpicker",
            "heading" => esc_html__("Border Color", "pacz"),
            "param_name" => "border_color",
            "value" => "#ccc",
            "description" => esc_html__("(default: #ccc). Doesn't work with Simple Style", "pacz"),
            "dependency" => array(
                'element' => "skin",
                'value' => array(
                    'custom'
                )
            )
        ),
        array(
            "type" => "colorpicker",
            "heading" => esc_html__("Background Color", "pacz"),
            "param_name" => "bg_color",
            "value" => "",
            "description" => esc_html__("(default: transparent). Doesn't work with Simple Style", "pacz"),
            "dependency" => array(
                'element' => "skin",
                'value' => array(
                    'custom'
                )
            )
        ),
        array(
            "type" => "colorpicker",
            "heading" => esc_html__("Background Hover Color", "pacz"),
            "param_name" => "bg_hover_color",
            "value" => "#232323",
            "description" => esc_html__("(default: #232323). Doesn't work with Simple Style", "pacz"),
            "dependency" => array(
                'element' => "skin",
                'value' => array(
                    'custom'
                )
            )
        ),
        array(
            "type" => "colorpicker",
            "heading" => esc_html__("Icons Color", "pacz"),
            "param_name" => "icon_color",
            "value" => "#ccc",
            "description" => esc_html__("(default: #ccc)", "pacz"),
            "dependency" => array(
                'element' => "skin",
                'value' => array(
                    'custom'
                )
            )
        ),
        array(
            "type" => "colorpicker",
            "heading" => esc_html__("Icons Hover Color", "pacz"),
            "param_name" => "icon_hover_color",
            "value" => "#eee",
            "description" => esc_html__("(default: #eee)", "pacz"),
            "dependency" => array(
                'element' => "skin",
                'value' => array(
                    'custom'
                )
            )
        ),


        array(
            "type" => "range",
            "heading" => esc_html__("Margin", "pacz"),
            "param_name" => "margin",
            "value" => "4",
            "min" => "0",
            "max" => "50",
            "step" => "1",
            "unit" => 'px',
            "description" => esc_html__("How much distance between icons? this margin will be applied to all directions.", "pacz")
        ),
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Icons Align", "pacz"),
            "param_name" => "align",
            "width" => 150,
            "value" => array(
                esc_html__('Left', "pacz") => "left",
                esc_html__('Right', "pacz") => "right",
                esc_html__('Center', "pacz") => "center"
            ),
            "description" => esc_html__("", "pacz")
        ),

        array(
            "type" => "textfield",
            "heading" => esc_html__("Facebook URL", "pacz"),
            "param_name" => "facebook",
            "value" => "",
            "description" => esc_html__("Fill this textbox with the full URL of your corresponding social netowork. include http:// if left blank this social network icon wont be shown.", "pacz")
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Twitter URL", "pacz"),
            "param_name" => "twitter",
            "value" => "",
            "description" => esc_html__("Fill this textbox with the full URL of your corresponding social netowork. include http:// if left blank this social network icon wont be shown.", "pacz")
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("RSS URL", "pacz"),
            "param_name" => "rss",
            "value" => "",
            "description" => esc_html__("Fill this textbox with the full URL of your corresponding social netowork. include http:// if left blank this social network icon wont be shown.", "pacz")
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Instagram URL", "pacz"),
            "param_name" => "instagram",
            "value" => "",
            "description" => esc_html__("Fill this textbox with the full URL of your corresponding social netowork. include http:// if left blank this social network icon wont be shown.", "pacz")
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Dribbble URL", "pacz"),
            "param_name" => "dribbble",
            "value" => "",
            "description" => esc_html__("Fill this textbox with the full URL of your corresponding social netowork. include http:// if left blank this social network icon wont be shown.", "pacz")
        ),
         array(
            "type" => "textfield",
            "heading" => esc_html__("Vimeo URL", "pacz"),
            "param_name" => "vimeo",
            "value" => "",
            "description" => esc_html__("Fill this textbox with the full URL of your corresponding social netowork. include http:// if left blank this social network icon wont be shown.", "pacz")
        ),
         array(
            "type" => "textfield",
            "heading" => esc_html__("Spotify URL", "pacz"),
            "param_name" => "spotify",
            "value" => "",
            "description" => esc_html__("Fill this textbox with the full URL of your corresponding social netowork. include http:// if left blank this social network icon wont be shown.", "pacz")
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Pinterest URL", "pacz"),
            "param_name" => "pinterest",
            "value" => "",
            "description" => esc_html__("Fill this textbox with the full URL of your corresponding social netowork. include http:// if left blank this social network icon wont be shown.", "pacz")
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Google Plus URL", "pacz"),
            "param_name" => "google_plus",
            "value" => "",
            "description" => esc_html__("Fill this textbox with the full URL of your corresponding social netowork. include http:// if left blank this social network icon wont be shown.", "pacz")
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Linkedin URL", "pacz"),
            "param_name" => "linkedin",
            "value" => "",
            "description" => esc_html__("Fill this textbox with the full URL of your corresponding social netowork. include http:// if left blank this social network icon wont be shown.", "pacz")
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Youtube URL", "pacz"),
            "param_name" => "youtube",
            "value" => "",
            "description" => esc_html__("Fill this textbox with the full URL of your corresponding social netowork. include http:// if left blank this social network icon wont be shown.", "pacz")
        ),

        array(
            "type" => "textfield",
            "heading" => esc_html__("Tumblr URL", "pacz"),
            "param_name" => "tumblr",
            "value" => "",
            "description" => esc_html__("Fill this textbox with the full URL of your corresponding social netowork. include http:// if left blank this social network icon wont be shown.", "pacz")
        ),



        array(
            "type" => "textfield",
            "heading" => esc_html__("Behance URL", "pacz"),
            "param_name" => "behance",
            "value" => "",
            "description" => esc_html__("Fill this textbox with the full URL of your corresponding social netowork. include http:// if left blank this social network icon wont be shown.", "pacz")
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("WhatsApp URL", "pacz"),
            "param_name" => "whatsapp",
            "value" => "",
            "description" => esc_html__("Fill this textbox with the full URL of your corresponding social netowork. include http:// if left blank this social network icon wont be shown.", "pacz")
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("qzone URL", "pacz"),
            "param_name" => "qzone",
            "value" => "",
            "description" => esc_html__("Fill this textbox with the full URL of your corresponding social netowork. include http:// if left blank this social network icon wont be shown.", "pacz")
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("vk.com URL", "pacz"),
            "param_name" => "vkcom",
            "value" => "",
            "description" => esc_html__("Fill this textbox with the full URL of your corresponding social netowork. include http:// if left blank this social network icon wont be shown.", "pacz")
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("IMDb URL", "pacz"),
            "param_name" => "imdb",
            "value" => "",
            "description" => esc_html__("Fill this textbox with the full URL of your corresponding social netowork. include http:// if left blank this social network icon wont be shown.", "pacz")
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Renren URL", "pacz"),
            "param_name" => "renren",
            "value" => "",
            "description" => esc_html__("Fill this textbox with the full URL of your corresponding social netowork. include http:// if left blank this social network icon wont be shown.", "pacz")
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Wechat URL", "pacz"),
            "param_name" => "wechat",
            "value" => "",
            "description" => esc_html__("Fill this textbox with the full URL of your corresponding social netowork. include http:// if left blank this social network icon wont be shown.", "pacz")
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Weibo URL", "pacz"),
            "param_name" => "weibo",
            "value" => "",
            "description" => esc_html__("Fill this textbox with the full URL of your corresponding social netowork. include http:// if left blank this social network icon wont be shown.", "pacz")
        ),
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Viewport Animation", "pacz"),
            "param_name" => "animation",
            "value" => $css_animations,
            "description" => esc_html__("Viewport animation will be triggered when this element is being viewed when you scroll page down. you only need to choose the animation style from this option. please note that this only works in moderns. We have disabled this feature in touch devices to increase browsing speed.", "pacz")
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Extra class name", "pacz"),
            "param_name" => "el_class",
            "value" => "",
            "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in Custom CSS Shortcode or Masterkey Custom CSS option.", "pacz")
        )

    )
));
vc_map(array(
    "name" => esc_html__("Video player", "pacz"),
    "base" => "vc_video",
    'icon' => 'icon-pacz-video-player vc_pacz_element-icon',
    'description' => esc_html__( 'Youtube, Vimeo,..', 'pacz' ),
    "category" => esc_html__('Social', 'pacz'),
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => esc_html__("Widget Title", "pacz"),
            "param_name" => "title",
            "value" => "",
            "description" => esc_html__("", "pacz")
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Video link", "pacz"),
            "param_name" => "link",
            "value" => "",
			 "description" => esc_html__(" Link to the video. More about supported formats at", "pacz"). __(' <a href="http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F" target="_blank">WordPress codex page</a>', "pacz"),
           
        ),
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Viewport Animation", "pacz"),
            "param_name" => "animation",
            "value" => $css_animations,
            "description" => esc_html__("Viewport animation will be triggered when this element is being viewed when you scroll page down. you only need to choose the animation style from this option. please note that this only works in moderns. We have disabled this feature in touch devices to increase browsing speed.", "pacz")
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Extra class name", "pacz"),
            "param_name" => "el_class",
            "value" => "",
            "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "pacz")
        )
    )
));
vc_map(array(
    "base" => "pacz_contact_info",
    "name" => esc_html__("Contact Info", "pacz"),
    'icon' => 'icon-pacz-contact-info vc_pacz_element-icon',
    "category" => esc_html__('Social', 'pacz'),
    'description' => esc_html__( 'Adds Contact info details.', 'pacz' ),
    "params" => array(

        array(
            "type" => "dropdown",
            "heading" => esc_html__("Skin", "pacz"),
            "param_name" => "skin",
            "value" => array(
                esc_html__("Dark", "pacz") => "dark",
                esc_html__("Light", "pacz") => "light",
                esc_html__("Custom", "pacz") => "custom"
            ),
            "description" => esc_html__("Choose your contact form style", "pacz")
        ),
        array(
             "type" => "colorpicker",
             "heading" => esc_html__("Text & Icon Color", "pacz"),
             "param_name" => "text_icon_color",
             "value" => "",
             "description" => esc_html__("", "pacz"),
             "dependency" => array(
                  'element' => "skin",
                  'value' => array(
                       'custom'
                  )
             )
        ),
        array(
             "type" => "colorpicker",
             "heading" => esc_html__("Border Color", "pacz"),
             "param_name" => "border_color",
             "value" => "",
             "description" => esc_html__("", "pacz"),
             "dependency" => array(
                  'element' => "skin",
                  'value' => array(
                       'custom'
                  )
             )
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Name", "pacz"),
            "param_name" => "name",
            "value" => ""
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Cellphone", "pacz"),
            "param_name" => "cellphone",
            "value" => ""
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Phone", "pacz"),
            "param_name" => "phone",
            "value" => ""
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Address", "pacz"),
            "param_name" => "address",
            "value" => ""
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Website", "pacz"),
            "param_name" => "website",
            "value" => ""
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Email", "pacz"),
            "param_name" => "email",
            "value" => ""
        ),
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Viewport Animation", "pacz"),
            "param_name" => "animation",
            "value" => $css_animations,
            "description" => esc_html__("Viewport animation will be triggered when this element is being viewed when you scroll page down. you only need to choose the animation style from this option. please note that this only works in moderns. We have disabled this feature in touch devices to increase browsing speed.", "pacz")
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Extra class name", "pacz"),
            "param_name" => "el_class",
            "value" => "",
            "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "pacz")
        )
    )
));
