<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

//--------------------------- Stop wordpress to make diffrent sizes of photos ---------------------------//
$options = get_option('AJDWP_theme_options');
if (!empty($options['stop_image_sizes'])) {
    function add_image_insert_override( $sizes ){
        unset( $sizes[ 'thumbnail' ]);
        unset( $sizes[ 'medium' ]);
        unset( $sizes[ 'medium_large' ] );
        unset( $sizes[ 'large' ]);
        unset( $sizes[ 'full' ] );
        return $sizes;
    }
    add_filter( 'intermediate_image_sizes_advanced', 'add_image_insert_override' );
}

?>