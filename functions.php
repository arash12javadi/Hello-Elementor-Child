<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

function your_theme_enqueue_styles() {

    $parent_style = 'parent-style';

    wp_enqueue_style( $parent_style, 
    get_template_directory_uri() . '/style.css'); 

    wp_enqueue_style( 'child-style', 
    get_stylesheet_directory_uri() . '/style.css', 
    array($parent_style), 
    wp_get_theme()->get('Version') 
    );
}

add_action('wp_enqueue_scripts', 'your_theme_enqueue_styles');

//__________________________________________________________________________//
//			Theme Update From Github Repo
//__________________________________________________________________________//

// Automatic theme updates from the GitHub repository
add_filter('pre_set_site_transient_update_themes', 'automatic_GitHub_updates', 100, 1);
function automatic_GitHub_updates($data) {
    $theme      = get_stylesheet(); // Folder name of the current theme
    $current    = wp_get_theme()->get('Version'); // Get the version of the current theme
    $user       = 'arash12javadi'; // The GitHub username hosting the repository
    $repo       = 'Hello-Elementor-Child'; // Repository name as it appears in the URL
    $file       = @json_decode(@file_get_contents('https://api.github.com/repos/'.$user.'/'.$repo.'/releases/latest', false, stream_context_create(['http' => ['header' => "User-Agent: ".$user."\r\n"]])));
    $update     = filter_var($file->tag_name, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    // Only return a response if the new version number is higher than the current version
    if (version_compare($update, $current, '>')){
        $data->response[$theme] = array(
	'theme'       => $theme,
	'new_version' => $update,
	'url'         => 'https://github.com/'.$user.'/'.$repo,
	// 'package'     => $file->assets[0]->browser_download_url,
	// 'package'     => $file->zipball_url,
	'package'     => 'https://codeload.github.com/arash12javadi/Hello-Elementor-Child-Theme/zip/refs/heads/Theme',
        );
    }
    return $data;
}
    
//__________________________________________________________________________//
//			    ADD JAVASCRIPTS AND CSS
//__________________________________________________________________________//
    
    
function load_css_js(){

    wp_enqueue_style('AJDWP_css_1', get_stylesheet_directory_uri() .'/style.css', '', 1, 'all');
    wp_enqueue_style( 'AJDWP_bootstrap_css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css' );    
    wp_enqueue_script( 'AJDWP_bootstrap_js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js' );    
    wp_enqueue_script( 'jquery');
    
    // wp_enqueue_script('AJDWP_navbar_js', get_template_directory_uri() . '/theme_addons/navbar/navbar.js');
    wp_enqueue_script('AJDWP_navbar_js', get_stylesheet_directory_uri() . '/theme_addons/navbar/navbar.js');
    //  wp_enqueue_script('AJDWP_like_folow_ajax_jsssss', get_stylesheet_directory_uri() . '/theme_addons/like_follow/Like_Follow_Ajax.js');
    // wp_enqueue_script('AJDWP_like_folow_ajax_js', get_stylesheet_directory_uri() . '/theme_addons/like_follow/Like_Follow_Ajax.js', array( 'jquery' ), '', true);

    wp_enqueue_script('AJDWP_user_profile', get_stylesheet_directory_uri() . '/theme_addons/user_profile/user_profile.js', array( 'jquery' ), '', true);
    wp_enqueue_script( 'AJDWP_jquery-3.6.0', 'https://code.jquery.com/jquery-3.6.0.min.js' );
    wp_enqueue_script( 'AJDWP_bootstrap-js-4.3.1', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js' );
    wp_enqueue_script( 'AJDWP_bootstrap-bundle-4.3.1', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js' );
    wp_enqueue_script( 'AJDWP_fontawsome-arash11javadi', 'https://kit.fontawesome.com/162c2377c3.js' );

    wp_enqueue_script('jquery-form');

    
}
add_action( 'wp_enqueue_scripts', 'load_css_js' );

        


//__________________________________________________________________________//
//				ADD PHP files
//__________________________________________________________________________//


//------------------  Navigation bar ------------------
include dirname(__FILE__)."/theme_addons/navbar/navbar.php";


//------------------  AJDWP_like_button_func ------------------
include dirname(__FILE__)."/theme_addons/Like_follow/likefollow.php";


//------------------  User_Social_Media ------------------
include dirname(__FILE__)."/theme_addons/User_Social_Media/User_Social_Media.php";


//------------------  Like_Follow_Ajax ------------------
include dirname(__FILE__)."/theme_addons/Like_follow/Like_Follow_Ajax.php";

//------------------  User Registration ------------------
include dirname(__FILE__)."/theme_addons/user_profile/user_profile.php";
include dirname(__FILE__)."/theme_addons/user_profile/user_profile_functions.php";
// include dirname(__FILE__)."/theme_addons/user_profile/author_page_profile_edit.php";

//------------------  User Dashboard Frontend ------------------
include dirname(__FILE__)."/theme_addons/user_dashboard_frontend/user_dashboard_create_pages.php";
include dirname(__FILE__)."/theme_addons/user_dashboard_frontend/user_dashboard_float_button.php";

//------------------  quick login right bottom ------------------
include dirname(__FILE__)."/theme_addons/quick_login_right_bottom/quick_login_right_bottom.php";


//__________________________________________________________________________//

//                   Theme Menu On Dashboard Page                   

//__________________________________________________________________________//


add_action('admin_menu', 'AJDWP_Theme_func');

function AJDWP_Theme_func(){
    add_menu_page( 
        'AJDWP_Theme_Options',
        'AJDWP_Theme_Options', 
        'manage_options', 
        'AJDWP_Theme_Options', 
        'AJDWP_Theme_init_func' ,
    );

}


//__________________________________________________________________________//

//                       CREATE AND UPDATE DATABASE 

//__________________________________________________________________________//


function AJDWP_Theme_init_func(){

    global $wpdb;
    $table_name = $wpdb->prefix . "ajdwp_like_follow";

    if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE '$table_name'", $table_name ) ) === $table_name ) {
        
        ?>
            <p>The table has been created and already exists :)</p>
        <?php

    }else{

        ?>
        <p>Easily click on the button below to create a table in your database : </p>
        <?php

        // include get_template_directory().'/theme_addons/Like_follow/Dashmenu_create_DB_with_button.php';
        include 'theme_addons/Like_follow/Dashmenu_create_DB_with_button.php';

    }
    
    //---------------------------------Create User Profile Pages----------------------------------------//

    $user_profile_page = get_page_by_path('user-profile');
    $pass_reset_page = get_page_by_path('password-reset-page');

    if ($user_profile_page && $pass_reset_page) {
        echo '<p>All needed pages for <b>User profile</b> are created and ready to use :)</p>';
    } else {
        echo '<a href="' . esc_url(admin_url('?user_profile_pages=true')) . '" class="button">User Profile Pages</a>';
    }

    //---------------------------------Create User Dashboard Pages----------------------------------------//

    $my_comments_page = get_page_by_path('my-comments');
    $my_posts_page = get_page_by_path('my-posts');
    $my_media_page = get_page_by_path('my-media');

    if ($my_comments_page && $my_posts_page && $my_media_page) {
        echo '<p>All needed pages for <b>User dashboard</b> are created and ready to use :)</p>';
    } else {
        echo '<a href="' . esc_url(admin_url('?user_dash_pages=true')) . '" class="button">User Dashboard Pages</a>';
    }

    
}


//__________________________________________________________________________//
//							Add theme Sidebars
//__________________________________________________________________________//

register_sidebar( array(
    'name'          => __( 'AJDWP Page Sidebar', 'AJDWP_theme' ),
    'id'            => 'AJDWP-page-sidebar',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>',
) );


//-------------------------------------------------------------------------//


register_sidebar( array(
    'name'          => __( 'AJDWP Blog Sidebar', 'AJDWP_theme' ),
    'id'            => 'AJDWP-blog-sidebar',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>',
) );


//-------------------------------------------------------------------------//


register_sidebar( array(
    'name'          => __( 'AJDWP Search Sidebar', 'AJDWP_theme' ),
    'id'            => 'AJDWP-search-sidebar',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>',
) );


//-------------------------------------------------------------------------//


register_sidebar( array(
    'name'          => __( 'AJDWP Shop Sidebar', 'AJDWP_theme' ),
    'id'            => 'AJDWP-Shop-sidebar',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>',
) );


//-------------------------------------------------------------------------//


register_sidebar( array(
    'name'          => __( 'AJDWP Header Sidebar', 'AJDWP_theme' ),
    'id'            => 'AJDWP-header-sidebar',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>',
) );


//------------------------------------- F O O T E R ------------------------------------//


register_sidebar( array(
    'name'          => __( 'AJDWP Footer widget 1', 'AJDWP_theme' ),
    'id'            => 'AJDWP-footer-widget-1',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>',
) );



//__________________________________________________________________________//

//					THEME BUILT-IN PLUGINS (USEFUL FUNCTIONS)

//__________________________________________________________________________//

//------------------------------------- Yoast Seo Settings ------------------------------------//

//---------- Removes the Yoast Metabox for Roles other then Admins
//---------- Returns true if user has specific role
function check_user_role( $role, $user_id = null ) {
    if ( is_numeric( $user_id ) )
    $user = get_userdata( $user_id );
    else
    $user = wp_get_current_user();
    if ( empty( $user ) )
    return false;
    return in_array( $role, (array) $user->roles );
}

//---------- Disable WordPress SEO meta box for all roles other than administrator and seo
function wpse_init(){
if( !( check_user_role( 'seo' ) || check_user_role( 'administrator' )) ) {
        // Remove page analysis columns from post lists, also SEO status on post editor
        add_filter( 'wpseo_use_page_analysis', '__return_false' );
        // Remove Yoast meta boxes
        add_action( 'add_meta_boxes', 'disable_seo_metabox', 100000 );
    }
}
add_action('init', 'wpse_init');

function disable_seo_metabox() {
    remove_meta_box( 'wpseo_meta', 'post', 'normal' );
    remove_meta_box( 'wpseo_meta', 'page', 'normal' );
}

//----------Remove Yoast SEO Columns
add_filter( 'manage_edit-post_columns', 'yoast_seo_admin_remove_columns', 10, 1 );
add_filter( 'manage_edit-page_columns', 'yoast_seo_admin_remove_columns', 10, 1 );

function yoast_seo_admin_remove_columns( $columns ) {
    unset($columns['wpseo-score']);
    unset($columns['wpseo-score-readability']);
    unset($columns['wpseo-title']);
    unset($columns['wpseo-metadesc']);
    unset($columns['wpseo-focuskw']);
    unset($columns['wpseo-links']);
    unset($columns['wpseo-linked']);
    return $columns;
}

//------------------------------------- Redirect users to their author page -> make a link in menu with this url `http://User_Author_page` ------------------------------------//
function custom_menu_link_url() {
    $current_user = wp_get_current_user();
    $author_url = get_author_posts_url($current_user->ID);

    // Replace the menu item URL with the dynamically generated author URL
    return $author_url;
}

// Filter the menu item URL
add_filter('wp_nav_menu_objects', 'custom_menu_link');
function custom_menu_link($items) {
    foreach ($items as &$item) {
        if ($item->url == 'http://User_Author_page') {
            $item->url = custom_menu_link_url();
        }
    }

    return $items;
}

//------------------------------------- Change User avatar for Comments and Posts by Changing `custom_avatar_url` ------------------------------------//
function custom_avatar_url($avatar, $id_or_email, $size, $default, $alt) {
    // Check if it's a comment
    if (is_object($id_or_email) && isset($id_or_email->comment_ID)) {
        $user_id = $id_or_email->user_id;

        // Check if the user has a custom avatar URL
        $custom_avatar_url = get_user_meta($user_id, 'custom_avatar_url', true);

        if (!empty($custom_avatar_url)) {
            // Use custom avatar URL with a fixed size of 40 pixels
            $avatar = '<img alt="' . esc_attr($alt) . '" src="' . esc_url($custom_avatar_url) . '" class="avatar avatar-40" style="height: 40px; width: 40px;">';
        }
    }

    return $avatar;
}

add_filter('get_avatar', 'custom_avatar_url', 10, 5);


//------------------------------------- Load Wordpress Media Library on Frontend ------------------------------------//
function enqueue_frontend_media_scripts() {
    wp_enqueue_media();
}
add_action('wp_enqueue_scripts', 'enqueue_frontend_media_scripts');


//---------------- Limit authors to see comments on their own posts ----------------//

function limit_comments_to_author_posts($comments_query) {
    if (is_admin() && current_user_can('author')) {
        global $user_ID;
        $comments_query->query_vars['post_author'] = $user_ID;
    }
}

add_filter('pre_get_comments', 'limit_comments_to_author_posts');

//---------------- Hide all admin noticese ----------------//

function hide_all_admin_notices() {
    remove_all_actions('admin_notices');
}
add_action('admin_init', 'hide_all_admin_notices');

//---------------- Disable wordpress Admin  ----------------///////////////////////////////////////////////////////////////////////////////////
add_action('init', 'restrict_wp_admin_access');

function restrict_wp_admin_access() {
    // Check if it's the admin area
    if (is_admin()) {
        // Get the current user
        $current_user = wp_get_current_user();

        //Check if the user is not an administrator
        
        // if ( !in_array('administrator', $current_user->roles) || !current_user_can('manage_options') ) {
        //     // Redirect non-admin users to a custom page
        //     wp_redirect(home_url('/404'));
        //     exit();
        // }
    }
}

//---------------- Disable Wordpress Login ----------------///////////////////////////////////////////////////////////////////////////////////
function redirect_login_page() {
    // Redirect to the custom login page
    $login_page = home_url('/user-profile/');
    $page_viewed = basename($_SERVER['REQUEST_URI']);

    if ($page_viewed == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET' && !current_user_can('manage_options')) {
        wp_redirect($login_page);
        exit;
    }
	
}

add_action('init','redirect_login_page');

//---------------- Excerpt length ----------------//

function mytheme_custom_excerpt_length( $length ) {
    if(is_author()){
        return 50;
    }else{
        return 100;
    }
}
add_filter( 'excerpt_length', 'mytheme_custom_excerpt_length', 999 );


//---------------- Redirect to the same page after sign-out ----------------//

function hungpd_dot_name_logout_redirect( $logouturl, $redir ){
    return $logouturl . '&amp;redirect_to=http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}
add_filter( 'logout_url', 'hungpd_dot_name_logout_redirect', 10, 2 );

//---------------- ERROR: Cannot modify header information ??? ----------------//

// add_filter('template_redirect', function () {
//     ob_start(null, 0, 0);
// });

// add_filter('template_redirect', function () {
//     ob_start();
// });

//---------------- Post per page for authors page ----------------//

function set_author_archive_limit( $query ) {
    if ( is_admin() || ! $query->is_main_query() )
        return;
    if ( is_author() ) {        
        $query->set( 'posts_per_page', 5 );
        return;
    }
}
add_action( 'pre_get_posts', 'set_author_archive_limit', 1 );

//---------------- Hide admin bar ----------------//

add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}


//---------------- total views ----------------//

function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0 View";
    }
    return $count.' Views';
}
function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}
// Remove issues with prefetching adding extra views
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);


