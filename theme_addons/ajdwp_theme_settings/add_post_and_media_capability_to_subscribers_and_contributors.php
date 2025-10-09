<?php
if (!defined('ABSPATH')) exit;

/**
 * Grant/revoke caps for Contributors/Subscribers based on your settings.
 * - If "can upload" is on: grant upload_files + delete_posts (delete own attachments).
 * - If "can post" is on: grant edit_posts + delete_posts.
 * Never grant delete_others_posts.
 */
function ajdwp_apply_role_caps_from_options()
{
    $options = get_option('AJDWP_theme_options');
    $user    = wp_get_current_user();
    if (!$user || empty($user->ID)) return;

    $roles = (array) $user->roles;

    // Helper to toggle a single cap
    $toggle_cap = function (WP_User $u, $cap, $on) {
        $on ? $u->add_cap($cap) : $u->remove_cap($cap);
    };

    // We explicitly NEVER give this:
    $user->remove_cap('delete_others_posts');

    // ----- Contributor -----
    if (in_array('contributor', $roles, true)) {
        // Upload => can delete own attachments (delete_posts)
        $toggle_cap($user, 'upload_files',         !empty($options['contributor_can_upload']));
        $toggle_cap($user, 'delete_posts',         !empty($options['contributor_can_upload']));

        // Post => edit/delete own posts
        $toggle_cap($user, 'edit_posts',           !empty($options['contributor_can_post']));
        $toggle_cap($user, 'delete_posts',         !empty($options['contributor_can_post']) || !empty($options['contributor_can_upload']));
    }

    // ----- Subscriber -----
    if (in_array('subscriber', $roles, true)) {
        // Upload => can delete own attachments
        $toggle_cap($user, 'upload_files',         !empty($options['subscriber_can_upload']));
        $toggle_cap($user, 'delete_posts',         !empty($options['subscriber_can_upload']));

        // Post => edit/delete own posts (optional)
        $toggle_cap($user, 'edit_posts',           !empty($options['subscriber_can_post']));
        $toggle_cap($user, 'delete_posts',         !empty($options['subscriber_can_post']) || !empty($options['subscriber_can_upload']));
    }
}
add_action('init', 'ajdwp_apply_role_caps_from_options');
