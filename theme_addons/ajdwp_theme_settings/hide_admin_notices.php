<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

//--------------------------- Hide all admin notices ---------------------------//
$options = get_option('AJDWP_theme_options');
if (!empty($options['hide_all_admin_notices'])) {
    function hide_all_admin_notices() {
        remove_all_actions('admin_notices');
    }
    add_action('admin_init', 'hide_all_admin_notices');
}
?>