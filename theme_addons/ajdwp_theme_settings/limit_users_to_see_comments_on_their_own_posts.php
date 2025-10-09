<?php
//_____________________________________ limit_users_to_see_comments_on_their_own_posts.php _____________________________________//
if (!defined('ABSPATH')) {
    exit;
}

// Limit comments screen to comments ON the current user's posts (if enabled)
add_action('pre_get_comments', function ($comments_query) {
    if (!is_admin()) return;

    $opts = (array) get_option('AJDWP_theme_options');
    if (empty($opts['limit_author_comments'])) return;

    // Only affect users who are not allowed to manage other authors' content
    // (admins/editors keep full view)
    if (current_user_can('edit_others_posts') || current_user_can('moderate_comments')) return;

    // Only on the comments screen
    global $pagenow;
    if ($pagenow !== 'edit-comments.php') return;

    $comments_query->query_vars['post_author'] = get_current_user_id();
});
