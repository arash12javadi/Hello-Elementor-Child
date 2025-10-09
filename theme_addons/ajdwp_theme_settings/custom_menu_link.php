<?php
if (!defined('ABSPATH')) exit;

/**
 * AJDWP – Dynamic Profile Menu Items (with your avatar logic)
 *
 * Placeholders you can use in Appearance → Menus:
 *   - URL:  https://User_Author_page
 *   - URL or Label: #profile_name#, #profile_avatar#, #profile_both#
 *
 * Logged in:
 *   - Placeholder URL → user's author page
 *   - #profile_* → title becomes name / avatar / both; URL is author page
 *
 * Logged out:
 *   - Placeholder URL → login URL (custom if set, else wp-login.php with redirect back)
 *   - #profile_* items are removed
 */

add_filter('wp_nav_menu_objects', function ($items, $args = null) {
    // If you still keep the toggle, respect it; else delete the next 3 lines.
    $opts = (array) get_option('AJDWP_theme_options');
    if (isset($opts['custom_menu_link']) && empty($opts['custom_menu_link'])) return $items;

    if (is_admin()) return $items;

    $placeholder_url = 'https://User_Author_page';
    $profile_tokens  = array('#profile_name#', '#profile_avatar#', '#profile_both#');
    $new_items       = array();

    // Build login URL (prefers your custom login page)
    $current_url = (is_ssl() ? 'https://' : 'http://') . ($_SERVER['HTTP_HOST'] ?? '') . ($_SERVER['REQUEST_URI'] ?? '/');
    $login_url   =
        (!empty($opts['redirect_login_page']) && !empty($opts['login_page_url']))
        ? esc_url_raw($opts['login_page_url'])
        : wp_login_url($current_url);

    if (is_user_logged_in()) {
        $u          = wp_get_current_user();
        $author_url = ($u && $u->ID) ? get_author_posts_url($u->ID) : home_url('/');

        // === Avatar HTML (your navbar-style logic, image only) ===
        $custom_avatar = get_user_meta($u->ID, 'custom_avatar_url', true);
        if (!empty($custom_avatar)) {
            $avatar_html = sprintf(
                '<img id="selected-image" src="%s" alt="%s" loading="lazy" style="width:30px; height:30px;" class="rounded-circle align-middle me-2" />',
                esc_url($custom_avatar),
                esc_attr__('User Avatar', 'hello-elementor-child')
            );
        } else {
            // WordPress avatar, 50px, with your classes
            $avatar_html = get_avatar(
                $u,
                30,
                '',
                esc_attr($u->display_name ?: $u->user_login),
                array('class' => 'rounded-circle align-middle me-2', 'loading' => 'lazy')
            );
        }
        $name = esc_html($u->display_name ?: $u->user_login);

        foreach ($items as $item) {
            $item_url   = isset($item->url)   ? (string) $item->url   : '';
            $item_title = isset($item->title) ? (string) $item->title : '';

            $is_profile_token = in_array($item_url, $profile_tokens, true) || in_array($item_title, $profile_tokens, true);
            $is_author_link   = ($item_url === $placeholder_url);

            if ($is_profile_token || $is_author_link) {
                // Always point to the current user's author page
                $item->url = esc_url($author_url);

                // Set title for #profile_* tokens
                if ($is_profile_token) {
                    $token = in_array($item_url, $profile_tokens, true) ? $item_url : $item_title;
                    if ($token === '#profile_name#') {
                        $item->title = $name;
                    } elseif ($token === '#profile_avatar#') {
                        $item->title = $avatar_html;              // HTML ok in titles for most themes
                    } elseif ($token === '#profile_both#') {
                        $item->title = $avatar_html . ' ' . $name; // avatar + name
                    }
                }
            }

            $new_items[] = $item;
        }
    } else {
        // Logged-out: remove #profile_* items, convert placeholder URL to login URL
        foreach ($items as $item) {
            $item_url   = isset($item->url)   ? (string) $item->url   : '';
            $item_title = isset($item->title) ? (string) $item->title : '';

            $is_profile_token = in_array($item_url, $profile_tokens, true) || in_array($item_title, $profile_tokens, true);
            if ($is_profile_token) continue;

            if ($item_url === $placeholder_url) {
                $item->url = esc_url($login_url);
            }
            $new_items[] = $item;
        }
    }

    return $new_items;
}, 10, 2);

/**
 * Highlight the user's own author link as current.
 */
add_filter('nav_menu_css_class', function ($classes, $item) {
    if (!is_user_logged_in() || !is_author()) return $classes;

    $current_user   = wp_get_current_user();
    $current_author = get_queried_object();

    if (!$current_author || empty($current_author->ID) || (int)$current_author->ID !== (int)$current_user->ID) {
        return $classes;
    }

    $author_url = untrailingslashit(get_author_posts_url($current_user->ID));
    $item_url   = untrailingslashit((string) ($item->url ?? ''));

    if ($item_url && $item_url === $author_url) {
        $classes[] = 'current-menu-item';
        $classes[] = 'active';
    }

    return $classes;
}, 10, 2);
