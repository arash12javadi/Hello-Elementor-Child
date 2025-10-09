<?php
//_____________________________________ functions.php _____________________________________//

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

function your_theme_enqueue_styles()
{

    $parent_style = 'parent-style';

    wp_enqueue_style(
        $parent_style,
        get_template_directory_uri() . '/style.css'
    );

    wp_enqueue_style(
        'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array($parent_style),
        wp_get_theme()->get('Version')
    );
}

add_action('wp_enqueue_scripts', 'your_theme_enqueue_styles');

//__________________________________________________________________________//
//			            ADD JAVASCRIPTS AND CSS
//__________________________________________________________________________//

function load_css_js()
{
    // -------------- Core CSS and JS --------------
    wp_enqueue_style('AJDWP_bootstrap_css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css');
    wp_enqueue_script('AJDWP_bootstrap_js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-form');

    // -------------- Detect current language --------------
    // (Polylang/WPML aware, falls back to WP locale)
    $current_lang = function_exists('pll_current_language')
        ? pll_current_language('slug')
        : (defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : determine_locale());

    wp_localize_script('AJDWP-user-profile-js', 'AJDWP', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'home_url' => home_url('/'),
        'lang'     => $current_lang,
        'is_admin' => current_user_can('administrator'), // <—— add this

        'assets'   => [
            'success_gif' => get_stylesheet_directory_uri() . '/camel-gif-animation-success-message.gif',
        ],
        'i18n'     => [
            // Registration
            'enter_username' => __('Please enter a username', 'hello-elementor-child'),
            'enter_email'    => __('Please enter an email address', 'hello-elementor-child'),
            'invalid_email'  => __('Invalid email', 'hello-elementor-child'),
            'enter_password' => __('Please enter a password', 'hello-elementor-child'),
            'mismatch'       => __('Passwords do not match', 'hello-elementor-child'),
            'reg_success'    => __('Registration successful.', 'hello-elementor-child'),
            'reg_failed'     => __('Registration failed.', 'hello-elementor-child'),

            // Forgot password
            'reset_sent'     => __('Password reset link sent. Check your email.', 'hello-elementor-child'),
            'reset_generic'  => __('Something went wrong. Please try again.', 'hello-elementor-child'),

            // Delete account (new)
            'admin_blocked'  => __('Admins cannot delete their account from the front-end.', 'hello-elementor-child'),
            'tick_checkbox'  => __('Please tick the checkbox to continue.', 'hello-elementor-child'),
            'generic_error'  => __('Error', 'hello-elementor-child'),

            // Common
            'server_error'   => __('Server error. Please try again.', 'hello-elementor-child'),
            'go_home'        => __('Go to Home', 'hello-elementor-child'),

            // Media
            'media_title'    => __('Select or Upload Media', 'hello-elementor-child'),
            'media_button'   => __('Use this media', 'hello-elementor-child'),
        ],
    ]);


    // -------------- Sidebar CSS and JS --------------
    wp_enqueue_style('AJDWP-sidebar-css', get_stylesheet_directory_uri() . '/theme_addons/sidebar/sidebar.css', [], '1.0', 'all');
    wp_enqueue_script('AJDWP-sidebar-js', get_stylesheet_directory_uri() . '/theme_addons/sidebar/sidebar.js', array('jquery'), '1.0', true);

    // -------------- Woo Styles and Scripts --------------
    $options = get_option('AJDWP_theme_options');
    if (!empty($options['woocommerce_theme_support'])) {
        wp_enqueue_style('AJDWP_woo_css', get_stylesheet_directory_uri() . '/theme_addons/woo/woo.css', [], '1.0', 'all');
        wp_enqueue_script('AJDWP-woo-js', get_stylesheet_directory_uri() . '/theme_addons/woo/woo.js', array('jquery'), '1.0', true);
    }

    // -------------- Quick Login Scripts --------------
    wp_enqueue_style('quick-login-css', get_stylesheet_directory_uri() . '/theme_addons/quick_login_right_bottom/quick-login.css', array(), '1.0', 'all');

    // Enqueue the quick login script with cache-busting
    $handle   = 'quick-login-js';
    $src      = get_stylesheet_directory_uri() . '/theme_addons/quick_login_right_bottom/quick-login.js';
    $path     = get_stylesheet_directory() . '/theme_addons/quick_login_right_bottom/quick-login.js';
    $version  = file_exists($path) ? filemtime($path) : '1.0.0';

    wp_enqueue_script($handle, $src, ['jquery'], $version, true);

    // Detect current language (Polylang/WPML aware; fallback to WP locale)
    $current_lang = function_exists('pll_current_language')
        ? pll_current_language('slug')
        : (defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : determine_locale());

    // Localize
    wp_localize_script($handle, 'quick_login_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('custom_user_login_nonce'),
        'lang'     => $current_lang,
        'strings'  => [
            'empty'       => __('Please enter both username and password.', 'hello-elementor-child'),
            'unexpected'  => __('An unexpected error occurred.', 'hello-elementor-child'),
            'server_error' => __('Server error. Please try again.', 'hello-elementor-child'),
            'success'     => __('Login successful', 'hello-elementor-child'),
        ],
    ]);

    // -------------- Like & Follow Scripts --------------
    wp_enqueue_script('AJDWP_like_follow_ajax_js', get_stylesheet_directory_uri() . '/theme_addons/Like_follow/Like_Follow_Ajax.js', array('jquery'), '1.0', true);
    wp_localize_script('AJDWP_like_follow_ajax_js', 'like_follow_ajax', array('ajax_url' => admin_url('admin-ajax.php')));

    // -------------- Navbar Sryles and Scripts --------------
    wp_enqueue_style('AJDWP-navbar-css', get_stylesheet_directory_uri() . '/theme_addons/navbar/navbar.css', [], '1.0', 'all');
    wp_enqueue_script('AJDWP-navbar-js', get_stylesheet_directory_uri() . '/theme_addons/navbar/navbar.js');

    // -------------- User Profile Styles and Scripts --------------
    wp_enqueue_style('AJDWP-user-profile-css', get_stylesheet_directory_uri() . '/theme_addons/user_profile/user_profile.css', [], '1.0', 'all');

    // Cache-busting version based on file mtime
    $ajdwp_user_profile_js_path = get_stylesheet_directory() . '/theme_addons/user_profile/user_profile.js';
    $ajdwp_user_profile_js_ver  = file_exists($ajdwp_user_profile_js_path) ? filemtime($ajdwp_user_profile_js_path) : '1.0.0';

    wp_enqueue_script(
        'AJDWP-user-profile-js',
        get_stylesheet_directory_uri() . '/theme_addons/user_profile/user_profile.js',
        ['jquery'],
        $ajdwp_user_profile_js_ver,
        true
    );

    //-------------- Fontawsome --------------
    wp_enqueue_script('AJDWP_fontawsome-arash11javadi', 'https://kit.fontawesome.com/162c2377c3.js');
}
add_action('wp_enqueue_scripts', 'load_css_js');


