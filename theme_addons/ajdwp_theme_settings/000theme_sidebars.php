<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


//__________________________________________________________________________//
//							Add theme Sidebars
//__________________________________________________________________________//
$options = get_option('AJDWP_theme_options');
if (!empty($options['theme_sidebars'])) {

    register_sidebar( array(
        'name'          => __( 'AJDWP Page Sidebar', 'AJDWP_theme' ),
        'id'            => 'AJDWP-page-sidebar',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );


    //-------------------------------------------------------------------------//


    register_sidebar( array(
        'name'          => __( 'AJDWP Blog Sidebar', 'AJDWP_theme' ),
        'id'            => 'AJDWP-blog-sidebar',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );


    //-------------------------------------------------------------------------//


    register_sidebar( array(
        'name'          => __( 'AJDWP Search Sidebar', 'AJDWP_theme' ),
        'id'            => 'AJDWP-search-sidebar',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );


    //-------------------------------------------------------------------------//


    register_sidebar( array(
        'name'          => __( 'AJDWP Shop Sidebar', 'AJDWP_theme' ),
        'id'            => 'AJDWP-Shop-sidebar',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );


    //-------------------------------------------------------------------------//


    register_sidebar( array(
        'name'          => __( 'AJDWP Header Sidebar', 'AJDWP_theme' ),
        'id'            => 'AJDWP-header-sidebar',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );


    //------------------------------------- F O O T E R ------------------------------------//


    register_sidebar( array(
        'name'          => __( 'AJDWP Footer widget 1', 'AJDWP_theme' ),
        'id'            => 'AJDWP-footer-widget-1',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

}



?>