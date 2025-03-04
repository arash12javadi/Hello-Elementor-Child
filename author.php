<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

global $wpdb;
$authorid = get_the_author_meta('ID');

// Fetch total followers
$query_follow = $wpdb->prepare(
    "SELECT COUNT(*) AS cntfollow FROM wp_ajdwp_like_follow WHERE follow_stat = 'follow' AND author_id = %d",
    $authorid
);
$result_follow = $wpdb->get_results($query_follow);

$totalfollow = 0;
if (!empty($result_follow)) {
    $totalfollow = $result_follow[0]->cntfollow;
}

// Fetch total posts by author
$author_email = get_the_author_meta('user_email');
$user = get_user_by('email', $author_email);
$userId = $user->ID;
$post_count = count_user_posts($userId);

// Fetch total likes received by the author's posts
$all_posts_by_this_author = $wpdb->get_results($wpdb->prepare(
    "SELECT ID FROM wp_posts WHERE post_author = %d",
    $authorid
));
$author_posts_total_likes = 0;

foreach ($all_posts_by_this_author as $each_post_id) {
    $count_each_post_id = $wpdb->prepare(
        "SELECT COUNT(like_stat) AS total_likes_count FROM wp_ajdwp_like_follow WHERE post_id = %d AND like_stat = 'like'",
        $each_post_id->ID
    );
    $my_sql_query_total = $wpdb->get_results($count_each_post_id);
    $total_likes_ctn = $my_sql_query_total[0]->total_likes_count;
    $author_posts_total_likes += $total_likes_ctn;
}

// Fetch followers
$query_followees_id = $wpdb->prepare(
    "SELECT user_id AS followee_id FROM wp_ajdwp_like_follow WHERE author_id = %d AND follow_stat = 'follow'",
    $authorid
);
$query_fetch = $wpdb->get_results($query_followees_id);

?>

<?php get_header(); ?>

<?php
// Load author profile edit
ob_start();
include dirname(__FILE__) . "/theme_addons/user_profile/author_page_profile_edit.php";
$author_page_profile_edit = ob_get_contents();
ob_end_clean();
?>

<?php echo do_shortcode('[user_dashboard_float_button]'); ?>

