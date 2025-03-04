<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Template part for displaying page content.
 */

do_action('AJDWP_content_page_top');

// Include your Like/Follow counters
include dirname(__FILE__, 2) . '/Like_follow/likeFollowCounters.php';

// Load options once
$options = get_option('AJDWP_theme_options');
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post-items shadow rounded'); ?>>
    <div class="row">
        <div class="order-2">
            <?php
            /**
             * 1) TITLE FIRST (for single or page) =====================================
             *    Also handle the scenario for archives (not single) below.
             */
            //_____________________________________ order-2 let this element be shown after order-1 _____________________________________//
            ?>
            <?php if (is_single() || is_page()) : ?>
                <?php if (!empty($options['show_page_title'])) : ?>
                    <!-- H1 Title -->
                    <h1 class="text-uppercase h1 AJDWP_page_title text-center mt-4">
                        <?php echo esc_html(get_the_title()); ?>
                    </h1>
                <?php endif; ?>
            <?php else : ?>
                <!-- If NOT single (e.g., on an archive), use an <h2> with link -->
                <h2 class="text-uppercase h2 pt-2">
                    <a aria-label="The post permalink" href="<?php echo esc_url(get_permalink()); ?>">
                        <?php the_title(); ?>
                    </a>
                </h2>
            <?php endif; ?>


            <?php
            /**
             * 2) FEATURED IMAGE AND ALT TEXT ==========================================
             *    - We move this block directly after the title.
             *    - If not a page, it links to the single post. 
             */
            ?>
            <div class="row">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail <?php echo (is_single() || is_page()) ? 'col-lg-12 py-3' : 'col-lg-3'; ?>">
                        <?php if (!is_page()) : ?>
                            <div class="featured-image d-flex align-items-center justify-content-center">
                                <a aria-label="The post featured image link" href="<?php echo esc_url(get_permalink()); ?>" class="post-hover">
                                    <?php the_post_thumbnail(is_single() ? 'medium_large' : 'thumbnail'); ?>
                                </a>
                            </div>
                        <?php else : ?>
                            <!-- On a Page, just show the image without linking -->
                            <div class="featured-image d-flex align-items-center justify-content-center">
                                <?php the_post_thumbnail('medium_large'); ?>
                            </div>
                        <?php endif; ?>

                        <?php
                        // Display the image alt text under the image, if available
                        $thumb_id = get_post_thumbnail_id();
                        if ($thumb_id) {
                            $alt_text = get_post_meta($thumb_id, '_wp_attachment_image_alt', true);
                            if (!empty($alt_text)) {
                                echo '<p class="text-center mt-2 small">' . esc_html($alt_text) . '</p>';
                            }
                        }
                        ?>
                    </div>

                <?php endif; ?>
                <?php
                /**
                 * 3) MAIN CONTENT OR EXCERPT =============================================
                 *    - If single or page, show the_content().
                 *    - If not single, show excerpt.
                 */
                ?>
                <div class="post-content-excerpt <?php echo (is_single() || is_page()) ? 'col-lg-12' : (has_post_thumbnail() ? 'col-lg-9' : 'col-lg-12'); ?>">
                    <?php if (is_single() || is_page()) : ?>
                        <div class="post-content text-justify px-lg-4">
                            <?php the_content(); ?>
                        </div>
                    <?php else : ?>
                        <div class="post-excerpt text-justify px-lg-4">
                            <?php the_excerpt(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php
            /**
             * 5) LIKE / FOLLOW / VIEWS / SOCIAL MEDIA =================================
             *    This block remains as you had it, but now it’s near the end.
             */
            ?>
            <div class="row border-top py-4 d-flex align-items-center">
                <div class="col-sm-8 justify-content-start d-inline-flex">

                    <!-- Like Buttons / Social for Non-Pages -->
                    <?php if (!is_page()) : ?>
                        <div class="like_buttons d-flex justify-content-start align-items-center">
                            <?php if (is_user_logged_in()) : ?>
                                <?php do_action('AJDWP_like_follow_social'); ?>
                            <?php else : ?>
                                <?php echo "Total likes: " . esc_html($totalLikes); ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Author Avatar + Follow Info (Non-Page) -->
                    <?php if (!is_page()) : ?>
                        <div class="d-flex align-items-center p-1 mx-4 border border-1 rounded d-inline-flex position-relative <?php echo !empty($follow_exsists) ? 'border-primary' : ''; ?>" style="width:max-content;">
                            <a aria-label="Link to this post authur page" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                <div class="g-0 d-flex justify-content-start align-items-center">
                                    <div class="">
                                        <?php
                                        $like_follow_custom_avatar_url = get_user_meta(get_the_author_meta('ID'), 'custom_avatar_url', true);
                                        if (!empty($like_follow_custom_avatar_url)) {
                                            echo '<img src="' . esc_url($like_follow_custom_avatar_url) . '" alt="Avatar" class="img-fluid rounded" style="width:50px; height:50px;">';
                                        } else {
                                            echo get_avatar(get_the_author_meta('ID'), 50, null, null, ['class' => 'img-fluid rounded']);
                                        }
                                        ?>
                                    </div>
                                    <div class="px-2 <?php echo !empty($follow_exsists) ? 'text-primary' : ''; ?>">
                                        <span><?php echo esc_html(get_userdata(get_the_author_meta('ID'))->display_name); ?></span>
                                    </div>
                                    <div class="post_page_author_total_follow position-absolute top-0 start-100 translate-middle badge rounded-pill <?php echo !empty($follow_exsists) ? 'bg-primary' : 'bg-secondary'; ?>">
                                        <?php echo esc_html($totalfollow); ?>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>

                    <!-- Page Publish Date if is_page -->
                    <?php if (is_page() && !empty($options['page_publish_date'])) : ?>
                        <div class="col-8">
                            <div class="px-lg-4 post-date-author text-start text-uppercase">
                                <span class="span-postdate">Created Date: </span>
                                <span class="post-date">
                                    <a aria-label="Link to the same released date posts" href="<?php echo esc_url(get_month_link(get_post_time('Y'), get_post_time('m'))); ?>">
                                        <span><?php echo esc_html(get_the_date('j ')); ?></span>
                                        <?php echo esc_html(get_the_date('M, Y')); ?>
                                    </a>
                                </span>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>

                <!-- Post Views (if enabled) -->
                <?php if (!empty($options['post_views'])) : ?>
                    <div class="col-sm-4 p-4 text-end AJDWP-theme-view-counter">
                        <?php echo esc_html(getPostViews(get_the_ID())); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        /**
         * 4) DATE & AUTHOR (if not a page) ========================================
         *    Moved below the content for your desired structure.
         */
        //_____________________________________ order-1 let this element be shown on the top of the posts _____________________________________//
        ?>
        <?php if (!is_page()) : ?>
            <div class="py-4 px-lg-4 border-bottom order-1">
                <div class="post-date-author text-start text-uppercase">
                    <?php if (!empty($options['post_publish_date'])) : ?>
                        <span class="span-postdate">Post Date: </span>
                        <span class="post-date">
                            <a aria-label="Link to the same released date posts" href="<?php echo esc_url(get_month_link(get_post_time('Y'), get_post_time('m'))); ?>">
                                <span><?php echo esc_html(get_the_date('j')); ?>/</span><?php echo esc_html(get_the_date("M/Y")); ?>
                            </a>
                        </span>
                        &nbsp;&nbsp;||&nbsp;&nbsp;
                    <?php endif; ?>

                    <span class="span-writtenby">Written By: </span>
                    <span class="post-author">
                        <a aria-label="Link to this post authur page" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                            <?php echo esc_html(get_the_author()); ?>
                        </a>
                    </span>
                </div>
            </div>
        <?php endif; ?>
    </div>
</article>

<?php do_action('AJDWP_content_page_bottom'); ?>
