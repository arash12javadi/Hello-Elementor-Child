<?php
//_____________________________________ limit_users_to_see_only_their_own_medias.php _____________________________________//
if (!defined('ABSPATH')) {
    exit;
}

// Admin Media Library (list view: /wp-admin/upload.php)
add_action('pre_get_posts', function ($q) {
    if (!is_admin() || !$q->is_main_query()) return;

    $opts = (array) get_option('AJDWP_theme_options');
    if (empty($opts['limit_media_library_access'])) return;

    global $pagenow;
    if ($pagenow !== 'upload.php') return;

    // Do not limit admins/editors (or anyone who can delete others' posts)
    if (current_user_can('delete_others_posts')) return;

    $q->set('author', get_current_user_id());
});

// Media modal / attachments AJAX (front-end + admin)
add_filter('ajax_query_attachments_args', function ($args) {
    $opts = (array) get_option('AJDWP_theme_options');
    if (empty($opts['limit_media_library_access'])) return $args;

    // Keep admins/editors unrestricted
    if (!current_user_can('delete_others_posts')) {
        $args['author'] = get_current_user_id();
    }
    return $args;
});
