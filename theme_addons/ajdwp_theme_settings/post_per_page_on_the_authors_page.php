<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
//--------------------------- Post per page for authors page ---------------------------//
$options = get_option('AJDWP_theme_options');
if (!empty($options['set_author_archive_limit'])) {
    function set_author_archive_limit( $query ) {
        if ( is_admin() || ! $query->is_main_query() )
            return;
        if ( is_author() ) {        
            $query->set( 'posts_per_page', 5 );
            return;
        }
    }
    add_action( 'pre_get_posts', 'set_author_archive_limit', 1 );
}

?>