//__________________________________________________________________________//
//				                ADD PHP files
//__________________________________________________________________________//

//------------------  Navigation bar ------------------
include dirname(__FILE__) . "/theme_addons/navbar/navbar.php";

//------------------  AJDWP_like_button_func ------------------
include dirname(__FILE__) . "/theme_addons/Like_follow/likefollow.php";

//------------------  User_Social_Media ------------------
include dirname(__FILE__) . "/theme_addons/User_Social_Media/User_Social_Media.php";

//------------------  Like_Follow_Ajax ------------------
include dirname(__FILE__) . "/theme_addons/Like_follow/Like_Follow_Ajax.php";

//------------------  User Registration ------------------
include dirname(__FILE__) . "/theme_addons/user_profile/user_profile.php";
include dirname(__FILE__) . "/theme_addons/user_profile/user_profile_functions.php";

//------------------  User Dashboard Frontend ------------------
include dirname(__FILE__) . "/theme_addons/user_dashboard_frontend/user_dashboard_create_pages.php";
include dirname(__FILE__) . "/theme_addons/user_dashboard_frontend/user_dashboard_float_button.php";

//------------------  quick login right bottom ------------------
include dirname(__FILE__) . "/theme_addons/quick_login_right_bottom/quick_login_right_bottom.php";

//------------------  Cookie & Policy ------------------
include dirname(__FILE__) . "/theme_addons/cookie_policy/cookie_policy_functions.php";

//--------------------------- WooCommerce ---------------------------//
$ajdwp_options = get_option('AJDWP_theme_options');
if (!empty($ajdwp_options['woocommerce_theme_support'])) {
    include dirname(__FILE__) . "/theme_addons/woo/woo.php";
}

//--------------------------- Theme Built-in Functions and Plugins ---------------------------//
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/1_Register_settings_and_add_settings_fields.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/add_meta_description_field_to_pages_and_posts.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/add_meta_keyword_field_to_pages_and_posts.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/add_post_and_media_capability_to_subscribers_and_contributors.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/add_woocommerce_theme_support.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/create_Like_Follow_table_on_theme_switch.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/custom_menu_link.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/Excerpt_length.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/hide_admin_bar_from_users.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/hide_admin_notices.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/limit_disk_usage_and_media_upload_of_the_users.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/limit_users_to_see_comments_on_their_own_posts.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/limit_users_to_see_only_their_own_medias.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/limit_users_to_see_only_their_own_posts.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/load_media_library_on_frontend.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/post_per_page_on_the_authors_page.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/redirect_login_logout_page.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/restrict_user_access_to_admin_side.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/stop_wordpress_to_make_diffrent_size_of_photos.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/theme_sidebars.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/theme_updates_from_the_github_repo.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/user_avatar.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/View_counter.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/Yoast_seo_settings.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/google_tag_manager.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/nav_link_name_for_not_logged_in_users.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/auth_ajax_cache_guard.php";

//--------------------------- 
//--------- Load translations text domain 
//---------------------------  

add_action('after_setup_theme', function () {
    load_child_theme_textdomain(
        'hello-elementor-child',
        get_stylesheet_directory() . '/languages'
    );
});




// Register shortcode [footag]
add_shortcode('footag', 'wpdocs_footag_func');

function wpdocs_footag_func()
{
    // Retrieve theme options
    $options = get_option('AJDWP_theme_options');

    // Use output buffering instead of direct echoing
    ob_start();

    echo '<pre>';
    print_r(esc_html(print_r($options, true)));
    echo '</pre>';

    // Return buffered output
    return ob_get_clean();
}
