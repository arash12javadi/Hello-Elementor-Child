<?php
//_____________________________________ auth_ajax_cache_guard.php _____________________________________//
if (!defined('ABSPATH')) exit;

/**
 * 1) Front-end AJAX URL with correct scheme/host (http/https).
 *    - Localizes AJDWP.ajaxUrl to your front-end script if enqueued.
 *    - Falls back to a tiny inline snippet if the handle isn't known.
 * 2) No-cache headers for media/uploader pages for logged-in users.
 */

// -----------------------------------------------------------------------------
// 1) Localize AJAX URL for front-end scripts
// -----------------------------------------------------------------------------
add_action('wp_enqueue_scripts', function () {
    // Add your known front-end script handles here (first one that is enqueued will be localized).
    $handles = array(
        'ajdwp-frontend',
        'persian-origins-frontend',
        // add more handles if needed...
    );

    $localized = false;
    $ajax_url  = admin_url('admin-ajax.php', is_ssl() ? 'https' : 'http');

    foreach ($handles as $handle) {
        if (wp_script_is($handle, 'enqueued')) {
            wp_localize_script($handle, 'AJDWP', array(
                'ajaxUrl' => $ajax_url,
                'nonce'   => wp_create_nonce('ajdwp-frontend'),
            ));
            $localized = true;
            break;
        }
    }

    // Fallback: if your script was enqueued earlier/elsewhere under an unknown handle,
    // inject a tiny inline safety snippet so AJDWP.ajaxUrl still exists.
    if (!$localized) {
        add_action('wp_print_footer_scripts', function () use ($ajax_url) {
?>
            <script>
                window.AJDWP = window.AJDWP || {};
                if (!AJDWP.ajaxUrl) {
                    AJDWP.ajaxUrl = <?php echo wp_json_encode($ajax_url); ?>;
                }
            </script>
<?php
        }, 99);
    }
});

// -----------------------------------------------------------------------------
// 2) Don’t cache pages that render the media modal (logged-in only)
// -----------------------------------------------------------------------------
add_action('template_redirect', function () {
    if (!is_user_logged_in()) return;

    // Adjust the slugs to match your site. You can filter this list if needed.
    $pages_with_uploader = apply_filters('ajdwp_pages_with_media_modal', array('user-account', 'my-media', 'my-posts', 'my-comments'));

    if (is_author() || is_page($pages_with_uploader)) {
        if (!defined('DONOTCACHEPAGE')) define('DONOTCACHEPAGE', true);
        nocache_headers();
    }
});
