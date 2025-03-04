<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

//--------------------------- Disable WordPress Admin Access ---------------------------//
$options = get_option('AJDWP_theme_options');
if (!empty($options['restrict_wp_admin_access'])) {
    function restrict_wp_admin_access() {
        // Check if it's the admin area
        if (is_admin() && !wp_doing_ajax()) {
            // Get the current user
            $current_user = wp_get_current_user();

            // Check if the user is not an administrator or doesn't have the manage_options capability
            if (!in_array('administrator', $current_user->roles) || !current_user_can('manage_options')) {
                // Redirect non-admin users to a custom page
                wp_redirect(home_url('/user-profile/'));
                exit();
            }
        }
    }
    add_action('admin_init', 'restrict_wp_admin_access');
}


?>