//---------------- Show user disk usage on media upload page ----------------//

add_action( 'pre-upload-ui', 'pre_upload_ui_func' );

function pre_upload_ui_func(){
    // include get_template_directory()."/theme_addons/database_queries/queries.php";
    include"theme_addons/database_queries/queries.php";
    $result_tduf = $total_disk_usage_formatted ?? "0";
    echo "You've used <b>". $result_tduf ." </b>Bytes from your <b>10MB</b>"; 
}

//---------------- Stop wordpress to make diffrent sizes of photos ----------------//

function add_image_insert_override( $sizes ){
    unset( $sizes[ 'thumbnail' ]);
    unset( $sizes[ 'medium' ]);
    unset( $sizes[ 'medium_large' ] );
    unset( $sizes[ 'large' ]);
    unset( $sizes[ 'full' ] );
    return $sizes;
}
add_filter( 'intermediate_image_sizes_advanced', 'add_image_insert_override' );



//---------------- Limit Posts access ( users can see only their own posts) ----------------//


function posts_for_current_author($query) {
    global $pagenow;
    
    if( 'edit.php' != $pagenow || !$query->is_admin )
        return $query;
    
    if( !current_user_can( 'edit_others_posts' ) ) {
        global $user_ID;
        $query->set('author', $user_ID );
    }
    return $query;
}
add_filter('pre_get_posts', 'posts_for_current_author');


