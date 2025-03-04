<?php  

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Register AJAX actions for logged-in users and non-logged-in users
add_action('wp_ajax_my_action_2', 'like_follow_ajax_func');
add_action('wp_ajax_nopriv_my_action_2', 'like_follow_ajax_func');

function like_follow_ajax_func() {
    global $wpdb;

    // Ensure `$_POST['data']` is set and not empty
    if (empty($_POST['data'])) {
        wp_send_json_error('Invalid data.');
        return;
    }

    // Sanitize and validate input data
    $data = $_POST['data'];
    $user_id = intval($data['userid']);
    $post_id = isset($data['postid']) ? intval($data['postid']) : 0;
    $author_id = isset($data['authorid']) ? intval($data['authorid']) : 0;
    $btn_type = sanitize_text_field($data['btntype']);

    // Validate the button type
    if (!in_array($btn_type, ['like', 'unlike', 'follow', 'unfollow'])) {
        wp_send_json_error('Invalid action type.');
        return;
    }

    // Perform action based on button type
    switch ($btn_type) {
        case 'like':
            handle_like_action($user_id, $post_id);
            break;
        case 'unlike':
            handle_unlike_action($user_id, $post_id);
            break;
        case 'follow':
            handle_follow_action($user_id, $author_id);
            break;
        case 'unfollow':
            handle_unfollow_action($user_id, $author_id);
            break;
    }

    // Fetch updated counts
    $total_likes = get_total_likes($post_id);
    $total_followers = get_total_followers($author_id);

    // Send response
    wp_send_json_success([
        "likes" => $total_likes,
        "followers" => $total_followers
    ]);
}

function handle_like_action($user_id, $post_id) {
    global $wpdb;

    // Ensure post ID is valid
    if (empty($post_id)) {
        return;
    }

    // Check if a like already exists
    $existing_like = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM wp_ajdwp_like_follow WHERE post_id = %d AND user_id = %d",
        $post_id, $user_id
    ));

    if ($existing_like == 0) {
        // Insert new like
        $wpdb->insert('wp_ajdwp_like_follow', [
            'user_id' => $user_id,
            'post_id' => $post_id,
            'like_stat' => 'like'
        ]);
    } else {
        // Update existing like
        $wpdb->update('wp_ajdwp_like_follow', [
            'like_stat' => 'like'
        ], [
            'user_id' => $user_id,
            'post_id' => $post_id
        ]);
    }
}

function handle_unlike_action($user_id, $post_id) {
    global $wpdb;

    // Ensure post ID is valid
    if (empty($post_id)) {
        return;
    }

    // Update like status to 'unlike'
    $wpdb->update('wp_ajdwp_like_follow', [
        'like_stat' => 'unlike'
    ], [
        'user_id' => $user_id,
        'post_id' => $post_id
    ]);
}

function handle_follow_action($user_id, $author_id) {
    global $wpdb;

    // Ensure author ID is valid
    if (empty($author_id)) {
        return;
    }

    // Check if a follow already exists
    $existing_follow = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM wp_ajdwp_like_follow WHERE author_id = %d AND user_id = %d",
        $author_id, $user_id
    ));

    if ($existing_follow == 0) {
        // Insert new follow
        $wpdb->insert('wp_ajdwp_like_follow', [
            'user_id' => $user_id,
            'author_id' => $author_id,
            'follow_stat' => 'follow'
        ]);
    } else {
        // Update existing follow
        $wpdb->update('wp_ajdwp_like_follow', [
            'follow_stat' => 'follow'
        ], [
            'user_id' => $user_id,
            'author_id' => $author_id
        ]);
    }
}

function handle_unfollow_action($user_id, $author_id) {
    global $wpdb;

    // Ensure author ID is valid
    if (empty($author_id)) {
        return;
    }

    // Update follow status to 'unfollow'
    $wpdb->update('wp_ajdwp_like_follow', [
        'follow_stat' => 'unfollow'
    ], [
        'user_id' => $user_id,
        'author_id' => $author_id
    ]);
}

function get_total_likes($post_id) {
    global $wpdb;

    // Ensure post ID is valid
    if (empty($post_id)) {
        return 0;
    }

    return $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM wp_ajdwp_like_follow WHERE like_stat = 'like' AND post_id = %d",
        $post_id
    ));
}

function get_total_followers($author_id) {
    global $wpdb;

    // Ensure author ID is valid
    if (empty($author_id)) {
        return 0;
    }

    return $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM wp_ajdwp_like_follow WHERE follow_stat = 'follow' AND author_id = %d",
        $author_id
    ));
}
?>