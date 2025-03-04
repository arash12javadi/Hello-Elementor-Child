<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
//--------------------------- woocommerce_theme_support ---------------------------//
$options = get_option('AJDWP_theme_options');
if (!empty($options['woocommerce_theme_support'])) {
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
    add_theme_support( 'woocommerce' );
}


?>