<div class="content mt-5">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-6">
                <div class="author-page">
                    <div class="post-author text-left bg-dark bg-gradient text-light border border-5 rounded py-4 px-4">
                        <div class="follow_edit d-flex justify-content-between">
                            <!-- Follow Button -->
                            <div>
                                <?php if (is_user_logged_in()) {
                                    do_action('AJDWP_like_follow_social');
                                } ?>
                            </div>

                            <!-- User Profile Edit Info in Modal -->
                            <div>
                                <?php echo $author_page_profile_edit; ?>
                            </div>

                            <!-- User Display Name -->
                            <div class="d-flex align-items-center justify-content-center display-6 font-weight-bold">
                                <?php echo get_the_author(); ?>
                            </div>

                            <!-- User Avatar -->
                            <div class="d-flex align-items-center justify-content-center my-3">
                                <?php
                                $custom_avatar_url = get_user_meta($userId, 'custom_avatar_url', true);

                                if (!empty($custom_avatar_url)) {
                                    echo '<img src="' . esc_url($custom_avatar_url) . '" alt="Avatar" class="rounded-circle border shadow" style="width:200px; height:200px;">';
                                } else {
                                    echo get_avatar($author_email, 200, null, null, array('class' => array('rounded-circle border shadow')));
                                }
                                ?>
                            </div>

                            <!-- User First & Last Name -->
                            <div class="d-flex align-items-center justify-content-center font-weight-bold h6">
                                <?php echo get_the_author_meta('first_name') . " " . get_the_author_meta('last_name'); ?>
                            </div>

                            <!-- Post Count -->
                            <?php
                            $author_id = get_queried_object_id(); // This gets the author ID from the author archive page
                            // Retrieve user data
                            $user_data = get_userdata($author_id);
                            // Check if the user has the 'subscriber' role
                            if (!in_array('subscriber', (array) $user_data->roles)) {
                            ?>
                                <div class="d-flex align-items-center justify-content-center font-weight-bold h6">
                                    <?php echo "Total Posts: (" . $post_count . ")"; ?>
                                </div>
                            <?php } ?>

                            <?php
                            // Get the user description
                            $user_description = get_the_author_meta('description');

                            // Check if the description is not empty
                            if (!empty($user_description)) : ?>
                                <div class="d-flex align-items-center justify-content-start mt-4 pt-4 border-top border-light font-weight-bold h5 aaaaa">
                                    <?php echo "About me: "; ?>
                                </div>
                                <div class="text-left justify-content-start">
                                    <?php echo esc_html($user_description); ?>
                                </div>
                            <?php endif; ?>


                            <?php if ($showPhone === 'yes' && $showEmail === 'yes') { ?>

                                <div class="text-left justify-content-start mt-4 pt-4 border-top border-light"></div>

                                <!-- Email Address -->
                                <div class="user_profile_email_div">
                                    <?php if ($showEmail === 'yes') { ?>
                                        <div class="font-weight-bold h5">
                                            <?php echo "Email me: "; ?>
                                        </div>
                                        <div class="text-left justify-content-start">
                                            <p>
                                                <a href="mailto:<?php echo esc_attr(get_the_author_meta('user_email')); ?>">
                                                    <?php echo esc_html(get_the_author_meta('user_email')); ?>
                                                </a>
                                            </p>
                                        </div>
                                    <?php } ?>
                                </div>

                                <!-- Phone Number -->
                                <div class="user_profile_number_div">
                                    <?php if ($showPhone === 'yes') { ?>
                                        <div class="font-weight-bold h5">
                                            <?php echo "Call me: "; ?>
                                        </div>
                                        <div>
                                            <a href="tel:<?php echo esc_attr(get_the_author_meta('phone_number')); ?>">
                                                <?php echo esc_html(get_the_author_meta('phone_number')); ?>
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <?php if (has_social_media_links()) { ?>
                                <div class="text-left justify-content-start mt-4 pt-4 border-top border-light"></div>
                                <!-- Social Media -->
                                <?php do_action("User_Social_Media"); ?>
                            <?php } ?>
                            <!-- Total Follow, Likes, and Profile Views -->
                            <?php if (!is_page()) { ?>
                                <div class="like_follow_social border-top mt-4 py-4 d-flex justify-content-center align-items-center">
                                    <?php
                                    $options = get_option('AJDWP_theme_options');
                                    if (!empty($options['like_follow_system'])) {
                                    ?>
                                        <?php echo "Total Followers: &nbsp" . esc_html($totalfollow); ?>
                                        <div class="vr mx-4"></div>
                                        Like Score: <?php echo esc_html($author_posts_total_likes); ?>

                                    <?php } //end if(!empty($options['like_follow_system'])){ 
                                    ?>
                                    <?php
                                    $options = get_option('AJDWP_theme_options');
                                    if (!empty($options['post_views'])) {
                                    ?>
                                        <div class="vr mx-4" <?php $options = get_option('AJDWP_theme_options');
                                                                if (empty($options['like_follow_system'])) {
                                                                    echo 'style="display:none;"';
                                                                } ?>></div>
                                        Profile visit: <?php echo esc_html(get_author_views(get_queried_object_id())); ?>
                                    <?php } //end if (!empty($options['post_views'])) {  
                                    ?>
                                </div>

                            <?php } //end if (!is_page()) { 
                            ?>

                            <?php
                            $options = get_option('AJDWP_theme_options');
                            if (!empty($options['like_follow_system'])) {
                            ?>

                                <!-- Followers -->
                                <div class="d-block border-top pt-4">
                                    <p>Followers:</p>
                                </div>

                                <div class="authors_followers">
                                    <?php
                                    if (count($query_fetch) > 0) {
                                        foreach ($query_fetch as $row) {
                                            if (!empty(get_userdata($row->followee_id))) {
                                    ?>
                                                <div class="m-1 border border-1 rounded d-inline-block">
                                                    <a href="<?php echo esc_url(get_author_posts_url($row->followee_id)); ?>">
                                                        <div class="g-0 d-flex justify-content-start align-items-center">
                                                            <div>
                                                                <?php
                                                                $like_follow_custom_avatar_url = get_user_meta($row->followee_id, 'custom_avatar_url', true);

                                                                if (!empty($like_follow_custom_avatar_url)) {
                                                                    echo '<img src="' . esc_url($like_follow_custom_avatar_url) . '" alt="Avatar" class="img-fluid rounded-start" style="width:50px; height:50px;">';
                                                                } else {
                                                                    echo get_avatar($row->followee_id, 50, null, null, array('class' => array('img-fluid rounded-start')));
                                                                }
                                                                ?>
                                                            </div>
                                                            <div class="px-2">
                                                                <span class="card-title"><?php echo esc_html(get_userdata($row->followee_id)->display_name); ?></span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                    <?php
                                            }
                                        }
                                    } else {
                                        echo "No followers yet.";
                                    }
                                    ?>
                                </div>
                            <?php } ?>

                        </div>
                    </div>
                </div>

                <!-- Author's Posts Archive -->
                <div class="<?php echo (count_user_posts($authorid) > 0) ? 'col-lg-6' : 'd-none'; ?>">
                    <section id="post-section" class="post-section">
                        <div class="container">
                            <div class="row">
                                <?php if (have_posts()) : ?>
                                    <?php while (have_posts()) : the_post(); ?>
                                        <article id="post-<?php the_ID(); ?>" <?php post_class('post-items shadow rounded'); ?>>
                                            <div class="row mb-4 mt-4">
                                                <div class="post-date-author col-lg-4">
                                                    <div class="d-flex align-items-center justify-content-start">
                                                        <span class="span-postdate">Post Date: &nbsp;</span>
                                                        <span class="post-date">
                                                            <a href="<?php echo esc_url(get_month_link(get_post_time('Y'), get_post_time('m'))); ?>">
                                                                <span><?php echo esc_html(get_the_date('j')); ?></span><?php echo esc_html(get_the_date('M, Y')); ?>
                                                            </a>
                                                        </span>
                                                    </div>
                                                    <?php if (has_post_thumbnail()) { ?>
                                                        <figure class="post-thumbnail">
                                                            <div class="featured-image d-flex align-items-center justify-content-center mt-3">
                                                                <a href="<?php echo esc_url(get_permalink()); ?>" class="post-hover">
                                                                    <?php the_post_thumbnail(is_single() ? 'medium_large' : 'thumbnail'); ?>
                                                                </a>
                                                            </div>
                                                        </figure>
                                                    <?php } ?>
                                                </div>
                                                <div class="post-content-excerpt col-lg-8">
                                                    <?php the_title(sprintf('<h3 class="post-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h3>'); ?>
                                                    <div class="post-excerpt">
                                                        <?php the_excerpt(); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </article>
                                    <?php endwhile; ?>
                                    <div class="d-flex align-items-center justify-content-center mt-4">
                                        <?php
                                        // Previous/next page navigation.
                                        the_posts_pagination(array(
                                            'prev_text' => '<i class="fa fa-angle-double-left"></i>',
                                            'next_text' => '<i class="fa fa-angle-double-right"></i>',
                                        ));
                                        ?>
                                    </div>
                                <?php else : ?>
                                    <?php get_template_part('theme_addons/content/content', 'none'); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>