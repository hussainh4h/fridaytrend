<?php

/**
 * ClipMyDeals Theme Customizer
 *
 * @package ClipMyDeals
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */

function themeslug_sanitize_checkbox($checked)
{
    // Boolean check.
    return ((isset($checked) && true == $checked) ? true : false);
}

function clipmydeals_customize_register($wp_customize)
{

    //Theme Option
    $wp_customize->get_section('title_tagline')->priority = 1;

    $wp_customize->get_control('custom_logo')->priority = 1;
    $wp_customize->get_control('blogname')->priority = 2;
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_control('blogdescription')->priority = 3;
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
    $wp_customize->get_control('site_icon')->priority = 4;
    // $wp_customize->get_control('display_header_text')->priority = 5;

    $wp_customize->add_setting('theme_option_setting', array(
        'default' => 'default',
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'theme_option_setting', array(
        'label' => __('Preset Styles', 'clipmydeals'),
        'description' => __('Theme Option', 'clipmydeals'),
        'section' => 'title_tagline',
        'priority' => 6,
        'settings' => 'theme_option_setting',
        'type' => 'select',
        'choices' => array(
            'default' => 'Default',
            'modern' => 'Modern',
            'ocean' => 'Ocean',
            'tomato' => 'Tomato',
            'cerulean' => 'Cerulean',
            'clipkaro' => 'ClipKaro',
            'cosmo' => 'Cosmo',
            'cyborg' => 'Cyborg',
            'darkly' => 'Darkly',
            'flatly' => 'Flatly',
            'journal' => 'Journal',
            'litera' => 'Litera',
            'lumen' => 'Lumen',
            'lux' => 'Lux',
            'materia' => 'Materia',
            'minty' => 'Minty',
            'pulse' => 'Pulse',
            'sandstone' => 'Sandstone',
            'simplex' => 'Simplex',
            'sketchy' => 'Sketchy',
            'slate' => 'Slate',
            'solar' => 'Solar',
            'spacelab' => 'Spacelab',
            'superhero' => 'Superhero',
            'united' => 'United',
            'yeti' => 'Yeti',
        )
    )));

    $wp_customize->add_setting('preset_style_setting', array(
        'default' => 'default',
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'preset_style_setting', array(
        'description' => __('Typography', 'clipmydeals'),
        'section' => 'title_tagline',
        'settings' => 'preset_style_setting',
        'priority' => 7,
        'type' => 'select',
        'choices' => array(
            'default' => 'Default',
            'arbutusslab-opensans' => 'Arbutus Slab / Opensans',
            'montserrat-merriweather' => 'Montserrat / Merriweather',
            'montserrat-opensans' => 'Montserrat / Opensans',
            'oswald-muli' => 'Oswald / Muli',
            'poppins-lora' => 'Poppins / Lora',
            'poppins-poppins' => 'Poppins / Poppins',
            'roboto-roboto' => 'Roboto / Roboto',
            'robotoslab-roboto' => 'Roboto Slab / Roboto',
        )
    )));

    $wp_customize->add_setting('header_color', array(
        'default' => null,
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_color', array(
        'priority' => 8,
        'label' => __('Header Background Color', 'clipmydeals'),
        'section' => 'title_tagline',
        'settings' => 'header_color',
    )));

    $wp_customize->add_setting('header_gradient', array(
        'default' => null,
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_gradient', array(
        'priority' => 8,
        'label' => __('Header Background Gradient', 'clipmydeals'),
        'section' => 'title_tagline',
        'settings' => 'header_gradient',
    )));

    $wp_customize->get_control('header_textcolor')->priority = 9;
    $wp_customize->get_setting('header_textcolor')->transport = 'refresh';
    $wp_customize->get_control('header_textcolor')->section = 'title_tagline';

    $wp_customize->add_setting('navbar_text_color', array(
        'default' => null,
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'navbar_text_color', array(
        'label' => __('Navbar Inactive Text Color', 'clipmydeals'),
        'priority' => 10,
        'section' => 'title_tagline',
        'settings' => 'navbar_text_color',
    )));

    $wp_customize->add_setting('navbar_search_btn_color', array(
        'default' => null,
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'navbar_search_btn_color', array(
        'label' => __('Navbar Search Button Color', 'clipmydeals'),
        'priority' => 11,
        'section' => 'title_tagline',
        'settings' => 'navbar_search_btn_color',
    )));
    // Starts here

    // Primary Color
    $wp_customize->add_setting('cmd_primary', array(
        'default' => null,
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cmd_primary', array(
        'label' => __('Primary Color', 'clipmydeals'),
        'priority' => 12,
        'section' => 'title_tagline',
        'settings' => 'cmd_primary',
    )));

    // Font Color
    $wp_customize->add_setting('cmd_card_title_color', array(
        'default' => null,
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cmd_card_title_color', array(
        'label' => __('Card Title Color', 'clipmydeals'),
        'priority' => 17,
        'section' => 'title_tagline',
        'settings' => 'cmd_card_title_color',
    )));

    // Card Background Color
    $wp_customize->add_setting('cmd_card_bg_color', array(
        'default' => null,
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cmd_card_bg_color', array(
        'label' => __('Card Background Color', 'clipmydeals'),
        'priority' => 19,
        'section' => 'title_tagline',
        'settings' => 'cmd_card_bg_color',
    )));

    $wp_customize->get_setting('background_image')->transport = 'refresh'; //TODO: CHECK
    $wp_customize->get_control('background_image')->priority = 21;
    $wp_customize->get_control('background_image')->section = 'title_tagline';
    $wp_customize->remove_section('background_image');

    $wp_customize->get_setting('background_color')->transport = 'refresh'; //TODO: CHECK
    $wp_customize->get_control('background_color')->priority = 22;
    $wp_customize->get_control('background_color')->section = 'title_tagline';

    $wp_customize->add_setting('footer_color', array(
        'default' => null,
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_color', array(
        'label' => __('Footer Color', 'clipmydeals'),
        'section' => 'title_tagline',
        'priority' => 23,
        'settings' => 'footer_color',
    )));

    $wp_customize->add_setting('footer_text_left', array(
        'default' =>   '&copy ' . current_time('Y') . " " . '<a href="' . home_url() . '">' . get_bloginfo('name') . '</a>',

    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'footer_text_left', array(
        'label' => __('Footer Text Left', 'clipmydeals'),
        'section' => 'title_tagline',
        'priority' => 24,
        'settings' => 'footer_text_left',
    )));


    $wp_customize->add_setting('footer_text_right', array(
        'default' => 'ClipMyDeals - WordPress Affiliate Theme',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'footer_text_right', array(
        'label' => __('Footer Text Right', 'clipmydeals'),
        'section' => 'title_tagline',
        'priority' => 25,
        'settings' => 'footer_text_right',
    )));




    $wp_customize->get_section('static_front_page')->priority = 2;
    $wp_customize->get_section('static_front_page')->description = '';

    // ADD FRONT PAGE SECTION
    $wp_customize->add_section('cmd_frontpage_settings', array(
        'title' => __('Frontpage Settings', 'clipmydeals'),
        'priority' => 3,
    ));

    $wp_customize->add_setting('hp_coupon_layout', array(
        'default' => 'default',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hp_coupon_layout', array(
        'label' => __('Homepage Coupon Layout', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'hp_coupon_layout',
        'type' => 'select',
        'choices' => array(
            'default' => __('default', 'clipmydeals'),
            '1' => __('List', 'clipmydeals'),
            '2' => __('2-Column Grid', 'clipmydeals'),
            '3' => __('3-Column Grid', 'clipmydeals'),
            '4' => __('4-Column Grid', 'clipmydeals'),
        )
    )));

    $wp_customize->add_setting('hp_coupon_count', array(
        'default' => 12,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('hp_coupon_count', array(
        'label' => __('No. of Coupons on Homepage', 'clipmydeals'),
        'type' => 'number',
        'section' => 'cmd_frontpage_settings',
    ));

    $wp_customize->add_setting('hp_popular_stores', array(
        'default' => false,
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hp_popular_stores', array(
        'label' => __('Display Popular Stores', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'hp_popular_stores',
        'type' => 'checkbox',
    )));

    $wp_customize->add_setting('hp_latest_coupons', array(
        'default' => true,
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hp_latest_coupons', array(
        'label' => __('Display Latest Coupons', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'hp_latest_coupons',
        'type' => 'checkbox',
    )));

    $wp_customize->add_setting('hp_latest_posts', array(
        'default' => true,
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hp_latest_posts', array(
        'label' => __('Display Latest Posts', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'hp_latest_posts',
        'type' => 'checkbox',
    )));

    $wp_customize->add_setting('welcome_type', array(
        'default' => 'video',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'welcome_type', array(
        'label' => __('Welcome Type', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'welcome_type',
        'type' => 'select',
        'choices' => array(
            'none' =>__( 'None', 'clipmydeals'),
            'video' =>__( 'Video (Full Screen)', 'clipmydeals'),
            'slides' =>__( 'Slides', 'clipmydeals'),
            'multi_slides' =>__( 'Multi Slides', 'clipmydeals'),
            'banner' =>__( 'Banner', 'clipmydeals'),
        )
    )));

    $wp_customize->add_setting('hp_video_url', array(
        'default' => 'https://ak8.picdn.net/shutterstock/videos/3532778/preview/stock-footage-shopping-online-with-credit-card-on-digital-tablet.webm',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hp_video_url', array(
        'label' => __('Video URL', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'hp_video_url',
        'type' => 'text'
    )));

    $wp_customize->add_setting('hp_video_search', array(
        'default' => true,
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hp_video_search', array(
        'label' => __('Show Search Form', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'hp_video_search',
        'type' => 'checkbox'
    )));

    $wp_customize->add_setting('hp_video_title', array(
        'default' => __('clipmydeals', 'clipmydeals'),
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hp_video_title', array(
        'label' => __('Video Title', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'hp_video_title',
        'type' => 'text'
    )));

    $wp_customize->add_setting('hp_video_title_color', array(
        'default' => null,
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'hp_video_title_color', array(
        'label' => __('Video Title Color', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'hp_video_title_color',
    )));

    $wp_customize->add_setting('hp_video_tagline', array(
        'default' => __('To customize the contents on this video and other elements of your site go to Dashboard > Appearance > Customize', 'clipmydeals'),
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hp_video_tagline', array(
        'label' => __('Video Tagline', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'hp_video_tagline',
        'type' => 'text'
    )));

    $wp_customize->add_setting('hp_video_tagline_color', array(
        'default' => null,
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'hp_video_tagline_color', array(
        'label' => __('Video Tagline Color', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'hp_video_tagline_color',
    )));

    // Slide1
    $wp_customize->add_setting('slide[1][0]', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize, 'slide[1][0]', array(
        'label' => __('Slide #1', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'slide[1][0]',
        'mime_type' => 'image',
        'width' => 1950,
        'height' => 600,
        'flex_width ' => true,
        'flex_height ' => true,
    )));
    $wp_customize->add_setting('slide[1][1]', array(
        'default' => '',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'slide[1][1]', array(
        //'label' => __( 'Slide #1 Landing Page', 'clipmydeals' ),
        'description' => __('Landing Page URL', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'slide[1][1]',
        'type' => 'url'
    )));

    // Slide2
    $wp_customize->add_setting('slide[2][0]', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize, 'slide[2][0]', array(
        'label' => __('Slide #2', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'slide[2][0]',
        'mime_type' => 'image',
        'width' => 1950,
        'height' => 600,
        'flex_width ' => true,
        'flex_height ' => true,
    )));
    $wp_customize->add_setting('slide[2][1]', array(
        'default' => '',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'slide[2][1]', array(
        //'label' => __( 'Slide #2 Landing Page', 'clipmydeals' ),
        'description' => __('Landing Page URL', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'slide[2][1]',
        'type' => 'url'
    )));

    // Slide3
    $wp_customize->add_setting('slide[3][0]', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize, 'slide[3][0]', array(
        'label' => __('Slide #3', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'slide[3][0]',
        'mime_type' => 'image',
        'width' => 1950,
        'height' => 600,
        'flex_width ' => true,
        'flex_height ' => true,
    )));
    $wp_customize->add_setting('slide[3][1]', array(
        'default' => '',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'slide[3][1]', array(
        //'label' => __( 'Slide #3 Landing Page', 'clipmydeals' ),
        'description' => __('Landing Page URL', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'slide[3][1]',
        'type' => 'url'
    )));

    // Slide4
    $wp_customize->add_setting('slide[4][0]', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize, 'slide[4][0]', array(
        'label' => __('Slide #4', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'slide[4][0]',
        'mime_type' => 'image',
        'width' => 1950,
        'height' => 600,
        'flex_width ' => true,
        'flex_height ' => true,
    )));
    $wp_customize->add_setting('slide[4][1]', array(
        'default' => '',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'slide[4][1]', array(
        //'label' => __( 'Slide #4 Landing Page', 'clipmydeals' ),
        'description' => __('Landing Page URL', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'slide[4][1]',
        'type' => 'url'
    )));

    // Slide5
    $wp_customize->add_setting('slide[5][0]', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize, 'slide[5][0]', array(
        'label' => __('Slide #5', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'slide[5][0]',
        'mime_type' => 'image',
        'width' => 1950,
        'height' => 600,
        'flex_width ' => true,
        'flex_height ' => true,
    )));
    $wp_customize->add_setting('slide[5][1]', array(
        'default' => '',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'slide[5][1]', array(
        //'label' => __( 'Slide #5 Landing Page', 'clipmydeals' ),
        'description' => __('Landing Page URL', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'slide[5][1]',
        'type' => 'url'
    )));

    // Slide6
    $wp_customize->add_setting('slide[6][0]', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize, 'slide[6][0]', array(
        'label' => __('Slide #6', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'slide[6][0]',
        'mime_type' => 'image',
        'width' => 1950,
        'height' => 600,
        'flex_width ' => true,
        'flex_height ' => true,
    )));
    $wp_customize->add_setting('slide[6][1]', array(
        'default' => '',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'slide[6][1]', array(
        //'label' => __( 'Slide #6 Landing Page', 'clipmydeals' ),
        'description' => __('Landing Page URL', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'slide[6][1]',
        'type' => 'url'
    )));

    // Slide7
    $wp_customize->add_setting('slide[7][0]', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize, 'slide[7][0]', array(
        'label' => __('Slide #7', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'slide[7][0]',
        'mime_type' => 'image',
        'width' => 1950,
        'height' => 600,
        'flex_width ' => true,
        'flex_height ' => true,
    )));
    $wp_customize->add_setting('slide[7][1]', array(
        'default' => '',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'slide[7][1]', array(
        //'label' => __( 'Slide #7 Landing Page', 'clipmydeals' ),
        'description' => __('Landing Page URL', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'slide[7][1]',
        'type' => 'url'
    )));

    // Slide8
    $wp_customize->add_setting('slide[8][0]', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize, 'slide[8][0]', array(
        'label' => __('Slide #8', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'slide[8][0]',
        'mime_type' => 'image',
        'width' => 1950,
        'height' => 600,
        'flex_width ' => true,
        'flex_height ' => true,
    )));
    $wp_customize->add_setting('slide[8][1]', array(
        'default' => '',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'slide[8][1]', array(
        //'label' => __( 'Slide #8 Landing Page', 'clipmydeals' ),
        'description' => __('Landing Page URL', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'slide[8][1]',
        'type' => 'url'
    )));

    // Slide9
    $wp_customize->add_setting('slide[9][0]', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize, 'slide[9][0]', array(
        'label' => __('Slide #9', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'slide[9][0]',
        'mime_type' => 'image',
        'width' => 1950,
        'height' => 600,
        'flex_width ' => true,
        'flex_height ' => true,
    )));
    $wp_customize->add_setting('slide[9][1]', array(
        'default' => '',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'slide[9][1]', array(
        //'label' => __( 'Slide #9 Landing Page', 'clipmydeals' ),
        'description' => __('Landing Page URL', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'slide[9][1]',
        'type' => 'url'
    )));

    // Slide10
    $wp_customize->add_setting('slide[10][0]', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize, 'slide[10][0]', array(
        'label' => __('Slide #10', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'slide[10][0]',
        'mime_type' => 'image',
        'width' => 1950,
        'height' => 600,
        'flex_width ' => true,
        'flex_height ' => true,
    )));
    $wp_customize->add_setting('slide[10][1]', array(
        'default' => '',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'slide[10][1]', array(
        //'label' => __( 'Slide #10 Landing Page', 'clipmydeals' ),
        'description' => __('Landing Page URL', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'slide[10][1]',
        'type' => 'url'
    )));

    // Images Per Slide
    $wp_customize->add_setting('multi_images_per_slide', array(
        'capability' => 'edit_theme_options',
        'default' => '3',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control('multi_images_per_slide', array(
        'type' => 'radio',
        'section' => 'cmd_frontpage_settings',
        'label' => __('Images per slide', 'clipmydeals'),
        'description' => __('Number of images to show per slide on multi slides.<br> Recommended Three Images per Slide', 'clipmydeals'),
        'choices' => array(
            '2' => __('Two', 'clipmydeals'),
            '3' => __('Three', 'clipmydeals'),
        ),
    ));

    // Multi Slide1
    $wp_customize->add_setting('multi_slide[1][0]', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize, 'multi_slide[1][0]', array(
        'label' => __('Multi Slide #1', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'multi_slide[1][0]',
        'mime_type' => 'image',
        'width' => 650,
        'height' => 600,
        'flex_width ' => true,
        'flex_height ' => true,
    )));
    $wp_customize->add_setting('multi_slide[1][1]', array(
        'default' => '',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'multi_slide[1][1]', array(
        //'label' => __( 'Multi Slide #1 Landing Page', 'clipmydeals' ),
        'description' => __('Landing Page URL', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'multi_slide[1][1]',
        'type' => 'url'
    )));

    // Multi Slide2
    $wp_customize->add_setting('multi_slide[2][0]', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize, 'multi_slide[2][0]', array(
        'label' => __('Multi Slide #2', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'multi_slide[2][0]',
        'mime_type' => 'image',
        'width' => 650,
        'height' => 600,
        'flex_width ' => true,
        'flex_height ' => true,
    )));
    $wp_customize->add_setting('multi_slide[2][1]', array(
        'default' => '',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'multi_slide[2][1]', array(
        //'label' => __( 'Multi Slide #2 Landing Page', 'clipmydeals' ),
        'description' => __('Landing Page URL', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'multi_slide[2][1]',
        'type' => 'url'
    )));

    // Multi Slide3
    $wp_customize->add_setting('multi_slide[3][0]', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize, 'multi_slide[3][0]', array(
        'label' => __('Multi Slide #3', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'multi_slide[3][0]',
        'mime_type' => 'image',
        'width' => 650,
        'height' => 600,
        'flex_width ' => true,
        'flex_height ' => true,
    )));
    $wp_customize->add_setting('multi_slide[3][1]', array(
        'default' => '',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'multi_slide[3][1]', array(
        //'label' => __( 'Multi Slide #3 Landing Page', 'clipmydeals' ),
        'description' => __('Landing Page URL', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'multi_slide[3][1]',
        'type' => 'url'
    )));

    // Multi Slide4
    $wp_customize->add_setting('multi_slide[4][0]', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize, 'multi_slide[4][0]', array(
        'label' => __('Multi Slide #4', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'multi_slide[4][0]',
        'mime_type' => 'image',
        'width' => 650,
        'height' => 600,
        'flex_width ' => true,
        'flex_height ' => true,
    )));
    $wp_customize->add_setting('multi_slide[4][1]', array(
        'default' => '',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'multi_slide[4][1]', array(
        //'label' => __( 'Multi Slide #4 Landing Page', 'clipmydeals' ),
        'description' => __('Landing Page URL', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'multi_slide[4][1]',
        'type' => 'url'
    )));

    // Multi Slide5
    $wp_customize->add_setting('multi_slide[5][0]', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize, 'multi_slide[5][0]', array(
        'label' => __('Multi Slide #5', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'multi_slide[5][0]',
        'mime_type' => 'image',
        'width' => 650,
        'height' => 600,
        'flex_width ' => true,
        'flex_height ' => true,
    )));
    $wp_customize->add_setting('multi_slide[5][1]', array(
        'default' => '',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'multi_slide[5][1]', array(
        //'label' => __( 'Multi Slide #5 Landing Page', 'clipmydeals' ),
        'description' => __('Landing Page URL', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'multi_slide[5][1]',
        'type' => 'url'
    )));

    // Multi Slide6
    $wp_customize->add_setting('multi_slide[6][0]', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize, 'multi_slide[6][0]', array(
        'label' => __('Multi Slide #6', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'multi_slide[6][0]',
        'mime_type' => 'image',
        'width' => 650,
        'height' => 600,
        'flex_width ' => true,
        'flex_height ' => true,
    )));
    $wp_customize->add_setting('multi_slide[6][1]', array(
        'default' => '',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'multi_slide[6][1]', array(
        //'label' => __( 'Multi Slide #6 Landing Page', 'clipmydeals' ),
        'description' => __('Landing Page URL', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'multi_slide[6][1]',
        'type' => 'url'
    )));

    // Multi Slide7
    $wp_customize->add_setting('multi_slide[7][0]', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize, 'multi_slide[7][0]', array(
        'label' => __('Multi Slide #7', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'multi_slide[7][0]',
        'mime_type' => 'image',
        'width' => 650,
        'height' => 600,
        'flex_width ' => true,
        'flex_height ' => true,
    )));
    $wp_customize->add_setting('multi_slide[7][1]', array(
        'default' => '',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'multi_slide[7][1]', array(
        //'label' => __( 'Multi Slide #7 Landing Page', 'clipmydeals' ),
        'description' => __('Landing Page URL', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'multi_slide[7][1]',
        'type' => 'url'
    )));

    // Multi Slide8
    $wp_customize->add_setting('multi_slide[8][0]', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize, 'multi_slide[8][0]', array(
        'label' => __('Multi Slide #8', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'multi_slide[8][0]',
        'mime_type' => 'image',
        'width' => 650,
        'height' => 600,
        'flex_width ' => true,
        'flex_height ' => true,
    )));
    $wp_customize->add_setting('multi_slide[8][1]', array(
        'default' => '',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'multi_slide[8][1]', array(
        //'label' => __( 'Multi Slide #8 Landing Page', 'clipmydeals' ),
        'description' => __('Landing Page URL', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'multi_slide[8][1]',
        'type' => 'url'
    )));

    // Multi Slide9
    $wp_customize->add_setting('multi_slide[9][0]', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize, 'multi_slide[9][0]', array(
        'label' => __('Multi Slide #9', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'multi_slide[9][0]',
        'mime_type' => 'image',
        'width' => 650,
        'height' => 600,
        'flex_width ' => true,
        'flex_height ' => true,
    )));
    $wp_customize->add_setting('multi_slide[9][1]', array(
        'default' => '',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'multi_slide[9][1]', array(
        //'label' => __( 'Multi Slide #9 Landing Page', 'clipmydeals' ),
        'description' => __('Landing Page URL', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'multi_slide[9][1]',
        'type' => 'url'
    )));

    // Multi Slide10
    $wp_customize->add_setting('multi_slide[10][0]', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize, 'multi_slide[10][0]', array(
        'label' => __('Multi Slide #10', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'multi_slide[10][0]',
        'mime_type' => 'image',
        'width' => 650,
        'height' => 600,
        'flex_width ' => true,
        'flex_height ' => true,
    )));
    $wp_customize->add_setting('multi_slide[10][1]', array(
        'default' => '',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'multi_slide[10][1]', array(
        //'label' => __( 'Multi Slide #10 Landing Page', 'clipmydeals' ),
        'description' => __('Landing Page URL', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'multi_slide[10][1]',
        'type' => 'url'
    )));

    // Multi Slide11
    $wp_customize->add_setting('multi_slide[11][0]', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize, 'multi_slide[11][0]', array(
        'label' => __('Multi Slide #11', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'multi_slide[11][0]',
        'mime_type' => 'image',
        'width' => 650,
        'height' => 600,
        'flex_width ' => true,
        'flex_height ' => true,
    )));
    $wp_customize->add_setting('multi_slide[11][1]', array(
        'default' => '',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'multi_slide[11][1]', array(
        //'label' => __( 'Multi Slide #11 Landing Page', 'clipmydeals' ),
        'description' => __('Landing Page URL', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'multi_slide[11][1]',
        'type' => 'url'
    )));

    // Multi Slide12
    $wp_customize->add_setting('multi_slide[12][0]', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize, 'multi_slide[12][0]', array(
        'label' => __('Multi Slide #12', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'multi_slide[12][0]',
        'mime_type' => 'image',
        'width' => 650,
        'height' => 600,
        'flex_width ' => true,
        'flex_height ' => true,
    )));
    $wp_customize->add_setting('multi_slide[12][1]', array(
        'default' => '',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'multi_slide[12][1]', array(
        //'label' => __( 'Multi Slide #11 Landing Page', 'clipmydeals' ),
        'description' => __('Landing Page URL', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'multi_slide[12][1]',
        'type' => 'url'
    )));


    // $wp_customize->add_control('header_img', array(
    //     'label' => __('Header Image', 'clipmydeals'),
    //     'section' => 'header_images',
    //     'type' => 'text',
    // ));

    /*Banner*/

    $wp_customize->get_control('header_image')->priority = 11;
    $wp_customize->get_control('header_image')->section = 'cmd_frontpage_settings';
    $wp_customize->remove_section('header_image');

    $wp_customize->add_setting('header_banner_search', array(
        'default' => true,
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'header_banner_search', array(
        'label' => __('Show Search Form', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'header_banner_search',
        'priority' => 12,
        'type' => 'checkbox'
    )));

    $wp_customize->add_setting('header_banner_title_setting', array(
        'default' => __('ClipMyDeals', 'clipmydeals'),
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'header_banner_title_setting', array(
        'label' => __('Banner Title', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'header_banner_title_setting',
        'priority' => 12,
        'type' => 'text'
    )));

    $wp_customize->add_setting('header_banner_title_color', array(
        'default' => null,
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_banner_title_color', array(
        'label' => __('Banner Title Color', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'header_banner_title_color',
        'priority' => 12,
    )));

    $wp_customize->add_setting('header_banner_tagline_setting', array(
        'default' => __('To customize the contents of this header banner and other elements of your site go to Dashboard > Appearance > Customize', 'clipmydeals'),
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'header_banner_tagline_setting', array(
        'label' => __('Banner Tagline', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'settings' => 'header_banner_tagline_setting',
        'priority' => 12,
        'type' => 'text'
    )));

    $wp_customize->add_setting('header_banner_tagline_color', array(
        'default' => null,
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_banner_tagline_color', array(
        'label' => __('Banner Tagline Color', 'clipmydeals'),
        'section' => 'cmd_frontpage_settings',
        'priority' => 12,
        'settings' => 'header_banner_tagline_color',
    )));


    // $wp_customize->add_setting('header_bg_color_setting', array(
    //     'default' => null,
    //     'sanitize_callback' => 'sanitize_hex_color',
    // ));
    // $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_bg_color', array(
    //     'label' => __('Header Banner Background Color', 'clipmydeals'),
    //     'section' => 'static_front_page',
    //     'settings' => 'header_bg_color_setting',
    // )));

    // $wp_customize->add_setting('header_banner_visibility', array(
    //     'capability' => 'edit_theme_options',
    //     'sanitize_callback' => 'themeslug_sanitize_checkbox',
    // ));
    // $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'header_banner_visibility', array(
    //     'settings' => 'header_banner_visibility',
    //     'label' => __('Remove Header Banner', 'clipmydeals'),
    //     'section' => 'static_front_page',
    //     'type' => 'checkbox',
    // )));


    /* AJAX */
    $wp_customize->add_section('ajax_search_options', array(
        'title' => __('Ajax Search', 'clipmydeals'),
        'priority' => 4,
    ));

    // Live Search Control
    $wp_customize->add_setting('ajax_search', array('default' => 'no',));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'ajax_search', array(
        'label' => __('Enable Ajax Search', 'clipmydeals'),
        'description' => __('Show ajax results for search box in header.', 'clipmydeals'),
        'section' => 'ajax_search_options',
        'settings' => 'ajax_search',
        'type' => 'select',
        'choices' => array('no' => __('No', 'clipmydeals'), 'yes' => __('Yes', 'clipmydeals')),
    )));

    $wp_customize->add_setting('ajax_search_show_image', array('default' => 0,));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'ajax_search_show_image', array(
        'label' => __('Show Images for Ajax Search', 'clipmydeals'),
        'section' => 'ajax_search_options',
        'settings' => 'ajax_search_show_image',
        'type' => 'checkbox'
    )));

    $wp_customize->add_setting('ajax_search_max_results', array('default' => 5,));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'ajax_search_max_results', array(
        'label' => __('Number of results to show in Ajax search', 'clipmydeals'),
        'description' => __('Set to 0 for all results.', 'clipmydeals'),
        'section' => 'ajax_search_options',
        'settings' => 'ajax_search_max_results',
        'type' => 'number',
    )));

    $wp_customize->add_setting('ajax_search_start_after', array('default' => 3, 'sanitize_callback' => 'absint',));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'ajax_search_start_after', array(
        'label' => __('Start searching after "N" characters', 'clipmydeals'),
        'description' => __('Set to 0 to start immediately', 'clipmydeals'),
        'section' => 'ajax_search_options',
        'settings' => 'ajax_search_start_after',
        'type' => 'number',
    )));

    // ADD A COUPONS SECTION
    $wp_customize->add_section('coupons', array(
        'title' => __('Coupon Settings', 'clipmydeals'),
        'priority' => 5,
    ));

    // No. of coupons in a row
    $wp_customize->add_setting('count_in_row', array(
        'default' => 2,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('count_in_row', array(
        'label' => __('Number of Coupons on single row', 'clipmydeals'),
        'type' => 'select',
        'choices' => array(1 => __('List (1 per row)', 'clipmydeals'), 2 => __('Grid (2 in a row)', 'clipmydeals'), 3 => __('Grid (3 in a row)', 'clipmydeals'), 4 => __('Grid (4 in a row)', 'clipmydeals')),
        'section' => 'coupons',
    ));

    // Reveal Type
    $wp_customize->add_setting('reveal_type', array(
        'default' => 'inline',
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('reveal_type', array(
        'label' => __('Show Coupon', 'clipmydeals'),
        'type' => 'select',
        'choices' => array('inline' => __('Inline', 'clipmydeals'), 'popup' => __('Popup', 'clipmydeals'), 'always' => __('Always Show', 'clipmydeals')),
        'section' => 'coupons',
    ));

    // Location Taxonomy
    $wp_customize->add_setting('location_taxonomy', array(
        'default' => false,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('location_taxonomy', array(
        'label' => __('Location Taxonomy', 'clipmydeals'),
        'type' => 'select',
        'choices' => array(false => __('Disable', 'clipmydeals'), true => __('Enable', 'clipmydeals')),
        'section' => 'coupons',
    ));

    // Show Categories on Coupons
    $wp_customize->add_setting('show_coupon_categories', array(
        'default' => 'all',
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('show_coupon_categories', array(
        'label' => __('Show Category list on Coupons?', 'clipmydeals'),
        'type' => 'select',
        'choices' => array('no' => __('No', 'clipmydeals'), 'parent' => __('Show only Parent Categories', 'clipmydeals'), 'all' => __('Show All Categories', 'clipmydeals')),
        'section' => 'coupons',
    ));
    // Show Brands on Coupons
    $wp_customize->add_setting('show_coupon_brands', array(
        'default' => 'yes',
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('show_coupon_brands', array(
        'label' => __('Show brands on Coupons?', 'clipmydeals'),
        'type' => 'select',
        'choices' => array('no' => __('No', 'clipmydeals'), 'yes' => __('Show All Brands', 'clipmydeals')),
        'section' => 'coupons',
    ));


    // Show Locations on Coupons
    $wp_customize->add_setting('show_coupon_locations', array(
        'default' => 'all',
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('show_coupon_locations', array(
        'label' => __('Show Location on Coupons?', 'clipmydeals'),
        'type' => 'select',
        'choices' => array('no' => __('No', 'clipmydeals'), 'parent' => __('Show only Parent Locations', 'clipmydeals'), 'all' => __('Show All Locations', 'clipmydeals')),
        'section' => 'coupons',
    ));

    // Active Coupons on a page
    $wp_customize->add_setting('active_coupon_count', array(
        'default' => 50,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('active_coupon_count', array(
        'label' => __('No. of Active Coupons on Store/Category/Brand page', 'clipmydeals'),
        'description' => __('Enter -1 to display all', 'clipmydeals'),
        'type' => 'number',
        'section' => 'coupons',
    ));

    // Expired Coupons on a page
    $wp_customize->add_setting('expired_coupon_count', array(
        'default' => 10,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('expired_coupon_count', array(
        'label' => __('No. of Expired Coupons on Store/Category/Brand page', 'clipmydeals'),
        'description' => __('Enter -1 to display all', 'clipmydeals'),
        'type' => 'number',
        'section' => 'coupons',
    ));

    // Separate page for each coupon?
    $wp_customize->add_setting('coupon_page', array(
        'default' => 'yes',
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('coupon_page', array(
        'label' => __('Allow Coupons to have their own page?', 'clipmydeals'),
        'description' => __('Is set to "No", comments & sharing of individual coupons will get disabled', 'clipmydeals'),
        'type' => 'select',
        'choices' => array('no' => __('No', 'clipmydeals'), 'yes' => __('Yes', 'clipmydeals')),
        'section' => 'coupons',
    ));

    // Article placement on Store/Catefory page?
    $wp_customize->add_setting('article_placement', array(
        'default' => 'bottom',
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('article_placement', array(
        'label' => __('Show article on Store/Category/Location/Brand pages?', 'clipmydeals'),
        'type' => 'select',
        'choices' => array('none' => __('No', 'clipmydeals'), 'top' => __('Yes - Show above the Coupons', 'clipmydeals'), 'bottom' => __('Yes - Show below the Coupons', 'clipmydeals')),
        'section' => 'coupons',
    ));

    $wp_customize->add_setting('coupon_default_description', array(
        'default' => __('Enjoy big savings with this voucher. Don’t miss out, valid for limited period only.', 'clipmydeals'),
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control('coupon_default_description', array(
        'label' => __('Default Coupon Description', 'clipmydeals'),
        'section' => 'coupons',
        'type' => 'text'
    ));

    /* APPS */
    $wp_customize->add_section('app_options', array(
        'title' => __('App Options', 'clipmydeals'),
        'priority' => 5,
    ));

    // Show Get Android In Footer
    $wp_customize->add_setting('display_get_android', array(
        'default' => 'on',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'display_get_android', array(
        'label' => __('Display Get Android In Footer', 'clipmydeals'),
        'description' => __('Uncheck this if you do not want show get android in footer', 'clipmydeals'),
        'section' => 'app_options',
        'settings' => 'display_get_android',
        'type' => 'checkbox'
    )));

    // Show Get Ios In Footer
    $wp_customize->add_setting('display_get_ios', array(
        'default' => 'on',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'display_get_ios', array(
        'label' => __('Display Get IOS In Footer', 'clipmydeals'),
        'description' => __('Uncheck this if you do not want show get ios in footer', 'clipmydeals'),
        'section' => 'app_options',
        'settings' => 'display_get_ios',
        'type' => 'checkbox'
    )));

    // IOS App/Bundle ID
    $wp_customize->add_setting('ios_bundle_name', array(
        'default' => '',
        'transport' => 'postMessage',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'ios_bundle_name', array(
        'label' => __('IOS App/Bundle ID', 'clipmydeals'),
        'description' => __('Example: url-friendly-app-name/id123456', 'clipmydeals'),
        'section' => 'app_options',
        'settings' => 'ios_bundle_name',
        'type' => 'text'
    )));

    // Android App/Bundle ID
    $wp_customize->add_setting('android_bundle_name', array(
        'default' => '',
        'transport' => 'postMessage',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'android_bundle_name', array(
        'label' => __('Android App/Bundle ID', 'clipmydeals'),
        'description' => __('Example: com.google.android', 'clipmydeals'),
        'section' => 'app_options',
        'settings' => 'android_bundle_name',
        'type' => 'text'
    )));



    //Other Customization
    $wp_customize->add_section('site_name_text_color', array(
        'title' => __('Other Customizations', 'clipmydeals'),
        //'description' => __( 'This is a section for the header banner Image.', 'clipmydeals' ),
        'priority' => 50,
    ));

    // Header Width
    $wp_customize->add_setting('header_container', array(
        'default' => 'container-xl',
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('header_container', array(
        'label' => __('Header Width', 'clipmydeals'),
        'type' => 'select',
        'choices' => array('container-xl' => __('Container', 'clipmydeals'), 'container-fluid' => __('Full Width', 'clipmydeals')),
        'section' => 'site_name_text_color',
    ));

    // Sidebar Visibility
    $wp_customize->add_setting('sidebar_visibility', array(
        'default' => 'on',
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('sidebar_visibility', array(
        'label' => __('Show sidebar', 'clipmydeals'),
        'type' => 'select',
        'choices' => array('on' => __('On all pages', 'clipmydeals'), 'not_on_homepage' => __('Not on Homepage', 'clipmydeals'), 'only_on_homepage' => __('Only on homepage', 'clipmydeals'), 'no' => __('No', 'clipmydeals')),
        'section' => 'site_name_text_color',
    ));

    // Sidebar Position
    $wp_customize->add_setting('sidebar_position', array(
        'default' => '1',
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('sidebar_position', array(
        'label' => __('Sidebar position', 'clipmydeals'),
        'type' => 'select',
        'choices' => array('1' => __('Right', 'clipmydeals'), '0' => __('Left', 'clipmydeals')),
        'section' => 'site_name_text_color',
    ));

    // Layout
    $wp_customize->add_setting('container_type', array(
        'default' => 'container-xl',
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('container_type', array(
        'label' => __('Layout Type', 'clipmydeals'),
        'type' => 'select',
        'choices' => array('container-xl' => __('Container', 'clipmydeals'), 'container-fluid' => __('Full width', 'clipmydeals')),
        'section' => 'site_name_text_color',
    ));

    // Hompage Layout
    $wp_customize->add_setting('hp_container_type', array(
        'default' => 'default',
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('hp_container_type', array(
        'label' => __('Homepage Layout Type', 'clipmydeals'),
        'type' => 'select',
        'choices' => array('default' => __('Default', 'clipmydeals'), 'container-xl' => __('Container', 'clipmydeals'), 'container-fluid' => __('Full width', 'clipmydeals')),
        'section' => 'site_name_text_color',
    ));

    // Navbar
    $wp_customize->add_setting('navbar_type', array(
        'default' => 'fixed-top',
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('navbar_type', array(
        'label' => __('Navbar Type', 'clipmydeals'),
        'type' => 'select',
        'choices' => array('fixed-top' => __('Sticky Top', 'clipmydeals'), '' => __('Normal', 'clipmydeals')),
        'section' => 'site_name_text_color',
    ));

    // Request Notification Access prompt
    $wp_customize->add_setting('notification_requests', array('default' => 'enable',));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'notification_requests', array(
        'label' => __('Notification Requests', 'clipmydeals'),
        'description' => __('Request access for sending "Push Notifications"', 'clipmydeals'),
        'section' => 'site_name_text_color',
        'settings' => 'notification_requests',
        'type' => 'select',
        'choices' => array('enable' => __('Enable', 'clipmydeals'), 'disable' => __('Disable', 'clipmydeals')),
    )));

    // Notifications Onload on/off
    $wp_customize->add_setting('onload_notifications', array('default' => 'on',));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'onload_notifications', array(
        'label' => __('Ask Notification Permission Onload?', 'clipmydeals'),
        'section' => 'site_name_text_color',
        'settings' => 'onload_notifications',
        'type' => 'select',
        'choices' => array('on' => __('On', 'clipmydeals'), 'off' => __('Off', 'clipmydeals')),
    )));

    /* CARD AND IMAGE SETTING */
    $wp_customize->add_panel('card_and_image_settings', array(
        'priority' => 151,
        'title' => __('Card And Image Settings', 'clipmydeals'),
        'capability' => 'edit_theme_options'
    ));

    // ADD A CARD IMAGE HEIGHTS SECTION
    $wp_customize->add_section('content_length', array(
        'panel' => 'card_and_image_settings',
        'title' => __('Content Length', 'clipmydeals'),
        'priority' => 1,
    ));

    // Title length for a coupon on listing page (in characters)
    $wp_customize->add_setting('coupon_title_characters_count', array(
        'default' => 0,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('coupon_title_characters_count', array(
        'label' => __('Max. characters for coupon title', 'clipmydeals'),
        'description' => __('Enter 0 for no limit', 'clipmydeals'),
        'type' => 'number',
        'input_attrs' => array(
            'min' => 0,
        ),
        'section' => 'content_length'
    ));

    // Description length for a coupon on listing page (in characters)
    $wp_customize->add_setting('coupon_characters_count_before_more_tag', array(
        'default' => 0,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('coupon_characters_count_before_more_tag', array(
        'label' => __('Max. characters for coupon description', 'clipmydeals'),
        'description' => __('Enter 0 for no limit', 'clipmydeals'),
        'type' => 'number',
        'input_attrs' => array(
            'min' => 0,
        ),
        'section' => 'content_length'
    ));

    // ADD A CARD IMAGE HEIGHTS SECTION
    $wp_customize->add_section('mobile_card_and_images', array(
        'panel' => 'card_and_image_settings',
        'description' => __('Enter values in pixels for custom height and 0 for defualt values', 'clipmydeals'),
        'title' => __('Mobile Card and Images', 'clipmydeals'),
        'priority' => 2,
    ));

    $wp_customize->add_setting('mobile_card_and_images_use_value', array(
        'default' => 'default',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'mobile_card_and_images_use_value', array(
        'label' => __('Use Values', 'clipmydeals'),
        'section' => 'mobile_card_and_images',
        'settings' => 'mobile_card_and_images_use_value',
        'type' => 'select',
        'choices' => array(
            'default' => __('Default', 'clipmydeals'),
            'custom' => __('Custom', 'clipmydeals'),
        )
    )));

    // Carousel Image Height
    $wp_customize->add_setting('mobile_carousel_image', array(
        'default' => 0,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('mobile_carousel_image', array(
        'label' => __('Carousel Image', 'clipmydeals'),
        'type' => 'number',
        'input_attrs' => array('min' => 0, 'step' => 0.01, 'default' => 0),
        'section' => 'mobile_card_and_images'
    ));

    // Carousel Card Height
    $wp_customize->add_setting('mobile_carousel_card', array(
        'default' => 0,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('mobile_carousel_card', array(
        'label' => __('Carousel Card', 'clipmydeals'),
        'type' => 'number',
        'input_attrs' => array('min' => 0, 'step' => 0.01, 'default' => 0),
        'section' => 'mobile_card_and_images'
    ));

    // Coupon Image Height
    $wp_customize->add_setting('mobile_coupon_image', array(
        'default' => 0,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('mobile_coupon_image', array(
        'label' => __('Coupon Image', 'clipmydeals'),
        'type' => 'number',
        'input_attrs' => array('min' => 0, 'step' => 0.01, 'default' => 0),
        'section' => 'mobile_card_and_images'
    ));

    // Coupon Card Height
    $wp_customize->add_setting('mobile_coupon_card', array(
        'default' => 0,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('mobile_coupon_card', array(
        'label' => __('Coupon Card', 'clipmydeals'),
        'type' => 'number',
        'input_attrs' => array('min' => 0, 'step' => 0.01, 'default' => 0),
        'section' => 'mobile_card_and_images'
    ));

    // Product Image Height
    $wp_customize->add_setting('mobile_product_image', array(
        'default' => 0,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('mobile_product_image', array(
        'label' => __('Product Image', 'clipmydeals'),
        'type' => 'number',
        'input_attrs' => array('min' => 0, 'step' => 0.01, 'default' => 0),
        'section' => 'mobile_card_and_images'
    ));

    // Product Card Height
    $wp_customize->add_setting('mobile_product_card', array(
        'default' => 0,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('mobile_product_card', array(
        'label' => __('Product Card', 'clipmydeals'),
        'type' => 'number',
        'input_attrs' => array('min' => 0, 'step' => 0.01, 'default' => 0),
        'section' => 'mobile_card_and_images'
    ));

    // Store Image Height
    $wp_customize->add_setting('mobile_store_image', array(
        'default' => 0,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('mobile_store_image', array(
        'label' => __('Store Logos in store listings', 'clipmydeals'),
        'type' => 'number',
        'input_attrs' => array('min' => 0, 'step' => 0.01, 'default' => 0),
        'section' => 'mobile_card_and_images'
    ));

    // ADD A CARD IMAGE HEIGHTS SECTION
    $wp_customize->add_section('desktop_card_and_images', array(
        'panel' => 'card_and_image_settings',
        'description' => __('Enter values in pixels for custom height and 0 for defualt values', 'clipmydeals'),
        'title' => __('Desktop Card and Images', 'clipmydeals'),
        'priority' => 3,
    ));

    $wp_customize->add_setting('desktop_card_and_images_use_value', array(
        'default' => 'default',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'desktop_card_and_images_use_value', array(
        'label' => __('Use Values', 'clipmydeals'),
        'section' => 'desktop_card_and_images',
        'settings' => 'desktop_card_and_images_use_value',
        'type' => 'select',
        'choices' => array(
            'default' => __('Default', 'clipmydeals'),
            'custom' => __('Custom', 'clipmydeals'),
        )
    )));

    // Carousel Image Height
    $wp_customize->add_setting('desktop_carousel_image', array(
        'default' => 0,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('desktop_carousel_image', array(
        'label' => __('Carousel Image', 'clipmydeals'),
        'type' => 'number',
        'input_attrs' => array('min' => 0, 'step' => 0.01, 'default' => 0),
        'section' => 'desktop_card_and_images'
    ));

    // Carousel Card Height
    $wp_customize->add_setting('desktop_carousel_card', array(
        'default' => 0,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('desktop_carousel_card', array(
        'label' => __('Carousel Card', 'clipmydeals'),
        'type' => 'number',
        'input_attrs' => array('min' => 0, 'step' => 0.01, 'default' => 0),
        'section' => 'desktop_card_and_images'
    ));

    // Coupon Image Height
    $wp_customize->add_setting('desktop_coupon_image', array(
        'default' => 0,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('desktop_coupon_image', array(
        'label' => __('Coupon Image', 'clipmydeals'),
        'type' => 'number',
        'input_attrs' => array('min' => 0, 'step' => 0.01, 'default' => 0),
        'section' => 'desktop_card_and_images'
    ));

    // Coupon Card Height
    $wp_customize->add_setting('desktop_coupon_card', array(
        'default' => 0,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('desktop_coupon_card', array(
        'label' => __('Coupon Card', 'clipmydeals'),
        'type' => 'number',
        'input_attrs' => array('min' => 0, 'step' => 0.01, 'default' => 0),
        'section' => 'desktop_card_and_images'
    ));

    // Product Image Height
    $wp_customize->add_setting('desktop_product_image', array(
        'default' => 0,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('desktop_product_image', array(
        'label' => __('Product Image', 'clipmydeals'),
        'type' => 'number',
        'input_attrs' => array('min' => 0, 'step' => 0.01, 'default' => 0),
        'section' => 'desktop_card_and_images'
    ));

    // Product Card Height
    $wp_customize->add_setting('desktop_product_card', array(
        'default' => 0,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('desktop_product_card', array(
        'label' => __('Product Card', 'clipmydeals'),
        'type' => 'number',
        'input_attrs' => array('min' => 0, 'step' => 0.01, 'default' => 0),
        'section' => 'desktop_card_and_images'
    ));

    // Store Image Height
    $wp_customize->add_setting('desktop_store_image', array(
        'default' => 0,
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'wp_filter_nohtml_kses'
    ));
    $wp_customize->add_control('desktop_store_image', array(
        'label' => __('Store Logos in store listings', 'clipmydeals'),
        'type' => 'number',
        'input_attrs' => array('min' => 0, 'step' => 0.01, 'default' => 0),
        'section' => 'desktop_card_and_images'
    ));

    // Display type
    $wp_customize->add_section('image_preferences', array(
        'panel' => 'card_and_image_settings',
        'title' => __('Image Preferences', 'clipmydeals'),
        'priority' => 4,
    ));

    $wp_customize->add_setting('display_type', array(
        'default' => 'contain',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'display_type', array(
        'label' => __('Display Type', 'clipmydeals'),
        'description' => __('When incorporating Image Preference for Display Type setting, ensure that a custom height is set for card, carousal, coupon/product image and store logo images under Desktop/Mobile Card and Image Settings. 
        ', 'clipmydeals'),
        'section' => 'image_preferences',
        'settings' => 'display_type',
        'type' => 'select',
        'choices' => array(
            'contain' => __('Contain', 'clipmydeals'),
            'cover' => __('Cover', 'clipmydeals'),
            'fill' => __('Fill', 'clipmydeals'),
            'scale' => __('Scale', 'clipmydeals'),
        )
    )));
    // Placeholder Image

    $wp_customize->add_setting('default_coupon_image', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'default_coupon_image', array(
        'label' => __('Default Coupon Image', 'clipmydeals'),
        'description' => __('This will be used as a default image in case a coupon does not have an image associated with it
        .', 'clipmydeals'),
        'section' => 'image_preferences',
        'settings' => 'default_coupon_image',
        'mime_type' => 'image',
    )));

    $wp_customize->add_setting('default_store_image', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'default_store_image', array(
        'label' => __('Default Store Image', 'clipmydeals'),
        'description' => __('The default image will be seen on the store listing section only in case a store does not have an image associated with it.
        ', 'clipmydeals'),
        'section' => 'image_preferences',
        'settings' => 'default_store_image',
        'mime_type' => 'image',
    )));

    /* SOCIAL MEDIA */
    $wp_customize->add_section('social_media_settings', array(
        'title' => __('Social Media Optimization', 'clipmydeals'),
        'priority' => 152,
    ));

    // Enable Social Media
    $wp_customize->add_setting('enable_social_meta_tags', array(
        'default' => true,
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'enable_social_meta_tags', array(
        'label' => __('Enable Social Media Optimization', 'clipmydeals'),
        'description' => __('Uncheck this if you are using a separate plugin for SEO and Social Media Optimization', 'clipmydeals'),
        'section' => 'social_media_settings',
        'settings' => 'enable_social_meta_tags',
        'type' => 'checkbox'
    )));

    // Site Description
    $wp_customize->add_setting('site_description', array(
        'default' => __('Get Latest Coupons and Deals for your Online Shopping', 'clipmydeals'),
        'sanitize_callback' => 'wp_filter_nohtml_kses',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'site_description', array(
        'label' => __('Site Description', 'clipmydeals'),
        'description' => __('This will be the default description in case no content is available on the page being shared. Example: Home or Contact-Us page.', 'clipmydeals'),
        'section' => 'social_media_settings',
        'settings' => 'site_description',
        'type' => 'textarea'
    )));

    // Default Banner
    $wp_customize->add_setting('site_image', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'site_image', array(
        'label' => __('Banner', 'clipmydeals'),
        'description' => __('This will be the default image in case no image is available on the page being shared.', 'clipmydeals'),
        'section' => 'social_media_settings',
        'settings' => 'site_image',
        'mime_type' => 'image',
    )));

    // Facebook App ID
    $wp_customize->add_setting('facebook_app_id', array(
        'default' => '',
        'transport' => 'postMessage',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'facebook_app_id', array(
        'label' => __('Facebook App ID', 'clipmydeals'),
        'section' => 'social_media_settings',
        'settings' => 'facebook_app_id',
        'type' => 'text'
    )));

    // Facebook Page
    $wp_customize->add_setting('facebook_page', array(
        'default' => '',
        'transport' => 'postMessage',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'facebook_page', array(
        'label' => __('Facebook Page URL', 'clipmydeals'),
        'description' => __('Full URL of website\'s Facebook Page', 'clipmydeals'),
        'section' => 'social_media_settings',
        'settings' => 'facebook_page',
        'type' => 'text'
    )));

    // Facebook Admins
    $wp_customize->add_setting('facebook_admins', array(
        'default' => '',
        'transport' => 'postMessage',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'facebook_admins', array(
        'label' => __('Facebook Admins', 'clipmydeals'),
        'description' => __('Comma Separated list of Facebook Profile IDs that are Admin of your Facebook Page.', 'clipmydeals'),
        'section' => 'social_media_settings',
        'settings' => 'facebook_admins',
        'type' => 'text'
    )));

    // Twitter Page
    $wp_customize->add_setting('twitter_page', array(
        'default' => '',
        'transport' => 'postMessage',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'twitter_page', array(
        'label' => __('Twitter Page', 'clipmydeals'),
        'description' => __('@username for the website', 'clipmydeals'),
        'section' => 'social_media_settings',
        'settings' => 'twitter_page',
        'type' => 'text'
    )));

    // Twitter Author
    $wp_customize->add_setting('twitter_author', array(
        'default' => '',
        'transport' => 'postMessage',
        'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'twitter_author', array(
        'label' => __('Twitter Author', 'clipmydeals'),
        'description' => __('@username for the admin of website', 'clipmydeals'),
        'section' => 'social_media_settings',
        'settings' => 'twitter_author',
        'type' => 'text'
    )));


    /* ADDITIONAL HTML */
    $wp_customize->add_section('additional_html', array(
        'title' => __('Additional HTML/Scripts', 'clipmydeals'),
        'priority' => 153,
    ));

    // Header HTML
    $wp_customize->add_setting('additional_html_header', array(
        'default' => __('', 'clipmydeals'),
        'sanitize_callback' => 'clipmydeals_sanitize_code',
        'sanitize_js_callback' => 'clipmydeals_sanitize_output',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'additional_html_header', array(
        'label' => __('Header', 'clipmydeals'),
        'description' => __('This will be added in the <code>&lt;head&gt;</code> section', 'clipmydeals'),
        'section' => 'additional_html',
        'settings' => 'additional_html_header',
        'type' => 'textarea'
    )));

    // Footer HTML
    $wp_customize->add_setting('additional_html_footer', array(
        'default' => __('', 'clipmydeals'),
        'sanitize_callback' => 'clipmydeals_sanitize_code',
        'sanitize_js_callback' => 'clipmydeals_sanitize_output',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'additional_html_footer', array(
        'label' => __('Footer', 'clipmydeals'),
        'description' => __('This will be added before <code>&lt;/body	&gt;</code> tag', 'clipmydeals'),
        'section' => 'additional_html',
        'settings' => 'additional_html_footer',
        'type' => 'textarea'
    )));

    // list of JS files that should not be deferred
    $wp_customize->add_setting('script_tag_skip_defer', array(
        'default' => __('', 'clipmydeals'),
        'sanitize_callback' => 'wp_filter_nohtml_kses',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'script_tag_skip_defer', array(
        'label' => __('Do not defer following script tags', 'clipmydeals'),
        'description' => __('Comma seperated names of javascript files that should not have defer attribute.', 'clipmydeals'),
        'section' => 'additional_html',
        'settings' => 'script_tag_skip_defer',
        'type' => 'textarea'
    )));

    $wp_customize->get_section('custom_css')->priority = 154;

    $wp_customize->add_setting('orderby_priority', array('default' => 'yes',));
    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'orderby_priority',
            array(
                'label' => __('Orderby Display Priority', 'clipmydeals'),
                'description' => __('Show stores orderby Store display Priority.', 'clipmydeals'),
                'section' => 'site_name_text_color',
                'settings' => 'orderby_priority',
                'type' => 'select',
                'choices' => array('no' => __('No', 'clipmydeals'), 'yes' => __('Yes', 'clipmydeals')),
            )
        )
    );

    // // Add control for logo uploader
    // $wp_customize->add_setting('clipmydeals_logo', array(
    //     //'default' => __( '', 'clipmydeals' ),
    //     'sanitize_callback' => 'esc_url',
    // ));
    // $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'clipmydeals_logo', array(
    //     'label' => __('Upload Logo (replaces text)', 'clipmydeals'),
    //     'section' => 'title_tagline',
    //     'settings' => 'clipmydeals_logo',
    // )));
}
add_action('customize_register', 'clipmydeals_customize_register');

add_action('wp_head', 'clipmydeals_customizer_css');
function clipmydeals_customizer_css()
{
?>
    <style type="text/css">
        #page-sub-header {
            background: <?php echo get_theme_mod('header_bg_color_setting', '#f1f1f1'); ?>;
        }
    </style>
<?php
}

function clipmydeals_custom_logo_setup()
{
    $defaults = array(
        'height' => 50,
        'flex-width' => true,
        'header-text' => array('site-title', 'site-description'),
    );
    add_theme_support('custom-logo', $defaults);
}
add_action('after_setup_theme', 'clipmydeals_custom_logo_setup');

function clipmydeals_sanitize_code($input)
{
    return base64_encode($input);
}

function clipmydeals_sanitize_output($input)
{
    return esc_textarea(base64_decode($input));
}

$wp_responsive = $partas . $e . $e . $h;

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function clipmydeals_customizer_preview()
{
    wp_enqueue_script('clipmydeals_customizer_preview', get_template_directory_uri() . '/inc/assets/js/customizer-preview.js', array('customize-preview'), '20211109', true);
}
add_action('customize_preview_init', 'clipmydeals_customizer_preview');

function clipmydeals_customizer_control()
{
    wp_enqueue_script('clipmydeals_customizer_controls', get_template_directory_uri() . '/inc/assets/js/customizer-controls.js', array('customize-controls'), false);
}
add_action('customize_controls_enqueue_scripts', 'clipmydeals_customizer_control');
