<?php  

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action('wp_ajax_my_action_2', 'like_follow_ajax_func');
add_action('wp_ajax_nopriv_my_action_2', 'like_follow_ajax_func');
function like_follow_ajax_func() {

    global $wpdb;
    $userid = $_POST['data']['userid'];
    $postid = $_POST['data']['postid'];
    $authorid = $_POST['data']['authorid'];
    $btntype = $_POST['data']['btntype'];


    if($btntype==="like"){
        $query_like = $wpdb->prepare("SELECT COUNT(*) AS cntpost FROM wp_ajdwp_like_follow WHERE post_id=".$postid." and user_id=".$userid);
        $result_like =$wpdb->get_results($query_like);
        if($result_like[0]->cntpost == 0){
            $insertquery_like = $wpdb->prepare("INSERT INTO wp_ajdwp_like_follow(user_id, post_id, like_stat) values(".$userid.", ".$postid.", 'like')");
            $wpdb->query($insertquery_like);
        }else {
            $updatequery = $wpdb->prepare("UPDATE wp_ajdwp_like_follow SET like_stat='like' where user_id=" . $userid . " and post_id=" . $postid);
            $wpdb->query($updatequery);
        }
    }

    if($btntype==="unlike"){
        $updatequery_unlike = $wpdb->prepare("UPDATE wp_ajdwp_like_follow SET like_stat='unlike' where user_id=" . $userid . " and post_id=" . $postid);
        $wpdb->query($updatequery_unlike);
    }


    if($btntype==="follow"){
        $query_follow = $wpdb->prepare("SELECT COUNT(*) AS cntpost FROM wp_ajdwp_like_follow WHERE author_id=".$authorid." and user_id=".$userid);
        $result_follow = $wpdb->get_results($query_follow);

        if($result_follow[0]->cntpost == 0){
            $insertquery_follow = $wpdb->prepare("INSERT INTO wp_ajdwp_like_follow(user_id, author_id, follow_stat) values(".$userid.", ".$authorid.", 'follow')");
            $wpdb->query($insertquery_follow);
        }else {
            $updatequery_follow = $wpdb->prepare("UPDATE wp_ajdwp_like_follow SET follow_stat='follow' where user_id=" . $userid . " and author_id=" . $authorid);
            $wpdb->query($updatequery_follow);
        }
    }

    if($btntype==="unfollow"){
        $updatequery_unfollow = $wpdb->prepare("UPDATE wp_ajdwp_like_follow SET follow_stat='unfollow' where user_id=" . $userid . " and author_id=" . $authorid);
        $updatequery_unfollow = "UPDATE wp_ajdwp_like_follow SET follow_stat='unfollow' where user_id=" . $userid . " and author_id=" . $authorid;
        $wpdb->query($updatequery_unfollow);
    }



    //count numbers of like and unlike in post
    $query_total_like = $wpdb->prepare("SELECT COUNT(*) AS cntLike FROM wp_ajdwp_like_follow WHERE like_stat = 'like' and post_id = ".$postid);
    $result_total_like = $wpdb->get_results($query_total_like);
    $totalLikes = $result_total_like[0]->cntLike;


    //count numbers of followers and haters of the author
    $query_total_follow = $wpdb->prepare("SELECT COUNT(*) AS cntfollow FROM wp_ajdwp_like_follow WHERE follow_stat = 'follow' and author_id = ".$authorid);
    $result_total_follow = $wpdb->get_results($query_total_follow);
    $totalfollow = $result_total_follow[0]->cntfollow;


    $return_arr = array(
        "likes"=>$totalLikes,
        "followers"=>$totalfollow
    );

    wp_send_json_success($return_arr);

}

?>