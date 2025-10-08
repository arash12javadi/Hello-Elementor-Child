<?php
if (!defined('ABSPATH')) exit;

add_filter('wp_nav_menu_objects', function ($items) {
    $new_items = [];

    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $author_url = get_author_posts_url($current_user->ID);
        $avatar = get_avatar(
            $current_user->ID,
            32,
            '',
            esc_attr($current_user->display_name),
            ['class' => 'rounded-circle align-middle me-2']
        );
        $name = esc_html($current_user->display_name);

        foreach ($items as $item) {
            // Handle dynamic placeholders
            if (
                in_array($item->url, ['#profile_name#', '#profile_avatar#', '#profile_both#']) ||
                in_array($item->title, ['#profile_name#', '#profile_avatar#', '#profile_both#'])
            ) {

                switch ($item->url) {
                    case '#profile_name#':
                    case '#profile_name':
                        $item->title = $name;
                        break;

                    case '#profile_avatar#':
                    case '#profile_avatar':
                        $item->title = $avatar;
                        break;

                    case '#profile_both#':
                    case '#profile_both':
                        $item->title = $avatar . ' ' . $name;
                        break;
                }

                $item->url = esc_url($author_url);
            }

            // Keep all menu items
            $new_items[] = $item;
        }
    } else {
        // If not logged in, remove the profile items completely
        foreach ($items as $item) {
            if (!in_array($item->url, ['#profile_name#', '#profile_avatar#', '#profile_both#'])) {
                $new_items[] = $item;
            }
        }
    }

    return $new_items;
});

add_filter('nav_menu_css_class', function ($classes, $item) {
    // Only when logged in and viewing an author page
    if (is_user_logged_in() && is_author()) {
        $current_user   = wp_get_current_user();
        $current_author = get_queried_object();

        // Make sure the viewed author is the current user
        if ($current_author && isset($current_author->ID) && (int)$current_author->ID === (int)$current_user->ID) {
            $author_url = untrailingslashit(get_author_posts_url($current_user->ID));
            $item_url   = untrailingslashit($item->url ?? '');

            // Mark as current only if this menu item matches the user's author page
            if ($item_url === $author_url) {
                $classes[] = 'current-menu-item';
                $classes[] = 'active';
            }
        }
    }

    return $classes;
}, 10, 2);


// How it works:

// You can add a Custom Link in your menu (via Appearance → Menus)
// and set the URL or Label to one of:

// #profile_name#
// #profile_avatar#
// #profile_both#