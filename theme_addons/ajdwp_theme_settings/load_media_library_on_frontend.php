<?php
//_____________________________________ load_media_library_on_frontend.php _____________________________________//

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

//--------------------------- Load Wordpress Media Library on Frontend 
// Helper: check current user's role
function ajdwp_user_has_role($role)
{
    $u = wp_get_current_user();
    return $u && in_array($role, (array) $u->roles, true);
}

// Always hook; decide at runtime.
add_action('wp_enqueue_scripts', 'ajdwp_maybe_enqueue_media');
function ajdwp_maybe_enqueue_media()
{
    $opts = get_option('AJDWP_theme_options');

    // Only run when the global toggle is on
    if (empty($opts['enqueue_frontend_media_scripts'])) return;

    // Must be logged in to use the media library
    if (! is_user_logged_in()) return;

    $should_load = false;

    // Condition A: on author archive
    if (is_author()) {
        $should_load = true;
    }

    // Condition B: setting "subscriber_can_upload" is enabled AND user is a subscriber (or has upload capability)
    if (!$should_load && !empty($opts['subscriber_can_upload'])) {
        // If you’ve actually granted the capability via your roles logic, this covers it:
        if (current_user_can('upload_files')) {
            $should_load = true;
        } else if (ajdwp_user_has_role('subscriber')) {
            // Fallback to role check in case you toggle before capabilities are applied
            $should_load = true;
        }
    }

    if (!$should_load) return;

    // Enqueue media modal + helpers
    wp_enqueue_media();
    wp_enqueue_script('media-editor');
    wp_enqueue_style('wp-jquery-ui-dialog');
}
