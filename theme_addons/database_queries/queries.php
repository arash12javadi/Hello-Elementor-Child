<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

    global $wpdb;

    //------------------------------------ $author_posts_total_likes -------------------------------------//

    $all_posts_by_this_author = $wpdb->get_results($wpdb->prepare("SELECT ID FROM `wp_posts` WHERE post_author =".get_the_author_meta('ID')));
    $author_posts_total_likes = 0;

    foreach($all_posts_by_this_author as $each_post_id){
        
        $count_each_post_id = $wpdb->prepare("SELECT COUNT(like_stat) AS total_likes_count FROM `wp_ajdwp_like_follow` WHERE post_id=".$each_post_id->ID." AND like_stat='like'");
        $my_sql_query_total =  $wpdb->get_results($count_each_post_id);
        // $total_likes_count = mysqli_fetch_array( $my_sql_query_total );
        $total_likes_ctn = $my_sql_query_total[0]->total_likes_count;
        $author_posts_total_likes +=  $total_likes_ctn;
    }
	
	//-------------------------------------author_followers------------------------------------//

    $author_id = get_the_author_meta('ID');
    $query_followees_id = $wpdb->prepare('SELECT `user_id` AS followee_id FROM `wp_ajdwp_like_follow` WHERE author_id = '.$author_id.' AND follow_stat = "follow"');
    $query_fetch = $wpdb->get_results($query_followees_id);
    
	//------------------------------------- User Disk Usage ------------------------------------//

    $total_disk_usage = 0; 
    $query_authors_posts = $wpdb->prepare("SELECT ID AS authors_posts FROM `wp_posts` WHERE `post_author` = ".get_current_user_id());
    $result_authors_posts = $wpdb->get_results($query_authors_posts);
    if(count($result_authors_posts) > 0){
        foreach($result_authors_posts as $row){
            $query_file_address = $wpdb->prepare("SELECT `meta_value` AS file_address FROM `wp_postmeta` WHERE `post_id` = ". $row->authors_posts ." AND `meta_key` = '_wp_attached_file'");
            $result_file_address = $wpdb->get_results($query_file_address);
            if(count($result_file_address) > 0){
                foreach($result_file_address as $row_fa){
                    // echo($row_fa['file_address']);
                    // echo "<br>";
                    $src= $row_fa->file_address;
                    $file_path= ABSPATH."wp-content/uploads/". $src;
                    $file_size= wp_filesize($file_path);
                    $total_disk_usage += $file_size;
                }
            }
        }
        $total_disk_usage_formatted = number_format($total_disk_usage, 0, ',', " '");
        // echo $total_disk_usage_formatted;
    }
?>