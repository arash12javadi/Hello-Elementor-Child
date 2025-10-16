<?php
if (!defined('ABSPATH')) exit;

if (!function_exists('ajdwp_output_gtm_head')) {
    function ajdwp_output_gtm_head()
    {
        // Skip in admin area and for logged-in users (keeps analytics clean)
        if (is_admin() || is_user_logged_in()) return;

        $opts = get_option('AJDWP_theme_options');
        if (empty($opts['google_tag_manager'])) return;

        $code = isset($opts['gtm_header_script']) ? trim($opts['gtm_header_script']) : '';
        if ($code === '') return;

        echo "\n{$code}\n";
    }
    add_action('wp_head', 'ajdwp_output_gtm_head', 1);
}

if (!function_exists('ajdwp_output_gtm_body')) {
    function ajdwp_output_gtm_body()
    {
        if (is_admin() || is_user_logged_in()) return;

        $opts = get_option('AJDWP_theme_options');
        if (empty($opts['google_tag_manager'])) return;

        if (!empty($opts['gtm_body_script'])) {
            echo "\n{$opts['gtm_body_script']}\n";
        }
    }
    add_action('wp_body_open', 'ajdwp_output_gtm_body', 1);
}