//---------------- Limit media library access ( users can see only their own files) ----------------//

add_filter( 'ajax_query_attachments_args', 'kanithemes_show_current_user_attachments' );

function kanithemes_show_current_user_attachments( $query ) {
    $user_id = get_current_user_id();
    if ( $user_id && !current_user_can('activate_plugins') && !current_user_can('edit_others_posts
') ) {
        $query['author'] = $user_id;
    }
    return $query;
}


//---------------- Limit Disk space ,files size, file types and photo dementions before upload ----------------//

if ( ! current_user_can( 'manage_options' ) ) {	
    if(is_user_logged_in()){
        $user_id = get_current_user_id();
        include dirname(__FILE__)."/theme_addons/database_queries/queries.php";
        update_user_meta( $user_id,'total_disk_usage', $total_disk_usage  );    
    }


    add_filter( 'upload_size_limit', 'max_upload_size' );
    function max_upload_size($size) {
        return 1024*500; 
    }

    add_filter( 'wp_handle_upload_prefilter', 'check_image_dimensions_during_upload' ); 

    function check_image_dimensions_during_upload( $file ){

        $mimes = array( 'image/jpeg', 'image/png', 'image/gif' );
        $img = getimagesize( $file['tmp_name'] );
        $maximum = array( 'width' => 1980, 'height' => 1440 );
        $minimum = array( 'width' => 300, 'height' => 300 );

        // Maximums
        if ( $img[0] > $maximum['width'] )
            $file['error'] = 
                'Image too large. Maximum width is ' 
                .$maximum['width'] 
                .'px. Uploaded image width is ' 
                .$img[0] . 'px';

        elseif ( $img[1] > $maximum['height'] )
            $file['error'] = 
                'Image too large. Maximum height is ' 
                .$maximum['height'] 
                .'px. Uploaded image height is ' 
                .$img[1] . 'px';
        // Minimums
        elseif ( $img[0] < $minimum['width'] )
            $file['error'] = 
                'Image too small. Minimum width is ' 
                .$minimum['width'] 
                .'px. Uploaded image width is ' 
                .$img[0] . 'px';

        elseif ( $img[1] < $minimum['height'] )
            $file['error'] = 
                'Image too small. Minimum height is ' 
                .$minimum['height'] 
                .'px. Uploaded image height is ' 
                .$img[1] . 'px';


        $filesize = filesize( $file['tmp_name'] );

        if($filesize > 500000){
            $file['error'] = "File uploads exceeding 500`000 Bytes(500Kb) are prohibited. Such uploads may lead to significant delays in page loading and other adverse effects. This file size is : ".number_format(($filesize), 0, ',', '`');
        } 

        $total_disk_used = get_user_meta(get_current_user_id(), 'total_disk_usage', true);
        if(($filesize + $total_disk_used) > 10000000){
            $file['error'] = "You are allowed to upload files with up to 10MB and you've used ( " .number_format($total_disk_used, 0, ',', '`') . " ). You got ( ".number_format((10000000 - $total_disk_used), 0, ',', '`')." ) left.";
        }

        if( !in_array( $file['type'], $mimes ) ){
            $file['error'] = "Only images (jpeg / png / gif) are allowed.";
        }

        return $file;
    }

}    



//__________________________________________________________________________//
//							W O O C O M M E R C E
//__________________________________________________________________________//


add_theme_support( 'wc-product-gallery-zoom' );
add_theme_support( 'wc-product-gallery-lightbox' );
add_theme_support( 'wc-product-gallery-slider' );
add_theme_support( 'woocommerce' );


?>
