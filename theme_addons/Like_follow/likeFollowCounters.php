<?php
// if ( ! defined( 'ABSPATH' ) ) {
// 	exit; // Exit if accessed directly.
// }


    //count numbers of like and unlike in post
    global $wpdb;
    $postid     = get_the_ID();
    $userid     = get_current_user_id();
    $authorid   = get_the_author_meta('ID');



    $query_like = $wpdb->prepare("SELECT COUNT(*) AS cntLike FROM wp_ajdwp_like_follow WHERE like_stat = 'like' and post_id = ".$postid);
    $result_like = $wpdb->get_results($query_like);
    if(!empty($result_like)){
        $totalLikes = $result_like[0]->cntLike;
    }


    $query_follow = $wpdb->prepare("SELECT COUNT(*) AS cntfollow FROM wp_ajdwp_like_follow WHERE follow_stat = 'follow' and author_id = ".$authorid);
    $result_follow = $wpdb->get_results($query_follow);
    if(!empty($result_follow)){
        $totalfollow = $result_follow[0]->cntfollow;
    }


    $query_like_exsists = $wpdb->prepare("SELECT COUNT(*) AS cntLikeExsists FROM wp_ajdwp_like_follow WHERE post_id=".$postid." and user_id=".$userid." and like_stat = 'like'");
    $result_like_exsists = $wpdb->get_results($query_like_exsists);
    if(!empty($result_like_exsists)){
        $like_exsists = $result_like_exsists[0]->cntLikeExsists;
    }


    $query_follow_exsists = $wpdb->prepare("SELECT COUNT(*) AS cntFollowExsists FROM wp_ajdwp_like_follow WHERE author_id=".$authorid." and user_id=".$userid." and follow_stat = 'follow'");
    $result_follow_exsists = $wpdb->get_results($query_follow_exsists);
    if(!empty($result_follow_exsists)){
        $follow_exsists = $result_follow_exsists[0]->cntFollowExsists;
    }


?>