<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

//--------------------------- custom_avatar_url ---------------------------//
// Change User avatar for Comments and Posts by Changing `custom_avatar_url` 
$options = get_option('AJDWP_theme_options');
if (!empty($options['custom_avatar_url'])) {
    function custom_avatar_url($avatar, $id_or_email, $size, $default, $alt) {
        // Check if it's a comment
        if (is_object($id_or_email) && isset($id_or_email->comment_ID)) {
            $user_id = $id_or_email->user_id;

            // Check if the user has a custom avatar URL
            $custom_avatar_url = get_user_meta($user_id, 'custom_avatar_url', true);

            if (!empty($custom_avatar_url)) {
                // Use custom avatar URL with a fixed size of 40 pixels
                $avatar = '<img alt="' . esc_attr($alt) . '" src="' . esc_url($custom_avatar_url) . '" class="avatar avatar-40" style="height: 40px; width: 40px;">';
            }
        }

        return $avatar;
    }

    add_filter('get_avatar', 'custom_avatar_url', 10, 5);
}


?>