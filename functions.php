<?php

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
    // Sidebar CSS and JS
    wp_enqueue_style('AJDWP-sidebar-css', get_stylesheet_directory_uri() . '/theme_addons/sidebar/sidebar.css', [], '1.0', 'all');
    wp_enqueue_script('AJDWP-sidebar-js', get_stylesheet_directory_uri() . '/theme_addons/sidebar/sidebar.js', array('jquery'), '1.0', true);

    // Woo Styles and Scripts
    $options = get_option('AJDWP_theme_options');
    if (!empty($options['woocommerce_theme_support'])) {
        wp_enqueue_style('AJDWP_woo_css', get_stylesheet_directory_uri() . '/theme_addons/woo/woo.css', [], '1.0', 'all');
        wp_enqueue_script('AJDWP-woo-js', get_stylesheet_directory_uri() . '/theme_addons/woo/woo.js', array('jquery'), '1.0', true);
    }

    // Core CSS and JS
    wp_enqueue_style('AJDWP_bootstrap_css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css');
    wp_enqueue_script('AJDWP_bootstrap_js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-form');

    // Quick Login Scripts
    wp_enqueue_style('quick-login-css', get_stylesheet_directory_uri() . '/theme_addons/quick_login_right_bottom/quick-login.css', array(), '1.0', 'all');
    wp_enqueue_script('quick-login-js', get_stylesheet_directory_uri() . '/theme_addons/quick_login_right_bottom/quick-login.js', array('jquery'), '1.0', true);
    wp_localize_script('quick-login-js', 'quick_login_ajax', array('ajax_url' => admin_url('admin-ajax.php')));

    // Like & Follow Scripts
    wp_enqueue_script('AJDWP_like_follow_ajax_js', get_stylesheet_directory_uri() . '/theme_addons/Like_follow/Like_Follow_Ajax.js', array('jquery'), '1.0', true);
    wp_localize_script('AJDWP_like_follow_ajax_js', 'like_follow_ajax', array('ajax_url' => admin_url('admin-ajax.php')));

    // Navbar Sryles and Scripts
    wp_enqueue_style('AJDWP-navbar-css', get_stylesheet_directory_uri() . '/theme_addons/navbar/navbar.css', [], '1.0', 'all');
    wp_enqueue_script('AJDWP-navbar-js', get_stylesheet_directory_uri() . '/theme_addons/navbar/navbar.js');

    // User Profile Styles and Scripts
    wp_enqueue_style('AJDWP-user-profile-css', get_stylesheet_directory_uri() . '/theme_addons/user_profile/user_profile.css', [], '1.0', 'all');
    wp_enqueue_script('AJDWP-user-profile-js', get_stylesheet_directory_uri() . '/theme_addons/user_profile/user_profile.js', array('jquery'), '', true);

    //fontawsome
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
// include dirname(__FILE__)."/theme_addons/user_profile/author_page_profile_edit.php";

//------------------  User Dashboard Frontend ------------------
include dirname(__FILE__) . "/theme_addons/user_dashboard_frontend/user_dashboard_create_pages.php";
include dirname(__FILE__) . "/theme_addons/user_dashboard_frontend/user_dashboard_float_button.php";

//------------------  quick login right bottom ------------------
include dirname(__FILE__) . "/theme_addons/quick_login_right_bottom/quick_login_right_bottom.php";

//------------------  Cookie & Policy ------------------
include dirname(__FILE__) . "/theme_addons/cookie_policy/cookie_policy_functions.php";

//--------------------------- Woocoomerce ---------------------------//
if (!empty($options['woocommerce_theme_support'])) {
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
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/Theme_sidebars.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/Theme_updates_from_the_GitHub_repo.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/user_avatar.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/View_counter.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/Yoast_seo_settings.php";
include dirname(__FILE__) . "/theme_addons/ajdwp_theme_settings/google_tag_manager.php";

//__________________________________________________________________________//

//                   Theme Settings Tab On Admin Side                   

//__________________________________________________________________________//


add_action('admin_menu', 'AJDWP_Theme_func');

function AJDWP_Theme_func()
{
    add_menu_page(
        'AJDWP_Theme_Options',
        'AJDWP Theme Settings',
        'manage_options',
        'AJDWP_Theme_Options',
        'AJDWP_Theme_init_func',
    );
}

function AJDWP_Theme_init_func()
{

    echo '<h3>Create Necessary Pages: </h3>';
    echo '</br>';
    //---------------------------------Create User Profile Pages----------------------------------------//

    $user_profile_page = get_page_by_path('user-profile');
    $pass_reset_page = get_page_by_path('password-reset-page');

    if ($user_profile_page && $pass_reset_page) {
        echo '<p>All needed pages for <b>User profile</b> are created and ready to use :)</p>';
    } else {
        echo '<p>Create <b>User Profile</b> Pages for login, Register and Password Recovery: </p>';
        echo '<a href="' . esc_url(admin_url('?user_profile_pages=true')) . '" class="AJDWP-Theme-Options-button">User Profile Pages</a>';
        echo '</br>';
    }

    //---------------------------------Create User Dashboard Pages----------------------------------------//

    $my_comments_page = get_page_by_path('my-comments');
    $my_posts_page = get_page_by_path('my-posts');
    $my_media_page = get_page_by_path('my-media');

    if ($my_comments_page && $my_posts_page && $my_media_page) {
        echo '<p>All needed pages for <b>User dashboard</b> are created and ready to use :)</p>';
    } else {
        echo '</br>';
        echo '<p>Create <b>User Dashboard</b> Pages in frontend: <p>';
        echo '<a href="' . esc_url(admin_url('?user_dash_pages=true')) . '" class="AJDWP-Theme-Options-button">User Dashboard Pages</a>';
        echo '</br></br>';
    }

    echo '<h3>Privacy Policy and Cookies: </h3>';
    echo '</br>';
    //------------------  Add Policy and Cookies files ------------------
    $privacy_notice_page = get_page_by_path('privacy-notice');

    if ($privacy_notice_page) {
        echo '<p>The page <b>Privacy Notice</b> is created and policy sample contents are added :)</p>';
    } else {
        echo '<p>Create <b>Privacy Notice</b> Page and add pre-written policies to it: </p>';
        echo '<a href="' . esc_url(admin_url('?privacy_notice_page=true')) . '" class="AJDWP-Theme-Options-button">Privacy Notice Page</a>';
        echo '</br>';
        echo '</br>';
    }

    include get_stylesheet_directory() . "/theme_addons/cookie_policy/cookie_policy_settings.php";

    //------------------  Display and handle the settings form ------------------ 

    echo '<form id="theme-settings-form" method="post" action="options.php">';
    settings_fields('AJDWP_theme_options_group');
    do_settings_sections('AJDWP_Theme_Options');
    submit_button();
    echo '</form>';
}
