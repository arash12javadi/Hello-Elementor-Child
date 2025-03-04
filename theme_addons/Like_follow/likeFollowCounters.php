<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

global $wpdb;
$post_id = get_the_ID();
$user_id = get_current_user_id();
$author_id = get_the_author_meta('ID');

$totalLikes = $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(*) FROM wp_ajdwp_like_follow WHERE like_stat = 'like' AND post_id = %d",
    $post_id
));

$totalfollow = $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(*) FROM wp_ajdwp_like_follow WHERE follow_stat = 'follow' AND author_id = %d",
    $author_id
));

$like_exsists = $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(*) FROM wp_ajdwp_like_follow WHERE post_id = %d AND user_id = %d AND like_stat = 'like'",
    $post_id, $user_id
));

$follow_exsists = $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(*) FROM wp_ajdwp_like_follow WHERE author_id = %d AND user_id = %d AND follow_stat = 'follow'",
    $author_id, $user_id
));
?>
