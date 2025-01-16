<?php

/**
 * ClipMyDeals functions and definitions
 * @package ClipMyDeals
 */

if (!function_exists('clipmydeals_setup')) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function clipmydeals_setup() {

        load_theme_textdomain('clipmydeals', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
        add_theme_support('title-tag');

        /*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
        add_theme_support('post-thumbnails');

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(array(
            'primary' => esc_html__('Primary', 'clipmydeals'),
        ));

        /*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
        add_theme_support('html5', array(
            'comment-form',
            'comment-list',
            'caption',
        ));

        // Set up the WordPress core custom background feature.
        add_theme_support('custom-background');

        // Add theme support for selective refresh for widgets.
        add_theme_support('customize-selective-refresh-widgets');
    }
endif;
add_action('after_setup_theme', 'clipmydeals_setup');


/**
 * Add Welcome message to dashboard
 */
function clipmydeals_reminder() {
    $theme_page_url = 'https://clipmydeals.com';

    if (!get_option('triggered_welcomet')) {
        /* translators: 1: documentation URL, 2: Support forums URL */
        $message = sprintf(
            __('<h2 style="color:#ffffff;">IMPORTANT</h2><p>Please follow the <a style="color: #fff; text-decoration:underline;" href="%1$s" target="_blank">Theme Activation steps</a> to enable all ClipMyDeals features.</p><p>We also recommend that you through complete <a style="color: #fff; text-decoration:underline;" href="%2$s" target="_blank">ClipMyDeals Documentation</a> for in-depth understanding of theme features & options.</p>', 'clipmydeals'),
            esc_url("https://clipmydeals.com/support/kb/faq.php?id=2"),
            esc_url("https://clipmydeals.com/support/kb/")
        );

        printf(
            '<div class="notice is-dismissible" style="background-color: #43569B; color: #fff; border-left: none;">
                        <p>%1$s</p>
                    </div>',
            $message
        );
        add_option('triggered_welcomet', '1', '', 'yes');
    }
}
add_action('admin_notices', 'clipmydeals_reminder');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function clipmydeals_content_width() {
    $GLOBALS['content_width'] = apply_filters('clipmydeals_content_width', 1170);
}
add_action('after_setup_theme', 'clipmydeals_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function clipmydeals_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'clipmydeals'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here.', 'clipmydeals'),
        'before_widget' => '<div id="%1$s" class="widget %2$s card rounded-4 border-0 shadow-sm overflow-hidden my-2">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="card-header widget-title"><h5 class="card-title mb-0">',
        'after_title'   => '</h5></div>',
    ));
    register_sidebar(array(
        'name'          => esc_html__('Footer 1', 'clipmydeals'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Add widgets here.', 'clipmydeals'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    register_sidebar(array(
        'name'          => esc_html__('Footer 2', 'clipmydeals'),
        'id'            => 'footer-2',
        'description'   => esc_html__('Add widgets here.', 'clipmydeals'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    register_sidebar(array(
        'name'          => esc_html__('Footer 3', 'clipmydeals'),
        'id'            => 'footer-3',
        'description'   => esc_html__('Add widgets here.', 'clipmydeals'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'clipmydeals_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function clipmydeals_scripts() {
    // load bootstrap css
    wp_enqueue_style('clipmydeals-bootstrap-css', get_template_directory_uri() . '/inc/assets/css/bootstrap.min.css');
    // fontawesome cdn
    wp_enqueue_style('wp-bootstrap-pro-fontawesome-cdn', '//use.fontawesome.com/releases/v5.0.12/css/all.css');
    // load ClipMyDeals styles
    wp_enqueue_style('clipmydeals-style', get_stylesheet_uri());
    if (get_theme_mod('theme_option_setting') && get_theme_mod('theme_option_setting') !== 'default') {
        wp_enqueue_style('clipmydeals-' . get_theme_mod('theme_option_setting'), get_template_directory_uri() . '/inc/assets/css/presets/theme-option/' . get_theme_mod('theme_option_setting') . '.css', false, '');
    }
    if (get_theme_mod('preset_style_setting') === 'poppins-lora') {
        wp_enqueue_style('clipmydeals-poppins-lora-font', '//fonts.googleapis.com/css?family=Lora:400,400i,700,700i|Poppins:300,400,500,600,700');
    }
    if (get_theme_mod('preset_style_setting') === 'montserrat-merriweather') {
        wp_enqueue_style('clipmydeals-montserrat-merriweather-font', '//fonts.googleapis.com/css?family=Merriweather:300,400,400i,700,900|Montserrat:300,400,400i,500,700,800');
    }
    if (get_theme_mod('preset_style_setting') === 'poppins-poppins') {
        wp_enqueue_style('clipmydeals-poppins-font', '//fonts.googleapis.com/css?family=Poppins:300,400,500,600,700');
    }
    if (get_theme_mod('preset_style_setting') === 'roboto-roboto') {
        wp_enqueue_style('clipmydeals-roboto-font', '//fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900,900i');
    }
    if (get_theme_mod('preset_style_setting') === 'arbutusslab-opensans') {
        wp_enqueue_style('clipmydeals-arbutusslab-opensans-font', '//fonts.googleapis.com/css?family=Arbutus+Slab|Open+Sans:300,300i,400,400i,600,600i,700,800');
    }
    if (get_theme_mod('preset_style_setting') === 'oswald-muli') {
        wp_enqueue_style('clipmydeals-oswald-muli-font', '//fonts.googleapis.com/css?family=Muli:300,400,600,700,800|Oswald:300,400,500,600,700');
    }
    if (get_theme_mod('preset_style_setting') === 'montserrat-opensans') {
        wp_enqueue_style('clipmydeals-montserrat-opensans-font', '//fonts.googleapis.com/css?family=Montserrat|Open+Sans:300,300i,400,400i,600,600i,700,800');
    }
    if (get_theme_mod('preset_style_setting') === 'robotoslab-roboto') {
        wp_enqueue_style('clipmydeals-robotoslab-roboto', '//fonts.googleapis.com/css?family=Roboto+Slab:100,300,400,700|Roboto:300,300i,400,400i,500,700,700i');
    }
    if (get_theme_mod('preset_style_setting') && get_theme_mod('preset_style_setting') !== 'default') {
        wp_enqueue_style('clipmydeals-' . get_theme_mod('preset_style_setting'), get_template_directory_uri() . '/inc/assets/css/presets/typography/' . get_theme_mod('preset_style_setting') . '.css', false, '');
    }

    wp_enqueue_script('jquery');
    // load bootstrap js
    wp_enqueue_script('clipmydeals-popper', get_template_directory_uri() . '/inc/assets/js/popper.min.js', array(), '', true);
    wp_enqueue_script('clipmydeals-bootstrapjs', get_template_directory_uri() . '/inc/assets/js/bootstrap.min.js', array(), '', true);
    wp_enqueue_script('clipmydeals-themejs', get_template_directory_uri() . '/inc/assets/js/theme-script.min.js', array(), '', true);
    wp_enqueue_script('clipmydeals-skip-link-focus-fix', get_template_directory_uri() . '/inc/assets/js/skip-link-focus-fix.min.js', array(), '20151215', true);

    // Load Carousel css
    wp_enqueue_style('carousel-css', get_template_directory_uri() . '/inc/assets/css/carousel.css');

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'clipmydeals_scripts');


function clipmydeals_password_form() {
    global $post;
    $label = 'pwbox-' . (empty($post->ID) ? rand() : $post->ID);
    $o = '<form action="' . esc_url(site_url('wp-login.php?action=postpass', 'login_post')) . '" method="post">
    <div class="d-block mb-3">' . __("To view this protected post, enter the password below:", "clipmydeals") . '</div>
    <div class="form-group form-inline"><label for="' . $label . '" class="me-2">' . __("Password:", "clipmydeals") . ' </label><input name="post_password" id="' . $label . '" type="password" size="20" maxlength="20" class="form-control me-2" /> <input type="submit" name="Submit" value="' . esc_attr__("Submit", "clipmydeals") . '" class="btn btn-primary"/></div>
    </form>';
    return $o;
}
add_filter('the_password_form', 'clipmydeals_password_form');


/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Functional Modifications
 */
require get_template_directory() . '/inc/cmd_functions.php';
require get_template_directory() . '/inc/cmd_tools.php';
require get_template_directory() . '/inc/cmd_widgets.php';
require get_template_directory() . '/inc/cmd_recommend.php';
require get_template_directory() . '/inc/cmd_notification_logs.php';
require get_template_directory() . '/inc/cmd_subscriptions.php';

/**
 * Additional Features
 */
if (!in_array('linkmydeals/linkmydeals.php', get_option('active_plugins'))) require get_template_directory() . '/inc/linkmydeals.php';
require get_template_directory() . '/inc/update.php';
require get_template_directory() . '/inc/demo_import.php';
require get_template_directory() . '/inc/quick_setup.php';
require get_template_directory() . '/vendor/autoload.php';

/**
 * Load plugin compatibility file.
 */
require get_template_directory() . '/inc/plugin-compatibility/plugin-compatibility.php';

/**
 * Load custom WordPress nav walker.
 */
if (!class_exists('wp_bootstrap_navwalker')) {
    require_once(get_template_directory() . '/inc/wp_bootstrap_navwalker.php');
}
