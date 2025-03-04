<?php

if (!defined('ABSPATH')) exit;


$options = get_option('AJDWP_theme_options');
if (!empty($options['google_tag_manager'])) {
    // Custom sanitization function for GTM scripts
    function sanitize_gtm_scripts($input) {
        $allowed_tags = array(
            'script' => array(
                'type' => array(),
                'src' => array(),
                'async' => array(),
                'defer' => array()
            ),
            'noscript' => array(),
            'iframe' => array(
                'src' => array(),
                'height' => array(),
                'width' => array(),
                'style' => array(),
                'frameborder' => array(),
                'scrolling' => array()
            ),
        );

        if (!empty($input['gtm_header_script'])) {
            $input['gtm_header_script'] = wp_kses($input['gtm_header_script'], $allowed_tags);
        }
        if (!empty($input['gtm_body_script'])) {
            $input['gtm_body_script'] = wp_kses($input['gtm_body_script'], $allowed_tags);
        }
        return $input;
    }
    add_filter('pre_update_option_AJDWP_theme_options', 'sanitize_gtm_scripts');

    // Output GTM header script
    function add_google_tag_manager_head() {
        $options = get_option('AJDWP_theme_options');
        if (!empty($options['gtm_header_script'])) {
            echo $options['gtm_header_script'];
        }
    }
    add_action('wp_head', 'add_google_tag_manager_head');

    // Output GTM body script
    function add_google_tag_manager_body() {
        $options = get_option('AJDWP_theme_options');
        if (!empty($options['gtm_body_script'])) {
            echo $options['gtm_body_script'];
        }
    }
    add_action('wp_body_open', 'add_google_tag_manager_body');

    // Debugging: Check if both scripts are being processed
    function debug_sanitized_gtm_scripts($input) {
        error_log('GTM Header Script: ' . print_r($input['gtm_header_script'], true));
        error_log('GTM Body Script: ' . print_r($input['gtm_body_script'], true));
        return $input;
    }
    add_filter('pre_update_option_AJDWP_theme_options', 'debug_sanitized_gtm_scripts');
}