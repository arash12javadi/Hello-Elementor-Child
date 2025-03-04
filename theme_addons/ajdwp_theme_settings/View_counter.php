<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$options = get_option('AJDWP_theme_options');
if (!empty($options['post_views'])) {

    //--------------------------- Total Views Functions ---------------------------//

    // Function to get post views
    function getPostViews($postID) {
        $count_key = 'post_views_count';
        $count = get_post_meta($postID, $count_key, true);
        
        if ($count === '') {
            $count = 0; // Initialize count to 0 if not set
            add_post_meta($postID, $count_key, $count); // Add initial count
            return "0 View";
        }
        
        return $count . ' Views';
    }

    // Function to increment post views
    function setPostViews($postID) {
        $count_key = 'post_views_count';
        $count = get_post_meta($postID, $count_key, true);
        
        if ($count === '') {
            $count = 0; // Initialize count to 0 if not set
            add_post_meta($postID, $count_key, $count);
        } else {
            $count++;
            update_post_meta($postID, $count_key, $count);
        }
    }

    // Function to get author views
    function get_author_views($author_id) {
        $count_key = 'author_views_count';
        $count = get_user_meta($author_id, $count_key, true);
    
        if ($count === '') {
            $count = 0; // Initialize count to 0 if not set
            add_user_meta($author_id, $count_key, $count); // Add initial count
            return "0 Views";
        }
    
        return $count . ' Views';
    }

    // Function to increment author views
    function increment_author_views($author_id) {
        $count_key = 'author_views_count';
        $count = get_user_meta($author_id, $count_key, true);
    
        if ($count === '') {
            $count = 0; // Initialize count to 0 if not set
            add_user_meta($author_id, $count_key, $count); // Add initial count
        } else {
            $count++;
            update_user_meta($author_id, $count_key, $count);
        }
    }

    // Function to track page views for both posts and author pages
    function track_page_views() {
        static $executed = false;
    
        if (!$executed) {
            if (is_author()) {
                // Handle author page views
                $author_id = get_queried_object_id();
    
                $cookie_name = 'author_view_' . $author_id;
                if (!isset($_COOKIE[$cookie_name])) {
                    increment_author_views($author_id);
                    // $expire = 24 * 60 * 60; // 24 hours
                    $expire = strtotime('tomorrow') - time(); // Calculate seconds until end of day
                    // $expire = 5; // 5 seconds for testing
                    setcookie($cookie_name, 'viewed', time() + $expire, '/', $_SERVER['HTTP_HOST']);
                }
            } elseif (is_single() || is_page()) {
                // Handle post or page views
                $postID = get_the_ID();
    
                $cookie_name = 'page_view_' . $postID;
                if (!isset($_COOKIE[$cookie_name])) {
                    setPostViews($postID);
                    // $expire = 5;
                    $expire = strtotime('tomorrow') - time(); // Calculate seconds until end of day
                    setcookie($cookie_name, 'viewed', time() + $expire, '/', $_SERVER['HTTP_HOST']);
                }
            }
            $executed = true;
        }
    }
    
    add_action('wp', 'track_page_views');

    // Remove issues with prefetching adding extra views
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
    
}// End if(!empty($options['post_views'])).

?>