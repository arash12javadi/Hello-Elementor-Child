<?php 

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

//--------------------------- Redirect Login/logout Page ---------------------------//

// Retrieve theme options
$options = get_option('AJDWP_theme_options', []); // Ensure $options is an array

// Check if the 'redirect_login_page' option is set and 'login_page_url' is not empty
if (!empty($options['redirect_login_page']) && !empty($options['login_page_url'])) {
    
    function custom_login_url() {
        $options = get_option('AJDWP_theme_options', []);

        // Check if 'login_page_url' exists before using it
        if (!empty($options['login_page_url'])) {
            // Get current user info
            $current_user = wp_get_current_user();
            
            // Check if the user is NOT an admin
            if (!in_array('administrator', (array) $current_user->roles, true)) {
                wp_redirect(esc_url($options['login_page_url']));
                exit();
            }
        }
    }

    add_action('login_form', 'custom_login_url');
}
?>
