<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/*
Template Name: FullWith canvas
*/

// Your page content goes here
if (have_posts()) :
    while (have_posts()) :
        the_post();
        the_content();
    endwhile;
endif;
?>