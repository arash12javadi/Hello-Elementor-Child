<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
//--------------------------- Limit media access ---------------------------//
//( users can see only their own files)
$options = get_option('AJDWP_theme_options');
if (!empty($options['limit_media_library_access'])) {
    add_filter( 'ajax_query_attachments_args', 'kanithemes_show_current_user_attachments' );

    function kanithemes_show_current_user_attachments( $query ) {
        $user_id = get_current_user_id();
        if ( $user_id && !current_user_can('activate_plugins') && !current_user_can('edit_others_posts') ) {
            $query['author'] = $user_id;
        }
        return $query;
    }
}
?>