<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
//--------------------------- Hide admin bar for users ---------------------------//
$options = get_option('AJDWP_theme_options');
if (!empty($options['remove_admin_bar'])) {
    add_action('after_setup_theme', 'remove_admin_bar');
    function remove_admin_bar() {
        if (!current_user_can('administrator') && !is_admin()) {
            show_admin_bar(false);
        }
    }
}
?>