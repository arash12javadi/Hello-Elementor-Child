<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

//--------------------------- Custom Menu Link ---------------------------//
// Redirect users to their author page -> make a link in menu with this url `https://User_Author_page`
$options = get_option('AJDWP_theme_options');
if (!empty($options['custom_menu_link'])) {
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
            if ($item->url == 'https://User_Author_page') {
                $item->url = custom_menu_link_url();
            }
        }

        return $items;
    }
}

?>