<?php
//_____________________________________ functions.php _____________________________________//

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/* --------------------------------------------------------------------------
 * Paths
 * -------------------------------------------------------------------------- */
if (! defined('AJDWP_CHILD_DIR')) {
    define('AJDWP_CHILD_DIR', trailingslashit(get_stylesheet_directory()));
}
if (! defined('AJDWP_CHILD_URI')) {
    define('AJDWP_CHILD_URI', trailingslashit(get_stylesheet_directory_uri()));
}
if (! defined('AJDWP_ADDONS')) {
    define('AJDWP_ADDONS', AJDWP_CHILD_DIR . 'theme_addons/');
}
if (! defined('AJDWP_SETTINGS')) {
    define('AJDWP_SETTINGS', AJDWP_ADDONS . 'ajdwp_theme_settings/');
}

/* --------------------------------------------------------------------------
 * Parent + Child styles
 * -------------------------------------------------------------------------- */
function your_theme_enqueue_styles()
{
    $parent_style = 'parent-style';

    wp_enqueue_style(
        $parent_style,
        get_template_directory_uri() . '/style.css'
    );

    wp_enqueue_style(
        'child-style',
        AJDWP_CHILD_URI . 'style.css',
        array($parent_style),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'your_theme_enqueue_styles');

/* --------------------------------------------------------------------------
 * Scripts & Styles (front-end)
 * -------------------------------------------------------------------------- */
function load_css_js()
{
    // -------------- Core CSS and JS --------------
    wp_enqueue_style(
        'AJDWP_bootstrap_css',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css'
    );

    wp_enqueue_script(
        'AJDWP_bootstrap_js',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js',
        array('jquery'),
        null,
        true
    );

    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-form');

    // -------------- Detect current language --------------
    // (Polylang/WPML aware, falls back to WP locale)
    $current_lang = function_exists('pll_current_language')
        ? pll_current_language('slug')
        : (defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : determine_locale());

    // -------------- Sidebar CSS and JS --------------
    wp_enqueue_style('AJDWP-sidebar-css', AJDWP_CHILD_URI . 'theme_addons/sidebar/sidebar.css', [], '1.0', 'all');
    wp_enqueue_script('AJDWP-sidebar-js', AJDWP_CHILD_URI . 'theme_addons/sidebar/sidebar.js', array('jquery'), '1.0', true);

    // -------------- Woo Styles and Scripts --------------
    $options = get_option('AJDWP_theme_options');
    if (! empty($options['woocommerce_theme_support'])) {
        wp_enqueue_style('AJDWP_woo_css', AJDWP_CHILD_URI . 'theme_addons/woo/woo.css', [], '1.0', 'all');
        wp_enqueue_script('AJDWP-woo-js', AJDWP_CHILD_URI . 'theme_addons/woo/woo.js', array('jquery'), '1.0', true);
    }

    // -------------- Quick Login Scripts --------------
    wp_enqueue_style('quick-login-css', AJDWP_CHILD_URI . 'theme_addons/quick_login_right_bottom/quick-login.css', array(), '1.0', 'all');

    // Enqueue the quick login script with cache-busting
    $ql_handle  = 'quick-login-js';
    $ql_src     = AJDWP_CHILD_URI . 'theme_addons/quick_login_right_bottom/quick-login.js';
    $ql_path    = AJDWP_CHILD_DIR . 'theme_addons/quick_login_right_bottom/quick-login.js';
    $ql_version = file_exists($ql_path) ? filemtime($ql_path) : '1.0.0';

    wp_enqueue_script($ql_handle, $ql_src, ['jquery'], $ql_version, true);

    // Localize quick login (after enqueuing/registration)
    wp_localize_script($ql_handle, 'quick_login_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('custom_user_login_nonce'),
        'lang'     => $current_lang,
        'strings'  => [
            'empty'        => __('Please enter both username and password.', 'hello-elementor-child'),
            'unexpected'   => __('An unexpected error occurred.', 'hello-elementor-child'),
            'server_error' => __('Server error. Please try again.', 'hello-elementor-child'),
            'success'      => __('Login successful', 'hello-elementor-child'),
        ],
    ]);

    // -------------- Like & Follow Scripts --------------
    wp_enqueue_script('AJDWP_like_follow_ajax_js', AJDWP_CHILD_URI . 'theme_addons/Like_follow/Like_Follow_Ajax.js', array('jquery'), '1.0', true);
    wp_localize_script('AJDWP_like_follow_ajax_js', 'like_follow_ajax', array('ajax_url' => admin_url('admin-ajax.php')));

    // -------------- Navbar Styles and Scripts --------------
    wp_enqueue_style('AJDWP-navbar-css', AJDWP_CHILD_URI . 'theme_addons/navbar/navbar.css', [], '1.0', 'all');
    wp_enqueue_script('AJDWP-navbar-js', AJDWP_CHILD_URI . 'theme_addons/navbar/navbar.js', array(), null, true);

    // -------------- User Profile Styles and Scripts --------------
    wp_enqueue_style('AJDWP-user-profile-css', AJDWP_CHILD_URI . 'theme_addons/user_profile/user_profile.css', [], '1.0', 'all');

    // Cache-busting version based on file mtime
    $up_js_path = AJDWP_CHILD_DIR . 'theme_addons/user_profile/user_profile.js';
    $up_js_ver  = file_exists($up_js_path) ? filemtime($up_js_path) : '1.0.0';

    // Register -> localize -> enqueue (correct order)
    wp_register_script(
        'AJDWP-user-profile-js',
        AJDWP_CHILD_URI . 'theme_addons/user_profile/user_profile.js',
        ['jquery'],
        $up_js_ver,
        true
    );

    wp_localize_script('AJDWP-user-profile-js', 'AJDWP', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'home_url' => home_url('/'),
        'lang'     => $current_lang,
        'is_admin' => current_user_can('administrator'),
        'assets'   => [
            'success_gif' => AJDWP_CHILD_URI . 'camel-gif-animation-success-message.gif',
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

    wp_enqueue_script('AJDWP-user-profile-js');

    // -------------- Font Awesome --------------
    wp_enqueue_script('AJDWP_fontawsome-arash11javadi', 'https://kit.fontawesome.com/162c2377c3.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'load_css_js');

/* --------------------------------------------------------------------------
 * PHP includes
 * -------------------------------------------------------------------------- */

// Navigation bar
require_once AJDWP_ADDONS . 'navbar/navbar.php';

// Like/Follow
require_once AJDWP_ADDONS . 'Like_follow/likefollow.php';
require_once AJDWP_ADDONS . 'Like_follow/Like_Follow_Ajax.php';

// User Social Media
require_once AJDWP_ADDONS . 'User_Social_Media/User_Social_Media.php';

// User Registration
require_once AJDWP_ADDONS . 'user_profile/user_profile.php';
require_once AJDWP_ADDONS . 'user_profile/user_profile_functions.php';

// User Dashboard Frontend
require_once AJDWP_ADDONS . 'user_dashboard_frontend/user_dashboard_create_pages.php';
require_once AJDWP_ADDONS . 'user_dashboard_frontend/user_dashboard_float_button.php';

// Quick login right bottom
require_once AJDWP_ADDONS . 'quick_login_right_bottom/quick_login_right_bottom.php';

// Cookie & Policy
require_once AJDWP_ADDONS . 'cookie_policy/cookie_policy_functions.php';

// WooCommerce (conditional)
$ajdwp_options = get_option('AJDWP_theme_options');
if (! empty($ajdwp_options['woocommerce_theme_support'])) {
    require_once AJDWP_ADDONS . 'woo/woo.php';
}

// Theme Built-in Functions and Plugins
require_once AJDWP_SETTINGS . '1_Register_settings_and_add_settings_fields.php';
require_once AJDWP_SETTINGS . 'add_meta_description_field_to_pages_and_posts.php';
require_once AJDWP_SETTINGS . 'add_meta_keyword_field_to_pages_and_posts.php';
require_once AJDWP_SETTINGS . 'add_post_and_media_capability_to_subscribers_and_contributors.php';
require_once AJDWP_SETTINGS . 'add_woocommerce_theme_support.php';
require_once AJDWP_SETTINGS . 'create_Like_Follow_table_on_theme_switch.php';
require_once AJDWP_SETTINGS . 'custom_menu_link.php';
require_once AJDWP_SETTINGS . 'Excerpt_length.php';
require_once AJDWP_SETTINGS . 'hide_admin_bar_from_users.php';
require_once AJDWP_SETTINGS . 'hide_admin_notices.php';
require_once AJDWP_SETTINGS . 'limit_disk_usage_and_media_upload_of_the_users.php';
require_once AJDWP_SETTINGS . 'limit_users_to_see_comments_on_their_own_posts.php';
require_once AJDWP_SETTINGS . 'limit_users_to_see_only_their_own_medias.php';
require_once AJDWP_SETTINGS . 'limit_users_to_see_only_their_own_posts.php';
require_once AJDWP_SETTINGS . 'load_media_library_on_frontend.php';
require_once AJDWP_SETTINGS . 'post_per_page_on_the_authors_page.php';
require_once AJDWP_SETTINGS . 'redirect_login_logout_page.php';
require_once AJDWP_SETTINGS . 'restrict_user_access_to_admin_side.php';
require_once AJDWP_SETTINGS . 'stop_wordpress_to_make_diffrent_size_of_photos.php';
require_once AJDWP_SETTINGS . 'theme_sidebars.php';
require_once AJDWP_SETTINGS . 'theme_updates_from_the_github_repo.php';
require_once AJDWP_SETTINGS . 'user_avatar.php';
require_once AJDWP_SETTINGS . 'View_counter.php';
require_once AJDWP_SETTINGS . 'Yoast_seo_settings.php';
require_once AJDWP_SETTINGS . 'gtm.php';
require_once AJDWP_SETTINGS . 'nav_link_name_for_not_logged_in_users.php';
require_once AJDWP_SETTINGS . 'auth_ajax_cache_guard.php';

/* --------------------------------------------------------------------------
 * Translations
 * -------------------------------------------------------------------------- */
add_action('after_setup_theme', function () {
    load_child_theme_textdomain(
        'hello-elementor-child',
        AJDWP_CHILD_DIR . 'languages'
    );
});