<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
//--------------------------- Contributor and Subscribers Media and Post Access ---------------------------// 
function allow_media_access_based_on_role() {
    $options = get_option('AJDWP_theme_options');
    $user_id = get_current_user_id();
    $user = get_userdata($user_id);

    if ($user) {
        if (in_array('contributor', (array) $user->roles)) {
            if (!empty($options['contributor_can_upload'])) {
                $user->add_cap('upload_files');
                $user->add_cap('delete_posts');
            } else {
                $user->remove_cap('upload_files');
                $user->remove_cap('delete_posts');
            }

            if (!empty($options['contributor_can_post'])) {
                $user->add_cap('edit_posts');
                $user->add_cap('delete_posts');
            } else {
                $user->remove_cap('edit_posts');
                $user->remove_cap('delete_posts');
            }
        }

        if (in_array('subscriber', (array) $user->roles)) {
            if (!empty($options['subscriber_can_upload'])) {
                $user->add_cap('upload_files');
                $user->add_cap('delete_posts');
            } else {
                $user->remove_cap('upload_files');
                $user->remove_cap('delete_posts');
            }

            if (!empty($options['subscriber_can_post'])) {
                $user->add_cap('edit_posts');
                $user->add_cap('delete_posts');
            } else {
                $user->remove_cap('edit_posts');
                $user->remove_cap('delete_posts');
            }
        }
    }
}
add_action('init', 'allow_media_access_based_on_role');

?>