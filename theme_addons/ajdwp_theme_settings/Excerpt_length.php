<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
//--------------------------- Excerpt length ---------------------------//
$options = get_option('AJDWP_theme_options');
if (!empty($options['custom_excerpt_length'])) {
    function mytheme_custom_excerpt_length($length) {
        $options = get_option('AJDWP_theme_options');
        $author_length = isset($options['excerpt_author_length']) ? $options['excerpt_author_length'] : 50;
        $general_length = isset($options['excerpt_general_length']) ? $options['excerpt_general_length'] : 100;
        
        if (is_author()) {
            return $author_length;
        } else {
            return $general_length;
        }
    }
    add_filter('excerpt_length', 'mytheme_custom_excerpt_length', 999);
}

?>