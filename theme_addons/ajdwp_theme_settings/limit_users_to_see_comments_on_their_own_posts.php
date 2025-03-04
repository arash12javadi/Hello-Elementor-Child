<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

//--------------------------- limit comments access ---------------------------// 
//Limit authors to see comments on their own posts only
$options = get_option('AJDWP_theme_options');
if (!empty($options['limit_author_comments'])) {
    function limit_comments_to_author_posts($comments_query) {
        if (is_admin() && current_user_can('author')) {
            global $user_ID;
            $comments_query->query_vars['post_author'] = $user_ID;
        }
    }

    add_filter('pre_get_comments', 'limit_comments_to_author_posts');
}
?>