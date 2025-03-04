<?php

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

//--------------------------- Load Wordpress Media Library on Frontend ---------------------------//
$options = get_option('AJDWP_theme_options');
if (!empty($options['enqueue_frontend_media_scripts'])) {
    function enqueue_frontend_media_scripts()
    {
        if (is_author()) {
            wp_enqueue_media();
        }
    }
    add_action('wp_enqueue_scripts', 'enqueue_frontend_media_scripts');
}
