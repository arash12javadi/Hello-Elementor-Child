<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


// --------------- Like Follow Table Creation Function on theme switch ---------------
function create_ajdwp_like_follow_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'ajdwp_like_follow';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "
        CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            post_id bigint(20) DEFAULT NULL,
            author_id bigint(20) DEFAULT NULL,
            like_stat varchar(20) DEFAULT NULL,
            follow_stat varchar(20) DEFAULT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY unique_action (user_id, post_id, author_id, like_stat, follow_stat)
        ) $charset_collate;
    ";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Hook the function to theme activation
add_action('after_switch_theme', 'create_ajdwp_like_follow_table');


?>