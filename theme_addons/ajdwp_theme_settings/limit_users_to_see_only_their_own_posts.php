<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
//--------------------------- Limit Posts access ---------------------------//
//( users can see only their own posts) 
$options = get_option('AJDWP_theme_options');
if (!empty($options['limit_post_access'])) {
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
}
?>