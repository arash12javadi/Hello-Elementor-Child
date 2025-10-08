<?php

if (!defined('ABSPATH')) exit;

/**
 * Meta keys
 */
const MI_LOGGED_OUT_HIDE   = '_mi_hide_for_logged_out';
const MI_LOGGED_OUT_TITLE  = '_mi_alt_title_for_logged_out';
const MI_LOGGED_OUT_URL    = '_mi_alt_url_for_logged_out';

/**
 * 1) Add custom fields to each menu item in the admin editor
 *    WordPress 5.4+ hook: wp_nav_menu_item_custom_fields
 */
add_action('wp_nav_menu_item_custom_fields', function ($item_id, $item, $depth, $args) {
    // Current values
    $hide_for_logged_out = get_post_meta($item_id, MI_LOGGED_OUT_HIDE, true);
    $alt_title_logged_out = get_post_meta($item_id, MI_LOGGED_OUT_TITLE, true);
    $alt_url_logged_out   = get_post_meta($item_id, MI_LOGGED_OUT_URL, true);

?>
    <div class="field-mi-logged-out-controls description description-wide" style="border-top:1px solid #ddd;padding-top:8px;margin-top:8px;">
        <strong><?php esc_html_e('Visibility & Label (Logged-out Users)', 'default'); ?></strong>

        <p class="description">
            <label for="mi-hide-logged-out-<?php echo esc_attr($item_id); ?>">
                <input type="checkbox"
                    id="mi-hide-logged-out-<?php echo esc_attr($item_id); ?>"
                    name="mi_hide_logged_out[<?php echo esc_attr($item_id); ?>]"
                    value="1" <?php checked($hide_for_logged_out, '1'); ?> />
                <?php esc_html_e('Hide this item for users who are not logged in', 'default'); ?>
            </label>
        </p>

        <p class="description">
            <label for="mi-alt-title-logged-out-<?php echo esc_attr($item_id); ?>">
                <?php esc_html_e('Alternative label for logged-out users (optional)', 'default'); ?><br>
                <input type="text"
                    id="mi-alt-title-logged-out-<?php echo esc_attr($item_id); ?>"
                    class="widefat"
                    name="mi_alt_title_logged_out[<?php echo esc_attr($item_id); ?>]"
                    value="<?php echo esc_attr($alt_title_logged_out); ?>"
                    placeholder="<?php esc_attr_e('e.g. Login / Register', 'default'); ?>">
            </label>
        </p>

        <p class="description">
            <label for="mi-alt-url-logged-out-<?php echo esc_attr($item_id); ?>">
                <?php esc_html_e('Alternative URL for logged-out users (optional)', 'default'); ?><br>
                <input type="url"
                    id="mi-alt-url-logged-out-<?php echo esc_attr($item_id); ?>"
                    class="widefat code"
                    name="mi_alt_url_logged_out[<?php echo esc_attr($item_id); ?>]"
                    value="<?php echo esc_attr($alt_url_logged_out); ?>"
                    placeholder="<?php echo esc_attr(wp_login_url()); ?>">
            </label>
        </p>
    </div>
<?php
}, 10, 4);

/**
 * 2) Save custom fields when the menu is saved
 *    Hook: wp_update_nav_menu_item
 */
add_action('wp_update_nav_menu_item', function ($menu_id, $menu_item_db_id, $args) {
    if (!current_user_can('edit_theme_options')) {
        return;
    }

    // Hide for logged-out
    $hide_val = isset($_POST['mi_hide_logged_out'][$menu_item_db_id]) ? '1' : '';
    update_post_meta($menu_item_db_id, MI_LOGGED_OUT_HIDE, $hide_val);

    // Alt title
    if (isset($_POST['mi_alt_title_logged_out'][$menu_item_db_id])) {
        $alt_title = sanitize_text_field($_POST['mi_alt_title_logged_out'][$menu_item_db_id]);
        if ($alt_title !== '') {
            update_post_meta($menu_item_db_id, MI_LOGGED_OUT_TITLE, $alt_title);
        } else {
            delete_post_meta($menu_item_db_id, MI_LOGGED_OUT_TITLE);
        }
    }

    // Alt URL
    if (isset($_POST['mi_alt_url_logged_out'][$menu_item_db_id])) {
        $alt_url_raw = trim($_POST['mi_alt_url_logged_out'][$menu_item_db_id]);
        $alt_url     = $alt_url_raw ? esc_url_raw($alt_url_raw) : '';
        if ($alt_url !== '') {
            update_post_meta($menu_item_db_id, MI_LOGGED_OUT_URL, $alt_url);
        } else {
            delete_post_meta($menu_item_db_id, MI_LOGGED_OUT_URL);
        }
    }
}, 10, 3);

/**
 * 3) Apply behaviour on the front-end:
 *    - If not logged in and item is set to "hide", remove it
 *    - Else if not logged in and alt title/url provided, replace them
 */
add_filter('wp_nav_menu_objects', function ($items, $args = null) {
    if (is_user_logged_in()) {
        return $items; // nothing to change for logged-in users
    }

    $filtered = [];
    foreach ($items as $item) {
        $hide_for_logged_out = get_post_meta($item->ID, MI_LOGGED_OUT_HIDE, true);

        if ($hide_for_logged_out === '1') {
            // Skip this item entirely for logged-out visitors
            continue;
        }

        // If alt title / url exist, apply them
        $alt_title = get_post_meta($item->ID, MI_LOGGED_OUT_TITLE, true);
        $alt_url   = get_post_meta($item->ID, MI_LOGGED_OUT_URL, true);

        if ($alt_title !== '') {
            $item->title = $alt_title;
        }

        if ($alt_url !== '') {
            $item->url = $alt_url;
        }

        $filtered[] = $item;
    }

    return $filtered;
}, 10, 2);

/**
 * 4) (Optional) Make Customizer live-preview pick up changes consistently
 *    Ensures menu item meta is available in preview refreshes.
 */
add_filter('wp_setup_nav_menu_item', function ($menu_item) {
    $menu_item->mi_hide_for_logged_out   = get_post_meta($menu_item->ID, MI_LOGGED_OUT_HIDE, true);
    $menu_item->mi_alt_title_logged_out  = get_post_meta($menu_item->ID, MI_LOGGED_OUT_TITLE, true);
    $menu_item->mi_alt_url_logged_out    = get_post_meta($menu_item->ID, MI_LOGGED_OUT_URL, true);
    return $menu_item;
});
